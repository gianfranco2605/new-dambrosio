# Da verificare con il titolare

Punti che richiedono una decisione di chi possiede il sito o i contenuti.
Nessuno di questi blocca la homepage: il lavoro e' completo e funzionante cosi' com'e'.

Aggiornato il 2026-10-07, dopo il rifacimento dell'architettura dei contenuti
(vedi `ARCHITECTURE.md`). I punti nuovi di questa iterazione sono i §11, §12 e §13.

---

## 0. Decisioni aperte della terza iterazione

### 0.1 Il testo dell'hero: tre combinazioni fra cui scegliere

Il testo dell'hero e' **solo testo gia' pubblicato sul sito**. Tre modi di combinarlo.
**Oggi e' in opera la A.**

| | Occhiello | Titolo (`h1`) | Paragrafo |
|---|---|---|---|
| **A** (in opera) | Dal 1956 | Studio Odontoiatrico D'Ambrosio | «Gli specialisti del sorriso. Tradizione – passione – innovazione per prenderci cura del tuo sorriso.» |
| **B** | Gli specialisti del sorriso | Studio Odontoiatrico D'Ambrosio | «Pilastro dell'odontoiatria a Roma da oltre 68 anni, eccellenza odontoiatrica, il vostro sorriso, la nostra passione.» (da `/lo-studio/`) |
| **C** | Dal 1956 | Studio Odontoiatrico D'Ambrosio | «I nostri pazienti vengono accolti in un ambiente elegante ed amichevole, con professionalità, competenza ed igiene.» |

**Attenzione sulla B:** contiene «**oltre 68 anni**». E' un numero che invecchia da solo
— nel 2026 sono 70 — e altrove il sito dice gia' «1956». Se si sceglie la B, va deciso
se aggiornare il numero o toglierlo.

Il titolo resta il nome dello studio in tutte e tre: e' l'unico testo esistente che
funzioni da `h1`, e serve anche per la ricerca. Nel riferimento Prism il titolo e'
invece una frase descrittiva («Oral Surgery and Implant Care in Summit, NJ») — per
averne una qui servirebbe **scrivere testo nuovo**, che il mandato vieta.

**Decisione richiesta:** A, B o C. Oppure autorizzare una frase nuova per il titolo.

### 0.2 La fotografia dell'hero: in Media Library non c'e' un ritratto di paziente

Il riferimento usa **un paziente solo**, in poltrona, sorridente, a figura grande sulla
destra. In Media Library **non esiste una fotografia cosi'**. Quello che c'e':

| ID | Che cos'e' | Perche' non va (o va) |
|---|---|---|
| **636** `AGA_5209` 2500×1685 | **in opera** — il dottore con una mamma e due bambini, nello studio vero | La composizione e' quella giusta (soggetti a destra, parete a sinistra) ed e' lo studio vero. Ma sono **quattro persone in una stanza**, non un primo piano: il lato destro resta piu' affollato del riferimento. |
| 604 `cover-estetica1` 2560×1709 | ritratto di donna che si tocca il viso, fondo **ciano piatto** | E' uno scatto da banca immagini, non lo studio; il ciano non appartiene alla palette e con il velo scuro sopra diventa fango. |
| 1601 / 1602 / 1625 | primi piani clinici di denti, prima/dopo | Nessun volto: non reggono un hero. |
| 1207 `brother-1` 1260×838 | i tre medici in posa | **Troppo piccola** per un hero a tutta larghezza. |

**Decisione richiesta:** commissionare uno scatto di un paziente in poltrona — volto
grande, sguardo in camera, molta luce, spazio vuoto a sinistra per il testo — oppure
accettare la 636. Serve almeno **2400px di larghezza**.

### 0.3 Social: manca il profilo Google

Nell'hero compaiono **solo i profili trovati sul sito**:

| Rete | Indirizzo | Dove l'ho trovato |
|---|---|---|
| Facebook | `facebook.com/studioodontoiatricodambrosio/` | menu (post `wp_navigation` 4) e footer |
| Instagram | `instagram.com/studioodontoiatricodambrosio/` | menu e footer |
| WhatsApp | `api.whatsapp.com/send/?phone=3755524596` | footer e tutte le CTA del sito |

Due cose da sapere:

- Il riferimento mostra anche un'icona **Google** (scheda Google Business, dove stanno
  le recensioni). **Sul sito non c'e' nessun link a una scheda Google.** Per uno studio
  odontoiatrico e' di solito il canale piu' importante: se la scheda esiste, serve
  l'indirizzo.
- In una parte template **vecchia** (`wp_template_part` 562, non piu' in uso) c'e'
  `facebook.com/stefania.dambrosio.18`, un **profilo personale**. Non e' stato usato:
  nelle parti piu' recenti e' stato sostituito dalla pagina dello studio. Confermare
  che sia giusto ignorarlo.

**Decisione richiesta:** fornire l'indirizzo della scheda Google Business, se esiste.

### 0.4 Il modulo di contatto: tre cose sistemate, una da decidere

Il modulo «Modulo contatto» (CF7, post 2063) era **configurato a meta'**:

1. **Il modello dell'email puntava a campi inesistenti.** Usava i segnaposto di default
   di CF7 (`[your-name]`, `[your-email]`, `[your-subject]`, `[your-message]`) mentre i
   campi del modulo si chiamano `nome-cognome`, `telefono`, `email`, `trattamento`,
   `messaggio`. L'email sarebbe partita **vuota**, con un `Reply-To:` non valido.
   E' stato riscritto: ora riporta tutti i campi e il `Reply-To` e' l'email del paziente.
2. **I messaggi erano in inglese** («One or more fields have an error…») su un sito
   `it_IT`. Tradotti.
3. **Il destinatario e' `[_site_admin_email]`**, cioe' oggi `gianf2605@gmail.com` —
   l'indirizzo dell'amministratore WordPress, non dello studio.
   **Decisione richiesta:** a quale indirizzo devono arrivare le richieste?
   Probabilmente `studio@sorrisodambrosio.it`.
4. **Nessuna ricevuta automatica al paziente.** CF7 puo' mandare una seconda email di
   conferma a chi compila. E' **disattivata**: per la sanita' una conferma automatica
   va scritta con attenzione (non deve sembrare la conferma di un appuntamento che
   invece non e' ancora fissato).
   **Decisione richiesta:** si vuole la ricevuta? Con che testo?

Verificato end-to-end: invio valido → messaggio di successo → email ricevuta da Mailpit
con tutti i campi al posto giusto.

### 0.5 Il pulsante «Prenota» sparisce sotto i 1340px

Misurato sulla pagina resa: logo, otto voci di menu, telefono e «Prenota un
appuntamento» chiedono **1328px** di finestra. Sotto quella soglia qualcosa deve cedere.
Oggi cede «Prenota» (resta nell'hero e nella fascia orari); sotto 1120px anche il menu
passa alla tendina.

L'alternativa e' **accorciare il menu**: otto voci di primo livello sono tante, il
riferimento ne ha sei. «Estetica» e «Denti e postura» potrebbero stare sotto
«Trattamenti».

**Decisione richiesta:** accettare cosi', oppure riorganizzare il menu.

> **Aggiornamento 2026-10-07 — questa e' stata presa.** Con i nuovi indirizzi
> le etichette si erano allungate e la **prima voce finiva sopra il logo** a
> 1440px (voce a 221px, logo fino a 232). Il menu e' sceso a **sette** voci:
> «Contatti» sta sotto «Lo Studio», insieme a «I Professionisti» e «Casi
> Studi». Resta raggiungibile anche dal pulsante «Prenota un appuntamento»
> nell'header, dalla fascia di chiusura di ogni pagina interna e dal footer.
> Se il titolare preferisce «Contatti» di primo livello, deve cedere qualcos'altro.

### 0.6 La barra fissa in fondo su mobile ora e' ridondante

Su mobile convivono il **pulsante telefono nell'header** (nuovo) e la **barra fissa in
fondo** con telefono e WhatsApp (dalla seconda iterazione). Due volte lo stesso numero.

**Decisione richiesta:** tenerle entrambe, oppure togliere il telefono dalla barra in
fondo e lasciarci solo WhatsApp e «Prenota».

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
analizzare il codice con target **PHP 8.3**. `vendor/` e' escluso da Git e **non va caricato in
produzione**: se la pipeline FTP sincronizza l'intera cartella del tema, vanno escluse
`vendor/`, `composer.json`, `composer.lock` e `phpstan.neon.dist`.


---

## 11. Le relazioni inferite fra i contenuti

Il nuovo modello collega trattamenti, tecnologie, medici e casi.
**Tutte le relazioni derivano dal testo o dalla struttura gia' pubblicati**:
nessuna e' inventata. Ma non tutte hanno la stessa forza, e queste vanno
confermate da chi conosce lo studio.

### 11.1 Strutturali — nessun dubbio

Vengono da dove una cosa stava, non da come era scritta.

| Relazione | Da dove |
|---|---|
| ogni trattamento → la sua area | la pagina in cui la sezione viveva |
| Dott.ssa Stefania → Ortodonzia | `/ortodonzia/` conteneva il suo titolo e i suoi casi |
| Dott. Giuseppe → Estetica Dentale | `/estetica-dentale/` conteneva il suo titolo e i suoi casi |
| i 4 casi `966 980 1012 1015` → Stefania, Ortodonzia | stavano sotto il suo titolo in `/casi-studi/` |
| i 4 casi `1598 1022 1026 1030` → Giuseppe, Estetica | idem |

### 11.2 Testuali — il testo lo dice

| Relazione | La frase |
|---|---|
| Chirurgia Computer-guidata → Software per la Chirurgia Guidata | «grazie all'utilizzo di un software innovativo… progettazione computerizzata» |
| Senza Incisione Lembo e Suture → Software per la Chirurgia Guidata | «nota come chirurgia computer-guidata» |
| Senza Incisione Lembo e Suture → TC Cone-Beam | «i risultati della Cone-Beam (radiografia 3D)» |
| Nicoletta → Parodontologia | «si distingue nell'ambito della Parodontologia» |
| Nicoletta → area Estetica Dentale | «e dell'estetica del sorriso» |
| Stefania → Ortodonzia Fissa, Linguale, Invisibile | «tecniche tradizionali, linguali e invisibili» |
| Giuseppe → area Implantologia | «eccelle nei campi dell'Implantologia» |
| Giuseppe → Parodontologia, Protesi Fissa Estetica | «Parodontologia e Protesi Estetica» |

**Nota:** «Ortodonzia Mobile» **non** e' attribuita a Stefania. La sua
biografia nomina tre tecniche e quella non c'e': dedurla sarebbe inventare.
Se se ne occupa, va spuntata.

### 11.3 Inferite — DA CONFERMARE, una per una

Qui ho dedotto. Ognuna e' plausibile, nessuna e' scritta.

| # | Relazione | Perche' l'ho dedotta | Il dubbio |
|---|---|---|---|
| a | Implantologia Post Estrattiva → RX Digitale Intraorale | «ogni caso va pianificato mediante un attento studio radiografico» | «radiografico» e' generico: potrebbe essere la TC Cone-Beam o l'ortopantomografo |
| b | Chirurgia dei Cannini Inclusi → Ortopantomografo | «esami radiografici», e la scheda dell'Ortopantomografo cita proprio i «denti inclusi» | come sopra |
| c | Laser Terapia → Chirurgia Piezo Elettrica | la scheda «Piezo» descrive il laser a diodi in uso «sin dal 1998» | **la scheda Piezo mescola due argomenti**: comincia con gli ultrasuoni e prosegue sul laser. Potrebbe essere un errore del testo originale |
| d | Sbiancamento Professionale → Chirurgia Piezo Elettrica | «sbiancamento dei denti con il laser», stessa scheda | come sopra: se il laser diventasse una scheda sua, questo legame andrebbe li' |
| e | Giuseppe → Chirurgia Rigenerativa | «Chirurgia Orale e Osseo-Ricostruttiva» | «osseo-ricostruttiva» e «rigenerativa» non sono sinonimi esatti |

**Decisione richiesta:** confermare o correggere le cinque. Si fa dal pannello,
senza toccare il codice: il riquadro «Tecnologie collegate» sul trattamento e
«Si occupa di» sul medico (vedi `GUIDA-CLIENTE.md`).

**Collegata:** la scheda **«Chirurgia Piezo Electtrica (Piezo-surgery)»**
contiene due argomenti diversi — la chirurgia piezoelettrica e il laser a
diodi. Se il laser merita una scheda sua, il testo va diviso; ma dividerlo
significa decidere dove tagliare, e quella e' una scelta di contenuto.

---

## 12. Gli orari e i recapiti non sono modificabili dall'admin

Stavano nel tema, ora stanno nel plugin: una fonte sola, letta da intestazione,
hero, fascia orari, pagina contatti, footer e dati strutturati. Cambiarli in un
punto li cambia in tutti e sei.

Il limite onesto: **sono in un file, non in una schermata**. Per modificarli
serve uno sviluppatore.

Non e' stata fatta una pagina di opzioni perche' questi dati cambiano una volta
ogni molti anni, e una schermata in piu' e' una schermata in piu' da mantenere.

**Decisione richiesta:** va bene cosi', oppure si vuole poterli modificare dal
pannello? E' un lavoro breve e non tocca nient'altro.

---

## 13. Punti aperti dell'iterazione 4

### 13.1 «Denti e postura» non ha un testo introduttivo

Quattro aree su cinque hanno un'introduzione, presa dai pannelli «Nostri
servizi» della homepage — testo gia' pubblicato. Per «Denti e postura» quel
pannello **non e' mai esistito**: in homepage le quattro voci erano Estetica,
Ortodonzia, Implantologia e «Altri Servizi».

La sua pagina oggi parte dal titolo e va dritta ai tre trattamenti. Funziona,
ma e' l'unica area che non si presenta.

**Decisione richiesta:** una frase di presentazione (2-3 righe, tono
informativo), oppure lasciare cosi'.

### 13.2 Il testo `alt` delle fotografie prima/dopo

Le sedici immagini delle otto gallerie hanno come testo alternativo il nome
di lavorazione del file. Misurato sulla pagina resa:

- «after case 4», «before case3», «caso 1 before», «after image case 2»… —
  per chi usa un lettore di schermo non dicono niente;
- due gallerie danno **lo stesso** testo a entrambe le immagini
  («case 3 giuseppe d'ambrosio» due volte), quindi prima e dopo non si
  distinguono;
- una coppia e' **scambiata**: l'immagine «prima» porta «After case 1 dott
  Giuseppe» e quella «dopo» porta «Before case 1 dott Giuseppe».

Non e' stato toccato: sono dati del plugin before/after, e correggerli vuol
dire scrivere otto descrizioni cliniche nuove.

**Decisione richiesta:** autorizzare la scrittura di otto testi alternativi
descrittivi e neutri (per esempio «Arcata superiore prima e dopo il
trattamento ortodontico»), o lasciare come sta.

### 13.3 Le gallerie prima/dopo salvano indirizzi assoluti

Il plugin before/after salva nel database l'indirizzo completo delle immagini
(`http://sorrisodambrosio.local/wp-content/uploads/...`). **Non e' un problema
introdotto da questo lavoro**, ma al momento di andare in produzione quelle
sedici immagini non si vedranno finche' non si fa un search-replace sul
database.

Tutto il resto del sito non ha questo problema: le immagini del tema e dei
nuovi contenuti si risolvono a runtime.

**Da fare al deploy:** `wp search-replace 'sorrisodambrosio.local' 'sorrisodambrosio.it'`
sulla tabella dei post meta, dopo il backup.

### 13.4 Gli slug non riproducono i refusi delle vecchie ancore

Le vecchie ancore contengono errori: `carico-inmediato`, `flapes`,
`ortodonzia-invisiblile`, `igieneprofesionale`, `inesti-in-ceramica`,
`facette-estetiche`, `cannini-inclusi`. Gli indirizzi nuovi sono scritti bene
(`carico-immediato`, `senza-lembo-e-suture`, `ortodonzia-invisibile`,
`igiene-e-profilassi-avanzata`...). Un refuso dentro un'ancora e' invisibile;
dentro un URL resta per sempre.

**Le vecchie ancore restano** come `id` sulle righe delle pagine d'area, quindi
un vecchio link esterno atterra ancora nel punto giusto.

Restano invece com'erano gli slug dove il **titolo** contiene il refuso
(`inesti-in-ceramica`, `cannini-inclusi`): correggere lo slug senza correggere
il titolo sarebbe incoerente, e il titolo e' testo (vedi §4).

**Decisione richiesta:** autorizzare la correzione dei due titoli — «Inesti» →
«Innesti», «Cannini» → «Canini» — e dei rispettivi indirizzi.

### 13.5 La pagina «70 anni attivita» e' vuota

`/70-anni-attivita/` (pagina 1874) e' **pubblicata e completamente vuota**: non
contiene nessun blocco. Ha pero' una descrizione per Google che parla del 1956.

Non e' stata toccata (regola: non si cancella niente). Oggi risponde 200 e
mostra solo intestazione e fondo pagina.

**Decisione richiesta:** cosa farne — riempirla, metterla in bozza, o
reindirizzarla a `/lo-studio/`.

### 13.6 I ritratti dei medici su `/professionisti/`

La pagina nuova mostra i tre ritratti in tre colonne da ~310px. Gli originali
sono 420×554, 420×419 e 486×642: a schermo normale vanno bene, su Retina il
piu' piccolo resta morbido. E' lo stesso problema gia' segnalato al §2.4,
nello stesso stato: attenuato dall'impaginazione, non risolto.
