<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Offers | Casa Verde Cliff Resort &amp; Spa</title>
    @vite(['resources/css/header.css','resources/css/pages.css','resources/css/theme.css','resources/css/footer.css','resources/css/rule.css','resources/js/app.js'])
    <style>
      .cv-offers-page{background:#f8f5ef;color:#09241e;min-height:70vh}
      .cv-offers-page .cv-offers-section{max-width:1500px;margin:0 auto;padding:25px clamp(20px,3.3vw,52px) 56px}
      .cv-offers-page .cv-eyebrow{display:block;text-align:center;color:#dba568;font-size:clamp(18px,1.5vw,24px);margin:0 0 8px}
      .cv-offers-page .cv-title{text-align:center;font-size:clamp(34px,3.2vw,52px);line-height:1.13;letter-spacing:.095em;font-weight:500;margin:12px auto 14px;max-width:1000px}
      .cv-offers-page .cv-lead{text-align:center;font-family:var(--cv-sans,Arial,sans-serif);font-size:clamp(16px,1.42vw,22px);line-height:1.45;max-width:790px;margin:0 auto 16px;color:#151515}
      .cv-offers-page .cv-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:clamp(18px,2.6vw,40px);max-width:none;align-items:stretch}
      .cv-offers-page .cv-card{padding:2px 2px 17px;gap:0;border:1.5px solid #e2ac71;border-radius:27px;background:transparent;text-align:left;overflow:hidden;box-shadow:none}
      .cv-offers-page .cv-card>img{display:block;width:100%;aspect-ratio:1.78;object-fit:cover;border-radius:24px;flex:none}
      .cv-offers-page .cv-offer-main{display:grid;grid-template-columns:82px minmax(0,1fr);gap:10px;padding:10px 13px 13px;flex:1}
      .cv-offers-page .cv-ico{display:flex;align-items:flex-start;justify-content:center;padding-top:8px;color:#dba568;font-size:42px;line-height:1}
      .cv-offers-page .cv-ico svg{width:65px;height:65px;max-width:100%;stroke:#dba568;fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}.cv-offers-page .cv-perk-icon{flex:0 0 39px;display:flex;align-items:center;justify-content:center;color:#dba568}.cv-offers-page .cv-perk-icon svg{width:35px;height:35px;fill:none;stroke:currentColor;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
      .cv-offers-page .cv-card h3{font-size:clamp(23px,2vw,32px);line-height:1.15;margin:0 0 9px;font-weight:500;color:#09241e}
      .cv-offers-page .cv-card p{font-family:var(--cv-serif,Georgia,serif);font-size:clamp(15px,1.15vw,18px);line-height:1.5;margin:0;padding:0;color:#394c46}
      .cv-offers-page .cv-offer-perk{border-top:1px solid #e2ac71;margin:0 10% 10px;padding:13px 0 0;min-height:42px;display:flex;align-items:center;gap:7px;font-size:clamp(13px,1.02vw,17px);line-height:1.4;white-space:normal}
      .cv-offers-page .cv-offer-perk b{font-family:var(--cv-serif,Georgia,serif);font-weight:700}
      .cv-offers-page .cv-offer-action{text-align:center;margin-top:auto}
      .cv-offers-page .cv-btn{display:inline-flex;justify-content:center;align-items:center;min-height:53px;width:min(240px,80%);border:3px solid #dba568;border-radius:21px;background:#344f47;color:#dba568;font-family:var(--cv-serif,Georgia,serif);font-size:15px;letter-spacing:0;text-decoration:none;text-transform:uppercase;padding:8px 16px}
      .cv-offers-page .cv-btn:hover{background:#dba568;color:#09241e}
      .cv-offers-page .cv-note{max-width:1140px;margin:26px auto 0;gap:45px;font-size:19px}
      .cv-offers-page .cv-note i{background:#e2ac71}
      @media(max-width:1050px){.cv-offers-page .cv-grid{gap:18px}.cv-offers-page .cv-offer-main{grid-template-columns:52px minmax(0,1fr);gap:8px;padding:12px 9px}.cv-offers-page .cv-ico{font-size:30px}.cv-offers-page .cv-ico svg{width:44px;height:44px}}
      @media(max-width:760px){.cv-offers-page .cv-offers-section{padding:26px 18px 45px}.cv-offers-page .cv-grid{grid-template-columns:1fr;max-width:480px;margin:auto;gap:22px}.cv-offers-page .cv-card>img{aspect-ratio:1.8}.cv-offers-page .cv-offer-main{grid-template-columns:65px minmax(0,1fr);padding:12px}.cv-offers-page .cv-ico svg{width:54px;height:54px}.cv-offers-page .cv-title{letter-spacing:.04em}.cv-offers-page .cv-note{gap:12px;font-size:15px}}
    </style>
</head>
<body>
    @include('layouts.header')
    @php
        $img = fn (?string $path) => asset($path && file_exists(public_path($path)) ? $path : 'images/main.jpg');
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
    @endphp
    <main class="cv-page cv-offers-page">
        <section class="cv-section cv-offers-section">
            <span class="cv-eyebrow">{{ $page['header']['eyebrow'] }}</span>
            <x-rule :ornament="$page['header']['ornament']" />
            <h1 class="cv-title">{!! nl2br(e($page['header']['title'])) !!}</h1>
            <p class="cv-lead">{{ $page['header']['text'] }}</p>
            <div class="cv-grid">
                @foreach($page['items'] as $offer)
                    <article class="cv-card">
                        <img src="{{ $img($offer['image']) }}" alt="{{ $offer['title'] }}" loading="lazy">
                        <div class="cv-offer-main">
                            <div class="cv-ico" aria-hidden="true">
                                @if($loop->index === 0)
                                    <svg viewBox="0 0 80 70"><path d="M8 59h64M12 64h55M26 56l9-32M35 24c-13-7-20-3-25 5 12-3 18-2 25-5ZM35 24c-7-13-17-14-25-11 10 3 16 7 25 11ZM35 24c3-15 11-20 20-18-9 5-14 11-20 18ZM35 24c13-7 23-4 29 4-13-2-21-1-29-4ZM35 24c7 4 12 12 13 20-6-8-10-14-13-20ZM48 57l10-24M58 33c-10-7-17-5-22 0 10-1 16 0 22 0ZM58 33c-3-10-10-14-18-12 9 4 13 8 18 12ZM58 33c5-11 13-13 20-10-10 2-15 6-20 10ZM58 33c9-4 16-1 20 5-10-2-15-3-20-5Z"/></svg>
                                @elseif($loop->index === 1)
                                    <svg viewBox="0 0 80 70"><path d="M40 57C25 43 24 28 40 9c16 19 15 34 0 48ZM40 57C20 55 11 43 13 25c19 3 29 14 27 32ZM40 57c20-2 29-14 27-32-19 3-29 14-27 32ZM40 57C24 65 11 60 3 47c13-9 26-7 37 10ZM40 57c16 8 29 3 37-10-13-9-26-7-37 10Z"/></svg>
                                @else
                                    <svg viewBox="0 0 80 70"><path d="M40 62 11 32C-3 14 20-2 40 22 60-2 83 14 69 32Z" stroke-width="3"/></svg>
                                @endif
                            </div>
                            <div><h3>{{ $offer['title'] }}</h3><p>{{ $offer['text'] }}</p></div>
                        </div>
                        @if(!empty($offer['perk']))
                            <div class="cv-offer-perk"><span class="cv-perk-icon" aria-hidden="true">
                                @if($loop->index === 0)
                                    <svg viewBox="0 0 40 40"><path d="M5 21 22 4h12v12L17 35a4 4 0 0 1-6 0l-6-6a5 5 0 0 1 0-8Z"/><circle cx="27" cy="12" r="3"/></svg>
                                @elseif($loop->index === 1)
                                    <svg viewBox="0 0 40 40"><path d="M20 35C8 25 9 16 20 5c11 11 12 20 0 30ZM20 35C8 33 4 26 6 17c10 2 15 8 14 18ZM20 35c12-2 16-9 14-18-10 2-15 8-14 18Z"/></svg>
                                @else
                                    <svg viewBox="0 0 40 40"><path d="M20 35 5 19C-3 8 12 1 20 13 28 1 43 8 35 19Z" stroke-width="2.5"/></svg>
                                @endif
                            </span><span><b>{{ $offer['perk'] }}</b> {{ $offer['perk_text'] ?? '' }}</span></div>
                        @endif
                        <div class="cv-offer-action"><a href="{{ $href($offer['button_url']) }}" class="cv-btn">{{ $offer['button_text'] }}</a></div>
                    </article>
                @endforeach
            </div>
            @if($page['note'])<div class="cv-note"><i></i><span>{{ $page['note'] }}</span><i></i></div>@endif
        </section>
    </main>
    @include('layouts.footer')
</body>
</html>
