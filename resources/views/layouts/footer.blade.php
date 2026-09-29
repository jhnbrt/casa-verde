@php
    $siteInfo = $site ?? null;
    $home = url('/');
@endphp

@unless($hideCta ?? false)
    <section class="cvf-cta">
        <h2>Ready to experience Casa Verde?</h2>
        <div class="cvf-rule"><i></i><img src="{{ asset('images/logo.png') }}" alt="" width="34" height="34"><i></i></div>
        <a href="{{ $home }}#contact" class="cvf-btn">Book your stay</a>
    </section>
@endunless

<footer class="cvf" id="footer">
    <div class="cvf-grid">

        <div class="cvf-col cvf-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Casa Verde lotus logo" width="64" height="64">
            <strong>Casa Verde Cliff Resort &amp; Spa</strong>
            <small>Camotes Island, Philippines</small>
        </div>

        <nav class="cvf-col" aria-label="Explore">
            <h3>Explore</h3>
            <a href="{{ route('villas.index') }}">Villas</a>
            <a href="{{ route('experiences.index') }}">Experiences</a>
            <a href="{{ route('wellness.index') }}">Wellness</a>
            <a href="{{ route('dining.index') }}">Dining</a>
            <a href="{{ route('longstay.index') }}">Long Stay</a>
        </nav>

        <nav class="cvf-col" aria-label="Useful links">
            <h3>Useful Links</h3>
            <a href="{{ route('gallery.index') }}">Gallery</a>
            <a href="{{ route('offers.index') }}">Special Offers</a>
            <a href="{{ route('events.index') }}">Events</a>
            <a href="{{ $home }}#contact">Contact</a>
        </nav>

        <div class="cvf-col">
            <h3>Contact</h3>
            <p class="cvf-line"><x-icon name="pin" /><span>Sitio Mankahilo, Consuelo,<br>San Francisco, Philippines</span></p>
            <p class="cvf-line"><x-icon name="whatsapp" /><a href="https://wa.me/639622558380">+63-962-255-8380</a></p>
            <p class="cvf-line"><x-icon name="mail" /><a href="mailto:bookings@casaverdecliffresort.com">bookings@casaverdecliffresort.com</a></p>
        </div>

        <div class="cvf-col">
            <h3>Stay Connected</h3>
            <div class="cvf-social">
                <a href="#" aria-label="Instagram"><x-icon name="instagram" :size="20" /></a>
                <a href="#" aria-label="Facebook"><x-icon name="facebook" :size="20" /></a>
                <a href="#" aria-label="TikTok"><x-icon name="tiktok" :size="20" /></a>
            </div>
            <h3 class="cvf-sub">Be the first to know</h3>
            <p>Sign up for updates on special offers, events and resort news.</p>
            <form class="cvf-news" action="#" onsubmit="return false">
                <input type="email" placeholder="Your email address" aria-label="Email address">
                <button type="submit" aria-label="Subscribe"><x-icon name="arrow" :size="22" /></button>
            </form>
        </div>

    </div>

    <div class="cvf-bottom">
        <span class="cvf-legal"><a href="#">Privacy Policy</a><b></b><a href="#">Terms &amp; Conditions</a></span>
        <span>&copy; {{ date('Y') }} Casa Verde Cliff Resort &amp; Spa. All rights reserved.</span>
    </div>
</footer>
