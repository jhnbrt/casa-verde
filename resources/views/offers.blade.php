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
      .cv-offers-page .cv-ico svg{width:65px;height:65px;max-width:100%;stroke:#dba568;fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}.cv-offers-page .cv-ico img,.cv-offers-page .cv-ico .cv-icon{width:65px;height:65px;object-fit:contain}.cv-offers-page .cv-perk-icon img,.cv-offers-page .cv-perk-icon .cv-icon{width:35px;height:35px;object-fit:contain}.cv-offers-page .cv-perk-icon .cv-emoji{font-size:28px;line-height:1}.cv-offers-page .cv-perk-icon{flex:0 0 39px;display:flex;align-items:center;justify-content:center;color:#dba568}.cv-offers-page .cv-perk-icon svg{width:35px;height:35px;fill:none;stroke:currentColor;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
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
                            <div class="cv-ico" aria-hidden="true"><x-offer-icon :name="$offer['icon']" variant="card" :position="$loop->index" /></div>
                            <div><h3>{{ $offer['title'] }}</h3><p>{{ $offer['text'] }}</p></div>
                        </div>
                        @if(!empty($offer['perk']))
                            <div class="cv-offer-perk"><span class="cv-perk-icon" aria-hidden="true"><x-offer-icon :name="$offer['perk_icon']" variant="perk" :position="$loop->index" /></span><span><b>{{ $offer['perk'] }}</b> {{ $offer['perk_text'] ?? '' }}</span></div>
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
