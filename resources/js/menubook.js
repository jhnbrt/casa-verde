// Menu book: the "Explore food menu" button opens a modal with a closed book (cover + "Click here"
// hint). Clicking the cover opens it like a real book; pages turn with the arrows, a click on a page,
// the keyboard or a swipe. On phones the cover opens and the pages are then read by scrolling.

const phone = window.matchMedia('(max-width: 820px)');

class MenuBook {
    constructor(root) {
        this.root = root;
        this.book = root.querySelector('[data-mb-book]');
        this.sheets = [...root.querySelectorAll('[data-mb-sheet]')];
        this.turns = Number(root.dataset.turns || 0);
        this.pages = Number(root.dataset.pages || 0);
        this.scroll = root.querySelector('[data-mb-scroll]');
        this.count = root.querySelector('[data-mb-count]');
        this.flipped = 0;
        this.opener = null;
        this.timers = [];

        this.root.querySelectorAll('[data-mb-close]').forEach((el) => el.addEventListener('click', () => this.close()));
        this.root.querySelector('[data-mb-open]')?.addEventListener('click', () => this.openCover());
        this.root.querySelectorAll('[data-mb-next]').forEach((el) => el.addEventListener('click', () => this.go(1)));
        this.root.querySelectorAll('[data-mb-prev]').forEach((el) => el.addEventListener('click', () => this.go(-1)));

        this.keyHandler = (event) => this.onKey(event);
        this.initSwipe();
        this.initRatio();
        this.render(0);
    }

    // Page shape comes from the cover picture, so any portrait menu size fits.
    initRatio() {
        const cover = this.sheets[0]?.querySelector('img');
        const apply = () => {
            if (cover && cover.naturalWidth && cover.naturalHeight) {
                this.root.style.setProperty('--mb-r', (cover.naturalWidth / cover.naturalHeight).toFixed(4));
            }
        };
        if (cover?.complete) apply(); else cover?.addEventListener('load', apply, { once: true });
    }

    open(opener) {
        this.opener = opener || null;
        this.reset();
        this.root.hidden = false;
        document.documentElement.classList.add('mb-lock');
        document.addEventListener('keydown', this.keyHandler);
        // Next frame, so the fade and zoom-in play.
        requestAnimationFrame(() => requestAnimationFrame(() => this.root.classList.add('is-visible')));
        this.root.querySelector('[data-mb-open]')?.focus({ preventScroll: true });
    }

    close() {
        this.root.classList.remove('is-visible');
        document.documentElement.classList.remove('mb-lock');
        document.removeEventListener('keydown', this.keyHandler);
        window.setTimeout(() => {
            this.root.hidden = true;
            this.reset();
            this.opener?.focus?.({ preventScroll: true });
        }, 350);
    }

    reset() {
        this.timers.forEach(window.clearTimeout);
        this.timers = [];
        this.root.classList.remove('is-open', 'is-reading');
        this.book.classList.remove('is-opening');
        if (this.scroll) { this.scroll.hidden = true; this.scroll.scrollTop = 0; }
        const before = this.flipped;
        this.flipped = 0;
        this.render(before);
    }

    // The cover: opens the book (or, on a phone, swings the cover open and then shows the pages to scroll).
    openCover() {
        if (phone.matches) {
            this.book.classList.add('is-opening');
            this.flipped = 1;
            this.render(0);
            this.timers.push(window.setTimeout(() => {
                this.root.classList.add('is-reading');
                if (this.scroll) { this.scroll.hidden = false; this.scroll.scrollTop = 0; }
                this.root.querySelector('.mb-close')?.focus({ preventScroll: true });
            }, 1000));
            return;
        }
        this.go(1);
        this.root.querySelector('[data-mb-next]:not([tabindex="-1"])')?.focus({ preventScroll: true });
    }

    go(step) {
        const target = Math.min(this.turns, Math.max(0, this.flipped + step));
        if (target === this.flipped) return;
        const before = this.flipped;
        this.flipped = target;
        this.render(before);
    }

    render(before) {
        const total = this.sheets.length;

        this.sheets.forEach((sheet, i) => {
            const isFlipped = i < this.flipped;
            const wasFlipped = i < before;
            const settled = isFlipped ? i + 1 : total - i;

            sheet.classList.toggle('is-flipped', isFlipped);

            if (isFlipped !== wasFlipped) {
                // The sheet that is turning stays on top until it has passed the middle.
                sheet.style.zIndex = 200;
                this.timers.push(window.setTimeout(() => { sheet.style.zIndex = settled; }, 600));
            } else {
                sheet.style.zIndex = settled;
            }
        });

        const closed = this.flipped === 0;
        const atEnd = this.flipped === total;

        this.book.classList.toggle('is-closed', closed);
        this.book.classList.toggle('is-end', atEnd);
        this.root.classList.toggle('is-open', !closed);
        this.root.classList.toggle('is-end', atEnd);

        const canPrev = this.flipped > 0;
        const canNext = this.flipped < this.turns;
        this.root.querySelectorAll('[data-mb-prev]').forEach((el) => { el.disabled = !canPrev; });
        this.root.querySelectorAll('[data-mb-next]').forEach((el) => { el.disabled = !canNext; });

        if (this.count) {
            if (closed || this.pages === 0) {
                this.count.textContent = '';
            } else {
                const left = this.flipped * 2 - 1;
                const right = this.flipped * 2;
                this.count.textContent = right > this.pages || atEnd
                    ? `Page ${left} of ${this.pages}`
                    : `Pages ${left}–${right} of ${this.pages}`;
            }
        }
    }

    onKey(event) {
        if (event.key === 'Escape') { event.preventDefault(); this.close(); return; }
        if (this.root.classList.contains('is-reading')) return;
        if (event.key === 'ArrowRight') { event.preventDefault(); if (this.flipped > 0) this.go(1); }
        if (event.key === 'ArrowLeft') { event.preventDefault(); this.go(-1); }
    }

    initSwipe() {
        const stage = this.root.querySelector('[data-mb-stage]');
        let startX = null;
        let startY = null;

        stage.addEventListener('pointerdown', (e) => { startX = e.clientX; startY = e.clientY; });
        stage.addEventListener('pointerup', (e) => {
            if (startX === null) return;
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            startX = startY = null;
            if (Math.abs(dx) > 55 && Math.abs(dx) > Math.abs(dy) * 1.5 && this.flipped > 0) this.go(dx < 0 ? 1 : -1);
        });
        stage.addEventListener('pointercancel', () => { startX = startY = null; });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const books = new Map();

    document.querySelectorAll('[data-mb]').forEach((root) => books.set(root.id, new MenuBook(root)));

    document.querySelectorAll('[data-menu-book]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            const book = books.get(trigger.dataset.menuBook);
            if (!book) return;
            event.preventDefault();
            book.open(trigger);
        });
    });
});
