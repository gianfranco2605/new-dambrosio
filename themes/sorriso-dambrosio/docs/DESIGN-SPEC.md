# DESIGN-SPEC — Homepage Studio Odontoiatrico D'Ambrosio

Data: 2026-10-06 · Scritta **prima** del codice, come impone la sezione DESIGN FIRST di `CLAUDE.md`.
Riferimento analizzato: `https://www.prismoralsurgery.com/` (misurato con Playwright a 1440 e 390px,
screenshot in `screenshots/ref/`).

---

## 1. Analisi del riferimento (Prism), misurata

| | 1440px | 390px |
|---|---|---|
| Contenitore | **1312px** (margini 64px) | 342px (margini **24px**) |
| Colonna di testo in uno split | 616px (due da 616 + 80 di gap) | piena |
| Hero | **951px** di altezza (≈106vh) | 890px |
| Altezze delle sezioni | 556 · 608 · 776 · 844 · 1390 · 1672 | — |
| Padding verticale di sezione | 80 → 128px | 48 → 80px |
| `h1` / `h2` / `h3` / testo | 56 / 48 / 24 / 18px (interlinea 27) | 40 / 36 / 24 / 18 |
| Famiglie | display serif (`the-seasons`) + `Inter` | idem |
| Fondo | crema `#f3efec`, testo `#666` | idem |

**Come sono fatte le sezioni** (questo e' il punto da cui si impara, non i colori):

1. **Hero** — fotografia a pieno sanguinamento, alta quanto lo schermo. Titolo **in basso a
   sinistra**, non centrato. Una scheda orizzontale di contatto **sborda sul bordo inferiore**.
2. **Il medico** — split asimmetrico: ritratto verticale che **tocca il bordo sinistro della
   finestra**, testo a destra dentro il contenitore. Niente cornice, niente card.
3. **Servizi** — fondo tinto diverso dal resto. A sinistra una fotografia che **riempie meta'
   sezione fino al bordo**, a destra una **matrice di celle divise da filetti**: niente riquadri,
   niente ombre, niente bordi arrotondati. Le icone sono minuscole e grigie.
4. **Tecnologia** — testo a sinistra, poi un **mosaico di immagini di formati diversi** con
   annotazioni accanto. Non una griglia regolare.
5. **Perche' sceglierci** — una **fascia orizzontale scorrevole** che esce dal bordo destro,
   pannelli di colore alternato.
6. **Nuovi pazienti** — testo stretto a sinistra, quattro **piastrelle piatte** a destra.
7. **Contatti** — fondo tinto, recapiti a sinistra, modulo a destra.
8. **Mappa** — a tutta larghezza, senza cornice, in fondo.

**Le costanti**: contenitore largo; le immagini **escono dal contenitore** da un lato solo;
fondi che si alternano (crema → tinto → immagine → crema → tinto); ritmo verticale generoso;
titoli quasi sempre a sinistra; **mai la stessa impaginazione due volte di fila**.

**Cosa prendo**: il contenitore largo, il sanguinamento da un lato, l'alternanza dei fondi, la
matrice a filetti al posto della griglia di card, il ritmo verticale, il display serif oltre i 56px.
**Cosa non prendo**: la sua palette, le sue fotografie, il suo tono, e la scheda bianca che
galleggia sull'hero (la risolvo in modo diverso, vedi §4.1).

---

## 2. Che cosa e' andato storto nella versione bocciata

| Sintomo | Causa |
|---|---|
| Tutte le sezioni identiche | un unico `sorriso_dambrosio_section_head()`: occhiello + titolo + **filetto rosso**, poi una griglia di card. Nove volte. |
| Colonna di lettura strettissima | `contentSize: 740px`. A 1440 il testo occupava **736px su 1425**: meta' pagina vuota. |
| Icone enormi | le icone dei servizi uscivano a **230×153px** e quelle di «Perche' noi» a 44px: due stili diversi, nessuno dei due coerente. |
| Hero debole | immagine `brother-1` da **1260×838** stirata a tutta larghezza, con un velo diagonale che la impastava. |

Nessuno di questi difetti si vedeva leggendo il codice: si vedono solo guardando la pagina resa.

---

## 3. Il sistema di layout di D'Ambrosio

### 3.1 Misure

| | valore | nota |
|---|---|---|
| `contentSize` | **1180px** | era 740 |
| `wideSize` | **1320px** | era 1280; e' anche il viewport a cui la tipografia fluida arriva al massimo |
| Padding di radice | `medium` = `clamp(1.5rem, 4vw, 2rem)` | 24px a 390, 32px a 1440 |
| Larghezza utile a 1440 | **1320px** (`alignwide`) | criterio: ≥ 1100px |
| Misura di lettura | `62ch` sul testo corrente | ~70 caratteri |
| Padding verticale di sezione | `clamp(4.5rem, 9vw, 8.5rem)` | 72 → 136px |

### 3.2 Griglia

Dodici colonne concettuali dentro i 1320px. Gli split usati, tutti **asimmetrici**:
`38/62` (immagine/testo), `30/70` (ritratto/biografia), `34/66` (titolo fisso/elenco).
Il 50/50 non compare mai. Sotto i 900px tutto scende a una colonna.

Le immagini importanti **escono dal contenitore** verso il bordo della finestra, da un lato solo
(`margin-inline-start: calc(50% - 50vw)`), come in Prism.

### 3.3 Tipografia

Le due famiglie restano **Fraunces** (display) e **DM Sans** (testo). Cambia la scala, che prima
saliva fino a 84px su una colonna da 736 — un titolo piu' largo della colonna che lo portava.

| ruolo | preset | min → max | a 1440 |
|---|---|---|---|
| `h1` | `xx-large` | 2.75 → **4.5rem** | **72px** |
| `h2` | `x-large` | 2 → 3.125rem | 50px |
| occhiello di sezione | `x-small` | .8125 → .875rem | 14px |
| sommario / lede | `large` | 1.5 → 2.125rem | 34px |
| `h3` | `medium` | 1.1875 → 1.375rem | 22px |
| testo | `base` | 1.0625 → 1.1875rem | 19px, interlinea 1.7 |
| testo minore | `small` | .9375 → 1rem | 16px |

Rapporto h1 : h2 : testo = **72 : 50 : 19**. Fraunces con `WONK 0`, `opsz 72`, `wght 400`.

### 3.4 Colore

**La palette non cambia.** Cambia il modo di stenderla: tre fondi invece di uno.

| fondo | colore | dove | testo |
|---|---|---|---|
| chiaro | `base` `#fcfcfc` | Lo Studio, Tecnologie, Orari | `main`, secondario `secondary` |
| tinto | `tertiary` `#bcc9dd` | I Professionisti | **sempre `main`** — `secondary` su `tertiary` fa 3,3:1, sotto la soglia |
| scuro | `main` `#39383A` | barra contatti, Perche' noi, footer | `base`, secondario `main-accent` |

`primary` `#1262fa` resta **solo** l'accento: link, un pulsante pieno, il focus. `primary-accent`
resta dichiarato e inutilizzato.

**Il filetto rosso sparisce.** Era `#ff0d00`, fuori palette, ereditato da un template (vedi
`DA-VERIFICARE.md` §2). Il suo lavoro — «qui comincia una sezione» — lo fa ora un filetto sottile
`--wp--custom--border--hairline` **sopra** la sezione.

### 3.5 L'intestazione di sezione (sostituisce occhiello + titolo + filetto)

```
──────────────────────────────────────────────────────  Dal 1956
Lo Studio
```

Un filetto da bordo a bordo del contenitore **sopra** la sezione; l'occhiello — che e' contenuto
esistente («Dal 1956», «Dottori», «Prestazioni»…) — in coda al filetto, a **destra**, 14px; il
titolo sotto, a sinistra, 50px. Mai centrato, mai sottolineato, mai in maiuscoletto spaziato.

### 3.6 Icone

Le otto icone PNG (quattro servizi, quattro «Perche' noi») **si tolgono**. Sono clipart blu
generiche accanto a fotografie vere dello studio: non aggiungono informazione e nella versione
bocciata uscivano a 230px. Restano solo le icone del blocco social nel footer, che sono SVG e
misurano 24px. Nessuna icona supera i 56px perche' non ce ne sono piu'.

### 3.7 Movimento

Una sola entrata, nell'hero. Nessuna transizione su card (non ci sono piu' card con hover).
`prefers-reduced-motion: reduce` spegne tutto. Nessun JavaScript aggiunto.

---

## 4. Le sezioni, una per una

Ogni sezione usa un **impianto diverso**. Nessuna riprende occhiello centrato + titolo
sottolineato + griglia di card uniformi.

### 4.1 Hero — fotografia piena, velo dalla sinistra, scheda contatti bianca

> **Rifatto nella terza iterazione**, sul riferimento `screenshots/ref/prism-hero.png`.
> Prima il titolo stava su una lastra opaca ancorata in basso a sinistra.

```
┌──────────────────────────────────────────────────────────┐
│ logo   Home Implantologia … Lo Studio  [tel]  [Prenota]  │  header sticky
├──────────────────────────────────────────────────────────┤
│                                                          │
│  Dal 1956                      FOTOGRAFIA 636            │
│                                                          │
│  Studio                    ◄── il velo si dissolve       │
│  Odontoiatrico                 prima dei volti           │
│  D'Ambrosio          72px                                │
│                                                          │
│  Gli specialisti del sorriso…                            │
│                                                          │
│  ┌────────────────────────────────────────────────────┐  │
│  │ ✉ Email   ☎ Telefono   ⚲ Indirizzo   [ Prenota ]   │  │ scheda bianca
│  └────────────────────────────────────────────────────┘  │
│                                            f  ig  wa     │ social a destra
└──────────────────────────────────────────────────────────┘
```

- **Velo** in diagonale (100deg) dal bordo sinistro: `deep` 0.94 fino al 28%,
  `mid` 0.80 al 46%, `edge` 0.35 al 62%, trasparente al 78%. Piu' una velatura
  dal basso per staccare la scheda.
  **Gli stop non sono a occhio**: si misurano i pixel compositi sotto ogni blocco
  di testo (screenshot del solo fondo, campionamento con PIL) e si spostano finche'
  **nessun pixel** scende sotto 4.5:1. Valori attuali: occhiello 9.74:1, h1 6.10:1,
  paragrafo 7.00:1 nel caso peggiore.
- **Occhiello** come frase breve in grassetto (`small`, 600), **non** etichetta in
  maiuscoletto spaziato: la regola 3 di DESIGN FIRST vieta quel cliche'.
- **Titolo** `xx-large` (72px a 1440) in Fraunces `font-weight: 300` — nel riferimento
  il display e' leggero, non nero.
- **Scheda contatti**: bianca, `border-radius: 999px`, ancorata in basso dentro il
  contenitore. Tre voci (etichetta piccola + valore) con icona di linea in `primary`,
  piu' il pulsante pieno «Prenota un appuntamento».
  A ≤1023px diventa una griglia 2×2, a ≤599px una colonna.
- **Social** in fondo a destra: **solo i profili che esistono gia' sul sito** —
  Facebook, Instagram, WhatsApp. Nessun account inventato.
- **≤781px**: la fotografia smette di fare da fondo, sta sopra per intero (42vh) e il
  testo la segue su fondo `main`. Il velo sparisce.

### 4.2 Header — logo, navigazione, telefono, prenotazione

```
logo        Home  Implantologia⌄  …  Lo Studio    [☎ +39 06 322 5754]  [Prenota]
```

- Fondo `base`, filetto sottile in basso, `position: sticky`, `z-index: 100`.
- A destra due pillole: telefono **pieno** (`primary`) e «Prenota un appuntamento»
  **contornato**, come nel riferimento.
- **Le soglie sono misurate, non scelte**: la riga intera (logo 140 + otto voci 695 +
  telefono 170 + prenota 224 + margini) chiede **1328px**.
  - ≥1341px: tutto in linea.
  - ≤1340px: cade «Prenota» (resta raggiungibile dall'hero e dalla fascia orari).
  - ≤1120px: la navigazione passa alla tendina. Il blocco Navigation di core lo
    farebbe **solo sotto i 600px**: con otto voci di primo livello si accavalla sul
    logo molto prima. Le due regole di core sotto `@media (min-width: 600px)` sono
    rimesse al comportamento mobile fino a 1120px.
  - ≤781px: il telefono diventa un pulsante a sola icona, l'etichetta resta ai
    lettori di schermo.

### 4.3 Lo Studio + I Nostri Pazienti — un solo capitolo, immagine che resta ferma

```
────────────────────────────────────────────── Dal 1956
┌───────────────┬──────────────────────────────────────┐
│               │  Lo Studio                      50px │
│  654          │                                      │
│  reception    │  Lo Studio Dentistico D'Ambrosio e'  │
│  «da / Dal    │  stato fondato nel 1956…             │
│  1956»        │  …                                   │
│               │  ────────────────────────────────    │
│  1920×2560    │  I Nostri Pazienti                   │
│  **sticky**   │                                      │
│  esce a       │  I nostri pazienti vengono accolti…  │
│  sinistra     │  (5 paragrafi su 2 colonne, 62ch)    │
└───────────────┴──────────────────────────────────────┘
```

- Split **38/62**. L'immagine **654** (1920×2560, il bancone con il marchio e «Dal 1956») esce dal
  bordo sinistro ed e' `position: sticky`: resta ferma mentre scorrono i due movimenti di testo.
- Due sezioni del vecchio sito diventano **un capitolo solo**: la storia e poi le persone.
  Il testo e' integrale e letterale, cambia solo il raggruppamento.
- I cinque paragrafi di «I Nostri Pazienti» vanno su **due colonne tipografiche** (`columns: 2`):
  cosi' un muro di prosa diventa una pagina.
- Il testo completo di «Lo Studio» vive anche su `/lo-studio/`: il titolo e' un collegamento.

### 4.3b I Nostri Orari — gli orari in evidenza (`#orari`)

> **Nuova nella terza iterazione.** Il titolare ha chiesto che gli orari si vedano.
> Stanno **subito dopo «Lo Studio»**, non in fondo alla pagina.

```
fondo `main`  ──────────────────────────── Quando siamo aperti
I Nostri Orari          │ Lunedi      [9:00 – 13:00] [14:00 – 19:00]
                        │ ┃Martedi OGGI [9:00 – 13:00] [  Chiuso  ]
Sabato-Domenica chiuso  │ Mercoledi   [9:00 – 13:00] [14:00 – 19:00]
                        │ …
[☎ +39 06 322 5754]     │ Sabato      [  Chiuso  ]   [  Chiuso  ]
[⏱ Prenota]             │ Domenica    [  Chiuso  ]   [  Chiuso  ]
```

- Split **38/62** su fascia **scura**: e' il modo di dare peso senza aggiungere colori.
- I giorni sono **righe divise da filetti**, non una tabella: mattina e pomeriggio
  sono due caselle a pillola, affiancate e della stessa misura.
- **I turni chiusi restano visibili ma spenti** (bordo tratteggiato, `main-accent`):
  dire «chiuso» e' informazione, nasconderla no.
- **Oggi** porta un filetto `primary` sul bordo sinistro **e** un marcatore testuale
  «oggi»: il colore da solo non basta. Il giorno si calcola in PHP con `wp_date()`,
  che usa il fuso del sito — non in JavaScript, e non con `date()` che userebbe quello
  del server.
- Due pulsanti: chiamare e prenotare.

### 4.4 I Professionisti — elenco redazionale, non tre card

```
────────────────────────────────────────────── Dottori
I Professionisti                     fondo `tertiary`

┌─────┐  Dott.ssa Nicoletta D'Ambrosio           22px
│ 260 │  La Dott.ssa Nicoletta D'Ambrosio si distingue…
│ ×325│
└─────┘
─────────────────────────────────────────────────────
┌─────┐  Dott.ssa Stefania D'Ambrosio
│     │  …                     Guarda alcuni dei suoi casi →
└─────┘
─────────────────────────────────────────────────────
┌─────┐  Dott. Giuseppe D'Ambrosio
└─────┘  …
```

- Tre **righe** a tutta larghezza, divise da filetti: 30/70, ritratto a sinistra, biografia a destra.
- I ritratti scendono da ~390px a **260px**: i tre originali (420×554, 420×419, 486×642) tornano
  nitidi anche su Retina. E' la risposta di impaginazione al problema aperto in `DA-VERIFICARE` §1.
- Fondo `tertiary`: e' la fascia tinta del sito, l'unica.

### 4.5 Nostri servizi — quattro pannelli fotografici a pieno sanguinamento

```
┌───────────┬───────────┬───────────┬───────────┐
│           │           │           │           │
│ fotografia a tutta altezza, 4 pannelli,       │
│ nessun margine fra loro, nessun bordo         │
│           │           │           │           │
│ Estetica  │Ortodonzia │Implanto.. │Altri Serv.│
│ testo     │ testo     │ testo     │ testo     │
│ DI PIU' → │ DI PIU' → │ DI PIU' → │ DI PIU' → │
└───────────┴───────────┴───────────┴───────────┘
         520px di altezza, da bordo a bordo
```

- Niente card, niente icone, niente bordi, niente ombre: **le fotografie sono i pannelli**.
  Testo in basso, sopra una sfumatura dal basso.
- Due immagini vanno sostituite perche' troppo piccole per un pannello da 330×520
  (restano nella Media Library esistente, stesso soggetto):
  - Implantologia: **156** (300×200) → **1226** `implantologia-cover` (2560×1707)
  - Ortodonzia: **1096** (640×427) → **527** (2560×2080, il typodont con gli attacchi)
- Sotto 900px i pannelli diventano due per riga, sotto 600px uno.

### 4.6 Tecnologie — colonna fissa e elenco a filetti

```
────────────────────────────────────────────── Tendenze
┌──────────────┬──────────────────────────────────────┐
│ Tecnologie   │ Tac 3D Cone-Beam          CONTINUA → │
│              │ Il nostro studio e' dotato…          │
│ ┌──────────┐ │ ──────────────────────────────────── │
│ │ 820      │ │ Rx Digitale Intraorale    CONTINUA → │
│ │ manipoli │ │ …                                    │
│ │ sticky   │ │ ──────────────────────────────────── │
│ └──────────┘ │ (7 voci)                             │
└──────────────┴──────────────────────────────────────┘
```

- Split **34/66**. A sinistra il titolo e **una sola** fotografia (**820**, 2560×1707), entrambi
  `sticky`. A destra le sette tecnologie come **righe di un elenco**, divise da filetti.
- Sette card quasi identiche diventano sette righe: si leggono in colpo d'occhio.
- I testi sono estratti troncati con `…`: restano **esattamente** quelli, e ogni riga rimanda
  alla pagina `/tecnologie/#ancora` dove il testo e' completo (gia' cosi' prima del redesign).

### 4.7 Perche' noi — fascia scura, sola tipografia

```
fondo `main`  ────────────────────────────── Fiducia
Crediamo nella tua salute dentale e desideriamo
offrirti la migliore cura possibile…            34px
────────────────────────────────────────────────────
Team           │ Vasta linea   │ Strutture e  │ Risultati
odontoiatrico  │ di servizi    │ attrezzature │ garantiti…
altamente      │ odontoiatrici.│ avanzate.    │
qualificato.   │               │              │
testo 16px     │ testo         │ testo        │ testo
```

- L'unica fascia **scura** in mezzo alla pagina. Il paragrafo introduttivo sale a 34px e fa da
  dichiarazione; i quattro motivi sono **quattro colonne di sola tipografia**, divise da filetti
  verticali. **Le quattro icone spariscono.**
- Nessun numero progressivo: i quattro motivi non sono una sequenza.

### 4.8 Prenota un appuntamento — il modulo (`#prenota`)

```
────────────────────────────────────────────────── Contatti
Prenota un          │  Nome e cognome *   │  Telefono *
appuntamento        │  Email *            │  Trattamento
                    │  Messaggio                        │
I nostri pazienti…  │  ☐ Ho letto e accetto l'informativa
☎ ✉ ⚲ ⏱            │  [ Invia richiesta ]
```

- Split **38/62**: a sinistra titolo, lede e i recapiti completi (tutti e tre i
  numeri); a destra il modulo Contact Form 7.
- Il markup del modulo porta le classi del tema (`.sd-form__row--2col`,
  `.sd-form__field`…): due colonne su desktop, una sotto i 600px.
- **`wpcf7_autop_or_not` spento** in `functions.php`: senza, CF7 infila `<p>` e `<br>`
  in mezzo alla griglia e la rompe.
- Gli stati di CF7 sono vestiti con i colori del tema: campo non valido e messaggio
  d'errore in `primary-accent`, esito riuscito in `primary`, invio in corso col
  modulo spento. **Il submit disabilitato ha uno stato visibile**: il tag
  `[acceptance]` di CF7 lo tiene disattivato finche' la privacy non e' spuntata, e
  senza quello stato sembra guasto.
- Il foglio di CF7 usa `.wpcf7 form.sent .wpcf7-response-output` (0,3,1): per vincerlo
  senza `!important` i selettori del tema partono da `.sd-prenota form.wpcf7-form`.

### 4.9 Vieni a Trovarci — mappa piena con pannello

```
┌──────────────────────────────────────────────────────┐
│ ┌──────────────────┐                                 │
│ │ Vieni a Trovarci │      MAPPA a tutta larghezza    │
│ │ ⚲ Lungotevere…   │                                 │
│ │ ☎ +39 06 322…    │                                 │
│ │ ⏱ I Nostri Orari │  pannello `main`, sborda        │
│ └──────────────────┘                                 │
└──────────────────────────────────────────────────────┘
```

La tabella degli orari **non e' piu' qui**: ha una fascia dedicata (§4.3b). Il
pannello tiene indirizzo e telefono e rimanda agli orari con un collegamento.
Sotto 900px il pannello sale sopra la mappa, a tutta larghezza.

---

## 5. Riepilogo: nessun impianto si ripete

| # | Sezione | Impianto | Fondo |
|---|---|---|---|
| — | Header | logo, navigazione, telefono e prenotazione; sticky | `base` |
| 1 | Hero | fotografia piena, velo dalla sinistra, scheda contatti bianca, social | fotografia |
| 2 | Lo Studio / Pazienti | split 36/64, immagine che esce a sinistra, testo su 3 colonne | `base` |
| 3 | **I Nostri Orari** | fascia scura, giorni a righe, oggi marcato | `main` |
| 4 | I Professionisti | elenco di righe 30/70 divise da filetti | `tertiary` |
| 5 | Nostri servizi | quattro pannelli fotografici a pieno sanguinamento | fotografie |
| 6 | Tecnologie | colonna sticky 34/66 + elenco a filetti | `base` |
| 7 | Perche' noi | fascia scura di sola tipografia su quattro colonne | `main` |
| 8 | **Prenota** | split 38/62, recapiti e modulo CF7 | `base` |
| 9 | Vieni a Trovarci | mappa a tutta larghezza con pannello sovrapposto | `base` + mappa |

Alternanza dei fondi: foto → chiaro → scuro → tinto → foto → chiaro → scuro → chiaro → mappa → footer scuro.

La barra contatti scura che stava sotto l'hero non c'e' piu': i recapiti sono nella
scheda bianca dentro l'hero.

---

## 6. Criteri di verifica (si misurano sulla pagina resa)

- [ ] Hero: immagine e titolo sopra la piega a 1440 / 768 / 390px.
- [ ] Nessuna regione vuota piu' alta di 200px in tutta la pagina.
- [ ] Ogni icona al massimo 56px di bounding box; niente sborda dal contenitore.
- [ ] Larghezza del contenuto principale a 1440px ≥ 1100px.
- [ ] `h1` ≥ 56px su desktop; scala netta fra h1, h2 e testo.
- [ ] Nessun overflow orizzontale a 1440 / 768 / 390px.
- [ ] Un solo `<h1>`, nessun salto di livello.
- [ ] Nessun valore di colore fuori da `theme.json`; palette invariata.
- [ ] `php -l` con il binario **8.3** del sito e PHPStan livello 5 (`phpVersion: 80300`) puliti.
