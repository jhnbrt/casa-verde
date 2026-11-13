<style>
.jn{--jn-ink:#1c1915;--jn-muted:#6a6157;--jn-gold:#a98150;--jn-deep:#06251d;padding-bottom:clamp(48px,7vw,96px);background:linear-gradient(180deg,#efe9df 0%,#e8e1d6 100%);color:var(--jn-ink);min-height:70vh}
.jn-head{text-align:center;padding:clamp(40px,7vw,84px) 20px clamp(24px,4vw,44px)}
.jn-head h1{font-size:clamp(2.4rem,6vw,4.4rem);font-weight:500;letter-spacing:-.01em;color:var(--jn-deep);margin:0}
.jn-head p{margin:14px auto 0;max-width:640px;font-size:clamp(1rem,1.7vw,1.2rem);letter-spacing:.04em;color:var(--jn-muted)}
.jn-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,300px),1fr));gap:clamp(20px,3vw,34px);width:min(1180px,92vw);margin:0 auto}
.jn-card{display:flex;flex-direction:column;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 10px 30px rgba(28,25,21,.08);text-decoration:none;color:inherit;transition:transform .25s,box-shadow .25s}
.jn-card:hover,.jn-card:focus-visible{transform:translateY(-4px);box-shadow:0 16px 38px rgba(28,25,21,.14)}
.jn-card img,.jn-ph{width:100%;aspect-ratio:3/2;object-fit:cover;display:block;background:#d8d0c2}
.jn-body{display:flex;flex-direction:column;gap:10px;padding:22px 24px 26px;flex:1}
.jn-date{font-size:.8rem;letter-spacing:.2em;text-transform:uppercase;color:var(--jn-gold)}
.jn-card h2{font-size:1.45rem;line-height:1.2;margin:0;color:var(--jn-deep)}
.jn-card p{margin:0;color:var(--jn-muted);line-height:1.6}
.jn-more{margin-top:auto;padding-top:6px;font-weight:600;color:var(--jn-deep);border-bottom:1px solid var(--jn-gold);align-self:flex-start}
.jn-empty{text-align:center;color:var(--jn-muted);padding:40px 20px;font-size:1.1rem}
.jn-article{width:min(780px,92vw);margin:0 auto;padding-top:clamp(28px,5vw,56px)}
.jn-article .jn-hero{width:100%;aspect-ratio:16/10;object-fit:cover;border-radius:14px;margin-bottom:30px;display:block}
.jn-article p{font-size:1.12rem;line-height:1.8;margin:0 0 1.2em}
.jn-others{width:min(1180px,92vw);margin:clamp(40px,6vw,72px) auto 0}
.jn-others>h2{text-align:center;font-size:1.6rem;color:var(--jn-deep);margin:0 0 24px}
.jn-others .jn-grid{width:100%;margin:0 auto;grid-template-columns:repeat(auto-fit,minmax(min(100%,300px),360px));justify-content:center}
.jn-article h2{font-size:clamp(1.4rem,3vw,1.9rem);color:var(--jn-deep);margin:1.6em 0 .6em}
.jn-gallery{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,300px),1fr));gap:16px;margin-top:34px}
.jn-gallery figure{margin:0}
.jn-gallery img{width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;display:block}
.jn-gallery figcaption{margin-top:8px;font-size:.92rem;color:var(--jn-muted);text-align:center}
.jn-article h3{font-size:clamp(1.15rem,2.4vw,1.45rem);color:var(--jn-deep);margin:1.5em 0 .5em}
.jn-article ul{margin:0 0 1.4em;padding-left:1.3em}
.jn-article li{font-size:1.1rem;line-height:1.75;margin:0 0 .7em}
.jn-article li::marker{color:var(--jn-gold)}
/* Article page: white page, centred header (back link, big title, published date), then the photo and text */
.jn--article{background:#fff}
.jn-art-head{width:min(1000px,92vw);margin:0 auto;padding:clamp(34px,6vw,72px) 0 clamp(18px,3vw,30px);text-align:center}
.jn-back{font-family:Lato,'Helvetica Neue',Arial,sans-serif;font-weight:700;font-size:.95rem;letter-spacing:.06em;text-transform:uppercase;color:#c9a45c;text-decoration:none}
.jn-back:hover,.jn-back:focus-visible{color:#a98150}
.jn-art-head h1{font-family:'Playfair Display',Georgia,serif;font-weight:700;font-size:clamp(2.2rem,6.4vw,4.6rem);line-height:1.18;color:#1b3d35;margin:22px 0 22px;letter-spacing:0}
.jn-pub{font-family:Lato,'Helvetica Neue',Arial,sans-serif;font-style:italic;font-size:1.12rem;color:#8d8d88;margin:0}
.jn--article .jn-article{padding-top:0}
.jn--article .jn-article p,.jn--article .jn-article li{font-family:Lato,'Helvetica Neue',Arial,sans-serif;color:#3d3d3a}
.jn--article .jn-article h2,.jn--article .jn-article h3{font-family:'Playfair Display',Georgia,serif;font-weight:700;color:#1b3d35}
/* "More Stories": off-white band, big centred heading, three cards in a row (photo, bold title, gold link) */
.jn--article{padding-bottom:0}
.jn--article .jn-others{width:100%;max-width:none;margin:clamp(40px,6vw,80px) 0 0;padding:clamp(40px,5vw,72px) 0 clamp(48px,6vw,92px);background:#faf9f6}
.jn--article .jn-others>h2{font-family:'Playfair Display',Georgia,serif;font-weight:700;font-size:clamp(2rem,4.4vw,3rem);color:#1b3d35;text-align:center;margin:0 0 clamp(28px,4vw,52px)}
.jn--article .jn-others .jn-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:clamp(20px,2.6vw,40px);width:min(1240px,92vw);max-width:none;margin:0 auto;justify-content:normal}
.jn--article .jn-others .jn-card{border-radius:8px;box-shadow:0 2px 14px rgba(0,0,0,.06)}
.jn--article .jn-others .jn-card:hover,.jn--article .jn-others .jn-card:focus-visible{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.1)}
.jn--article .jn-others .jn-card img,.jn--article .jn-others .jn-ph{aspect-ratio:19/10}
.jn--article .jn-others .jn-body{gap:0;padding:26px 27px 34px}
.jn--article .jn-others .jn-card h2{font-family:'Playfair Display',Georgia,serif;font-weight:700;font-size:clamp(1.15rem,1.7vw,1.45rem);line-height:1.55;color:#1b3d35;text-align:left;margin:0 0 20px}
.jn--article .jn-others .jn-more{margin-top:0;padding-top:0;border:0;align-self:flex-start;font-family:Lato,'Helvetica Neue',Arial,sans-serif;font-weight:700;font-size:1.05rem;color:#c0975a}
@media (max-width:900px){.jn--article .jn-others .jn-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:600px){.jn--article .jn-others .jn-grid{grid-template-columns:minmax(0,1fr)}}
.jn-article h4{font-size:clamp(1.05rem,2vw,1.25rem);color:var(--jn-deep);margin:1.4em 0 .4em}
.jn--article .jn-article h4{font-family:'Playfair Display',Georgia,serif;font-weight:700;color:#1b3d35}
</style>
