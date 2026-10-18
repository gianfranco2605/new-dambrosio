// Audit eseguito nel browser: misura la pagina resa, non i sorgenti.
window.sdAudit = async function () {
  const sleep = ms => new Promise(r => setTimeout(r, ms));
  // Il tema usa scroll-behavior:smooth: senza questo, window.scrollTo anima
  // e le misure cadono a meta' scorrimento (l'hero risultava "non visibile").
  const prevBehavior = document.documentElement.style.scrollBehavior;
  document.documentElement.style.scrollBehavior = 'auto';
  const b = document.querySelector('.iubenda-cs-accept-btn'); if (b) b.click();
  await sleep(250);
  const H = document.body.scrollHeight;
  for (let y = 0; y < H + 900; y += 450) { window.scrollTo(0, y); await sleep(90); }
  window.scrollTo(0, 0); await sleep(500);

  const out = { w: innerWidth, h: innerHeight, pageH: document.body.scrollHeight };
  out.overflow = { scrollW: document.documentElement.scrollWidth, innerW: innerWidth,
                   ok: document.documentElement.scrollWidth <= innerWidth + 1 };

  // contenuto principale
  const wraps = [...document.querySelectorAll('main .sd-wrap, main .sd-chapter__grid')];
  out.contentWidth = Math.max(...wraps.map(e => Math.round(e.getBoundingClientRect().width)));

  // titoli
  const h1s = [...document.querySelectorAll('h1')];
  out.h1Count = h1s.length;
  out.h1 = h1s[0] ? { fs: parseFloat(getComputedStyle(h1s[0]).fontSize),
                      top: Math.round(h1s[0].getBoundingClientRect().top + scrollY),
                      bottom: Math.round(h1s[0].getBoundingClientRect().bottom + scrollY) } : null;
  out.levels = [...document.querySelectorAll('h1,h2,h3,h4,h5,h6')].map(h => +h.tagName[1]);
  out.jumps = out.levels.reduce((a, l, i) => i && l - out.levels[i-1] > 1 ? a.concat(out.levels[i-1]+'->'+l) : a, []);
  out.h2fs = [...new Set([...document.querySelectorAll('main h2')].map(h => parseFloat(getComputedStyle(h).fontSize)))];
  out.bodyFs = parseFloat(getComputedStyle(document.querySelector('.sd-prose p')).fontSize);

  // hero
  const hero = document.querySelector('.sd-hero');
  const hr = hero.getBoundingClientRect();
  const himg = hero.querySelector('img');
  const ir = himg.getBoundingClientRect();
  out.hero = { h: Math.round(hr.height), vhPct: +(hr.height / innerHeight * 100).toFixed(1),
    imgVisible: ir.width > 0 && ir.height > 0 && ir.top < innerHeight && ir.bottom > 0,
    imgNatural: himg.naturalWidth + 'x' + himg.naturalHeight,
    imgBox: Math.round(ir.width) + 'x' + Math.round(ir.height),
    upscale: +(Math.max(ir.width / himg.naturalWidth, ir.height / himg.naturalHeight)).toFixed(2),
    titleAboveFold: h1s[0] ? h1s[0].getBoundingClientRect().bottom <= innerHeight : false,
    titleTopAboveFold: h1s[0] ? h1s[0].getBoundingClientRect().top < innerHeight : false };

  // icone e immagini: nessuna deve sborare dal contenitore
  out.overflowing = [];
  document.querySelectorAll('main img, main svg, main iframe').forEach(el => {
    const r = el.getBoundingClientRect();
    const p = el.parentElement.getBoundingClientRect();
    if (r.width > p.width + 2 || r.height > p.height + 2) {
      out.overflowing.push({ tag: el.tagName, cls: String(el.className.baseVal ?? el.className).slice(0, 40),
        box: Math.round(r.width) + 'x' + Math.round(r.height), parent: Math.round(p.width) + 'x' + Math.round(p.height) });
    }
  });

  // icone: ogni immagine "piccola" (sorgente <= 256px, cioe' un'icona)
  out.icons = [];
  out.logos = [];
  document.querySelectorAll('.wp-site-blocks img, .wp-site-blocks svg').forEach(el => {
    const nat = el.naturalWidth || 0;
    const r = el.getBoundingClientRect();
    const isLogo = el.closest('.logo-footer, .wp-block-site-logo, .logo-menu, .sd-roster__portrait, .sd-panel__figure, .sd-chapter__figure, .sd-tech__figure, .sd-hero__media');
    if (isLogo) { out.logos.push({ w: Math.round(r.width), h: Math.round(r.height) }); return; }
    if (el.tagName.toLowerCase() === 'svg' || (nat && nat <= 256)) {
      out.icons.push({ cls: String(el.className.baseVal ?? el.className).slice(0, 36),
        src: (el.currentSrc || '').split('/').pop().slice(0, 30),
        w: Math.round(r.width), h: Math.round(r.height) });
    }
  });
  out.maxIcon = out.icons.length ? Math.max(...out.icons.map(i => Math.max(i.w, i.h))) : 0;

  // La regione vuota piu' alta: si uniscono le bande verticali occupate da
  // qualunque elemento di contenuto e si cerca il buco piu' grande fra loro.
  // Misurare per colonne darebbe falsi positivi (il gutter fra due colonne).
  // I campi di un modulo sono contenuto: un textarea vuoto non e' una
  // regione vuota della pagina.
  const sel = 'img, svg, iframe, p, h1, h2, h3, h4, table, li, figcaption, input, textarea, select, label, button, hr, .wp-block-button__link, .sd-panel';
  const bands = [];
  document.querySelectorAll('.wp-site-blocks ' + sel).forEach(el => {
    const r = el.getBoundingClientRect();
    if (r.height <= 0 || r.width <= 0) return;
    if (getComputedStyle(el).visibility === 'hidden') return;
    bands.push([r.top + scrollY, r.bottom + scrollY]);
  });
  bands.sort((a, b) => a[0] - b[0]);
  const merged = [];
  for (const b of bands) {
    if (merged.length && b[0] <= merged[merged.length - 1][1]) {
      merged[merged.length - 1][1] = Math.max(merged[merged.length - 1][1], b[1]);
    } else merged.push([b[0], b[1]]);
  }
  let worst = { h: 0, from: 0, to: 0 };
  for (let i = 1; i < merged.length; i++) {
    const gap = merged[i][0] - merged[i - 1][1];
    if (gap > worst.h) worst = { h: Math.round(gap), from: Math.round(merged[i - 1][1]), to: Math.round(merged[i][0]) };
  }
  out.gaps = merged.slice(0, -1).map((m, i) => ({ h: Math.round(merged[i + 1][0] - m[1]), at: Math.round(m[1]) }))
    .filter(g => g.h > 120).sort((a, b) => b.h - a.h).slice(0, 8);
  out.worstBlank = worst;
  document.documentElement.style.scrollBehavior = prevBehavior;
  return out;
};

/* ---------------------------------------------------------------
   Audit dell'header — C1..C7 (giro 2 sulle correzioni di testata).
   Misura la pagina resa. Ritorna solo numeri e booleani: va letto
   da `page.evaluate`, non stampato a schermo.
   --------------------------------------------------------------- */

window.sdVisible = function (el) {
  if (!el) return false;
  const cs = getComputedStyle(el);
  const r = el.getBoundingClientRect();
  return cs.display !== 'none' && cs.visibility !== 'hidden' && cs.opacity !== '0' &&
         r.width > 0 && r.height > 0;
};

/* Apre un pannello senza il cursore: ricalca la regola di `:hover`
   (core mette `display`, il tema visibilita'/opacita'), cosi' la
   geometria misurata e' quella vera. */
window.sdForceOpen = function (li) {
  let s = document.getElementById('sd-audit-open');
  if (!s) { s = document.createElement('style'); s.id = 'sd-audit-open'; document.head.appendChild(s); }
  s.textContent = li ? `.sd-audit-open > .wp-block-navigation__submenu-container{
      display:flex!important;visibility:visible!important;opacity:1!important;transform:none!important;}` : '';
  document.querySelectorAll('.sd-audit-open').forEach(e => e.classList.remove('sd-audit-open'));
  if (li) li.classList.add('sd-audit-open');
};

window.sdHeaderAudit = function () {
  const num = v => Math.round(v * 100) / 100;
  const cont = document.querySelector('.sd-header__nav .wp-block-navigation__container');
  const tops = [...cont.children].filter(li => getComputedStyle(li).display !== 'none');
  const logo = document.querySelector('.sd-header__logo');
  const out = { w: innerWidth };

  // --- C1: 16px dappertutto, e mai una sottovoce piu' grande del suo capo
  const sizes = [];
  tops.forEach(li => {
    const a = li.querySelector(':scope > .wp-block-navigation-item__content');
    if (!a) return;
    const pfs = parseFloat(getComputedStyle(a).fontSize);
    const subs = [...li.querySelectorAll('.wp-block-navigation__submenu-container .wp-block-navigation-item__content')]
      .map(s => parseFloat(getComputedStyle(s).fontSize));
    sizes.push({ p: pfs, subMax: subs.length ? Math.max(...subs) : null, n: subs.length });
  });
  out.C1 = {
    topSizes: [...new Set(sizes.map(s => s.p))],
    subSizes: [...new Set(sizes.map(s => s.subMax).filter(v => v !== null))],
    allTop16: sizes.every(s => s.p === 16),
    allSub16: sizes.every(s => s.subMax === null || s.subMax === 16),
    noSubBiggerThanParent: sizes.every(s => s.subMax === null || s.subMax <= s.p),
    pass: sizes.every(s => s.p === 16 && (s.subMax === null || s.subMax === 16))
  };

  // --- C2: una riga sola, e il marchio non viene toccato
  const rows = [...new Set(tops.map(li => Math.round(li.getBoundingClientRect().top)))];
  const lr = logo.getBoundingClientRect();
  const firstLeft = Math.min(...tops.map(li => li.getBoundingClientRect().left));
  out.C2 = { rows: rows.length, logoRight: num(lr.right), navFirstLeft: num(firstLeft),
             gapToLogo: num(firstLeft - lr.right),
             pass: rows.length === 1 && firstLeft >= lr.right };

  // --- C3: niente testo fuori dal pannello, niente pannello fuori dalla finestra
  const bad = [];
  let panels = 0;
  tops.filter(li => li.classList.contains('has-child')).forEach(li => {
    window.sdForceOpen(li);
    const p = li.querySelector(':scope > .wp-block-navigation__submenu-container');
    if (!p) return;
    panels++;
    const pr = p.getBoundingClientRect();
    const cs = getComputedStyle(p);
    const inL = pr.left + parseFloat(cs.paddingLeft), inR = pr.right - parseFloat(cs.paddingRight);
    [...p.querySelectorAll(':scope > li > .wp-block-navigation-item__content')].forEach(a => {
      const r = a.getBoundingClientRect();
      const over = a.scrollWidth > a.clientWidth + 1;
      if (over || r.right > inR + 1 || r.left < inL - 1)
        bad.push({ t: a.textContent.trim().slice(0, 30), sw: a.scrollWidth, cw: a.clientWidth,
                   right: num(r.right), inR: num(inR) });
    });
    if (pr.right > innerWidth + 1 || pr.left < -1)
      bad.push({ panel: li.textContent.trim().slice(0, 14), right: num(pr.right), vw: innerWidth });
  });
  window.sdForceOpen(null);
  out.C3 = { panels, offenders: bad.slice(0, 6), pass: bad.length === 0 };

  // --- C5: i pulsanti della barra non toccano i bordi
  const act = document.querySelector('.sd-header__actions');
  const top = document.querySelector('.sd-header__top');
  if (act && top) {
    const ar = act.getBoundingClientRect(), tr = top.getBoundingClientRect();
    const btns = [...act.querySelectorAll('.sd-btn')].map(b => b.getBoundingClientRect());
    const above = Math.min(...btns.map(b => b.top - tr.top));
    const below = Math.min(...btns.map(b => tr.bottom - b.bottom));
    out.C5 = { above: num(above), below: num(below), barH: num(tr.height), pass: above > 0 && below > 0 };
  }
  return out;
};

/* C4 — va chiamata mentre la voce e' davvero sotto il cursore o a fuoco:
   una sottolineatura sola, sotto l'etichetta, e niente riga sulla freccia. */
window.sdUnderlineProbe = function (li) {
  const num = v => Math.round(v * 100) / 100;
  const a = li.querySelector(':scope > .wp-block-navigation-item__content');
  const t = li.querySelector(':scope > .wp-block-navigation-submenu__toggle');
  const line = (el) => {
    if (!el) return null;
    const af = getComputedStyle(el, '::after'), be = getComputedStyle(el, '::before');
    const cs = getComputedStyle(el);
    const seg = (p) => p.content !== 'none' && parseFloat(p.width) > 0 && parseFloat(p.height) > 0;
    return {
      afterW: af.content !== 'none' ? num(parseFloat(af.width) || 0) : 0,
      hasAfter: seg(af), hasBefore: seg(be),
      bb: parseFloat(cs.borderBottomWidth) || 0,
      bt: parseFloat(cs.borderTopWidth) || 0,
      deco: cs.textDecorationLine,
      shadow: cs.boxShadow === 'none' ? 0 : 1,
      bgImg: cs.backgroundImage === 'none' ? 0 : 1,
      w: num(el.getBoundingClientRect().width)
    };
  };
  const L = line(a), T = line(t);
  // larghezza reale del testo dell'etichetta
  const rg = document.createRange(); rg.selectNodeContents(a);
  const labelW = num(rg.getBoundingClientRect().width);
  const toggleClean = !T || (!T.hasAfter && !T.hasBefore && T.bb === 0 && T.bt === 0 &&
                             T.deco === 'none' && T.shadow === 0 && T.bgImg === 0);
  // quante righe porta l'etichetta: ne e' ammessa al massimo una
  const linkLines = (L.hasAfter ? 1 : 0) + (L.hasBefore ? 1 : 0) + (L.bb > 0 ? 1 : 0);
  return {
    label: a.textContent.trim().slice(0, 20),
    linkAfterW: L.afterW, labelW, linkFs: num(parseFloat(getComputedStyle(a).fontSize)),
    underlineMatchesLabel: L.afterW > 0 && Math.abs(L.afterW - L.w) <= 2,
    toggle: T, toggleClean, linkLines,
    active: L.afterW > 0,
    pass: toggleClean && linkLines <= 1
  };
};

/* C6/C7 — le azioni sul telefono: ciascuna una volta sola. */
window.sdMobileCta = function () {
  const panel = document.querySelector('.sd-header .wp-block-navigation__responsive-container');
  const isOpen = panel && panel.classList.contains('is-menu-open');
  const inPanel = (el) => isOpen && panel.contains(el);
  const scopes = [...document.querySelectorAll('.sd-header, .sd-mobile-cta')];
  const all = scopes.flatMap(sc => [...sc.querySelectorAll(
      'a[href^="tel:"], a[href*="#prenota"], a[href*="/contatti"], a[href*="whatsapp"]')])
    .filter(window.sdVisible);
  const classify = (a) => a.getAttribute('href').startsWith('tel:') ? 'tel'
    : /#prenota/.test(a.href) ? 'book'
    : /whatsapp/.test(a.href) ? 'whatsapp' : 'contacts';
  const outside = all.filter(a => !inPanel(a)).map(a => ({ k: classify(a), t: a.textContent.trim().slice(0, 22) }));
  const inside = all.filter(a => inPanel(a)).map(a => ({ k: classify(a), t: a.textContent.trim().slice(0, 22) }));
  const cnt = (arr, k) => arr.filter(x => x.k === k).length;
  return {
    menuOpen: !!isOpen,
    outside, inside,
    C6: { tel: cnt(outside, 'tel'), book: cnt(outside, 'book'),
          contacts: cnt(outside, 'contacts') + cnt(outside, 'whatsapp'),
          pass: cnt(outside, 'tel') === 1 && cnt(outside, 'book') === 0 &&
                cnt(outside, 'contacts') + cnt(outside, 'whatsapp') === 0 },
    C7: { book: cnt(inside, 'book'), contacts: cnt(inside, 'contacts') + cnt(inside, 'whatsapp'),
          pass: cnt(inside, 'book') >= 1 && cnt(inside, 'contacts') + cnt(inside, 'whatsapp') >= 1 }
  };
};
