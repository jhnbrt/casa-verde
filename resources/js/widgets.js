// AI chat bubble and counter-offer popup (settings come from the admin, see layouts/widgets.blade.php).

document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById('cvw-config');
    if (!element) return;

    let config;

    try {
        config = JSON.parse(element.textContent);
    } catch {
        return;
    }

    if (config.offer) initOffer(config);
    if (config.chat) initChat(config);
});

const store = {
    get(area, key) { try { return area.getItem(key); } catch { return null; } },
    set(area, key, value) { try { area.setItem(key, value); } catch { /* private mode: ignore */ } },
};

/* ---------------------------------------------------------------- counter offer */

function initOffer(config) {
    const dialog = document.querySelector('[data-cvw-offer]');
    if (!dialog) return;

    const rules = config.rules;
    const SECONDS = 'cvw_seconds';
    const SEEN = 'cvw_offer_seen';

    let seconds = Number(store.get(sessionStorage, SECONDS) || 0);
    let shown = false;

    // Time on the website counts only while the page is on screen, and carries across pages of one visit.
    setInterval(() => {
        if (document.visibilityState === 'visible') {
            seconds += 1;
            store.set(sessionStorage, SECONDS, String(seconds));
        }
    }, 1000);

    const seenRecently = () => {
        if (rules.days <= 0) return store.get(sessionStorage, SEEN) === '1';

        const at = Number(store.get(localStorage, SEEN) || 0);
        return at > 0 && Date.now() - at < rules.days * 86400000;
    };

    const canShow = () => !shown && !dialog.open && seconds >= rules.seconds && !seenRecently();

    const show = () => {
        shown = true;
        store.set(localStorage, SEEN, String(Date.now()));
        store.set(sessionStorage, SEEN, '1');
        dialog.showModal();
    };

    // A visitor whose mouse leaves through the top of the window is heading for the tabs, the address bar or the close button.
    if (rules.exit) {
        document.addEventListener('mouseout', (event) => {
            if (!event.relatedTarget && event.clientY <= 0 && canShow()) show();
        });
    }

    // Switching to another tab or app: the offer is waiting when they come back (also covers phones).
    if (rules.returning) {
        let wasAway = false;

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                wasAway = seconds >= rules.seconds && !shown && !seenRecently();
            } else if (wasAway) {
                wasAway = false;
                setTimeout(() => { if (canShow()) show(); }, 600);
            }
        });
    }

    dialog.querySelectorAll('[data-cvw-offer-close]').forEach((button) => button.addEventListener('click', () => dialog.close()));
    dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
    dialog.querySelector('[data-cvw-offer-cta]')?.addEventListener('click', () => dialog.close());

    const copy = dialog.querySelector('[data-cvw-copy]');
    const code = dialog.querySelector('[data-cvw-code]');

    copy?.addEventListener('click', async () => {
        const text = code?.textContent.trim() || '';

        try {
            await navigator.clipboard.writeText(text);
        } catch {
            const range = document.createRange();
            range.selectNodeContents(code);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            document.execCommand('copy');
        }

        copy.textContent = 'Copied!';
        setTimeout(() => { copy.textContent = 'Copy code'; }, 2000);
    });

    // Preview for the owner: add ?preview-offer to any page address to see the popup straight away.
    if (new URLSearchParams(window.location.search).has('preview-offer')) {
        setTimeout(() => { shown = true; dialog.showModal(); }, 700);
    }
}

/* ---------------------------------------------------------------- AI chat */

function initChat(config) {
    const root = document.querySelector('[data-cvw-chat]');
    if (!root) return;

    const panel = root.querySelector('.cvw-panel');
    const toggle = root.querySelector('[data-cvw-chat-toggle]');
    const log = root.querySelector('[data-cvw-log]');
    const form = root.querySelector('[data-cvw-form]');
    const input = root.querySelector('[data-cvw-input]');
    const chips = root.querySelector('[data-cvw-chips]');
    const send = form.querySelector('button');
    const KEY = 'cvw_chat';

    let history = [];
    let busy = false;

    try { history = JSON.parse(store.get(sessionStorage, KEY) || '[]'); } catch { history = []; }

    const save = () => store.set(sessionStorage, KEY, JSON.stringify(history.slice(-30)));

    const clean = (text) => text
        .replace(/\*\*(.+?)\*\*/g, '$1')
        .replace(/^#{1,6}\s*/gm, '')
        .replace(/^\s*[-*]\s+/gm, '• ');

    // Web addresses and site paths in an answer become links; everything else stays plain text.
    const fill = (node, text) => {
        text.split(/(\s+)/).forEach((token) => {
            const match = token.match(/^(https?:\/\/[^\s]+?|\/#?[a-z][\w\-/]*)([.,!?)]*)$/i);

            if (!match) {
                node.append(token);
                return;
            }

            const link = document.createElement('a');
            link.href = match[1];
            link.textContent = match[1];

            if (/^https?:/i.test(match[1])) {
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
            }

            node.append(link, match[2]);
        });
    };

    const add = (role, text) => {
        const bubble = document.createElement('div');
        bubble.className = 'cvw-msg cvw-msg--' + role;
        fill(bubble, role === 'user' ? text : clean(text));
        log.append(bubble);
        log.scrollTop = log.scrollHeight;
        return bubble;
    };

    const greet = () => add('bot', config.chat.greeting);

    history.forEach((message) => add(message.role === 'user' ? 'user' : 'bot', message.content));
    if (history.length === 0) greet();
    else if (chips) chips.hidden = true;

    const open = (state) => {
        root.classList.toggle('is-open', state);
        panel.hidden = !state;
        toggle.setAttribute('aria-expanded', String(state));

        if (state) {
            log.scrollTop = log.scrollHeight;
            setTimeout(() => input.focus(), 50);
        } else {
            toggle.focus();
        }
    };

    toggle.addEventListener('click', () => open(true));
    root.querySelector('[data-cvw-chat-close]').addEventListener('click', () => open(false));
    root.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !panel.hidden) open(false); });

    const ask = async (text) => {
        if (busy || !text.trim()) return;

        busy = true;
        send.disabled = true;
        if (chips) chips.hidden = true;

        history.push({ role: 'user', content: text });
        add('user', text);
        save();

        const typing = document.createElement('div');
        typing.className = 'cvw-msg cvw-msg--bot';
        typing.innerHTML = '<span class="cvw-typing" aria-label="Typing"><i></i><i></i><i></i></span>';
        log.append(typing);
        log.scrollTop = log.scrollHeight;

        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), 40000);

        try {
            const response = await fetch(config.chat.url, {
                method: 'POST',
                signal: controller.signal,
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': config.csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ messages: history.slice(-14) }),
            });

            const data = await response.json().catch(() => ({}));
            typing.remove();

            if (response.ok && data.reply) {
                history.push({ role: 'assistant', content: data.reply });
                add('bot', data.reply);
            } else {
                // The question stays in the conversation only when it was answered.
                history.pop();
                add('error', data.message || 'Sorry, something went wrong. Please try again.');
            }
        } catch {
            typing.remove();
            history.pop();
            add('error', 'Sorry, I could not reach the server. Please check your connection and try again.');
        } finally {
            clearTimeout(timer);
            busy = false;
            send.disabled = false;
            save();
            input.focus();
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const text = input.value.trim();
        input.value = '';
        ask(text);
    });

    chips?.querySelectorAll('[data-cvw-chip]').forEach((chip) => chip.addEventListener('click', () => ask(chip.textContent.trim())));
}
