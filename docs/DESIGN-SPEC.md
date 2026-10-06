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

### 4.1 Hero — fotografia piena con lastra scura in basso a sinistra

```
┌──────────────────────────────────────────────────────┐
│ header                                               │
├──────────────────────────────────────────────────────┤
│                                                      │
│      FOTOGRAFIA 636 (2500×1685), 86vh, intera        │
│                                                      │
│                                                      │
│ ┌─────────────────────────┐                          │
│ │ Studio                  │                          │
│ │ Odontoiatrico           │ ← lastra `main`,         │
│ │ D'Ambrosio          72px│   esce dal bordo sinistro│
│ │                         │                          │
│ │ Gli specialisti…        │                          │
│ │ Tradizione – passione…  │                          │
│ │ CONTATTI →   CHI SIAMO →│                          │
├─┴─────────────────────────┴──────────────────────────┤
│ Indirizzo │ Tel │ Cel │ Email      fondo `main`      │
└──────────────────────────────────────────────────────┘
```

- Immagine **636** `AGA_5209-jpg.webp`, **2500×1685**, al posto di `brother-1` (1260×838): sopporta
  i 1440 e il Retina. E' lo studio vero, con la luce della finestra sul Tevere.
- `min-height: 86vh` da 782px in su, `72vh` sotto. Mai una zona vuota: la fotografia riempie.
- `object-position: 58% 42%` a 1440, `50% 38%` a 768, **l'immagine verticale 1492** sotto 782px.
- Il testo **non sta sopra la foto con un velo**: sta su una **lastra opaca `main`** ancorata in
  basso a sinistra, che sborda dal bordo della finestra. Contrasto pieno, fotografia intatta.
- L'occhiello «Gli specialisti del sorriso» **non e' piu' un'etichetta sopra il titolo**: scende
  sotto, in testa al claim, e si legge come una frase. Testo identico, posizione diversa.
- La lastra e la barra contatti sono **la stessa forma scura**, continua: niente scheda bianca
  che galleggia con l'ombra.

### 4.2 Barra contatti — rail scuro a tutta larghezza

Quattro celle divise da filetti verticali, fondo `main`, etichette (`Indirizzo:`, `Tel:`, `Cel:`)
in `main-accent` a 13px, valori in `base` a 16px. Nessun bordo arrotondato, nessuna ombra.
Sotto 782px diventa due righe da due celle.

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

### 4.8 I Nostri Orari + Vieni a Trovarci — mappa piena con pannello

```
┌──────────────────────────────────────────────────────┐
│ ┌──────────────────┐                                 │
│ │ I Nostri Orari   │                                 │
│ │ Lunedi  9–13 14–19│      MAPPA a tutta larghezza   │
│ │ …                │      520px                      │
│ │ Sabato-Dom chiuso│                                 │
│ │ ──────────────── │                                 │
│ │ Vieni a Trovarci │                                 │
│ │ Lungotevere Fl.34│                                 │
│ └──────────────────┘   pannello `main`, sborda       │
└──────────────────────────────────────────────────────┘
```

- La mappa esce da entrambi i bordi (`alignfull`), il pannello scuro con orari e indirizzo
  ci galleggia sopra a sinistra. Sotto 900px il pannello sta sopra la mappa, a tutta larghezza.
- Chiude il percorso: chi siamo → chi cura → cosa facciamo → come raggiungerci.

---

## 5. Riepilogo: nessun impianto si ripete

| # | Sezione | Impianto | Fondo |
|---|---|---|---|
| 1 | Hero | fotografia piena + lastra opaca che sborda | fotografia |
| 2 | Contatti | rail orizzontale a celle | `main` |
| 3 | Lo Studio / Pazienti | split 38/62, immagine sticky che esce a sinistra, testo su 2 colonne | `base` |
| 4 | I Professionisti | elenco di righe 30/70 divise da filetti | `tertiary` |
| 5 | Nostri servizi | quattro pannelli fotografici a pieno sanguinamento | fotografie |
| 6 | Tecnologie | colonna sticky 34/66 + elenco a filetti | `base` |
| 7 | Perche' noi | fascia scura di sola tipografia su quattro colonne | `main` |
| 8 | Orari / Mappa | mappa a tutta larghezza con pannello sovrapposto | `base` + mappa |

Alternanza dei fondi: foto → scuro → chiaro → tinto → foto → chiaro → scuro → chiaro → footer scuro.

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
- [ ] `php -l` con il binario 7.4 del sito e PHPStan livello 5 puliti.
