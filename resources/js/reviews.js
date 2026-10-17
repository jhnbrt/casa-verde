// Reviews page: 3D testimonial carousel and slot-machine figures.

document.addEventListener('DOMContentLoaded', () => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    initCarousel(reduceMotion);
    initSlotFigures(reduceMotion);
});

function initCarousel(reduceMotion) {
    const root = document.querySelector('[data-rv-carousel]');
    if (!root) return;

    const slides = [...root.querySelectorAll('.rv-slide')];
    const avatars = [...root.querySelectorAll('[data-rv-go]')];
    const count = slides.length;
    const AVATAR_WINDOW = 5;
    const delay = reduceMotion ? 0 : parseInt(root.dataset.autoplay || '0', 10);

    let active = 0;
    let timer = null;
    let stopped = false; // set once the visitor takes over

    const offsetOf = (index) => {
        let d = (index - active) % count;
        if (d > count / 2) d -= count;
        if (d < -count / 2) d += count;
        return d;
    };

    const render = () => {
        slides.forEach((slide, index) => {
            const d = offsetOf(index);
            slide.dataset.pos = Math.abs(d) >= 2 ? (d > 0 ? '2' : '-2') : String(d);
            slide.setAttribute('aria-hidden', d === 0 ? 'false' : 'true');
        });

        const start = Math.max(0, Math.min(active - 2, avatars.length - AVATAR_WINDOW));

        avatars.forEach((avatar, index) => {
            avatar.hidden = avatars.length > AVATAR_WINDOW && (index < start || index >= start + AVATAR_WINDOW);

            if (index === active) avatar.setAttribute('aria-current', 'true');
            else avatar.removeAttribute('aria-current');
        });
    };

    const go = (index) => {
        active = (index + count) % count;
        render();
    };

    const takeOver = () => {
        stopped = true;
        clearTimeout(timer);
    };

    const schedule = () => {
        clearTimeout(timer);
        if (!delay || stopped || count < 2) return;
        timer = setTimeout(() => { go(active + 1); schedule(); }, delay);
    };

    root.querySelector('[data-rv-prev]')?.addEventListener('click', () => { takeOver(); go(active - 1); });
    root.querySelector('[data-rv-next]')?.addEventListener('click', () => { takeOver(); go(active + 1); });
    avatars.forEach((avatar, index) => avatar.addEventListener('click', () => { takeOver(); go(index); }));

    // Clicking a side card brings it to the middle.
    slides.forEach((slide, index) => slide.addEventListener('click', () => {
        if (offsetOf(index) !== 0) { takeOver(); go(index); }
    }));

    root.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') { takeOver(); go(active - 1); }
        if (event.key === 'ArrowRight') { takeOver(); go(active + 1); }
    });

    // Swipe on touch screens.
    const viewport = root.querySelector('.rv-viewport');
    let startX = null;

    viewport.addEventListener('pointerdown', (event) => { startX = event.clientX; });
    viewport.addEventListener('pointerup', (event) => {
        if (startX === null) return;
        const dx = event.clientX - startX;
        startX = null;
        if (Math.abs(dx) > 48) { takeOver(); go(active + (dx < 0 ? 1 : -1)); }
    });
    viewport.addEventListener('pointercancel', () => { startX = null; });

    // Autoplay pauses while the visitor is pointing at or focused inside the carousel.
    root.addEventListener('pointerenter', () => clearTimeout(timer));
    root.addEventListener('pointerleave', schedule);
    root.addEventListener('focusin', () => clearTimeout(timer));
    root.addEventListener('focusout', schedule);
    document.addEventListener('visibilitychange', () => (document.hidden ? clearTimeout(timer) : schedule()));

    render();
    schedule();
}

function initSlotFigures(reduceMotion) {
    const figures = [...document.querySelectorAll('[data-slot]')];
    if (!figures.length || reduceMotion) return; // the plain value is already in the page

    const STEP = 1.15; // em, must match .rv-reel height in reviews.css

    figures.forEach((figure) => {
        const value = figure.dataset.slot;
        const strips = [];
        let reelIndex = 0;

        figure.textContent = '';

        for (const char of value) {
            if (!/\d/.test(char)) {
                const still = document.createElement('span');
                still.className = 'rv-ch';
                still.textContent = char;
                still.setAttribute('aria-hidden', 'true');
                figure.append(still);
                continue;
            }

            const digit = Number(char);
            const cycles = 2 + Math.min(reelIndex, 4);
            const stopAt = cycles * 10 + digit;

            const reel = document.createElement('span');
            reel.className = 'rv-reel';
            reel.setAttribute('aria-hidden', 'true');

            const strip = document.createElement('span');
            strip.className = 'rv-reel__strip';

            // The reel runs past its stop by two digits so the little settle at the end never shows an empty gap.
            for (let i = 0; i <= stopAt + 2; i++) {
                const cell = document.createElement('span');
                cell.textContent = String(i % 10);
                strip.append(cell);
            }

            strip.dataset.stop = String(stopAt);
            strip.dataset.reel = String(reelIndex);
            reel.append(strip);
            figure.append(reel);
            strips.push(strip);
            reelIndex++;
        }

        const spin = () => {
            strips.forEach((strip) => {
                const index = Number(strip.dataset.reel);
                const duration = 1500 + index * 260;

                strip.classList.add('is-spinning');
                strip.style.transition = `transform ${duration}ms cubic-bezier(.25,.8,.35,1.08) ${index * 90}ms`;
                strip.style.transform = `translateY(${-Number(strip.dataset.stop) * STEP}em)`;

                const done = () => strip.classList.remove('is-spinning');
                strip.addEventListener('transitionend', done, { once: true });
                setTimeout(done, duration + index * 90 + 200);
            });
        };

        if (!('IntersectionObserver' in window)) {
            spin();
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            if (entries.some((entry) => entry.isIntersecting)) {
                observer.disconnect();
                spin();
            }
        }, { threshold: 0.6 });

        observer.observe(figure);
    });
}
