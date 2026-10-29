// Booking page: photo carousels, full-screen viewer, form toggle, guest steppers and the live price.

const peso = (n) => '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
const iso = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
const parse = (s) => (s ? new Date(`${s}T00:00:00`) : null);

document.addEventListener('DOMContentLoaded', () => {
    const lightbox = initLightbox(document.querySelector('[data-bk-lightbox]'));

    document.querySelectorAll('[data-bk-villa]').forEach((villa) => {
        initGallery(villa, lightbox);
        initToggle(villa);

        const form = villa.querySelector('[data-bk-form]');
        if (form) {
            initSteppers(form);
            initQuote(form);
            initSubmit(form);
        }

        if (villa.dataset.open === 'true') {
            const alert = villa.querySelector('[data-bk-alert]');
            setTimeout(() => (alert || villa).scrollIntoView({ behavior: 'smooth', block: alert ? 'center' : 'start' }), 120);
            alert?.focus({ preventScroll: true });
        }
    });
});

function initGallery(villa, lightbox) {
    const gallery = villa.querySelector('[data-bk-gallery]');
    if (!gallery) return;

    const imgs = [...gallery.querySelectorAll('[data-bk-img]')];

    gallery.querySelectorAll('[data-bk-open]').forEach((el) => {
        el.addEventListener('click', () => lightbox?.open(imgs, Number(el.dataset.bkOpen) || 0));
    });
}

function initLightbox(dialog) {
    if (!dialog) return null;
    const img = dialog.querySelector('[data-bk-lb-img]');
    const count = dialog.querySelector('[data-bk-lb-count]');
    let list = [];
    let i = 0;

    const show = (n) => {
        i = (n + list.length) % list.length;
        img.src = list[i].src;
        img.alt = list[i].alt;
        count.textContent = `${i + 1} / ${list.length}`;
    };

    dialog.querySelector('[data-bk-lb-close]').addEventListener('click', () => dialog.close());
    dialog.querySelector('[data-bk-lb-prev]').addEventListener('click', () => show(i - 1));
    dialog.querySelector('[data-bk-lb-next]').addEventListener('click', () => show(i + 1));
    dialog.addEventListener('click', (e) => { if (e.target === dialog) dialog.close(); });
    dialog.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') show(i - 1);
        if (e.key === 'ArrowRight') show(i + 1);
    });

    return { open(slides, start) { list = slides; show(start); dialog.showModal(); } };
}

function initToggle(villa) {
    const button = villa.querySelector('[data-bk-toggle]');
    const panel = villa.querySelector('.bk-panel');
    if (!button || !panel) return;

    button.addEventListener('click', () => {
        const open = panel.hidden;
        panel.hidden = !open;
        button.setAttribute('aria-expanded', String(open));
        button.querySelector('[data-bk-toggle-text]').textContent = open ? button.dataset.closeText : button.dataset.openText;
        if (open) {
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            panel.querySelector('input[type="date"]')?.focus({ preventScroll: true });
        }
    });
}

function initSteppers(form) {
    form.querySelectorAll('[data-bk-stepper]').forEach((box) => {
        const input = box.querySelector('input');
        const min = Number(box.dataset.min);
        const max = Number(box.dataset.max);
        const minus = box.querySelector('[data-step="-1"]');
        const plus = box.querySelector('[data-step="1"]');

        const sync = () => {
            const n = Math.min(max, Math.max(min, parseInt(input.value, 10) || min));
            input.value = n;
            minus.disabled = n <= min;
            plus.disabled = n >= max;
        };

        box.addEventListener('click', (e) => {
            const step = e.target.closest('[data-step]');
            if (!step) return;
            input.value = (parseInt(input.value, 10) || min) + Number(step.dataset.step);
            sync();
        });
        input.addEventListener('change', sync);
        sync();
    });
}

// The total shown here is a preview. The server works out the real price when the request is sent.
function initQuote(form) {
    const rates = JSON.parse(form.dataset.rates);
    const inEl = form.querySelector('[data-bk-in]');
    const outEl = form.querySelector('[data-bk-out]');
    const hint = form.querySelector('[data-bk-nights-hint]');
    const q = (s) => form.querySelector(s);

    const render = () => {
        const a = parse(inEl.value);
        const b = parse(outEl.value);

        // Check-out can never be on or before check-in.
        if (a) {
            const next = new Date(a); next.setDate(next.getDate() + 1);
            outEl.min = iso(next);
            if (b && b <= a) { outEl.value = ''; }
        }

        const out = parse(outEl.value);
        if (!a || !out) {
            q('[data-bk-lines]').hidden = true;
            q('[data-bk-empty]').hidden = false;
            hint.textContent = 'Select your dates to see the price.';
            hint.style.color = '';
            return;
        }

        let nights = 0, total = 0, regular = 0, promoNights = 0;
        for (const d = new Date(a); d < out; d.setDate(d.getDate() + 1)) {
            nights++;
            const day = iso(d);
            const onPromo = rates.promo && day >= rates.from && day <= rates.to;
            total += onPromo ? rates.promo : rates.regular;
            regular += rates.regular;
            if (onPromo) promoNights++;
        }

        if (nights > rates.maxNights) {
            hint.textContent = `Online requests are limited to ${rates.maxNights} nights. Please contact us for longer stays.`;
            hint.style.color = '#a63d2f';
            q('[data-bk-lines]').hidden = true;
            q('[data-bk-empty]').hidden = false;
            return;
        }

        hint.style.color = '';
        hint.textContent = `${nights} ${nights === 1 ? 'night' : 'nights'}${promoNights ? ` · ${promoNights} at the promo rate` : ''}`;
        q('[data-bk-empty]').hidden = true;
        q('[data-bk-lines]').hidden = false;
        q('[data-bk-line-label]').textContent = `${nights} ${nights === 1 ? 'night' : 'nights'}`;
        q('[data-bk-line-amount]').textContent = peso(regular);
        q('[data-bk-save-row]').hidden = regular - total <= 0;
        q('[data-bk-save]').textContent = '−' + peso(regular - total);
        q('[data-bk-total]').textContent = peso(total);
    };

    inEl.addEventListener('change', render);
    outEl.addEventListener('change', render);
    render();
}

function initSubmit(form) {
    const submit = form.querySelector('[data-bk-submit]');

    form.addEventListener('submit', (e) => {
        // Browser checks give friendly messages; novalidate lets us mark every bad field at once.
        let firstBad = null;
        form.querySelectorAll('input:not([type="hidden"]), textarea').forEach((el) => {
            if (el.closest('.bk-hp')) return;
            const bad = !el.checkValidity();
            el.classList.toggle('is-invalid', bad);
            if (bad && !firstBad) firstBad = el;
        });

        if (firstBad) {
            e.preventDefault();
            firstBad.focus();
            firstBad.reportValidity?.();
            return;
        }

        submit.disabled = true;
        submit.classList.add('is-busy');
    });

    form.addEventListener('input', (e) => e.target.classList?.remove('is-invalid'));
}
