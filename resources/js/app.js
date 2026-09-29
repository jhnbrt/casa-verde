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
