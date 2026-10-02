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
