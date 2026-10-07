# REPORT — Redesign homepage Studio Odontoiatrico D'Ambrosio

Data: 2026-10-06 (**terza iterazione**) · Ambiente: Local by Flywheel, `http://sorrisodambrosio.local`
WordPress 7.1.2 · **PHP del sito 8.3.17** · Tema attivo: `sorriso-dambrosio` 1.1.0 (child di `ollie` 1.6.3)

> La prima iterazione (2026-10-05) e' stata **bocciata**: riproduceva il layout del vecchio
> sito. Questo documento racconta che cosa era andato storto, che cosa e' cambiato e che cosa
> nel risultato nuovo non e' ancora all'altezza. L'autocritica e' al §6.

---

## 1. Perche' la prima versione e' stata bocciata

Il giudizio era: «riproduce il layout del vecchio sito (occhiello + titolo + filetto rosso +
griglie di card, stesso ordine delle sezioni)». Guardando la pagina resa, aveva ragione.

| Sintomo | Causa, trovata nel codice |
|---|---|
| Tutte le sezioni identiche | un solo `sorriso_dambrosio_section_head()` — occhiello, titolo, **filetto rosso** — ripetuto nove volte, seguito ogni volta da una griglia di card con bordo e ombra |
| Colonna di lettura strettissima | `contentSize: 740px`: a 1440 il testo occupava **736px su 1425**, meta' pagina vuota |
| Icone enormi | le icone dei servizi uscivano a **230 × 153px**, quelle di «Perche' noi» a 44px: due stili diversi, nessuno coerente |
| Hero debole | `brother-1` (**1260 × 838**) stirata a tutta larghezza, con un velo diagonale che la impastava |

La causa a monte non era tecnica: **i vincoli imponevano testo letterale nello stesso ordine
e i criteri di accettazione erano solo tecnici**. Con quei vincoli, riprodurre la struttura
del vecchio sito era il percorso di minima resistenza, e tutti i criteri passavano lo stesso.

Il primo commit di questa iterazione cambia proprio quello (`CLAUDE.md`): il testo resta
letterale, **ordine, raggruppamento e presentazione diventano liberi**, e ai criteri tecnici
si affiancano criteri di design misurabili sulla pagina resa.

---

## 2. Il riferimento, misurato

`https://www.prismoralsurgery.com/`, a 1440 e 390px (screenshot in `screenshots/ref/`).
L'analisi completa sta in **`DESIGN-SPEC.md` §1**. In breve:

- contenitore **1312px** a 1440 (margini 64), **24px** di margine a 390;
- hero alto **951px** con il titolo **in basso a sinistra**, non al centro;
- sezioni da 556 a 1672px, padding verticale 80 → 128px;
- `h1` 56 · `h2` 48 · `h3` 24 · testo 18/27;
- le immagini **escono dal contenitore** verso il bordo della finestra, da un lato solo;
- i fondi si alternano: crema → tinto → immagine → crema → tinto;
- i servizi non sono card ma una **matrice di celle divise da filetti**;
- **mai la stessa impaginazione due volte di fila**.

---

## 3. Che cosa e' stato costruito

Stessa architettura di prima — child theme di Ollie, homepage fatta di pattern PHP versionati,
pagina 2059 come front page — ma **tutti i pattern riscritti** su `DESIGN-SPEC.md`.

### 3.1 Il sistema

| | prima | ora |
|---|---|---|
| `contentSize` | 740px | **1180px** |
| `wideSize` | 1280px | **1320px** |
| Larghezza utile misurata a 1440 | 736px | **1320px** |
| `h1` / `h2` / testo | 84 / 46 / 18,6px | **72 / 50 / 19px** |
| Fondi | uno (`base`, piu' un «tinto» che era bianco puro) | **tre**: `base`, `tertiary`, `main` |
| Immagini in pagina | 27 | **12** |
| Icone | 8 PNG, fino a 230px | **nessuna** (restano i 3 SVG social del footer, 24px) |

Due pezzi nuovi di impianto:

- **`sorriso_dambrosio_section_head()` rifatto.** Il filetto corre **sopra** la sezione e
  l'occhiello gli si siede in coda, a destra; il titolo sta sotto, a sinistra. E' il contrario
  esatto della figura precedente. Il filetto rosso `#ff0d00` e' uscito da `theme.json`.
- **`.sd-shelf`**, una griglia a tre colonne con la colonna centrale larga quanto il
  contenitore: serve a far uscire un elemento verso il bordo della finestra senza margini
  negativi calcolati a mano, e si riusa nell'hero e nel capitolo «Lo Studio».

### 3.2 Le otto sezioni, otto impianti

| # | Sezione | Impianto | Fondo |
|---|---|---|---|
| 1 | Hero | fotografia intera + lastra opaca che sborda dal bordo sinistro | fotografia |
| 2 | Contatti | rail orizzontale a quattro celle divise da filetti | `main` |
| 3 | Lo Studio / I Nostri Pazienti | split 36/64, fotografia che riempie la colonna fino al bordo; sotto, tre colonne tipografiche | `base` |
| 4 | I Professionisti | tre righe 30/70 divise da filetti | `tertiary` |
| 5 | Nostri servizi | quattro pannelli fotografici a pieno sanguinamento | fotografie |
| 6 | Tecnologie | colonna sinistra con una sola fotografia a tutta altezza, sette righe a destra | `base` |
| 7 | Perche' noi | fascia scura di sola tipografia su quattro colonne | `main` |
| 8 | Orari / Vieni a Trovarci | mappa a tutta larghezza con pannello scuro sovrapposto | `base` + mappa |

Nessuna sezione riusa l'impianto di un'altra, e **nessuna riusa quello del vecchio sito**.

### 3.3 I quattro difetti segnalati

- **Hero.** Fotografia **636** (2500 × 1685) al posto di `brother-1` (1260 × 838). A 1440 il
  fattore di scala misurato e' **0,99**: nessun ingrandimento. Alta **780px = 86,7vh**, il
  titolo e' per intero sopra la piega. Il testo non sta piu' sopra l'immagine con un velo
  diagonale ma su una **lastra opaca** ancorata in basso a sinistra, che prosegue nella barra
  dei contatti: contrasto pieno, fotografia intatta.
  Sotto i 782px la lastra copriva meta' immagine e di quella verticale restava visibile solo
  il soffitto: li' l'immagine non fa piu' da fondo, sta sopra per intero e la lastra la segue.
- **Icone.** Tolte tutte e otto. Erano clipart blu generiche accanto a fotografie vere dello
  studio. La misura piu' grande rimasta in pagina e' **24px** (SVG social del footer).
- **Colonna di contenuto.** 736 → **1320px** a 1440.
- **Aree vuote.** La corsa verticale piu' alta senza nessun elemento di contenuto e' **190px**
  a 1440, 132px a 768 e a 390. Due difetti trovati e corretti in corso d'opera: la fotografia
  del capitolo e quella delle Tecnologie, messe in `position: sticky`, lasciavano sotto di se'
  un vuoto di 597 e 546px — in una schermata a pagina intera si vedeva benissimo. Ora
  riempiono l'altezza della colonna.

---

## 4. Criteri di accettazione

Verificati sul **sito in esecuzione** con Playwright, non leggendo i sorgenti.
Lo script di misura e' `wp-content/.playwright-mcp/audit.js`.

### 4.1 Design

| | Criterio | 1440 | 768 | 390 |
|---|---|---|---|---|
| ✅ | Hero: immagine visibile e titolo sopra la piega | sì · 86,7vh | sì · 82vh | sì · 46vh di foto + lastra |
| ✅ | Nessuna regione vuota piu' alta di 200px | **190px** | 132px | 132px |
| ✅ | Ogni icona ≤ 56px | **24px** | 24px | 24px |
| ✅ | Niente sborda dal proprio contenitore | 0 casi | 0 | 0 |
| ✅ | Contenuto principale ≥ 1100px a 1440 | **1320px** | — | — |
| ✅ | `h1` ≥ 56px su desktop | **72px** | 56,5px | 46px |
| ✅ | Scala netta h1 / h2 / testo | 72 / 50 / 19 | 56 / 40 / 18 | 46 / 33 / 17 |
| ✅ | Hero ≥ 80vh su desktop | 86,7vh | 82vh | — |
| ✅ | L'immagine dell'hero non viene ingrandita | scala 0,99 | 0,65 | 0,88 |

### 4.2 Tecnica (tutti i criteri della prima iterazione, rimisurati)

| | Criterio | Esito |
|---|---|---|
| ✅ | Homepage 200, nessun warning/notice PHP nell'HTML | 200; zero occorrenze |
| ✅ | `debug.log` vuoto dopo la visita | resta vuoto |
| ✅ | Un solo `<h1>`, nessun salto di livello | 1 `h1`; sequenza h1→h2→h3 senza salti, a tutte e tre le larghezze |
| ✅ | Nessun overflow orizzontale a 1440 / 768 / 390 | `scrollWidth` 1425/1440, 753/768, 375/390 |
| ✅ | Palette identica; nessun hex/rgb fuori da `theme.json` | 7 colori identici; scansione del tema (escluso `vendor/`): nessun valore altrove |
| ✅ | Nessun `fonts.googleapis.com` / `fonts.gstatic.com` | zero |
| ✅ | `wp-block-navigation` con link reali | `<nav>` presente, 49 link (ref 4) |
| ✅ | Plugin before/after funzionante | non era in homepage (ne' prima ne' ora). Su `/ortodonzia/`: 4 slider montati, nessun overflow |
| ✅ | Tutti gli `<img>` da `/uploads/` o dal child theme, tutti con `alt` | 12 su 12 (7 `alt` scritti ora: vedi `DA-VERIFICARE` §3) |
| ✅ | `width`/`height` su ogni immagine | 12 su 12 |
| ✅ | PHPStan livello 5 target 7.4 · `php -l` 7.4 | **No errors**; 11 file, tutti OK *(alla terza iterazione il target e' PHP 8.3: §11.5)* |
| ✅ | Contrasto del testo su tutte le fasce | minimo misurato **5,64:1** (testo secondario su `base`); su `tertiary` 6,96:1, su `main` 7,43:1 |
| ✅ | `git status` pulito fuori dai percorsi consentiti | traccia solo `.gitignore`, `.claude/settings.json`, `CLAUDE.md`, `themes/sorriso-dambrosio/` |

**Nota sul contrasto `tertiary`.** `#bcc9dd` e' una tinta chiara: il testo `secondary`
`#636381` ci farebbe **3,3:1**, sotto la soglia AA. Su quella fascia tutto il testo e' quindi
`main`. E' una regola del sistema, scritta in `style.css` accanto al selettore.

### 4.3 Performance

- **Nessun JavaScript aggiunto dal tema.** L'unico JS di WordPress in pagina e' il modulo del
  blocco Navigation. Il resto e' iubenda, cioe' plugin.
- **Un solo foglio di stile** dal child theme.
- Pagina da **8214px** a 1440 (era 8767), **27 → 12 immagini**.
- 10 immagini su 12 in `loading="lazy"`; caricate subito solo il logo dell'header e l'immagine
  dell'hero (`fetchpriority="high"`, con `srcset` e sorgente dedicata sotto i 782px).
- Mappa Google in `loading="lazy"`.
- Font con `font-display: swap`; Fraunces precaricato.

---

## 5. Il confronto affiancato

`screenshots/compare/compare-1440.png` — quattro colonne a 1440px:
**originale** · **prima versione (bocciata)** · **nuova** · **Prism (riferimento)**.

Screenshot singoli:

| Cartella | Contenuto |
|---|---|
| `screenshots/before/` | la home originale (tema `sorrisodambrosio`) |
| `screenshots/rejected/` | la prima iterazione, bocciata (era `after/`) |
| `screenshots/ref/` | Prism a 1440 e 390 |
| `screenshots/final/` | la nuova: `final-1440-full/hero`, `final-768-full`, `final-390-full/hero` |

---

## 6. Autocritica

Guardando le quattro colonne una accanto all'altra.

### 6.1 In che cosa la nuova differisce dalla vecchia — in concreto

1. **L'intestazione di sezione e' rovesciata.** Prima: occhiello, titolo, filetto rosso sotto,
   nove volte uguale. Ora: filetto sopra da bordo a bordo, occhiello in coda a destra, titolo
   sotto a sinistra. E' la differenza che si vede per prima nel confronto.
2. **Spariscono le griglie di card.** Nella vecchia e nella bocciata ci sono tre griglie di
   riquadri con bordo e ombra (4 servizi, 7 tecnologie, 4 motivi). Nella nuova non c'e' **nessun
   riquadro**: i servizi sono quattro pannelli fotografici a pieno sanguinamento, le tecnologie
   sette righe divise da filetti, i motivi quattro colonne di sola tipografia su fondo scuro.
3. **La pagina ha tre fondi invece di uno.** Prima era tutta `base`, con un «tinto» che era
   bianco puro, cioe' invisibile. Ora `base` → `tertiary` → fotografie → `main`: le sezioni si
   distinguono senza bisogno di una riga che le separi.
4. **Le immagini escono dal contenitore.** Prima stavano tutte dentro la colonna, a formato
   fisso. Ora la fotografia del capitolo tocca il bordo sinistro della finestra e i quattro
   pannelli dei servizi vanno da bordo a bordo.
5. **La colonna e' larga il doppio** (736 → 1320px) e il testo ha una misura vera (40rem,
   ~68 caratteri) invece di riempire tutta la riga.
6. **Il percorso e' cambiato.** Nove sezioni in fila diventano otto, e due si fondono:
   «Lo Studio» e «I Nostri Pazienti» sono un capitolo solo, intorno a una fotografia.
7. **Niente clipart.** Otto icone PNG via; le fotografie dello studio reggono da sole.

### 6.2 In che cosa somiglia all'approccio di Prism

- Hero a tutta pagina con **il titolo in basso a sinistra**, non al centro (Prism: 951px e
  titolo in basso; qui 780px e titolo in basso).
- **Immagini che escono dal contenitore da un lato solo** — e' il gesto strutturale che si
  ripete in tutta la pagina di Prism, ed e' quello che si e' preso.
- **Fondi alternati** per segnare i capitoli, invece di righe divisorie.
- **Matrice a filetti al posto della griglia di card** per un elenco di prestazioni.
- **Contenitore largo** (1312 Prism / 1320 qui) e **ritmo verticale generoso**.
- **Display serif oltre i 56px** contro un grottesco per il testo.
- **Mappa a tutta larghezza in fondo**, senza cornice.

Non si e' preso: la palette crema-azzurra, il tono delle fotografie, e la scheda bianca che
galleggia sull'hero (qui la lastra e' opaca e continua nella barra dei contatti).

### 6.3 Dove la nuova e' ancora sotto il riferimento

Onestamente:

1. **Le Tecnologie sono la sezione piu' debole.** Prism, per la stessa materia, costruisce un
   mosaico di immagini di formati diversi con le annotazioni accanto. Qui c'e' **una sola
   fotografia e un elenco**. E' un passo avanti rispetto alle sette card, ma e' la soluzione
   piu' prudente delle otto, e quella fotografia tagliata alta e stretta (403 × 848) e' un
   ritaglio forzato di uno scatto orizzontale.
2. **Il titolo «Nostri servizi» galleggia.** Sta in un campo bianco sopra i pannelli, mentre
   Prism porta il titolo dentro la fascia tinta a cui appartiene. Qui titolo e pannelli non
   sembrano ancora la stessa cosa.
3. **Il capitolo «Lo Studio / I Nostri Pazienti» resta denso di prosa.** Nove paragrafi.
   Prism non ha mai piu' di tre o quattro paragrafi per sezione, perche' i suoi testi vivono
   sulle pagine interne. Qui non si poteva: per «I Nostri Pazienti» **non esiste una pagina
   che contenga il testo**, quindi accorciarlo lo avrebbe perso (vedi `DA-VERIFICARE` §1).
   Le tre colonne tipografiche rendono il blocco leggibile, non breve.
4. **La fascia `tertiary` e' piu' carica di quanto vorrei.** `#bcc9dd` e' l'unico azzurro
   chiaro in palette: steso su una sezione intera pesa piu' del `#cfe0ea` di Prism, e obbliga
   tutto il testo a `main` per restare leggibile. Con una tinta piu' tenue si respirerebbe
   meglio — ma significherebbe introdurre un colore nuovo, che il mandato vieta.
5. **I tre ritratti dei medici restano disomogenei.** Rimpicciolirli a 260px ne nasconde la
   bassa risoluzione, non il fatto che siano tre scatti diversi, con tre sfondi e tre luci.
   E' un problema di materiale, non di impaginazione (`DA-VERIFICARE` §2.4).
6. **Il vuoto verticale piu' grande e' 190px, contro un limite di 200.** Passa, ma di poco:
   il ritmo fra le sezioni e' stato stretto apposta (`clamp(3.25rem, 6.6vw, 6rem)` invece di
   `clamp(4.5rem, 9vw, 8.5rem)`) per rientrare. Prism respira di piu' fra una sezione e
   l'altra; qui il criterio e la generosita' tirano in direzioni opposte.

**La nuova somiglia ancora alla vecchia?** No. Le tre differenze che chiudono la questione
sono strutturali, non cosmetiche: niente piu' griglie di card, immagini che escono dal
contenitore, fondi alternati. Nel confronto a quattro colonne, le colonne 1 e 2 hanno lo
stesso ritmo; la 3 ha quello della 4.

---

## 11. Terza iterazione — header, hero, orari, modulo

Il titolare ha approvato la direzione della seconda iterazione. Questa rifinisce la
homepage sul riferimento `screenshots/ref/prism-hero.png`.

### 11.1 Che cosa e' cambiato

| | |
|---|---|
| **Ambiente** | Il sito gira ora su **PHP 8.3.17** (verificato servendo un file dal sito, non con `wp`: WP-CLI usa una PHP propria). `phpVersion` di PHPStan, `composer.json` e `style.css` allineati. |
| **Header** | Logo, navigazione, poi telefono (pillola piena) e «Prenota un appuntamento» (contornata). Sticky, filetto in basso. Su mobile il telefono resta come icona. |
| **Hero** | Velo in diagonale dalla sinistra al posto della lastra opaca; occhiello, titolo e paragrafo a sinistra; **scheda contatti bianca arrotondata** ancorata in basso; social in fondo a destra. |
| **Orari** | Nuova fascia scura **subito dopo «Lo Studio»**: giorni a righe, mattina e pomeriggio in caselle, oggi marcato. |
| **Prenota** | Nuova sezione `#prenota` con il modulo Contact Form 7, due colonne su desktop. |
| **Via** | La barra contatti scura sotto l'hero e la tabella orari in fondo: i recapiti stanno nella scheda dell'hero, gli orari nella loro fascia. |

### 11.2 Le soglie dell'header sono misurate, non scelte

La riga intera — logo 140 + otto voci 695 + telefono 170 + «Prenota» 224 + margini —
chiede **1328px**. Sotto, qualcosa deve cedere:

- **≤1340px**: cade «Prenota» (resta nell'hero e nella fascia orari).
- **≤1120px**: la navigazione passa alla tendina. Il blocco Navigation di core lo
  farebbe **solo sotto i 600px**: con otto voci di primo livello si accavallava sul
  logo gia' a 1440. Le due regole di core sotto `@media (min-width: 600px)` sono state
  rimesse al comportamento mobile fino a 1120px.
- **≤781px**: il telefono diventa un pulsante a sola icona.

### 11.3 Il contrasto dell'hero e' misurato sui pixel, non stimato

Il testo bianco sta sopra una fotografia: il contrasto dipende da **che cosa c'e' sotto
ogni glifo**, non dal colore dichiarato. Procedura usata:

1. nascondere il testo lasciando il layout invariato (`visibility: hidden`);
2. screenshot del solo fondo (fotografia + velo compositi);
3. campionare i pixel dentro il riquadro di ciascun blocco e calcolare il rapporto
   con il bianco, pixel per pixel.

| | medio | **peggiore** | pixel sotto 4.5:1 |
|---|---|---|---|
| Occhiello «Dal 1956» | 9,89:1 | **9,74:1** | 0 / 348 |
| `h1` | 9,35:1 | **6,10:1** | 0 / 22.274 |
| Paragrafo | 10,13:1 | **7,00:1** | 0 / 8.448 |

Alla prima taratura il **23%** del riquadro dell'`h1` stava sotto 4,5:1: gli stop del
velo sono stati spostati (28/46/62/78%) e le opacita' centrali alzate finche' nessun
pixel e' rimasto sotto soglia.

### 11.4 Il modulo era configurato a meta'

Tre difetti trovati facendolo girare, non leggendolo:

1. **Il modello dell'email puntava a campi inesistenti** — i segnaposto di default di
   CF7 (`[your-name]`, `[your-email]`…) contro campi che si chiamano `nome-cognome`,
   `telefono`, `email`, `trattamento`, `messaggio`. Le richieste sarebbero arrivate
   **vuote**, con un `Reply-To:` non valido. Riscritto.
2. **I messaggi erano in inglese** su un sito `it_IT`. Tradotti.
3. **Il submit sembrava guasto**: il tag `[acceptance]` di CF7 lo tiene disabilitato
   finche' la privacy non e' spuntata, e senza uno stato visibile si legge come un
   pulsante rotto. Ora ha un suo aspetto d'attesa.

Verifica end-to-end: invio valido → stato `sent` → **email ricevuta da Mailpit** con
tutti i campi al posto giusto e `Reply-To` sull'email del paziente.

### 11.5 Criteri di accettazione

| | Criterio | Esito |
|---|---|---|
| ✅ | Header: telefono e «Prenota» a destra della navigazione a 1440px | entrambi visibili, allineati a destra; la riga sta su **una sola linea** (71px) |
| ✅ | Header sticky | `position: sticky`, `top: 0`, misurato a `scrollY` 3433: `rect.top === 0` |
| ✅ | Telefono come icona a 390px | pulsante 52×44, etichetta nascosta ai soli occhi (resta ai lettori di schermo) |
| ✅ | Hero: immagine visibile, testo leggibile sul velo | **nessun pixel sotto 4.5:1** sotto nessuno dei tre blocchi (§11.3) |
| ✅ | Scheda contatti: i collegamenti funzionano | `mailto:studio@sorrisodambrosio.it` · `tel:063225754` · `maps.app.goo.gl/jsVVDPiXFAFLCiE18` · «Prenota» → `#prenota` |
| ✅ | «Prenota» porta a `#prenota` | dopo il clic il titolo della sezione e' a 245px, **sotto** l'header (71px), interamente visibile |
| ✅ | Nessun overflow a 1440 / 768 / 390 | `scrollWidth` 1425/1440, 753/768, 375/390 |
| ✅ | Social: solo profili reali | Facebook, Instagram, WhatsApp — tutti e tre presenti nel menu o nel footer del sito esistente. **Google non c'e'**: segnalato in `DA-VERIFICARE` §0.3 |
| ✅ | Orari subito dopo «Lo Studio» | sezione 3 su 9; `#orari` presente |
| ✅ | Oggi evidenziato | DOM: `.sd-hours-week__day.is-today` → «Martedi \| OGGI \| 9:00 – 13:00 \| Chiuso». Calcolato con `wp_date()`, non in JavaScript |
| ✅ | Modulo CF7 dentro `#prenota` | `form.wpcf7-form` presente |
| ✅ | Invio vuoto → errori vestiti | 3 campi `.wpcf7-not-valid` con bordo `primary-accent`, messaggi in `primary-accent`, riquadro di risposta su fondo `primary-accent` all'8% |
| ✅ | Invio valido → successo + email | stato `sent`, messaggio italiano, **email in Mailpit** con tutti i campi |
| ✅ | Nessun `<p>` o `<br>` iniettato da CF7 | **0** `<p>` e **0** `<br>` dentro `.sd-form` |
| ✅ | Nessun colore fuori da `theme.json`; font locali | scansione del tema (escluso `vendor/`): zero; zero riferimenti a Google Fonts |
| ✅ | PHPStan con target **PHP 8.3** | `phpVersion: 80300`, livello 5: **No errors** su 12 file. `php -l` con il binario 8.3 del sito: tutti OK |
| ✅ | `debug.log` senza nuove voci | log azzerato, pagina visitata: **resta vuoto** |
| ✅ | Tutti i criteri precedenti | contenuto 1320px · `h1` 72px · hero 86,7vh · icone max **24px** · regione vuota max **190px** · un solo `h1` · nessun salto di livello |

### 11.6 Confronto con il riferimento, e autocritica

`screenshots/compare/hero-vs-prism-1440.png` — i due hero affiancati.

**Che cosa segue il riferimento, in concreto**

1. **Struttura dell'header identica**: logo a sinistra, navigazione, poi due pillole —
   telefono pieno, prenotazione contornata.
2. **Fotografia a tutto schermo con velo dalla sinistra**, testo bianco allineato a
   sinistra nella colonna scurita.
3. **Occhiello come frase breve in grassetto**, non etichetta in maiuscoletto spaziato —
   come «Precision. Comfort. Compassion.».
4. **Titolo display leggero** (Fraunces 300) su piu' righe, non un nero pesante.
5. **Scheda contatti bianca completamente arrotondata**, ancorata in basso, con icone di
   linea e il pulsante pieno all'estremita' destra.
6. **Social piccoli, bianchi, in fondo a destra**, sotto la scheda.

**Dove resta sotto**

1. **La fotografia non e' della stessa natura.** Prism ha **un volto solo**, grande,
   luminoso, che occupa mezzo fotogramma. La 636 e' una stanza con quattro persone: il
   lato destro resta piu' affollato e nessun viso ha quel peso. E' un limite di
   materiale, non di impaginazione (`DA-VERIFICARE` §0.2).
2. **Il velo e' piu' denso di quello del riferimento.** Per portare ogni pixel sotto
   l'`h1` oltre 4,5:1 su una fotografia chiara e mossa serve piu' copertura di quanta ne
   usi Prism, che ha a destra un fondo sfocato e uniforme. Il risultato: la famiglia e'
   leggermente piu' spenta di quanto vorrei. Fra contrasto misurato e brillantezza ho
   scelto il contrasto.
3. **Il titolo e' il nome dello studio, non una frase.** Prism dice «Oral Surgery and
   Implant Care in Summit, NJ»: dice al visitatore **che cosa si fa e dove**. «Studio
   Odontoiatrico D'Ambrosio» dice solo chi. Per averne una qui servirebbe scrivere testo
   nuovo, che il mandato vieta: le alternative possibili con il testo esistente sono in
   `DA-VERIFICARE` §0.1.
4. **L'header e' piu' denso.** Prism ha sei voci di menu, noi otto piu' due pulsanti: a
   1440 la riga e' piena e «Prenota» cade gia' a 1340. Il riferimento respira di piu'.
5. **La scheda contatti e' piu' carica.** Prism mostra solo le etichette
   («Email», «Phone», «Location») e tiene il valore nel collegamento. La nostra mostra
   etichetta **e** valore: piu' utile per chi vuole il numero a colpo d'occhio, ma meno
   ariosa. Scelta deliberata, reversibile in una riga.

---

## 7. Modifiche fuori dal tema

Poche, tutte reversibili.

1. **Contenuto della pagina 2059** aggiornato: otto `core/pattern` invece di nove
   (`pazienti` e' confluito in `lo-studio`).
2. **Testo alternativo scritto su 7 allegati** che ne erano privi (636, 654, 527, 1226, 817,
   1085, 75). Elencati in `DA-VERIFICARE` §3.
3. Restano dalla prima iterazione: la pagina 2059 come front page, `custom_logo = 645`,
   i meta Yoast copiati dalla pagina 57, `WP_DEBUG` attivo in `wp-config.php`.
4. **Terza iterazione** — contenuto della pagina 2059 riscritto a nove pattern
   (entrano `orari`, `prenota`, `vieni-a-trovarci`; escono `contact-bar` e
   `orari-contatti`). Sul modulo CF7 **2063**: modello dell'email riscritto sui campi
   reali e messaggi tradotti in italiano (vedi `DA-VERIFICARE` §0.4).

Non sono stati toccati: il core, il tema `ollie`, il tema `sorrisodambrosio`, nessun plugin,
nessuna pagina esistente. La pagina 57 e' **intatta e ancora pubblicata**.

---

## 8. Il caso iubenda (vale ancora, per le altre pagine)

Il plugin `iubenda-cookie-law-solution` cerca un pattern chiamato **`<stylesheet>/footer`** —
esattamente il nome del nostro — e, se non vi trova la stringa `[iub-wp-block-buttons]`, vi
inietta un proprio `<div>` **come primo figlio del secondo `<div>` del footer**.

Inoltre il blocco `core/shortcode` **non espande gli shortcode** (fa solo `wpautop`) e le parti
template non passano dal filtro `the_content`. Il tema colloca quindi da se' il blocco legale
in fondo al footer e lo esegue con `do_shortcode()`; un commento HTML mantiene la stringa
letterale nel pattern, cosi' il plugin non inietta nulla. **Non toccare quel blocco.**

---

## 9. Come tornare indietro

Il rollback non richiede il backup: **nulla e' stato distrutto**.

```bash
cd "~/Local Sites/sorrisodambrosio/app/public/wp-content"

wp theme activate sorrisodambrosio   # il tema precedente e' ancora installato
wp option update page_on_front 57    # la vecchia pagina torna front page
```

Per tornare alla **prima iterazione** del nuovo tema invece che al vecchio sito:

```bash
git revert b2eef80 32bc44e           # i due commit di layout di questa iterazione
```

Per ripartire proprio dal database di prima:

```bash
wp db import ../../../backups/pre-redesign.sql
```

> Quel file e' lo stato del **2026-10-05 prima del lavoro**. Reimportarlo annulla anche
> tutto quello che fosse stato scritto nel frattempo.

---

## 10. File prodotti

| Percorso | Contenuto |
|---|---|
| `docs/TRIAGE.md` | analisi dello stato di partenza (prima iterazione) |
| `docs/DESIGN-SPEC.md` | **il sistema di layout, scritto prima del codice** |
| `docs/REPORT.md` | questo documento |
| `docs/DA-VERIFICARE.md` | punti che richiedono una decisione del titolare |
| `redesign/homepage-text-original.txt` | i 199 frammenti di testo originali, in ordine |
| `redesign/screenshots/before/` | la home originale |
| `redesign/screenshots/rejected/` | la prima iterazione, bocciata |
| `redesign/screenshots/ref/` | Prism a 1440 e 390 |
| `redesign/screenshots/final/` | la seconda iterazione, a 1440 / 768 / 390 |
| `redesign/screenshots/final-it3/` | **la terza**: hero, orari e modulo a 1440 e 390 |
| `redesign/screenshots/reference/prism-hero.png` | l'hero del riferimento |
| `redesign/screenshots/compare/compare-1440.png` | il confronto a quattro colonne |
| `redesign/screenshots/compare/hero-vs-prism-1440.png` | **l'hero nuovo accanto al riferimento** |
| `wp-content/.playwright-mcp/audit.js` | lo script di misura usato per il §4 |
| `backups/pre-redesign.sql` | dump del database prima di ogni modifica |

### Commit

```
10d8e0b  Setup child theme Sorriso D'Ambrosio
3185948  Homepage: hero, sezioni, header, footer e CTA mobile
79976cb  Rifinitura, PHPStan e regole di progetto
-- seconda iterazione --
1a99cf7  Regole di progetto: contenuto letterale ma impaginazione libera, design first
32bc44e  theme.json: misure realistiche e scala tipografica rifatta
b2eef80  Homepage rifatta dalla specifica di design: otto sezioni, otto impianti
96a2cc4  CLAUDE.md: stato dopo la seconda iterazione
fa82050  README e documentazione di progetto dentro il repository
-- terza iterazione --
31186e6  Ambiente: il sito passa a PHP 8.3
93527ab  Header, hero, orari in evidenza e modulo di prenotazione
b6ba780  Hero: velo tarato sul contrasto misurato, e stacking su mobile
```

---

## 12. Quarta iterazione — l'architettura dei contenuti e le pagine interne

Data: 2026-10-07. Specifica: `ARCHITECTURE.md`, scritta prima del codice.
Materiali: `../../../redesign/screenshots/before-iter4/` (prima),
`final-it4/` (dopo), `compare/it4-*.png` (affiancati).

### 12.1 Il problema vero non era il design

La homepage era approvata; le altre pagine erano ancora quelle di Ollie, con
una colonna da 740px e mezza pagina vuota. Ma rifarle col sistema nuovo
sarebbe stato curare il sintomo.

> **Il menu aveva 45 voci di secondo livello. Tutte e 45 puntavano a un'ancora
> dentro una di sette pagine monolitiche.**

«Carico Immediato» non era una pagina: era `#carico-inmediato`. Non si poteva
condividere, Google non lo indicizzava come risorsa, non aveva un titolo, una
descrizione, un'immagine sociale. `/trattamenti/` era **lunga 13.352 pixel** a
1440px: dodici prestazioni diversissime in un rotolo solo. E per aggiungerne
una bisognava aprire 46 KB di blocchi, duplicare un gruppo, cambiare l'ancora
e poi ricordarsi del menu.

### 12.2 Che cosa e' stato costruito

| | Prima | Dopo |
|---|---|---|
| Trattamenti | 30 ancore dentro 5 pagine | **30 schede**, un indirizzo ciascuna |
| Tecnologie | 8 ancore dentro 1 pagina | **8 schede** |
| Professionisti | 3 blocchi di testo nel tema | **3 schede**, con relazioni |
| Casi clinici | 8 shortcode sparsi su 3 pagine | **8 schede** |
| Aree | 5 pagine da 371 a 1.762 parole | **5 termini**, una tassonomia sola |
| Pagina contatti | non esisteva | `/contatti/` |
| Dove sta il contenuto | nel tema e nelle pagine | in un **plugin**, che sopravvive al tema |

`plugins/sorriso-dambrosio-core/`: quattro tipi, una tassonomia condivisa,
post meta nativo (niente ACF: non c'era, e non valeva una dipendenza
commerciale), tredici blocchi a resa server, le regole di riscrittura, la
mappa dei redirect, gli orari, i recapiti e il JSON-LD.

Il tema guadagna dieci template e un capitolo di CSS (§18), e **perde** gli
orari e i recapiti: erano contenuto nel posto sbagliato.

### 12.3 Gli indirizzi: tutti tenuti, nessun redirect

Il vincolo era «nessun 404». Il risultato e' piu' stretto: **otto indirizzi su
otto restano identici e rispondono 200 senza nemmeno un redirect**. Le vecchie
pagine passano in bozza e il loro indirizzo viene raccolto da un termine
d'area o da un archivio che mostra lo stesso contenuto, arricchito.

Le aree stanno alla radice (`/implantologia/`) con **una regola per termine**,
generata dall'elenco reale. Una tassonomia con slug vuoto avrebbe risolto il
problema ingoiando ogni URL di primo livello del sito.

I quattro indirizzi rotti del vecchio menu (`/estetica/`,
`/implantologia/implantologia/`…) ora hanno un 301 dichiarato invece di una
correzione indovinata da WordPress.

```
bash docs/migration/verifica-url.sh
  — Le vecchie pagine e gli articoli      15 × 200
  — Le voci rotte del vecchio menu         4 × 200 (dopo 1 redirect)
  — I nuovi indirizzi                     52 × 200
  Controllati: 71 · falliti: 0
```

Le 45 vecchie ancore (`#flapes`, `#piezo`, `#carico-inmediato`…) restano come
`id` sulle righe delle pagine d'area: un vecchio link esterno atterra ancora
nel punto giusto. Gli **slug** pero' sono scritti bene — un refuso dentro
un'ancora e' invisibile, dentro un URL resta per sempre.

### 12.4 Il testo e' verificato, non dichiarato

`docs/migration/verifica-testi.php` prende ogni vecchia sezione, la spezza in
frasi e controlla che ognuna sia arrivata a destinazione.

```
Voci confrontate: 38 · frasi: 176 · mancanti: 0
```

Si ignorano solo le etichette dei pulsanti «Prenota un appuntamento», che sono
diventate la fascia di chiusura identica su tutte le pagine interne.

La FAQ di sei domande che chiudeva `/implantologia/` non e' ne' un trattamento
ne' una tecnologia: sta nel meta del termine e la pagina d'area la rende.
Senza quel passaggio si sarebbe persa.

### 12.5 Gli impianti nuovi

Nessuno compare in homepage: la regola 3 di DESIGN FIRST vale anche fra la
home e le pagine interne.

| Impianto | Dove | Che cos'e' |
|---|---|---|
| **Intestazione d'area** | `/implantologia/` e le altre 4 | fascia tinta, titolo a sinistra, fotografia che esce a destra |
| **Zig-zag** | gli elenchi dei trattamenti | righe alternate, fotografia a sinistra poi a destra |
| **Matrice a filetti** | `/tecnologie/` | celle divise da linee, niente riquadri e niente ombre |
| **Scheda** | un trattamento | titolo a sinistra, fotografia che esce **a destra** |
| **Scheda a specchio** | una tecnologia, un articolo | la fotografia esce **a sinistra** |
| **Tre colonne** | `/professionisti/` | i tre medici a confronto, divisi da filetti verticali |
| **Indice per aree** | `/prestazioni/` e la 404 | tutti i trattamenti in un colpo d'occhio, 2.283px invece di 13.352 |
| **Fascia di prenotazione** | fondo di ogni pagina interna | una sola, identica ovunque |

### 12.6 Quattro difetti che solo la pagina resa mostrava

Nessuno dei quattro si vedeva leggendo il codice.

1. **Il sanguinamento non arrivava da nessuna parte.** `calc(50% - 50vw)`
   funziona solo se il contenitore e' l'elemento stesso: dentro una cella di
   griglia quel 50% si misura sulla cella. Ora c'e' `--sd-bleed`, che si
   misura sempre sul viewport.
2. **Nello zig-zag la fotografia finiva nella colonna larga.** `order` sposta
   la cella, non la colonna: sulle righe pari l'immagine prendeva il 62% e il
   testo si stringeva nel 38%. Si scambiano le colonne, non gli elementi.
3. **La prima voce di menu finiva sopra il logo.** Misurato a 1440px: voce a
   221px, logo fino a 232. Con otto voci la riga chiedeva 1.290px contro i
   1.256 disponibili. Il menu e' sceso a sette (vedi `DA-VERIFICARE` §0.5).
4. **`<h2></h2>` vuoti.** `block.json` dichiara i valori di partenza, quindi
   un attributo arriva sempre valorizzato — anche a `''` — e un `isset()`
   secco faceva vincere quel vuoto sul ripiego scritto in PHP.

E due di contorno, trovati misurando il JSON-LD:

5. **`&rsquo;` e' un'entita' nominata di HTML5.** Senza `ENT_HTML5` la tabella
   di default (HTML 4.01) non la conosce e la lascia li'. In una pagina HTML
   non si vede; dentro un JSON-LD si'.
6. **iubenda rimonta tutta la pagina con un parser DOM** e ogni carattere non
   ASCII torna indietro come entita' numerica — dentro il JSON-LD, testo
   letterale. Il plugin non si tocca: il JSON esce tutto in `\uXXXX`.
   Verificato con `?iub_no_parse=1`, che spegne quel passaggio.

### 12.7 Criteri di accettazione

| Criterio | Esito |
|---|---|
| `ARCHITECTURE.md` esiste e corrisponde a quanto costruito | ✔ `docs/ARCHITECTURE.md` |
| Ogni vecchio URL risponde 200, o 301 verso un 200. Zero 404 | ✔ **71 controllati, 0 falliti** |
| Ogni frammento di testo e' presente a destinazione | ✔ **176 frasi, 0 mancanti** |
| Ogni tipo ha singolo e archivio funzionanti | ✔ 4 tipi × 2 viste |
| Ogni area elenca i suoi trattamenti dinamicamente | ✔ 5 aree |
| Un trattamento di prova da WP-CLI compare nelle liste giuste | ✔ area, indice, tecnologia collegata, altri trattamenti; **non** nelle altre aree; sparisce quando si cancella |
| Gli orari vengono da una fonte sola | ✔ `Studio::hours()` → home, footer, contatti, lo-studio, JSON-LD |
| Navigazione con link veri a 1440 e 390px | ✔ 7 voci, 45 sottovoci con indirizzi reali |
| Briciole corrette | ✔ e generano il `BreadcrumbList` |
| Before/after funziona dove era usato | ✔ **25 gallerie, tutte inizializzate** |
| CF7 funziona, Mailpit riceve | ✔ con tutti i campi e `Reply-To` del paziente |
| JSON-LD valido, solo dati esistenti | ✔ `Dentist` + `BreadcrumbList` + `MedicalProcedure`/`Physician` |
| Niente overflow a 1440/768/390, un `h1` per pagina | ✔ 14 pagine × 3 larghezze |
| PHPStan livello 5, PHP 8.3, su plugin e tema | ✔ `[OK] No errors` su entrambi |
| `debug.log` pulito | ✔ |
| `git status` traccia solo i percorsi ammessi | ✔ `docs/`, `themes/sorriso-dambrosio/`, `plugins/sorriso-dambrosio-core/` |
| Confronto affiancato vecchio/nuovo | ✔ 5 coppie in `screenshots/compare/it4-*.png` |

### 12.8 Autocritica

**Dove funziona.**

`/prestazioni/` mostra trenta trattamenti in **2.283px**. La vecchia
`/trattamenti/` ne mostrava dodici in **13.352px**. Non e' una pagina piu'
corta: e' una pagina che fa un lavoro diverso — indicizza invece di
raccontare, e il racconto sta nelle trenta schede.

Il modello regge la prova vera: un trattamento nuovo creato da riga di comando
compare da solo in cinque punti. Nessuno di quei cinque e' stato scritto a
mano, quindi nessuno dei cinque puo' dimenticarsene.

**Dove e' ancora sotto.**

- **Le pagine d'area sono lunghe.** `/implantologia/` fa 5.964px con cinque
  trattamenti; `/trattamenti/`, con dodici, va oltre. Lo zig-zag e' leggibile
  ma generoso. Una variante piu' compatta sopra le otto voci sarebbe giusta.
- **Molte schede non hanno ne' tecnologie ne' medici collegati.** Delle trenta,
  sei hanno una tecnologia collegata e sei un medico: le altre chiudono con la fascia
  di prenotazione e basta. Non e' un difetto del codice — e' che le relazioni
  si potevano derivare solo dal testo esistente, e il testo non le dice. Si
  riempiono dal pannello in un pomeriggio (`DA-VERIFICARE` §11).
- **«Denti e postura» non si presenta**: e' l'unica area senza introduzione,
  perche' quel testo non esiste da nessuna parte.
- **I ritratti su `/professionisti/`** sono resi a ~310px da originali di
  420px: su Retina il piu' piccolo resta morbido. Problema vecchio, attenuato
  due volte, mai risolto.
- **Le gallerie prima/dopo hanno testi alternativi inutilizzabili** e una
  coppia scambiata. Sono dati del plugin: correggerli significa scrivere otto
  descrizioni cliniche nuove, che e' una decisione di contenuto.
- **Gli orari restano in un file.** Una fonte sola e' un progresso netto
  rispetto a tre copie, ma «una fonte sola che richiede uno sviluppatore» non
  e' lo stesso di «una fonte sola che il cliente puo' cambiare».

**Quello che non ho fatto, e perche'.**

Non ho aggiunto una schermata di opzioni per orari e recapiti: cambiano una
volta ogni molti anni e sarebbe una superficie in piu' da mantenere.
Non ho diviso la scheda «Chirurgia Piezo Elettrica», che mescola ultrasuoni e
laser: dividerla vuol dire decidere dove tagliare un testo, e quello non e' un
mandato che ho. Non ho corretto «Inesti» e «Cannini»: sono titoli, cioe' testo.
Tutt'e tre stanno in `DA-VERIFICARE.md` con la domanda gia' formulata.
