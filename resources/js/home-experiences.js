// Home page, Experiences: 3D coverflow carousel that turns by itself (like the Reviews page).

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-exp-carousel]');
    if (!root) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const slides = [...root.querySelectorAll('.exp-slide')];
    const dots = [...root.querySelectorAll('[data-exp-go]')];
    const count = slides.length;
    const delay = reduceMotion ? 0 : parseInt(root.dataset.autoplay || '0', 10);

    let active = 0;
    let timer = null;
    let hovering = false;
    let visible = true;

    const offsetOf = (index) => {
        let d = (index - active) % count;
        if (d > count / 2) d -= count;
        if (d < -count / 2) d += count;
        return d;
    };

    const render = () => {
        slides.forEach((slide, index) => {
            const d = offsetOf(index);
            slide.dataset.pos = String(Math.max(-3, Math.min(3, d)));
            slide.setAttribute('aria-hidden', d === 0 ? 'false' : 'true');
        });

        dots.forEach((dot, index) => {
            if (index === active) dot.setAttribute('aria-current', 'true');
            else dot.removeAttribute('aria-current');
        });
    };

    const go = (index) => {
        active = (index + count) % count;
        render();
    };

    // Turn by itself, but never while the visitor is pointing at it or it is off screen.
    const schedule = () => {
        clearTimeout(timer);
        if (!delay || count < 2 || hovering || !visible) return;
        timer = setTimeout(() => { go(active + 1); schedule(); }, delay);
    };

    // After the visitor moves it, wait a full pause before turning again.
    const touched = (index) => { go(index); schedule(); };

    root.querySelector('[data-exp-prev]')?.addEventListener('click', () => touched(active - 1));
    root.querySelector('[data-exp-next]')?.addEventListener('click', () => touched(active + 1));
    dots.forEach((dot, index) => dot.addEventListener('click', () => touched(index)));

    // Clicking a side card brings it to the middle.
    slides.forEach((slide, index) => slide.addEventListener('click', () => {
        if (offsetOf(index) !== 0) touched(index);
    }));

    root.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') touched(active - 1);
        if (event.key === 'ArrowRight') touched(active + 1);
    });

    root.addEventListener('mouseenter', () => { hovering = true; schedule(); });
    root.addEventListener('mouseleave', () => { hovering = false; schedule(); });
    root.addEventListener('focusin', () => { hovering = true; schedule(); });
    root.addEventListener('focusout', () => { hovering = false; schedule(); });

    // Swipe on touch screens.
    const viewport = root.querySelector('.exp-viewport');
    let startX = null;
    viewport.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; }, { passive: true });
    viewport.addEventListener('touchend', (e) => {
        if (startX === null) return;
        const dx = e.changedTouches[0].clientX - startX;
        if (Math.abs(dx) > 40) touched(active + (dx < 0 ? 1 : -1));
        startX = null;
    });

    if ('IntersectionObserver' in window) {
        new IntersectionObserver(([entry]) => { visible = entry.isIntersecting; schedule(); }, { threshold: 0.25 }).observe(root);
    }

    render();
    schedule();
});
