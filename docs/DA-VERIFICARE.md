# Da verificare con il titolare

Punti che richiedono una decisione di chi possiede il sito o i contenuti.
Nessuno di questi blocca la homepage: il lavoro e' completo e funzionante cosi' com'e'.

Aggiornato il 2026-10-06, dopo il rifacimento del layout (vedi `DESIGN-SPEC.md`).

---

## 1. Testi accorciati sulla homepage, con rimando alla pagina completa

Il vincolo ora in vigore (`CLAUDE.md`, regola 2) permette di accorciare un testo lungo
sulla homepage **solo** rimandando con un collegamento alla pagina dove vive per intero.
Questi sono tutti i casi, nessuno escluso.

| Dove | Che cosa succede | Dove sta il testo completo |
|---|---|---|
| **Tecnologie** (7 voci) | Ogni voce porta un estratto che si interrompe a meta' frase con `…`. **Non e' una scelta di questo lavoro**: quei troncamenti erano gia' nella homepage precedente. | `/tecnologie/#tc-cone-beam`, `#rx-intraorale`, `#scanner-intraorale`, `#ortopantomografo`, `#chirurgia-guidata`; `/trattamenti/#laser-terapia`; `/estetica-dentale/#igieneprofesionale` — un collegamento per riga |
| **Lo Studio** | Il testo in homepage e' **integrale**, ma gli manca un paragrafo che esiste su `/lo-studio/`: «A distanza di quasi 70 anni, lo studio continua a mantenere viva quella stessa passione, con l'apporto e la competenza dei figli Nicoletta, Stefania e Giuseppe.» Senza quel paragrafo il successivo «**Ognuno di loro**…» resta sospeso (vedi §4). Il titolo della sezione e' ora un collegamento a `/lo-studio/`. | `/lo-studio/` (pagina 48) |

**Nessun altro testo e' stato accorciato.** «I Nostri Pazienti», le tre biografie dei medici,
«Perche' noi», i servizi e gli orari sono in homepage per intero e alla lettera: per quei
testi **non esiste un'altra pagina** che li contenga, quindi accorciarli li avrebbe persi.

**Decisione richiesta:** autorizzare la riscrittura degli estratti «Tecnologie» (oggi sono
tagli a meta' frase, non riassunti) oppure lasciarli come sono.

---

## 2. Immagini: cambi, aggiunte e rimozioni

La homepage e' passata da **27 a 12 immagini**. Tutte vengono dalla Media Library esistente:
non ne e' stata caricata nessuna.

### 2.1 Sostituite perche' troppo piccole per il nuovo taglio

| Dove | Prima | Risoluzione | Ora | Risoluzione |
|---|---|---|---|---|
| Hero | `brother-1-jpg.webp` (1207) | **1260 × 838** | `AGA_5209-jpg.webp` (**636**) | **2500 × 1685** |
| Servizi, Ortodonzia | `ortodonzia-jpg.webp` (1096) | 640 × 427 | `shedrack-salami…` (**527**) | 2560 × 2080 |
| Servizi, Implantologia | `implantologia-1…` (156) | **300 × 200** | `implantologia-cover-jpg.webp` (**1226**) | 2560 × 1707 |

Il soggetto resta lo stesso in tutti e tre i casi. L'hero era il problema piu' serio segnalato
nella versione precedente: a 1440px l'immagine veniva ingrandita, su Retina il doppio.
Oggi il fattore di scala misurato a 1440px e' **0,99** (nessun ingrandimento).

**Decisione richiesta:** approvare le tre fotografie scelte.

### 2.2 Aggiunte

| ID | File | Dove |
|---|---|---|
| **636** | `AGA_5209-jpg.webp` | hero |
| **654** | `IMG_20230925_121909…` — il bancone con il marchio e «Dal 1956» | colonna sinistra del capitolo «Lo Studio» |
| **820** | `dental-technolgy-scaled.webp` | colonna sinistra di «Tecnologie» |

### 2.3 Tolte dalla homepage (restano in Media Library e sulle altre pagine)

- Le **otto icone PNG**: `happy-tooth` (801), `braces` (803), `dental-implant` (804),
  `tooth-services` (799), `dentist` (193), `dental-1` (194), `dentist-chair` (195), `tooth-2` (196).
  Erano clipart blu generiche accanto a fotografie vere dello studio e nella versione bocciata
  uscivano a **230 × 153px**. Non aggiungevano informazione che il titolo non desse gia'.
- Le **sette fotografie delle schede «Tecnologie»** (1301, 167, 1062, 769, 1059, 759, 1855):
  la sezione e' ora un elenco di righe, con **una sola** fotografia a sinistra.
- `brother-1` (1207, i tre medici) e `AGA_5209-e1742…` (1653).

**Decisione richiesta:** confermare la rimozione delle icone. Se si vogliono mantenere, serve
un set coerente (stesso tratto, stesso peso, stesso formato) reso a non piu' di 56px.

### 2.4 Quel che resta aperto sulla risoluzione

I **tre ritratti dei medici** restano gli originali piccoli — `nick-jpeg` (1201) 420 × 554,
`Screenshot-2024-02-01…` (1085) **420 × 419**, `peppe-profile` (75) 486 × 642 — ma il nuovo
impianto li rende a **260 × 325px** invece che a ~390: a 1x sono nitidi, su Retina il piu'
piccolo (1085) resta morbido. Il problema e' attenuato, non risolto.

Resta valido quanto segnalato prima: se i ritratti venissero rifatti, andrebbero scattati con
la **stessa inquadratura e lo stesso sfondo**. Oggi sono tre contesti diversi (studio,
poltrona, riunito) e si vede.

**Decisione richiesta:** fornire tre ritratti nuovi e omogenei, oppure accettare la resa attuale.

---

## 3. Testo alternativo scritto da zero su 7 allegati

Il testo `alt` e' informazione di accessibilita', non contenuto editoriale, ma **queste
stringhe non esistevano da nessuna parte**: sono state scritte ora, descrittive e neutre,
senza nessuna affermazione commerciale.

| ID | Testo scritto |
|---|---|
| 636 | «Il dottore con una mamma e due bambini nella sala operativa dello studio» |
| 654 | «Il bancone della reception con il marchio dello studio e la scritta Dal 1956» |
| 527 | «Modello didattico con apparecchio ortodontico fisso» |
| 1226 | «Modello di impianto dentale accanto a modelli delle arcate» |
| 817 | «Strumenti odontoiatrici disposti su un vassoio sterile» |
| 1085 | «Ritratto della Dott.ssa Stefania D'Ambrosio» |
| 75 | «Ritratto del Dott. Giuseppe D'Ambrosio» |

**Decisione richiesta:** rileggerle. Si correggono in Media Library, non nel codice.

---

## 4. Testi con refusi e spaziature anomale, lasciati invariati

Il vincolo «il testo e' letterale» resta, quindi **nulla e' stato corretto**. Questi errori
erano gia' nel testo pubblicato e ora si notano di piu', perche' la tipografia e' piu' grande.

- **`cure dentistiche.Inizialmente situato`** — manca lo spazio dopo il punto («Lo Studio»).
- **«Ognuno di loro, con un'esperienza consolidata…»** — comincia con «Ognuno di loro» ma
  **nel testo della homepage non e' mai stato detto chi siano «loro»**. Il paragrafo che li
  presenta sta su `/lo-studio/` e non e' mai stato in homepage (vedi §1).
- **«Lunedi», «Martedi»** senza accento nella tabella orari (gli altri giorni ce l'hanno).
- **«dell' onesta'»** — spazio dopo l'apostrofo («I Nostri Pazienti»).
- **«Inesti in Ceramica»** nel menu — probabilmente «Innesti».
- **«Chirurgia Piezo Electtrica»** nel menu — «Elettrica».
- **«Chirurgia dei Cannini Inclusi»** nel menu — «Canini».
- **«Protesi Removibili Parziali​»** nel menu — contiene uno zero-width space in fondo.

**Decisione richiesta:** autorizzare le correzioni, una per una o in blocco.

---

## 5. Pubblicita' sanitaria (D.L. Bersani / linee guida FNOMCeO)

Il tono informativo e' stato mantenuto e **non e' stata aggiunta nessuna affermazione nuova**.
Restano pero' nel testo gia' pubblicato alcune espressioni che in Italia sono considerate
promozionali e non informative, e che un ordine professionale potrebbe contestare:

- «servizi di estetica dentale **di prima classe**»
- «**Risultati garantiti** e sorrisi piu' luminosi» / «I nostri trattamenti **garantiscono**
  risultati sorprendenti»
- «**la scelta perfetta**» (Implantologia)
- «ortodonzia **di alta qualita'**», «implantologia **di alta qualita'**»

Nel nuovo impianto due di queste frasi sono **piu' esposte di prima**: «Risultati garantiti e
sorrisi piu' luminosi» e' uno dei quattro titoli della fascia scura «Perche' noi», dove prima
era il testo piccolo di una card.

**Decisione richiesta:** far validare questi passaggi al titolare o al suo consulente, e
indicare come riformularli.

---

## 6. Il filetto rosso: deciso, e' stato tolto

Nella versione precedente ogni titolo di sezione era sottolineato da una barretta sfumata da
`#ff0d00` a trasparente — la `.florence-gradient` del vecchio tema. Quel rosso **non fa parte
della palette** e nel CSS del vecchio tema era un valore isolato.

Il nuovo impianto non lo usa: il «qui comincia una sezione» lo fa un **filetto grigio sottile
sopra** la sezione, con l'occhiello in coda a destra. `settings.custom.accent` e' uscito da
`theme.json`.

**Questa decisione era in sospeso e qui e' stata presa.** Se il titolare la vuole indietro
si rimette — ma riporterebbe la pagina verso l'impianto che e' stato bocciato.

---

## 7. Dati societari e di contatto da confermare

- In footer compare **«©Copyright 2026»**: e' il testo attuale, lasciato invariato.
  Va deciso se renderlo dinamico (anno corrente) o aggiornarlo a mano.
- Il **direttore sanitario** non e' indicato da nessuna parte. Per uno studio odontoiatrico
  e' un'informazione obbligatoria nella comunicazione sanitaria.
- Esistono **tre numeri di telefono** (06 322 5754, 06 361 0625, 375 552 4596). Nella barra
  fissa mobile c'e' il primo fisso, in «CONTATTI» il WhatsApp sul 375 552 4596.
  Confermare che il recapito da privilegiare sia questo.

---

## 8. Scelte di impaginazione da confermare

- **Due sezioni diventano un capitolo solo.** «Lo Studio» e «I Nostri Pazienti» stanno ora
  nella stessa sezione: la storia accanto alla fotografia del bancone, l'accoglienza sotto,
  su tre colonne tipografiche. Nessuna parola e' stata aggiunta o tolta.
- **Ordine delle sezioni.** «I Nostri Orari» e «Vieni a Trovarci» chiudono la pagina, sulla
  mappa. Prima stavano in mezzo.
- **Gli occhielli hanno cambiato posto.** «Dal 1956», «Dottori», «Prestazioni»… erano `<h2>`
  centrati sopra il titolo. Ora sono paragrafi in coda al filetto, a destra. Testo identico.
  Due occhielli sono **nuovi** perche' le sezioni sono cambiate: «Accoglienza» (per «I Nostri
  Pazienti», che prima riusava «Dal 1956») ed **«Email:»** nella barra contatti, che prima non
  aveva etichetta. Sono le uniche due parole aggiunte all'intera pagina.
- **I pulsanti «DI PIU'» e «CONTINUA →» sono diventati collegamenti di testo**, non piu'
  pulsanti con bordo. Il testo e' lo stesso.
- **La vecchia home non e' stata toccata.** La pagina 57 «Home Page» (`/studio-dentistico/`)
  resta pubblicata e raggiungibile. Va deciso se in produzione lasciarla cosi', metterla in
  bozza o reindirizzarla.

**Decisione richiesta:** approvare «Accoglienza» ed «Email:», oppure indicare altre parole.

---

## 9. Font

**Fraunces** (titoli) e **DM Sans** (testo), entrambi con licenza SIL OFL e **inclusi nel
tema**, senza nessuna chiamata a Google (GDPR a posto, come gia' lo era prima con Montserrat).

**Decisione richiesta:** approvazione estetica. Montserrat resta disponibile; il cambio e'
una voce in `theme.json`.

---

## 10. Dipendenza di sviluppo

Nel tema c'e' un `composer.json` con **PHPStan** come sola dipendenza di sviluppo, per
analizzare il codice con target PHP 7.4. `vendor/` e' escluso da Git e **non va caricato in
produzione**: se la pipeline FTP sincronizza l'intera cartella del tema, vanno escluse
`vendor/`, `composer.json`, `composer.lock` e `phpstan.neon.dist`.
