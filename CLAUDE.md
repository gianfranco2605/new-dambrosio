# CLAUDE.md — Sito Studio Odontoiatrico D'Ambrosio

Guida per sessioni future su questo repository. Homepage e pagine interne sono fatte;
i contenuti sono stati rimodellati (vedi `docs/ARCHITECTURE.md`). Quel che resta sono
**decisioni del titolare**, elencate in `docs/DA-VERIFICARE.md`.

---

## Che cos'e' questo repository

La cartella `wp-content/` di un sito WordPress (Studio Odontoiatrico D'Ambrosio,
`sorrisodambrosio.it`, Roma). **La radice del repo e' `wp-content/`**, non la radice di
WordPress. Git traccia soltanto:

```
.gitignore
CLAUDE.md
README.md
docs/                          (documentazione e script di migrazione)
.claude/settings.json
themes/sorriso-dambrosio/            (escluso vendor/)
plugins/sorriso-dambrosio-core/      (escluso vendor/)
```

Tutto il resto — core, altri temi, plugin, uploads — e' deliberatamente ignorato.

## Ambiente

- **Local by Flywheel**, Apache. Sito: `http://sorrisodambrosio.local`
  (confermare sempre con `wp option get siteurl`).
- **Il sito gira su PHP 8.3.17** (lightning-services di Local), in locale e in produzione.
  Verificabile servendo un file dal sito, non con `wp`:
  ```bash
  printf '%s' '<?php echo PHP_VERSION;' > .playwright-mcp/phpcheck.php
  curl -s http://sorrisodambrosio.local/wp-content/.playwright-mcp/phpcheck.php; rm .playwright-mcp/phpcheck.php
  ```
- Per il lint usare il binario **8.3** del sito:
  ```bash
  PHP83="$HOME/Library/Application Support/Local/lightning-services/php-8.3.17+1/bin/darwin-arm64/bin/php"
  "$PHP83" -n -l file.php      # -n salta il php.ini ed evita l'errore di xdebug
  ```
- `wp` stampa su stderr un errore di caricamento di `xdebug.so`: e' rumore, si filtra con
  `2>&1 | grep -v 'xdebug.so'`.

## Vincoli da rispettare sempre

1. **I colori non cambiano.** La palette sta in `theme.json` ed e' quella storica del
   sito. Non introdurre colori nuovi. **Nessun valore hex/rgb/hsl fuori da `theme.json`**:
   nel CSS si usano `var(--wp--preset--color--…)` e `var(--wp--custom--…)`.
2. **CONTENUTO — il testo e' letterale, l'impaginazione e' libera.**
   Si riusa il testo esistente **alla lettera**: niente riscritture, niente contenuto
   inventato, niente nuove affermazioni commerciali, offerte, prezzi, testimonianze,
   nomi, titoli o numeri. La pubblicita' sanitaria in Italia impone un tono informativo.
   **Ma ordine, raggruppamento e presentazione sono liberi**: si possono fondere sezioni,
   spezzare testi lunghi, usare accordion/`<details>`, trasformare le card in elenchi o
   in blocchi editoriali. **Non esiste alcun obbligo** di mantenere l'ordine originale
   ne' di avere ogni frammento del testo originale sulla homepage.
   I testi lunghi (biografie dei medici, descrizioni delle tecnologie) possono essere
   **accorciati sulla homepage solo rimandando con un link alla pagina esistente** dove
   vive il testo completo. **Ogni caso di questo tipo va elencato** in
   `docs/DA-VERIFICARE.md`.
3. **Le immagini non cambiano.** Solo quelle gia' in Media Library.
4. **Non modificare mai** il core, il tema `ollie` (parent), il tema `sorrisodambrosio`
   (vecchio, tenuto per il rollback), nessun plugin **di terze parti**.
   `plugins/sorriso-dambrosio-core/` e' nostro e si modifica.
5. **Non cancellare pagine o contenuti.** Le pagine vecchie restano: quelle nuove si
   affiancano, cosi' ogni passo e' reversibile.
6. **Font inclusi nel tema.** Nessuna CDN, nessun Google Fonts (GDPR).

## DESIGN FIRST — come si affronta una pagina

Il design viene **prima** del markup, e si giudica guardando la pagina resa.

1. **Analisi di un riferimento.** Screenshot a pagina intera (1440 e 390px) del
   riferimento (`https://www.prismoralsurgery.com/`) e analisi concreta del suo sistema
   di layout: larghezze dei contenitori, griglia, scala tipografica, composizione
   dell'hero, alternanza delle sezioni, respiro, uso delle immagini.
2. **Specifica scritta prima del codice.** `docs/DESIGN-SPEC.md` definisce il
   sistema di layout e, **per ogni sezione**, quale pattern di impaginazione usa.
   Si usa la skill `frontend-design`.
3. **Nessuna sezione puo' riusare il pattern del vecchio sito**: occhiello centrato +
   titolo sottolineato dal filetto + griglia di card uniformi. Quello e' il risultato
   bocciato: va evitato per costruzione.
4. **`contentSize`/`wideSize` realistici**: a 1440px il contenuto principale non deve
   essere piu' stretto di 1100px.
5. Colori, testi e immagini restano quelli esistenti.

## L'architettura dei contenuti

> Scritta per intero in **`docs/ARCHITECTURE.md`**: leggerla prima di toccare
> contenuti, URL o template delle pagine interne.

### La riga di confine fra plugin e tema

**Se cancellando il tema si perde un'informazione, quell'informazione sta nel posto
sbagliato.**

| | Dove |
|---|---|
| Tipi di contenuto, tassonomia, campi, relazioni | `plugins/sorriso-dambrosio-core/` |
| Regole di riscrittura, permalink, mappa dei redirect | idem |
| **Orari e recapiti** (`SD\Core\Studio`) | idem — **unica fonte** |
| Blocchi dinamici (la logica che sceglie i contenuti) | idem |
| JSON-LD | idem |
| `theme.json`, `style.css`, `templates/`, `parts/`, `patterns/` | `themes/sorriso-dambrosio/` |

Gli helper del tema `sorriso_dambrosio_contacts()` e `sorriso_dambrosio_hours()`
**delegano al plugin** e tengono solo una copia di scorta per il caso in cui il
plugin sia spento. Non aggiungere orari o recapiti altrove: li leggono gia'
header, hero, fascia orari, `/contatti/`, footer e JSON-LD.

### I tipi di contenuto

| Tipo | Archivio | Singolo | Note |
|---|---|---|---|
| `sd_trattamento` | `/prestazioni/` | `/<area>/<slug>/` | 30 voci |
| `sd_tecnologia` | `/tecnologie/` | `/tecnologie/<slug>/` | 8 |
| `sd_professionista` | `/professionisti/` | `/professionisti/<slug>/` | 3 |
| `sd_caso` | `/casi-studi/` | `/casi-studi/<slug>/` | 8, rendono `[bafg]` |

Una sola tassonomia, **`sd_area`**, condivisa dai tre tipi che hanno un'area.
Cinque termini: `implantologia`, `ortodonzia`, `estetica-dentale`,
`denti-e-postura`, `trattamenti`.

### Gli URL: regole per termine, mai jolly

Le aree stanno **alla radice** (`/implantologia/`), dove stavano le pagine omonime.
`Permalinks::add_area_rules()` scrive **una regola per ciascun termine esistente**,
piu' una per i trattamenti sotto quell'area. Una tassonomia con `rewrite => ['slug' => '']`
ingoierebbe ogni indirizzo di primo livello del sito.

Il `rewrite.slug` del tipo e' il segnaposto `%sd_area%`, e WordPress lo mette **alla
lettera** nelle regole generate: `post_type_link()` sistema l'indirizzo in uscita, ma
in entrata serve la regola scritta a mano.

Se cambia l'elenco delle aree, le regole si rigenerano da sole (firma in
`sd_core_area_rules`).

**La mappa dei redirect** e' in `Permalinks::redirects()`: quattro voci, nessun plugin.

### La migrazione

Gli script stanno in **`docs/migration/`**, sono **idempotenti** (riconoscono una voce
gia' migrata dal meta `_sd_origine`) e vanno eseguiti in ordine. `docs/PROGRESS.md`
ha i comandi. Due verifiche, da eseguire sempre dopo:

```bash
wp eval-file docs/migration/verifica-testi.php    # ogni frase e' arrivata a destinazione
bash docs/migration/verifica-url.sh               # nessun vecchio URL e' un 404
```

### Per una pagina o un contenuto nuovo

- **Un trattamento, una tecnologia, un medico, un caso**: si creano dal pannello.
  Compaiono da soli dove devono. Vedi `docs/GUIDA-CLIENTE.md`.
- **Un'area nuova**: si crea il termine; la regola di riscrittura nasce da sola.
- **Una pagina di servizio**: template in `templates/`, dichiarato in
  `theme.json → customTemplates` se e' un template di pagina.
- **Ogni pagina interna** comincia con `wp:sd/briciole` e finisce con
  `wp:sd/cta-prenota`.

## Il tema

`themes/sorriso-dambrosio/` — child di **Ollie 1.6.3**, `theme.json` **v3**.

- **Tipografia**: `secondary` = Fraunces (titoli h1/h2), `primary` = DM Sans (testo, h3).
  Entrambi woff2 variabili in `assets/fonts/`.
- **Spaziature e misure**: ereditate da Ollie (`small` … `xxxx-large`).
  `contentSize` **1180px**, `wideSize` **1320px** (a 1440 la colonna utile e' 1320).
  In CSS: `--sd-max` 1320px, `--sd-gutter`, `--sd-rhythm`, e la griglia `.sd-shelf` per far
  uscire un elemento verso il bordo della finestra.
- **Convenzione delle classi**: prefisso `sd-`.
- **Le sezioni sono pattern PHP** in `patterns/`, richiamate dalla pagina con
  `<!-- wp:pattern {"slug":"sorriso-dambrosio/..."} /-->`.
  Cosi' la pagina resta versionata nel tema e **gli URL dei media si risolvono a runtime**:
  niente URL `.local` congelati nel database, nessun search-replace al deploy.
- Helper disponibili nei pattern (definiti in `functions.php`):
  - `sorriso_dambrosio_section_head( $occhiello, $titolo, $livello = 2, $href = '' )` —
    filetto **sopra** la sezione, occhiello in coda a destra, titolo sotto a sinistra
  - `sorriso_dambrosio_image( $id, $size, $classi, $attr )` — usa
    `wp_get_attachment_image()`, quindi srcset, dimensioni e lazy loading gratis
  - `sorriso_dambrosio_image_url( $id, $size )`
  - `sorriso_dambrosio_contacts()` — **i recapiti in un posto solo**. Header, hero,
    orari, modulo e «Vieni a Trovarci» leggono da qui: non riscriverli nei pattern.
  - `sorriso_dambrosio_hours()` — gli orari, indicizzati per `date('N')`
  - `sorriso_dambrosio_icon( $nome, $classi )` — icone di linea inline (`mail`,
    `phone`, `pin`, `clock`, `calendar`, `facebook`, `instagram`, `whatsapp`).
    Nessuna CDN, `currentColor`, 24px: la misura si governa in CSS, mai sopra i 56px.

### Per una sezione nuova in homepage

1. Scrivere un pattern per sezione in `patterns/`, riusando il testo esistente alla lettera.
2. Richiamarlo dalla front page con `<!-- wp:pattern {"slug":"..."} /-->`.
3. Header, footer e CTA mobile sono gia' pronti e condivisi: non riscriverli.

### I blocchi del plugin, usabili in ogni template

`sd/briciole` · `sd/area-intestazione` · `sd/area-contenuto` ·
`sd/elenco-trattamenti` (impianto `zigzag`, `righe`, `matrice`) ·
`sd/indice` (impianto `matrice`, `aree`) · `sd/tecnologie-collegate` ·
`sd/professionisti` (impianto `righe`, `schede`) · `sd/casi` · `sd/caso` ·
`sd/orari` · `sd/contatti` · `sd/cta-prenota` · `sd/intestazione`

Il titolo di sezione prende l'attributo `livello`: su un archivio la sezione **e'** la
pagina, quindi `"livello":"1"`. Un `h1` per pagina, mai due.

## Trappole gia' incontrate (non ripeterle)

- **Il menu e' un blocco Navigation**, non un menu classico: `wp menu` non serve a nulla.
  Punta al post `wp_navigation` **ID 4**, che esiste ed e' popolato. Un blocco Navigation
  che punta a un ID inesistente **non rende nulla e non da' errore**.
- **Il layout `constrained` di core** mette `margin-inline:auto` e `max-width: contentSize`
  sui figli diretti: centra quello che si vuole a sinistra e stringe le colonne a 740px.
  Rimedi: `alignwide` sull'elemento, oppure `layout:{"type":"default"}`, oppure
  sovrascrivere con `!important` (core ha la stessa specificita').
- **Il supporto layout dei Group emette `display:flex`** sulla classe generata: per
  nascondere un gruppo serve `display:none !important`.
- **Gli stili inline dagli attributi del blocco battono il CSS del tema.** Se serve che
  una regola CSS vinca (es. `margin-top:auto`), non impostare quel margine nel blocco.
- **`core/shortcode` non esegue gli shortcode**: fa solo `wpautop`. Le parti template non
  passano dal filtro `the_content`, quindi uno shortcode li' dentro resta testuale.
  Nei pattern PHP si usa `do_shortcode()`.
- **iubenda** cerca il pattern chiamato `<stylesheet>/footer` e, se non vi trova la
  stringa `[iub-wp-block-buttons]`, ci inietta dentro un `<div>` come primo figlio del
  **secondo `<div>`** del footer. Il nostro `patterns/footer.php` lo colloca gia' da se'
  e lo esegue: non toccare quel blocco ne' il commento che lo accompagna.
- **Ollie assegna alle tabelle fondo `base`**: su fondo scuro il testo diventa bianco su
  bianco. Serve l'override (vedi `.sd-footer .sd-hours` in `style.css`).
- **Classi ereditate dai contenuti in archivio**: `.is-style-hidden-destok` e
  `.is-style-hidden-mobile` sono usate dal post `wp_navigation` 4 ed erano definite solo
  nel CSS del vecchio tema. Sono state riportate nel child: **non rimuoverle**.
- **Il testo `alt` sta sugli allegati**, non negli attributi dei blocchi: `wp_get_attachment_image()`
  lo prende da li'. Se un'immagine esce senza `alt`, il meta `_wp_attachment_image_alt`
  dell'allegato e' vuoto.
- **Quando si crea una pagina nuova**, copiare i meta Yoast da quella che sostituisce,
  altrimenti si perdono title e description.
- **Screenshot a pagina intera con Playwright**: le immagini in `lazy` non si caricano.
  Scorrere tutta la pagina prima di scattare, altrimenti si fotografano riquadri colorati.
- Il banner cookie iubenda copre l'hero: nei test va chiuso
  (`document.querySelector('.iubenda-cs-accept-btn').click()`).

- **Il blocco Navigation di core passa alla tendina solo sotto i 600px.** Con otto voci
  di primo livello si accavalla sul logo molto prima. Il child rimette le due regole di
  core (`.wp-block-navigation__responsive-container` e `…-container-open`, entrambe
  sotto `@media (min-width: 600px)`) al comportamento mobile fino a 1120px.
- **Contact Form 7**: il blocco `core/shortcode` non esegue gli shortcode e i pattern
  non passano da `the_content` — serve `do_shortcode()`. I `<p>` e `<br>` automatici
  vanno spenti con `wpcf7_autop_or_not`, altrimenti spezzano la griglia del modulo.
- **`html_class` di CF7 mette la classe SUL `<form>`**, non attorno: i selettori sono
  `form.sd-form-wrapper`, non un discendente.
- **Il foglio di CF7 vince sul tema a parita' di specificita'**: usa
  `.wpcf7 form.sent .wpcf7-response-output` (0,3,1) ed e' stampato dopo. I selettori del
  tema partono da `.sd-prenota form.wpcf7-form` per vincere senza `!important`.
- **Il tag `[acceptance]` di CF7 tiene il submit disabilitato** finche' la casella non
  e' spuntata. Senza uno stato visibile il pulsante sembra guasto: `:disabled` e' vestito.
- **Il contrasto del testo sulla fotografia si misura, non si stima.** Nascondere il
  testo con `visibility:hidden`, fotografare il solo fondo, campionare i pixel dentro il
  riquadro di ogni blocco (PIL con `/usr/local/bin/python3`, non `/usr/bin/python3` che
  non ha PIL). Vedi `docs/REPORT.md` §11.3.
- **L'header e' sticky**: le ancore hanno bisogno di `scroll-margin-top`, altrimenti il
  titolo della sezione finisce sotto l'header.
- **Local serve le pagine da una cache**: dopo aver cambiato meta o contenuti, se la
  pagina sembra vecchia usare una query di cache-busting (`/?cb=1`) o `wp cache flush`.

### Trovate nell'iterazione 4

- **`wp eval-file` esegue con `eval()`, non con `include`**: uno script non puo'
  dichiarare `strict_types`, non sarebbe la prima istruzione del file. Gli script in
  `docs/migration/` per questo non la dichiarano.
- **Un pattern nuovo non compare** finche' non cambia la **versione del tema** in
  `style.css`: WordPress tiene in cache l'elenco dei pattern per versione, e ne'
  `wp cache flush` ne' `wp transient delete --all` lo svuotano.
- **`block.json` dichiara i valori di partenza**, quindi un attributo arriva sempre
  valorizzato — anche a `''`. Un `isset()` secco fa vincere quel vuoto sul ripiego
  scritto in PHP: `Blocks::attr()` tratta la stringa vuota come «non dato».
- **iubenda rimonta tutta la pagina con un parser DOM** e converte ogni carattere non
  ASCII in entita' numerica. Nel JSON-LD diventa testo letterale: il JSON si scrive
  **senza** `JSON_UNESCAPED_UNICODE`, cosi' resta tutto ASCII. Si verifica con
  `?iub_no_parse=1`, che spegne quel passaggio.
- **`&rsquo;` e' un'entita' nominata di HTML5.** `html_entity_decode` senza `ENT_HTML5`
  usa la tabella HTML 4.01 e non la conosce. Usare `Render::plain()`.
- **`wptexturize` trasforma l'apostrofo in entita' a ogni resa**: nei titoli dei
  contenuti vanno i caratteri veri (`’`), non `&#8217;`.
- **Il plugin BEAF stampa il titolo del caso in un `h2` suo**, allo stesso livello dei
  titoli che lo raggruppano. E' nascosto in `.sd-cases` / `.sd-case`.
- **Le gallerie BEAF salvano URL assoluti** nel meta `beaf_meta`: al deploy serve un
  `wp search-replace`. Non e' un problema introdotto da noi.
- **`calc(50% - 50vw)` non sanguina dentro una cella di griglia**: quel 50% si misura
  sulla cella. Usare `--sd-bleed`, che si misura sul viewport.
- **In una griglia `order` sposta la cella, non la colonna**: per alternare
  immagine/testo si scambiano le colonne (`grid-template-areas`), non gli elementi.

## Verifiche prima di dire che e' finito

Controllare sempre **il sito in esecuzione**, non i file sorgente.

```bash
PHP83="$HOME/Library/Application Support/Local/lightning-services/php-8.3.17+1/bin/darwin-arm64/bin/php"

# sintassi, su ogni file PHP del tema
find themes/sorriso-dambrosio plugins/sorriso-dambrosio-core docs/migration \
  -name '*.php' -not -path '*/vendor/*' -exec "$PHP83" -n -l {} \;

# analisi statica con target 8.3, su tema E plugin
( cd themes/sorriso-dambrosio && composer run phpstan )
( cd plugins/sorriso-dambrosio-core && composer run phpstan )

# nessun colore fuori da theme.json
grep -rnE '(^|[^&])#[0-9a-fA-F]{3,8}\b|rgba?\(|hsla?\(' \
  themes/sorriso-dambrosio plugins/sorriso-dambrosio-core \
  --include='*.php' --include='*.css' --include='*.html' --exclude-dir=vendor | grep -v theme.json

# la pagina risponde e non logga
: > debug.log && curl -s -o /dev/null -w '%{http_code}\n' http://sorrisodambrosio.local/ && cat debug.log
```

```bash
# i contenuti: ogni frase e' arrivata a destinazione, nessun vecchio URL e' un 404
wp eval-file docs/migration/verifica-testi.php
bash docs/migration/verifica-url.sh
```

Poi, con Playwright: un solo `<h1>`, livelli di heading senza salti, nessun overflow
orizzontale a 1440 / 768 / 390px, contatto raggiungibile su mobile, e **le gallerie
prima/dopo inizializzate** (il contenitore prende la classe `twentytwenty-container`).

### Criteri di accettazione del design (si verificano con Playwright, non leggendo il codice)

- Hero: immagine **e** titolo visibili sopra la piega a 1440 / 768 / 390px; **nessuna
  regione vuota piu' alta di 200px** in nessun punto della pagina.
- Ogni icona: bounding box reso **al massimo 56px**; nulla sborda dal proprio contenitore.
- Larghezza del contenuto principale a 1440px: **almeno 1100px**.
- `h1` **almeno 56px** su desktop, con una scala tipografica netta fra h1, h2 e testo.
- Confronto affiancato vecchia home / nuova home / riferimento, a 1440px, in
  `../../../redesign/screenshots/compare/`, e autocritica in `REPORT.md`.

## Stato attuale

### Le pagine

- **Front page**: pagina **2059** («Home»), nove pattern: hero · lo-studio · orari ·
  professionisti · servizi · tecnologie · perche-noi · prenota · vieni-a-trovarci.
- **`/lo-studio/`**: pagina **2163**, template `page-lo-studio`, pattern
  `lo-studio-pagina`. La vecchia pagina 48 e' in bozza con slug `lo-studio-originale`.
- **`/contatti/`**: pagina **2164**, template `page-contatti`. Non esisteva.
- **`/implantologia/` `/ortodonzia/` `/estetica-dentale/` `/denti-e-postura/`
  `/trattamenti/`**: termini `sd_area`, template `taxonomy-sd_area`.
- **`/tecnologie/` `/casi-studi/` `/professionisti/` `/prestazioni/`**: archivi dei tipi.
- **In bozza, intatte, reversibili**: 32, 34, 36, 38, 40, 46, 1619, 48.
  La vecchia home **57** (`/studio-dentistico/`) e' ancora pubblicata.

### I contenuti

30 trattamenti · 8 tecnologie · 3 professionisti · 8 casi · 5 aree.
Il modello, gli URL e le relazioni stanno in **`docs/ARCHITECTURE.md`**.

### Il modulo di contatto

Contact Form 7, post **2063**, shortcode
`[contact-form-7 id="17f113f" title="Modulo contatto" html_class="sd-form-wrapper"]`.
Reso in homepage dal pattern `prenota` e su `/contatti/` dal pattern `contatti-modulo`
(lo stesso modulo, senza la colonna dei recapiti: li' stanno gia' in cima alla pagina).
L'ancora `#prenota` e' il bersaglio dei pulsanti **nella pagina che contiene il modulo**;
le pagine interne puntano invece a `/contatti/#prenota` tramite `sd/cta-prenota`.

### Il menu

Blocco Navigation, post `wp_navigation` **ID 4**. **Sette** voci di primo livello —
Lo Studio ▾ · Implantologia ▾ · Ortodonzia ▾ · Estetica ▾ · Denti e postura ▾ ·
Trattamenti ▾ · Tecnologie ▾ — e 41 sottovoci, tutte con **indirizzi veri**.
Sette e non otto: misurato, con otto la prima voce finiva **sopra il logo** a 1440px.
Il menu si aggiorna a mano quando si aggiunge un contenuto: e' voluto.

### L'ambiente

- Mailpit di Local: SMTP **10035**, API HTTP **http://localhost:10035/api/v1/messages**.
- Temi: `sorriso-dambrosio` **1.2.0** attivo, `ollie` parent, `sorrisodambrosio`
  inattivo per il rollback.
- Plugin nostro: `sorriso-dambrosio-core` **1.0.0**, attivo.
- Backup: `../../../backups/pre-redesign.sql` (pre-design),
  `../../../backups/pre-architecture.sql` (pre-migrazione dei contenuti).

### La documentazione

Tutta in **`docs/`**, versionata:

| File | A che serve |
|---|---|
| `ARCHITECTURE.md` | il modello dei contenuti, gli URL, i template — **leggerlo prima di toccarli** |
| `DESIGN-SPEC.md` | il sistema di layout — **leggerlo prima di toccare il design** |
| `GUIDA-CLIENTE.md` | come ci lavora lo studio, in italiano e senza gergo |
| `REPORT.md` | che cosa e' stato costruito, le verifiche, l'autocritica (§12 per l'ultima iterazione) |
| `DA-VERIFICARE.md` | le decisioni ancora in mano al titolare |
| `PROGRESS.md` | il punto di ripresa, e i comandi per rieseguire la migrazione |
| `TRIAGE.md` | lo stato di partenza |
| `migration/` | gli script, idempotenti, piu' le due verifiche |

Lo script di misura per i criteri di design e' `.playwright-mcp/audit.js`.
Materiali grezzi **non versionati** (screenshot, HTML reso, log): `../../../redesign/`.

### Da fare

Niente di bloccante: quel che resta sono **decisioni del titolare**, tutte in
`docs/DA-VERIFICARE.md` — cinque relazioni inferite da confermare (§11.3), orari
modificabili dall'admin o no (§12), l'introduzione mancante di «Denti e postura»
(§13.1), i testi alternativi delle gallerie prima/dopo (§13.2), i refusi nei titoli
(§13.4), la pagina vuota `/70-anni-attivita/` (§13.5).

## Repository

Remote: `https://github.com/gianfranco2605/new-dambrosio`.
La radice del repo e' `wp-content/`. Rami: `main` (stato approvato),
`redesign/altre-pagine` (lavoro in corso sulle pagine interne).

## Deploy

CI/CD esistente: GitHub Actions → FTP. **Non vanno in produzione**:
`vendor/`, `composer.json`, `composer.lock`, `phpstan.neon.dist` (sia nel tema sia nel
plugin), `docs/`, `README.md`, `CLAUDE.md` e `.claude/` — sono strumenti e
documentazione di sviluppo.

**Va in produzione anche `plugins/sorriso-dambrosio-core/`**, e al primo deploy va
**attivato**: senza, i tipi di contenuto non esistono e le pagine interne rispondono 404.

**Al primo deploy, dopo il backup**, serve un search-replace per le gallerie
prima/dopo: il plugin BEAF salva gli URL assoluti delle immagini nel proprio meta.

```bash
wp db export backup-pre-deploy.sql
wp search-replace 'sorrisodambrosio.local' 'sorrisodambrosio.it' --precise
wp rewrite flush --hard
bash docs/migration/verifica-url.sh https://www.sorrisodambrosio.it
```
