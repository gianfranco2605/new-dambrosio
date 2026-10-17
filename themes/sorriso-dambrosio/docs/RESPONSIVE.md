# RESPONSIVE — mappa per il lavoro su layout e CSS

Tutto quello che serve per toccare layout e responsive **senza leggere `style.css` per intero**
(4.643 righe, 140 KB). Aggiornato: 2026-10-17, tema **1.7.0**.

## 1. Mappa di `style.css`

Trovare la sezione con `grep -n '^   5\.' style.css` (o il titolo), poi `Read` con `offset`/`limit`.
I numeri di riga sono indicativi: cambiano a ogni modifica.

| § | Sezione | riga ~ |
| --- | --- | --- |
| 1 | Misure del sistema (`--sd-max`, `--sd-gutter`, `--sd-rhythm`, `.sd-shelf`, `--sd-bleed`) | 26 |
| 2 | Tipografia di dettaglio | 92 |
| 3 | Intestazione di sezione | 143 |
| 4 | Hero | 287 |
| 5 | Header (5.1 piano di sopra · 5.2 logo e nav · 5.2b stacco voci · 5.3 voce+freccia · 5.4 soglia tendina · 5.4b azioni su telefono · 5.5 sottomenu) | 602 |
| 6 | Lo Studio / I Nostri Pazienti | 1301 |
| 7 | I Professionisti | 1560 |
| 8 | Nostri servizi | 1635 |
| 9 | Tecnologie | 1718 |
| 10 | Perche' noi | 1837 |
| 11 | Orari e Vieni a Trovarci (mappa) | 1892 |
| 12 | Tabella orari | 2347 |
| 14 | CTA mobile | 2426 |
| 15 | Footer | 2475 |
| 16 | Utility ereditate (`.is-style-hidden-*` — **non rimuovere**) | 2500 |
| 17 | Accessibilita' e igiene | 2521 |
| 18 | PAGINE INTERNE: 18.1 briciole · 18.2 intestazione area · 18.3 zig-zag · 18.4 righe · 18.5 matrice · 18.6 scheda · 18.7 pagina/404 · 18.8 professionisti · 18.9 casi prima/dopo · 18.10 medico · 18.11 indice aree · 18.12 fascia prenota · 18.13 contatti · 18.14 testo area/FAQ · 18.15 Lo Studio pagina | 2560 |
| 19 | REGOLE GLOBALI: 19.1 bersagli 44px · 19.2 testo ≥16px mobile · 19.3 misura · 19.4 pulsante · 19.5 orfane · 19.6 lastra · 19.7 bersagli mobile | 3636 |
| 20 | MENU MOBILE: 20.1 pannello · 20.2 elenco · 20.3 azioni · 20.4 mappa · 20.5 tetto cornici · 20.6 mappa bloccata | 3994 |

Nota: 19.7, 20.5 e 20.6 stanno **in fondo** al file (dopo 20.4), non vicino alle loro sezioni.

## 2. Breakpoint in uso (debito noto)

Non c'e' una scala unica: sono cresciuti sezione per sezione.

| media query | volte | significato |
| --- | --- | --- |
| `max-width: 599px` / `600px` | 9 + 2 | telefono (soglia di core per la Navigation) |
| `max-width: 781px` | 5 | soglia colonne di core (`wp-block-columns` impilano a 781) |
| `max-width: 860px` | 6 | |
| `max-width: 899px` / `900px` | 7 + 5 | tablet |
| `max-width: 1023px` | 3 | |
| `max-width: 1120px` | 2 | **menu a tendina fino a 1120px** (core lo farebbe solo sotto 600) |
| (header) 1340px | — | stacco voci del menu a due gradini (`--sd-nav-gap`) |
| `prefers-reduced-motion` | 11 | |

Piano di unificazione, compito per compito: `docs/TASKS.md` T1–T5.

**Regola per il lavoro nuovo:** non aggiungere soglie nuove. Riusare `599 / 781 / 899 / 1120`.
Se si unificano 860→899 o 600→599, verificare la sezione a entrambe le larghezze.
Larghezze di controllo: **390 · 768 · 1024 · 1280 · 1440**.

## 3. Sistema di design (token da usare — mai valori a mano)

- **Spaziature** `var(--wp--custom--space--…)`: `xxxs` 4→6 · `xxs` 8→12 · `xs` 12→16 · `sm` 16→24 ·
  `md` 20→32 · `lg` 28→44 · `xl` 36→60 · `xxl` 48→88 · `xxxl` 56→112 (390px→1440px).
  I nomi sono **solo lettere**: una chiave `2xl` diventerebbe `--…--2-xl` e il `var()` fallirebbe in silenzio.
- **Misure di lettura** `settings.custom.measure`: `prose` 38rem, `narrow` 34rem, `menu` 24rem, `thumb`. Mai `ch`.
- **Proporzioni** `settings.custom.frame`: `hero` 16/9 · `wide` 3/2 · `plate` 4/3 · `portrait` 4/5.
- **Layout**: `contentSize` 1180px, `wideSize` 1320px; a 1440 il contenuto ≥ 1100px.
- **Colori**: solo preset di `theme.json` (`surface--tint`, `surface--tintStrong` = primary 8%/14%).
- **Font**: Fraunces (`secondary`, h1/h2), DM Sans (`primary`, testo/h3). Preset `nav` 16px `fluid:false`.
- Verifica a fine lavoro (tutti devono dare 0):
  ```bash
  grep -cE '(^|[^&])#[0-9a-fA-F]{3,8}\b|rgba?\(|hsla?\(' style.css
  grep -c 'clamp(' style.css
  grep -cE 'max-width: *[0-9.]+rem' style.css
  ```

## 4. Componenti e regole fisse

- Bersagli **≥ 44×44**. Per allargare senza spostare: `padding-block` + `margin-block` negativo, o `::after`.
- Testo **≥ 16px** su mobile.
- **Un pulsante**: pillola `999px`, `x-small` 600, min 44px (anche CF7 e `wp-block-button__link`).
- **Lastra immagini**: `Render::thumb()` (plugin) + `.sd-plate`. `cover` se l'originale ≥ slot×1,6, altrimenti
  `contain` e **mai ingrandito**. Ritratti `--sd-focus: 50% 18%`. Senza immagine la riga va a colonna piena.
- Alternanza immagine/testo nelle griglie: scambiare `grid-template-areas`, non `order`.
- Sanguinamento: `--sd-bleed` (misura sul viewport), non `calc(50% - 50vw)` dentro una cella.
- Header sticky → le ancore vogliono `scroll-margin-top`.
- `!important` solo nei 3 casi commentati (preset font-size di core, fogli CF7 e iubenda).

## 5. Header e menu mobile — cosa si e' imparato (giri 1–2)

- Il menu e' il blocco **Navigation** di core (post `wp_navigation` **ID 4**, 7 voci + 41 sottovoci).
  Core gestisce apertura, focus, scroll lock. Il tema fa solo il **disegno** (§5 e §20) + `assets/js/menu.js` (Escape).
- **Tendina fino a 1120px**: il tema rimette le regole di core `.wp-block-navigation__responsive-container`
  (e `…-container-open`) al comportamento mobile.
- Stacco voci: **una variabile**, `--sd-nav-gap` (28px, 16px sotto 1340). Non usare `blockGap` nel pattern:
  la regola di core (0,1,0) perde contro il tema e "non succede nulla".
- Voci del pannello: `white-space: normal` + `overflow-wrap` (il `nowrap` della barra si ereditava e faceva sbordare
  «Software di Progettazione…»). Pannello `width: max-content`, tetto `measure--menu`.
- Riga di una voce con tendina = **griglia a due colonne**, non flex (col flex il sottomenu usciva dallo schermo).
- Sottolineatura `::after` **solo sul link**, mai sul `<button>` della freccia.
- Pulsanti header: 13px, pillola 36px, bersaglio 44px via `::after`; solo ≥782px.
- **Sotto 1120px**: il piano di sopra si spegne, la riga del marchio ha **una** icona telefono; nel pannello
  «Prenota» + «Contatti». La barra fissa `.sd-mobile-cta` e' **ritirata** (regola commentata; si riaccende
  con `display: flex`). WhatsApp ora e' raggiungibile solo da «Contatti» — decisione aperta del titolare.
- Tastiera: il `<li>` ha `open-on-hover-click`, quindi il pannello si apre con **Invio sulla freccia**, non con
  `:focus-within`. E' il comportamento di core, non un bug.
- Override dal DB: **nessuno** (template part 842 e' del tema vecchio; global styles 2061 vuoto). I file sono la sola fonte.

## 6. Trappole di CSS/block theme

- Layout `constrained` di core: `margin-inline:auto` + `max-width: contentSize` sui figli. Rimedi: `alignwide`,
  `layout:{"type":"default"}`, o override.
- Group con layout emette `display:flex`: per nasconderlo serve `display:none !important`.
- Gli stili inline dagli attributi del blocco battono il CSS del tema: non impostare nel blocco cio' che il CSS deve governare.
- Ollie da' alle tabelle fondo `base` (bianco su bianco su fondo scuro): vedi `.sd-footer .sd-hours`.
- CF7: la classe sta **sul** `<form>` (`form.sd-form-wrapper`); i selettori partono da `.sd-prenota form.wpcf7-form`.
- **Pattern modificato o nuovo non compare** → alzare `Version:` in `style.css`.
- Pagina vecchia dopo una modifica → `/?cb=1` o `wp cache flush`.
- Il markup dei blocchi `sd/*` (classi, struttura) sta nel plugin: `../../plugins/sorriso-dambrosio-core/blocks/`
  e `includes/class-render.php`. Leggerlo **solo** se la classe da stilare non si trova nel tema.

## 7. Verificare una modifica di layout (economico)

1. `curl -s -o /dev/null -w '%{http_code}\n' "http://sorrisodambrosio.local/<pagina>/?cb=1"` → 200.
2. Una sola schermata Playwright, **solo la pagina e la larghezza toccate** (prima chiudere iubenda:
   `document.querySelector('.iubenda-cs-accept-btn')?.click()`; per le immagini lazy scorrere prima di scattare).
3. Overflow orizzontale senza screenshot:
   `document.documentElement.scrollWidth > innerWidth` (via `browser_evaluate`).
4. Lint solo se si tocca PHP: `"$PHP83" -n -l <file>`.

Criteri di accettazione completi (hero sopra la piega, nessuna fascia vuota > 200px, icone ≤ 56px, h1 ≥ 56px desktop):
`docs/DESIGN-SPEC.md` §6. Da controllare **prima di un commit**, non a ogni ritocco.
