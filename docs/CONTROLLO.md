# CONTROLLO — stato del giro di controllo (2026-10-25)

Ramo: `controllo/2026-10-25` · lo guida il capo della squadra (`docs/LOOP-CONTROLLO.md`).

## Checklist

#### Phase 0 — Setup
- [x] 0.1 ramo creato da main pulito
- [x] 0.2 node_modules presente in docs/audit
- [x] 0.3 urls.txt dai sitemap (invariato: 68 × 200 + la 404 di prova)
- [x] 0.4 audit.mjs misura sovrapposizioni, troncamenti e console (`c4b54ac`; prova su / a 390 e 1440: 0 falsi allarmi)

#### Phase 1 — Survey
- [x] 1.1 revisore-tema, revisore-plugin, misuratore (full) in parallelo
- [x] 1.2 coda in questo file, Prima nelle note

#### Phase 2 — Fix loop
- [ ] 2.1 correttore → verificatore → commit, una riga della coda alla volta
- [ ] 2.2 controllo di regressione ogni 5 correzioni e a fine coda

#### Phase 3 — TASKS.md
- [ ] 3.1 F-X-1 (T6 resto) e F-X-2 (T7) nel giro 2.1
- [ ] 3.2 T6/T7 spuntati, RESPONSIVE §1 aggiornato

#### Phase 4 — Close
- [ ] 4.1 misuratore final, Dopo nelle note
- [ ] 4.2 RESPONSIVE §1: righe e versione
- [ ] 4.3 controlli finali
- [ ] 4.4 riepilogo in cima a questo file
- [ ] 4.5 push e stop

## Coda

54 rilievi dei tre agenti → **28 righe `fix`**, più F-R-25 (dal correttore, durante F-R-1) e F-P-16 (dal verificatore, durante F-P-4): **30**. Uniti i doppioni: F-T-4 = F-P-3 (orari del footer),
F-R-17 = F-P-11 (accenti dei giorni), F-R-14 + F-R-16 (taglio e proporzioni del capitolo in home).
F-T-8 (commenti sui `!important`) è assorbito dalla fase 3, F-X-1, come chiede il revisore.

| id | gravità | tipo | titolo | stato |
| --- | --- | --- | --- | --- |
| F-P-1 | high | fix | JSON-LD: `JSON_UNESCAPED_SLASHES` lascia passare `</script>` | fatta `8fc8529` |
| F-R-1 | high | fix | il ritratto del medico scivola di 156px e copre l'h1 (≤ 899) | fatta `881f57f` |
| F-R-25 | medium | fix | sopra 899 il ritratto non si appiccica mai: `.sd-plate` batte `sticky` | da fare |
| F-T-1 | high | fix | `--wp--custom--surface--tintStrong` non esiste: voce corrente senza fondo | fatta `739b17b` |
| F-P-2 | medium | fix | ogni trattamento risponde 200 sotto tutte e sei le radici | fatta `966d8f7` |
| F-P-5 | medium | fix | un'area creata dal pannello sparisce da `/prestazioni/` | fatta `0de3ed0` |
| F-P-4 | medium | fix | i cinque campi dell'area non sono registrati né modificabili | fatta `2268268` |
| F-R-12 | medium | fix | il corpo delle pagine interne non si allinea al titolo (e la 404 galleggia) | da fare |
| F-R-5 | medium | fix | quattro archivi di area senza `<title>` | da fare |
| F-T-2 | medium | fix | `#prenota`, `#orari`, `#chi-siamo` finiscono sotto l'header appiccicato | da fare |
| F-T-3 | medium | fix | `measure--lede` annullata da §19.3; `measure--short` mai applicata | da fare |
| F-T-4 | medium | fix | footer e mobile-cta riscrivono orari e recapiti invece di leggerli (= F-P-3) | da fare |
| F-T-5 | medium | fix | 76px di banda vuota sotto il footer ≤ 781 (vedi vincolo nelle note) | da fare |
| F-R-3 | medium | fix | l'indirizzo nella barra dell'hero finisce in puntini a 320 e 600 | da fare |
| F-R-6 | medium | fix | bersagli < 44px: `summary` delle domande e lente della lightbox | da fare |
| F-R-11 | medium | fix | `/casi-studi/`: 412px vuoti a destra da 1121 in su | da fare |
| F-R-13 | medium | fix | righe da 88 caratteri dentro le colonne di `/studio-dentistico/` | da fare |
| F-R-14 | medium | fix | capitolo in home: foto ingrandita ×1.01 e due proporzioni in griglia (+ F-R-16) | da fare |
| F-T-7 | low | fix | due `max-width` in `ch`, vietati da CLAUDE.md (+ DESIGN-SPEC §3.1) | da fare |
| F-T-9 | low | fix | `.sd-prenota__lede` definita in quattro punti, in nessun markup | da fare |
| F-T-12 | low | fix | due dichiarazioni annullate tre righe dopo, §18.4 | da fare |
| F-T-14 | low | fix | `$sd_c['address']` stampato grezzo in due pattern | da fare |
| F-T-15 | low | fix | `SORRISO_DAMBROSIO_VERSION` ferma a 1.0.0; docblock di `menu.js` sbagliato | da fare |
| F-P-7 | low | fix | `auth_callback` guarda `edit_posts`, non il contenuto (anche `can_edit_area()`) | da fare |
| F-P-8 | low | fix | `$_SERVER['REQUEST_URI']` letto senza sanitizzare | da fare |
| F-P-13 | low | fix | `"anchor": true` sui 14 blocchi, ancora mai resa | da fare |
| F-P-16 | low | fix | il nonce del riquadro area non è legato all'ID del termine | da fare |
| F-R-18 | low | fix | la riga del giorno corrente rientra di 12px | da fare |
| F-R-19 | low | fix | gli estratti delle schede si tagliano a metà parola | da fare |
| F-R-22 | low | fix | il `<title>` della 404 è in inglese | da fare |

## Correzioni fatte

1. **F-P-1** `8fc8529` — JSON-LD: aggiunto `JSON_HEX_TAG`. Con un titolo ostile il blob passava da
   troncato a 1.972 byte con `<script>alert(1)</script>` eseguibile a 2.776 byte interi che
   `json.loads` legge; sulle pagine vere non cambia di un byte (sha1 invariati su 6 URL).
2. **F-R-1** `881f57f` — `top: auto` dentro la media query che c'era già: `.sd-plate` ridichiara
   `position: relative` più in basso nel foglio, quindi azzerare `position` non bastava. Erano tutte
   e 21 le sovrapposizioni del giro: **21 → 0** sulle tre schede professionista.
3. **F-T-1** `739b17b` — nome del token in kebab-case. Il fondo della voce corrente nel pannello passa da
   `rgba(0,0,0,0)` a `rgba(18, 98, 250, 0.14)`; corretta anche `RESPONSIVE.md:59`, che insegnava il
   nome sbagliato ed era l'origine del difetto. Nessun altro token camelCase rotto nel foglio.
4. **F-P-2** `966d8f7` — un 301 in `legacy_redirects()` verso `get_permalink()`, le 217 regole
   intatte. **180 indirizzi che rispondevano 200 → 30**: 30 canoniche a zero hop, 150 duplicati a un
   solo 301, nessun loop. `/prestazioni/<slug>/` resta il ripiego senza area.
5. **F-P-5** `0de3ed0` — l'ordine delle aree si calcola in PHP: `meta_key` in `get_terms()` filtrava
   invece di ordinare. Il `<main>` di `/prestazioni/` ha lo stesso sha1 prima e dopo; un'area senza
   `_sd_ordine` passa da esclusa dalla query a presente e ultima. Provato in memoria, senza
   scritture in banca dati.
6. **F-P-4** `2268268` — cinque `register_term_meta()` col loro sanitize, un `auth_callback` che
   chiede la capability alla tassonomia, e il riquadro su «Aree → Modifica area» con nonce e
   `edit_term`. Prova decisiva: un subscriber con un nonce **proprio e valido** ottiene 0 scritture.
   Checksum del termmeta identico prima e dopo (40 righe, 5 aree).

## Da decidere (titolare)

Sei domande. Nessuna di queste è stata toccata.

1. **La barra fissa in fondo su mobile resta spenta o torna?** (F-T-6) Con lei torna la scorciatoia
   WhatsApp, oggi raggiungibile solo da «Contatti». Finché la decisione è sospesa, pattern, template
   part, voce di `theme.json` e le due sezioni di CSS restano in piedi: non si cancella niente.
   `docs/DA-VERIFICARE.md`.
2. **«Lunedi» e «Martedi» senza accento** accanto a «Mercoledì», «Giovedì», «Venerdì» (F-R-17 = F-P-11).
   Testo pubblicato, quindi non lo correggiamo da soli: sono due caratteri in
   `class-studio.php:64-65` più la scorta del tema, da cambiare nello stesso momento.
   Già aperta in `DA-VERIFICARE.md:233`.
3. **Due refusi dentro gli indirizzi**: `/estetica-dentale/inesti-in-ceramica/` («Innesti») e
   `/trattamenti/cannini-inclusi/` («Canini») (F-P-6). Vanno corretti insieme ai titoli: cambiare lo
   slug lasciando il titolo sbagliato sarebbe peggio. `DA-VERIFICARE.md` §13.1 e §13.4.
4. **`/category/tecnologie/`** cade sul modello di Ollie, con l'h1 fuori da `<main>` (F-R-4). La
   tassonomia del sito è `sd_area` e nessuna pagina porta lì: redirect verso `/tecnologie/` oppure
   `noindex`. Un modello nuovo sarebbe lavoro per URL che non si usano.
5. **La mappa bloccata dal consenso** lascia 736px di fascia quasi vuota su `/` e `/contatti/`
   (F-R-10): si accetta così o si abbassa la cornice finché iubenda non attiva l'iframe? Servirebbe
   testo nuovo. `DA-VERIFICARE.md` §15.4.
6. **`/70-anni-attivita/` è pubblicata e vuota** (F-R-21): titolo, chiamata all'azione, piede.
   Riempirla, rimetterla in bozza o reindirizzarla a `/lo-studio/`. `DA-VERIFICARE.md` §13.5.

Resta aperto anche l'elenco delle **fotografie da rifare** (F-R-15): 142 immagini stanno sotto la
soglia retina perché gli originali in Media Library non sono più grandi. Ingrandirle è proprio quel
che non si fa. `DA-VERIFICARE.md` §2.4, §14, §15.2.

## Note

### Prima (misura `full`, 69 URL × 16 larghezze)

| controllo | n. |
| --- | --- |
| sovrapposizione | 21 |
| overflow orizzontale | 0 |
| console | 16 |
| troncamento | 2 |
| struttura (h1 in `<main>`) | 16 |
| immagini | 143 |
| bersagli/testo mobile | 105 |
| tipografia (riga lunga + orfani) | 20 |
| bande vuote | 0 |
| icone | 0 |
| errori di caricamento | 0 |
| **totale** | **323** (54 high · 258 medium · 11 low) |

Le 323 segnalazioni si riducono a 24 cause distinte. Tastiera: **nessun problema** (F-R-24) — skip
link, pannello, sottomenu, Escape e contorno di fuoco tutti a posto, su `/` a 390 e su `/lo-studio/`
e `/contatti/` a 1440.

### Rilievi archiviati come nota (nessuna modifica)

- **F-R-2** — i 16 errori di console sono il 404 della pagina di prova, richiesto dall'elenco. Le
  altre 68 URL hanno console pulita a tutte le larghezze. Riga di riferimento: console sporca
  **altrove**, in un giro futuro, è una regressione vera.
- **F-R-7** — link «Privacy Policy» 91×22 e badge iubenda 38×38: iniettati dal plugin con stile in
  linea, `DA-VERIFICARE.md` §15.1.
- **F-R-9** — etichette `x-small` a 15px: scala fluida deliberata, `style.css` 19.2.
- **F-R-20** — orfani nei titoli: il testo è letterale e non si riscrive.
- **F-T-10** — `.sd-shelf` (§1, 19 righe) non è usata da nessuno: ambigua fra utility di riserva e
  codice morto.
- **F-T-11** — `measure.statement`, `frame.panel` e il preset `display` di `theme.json` non sono letti
  da nessuno; `display` duplica i valori di `title` e il titolare vede due voci identiche nel
  pannello. `space.xxxl` è in riserva dichiarata.
- **F-T-13** — `RESPONSIVE.md` e `DESIGN-SPEC.md` §3.1 non descrivono più il foglio. Due righe sono
  attivamente dannose e sono le fonti di due difetti della coda: la 59 insegna il nome di token
  sbagliato (F-T-1), DESIGN-SPEC §3.1 insegna `62ch`, unità vietata (F-T-7). Si allineano in 4.2.
- **F-P-9** — i blocchi `sd/*` non hanno `editorScript`: scelta dichiarata (nessuna build), ma il
  prezzo non è scritto da nessuna parte — non compaiono nell'inseritore e nell'editor del sito sono
  blocchi non supportati. Da annotare in `ARCHITECTURE.md` §4.1.
- **F-P-10** — `sd/intestazione` è registrato e non lo usa nessuno; `ARCHITECTURE.md:282` promette un
  `sd/aree` che non è mai stato scritto. Registrati 14, usati 13.
- **F-P-12** — `Text Domain` dichiarato e zero stringhe PHP traducibili: ~80 stringhe per zero
  traduzioni, non si fa ora.
- **F-P-14** — la classe `sd-img` esce su ogni immagine e il tema non la stila (prende le immagini per
  discendenza). Nessuna classe emessa dal plugin manca al tema: era il controllo richiesto.
- **F-P-15** — il controllo della firma delle aree gira a ogni richiesta con un'opzione non
  autoloaded: una query in più per un evento che capita una volta all'anno.
- **`do_blocks()` non è deterministico dentro la stessa richiesta** (misurato dal verificatore di
  F-P-4: 5.837 contro 5.840 byte fra due chiamate, è il contatore del lazy-loading). I confronti di
  resa prima/dopo vanno fatti su **richieste separate**, non su due chiamate in memoria.
- **kses e i delimitatori di blocco**: `wp_kses_post()` *non* rompe i commenti `<!-- wp:… -->`. Il
  core aggancia `wp_pre_kses_block_attributes` su `pre_kses` e codifica i doppi trattini come
  `\u002d\u002d`, con `parse_blocks()` e `do_blocks()` identici. La conclusione di F-P-4 (niente
  kses in lettura) resta giusta, ma per un'altra ragione: la scrittura è già coperta.
- **F-R-8** e **F-R-23** — due difetti dello strumento, non del sito: i link in linea dentro i
  paragrafi contano come bersagli piccoli (30 delle 105 segnalazioni mobile), e lo scorrimento rapido
  di `audit.mjs` fotografa immagini `lazy` non ancora decodificate. Da sistemare in `docs/audit/` per
  non inseguire difetti che non esistono.

### Scostamenti dalle regole del giro

- **Verifica di F-P-2, scrittura in banca dati.** In banca dati i trattamenti senza area sono 0, così
  il verificatore ha creato un trattamento pubblicato senza area per misurare il ripiego
  `/prestazioni/<slug>/`, poi l'ha cancellato con `--force`. La misura è buona — ed era l'unico modo
  di provare quel ramo invece di crederci — ma il giro vuole WP-CLI in sola lettura e niente
  scritture in banca dati: è uno scostamento, non un permesso. Stato ripristinato e verificato:
  30 trattamenti pubblicati, come prima. Per i prossimi: quel ramo si prova in PHP, senza salvare.

**Vincoli dati dal titolare della sessione (25/10, da rispettare nella coda)**

- **F-T-6 — la barra `.sd-mobile-cta` non si cancella.** È `tipo: titolare`: la barra è spenta in
  attesa della decisione sulla scorciatoia WhatsApp (`docs/DA-VERIFICARE.md`). Pattern, template part,
  voce di `theme.json` e le due sezioni di CSS restano in piedi. Va in «Da decidere», non in coda.
- **F-T-5 — lo spazio in fondo deve tornare da sé con la barra.** Il `padding-bottom: 4.75rem` su
  `.wp-site-blocks` (§14) non si toglie e basta: riaccendendo la barra il footer tornerebbe coperto.
  La correzione deve legare lo spazio alla barra — la dichiarazione va insieme alla regola sospesa
  della barra, con un commento che nomina la dipendenza — così riaccendere la barra è una modifica
  sola e lo spazio torna con lei.
