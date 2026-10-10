# AUDIT — rifinitura di design, tutto il sito

Iterazione 5 · 2026-10-07 · misurato sul sito in esecuzione, non letto nel codice.

**68 URL × 3 larghezze (1440 / 768 / 390) = 204 pagine rese.**
Screenshot a pagina intera in `before/`, metriche grezze in `metrics-before.json`,
problemi derivati in `issues-before.json`, bande vuote in `whitespace-before.json`.

---

## 0. Come e' stato misurato

Tre strumenti, perche' uno solo non bastava:

1. **Metriche DOM** (`audit.mjs`) — per ogni immagine: resa, naturale, `object-fit`,
   proporzioni; bersagli sotto i 44px; testo sotto i 16px; righe oltre i 75 caratteri;
   overflow; padding di sezione; stili dei pulsanti.
2. **Bande vuote sui pixel** (`whitespace.py`) — il rilevatore basato sul DOM
   **non trovava niente**: gli elementi annidati riempiono ogni intervallo, quindi
   «fra due blocchi non c'e' spazio» anche quando mezza schermata e' bianca. Quel che
   il titolare vede si misura guardando i pixel: una riga e' vuota se tutti i suoi
   pixel stanno entro 10 livelli dal fondo di quella riga.
3. **Occhio**, su una pagina per tipo di template e su quelle che le metriche segnalano.

Il punto 2 e' la lezione di questa fase: **il DOM diceva zero vuoti, i pixel ne hanno
trovati 764.** Il titolare aveva ragione e lo strumento sbagliato diceva di no.

---

## 1. Il quadro

| Categoria | Segnalazioni | Cause radice |
|---|---:|---:|
| Bande vuote (pixel) | **764** | 3 |
| Mobile (bersagli, testo) | 1.868 | 8 |
| Immagini | 179 | 2 |
| Tipografia | 244 | 3 |
| Componenti | — | 2 |

Millesettecento segnalazioni, **diciotto cause**. Si correggono quelle.

---

## 2. ALTA — da correggere per forza

### H1. La mappa e' un rettangolo bianco finche' non si accettano i cookie

**Dove:** `/contatti/` e `/` (pattern `vieni-a-trovarci`). Tutte le larghezze.
**Misurato:** prima del consenso iubenda sostituisce il `src` dell'iframe con
`cdn.iubenda.com/cookie_solution/empty.html` — una pagina vuota. Nessun segnaposto,
nessun messaggio. Il visitatore vede **736px di bianco** a 1440, **416px** a 390.

```
consenso: false → src = //cdn.iubenda.com/cookie_solution/empty.html · 0 richieste a Google
consenso: true  → src = https://www.google.com/maps/embed?… · 59 richieste
```

E' **la singola fascia vuota piu' grande del sito** e la vedono tutti al primo arrivo.

**Causa:** `.sd-visit__map` non ha nessun fondo ne' contenuto dietro l'iframe.
**Correzione:** un fallback disegnato **dietro** l'iframe — fondo tinto, icona del
pin e l'indirizzo, che sono gia' nel sito. Quando la mappa si carica lo copre.
Nessuna parola nuova. Il plugin non si tocca.

### H2. Le immagini trasparenti su fondo bianco si leggono come vuoti

**Dove:** 25 pagine — ogni trattamento e ogni tecnologia con un ritaglio PNG.
**Misurato:** `trattamenti` a 768px ha una fascia vuota di **500px** fra «Protesi
Removibili Totali» e «Protesi Fissa su Impianto». Non e' spazio: **e' l'immagine.**
Un ritaglio su fondo trasparente, reso con `object-fit: cover` su pagina bianca,
e' indistinguibile dal nulla — per il rilevatore e per l'occhio.

**Causa:** la libreria e' fatta in gran parte di **render 3D ritagliati**, non di
fotografie. Il tema li tratta come fotografie.
**Correzione:** il componente **lastra** (§5.2).

### H3. 95 immagini rese piu' grandi dell'originale

**Misurato a 1440px.** Il rapporto e' `naturalWidth / larghezzaResa`: sotto 1.5 l'immagine
e' morbida su schermi retina, sotto 1.0 e' ingrandita e basta.

| Rapporto | Quante | Esempio |
|---|---:|---|
| **×0.46** | 9 | `.sd-entry__figure` resa **652×489** da un originale **300×215** |
| ×0.52–0.66 | 41 | gallerie prima/dopo: rese 608×456 e 768×576 da originali **400×300** |
| ×0.65 | 8 | `.sd-zig__figure` resa 458×344 da 300×200 |
| ×1.0 | 37 | il taglio giusto esiste ma non c'e' margine per il retina |

**Le dimensioni vere degli originali** (non le derivate servite):

```
300×215  carico-immediato        300×200  chirurgia-computer-guidata
300×200  chirurgia-minimamente-invasiva   300×263  post-estrattiva
300×169  senza-lembo-e-suture    300×200  ortodonzia-fissa
300×127  ortodonzia-invisibile   300×167  ortodonzia-linguale
405×350  pedana-stabilometrica   425×255  trattamenti-con-il-bite
433×577  inesti-in-ceramica      433×577  intarsi-in-ceramica
372×301  protesi-fissa-su-impianto
300×424  fotosan (tecnologia)    370×245  rx-digitale-intraorale (tecnologia)
```

**Tredici originali su trenta non arrivano a 440px di larghezza.** Non e' un problema
di `srcset`: non esiste il file grande. Il vincolo dice di non sostituire le immagini,
quindi **si riduce lo spazio**, non si ingrandisce la fotografia (§5.2), e le tredici
finiscono in `DA-VERIFICARE.md` come «serve una fotografia migliore».

### H4. «Ortodonzia Mobile» non ha nessuna immagine

L'unico trattamento senza immagine in evidenza. Nello zig-zag la sua riga tiene una
colonna da 38% completamente vuota.
**Correzione:** quando manca l'immagine la riga passa a una colonna sola, a tutta
larghezza. E la voce finisce in `DA-VERIFICARE.md`.

---

## 3. MEDIA — mobile

Milleottocentosessantotto segnalazioni, **otto cause**. Tutte a 390px.

| # | Problema | Misura | Dove | Occorrenze |
|---|---|---|---|---|
| M1 | Icone social troppo piccole | **36×36px** (minimo 44) | footer, tutte le pagine | 1.132 |
| M2 | Testo sotto i 16px | **15,08px** | `.sd-rows__text`, `.sd-matrix__text`, `.sd-cases__caption`, biografie | 264 |
| M3 | Pulsanti sotto i 44px | **40px** di altezza | `.sd-btn` ovunque | 233 |
| M4 | Pulsante del menu | **32×32px** | header | 68 |
| M5 | Etichetta pulsante a 13px | «Prenota un appuntamento» | header | 71 |
| M6 | Briciole di pane a 13px | `.sd-crumbs__item` | pagine interne | 33 |
| M7 | Privacy Policy di iubenda | 91×22px | footer | 48 |
| M8 | Recapiti nel footer | 152×25px | footer | 4 |

M1, M3, M4 e M7 sono lo stesso difetto: **nessuna regola globale di bersaglio minimo.**

### M9. Il menu mobile non e' progettato — segnalato dal titolare

Oggi e' la tendina predefinita del blocco Navigation di core: tutte le voci di secondo
livello **aperte insieme**, nessuna gerarchia, nessun pulsante di chiusura disegnato,
niente telefono ne' «Prenota» dentro il pannello, nessuna animazione, il corpo della
pagina continua a scorrere dietro. Con **sette voci e 41 sottovoci** e' una lista di
48 righe buttate sullo schermo. Rifatto da zero in §5.4.

---

## 4. MEDIA — tipografia e componenti

| # | Problema | Misura | Causa |
|---|---|---|---|
| T1 | Righe da ~79 caratteri | `.sd-rows__text` a **631px** | la colonna non ha una misura massima — 214 occorrenze |
| T2 | Righe da ~101 caratteri | `.wp-block-paragraph` a **812px** | il contenuto delle pagine generiche e degli articoli non eredita `sd-prose` |
| T3 | Orfani nei titoli | «anni» da solo in 8 `h1` | i titoli dei casi vanno a capo prima dell'ultima parola |
| C1 | **Due stili di pulsante** | `.sd-btn` raggio **999px** alto 43 · `wp-block-button__link` raggio **4px** alto 50 | i pulsanti dentro il contenuto degli articoli non passano dal tema |
| C2 | Invio del modulo piu' alto | 52px contro 43 | CF7 ha il suo padding |

I padding di sezione sono invece gia' coerenti: **95/95 su tutto il sito**.

### C3. La spaziatura non e' un sistema

`style.css` contiene **83 `clamp()` con 35 valori distinti**, inventati riga per riga:
`clamp(1rem,2vw,1.5rem)`, `clamp(1rem,2vw,1.4rem)`, `clamp(0.75rem,1.5vw,1.25rem)`…
Nessuno sbaglia da solo; insieme non fanno un ritmo. `settings.spacing` in `theme.json`
e' **vuoto**: la scala arriva da Ollie e il tema non la usa.

Questa e' la causa radice di «sembra fatto di fretta», e si corregge per prima (§5.1).

---

## 5. Il piano di correzione — prima il sistema

### 5.1 Una scala di spaziatura in `theme.json`
Sette passi fluidi, dichiarati una volta, piu' le misure del ritmo come
`settings.custom`. Gli 83 `clamp()` sparsi diventano riferimenti a quella scala.
→ risolve C3, e di conseguenza buona parte del ritmo verticale.

### 5.2 Il componente «lastra» per le immagini
Una cornice a proporzione fissa, con fondo tinto e spaziatura interna.
**La modalita' si decide dal dato, non a occhio:** se l'originale e' largo almeno
1,6 volte lo spazio che deve riempire e' una fotografia vera e si usa `cover`;
altrimenti e' un ritaglio e si usa `contain`, senza mai superare la sua misura naturale.
→ risolve H2, H3, H4 e le proporzioni miste, in un colpo solo.

### 5.3 Regole globali di bersaglio e di misura di lettura
Bersaglio minimo 44×44 su tutto cio' che si tocca; testo di contenuto mai sotto i 16px
su mobile; ogni colonna di testo con una misura massima.
→ risolve M1–M8, T1, T2.

### 5.4 Il menu mobile, rifatto
Pannello a schermo intero, gerarchia a fisarmonica con indicatore, pulsante di
chiusura, telefono e «Prenota» dentro, animazione che rispetta
`prefers-reduced-motion`, scorrimento del corpo bloccato, fuoco intrappolato, Escape.
→ risolve M9.

### 5.5 Il fallback della mappa
→ risolve H1.

### 5.6 Un solo stile di pulsante
→ risolve C1, C2.

---

## 6. Esito — compilato dopo le correzioni

Vedi `AUDIT-DOPO.md`.

---

# 6. ESITO — rimisurato sul sito in esecuzione

Stesse 68 URL, stesse tre larghezze, stesso strumento. `after/`,
`metrics-after.json`, `issues-after.json`, `whitespace-after.json`.

| | prima | dopo | |
|---|---:|---:|---|
| **Problemi gravi** | **95** | **0** | ✔ |
| Problemi medi | 2.185 | 274 | −87% |
| Problemi bassi | 11 | 11 | orfani nei titoli, vedi §6.4 |
| — mobile (bersagli, testo) | 1.868 | 101 | −95% |
| — tipografia (righe lunghe) | 244 | 20 | −92% |
| — immagini ingrandite | 166 | 145 | nessuna **sotto ×1**: §6.3 |
| — proporzioni miste | 4 | **0** | ✔ |
| Bande vuote | 764 | 557 | −27% |
| Bande oltre 330px | 11 | **0** (filtrate le immagini) | ✔ |
| Banda vuota piu' alta | 682px | 322px | −53% |

La pagina piu' pesante del sito, `/trattamenti/` a 768px, passa da **10.706 a
8.757 pixel di altezza senza perdere una parola**: −1.949px, quasi due schermi.

## 6.1 Le diciotto cause, una per una

| # | Causa | Esito |
|---|---|---|
| H1 | La mappa e' un rettangolo bianco prima del consenso | **risolto** — fondo tinto con l'indirizzo dietro l'iframe, e la fascia si stringe da 416 a 256px finche' la mappa e' bloccata |
| H2 | I ritagli trasparenti si leggono come vuoti | **risolto** — la lastra con il filetto |
| H3 | 95 immagini rese piu' grandi dell'originale | **risolto** — nessuna immagine e' piu' ingrandita |
| H4 | «Ortodonzia Mobile» non ha immagine | **risolto** — la riga passa a colonna piena |
| M1 | Icone social 36×36 | **risolto** — 44×44 |
| M2 | Testo a 15px su mobile | **risolto** — minimo 16px |
| M3 | Pulsanti alti 40px | **risolto** — minimo 44px |
| M4 | Pulsante del menu 32×32 | **risolto** — 44×44 |
| M5 | Etichetta del pulsante a 13px | **risolto** |
| M6 | Briciole a 13px | **risolto** |
| M7 | Privacy Policy di iubenda 91×22 | **aperto** — §6.2 |
| M8 | Recapiti nel footer 152×25 | **risolto** |
| M9 | Menu mobile non progettato | **risolto** — §6.5 |
| T1 | Righe da 79 caratteri | **risolto** — misura per corpo del testo |
| T2 | Righe da 101 caratteri nel contenuto | **risolto** |
| T3 | Orfani nei titoli | **attenuato** — `text-wrap: balance` |
| C1 | Due stili di pulsante | **risolto** — uno solo |
| C2 | Invio del modulo piu' alto degli altri | **risolto** |
| C3 | La spaziatura non e' un sistema | **risolto** — §6.6 |

## 6.2 Quel che resta aperto, e perche'

- **Il collegamento alla privacy di iubenda** resta 91×22px. Lo inietta il
  plugin con uno stile in linea e un foglio caricato dopo quello del tema;
  alzare la specificita' non e' bastato, probabilmente lo ridimensiona via
  JavaScript. Il plugin non si tocca (regola 4). → `DA-VERIFICARE.md` §15.1
- **Diciannove immagini sotto i 240px di larghezza.** Sono scelte: i ritratti
  nell'elenco «Chi se ne occupa» (216px) e le schede verticali dentro cornici
  orizzontali. Ingrandirle vorrebbe dire ingrandire l'originale.
- **Cinquecentoventi bande vuote** restano, tutte **sotto i 330px**. Sono le
  giunture fra una sezione e l'altra: due padding da 86px che si incontrano
  fanno 172px di stacco, ed e' il ritmo voluto (`DESIGN-SPEC.md` §3.1 ne
  prevedeva fino a 272). La soglia di 160px dello strumento e' piu' severa
  del disegno.

## 6.3 Le immagini: 145 segnalazioni, zero ingrandimenti

Il rilevatore marca come «media» ogni immagine il cui originale sia largo meno
di **una volta e mezza** lo spazio che occupa — cioe' nitida a schermo normale
ma morbida su retina. Marca come «grave» quelle con un originale **piu' piccolo
dello spazio**, cioe' ingrandite davvero.

**Le gravi sono zero.** Le 145 medie sono l'elenco delle fotografie da rifare,
scritto una per una in `DA-VERIFICARE.md` §14 con la misura che servirebbe.
Dove l'originale era piccolo e' stato ridotto lo **spazio**: e' la regola del
mandato, e si vede — le gallerie prima/dopo sono nitide ma piccole, perche'
400×300 e' quanto esiste.

## 6.4 Gli undici problemi bassi

Sono orfani nei titoli: «anni» che resta solo sull'ultima riga negli otto
titoli dei casi clinici, piu' «Bite» e «c'e'». `text-wrap: balance` distribuisce
le righe dove il browser lo supporta, ma su «Paziente "F.N". 41 anni» non c'e'
molto da bilanciare. Si risolverebbe solo cambiando il testo.

## 6.5 Il menu mobile — i quattordici controlli

Verificati con Playwright, uno per uno:

```
✔ si apre                      ✔ il sottomenu si espande
✔ scorrimento del corpo bloccato ✔ aria-expanded passa a true
✔ barra fissa in fondo nascosta  ✔ la freccia ruota
✔ telefono dentro il pannello    ✔ Escape chiude
✔ «Prenota» dentro il pannello   ✔ scorrimento sbloccato
✔ sottomenu chiusi all'apertura  ✔ il pulsante di chiusura chiude
✔ bersagli tutti >= 44px         ✔ fuoco dentro il pannello (10 Tab)
```

Stati fotografati in `compare/menu-390-chiuso.png`, `-aperto.png`,
`-sottomenu.png`.

## 6.6 Il sistema, adesso

Nel foglio di stile del tema non c'e' **nessun valore scritto a mano**:

```
colori hardcoded   0
clamp()            0
max-width in rem   0
```

Tutto viene da `theme.json`: nove passi di spaziatura, undici misure di
lettura, cinque proporzioni di cornice, un bersaglio minimo. Le regole stanno
in `CLAUDE.md` § Il sistema di design.

## 6.7 Che cosa ha insegnato questa misura

Due volte lo strumento ha mentito, e in due direzioni opposte.

**La prima**: il rilevatore basato sul DOM diceva «zero spazi vuoti» mentre il
titolare ne vedeva ovunque. Gli elementi annidati riempiono ogni intervallo,
quindi fra due blocchi non c'e' **mai** spazio — anche quando mezza schermata
e' bianca. Misurando i pixel ne sono saltati fuori 764.

**La seconda**: il rilevatore a pixel conta come vuota qualsiasi riga piatta, e
per lui una fotografia chiara e' piatta come il bianco. Senza saperlo si finisce
a «correggere» cose che ci sono. Ora l'audit registra dove stanno immagini,
mappe e video, e le fasce che ci cadono dentro non si contano.

La lezione: **uno strumento che misura va misurato a sua volta**, e il modo di
farlo e' guardare la pagina.
