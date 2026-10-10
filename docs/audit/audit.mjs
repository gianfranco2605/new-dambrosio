import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE = 'http://sorrisodambrosio.local';
const urls = fs.readFileSync(process.argv[2], 'utf8').trim().split('\n');
const outDir = process.argv[3];
const widths = (process.argv[4] || '1440,768,390').split(',').map(Number);
const shoot = process.argv[5] !== 'no-shots';
fs.mkdirSync(outDir, { recursive: true });

const slug = u => (u === '/' ? 'home' : u.replace(/^\/|\/$/g, '').replace(/\//g, '--')) || 'home';

const METRICS = () => {
  const vw = window.innerWidth;
  const inMain = el => el.closest('main') !== null;
  const vis = el => {
    const s = getComputedStyle(el);
    if (s.display === 'none' || s.visibility === 'hidden' || +s.opacity === 0) return false;
    const b = el.getBoundingClientRect();
    return b.width > 2 && b.height > 2;
  };

  // --- immagini di contenuto
  const images = [...document.querySelectorAll('main img')]
    .filter(i => vis(i) && !i.closest('.sd-header, .sd-footer, .bafg-slider-info'))
    .map(i => {
      const b = i.getBoundingClientRect();
      const s = getComputedStyle(i);
      return {
        src: (i.currentSrc || i.src).split('/').pop().split('?')[0],
        w: Math.round(b.width), h: Math.round(b.height),
        nw: i.naturalWidth, nh: i.naturalHeight,
        fit: s.objectFit, pos: s.objectPosition,
        ar: +(b.width / Math.max(1, b.height)).toFixed(3),
        nar: i.naturalHeight ? +(i.naturalWidth / i.naturalHeight).toFixed(3) : 0,
        ratio: +(i.naturalWidth / Math.max(1, b.width)).toFixed(2),
        // il RUOLO, non il modificatore della lastra: due lastre `cover`
        // in due griglie diverse non devono confrontarsi fra loro
        parent: (i.closest('figure,article,div')?.className || '').toString().split(' ')
          .filter(c => c.startsWith('sd-') && c !== 'sd-plate' && !c.startsWith('sd-plate--')).slice(0, 2).join('.'),
        // La proporzione che si vede e' quella della CORNICE, non quella
        // dell'immagine: dentro una lastra `contain` i ritagli hanno
        // proporzioni diverse fra loro ma la griglia resta allineata.
        far: (() => { const f = i.closest('.sd-plate, figure'); if (!f) return 0; const r = f.getBoundingClientRect(); return +(r.width / Math.max(1, r.height)).toFixed(3); })(),
        cls: (i.className || '').toString().slice(0, 60),
      };
    });

  // --- vuoti verticali fra blocchi di contenuto visibili
  const blocks = [...document.querySelectorAll('main section, main > div > div, main .sd-wrap > *, main article, main figure, main h1, main h2, main h3, main p, main ul, main img, main iframe, main form')]
    .filter(vis)
    .map(el => { const b = el.getBoundingClientRect(); return { t: Math.round(b.top + scrollY), b: Math.round(b.bottom + scrollY), tag: el.tagName, cls: (el.className || '').toString().split(' ').filter(c => c.startsWith('sd-')).slice(0, 2).join('.') }; })
    .sort((a, b) => a.t - b.t);
  const gaps = [];
  let cursor = blocks.length ? blocks[0].b : 0;
  let prev = blocks[0];
  for (const b of blocks) {
    if (b.t - cursor > 40) gaps.push({ from: cursor, size: Math.round(b.t - cursor), after: `${prev?.tag}.${prev?.cls}`, before: `${b.tag}.${b.cls}` });
    if (b.b > cursor) { cursor = b.b; prev = b; }
  }

  // --- sezioni vuote
  const emptySections = [...document.querySelectorAll('main section, main .sd-section')]
    .filter(s => vis(s) && s.innerText.trim().length < 3 && !s.querySelector('img,iframe,svg'))
    .map(s => (s.className || '').toString().slice(0, 70));

  // --- tap target e tipografia
  const tapSmall = vw <= 600 ? [...document.querySelectorAll('a, button, input[type=submit], select, summary')]
    .filter(vis)
    .filter(e => { const b = e.getBoundingClientRect(); return (b.height < 44 || b.width < 24) && e.closest('main, .sd-header, .sd-footer, .sd-mobile-cta'); })
    .map(e => ({ t: e.innerText.trim().slice(0, 28) || e.getAttribute('aria-label') || e.tagName, h: Math.round(e.getBoundingClientRect().height), w: Math.round(e.getBoundingClientRect().width), cls: (e.className || '').toString().slice(0, 40) }))
    .slice(0, 25) : [];

  const smallText = vw <= 600 ? [...document.querySelectorAll('main p, main li, main td, main label, main span')]
    .filter(el => vis(el) && el.innerText.trim().length > 20)
    .map(el => ({ px: parseFloat(getComputedStyle(el).fontSize), t: el.innerText.trim().slice(0, 30), cls: (el.className || '').toString().slice(0, 36) }))
    .filter(x => x.px < 16).slice(0, 15) : [];

  // --- lunghezza di riga su desktop (approssimata dai caratteri per riga)
  const longLines = vw >= 1024 ? [...document.querySelectorAll('main p, main li')]
    .filter(el => vis(el) && el.innerText.trim().length > 90)
    .map(el => {
      const st = getComputedStyle(el);
      const cw = parseFloat(st.fontSize) * 0.5; // DM Sans: larghezza media ~0.5em
      const chars = Math.round(el.getBoundingClientRect().width / cw);
      return { chars, px: Math.round(el.getBoundingClientRect().width), cls: (el.className || '').toString().slice(0, 40), t: el.innerText.trim().slice(0, 28) };
    }).filter(x => x.chars > 75).slice(0, 12) : [];

  // --- overflow orizzontale
  const overflow = [...document.querySelectorAll('body *')]
    .filter(e => { const b = e.getBoundingClientRect(); return b.width > 0 && (b.right > vw + 1 || b.left < -1) && !e.className.toString().includes('iub'); })
    .slice(0, 6).map(e => `${e.tagName}.${(e.className || '').toString().split(' ').slice(0, 2).join('.')}`);

  // --- intestazioni: orfani e scala
  const headings = [...document.querySelectorAll('main h1, main h2, main h3')].filter(vis).map(h => {
    const words = h.innerText.trim().split(/\s+/);
    return { lvl: +h.tagName[1], px: Math.round(parseFloat(getComputedStyle(h).fontSize)), words: words.length, last: words[words.length - 1] || '', t: h.innerText.trim().slice(0, 44), w: Math.round(h.getBoundingClientRect().width) };
  });

  // --- padding di sezione e stili dei componenti
  const sectionPads = [...document.querySelectorAll('main .sd-section, main section')].filter(vis).map(s => {
    const st = getComputedStyle(s);
    return { cls: (s.className || '').toString().split(' ').filter(c => c.startsWith('sd-section')).join('.'), pt: Math.round(parseFloat(st.paddingTop)), pb: Math.round(parseFloat(st.paddingBottom)), bg: st.backgroundColor };
  });

  const buttons = [...document.querySelectorAll('main .sd-btn, main .wp-block-button__link, main input[type=submit], main .wpcf7-submit')].filter(vis).map(b => {
    const s = getComputedStyle(b);
    return { cls: (b.className || '').toString().split(' ').filter(c => c.startsWith('sd-') || c.startsWith('wpcf7') || c.startsWith('wp-block')).slice(0, 2).join('.'), h: Math.round(b.getBoundingClientRect().height), r: s.borderRadius, bg: s.backgroundColor, fs: Math.round(parseFloat(s.fontSize)), px: s.paddingLeft };
  });

  const icons = [...document.querySelectorAll('svg.sd-icon')].filter(vis).map(i => Math.round(Math.max(i.getBoundingClientRect().width, i.getBoundingClientRect().height)));

  // Le fasce occupate da un'immagine o da una mappa: servono al
  // rilevatore di spazi vuoti, che lavora sui pixel e non saprebbe
  // distinguere una fotografia chiara dal nulla.
  const occupato = [...document.querySelectorAll('img, iframe, video, svg')]
    .filter(vis)
    .map(e => { const b = e.getBoundingClientRect(); return [Math.round(b.top + scrollY), Math.round(b.bottom + scrollY)]; });

  return {
    vw, docW: document.documentElement.scrollWidth, height: Math.round(document.body.scrollHeight), occupato,
    images, gaps, emptySections, tapSmall, smallText, longLines, overflow, headings, sectionPads, buttons,
    iconsMax: icons.length ? Math.max(...icons) : 0,
    h1: document.querySelectorAll('main h1').length,
  };
};

const b = await chromium.launch();
const all = {};
for (const u of urls) {
  all[u] = {};
  for (const w of widths) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, deviceScaleFactor: 1 });
    const p = await ctx.newPage();
    try {
      await p.goto(BASE + u, { waitUntil: 'load', timeout: 45000 });
      await p.evaluate(() => { const a = document.querySelector('.iubenda-cs-accept-btn'); if (a) a.click(); });
      await p.waitForTimeout(350);
      await p.evaluate(async () => { const h = document.body.scrollHeight; for (let y = 0; y < h; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 70)); } window.scrollTo(0, 0); });
      await p.waitForTimeout(1600);
      all[u][w] = await p.evaluate(METRICS);
      if (shoot) await p.screenshot({ path: path.join(outDir, `${slug(u)}-${w}.png`), fullPage: true });
    } catch (e) { all[u][w] = { error: e.message }; }
    await ctx.close();
  }
  process.stderr.write('.');
}
await b.close();
fs.writeFileSync(path.join(outDir, '..', `metrics-${path.basename(outDir)}.json`), JSON.stringify(all, null, 1));
console.log('\nfatto:', urls.length, 'url ×', widths.length, 'larghezze');
