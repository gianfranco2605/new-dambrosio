# TRIAGE — Redesign homepage Studio Odontoiatrico D'Ambrosio

Data: 2026-10-05 · Sito locale: `http://sorrisodambrosio.local` (confermato con `wp option get siteurl`)
WordPress 7.1.2 · PHP runtime del sito 7.4.30 (Local lightning-services) · WP-CLI 2.8.1

---

## 1. Stato attuale

### Tema
| | |
|---|---|
| Attivo | `sorrisodambrosio` v1.1.0 — **block theme standalone**, non un child |
| Natura | fork/copia di Ollie (contiene gli stessi ~70 pattern di Ollie) |
| theme.json | schema **v2** |
| Font | Montserrat (variable **.ttf**, 1 famiglia) + Inter .woff2 presente ma non dichiarato |
| Nota | ha un **repo git annidato** in `themes/sorrisodambrosio/.git` |

`themes/ollie` è stato installato da WordPress.org (v1.6.3, theme.json **v3**) come parent del nuovo child.
Il tema vecchio resta installato e intatto: il rollback è una semplice riattivazione.

### Plugin attivi (11 + 1 mu)
`aruba-hispeed-cache`, `beaf-before-and-after-gallery` 4.7.18, `dominant-color-images`,
`iubenda-cookie-law-solution`, `worker`, `webp-uploads`, `performance-lab`, `popup-maker`,
`google-site-kit`, `duplicate-post`, `wordpress-seo`; mu-plugin `0-worker`.

### Pagine e front page
`show_on_front = page`, `page_on_front = 57` ("Home Page", slug `studio-dentistico`), `page_for_posts = 0`.
Altre pagine pubblicate: Implantologia (32), Ortodonzia (34), Estetica Dentale (36), Trattamenti (38),
Denti e postura (40), Tecnologie (46), Il Nostro Studio (48), Casi Studi (1619), 70 anni attività (1874), 404 (1436).

### Come è costruita la homepage
Solo **blocchi core** — nessun page builder, nessuno shortcode nel contenuto della pagina.
La home è composta da **tre sorgenti**:

1. **Template `front-page`** — post `wp_template` **nel DB** (ID 800), non un file del tema.
   Contiene hero, sezione Contatti/mappa, Tecnologie, Perché noi, e richiama le parti header/footer.
2. **Contenuto della pagina 57** — inserito da `wp:post-content`.
3. **Due blocchi riusabili** (`wp_block`): **1454** "lo studio" e **1461** "chi-siamo" (I Professionisti).

Parti template, anch'esse nel DB: `header` (842), `footer` (961).
Il menu è un blocco Navigation che punta al post `wp_navigation` **ID 4** (esiste, popolato: 8 voci di
primo livello con 6 sottomenu + social links). **`wp menu` non ha effetto su questo.**

### Plugin before/after (BEAF)
- Plugin: `beaf-before-and-after-gallery` 4.7.18 — CPT `bafg`, shortcode `[bafg]`, `[bafg_gallery]`, `[bafg_preview]`.
- 8 gallerie pubblicate (casi ortodonzia e casi dott. Giuseppe).
- Inserimento: blocchi `wp:shortcode` con `[bafg id=...]` **dentro il contenuto delle pagine**.
- **Non è presente nella homepage.** Compare su `casi-studi`, `ortodonzia`, `estetica-dentale`.
- Conseguenza: essendo guidato dal contenuto e non dal tema, il cambio tema **non lo tocca**.
  Nel nuovo layout della home non va integrato perché non c'era. Verificato comunque dopo il cambio tema.

### Palette attuale (da `themes/sorrisodambrosio/theme.json`) — DA RIPRODURRE ESATTAMENTE
| slug | hex | nome |
|---|---|---|
| `primary` | `#1262fa` | Primary |
| `main` | `#39383A` | Main |
| `secondary` | `#636381` | Secondary |
| `main-accent` | `#d0ccde` | Main Accent |
| `primary-accent` | `#a51212` | Primary Accent |
| `tertiary` | `#bcc9dd` | Tertiary |
| `base` | `#fcfcfc` | Base |

Colori accessori presenti nel CSS/contenuto attuale (non fanno parte della palette, ma servono per
riprodurre il look — andranno in `settings.custom` di `theme.json`, mai in file CSS):
`#ff0d00` (barra accento "florence-gradient"), `#e2e2ef` e `#e3e3ea` (bordo card), `#ffffff`
(`.has-base-background-color` è forzato a bianco puro nel tema attuale).

Gradients e duotone: quelli di default di Ollie, invariati.

### Tipografia attuale
Montserrat 100–700 (variable TTF locale) + fluid type scale a 8 gradini.
**Nessun riferimento a `fonts.googleapis.com` / `fonts.gstatic.com`** già oggi: il GDPR è già rispettato.

### Problemi rilevati nella home attuale
- **Due `<h1>` identici**: hero desktop (`is-style-hidden-mobile`) e hero mobile (`is-style-hidden-destok`)
  sono due blocchi Cover duplicati, nascosti via CSS. Il testo è duplicato nel DOM.
- Il banner cookie iubenda copre l'hero al primo caricamento.
- Molti valori di colore hardcoded inline nel contenuto (`#e2e2ef`, `#1262fa`, `#64648254`).
- Testo con spaziature anomale residue dall'editing (`"Dal 1956       "`, indentazioni dentro i paragrafi).

---

## 2. Testo originale

Salvato in `homepage-text-original.txt` — 199 frammenti estratti **in ordine DOM dalla pagina resa**
(header + main + footer), non dai sorgenti. La tipografia è quella prodotta da `wptexturize`
(apostrofi curvi `’`, en-dash `–`): i pattern nuovi useranno esattamente quei caratteri così che
la verifica di presenza sia un confronto letterale.

Copie di servizio nella stessa cartella: `homepage-raw-content.html`, `front-page-template-original.txt`,
`header-original.txt`, `footer-original.txt`.

### Recapiti disponibili (solo questi, nessun dato inventato)
- `studio@sorrisodambrosio.it`
- Tel +39 06 322 5754 · Tel +39 06 361 0625 · Cel +39 375 552 4596
- WhatsApp: `https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0`
- Lungotevere Flaminio n. 34 — 00196 — Roma (RM) · P.IVA 05429801003

---

## 3. Screenshot "prima"

`screenshots/before/` — `homepage-1440-full.png`, `homepage-1440-hero.png`, `homepage-390-full.png`,
`reference-prism-1440-hero.png`, `reference-prism-1440-full.png`, `reference-prism-390-full.png`.

Cosa si prende dal riferimento Prism: hero **allineato a sinistra** con occhiello + titolo display grande
+ paragrafo di supporto; **scheda contatti che sborda sul bordo inferiore dell'hero**; ritmo verticale ampio.
Non si copiano testi, immagini né il suo stile cromatico.

---

## 4. Direzione visiva

L'angolo distintivo non è "tecnologia dentale" ma **la continuità dal 1956**: uno studio romano di tre
generazioni, in un edificio di design sul Tevere. Quindi un impianto **editoriale e architettonico**,
calmo, con molto bianco — non la card-grid clinica di default.

**Colore** — palette invariata, usata con parsimonia: `base` come fondo, `main` per il testo,
`primary` **solo** come accento (occhiello, filetto, focus, una sola CTA piena), `secondary` per il
testo secondario, `main-accent`/`tertiary` per superfici e divisori tenui. `primary-accent` resta
dichiarato ma non impiegato.

**Tipografia** — due famiglie nettamente distinte, entrambe **variable woff2 copiate dentro il child**:
- **Fraunces** (display: h1, h2) — serif caldo, old-style, con `WONK` disattivato per restare sobrio.
- **DM Sans** (testo, UI, h3) — grottesca morbida, molto leggibile.

Gli occhielli esistenti ("Dal 1956", "Dottori", "Prestazioni"…) sono **contenuto**, quindi restano:
vengono resi piccoli, in `primary`, **in caso naturale e non in maiuscoletto spaziato**, con il filetto
sotto — evitando il cliché dell'eyebrow tutto maiuscolo.

**Layout** — misura di lettura < 75 caratteri; split asimmetrici 7/5 per le sezioni narrative;
ritmo verticale sui preset spacing di Ollie. Il "colpo" visivo è concentrato in **un solo punto**:
il titolo dell'hero in Fraunces, grande e allineato a sinistra. Tutto il resto resta quieto.

**Motion** — solo CSS, una sola entrata orchestrata nell'hero, `prefers-reduced-motion` rispettato.
Nessun JS aggiunto.

---

## 5. Struttura prevista della nuova homepage

Un solo `<h1>`, gerarchia h1 → h2 → h3 senza salti.

| # | Sezione | Pattern | Testo di origine |
|---|---|---|---|
| — | Header | `parts/header.html` | logo 645 + Navigation **ref 4** + telefono |
| 1 | Hero | `hero` | occhiello "Gli specialisti del sorriso", **h1** "Studio Odontoiatrico D’Ambrosio", claim, CONTATTI → / CHI SIAMO → |
| 2 | Scheda contatti sovrapposta | `contact-bar` | indirizzo, telefoni, email (dal footer attuale) |
| 3 | Lo Studio | `lo-studio` | blocco riusabile 1454 |
| 4 | I Nostri Pazienti | `pazienti` | pagina 57 |
| 5 | I Professionisti | `professionisti` | blocco riusabile 1461 |
| 6 | Nostri servizi | `servizi` | pagina 57 (4 card) |
| 7 | Tecnologie | `tecnologie` | template front-page (7 card) |
| 8 | Perché noi | `perche-noi` | template front-page (4 colonne) |
| 9 | I Nostri Orari | `orari` | pagina 57 (tabella + "Sabato-Domenica chiuso") |
| 10 | Vieni a Trovarci | `contatti` | template front-page (mappa Google) |
| — | Footer | `parts/footer.html` | footer attuale |
| — | CTA mobile sticky | `parts/mobile-cta.html` | telefono + WhatsApp esistenti |

Riordino rispetto a oggi: **Orari** e **Vieni a Trovarci** scendono in fondo, vicino al footer, dove
chiudono il percorso "conosci lo studio → conosci i medici → cosa facciamo → come raggiungerci".
Nessun testo viene aggiunto, tolto o riscritto.

---

## 6. Vincoli operativi confermati

- PHP del sito **7.4**: niente `match`, union types, nullsafe, named args, promozione nel costruttore,
  `str_contains`/`str_starts_with`, enum, `readonly`. Lint con il binario 7.4 di Local, non con il PHP 8.3 del PATH.
- Mai toccare core, il tema `ollie`, i plugin, il tema `sorrisodambrosio`.
- La vecchia home (pagina 57) resta pubblicata e invariata: la nuova è una **pagina nuova** impostata come front page.
- Nessun hex/rgb nel child fuori da `theme.json`.
