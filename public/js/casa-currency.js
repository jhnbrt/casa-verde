(() => {
  const script = document.currentScript;
  const select = document.getElementById('cv-currency-select');
  if (!select) return;
  const supported = [...select.options].map(o => o.value);
  const regionMap = {US:'USD',GB:'GBP',AU:'AUD',CA:'CAD',JP:'JPY',SG:'SGD',KR:'KRW',CN:'CNY',HK:'HKD',AE:'AED',IN:'INR',NZ:'NZD',PH:'PHP',DE:'EUR',FR:'EUR',IT:'EUR',ES:'EUR',NL:'EUR',IE:'EUR',BE:'EUR',AT:'EUR',PT:'EUR',FI:'EUR'};
  const locale = navigator.language || 'en-PH';
  const region = locale.split('-')[1]?.toUpperCase();
  let currency = localStorage.getItem('casa_display_currency') || regionMap[region] || 'PHP';
  if (!supported.includes(currency)) currency = 'PHP';
  select.value = currency;
  let rates = {PHP:1};
  const fmt = (n, code) => new Intl.NumberFormat('en-US',{style:'currency',currency:code,minimumFractionDigits:code==='JPY'||code==='KRW'?0:2,maximumFractionDigits:code==='JPY'||code==='KRW'?0:2}).format(n);
  const candidates = '.villa-price,.vs-price strong,.bk-price strong,.bk-price s,.bk-summary [data-bk-total],.bk-summary [data-bk-line-amount],.bk-summary [data-bk-save],[data-bk-total],[data-bk-line-amount],[data-bk-save],.mbp-price,.wr-amount strong,.yg-price';
  const pesoPattern = /(?:PHP\s*|₱\s*)([\d,]+(?:\.\d+)?)/gi;
  const amountPattern = /^\s*([\d,]+(?:\.\d+)?)\s*$/;
  function convertText(text, el) {
    if (el.matches('.mbp-price')) {
      const match = text.match(/(?:₱|PHP\s*)?([\d,]+(?:\.\d+)?)/i);
      if (!match) return text;
      return text.replace(match[0],fmt(Number(match[1].replaceAll(',',''))*rates[currency],currency));
    }
    if (el.matches('.wr-amount strong,.villa-price strong')) {
      const match = text.match(amountPattern);
      if (match) return fmt(Number(match[1].replaceAll(',',''))*rates[currency],currency);
    }
    return text.replace(pesoPattern,(_,num)=>fmt(Number(num.replaceAll(',',''))*rates[currency],currency));
  }
  let busy=false;
  function render(){
    if (busy) return;
    busy=true;
    document.querySelectorAll(candidates).forEach(el=>{
      if (el.closest('header') || el.closest('.cv-currency-control')) return;
      // Preserve the original PHP amount; dynamic booking totals update their text separately.
      const textNodes=[...el.childNodes].filter(n=>n.nodeType===Node.TEXT_NODE);
      for (const node of textNodes) {
        if (node._casaDisplayed !== undefined && node.textContent !== node._casaDisplayed) node._casaOriginal=node.textContent;
        if (node._casaOriginal === undefined) node._casaOriginal=node.textContent;
        const result=currency==='PHP'||!rates[currency]?node._casaOriginal:convertText(node._casaOriginal,el);
        if (node.textContent!==result) node.textContent=result;
        node._casaDisplayed=result;
      }
    });
    busy=false;
  }
  select.addEventListener('change',()=>{currency=select.value;localStorage.setItem('casa_display_currency',currency);render();});
  fetch(script.dataset.ratesUrl,{headers:{Accept:'application/json'}}).then(r=>r.ok?r.json():null).then(data=>{
    if(data?.available && data.rates){rates=data.rates; if(!rates[currency]){currency='PHP';select.value='PHP';} render();}
    else {currency='PHP';select.value='PHP';select.title='Live exchange rates unavailable; displaying PHP';}
  }).catch(()=>{currency='PHP';select.value='PHP';select.title='Live exchange rates unavailable; displaying PHP';});
  let pending=false;
  new MutationObserver(()=>{if(busy||pending)return;pending=true;requestAnimationFrame(()=>{pending=false;render();});}).observe(document.body,{subtree:true,childList:true,characterData:true});
  render();
})();
