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
