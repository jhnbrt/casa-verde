import '../css/photo-viewer.css';
// Mobile navigation toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.nav-toggle');
    const header = document.querySelector('.navbar');

    if (!toggle || !header) return;

    const setOpen = (open) => {
        header.classList.toggle('nav-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    };

    toggle.addEventListener('click', () => setOpen(!header.classList.contains('nav-open')));

    header.querySelectorAll('.nav-links a').forEach((link) =>
        link.addEventListener('click', () => setOpen(false))
    );

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') setOpen(false);
    });
});


// Header gets a soft shadow once the page is scrolled
document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.navbar');
    if (!header) return;

    const update = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
    update();
    window.addEventListener('scroll', update, { passive: true });
});


// Admin: show a preview of the chosen photo before the entry is saved
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('[data-image-input]');
    if (!input) return;

    const preview = document.querySelector('[data-image-preview]');
    const empty = document.querySelector('[data-image-empty]');
    const name = document.querySelector('[data-image-name]');

    input.addEventListener('change', () => {
        const file = input.files && input.files[0];
        if (!file) return;

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        empty.classList.add('hidden');
        name.textContent = 'Selected: ' + file.name + '. Save changes to publish it.';
    });
});

// Admin: manage a gallery of photos on one entry (remove, reorder, preview new uploads)
document.addEventListener('DOMContentLoaded', () => {
    const box = document.querySelector('[data-gallery-box]');
    if (!box) return;

    const list = box.querySelector('[data-gallery-list]');
    const empty = box.querySelector('[data-gallery-empty]');
    const input = box.querySelector('[data-gallery-input]');
    const note = box.querySelector('[data-gallery-new-note]');
    const preview = box.querySelector('[data-gallery-new-preview]');

    const items = () => [...list.querySelectorAll('[data-gallery-item]')];

    const refresh = () => {
        items().forEach((item, index) => {
            const number = item.querySelector('[data-gallery-number]');
            if (number) number.textContent = String(index + 1);
        });

        empty.classList.toggle('hidden', items().length > 0);
    };

    list.addEventListener('click', (event) => {
        const item = event.target.closest('[data-gallery-item]');
        if (!item) return;

        const move = event.target.closest('[data-gallery-move]');

        if (move) {
            const step = Number(move.dataset.galleryMove);
            const sibling = step < 0 ? item.previousElementSibling : item.nextElementSibling;

            if (sibling) {
                if (step < 0) list.insertBefore(item, sibling);
                else list.insertBefore(sibling, item);
            }

            refresh();
            move.focus();
            return;
        }

        if (event.target.closest('[data-gallery-remove]')) {
            const next = item.nextElementSibling || item.previousElementSibling;
            item.remove();
            refresh();
            next?.querySelector('[data-gallery-remove]')?.focus();
        }
    });

    input.addEventListener('change', () => {
        preview.replaceChildren();
        const files = [...(input.files || [])];

        if (!files.length) {
            note.textContent = '';
            return;
        }

        files.forEach((file) => {
            const figure = document.createElement('figure');
            figure.className = 'overflow-hidden rounded-lg border border-sand-300 bg-white';

            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = '';
            image.className = 'aspect-[4/3] w-full object-cover';

            const caption = document.createElement('figcaption');
            caption.className = 'truncate px-2 py-1 text-xs text-ink-soft';
            caption.textContent = file.name;

            figure.append(image, caption);
            preview.append(figure);
        });

        const total = items().length + files.length;
        note.textContent = files.length + ' new photo' + (files.length === 1 ? '' : 's') + ' selected (' + total + ' in the gallery after saving). Press Save changes to add ' + (files.length === 1 ? 'it' : 'them') + '. Choosing again replaces this selection.';
    });

    refresh();
});


// Public pages: accessible full-screen photo viewer. Gallery has its own viewer.
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.gal-lightbox, .gal-lb, [data-gallery-lightbox]') || document.body.classList.contains('admin')) return;
    const excluded = 'header, footer, nav, a, button, form, .feature-icon, .villa-icon, .villa-feature-icon, .vs-check-logo, .gal-tile, .gal-photo, [data-no-lightbox], [data-gallery-remove], .navbar, .site-logo';
    const candidates = [...document.querySelectorAll('main img, section img, article img')].filter(img => {
        if (img.closest(excluded) || img.getAttribute('aria-hidden') === 'true') return false;
        const src = img.currentSrc || img.src;
        if (!src || /(?:logo|icon|glyph|favicon|badge|symbol)/i.test(src)) return false;
        return true;
    });
    if (!candidates.length) return;
    const viewer = document.createElement('div');
    viewer.className = 'cv-photo-viewer';
    viewer.setAttribute('role','dialog');
    viewer.setAttribute('aria-modal','true');
    viewer.setAttribute('aria-label','Full screen photo viewer');
    viewer.hidden = true;
    viewer.innerHTML = `<div class="cv-photo-viewer__toolbar"><span class="cv-photo-viewer__count" aria-live="polite"></span><button type="button" data-cv-zoom aria-label="Zoom photo">Zoom +</button><button type="button" data-cv-close aria-label="Close viewer">×</button></div><button type="button" class="cv-photo-viewer__prev" data-cv-prev aria-label="Previous photo">‹</button><img class="cv-photo-viewer__image" alt=""><button type="button" class="cv-photo-viewer__next" data-cv-next aria-label="Next photo">›</button>`;
    document.body.appendChild(viewer);
    const image = viewer.querySelector('img');
    const count = viewer.querySelector('.cv-photo-viewer__count');
    const zoomButton = viewer.querySelector('[data-cv-zoom]');
    let index = 0, zoomed = false, previousFocus = null, touchStart = null;
    const show = (next) => {
        index = (next + candidates.length) % candidates.length;
        const source = candidates[index];
        image.src = source.currentSrc || source.src;
        image.alt = source.alt || 'Resort photo';
        count.textContent = `${index + 1} / ${candidates.length}`;
        zoomed = false;
        image.classList.remove('is-zoomed');
        zoomButton.textContent = 'Zoom +';
    };
    const close = () => {
        viewer.hidden = true;
        document.body.classList.remove('cv-photo-viewer-open');
        image.removeAttribute('src');
        previousFocus?.focus?.();
    };
    candidates.forEach((img, i) => {
        img.classList.add('cv-photo-viewer-trigger');
        img.setAttribute('tabindex', '0');
        img.setAttribute('role', 'button');
        img.setAttribute('aria-label', `View full screen: ${img.alt || 'resort photo'}`);
        const open = () => {
            previousFocus = document.activeElement;
            show(i);
            viewer.hidden = false;
            document.body.classList.add('cv-photo-viewer-open');
            viewer.querySelector('[data-cv-close]').focus();
        };
        img.addEventListener('click', open);
        img.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); open(); }
        });
    });
    viewer.querySelector('[data-cv-close]').addEventListener('click', close);
    viewer.querySelector('[data-cv-prev]').addEventListener('click', () => show(index - 1));
    viewer.querySelector('[data-cv-next]').addEventListener('click', () => show(index + 1));
    zoomButton.addEventListener('click', () => {
        zoomed = !zoomed;
        image.classList.toggle('is-zoomed', zoomed);
        zoomButton.textContent = zoomed ? 'Zoom −' : 'Zoom +';
    });
    viewer.addEventListener('click', event => { if (event.target === viewer) close(); });
    document.addEventListener('keydown', event => {
        if (viewer.hidden) return;
        if (event.key === 'Escape') close();
        if (event.key === 'ArrowLeft') show(index - 1);
        if (event.key === 'ArrowRight') show(index + 1);
        if (event.key === 'Tab') {
            const controls = [...viewer.querySelectorAll('button')];
            const first = controls[0], last = controls[controls.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    });
    viewer.addEventListener('touchstart', e => { touchStart = e.changedTouches[0]?.clientX ?? null; }, {passive:true});
    viewer.addEventListener('touchend', e => {
        if (touchStart === null) return;
        const dx = (e.changedTouches[0]?.clientX ?? touchStart) - touchStart;
        if (Math.abs(dx) > 55) show(index + (dx < 0 ? 1 : -1));
        touchStart = null;
    }, {passive:true});
});
