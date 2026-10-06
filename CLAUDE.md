# CLAUDE.md — Sito Studio Odontoiatrico D'Ambrosio

Guida per sessioni future su questo repository. La homepage e' fatta; il lavoro che
resta sono **le altre pagine**, con lo stesso impianto.

---

## Che cos'e' questo repository

La cartella `wp-content/` di un sito WordPress (Studio Odontoiatrico D'Ambrosio,
`sorrisodambrosio.it`, Roma). **La radice del repo e' `wp-content/`**, non la radice di
WordPress. Git traccia soltanto:

```
.gitignore
CLAUDE.md
.claude/settings.json
themes/sorriso-dambrosio/      (escluso vendor/)
```

Tutto il resto — core, altri temi, plugin, uploads — e' deliberatamente ignorato.

## Ambiente

- **Local by Flywheel**, Apache. Sito: `http://sorrisodambrosio.local`
  (confermare sempre con `wp option get siteurl`).
- **Il sito gira su PHP 7.4.30** (lightning-services di Local).
- **WP-CLI gira su PHP 8.3.11**, non su quella del sito. Non fidarsi di `wp` per
  giudizi su compatibilita' PHP, e ignorare i `Deprecated:` che WP-CLI emette da se'.
- Per il lint usare il binario **7.4** del sito, non il `php` nel PATH:
  ```bash
  PHP74="$HOME/Library/Application Support/Local/lightning-services/php-7.4.30+6/bin/darwin-arm64/bin/php"
  "$PHP74" -n -l file.php      # -n salta il php.ini ed evita l'errore di xdebug
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
   `../../../redesign/DA-VERIFICARE.md`.
3. **Le immagini non cambiano.** Solo quelle gia' in Media Library.
4. **Non modificare mai** il core, il tema `ollie` (parent), il tema `sorrisodambrosio`
   (vecchio, tenuto per il rollback), nessun plugin.
5. **Non cancellare pagine o contenuti.** Le pagine vecchie restano: quelle nuove si
   affiancano, cosi' ogni passo e' reversibile.
6. **Font inclusi nel tema.** Nessuna CDN, nessun Google Fonts (GDPR).

## DESIGN FIRST — come si affronta una pagina

Il design viene **prima** del markup, e si giudica guardando la pagina resa.

1. **Analisi di un riferimento.** Screenshot a pagina intera (1440 e 390px) del
   riferimento (`https://www.prismoralsurgery.com/`) e analisi concreta del suo sistema
   di layout: larghezze dei contenitori, griglia, scala tipografica, composizione
   dell'hero, alternanza delle sezioni, respiro, uso delle immagini.
2. **Specifica scritta prima del codice.** `../../../redesign/DESIGN-SPEC.md` definisce il
   sistema di layout e, **per ogni sezione**, quale pattern di impaginazione usa.
   Si usa la skill `frontend-design`.
3. **Nessuna sezione puo' riusare il pattern del vecchio sito**: occhiello centrato +
   titolo sottolineato dal filetto + griglia di card uniformi. Quello e' il risultato
   bocciato: va evitato per costruzione.
4. **`contentSize`/`wideSize` realistici**: a 1440px il contenuto principale non deve
   essere piu' stretto di 1100px.
5. Colori, testi e immagini restano quelli esistenti.

## Il tema

`themes/sorriso-dambrosio/` — child di **Ollie 1.6.3**, `theme.json` **v3**.

- **Tipografia**: `secondary` = Fraunces (titoli h1/h2), `primary` = DM Sans (testo, h3).
  Entrambi woff2 variabili in `assets/fonts/`.
- **Spaziature e misure**: ereditate da Ollie (`small` … `xxxx-large`).
  `contentSize` 740px, `wideSize` 1280px.
- **Convenzione delle classi**: prefisso `sd-`.
- **Le sezioni sono pattern PHP** in `patterns/`, richiamate dalla pagina con
  `<!-- wp:pattern {"slug":"sorriso-dambrosio/..."} /-->`.
  Cosi' la pagina resta versionata nel tema e **gli URL dei media si risolvono a runtime**:
  niente URL `.local` congelati nel database, nessun search-replace al deploy.
- Helper disponibili nei pattern (definiti in `functions.php`):
  - `sorriso_dambrosio_section_head( $occhiello, $titolo, $livello = 2 )`
  - `sorriso_dambrosio_image( $id, $size, $classi, $attr )` — usa
    `wp_get_attachment_image()`, quindi srcset, dimensioni e lazy loading gratis
  - `sorriso_dambrosio_image_url( $id, $size )`

### Per una pagina nuova

1. Scrivere un pattern per sezione in `patterns/`, riusando il testo esistente alla lettera.
2. Creare la pagina e metterci dentro i `wp:pattern`.
3. Se serve un template dedicato, aggiungerlo in `templates/`; altrimenti Ollie usa `page.html`.
4. Header, footer e CTA mobile sono gia' pronti e condivisi: non riscriverli.

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

## Verifiche prima di dire che e' finito

Controllare sempre **il sito in esecuzione**, non i file sorgente.

```bash
PHP74="$HOME/Library/Application Support/Local/lightning-services/php-7.4.30+6/bin/darwin-arm64/bin/php"

# sintassi, su ogni file PHP del tema
find themes/sorriso-dambrosio -name '*.php' -not -path '*/vendor/*' -exec "$PHP74" -n -l {} \;

# analisi statica con target 7.4
cd themes/sorriso-dambrosio && composer run phpstan

# nessun colore fuori da theme.json
grep -rnE '(^|[^&])#[0-9a-fA-F]{3,8}\b|rgba?\(|hsla?\(' themes/sorriso-dambrosio \
  --include='*.php' --include='*.css' --include='*.html' | grep -v theme.json

# la pagina risponde e non logga
: > debug.log && curl -s -o /dev/null -w '%{http_code}\n' http://sorrisodambrosio.local/ && cat debug.log
```

Poi, con Playwright: un solo `<h1>`, livelli di heading senza salti, nessun overflow
orizzontale a 1440 / 768 / 390px, contatto raggiungibile su mobile.

### Criteri di accettazione del design (si verificano con Playwright, non leggendo il codice)

- Hero: immagine **e** titolo visibili sopra la piega a 1440 / 768 / 390px; **nessuna
  regione vuota piu' alta di 200px** in nessun punto della pagina.
- Ogni icona: bounding box reso **al massimo 56px**; nulla sborda dal proprio contenitore.
- Larghezza del contenuto principale a 1440px: **almeno 1100px**.
- `h1` **almeno 56px** su desktop, con una scala tipografica netta fra h1, h2 e testo.
- Confronto affiancato vecchia home / nuova home / riferimento, a 1440px, in
  `../../../redesign/screenshots/compare/`, e autocritica in `REPORT.md`.

## Stato attuale

- Front page: pagina **2059** («Home»). La vecchia pagina **57** e' intatta e pubblicata.
- Temi: `sorriso-dambrosio` attivo, `ollie` parent, `sorrisodambrosio` inattivo per il rollback.
- Backup pre-lavoro: `../../../backups/pre-redesign.sql`.
- Analisi, report e punti aperti: `../../../redesign/`.
- **Da fare**: tutte le altre pagine. Leggere prima `redesign/DA-VERIFICARE.md`: contiene
  decisioni del titolare ancora pendenti (immagini a bassa risoluzione, refusi nei testi,
  formule promozionali da validare) che valgono per tutto il sito, non solo per la home.

## Deploy

CI/CD esistente: GitHub Actions → FTP. **`themes/sorriso-dambrosio/vendor/`,
`composer.json`, `composer.lock` e `phpstan.neon.dist` non vanno in produzione**: sono
solo strumenti di sviluppo.
