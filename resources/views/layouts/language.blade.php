{{--
    Language menu for the header. Visitors pick a language (or "Automatic": by their location) and the
    page is translated by Google Website Translator, edited admin content included. The translator is
    only loaded for a visitor who is not reading English. Everything is inline so it works without a rebuild.
    Which languages are offered is set in the admin (Languages).
--}}
@if(! empty($cvLang['show']))

    @php
        // Built here, not inside @json(...): Blade's @json cannot take an expression that contains commas.
        $cvLangConfig = [
            'codes' => array_keys($cvLang['languages']),
            'short' => array_map(fn ($language) => $language['short'], $cvLang['languages']),
            'google' => $cvLang['google'],
            'autoOn' => (bool) $cvLang['autoOn'],
            'auto' => $cvLang['auto'],
        ];
    @endphp

    <div class="cv-lang notranslate" translate="no" data-cv-lang>
        <button type="button" class="cv-lang__btn" aria-haspopup="listbox" aria-expanded="false" aria-label="Choose language" data-cv-lang-btn>
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3.2 3 14.8 0 18M12 3c-3 3.2-3 14.8 0 18"/></svg>
            <span data-cv-lang-label>EN</span>
            <svg class="cv-lang__chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <ul class="cv-lang__menu" role="listbox" aria-label="Language" hidden data-cv-lang-menu>
            @if($cvLang['autoOn'])
                <li role="presentation">
                    <button type="button" role="option" class="cv-lang__opt cv-lang__opt--auto" data-cv-lang-pick="auto">
                        <span>Automatic</span>
                        <small>Based on where you are</small>
                    </button>
                </li>
            @endif
            @foreach($cvLang['languages'] as $code => $language)
                <li role="presentation">
                    <button type="button" role="option" class="cv-lang__opt" lang="{{ $code }}" data-cv-lang-pick="{{ $code }}">
                        <span>{{ $language['native'] }}</span>
                        @if($language['native'] !== $language['name'])<small>{{ $language['name'] }}</small>@endif
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div id="google_translate_element" aria-hidden="true"></div>

    <style>
        .cv-lang { position: relative; flex: none; margin-right: 8px; font-family: Arial, Helvetica, sans-serif; }
        .cv-lang__btn {
            display: inline-flex; align-items: center; gap: 5px; height: 34px; padding: 0 9px;
            border: 1px solid rgba(207, 161, 93, .5); border-radius: 30px; background: transparent;
            color: #cfa15d; font: 600 11px Arial, Helvetica, sans-serif; letter-spacing: .03em; cursor: pointer;
            transition: background .2s, color .2s, border-color .2s;
        }
        .cv-lang__btn:hover, .cv-lang__btn[aria-expanded="true"] { background: rgba(207, 161, 93, .14); color: #fff; border-color: #cfa15d; }
        .cv-lang__btn svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 1.6; stroke-linecap: round; stroke-linejoin: round; }
        .cv-lang__chev { width: 10px !important; height: 10px !important; transition: transform .2s; }
        .cv-lang__btn[aria-expanded="true"] .cv-lang__chev { transform: rotate(180deg); }

        .cv-lang__menu {
            position: absolute; top: calc(100% + 10px); right: 0; z-index: 400;
            width: 240px; max-height: min(70vh, 460px); overflow-y: auto; margin: 0; padding: 6px; list-style: none;
            border: 1px solid rgba(207, 161, 93, .45); border-radius: 14px; background: rgba(6, 33, 27, .98);
            box-shadow: 0 22px 44px -18px rgba(0, 0, 0, .7); scrollbar-width: thin;
        }
        .cv-lang__menu[hidden] { display: none; }
        .cv-lang__opt {
            display: flex; align-items: baseline; justify-content: space-between; gap: 10px; width: 100%;
            padding: 10px 12px; border: 0; border-radius: 9px; background: transparent; color: #f1e6cf;
            font: 500 14px Arial, Helvetica, sans-serif; text-align: left; cursor: pointer;
        }
        .cv-lang__opt small { color: #bba982; font-size: 11px; }
        .cv-lang__opt:hover, .cv-lang__opt:focus-visible { background: rgba(207, 161, 93, .18); outline: 0; }
        .cv-lang__opt[aria-selected="true"] { background: rgba(207, 161, 93, .26); color: #fff; }
        .cv-lang__opt--auto { border-bottom: 1px solid rgba(207, 161, 93, .25); border-radius: 9px 9px 0 0; margin-bottom: 4px; }

        /* Phones and tablets: the menu sits beside the hamburger button */
        @media (max-width: 1180px) {
            .cv-lang { order: 2; margin-left: auto; margin-right: 10px; }
            .navbar .nav-toggle { order: 3; margin-left: 0 !important; }
        }
        @media (max-width: 560px) {
            .cv-lang__btn { height: 40px; padding: 0 10px; }
            .cv-lang__menu { position: fixed; top: 82px; left: 12px; right: 12px; width: auto; }
        }

        /* Translated words are often longer than the English ones (French, German, Filipino, Russian...).
           On a wide screen the menu stays on ONE line and the text shrinks to fit (the script below measures it).
           When even the smallest readable size does not fit, the header switches to the same menu button phones use. */
        @media (min-width: 1181px) {
            html:not([lang="en"]) .navbar .nav-links { min-width: 0; gap: clamp(10px, 1.3vw, 28px); margin-left: clamp(14px, 2vw, 45px); }
            html:not([lang="en"]) .navbar .nav-links a { font-size: var(--cv-nav-fs, clamp(11px, .85vw, 15px)); white-space: nowrap; letter-spacing: 0; }
            html:not([lang="en"]) .navbar .nav-book-desktop { flex: none; font-size: var(--cv-nav-fs, clamp(10px, .75vw, 13px)); white-space: nowrap; }
        }

        /* When the header menu does not fit on one line (any language, English too) the script measures it and
           steps in, never switching to a menu button on a wide screen:
           1. "fit":  tighter spacing and a smaller text size, still on one line;
           2. "wrap": if that is still too wide, labels may use two short lines at a small size. */
        @media (min-width: 1181px) {
            html.cv-nav-fit .navbar .nav-links { gap: clamp(8px, 1.1vw, 22px); }
            html.cv-nav-fit .navbar .nav-links a:not(.book-button) { font-size: var(--cv-nav-fs); letter-spacing: .03em; white-space: nowrap; }
            html.cv-nav-fit .navbar .nav-book-desktop { font-size: max(9px, calc(var(--cv-nav-fs) - 2px)); white-space: nowrap; }

            html.cv-nav-wrap .navbar .nav-links { gap: clamp(8px, 1vw, 18px); }
            html.cv-nav-wrap .navbar .nav-links a:not(.book-button) { font-size: var(--cv-nav-fs); letter-spacing: 0; white-space: normal; text-align: center; line-height: 1.15; max-width: 9em; }
            html.cv-nav-wrap .navbar .nav-book-desktop { font-size: max(9px, calc(var(--cv-nav-fs) - 1px)); white-space: normal; line-height: 1.15; max-width: 200px; text-align: left; }
        }

        /* Everywhere else: long words break instead of spilling out, and wide letter spacing is toned down */
        html:not([lang="en"]) body { overflow-wrap: break-word; }
        html:not([lang="en"]) :is(h1, h2, h3) { letter-spacing: .02em; }
        html:not([lang="en"]) :is(button, [class*="button"], [class*="btn"]) { white-space: normal; }

        /* Hide Google's own banner and highlights: our menu is the only control */
        body { top: 0 !important; }
        body > .skiptranslate, iframe.skiptranslate, .goog-te-banner-frame,
        .VIpgJd-ZVi9od-ORHb-OEVmcd, .VIpgJd-ZVi9od-aZ2wEe-wOHMyf, #goog-gt-tt, .goog-tooltip { display: none !important; }
        .goog-text-highlight { background: none !important; box-shadow: none !important; }
        #google_translate_element { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }
    </style>

    <script>
        (function () {
            var cfg = @json($cvLangConfig);

            var host = location.hostname;
            var secure = location.protocol === 'https:' ? '; Secure' : '';

            function get(name) {
                var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
                return m ? decodeURIComponent(m[1]) : '';
            }
            function set(name, value, days) {
                document.cookie = name + '=' + encodeURIComponent(value) + '; path=/; max-age=' + days * 86400 + '; SameSite=Lax' + secure;
            }
            function drop(name) {
                document.cookie = name + '=; path=/; max-age=0';
                document.cookie = name + '=; path=/; max-age=0; domain=' + host;
                document.cookie = name + '=; path=/; max-age=0; domain=.' + host;
            }

            // Which language is this visitor reading? Their own choice, else the automatic one, else English.
            var choice = get('cv_lang');
            if (choice && choice !== 'auto' && cfg.codes.indexOf(choice) === -1) choice = 'auto';
            var mode = choice || (cfg.autoOn ? 'auto' : 'en');
            var target = mode;

            if (mode === 'auto') {
                target = cfg.auto || get('cv_auto') || 'en';
                if (cfg.auto) set('cv_auto', cfg.auto, 30);   // remembered, so the server does not look again
                if (cfg.codes.indexOf(target) === -1) target = 'en';
            }

            document.documentElement.lang = target === 'en' ? 'en' : target;

            // Let the translator know, then load it (only when not English).
            if (target === 'en') {
                drop('googtrans');
            } else {
                // The translator keeps its own copy of this cookie on the whole domain. Two cookies with the same
                // name can disagree (the old language wins), so clear every copy before setting the new one.
                drop('googtrans');
                document.cookie = 'googtrans=/en/' + target + '; path=/; max-age=' + 180 * 86400 + '; SameSite=Lax' + secure;

                window.googleTranslateElementInit = function () {
                    new google.translate.TranslateElement({
                        pageLanguage: 'en',
                        includedLanguages: cfg.google,
                        autoDisplay: false
                    }, 'google_translate_element');
                };

                var s = document.createElement('script');
                s.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                s.async = true;
                document.head.appendChild(s);
            }

            // ---- Header menu: make it fit, never with a menu button on a wide screen ----
            function fitNav() {
                var root = document.documentElement;
                var bar = document.querySelector('.navbar');
                var nav = document.getElementById('primary-nav');

                root.classList.remove('cv-nav-fit', 'cv-nav-wrap');
                root.style.removeProperty('--cv-nav-fs');
                if (!bar || !nav || window.innerWidth <= 1180) return;   // tablets and phones keep their own menu

                var overflows = function () {
                    // Measure the menu items themselves: the dropdown panels (hidden until hovered) stick out of the
                    // menu and used to make a menu that fits look too wide, which shrank its text for no reason.
                    var edge = nav.getBoundingClientRect().right;
                    var last = null;

                    for (var i = nav.children.length - 1; i >= 0; i--) {
                        if (nav.children[i].offsetParent !== null) { last = nav.children[i]; break; }
                    }

                    return (last !== null && last.getBoundingClientRect().right > edge + 1) || bar.scrollWidth > bar.clientWidth + 1;
                };

                if (!overflows()) return;   // fits as designed

                var link = nav.querySelector('a:not(.book-button)');
                var natural = link ? Math.floor(parseFloat(getComputedStyle(link).fontSize)) || 15 : 15;

                // 1. one line, smaller text
                var size = natural;
                root.style.setProperty('--cv-nav-fs', size + 'px');
                root.classList.add('cv-nav-fit');
                while (overflows() && size > 10) {
                    size -= 1;
                    root.style.setProperty('--cv-nav-fs', size + 'px');
                }
                if (!overflows()) return;

                // 2. labels on up to two lines, small text
                root.classList.remove('cv-nav-fit');
                root.classList.add('cv-nav-wrap');
                size = Math.min(natural, 13);
                root.style.setProperty('--cv-nav-fs', size + 'px');
                while (overflows() && size > 8) {
                    size -= 1;
                    root.style.setProperty('--cv-nav-fs', size + 'px');
                }
            }

            // ---- The rest of the page: buttons, headings and card text that a translation made too long ----
            var FIT_SELECTOR = 'a[class*="button"], button[class*="button"], a[class*="btn"], button[class*="btn"], .book-button, ' +
                'h1, h2, h3, h4, [class*="card"] p, [class*="badge"], [class~="tag"], [class*="-tag"], [class*="chip"], [class*="pill"]';

            function fitText() {
                if (target === 'en') return;

                // start again from the designed sizes (this also handles a resized window)
                [].forEach.call(document.querySelectorAll('[data-cv-fit]'), function (el) {
                    el.style.removeProperty('font-size');
                    el.removeAttribute('data-cv-fit');
                });

                [].forEach.call(document.querySelectorAll(FIT_SELECTOR), function (el) {
                    if (el.closest('.navbar, .cv-lang, .cvw-chat, dialog')) return;   // the header fits itself above

                    var cs = getComputedStyle(el);
                    if (cs.display === 'inline' || cs.display === 'none') return;

                    var clipped = /hidden|clip|auto|scroll/.test(cs.overflowY);
                    var over = function () {
                        return el.scrollWidth > el.clientWidth + 1 || (clipped && el.scrollHeight > el.clientHeight + 2);
                    };

                    if (!over()) return;

                    var base = parseFloat(cs.fontSize);
                    var size = base;
                    el.setAttribute('data-cv-fit', '1');

                    // smaller step by step, but never below 70% of the designed size (it stays readable)
                    while (over() && size > base * 0.7) {
                        size -= Math.max(0.5, base * 0.04);
                        el.style.fontSize = size + 'px';
                    }
                });
            }

            var fitTimer = null;
            function fitSoon() {
                clearTimeout(fitTimer);
                fitTimer = setTimeout(function () {
                    try { fitNav(); fitText(); } catch (e) { /* never let fitting break the page */ }
                }, 200);
            }

            // The header calls this right after it is written, so the menu is already the right size on the first
            // paint (no jump a moment after the page opens).
            window.cvFitNav = function () {
                try { fitNav(); } catch (e) { /* never let fitting break the page */ }
            };

            window.addEventListener('resize', fitSoon);
            window.addEventListener('load', fitSoon);
            document.addEventListener('DOMContentLoaded', function () {
                // Only a translated page changes its text after loading; watching the whole page in English
                // made the header re-measure itself on every carousel or chat update.
                if (target !== 'en' && 'MutationObserver' in window) {
                    // the translation arrives as a burst of text changes
                    new MutationObserver(fitSoon).observe(document.body, { childList: true, subtree: true, characterData: true });
                }
                fitSoon();
            });
            if (document.fonts && document.fonts.ready) document.fonts.ready.then(fitSoon);

            // The menu
            function ready() {
                var root = document.querySelector('[data-cv-lang]');
                if (!root) return;

                var btn = root.querySelector('[data-cv-lang-btn]');
                var menu = root.querySelector('[data-cv-lang-menu]');
                var label = root.querySelector('[data-cv-lang-label]');
                var options = [].slice.call(root.querySelectorAll('[data-cv-lang-pick]'));

                label.textContent = cfg.short[target] || target.toUpperCase();

                options.forEach(function (o) {
                    o.setAttribute('aria-selected', String(o.getAttribute('data-cv-lang-pick') === mode));
                });

                function close() { menu.hidden = true; btn.setAttribute('aria-expanded', 'false'); }
                function open() {
                    menu.hidden = false; btn.setAttribute('aria-expanded', 'true');
                    var current = options.filter(function (o) { return o.getAttribute('aria-selected') === 'true'; })[0] || options[0];
                    current.focus();
                }

                btn.addEventListener('click', function () { menu.hidden ? open() : close(); });
                document.addEventListener('click', function (e) { if (!root.contains(e.target)) close(); });
                root.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') { close(); btn.focus(); }
                    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                        e.preventDefault();
                        var i = options.indexOf(document.activeElement);
                        i = e.key === 'ArrowDown' ? (i + 1) % options.length : (i - 1 + options.length) % options.length;
                        options[i].focus();
                    }
                });

                options.forEach(function (o) {
                    o.addEventListener('click', function () {
                        var pick = o.getAttribute('data-cv-lang-pick');
                        set('cv_lang', pick, 180);
                        if (pick === 'auto') drop('cv_auto');   // look again at where the visitor is
                        drop('googtrans');                       // forget the language being shown now
                        location.reload();
                    });
                });
            }

            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready);
            else ready();
        })();
    </script>
@endif
