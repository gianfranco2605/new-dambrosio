# REPORT — Redesign homepage Studio Odontoiatrico D'Ambrosio

Data: 2026-10-06 (seconda iterazione) · Ambiente: Local by Flywheel, `http://sorrisodambrosio.local`
WordPress 7.1.2 · PHP del sito 7.4.30 · Tema attivo: `sorriso-dambrosio` 1.1.0 (child di `ollie` 1.6.3)

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
| ✅ | PHPStan livello 5 target 7.4 · `php -l` 7.4 | **No errors**; 11 file, tutti OK |
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

## 7. Modifiche fuori dal tema

Poche, tutte reversibili.

1. **Contenuto della pagina 2059** aggiornato: otto `core/pattern` invece di nove
   (`pazienti` e' confluito in `lo-studio`).
2. **Testo alternativo scritto su 7 allegati** che ne erano privi (636, 654, 527, 1226, 817,
   1085, 75). Elencati in `DA-VERIFICARE` §3.
3. Restano dalla prima iterazione: la pagina 2059 come front page, `custom_logo = 645`,
   i meta Yoast copiati dalla pagina 57, `WP_DEBUG` attivo in `wp-config.php`.

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
| `redesign/screenshots/final/` | la nuova, a 1440 / 768 / 390 |
| `redesign/screenshots/compare/compare-1440.png` | il confronto a quattro colonne |
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
```
