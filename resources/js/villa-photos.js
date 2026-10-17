// Villas page: click a villa photo to open its photo gallery (sliding cards in a pop-up).

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-vm]').forEach(initGallery);

    document.querySelectorAll('[data-vm-open]').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById(button.dataset.vmOpen)?.showModal();
        });
    });
});

function initGallery(dialog) {
    const slides = [...dialog.querySelectorAll('.vm-slide')];
    const counter = dialog.querySelector('[data-vm-count]');
    const stage = dialog.querySelector('.vm-stage');
    const count = slides.length;

    let active = 0;

    const offsetOf = (index) => {
        let d = (index - active) % count;
        if (d > count / 2) d -= count;
        if (d < -count / 2) d += count;
        return d;
    };

    const render = () => {
        slides.forEach((slide, index) => {
            const d = offsetOf(index);
            slide.dataset.pos = Math.abs(d) >= 3 ? (d > 0 ? '3' : '-3') : String(d);
            slide.setAttribute('aria-hidden', d === 0 ? 'false' : 'true');
        });

        if (counter) counter.textContent = `${active + 1} / ${count}`;
    };

    const go = (index) => {
        active = (index + count) % count;
        render();
    };

    dialog.querySelector('[data-vm-prev]')?.addEventListener('click', () => go(active - 1));
    dialog.querySelector('[data-vm-next]')?.addEventListener('click', () => go(active + 1));
    dialog.querySelector('[data-vm-close]')?.addEventListener('click', () => dialog.close());

    // A photo beside the middle one slides into the middle when clicked.
    slides.forEach((slide, index) => slide.addEventListener('click', () => {
        if (offsetOf(index) !== 0) go(index);
    }));

    // Clicking the dark area around the photos closes the gallery.
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog || event.target.classList.contains('vm-inner') || event.target === stage) {
            dialog.close();
        }
    });

    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') go(active - 1);
        if (event.key === 'ArrowRight') go(active + 1);
    });

    // Swipe on touch screens.
    let startX = null;

    stage.addEventListener('pointerdown', (event) => { startX = event.clientX; });
    stage.addEventListener('pointerup', (event) => {
        if (startX === null) return;
        const dx = event.clientX - startX;
        startX = null;
        if (Math.abs(dx) > 40) go(active + (dx < 0 ? 1 : -1));
    });
    stage.addEventListener('pointercancel', () => { startX = null; });

    // The page behind stays still while the gallery is open; it always starts on the first photo.
    dialog.addEventListener('close', () => document.documentElement.classList.remove('vm-lock'));
    new MutationObserver(() => {
        if (dialog.open) {
            document.documentElement.classList.add('vm-lock');
            go(0);
        }
    }).observe(dialog, { attributes: true, attributeFilter: ['open'] });

    render();
}
