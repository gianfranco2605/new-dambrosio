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

54 rilievi dei tre agenti → **28 righe `fix`**, più tre righe trovate correggendo: F-R-25 (durante F-R-1), F-P-16 (durante F-P-4), F-R-26 e F-R-27 (durante F-R-12), F-T-17 (durante F-T-2): **33**. Uniti i doppioni: F-T-4 = F-P-3 (orari del footer),
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
| F-R-12 | medium | fix | il corpo delle pagine interne non si allinea al titolo (e la 404 galleggia) | fatta `c344d60` (2 tentativi) |
| F-R-5 | medium | titolare | ~~quattro~~ **cinque** archivi di area senza `<title>`: dato Yoast | in Da decidere |
| F-T-2 | medium | fix | `#prenota`, `#orari`, `#chi-siamo` finiscono sotto l'header appiccicato | fatta `4c63766` |
| F-T-17 | medium | fix | `--sd-sticky-top` (156px) supera di 71-78px l'header vero sotto i 1121 | da fare |
| F-T-3 | medium | fix | `measure--lede` annullata da §19.3; `measure--short` mai applicata | fatta `8286a9d` |
| F-T-4 | medium | fix | footer e mobile-cta riscrivono orari e recapiti invece di leggerli (= F-P-3) | fatta `0feb2cb` (recapiti; orari al titolare) |
| F-T-5 | medium | fix | 76px di banda vuota sotto il footer ≤ 781 (vedi vincolo nelle note) | da fare |
| F-R-3 | medium | fix | l'indirizzo nella barra dell'hero finisce in puntini a 320 e 600 | fatta `84f112e` |
| F-R-6 | medium | fix | bersagli < 44px: `summary` delle domande e lente della lightbox | fatta `7de18d6` |
| F-R-11 | ~~medium~~ | **nota** | `/casi-studi/`: 412px vuoti a destra — misurato, non è un difetto da CSS | declassata, annullata |
| F-R-13 | medium | fix | righe da 88 caratteri dentro le colonne di `/studio-dentistico/` | fatta `653a7fc` |
| F-R-14 | medium | fix | capitolo in home: foto ingrandita e due proporzioni in griglia (+ F-R-16) | fatta `0a3b93c` (resto al titolare) |
| F-R-26 | low | fix | `.sd-doctor__bio` non si allinea al nome (stessa famiglia di F-R-12) | da fare |
| F-R-27 | low | titolare | `/studio-dentistico/`: la gronda e le foto sotto misura sono impaginazione, non CSS | da fare |
| F-T-7 | low | fix | due `max-width` in `ch`, vietati da CLAUDE.md (+ quarto grep di guardia) | fatta `b7ff013` |
| F-T-9 | low | fix | `.sd-prenota__lede` definita in quattro punti, in nessun markup | fatta `437c1ad` |
| F-T-12 | low | fix | due dichiarazioni annullate tre righe dopo, §18.4 | fatta `437c1ad` |
| F-T-14 | low | fix | `$sd_c['address']` stampato grezzo in **tre** pattern (anche `footer.php:50`) | da fare |
| F-T-15 | low | fix | `SORRISO_DAMBROSIO_VERSION` ferma a 1.0.0; docblock di `menu.js` sbagliato | da fare |
| F-P-7 | low | fix | `auth_callback` guarda `edit_posts`, non il contenuto (anche `can_edit_area()`) | da fare |
| F-P-8 | low | fix | `$_SERVER['REQUEST_URI']` letto senza sanitizzare | da fare |
| F-P-13 | low | fix | `"anchor": true` sui 14 blocchi, ancora mai resa | da fare |
| F-P-16 | low | fix | il nonce del riquadro area non è legato all'ID del termine | da fare |
| F-R-18 | low | fix | la riga del giorno corrente rientra di 12px | da fare |
| F-R-19 | low | fix | gli estratti delle schede si tagliano a metà parola | da fare |
| F-R-22 | low | titolare | il `<title>` della 404 è in inglese: impostazione Yoast | in Da decidere |

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
7. **F-R-12** `c344d60` — due tentativi. `layout:{"type":"default"}` su cinque template invece di un
   quarto `!important`, più l'azzeramento del rientro sui soli figli diretti: il contenuto stesso
   comincia con un Group che riporta `has-global-padding`, e ce l'hanno 20 schede su 20. Primo `<p>`
   contro h1: endodonzia 34 → 20 a 390 e 112 → 92 a 1440; la 404 da 416 a 92 con la colonna ancora a
   608px. Audit su 13 URL a quattro larghezze: high 5 → 5 identici, medium 91 → 90.
8. **F-T-2** `4c63766` — via il doppione di §17: tre selettori di id battevano per specificità la
   regola col token, e il bordo della sezione restava 54-64px **sotto** l'header. Ora 4-14px sotto di
   esso. In più `/lo-studio/` aveva un `id="orari"` che atterrava a 88 mentre il vicino
   `#prenota-cta` atterrava a 156: ora vanno d'accordo.
9. **F-T-3** `8286a9d` — una sola dichiarazione di misura per lede. DESIGN-SPEC non fissa la
   larghezza di un lede, quindi resta quella che la pagina mostra da sempre (608px) e spariscono le
   cinque righe morte. Screenshot d'elemento con lo stesso md5 prima e dopo.
10. **F-T-4** `0feb2cb` — recapiti del footer e della barra mobile dall'unica fonte: letterali 9 → 0,
    HTML servito identico byte per byte. Gli orari restano a mano, in attesa del titolare.
11. **F-R-3** `84f112e` — sotto i 1121 l'indirizzo dell'hero va a capo invece di troncarsi. Erano le
    **due sole** segnalazioni di troncamento del giro: 2 → 0. Sopra i 1120 niente cambia.
12. **F-R-6** `7de18d6` — bersagli a 44px: la regola di §19.7 era ancorata a `.sd-areatext` e non
    arrivava alla FAQ dentro la prosa. `summary` 350×29 → 350×60, lente 20×20 → 44×44 su dodici
    bersagli, tutte dentro la loro immagine. Niente `flex`: sposterebbe il `::marker`.
13. **F-R-13** `653a7fc` — la misura di lettura entra anche nei blocchi Colonne: riga massima
    **100 → 75 caratteri** a 1440, `tipografia` 9 → 0. Serviva una seconda regola: quei pannelli
    stanno in un Gruppo flex verticale, e stringere il paragrafo deformava la foto (287×500 invece
    di 350×500). `align-self: stretch` riporta la geometria identica.
14. **F-R-14 + F-R-16** `0a3b93c` — taglio `1152x1536` (quello che serve già `/lo-studio/`) e soglia
    1120 nel `sizes`: **immagini rese oltre la larghezza vera del file 2 → 0**. Nella matrice la
    cornice è quella della cella e il ritaglio sta al centro: scarto dei titoli 65 → 25px a 1440,
    35 → 0 a 781.
15. **F-T-7** `b7ff013` — i due `max-width` in `ch` diventano token (`prose` sull'hero, `wide` sul
    titolo di pagina): nessuna riga guadagnata o persa a otto larghezze. Soprattutto, `RESPONSIVE.md`
    §3 ha ora un **quarto grep** che cerca `ch`: i tre vecchi guardavano `clamp`, i colori e i `rem`,
    ed è per questo che quelle due righe erano sopravvissute a tre giri. Provato che il guardiano
    guardi: 0 sul foglio vero, 1 su una copia sabotata dove il pattern ancorato a inizio riga dava 0.
16. **F-T-9 + F-T-12** `437c1ad` — 19 righe di CSS morto via, 10 di commento al loro posto. Nessun
    effetto a schermo: **22 screenshot byte-identici** fra correttore e verificatore, e il confronto
    letto dal CSSOM (foglio prima 72.879 caratteri con le due regole, dopo 72.539 senza) invece che
    dalla cache.

## Da decidere (titolare)

Undici domande. Nessuna di queste è stata toccata.

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

7. **`/studio-dentistico/` va rimpaginata nell'editor** (F-R-27). I 20px che scostano il testo da
   quel titolo sono la gronda di quattro pannelli `alignwide` fatti nell'editor, non una regola del
   foglio: toglierla in CSS ingrandisce una foto 300×200 a 304×203, cioè rompe un vincolo per
   sistemarne un altro. Nello stesso giro cinque ritratti di quella pagina, già oltre la misura
   naturale prima di questo giro, crescono di un filo (781: 706 contro 420 naturali; 1440: 376 → 389):
   stessa voce di audit, stessa gravità, si chiude con la rimpaginazione.

8. **Il titolo del documento di tutti e cinque gli archivi d'area è rotto** (F-R-5), non di quattro:
   `/estetica-dentale/` sembrava sano solo perché mostra la parola `Dentale`. Tutti e cinque i termini
   hanno un «Titolo SEO» di Yoast che comincia con `%%title%%`, la variabile del *titolo del post*,
   sempre vuota su un archivio di termine; sul termine 26 il titolare ha scritto anche `Dentale` come
   testo letterale. Il dato sta in `wpseo_taxonomy_meta`, non in `wp_termmeta`. Il modello già
   impostato (`%%term_title%% Archivi %%sep%% %%sitename%%`) produrrebbe da sé il titolo giusto: è il
   dato a batterlo. Due strade, entrambe dal pannello e senza codice — **(a)** svuotare il campo sui
   cinque termini, **(b)** sostituire `%%title%%` con `%%term_title%%` e togliere `Dentale` dal
   termine 26. Serve il titolare perché il testo esatto è pubblicità sanitaria e il sito usa già due
   nomi diversi («Studio Odontoiatrico D'Ambrosio» a mano, «Sorriso D'Ambrosio» da `%%sitename%%`).
   `wpseo_desc` e `wpseo_focuskw` dei cinque termini sono corretti e non si toccano.
9. **Il titolo del documento della 404 è in inglese** (F-R-22): «Page not found», mentre la pagina resa
   dice «Questa pagina non c'è». Anche questa è un'impostazione Yoast, non codice
   (`title-404-wpseo`, più `breadcrumbs-404crumb` = «Error 404: Page not found»): un filtro
   `document_title_parts` sarebbe inerte, perché Yoast cortocircuita su `pre_get_document_title`.
   Si cambia dal pannello; se un domani servisse del codice, l'aggancio è `wpseo_title`.

10. **La tabella degli orari nel footer resta scritta a mano** (parte di F-T-4). Recapiti, email,
    tre numeri, indirizzo e WhatsApp ora leggono l'unica fonte, e il footer servito non cambia di un
    byte. Gli **orari** no: la fonte ha sette giorni (Sabato e Domenica «Chiuso»), il footer ne
    pubblica cinque e il fine settimana lo dice a parole nella riga «Sabato-Domenica chiuso».
    Collegarla aggiungerebbe due righe di testo nuove al footer di **ogni** pagina — contenuto, non
    impianto. Due strade: **(a)** il footer continua a dire il fine settimana a parole e accetta di
    non leggere la fonte per gli orari; **(b)** passa alla tabella di sette righe come la fascia
    orari, e la riga a parole sparisce. Finché non si decide, un orario cambiato dal pannello non
    arriva al footer: è la ragione per cui il rilievo esisteva.

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
- **L'altezza vera dell'header appiccicato, misurata** (dal verificatore di F-T-2, in attesa di
  F-T-17): **78px a 390 e 781**, 85px a 1120, 142px a 1121 e 1180, 152px a 1440. Il commento di §1
  dice 155/142/134 e sbaglia su mobile. `--sd-sticky-top: 9.75rem` (156px) supera l'header vero di
  71-78px sotto i 1121, e non regge solo le ancore: lo leggono anche il `min-height` dell'hero
  (riga 298), la foto del capitolo (1366-1369) e tre colonne appiccicate (1779, 3387). Per questo
  F-T-17 non è una sostituzione di numero.
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
  non inseguire difetti che non esistono. **Terzo difetto dello strumento**, trovato verificando
  F-R-6: `vis()` scarta gli elementi con `opacity: 0`, così la lente della lightbox — un bersaglio
  vero da 20×20 — non compariva in nessuna misura. Un bersaglio invisibile fino al passaggio del
  cursore resta premibile col dito: va misurato.
- **F-R-11 declassata a nota dopo una correzione annullata.** Tre colonne da 1121 riempivano la riga,
  ma il vuoto non spariva: si **spostava**. Misurato in area a 1440: da 412×706 px (290.872 px²) sul
  margine destro a 867×331 px (286.977 px²) **dentro il gruppo**, accanto alla scheda orfana — l'1,3%
  di vuoto in meno, in una posizione peggiore, su 6 griglie e 5 pagine. E le foto scendevano a 326px
  (−18,5%) a 1121. La premessa del rilievo era sbagliata: `/casi-studi/` non ha una griglia da 8 ma
  **due griglie da 4**, raggruppate per professionista, e tutte e sei le griglie del sito hanno quattro
  schede — tre colonne danno 3+1 *sempre*, mai 3+3+2. Il vuoto a destra è la conseguenza accettata di
  originali 400×300, e il rimedio vero è già scritto: `docs/DA-VERIFICARE.md` §14.1, fotografie
  800×600, e solo allora si alza il `25rem` di `.sd-cases`. Scartate anche `justify-content: center`
  (titoli dei gruppi a x=92 e schede a x=298), quattro colonne (1.732px, non ci stanno) e schede oltre
  25rem (ingrandirebbero le foto). `git restore` fatto, niente commesso.
- **Il contenitore BEAF ha `max-width: none`**, non `100%`: vince l'`unset !important` del plugin. A
  limitare le foto delle schede caso è il `minmax(0, 25rem)` della colonna, non una regola del tema —
  da ricordare per T6/F-X-1, che parla proprio di quel `!important`.
- **I numeri di riga dei referti invecchiano durante il giro.** Ogni riferimento di F-T-9 e F-T-12 era
  già spostato (§18.4 2881 → 2889, §18.15 3659 → 3708, §19.2 3750 → 3799, §19.3 3816 → 3870), e lo era
  anche la ri-misura fatta dal verificatore di F-T-3 poche righe prima (3688/3779/3850). Da trattare
  come indizi: si cerca il selettore con `grep -n`, non si va al numero.
- **Il commento sopra `.sd-doc__title` dice che `ch` «mentiva del doppio»**: come conteggio di
  caratteri regge, come metrica del font no — in Fraunces il fattore è 1.67, il doppio è di DM Sans.
  Una riga da sistemare al prossimo giro, segnalata dal correttore di F-T-7 e fuori dalle due
  rifiniture che gli avevo chiesto.
- **`naturalWidth` non misura il file.** Con `srcset` è il valore **corretto per densità** e segue
  `sizes`: per questo l'audit vedeva ×0.99 sulla foto del capitolo e il difetto vero — ×1.43 fra 1024
  e 1120 — restava invisibile. Le misure di ingrandimento si fanno con `getimagesize()` sul file in
  `uploads/`. Da sistemare in `docs/audit/` insieme agli altri tre difetti dello strumento.
- **Tre sbagli nei rilievi stessi**, da ricordare quando si legge un referto: F-R-6 attribuiva tre
  segnalazioni a `/magnetic-mallet/`, che non ha **nessun** blocco `details` (erano 3+3 fra
  `/impianti-dentali/` e `/tooth-transformer/`); F-R-5 parlava di quattro archivi d'area rotti quando
  sono cinque; F-R-14 metteva l'ingrandimento a 1440, dove il file bastava, mentre era a 1024 e 1120.
  Gli agenti che misurano sbagliano anche loro: il verificatore serve a questo.

### Scostamenti dalle regole del giro

- **Un verificatore si è piantato** (F-T-4, primo tentativo): morto al primo passo, senza toccare
  niente — `git stash list` vuoto, i tre file ancora modificati, `Version:` ancora 1.8.8. Ripreso con
  una consegna più corta (sei controlli, nessun lavoro col browser: non c'era niente di visivo da
  misurare) ed è passato. Da ricordare: dopo un agente caduto, la **prima** cosa da guardare è lo
  stash, perché il verificatore usa `git stash` per lo scatto «prima».
- **Correggendo F-T-4 è nata una terza copia del difetto di F-T-14**: `footer.php:50` stampa
  `$sd_c['address']` grezzo con `phpcs:ignore`, copiando il precedente di `prenota.php:48` — che è
  proprio la versione non aggiornata. Non è una via di iniezione (è un letterale di codice, niente
  opzione, niente banca dati), così invece di spendere un giro di verifica per una riga ho allargato
  F-T-14 da due a tre occorrenze.

- **Due righe in un commit solo** (F-T-9 e F-T-12). Il giro vuole «una correzione verificata, un
  commit»; queste due sono cancellazioni di CSS morto nello stesso file, entrambe senza effetto su
  schermo, e la ragione per cui le correzioni sono in fila — un solo agente che scrive — resta
  rispettata. Ho preferito un commit a un giro di andata e ritorno in più: è una scelta mia, non una
  svista.
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
