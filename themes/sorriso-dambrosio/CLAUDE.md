# CLAUDE.md — tema `sorriso-dambrosio`

**La sessione si apre in questa cartella.** Questa e' la guida operativa unica: il `CLAUDE.md`
di `wp-content/` (due livelli sopra) tiene solo i vincoli del repo, in quindici righe.

Tema **1.7.0**, child di Ollie, `theme.json` v3. Homepage e pagine interne **finite**: si rifinisce
layout e responsive. Compito in corso = **il primo non spuntato di `docs/TASKS.md`**.

## Come lavorare (pochi token — obbligatorio)

- **Un compito per sessione**, descritto con pagina + larghezza + cosa non va. Fatto quello:
  riepilogo breve e stop. Niente loop autonomi.
- **`style.css` non si legge mai per intero** (140 KB). Mappa delle sezioni in `docs/RESPONSIVE.md` §1:
  `grep -n` del selettore o del numero di sezione (`/* == 20`), poi lettura con `offset`/`limit`.
- Documenti: **solo la sezione utile** (`grep -n '^#' docs/FILE.md` prima).
- Playwright: una pagina, una larghezza, solo se serve vedere. Per l'overflow basta `browser_evaluate`.
- Niente esplorazione del repo, niente audit multi-URL non richiesti.
- Fuori da questa cartella si legge solo, se indispensabile, il markup dei blocchi del plugin
  (`../../plugins/sorriso-dambrosio-core/blocks/`, `includes/class-render.php`).

## Vincoli che non si negoziano

- **Colori**: solo la palette di `theme.json`. Nessun hex/rgb/hsl, nessun `clamp()`, nessun `rem`
  di max-width, mai `ch`: solo token (`--wp--custom--space--{xxxs…xxxl}`, `settings.custom.measure`,
  `settings.custom.frame`).
- **Nessun breakpoint nuovo**: riusare `599 / 781 / 899 / 1120`.
- **Testo letterale** (pubblicita' sanitaria): niente riscritture ne' contenuto inventato.
  Ordine e impaginazione sono liberi.
- **Immagini** solo dalla Media Library, mai ingrandite oltre la misura naturale.
- Bersagli ≥ 44px, testo ≥ 16px su mobile, un solo `h1` per pagina.
- Non si toccano core, `ollie`, il vecchio tema `sorrisodambrosio`, i plugin di terzi.
- Nessun commit/push senza richiesta. Messaggi di commit **in italiano**, come i precedenti.

## Dove sta cosa

| | |
| --- | --- |
| `style.css` | tutto il CSS (sezioni §1–§20, mappa in `docs/RESPONSIVE.md` §1) |
| `theme.json` | palette, font, token di spaziatura/misura/proporzione, `contentSize` 1180 / `wideSize` 1320 |
| `templates/` | front-page, page, page-lo-studio, page-contatti, archivi e singoli dei 4 tipi, `taxonomy-sd_area`, 404 |
| `parts/` | header, footer, mobile-cta (richiamano i pattern) |
| `patterns/` | sezioni PHP: hero · lo-studio · orari · professionisti · servizi · tecnologie · perche-noi · prenota · vieni-a-trovarci · header · footer · contatti-* · lo-studio-pagina |
| `functions.php` | helper: `sorriso_dambrosio_section_head()`, `_image()`, `_image_url()`, `_contacts()`, `_hours()`, `_icon()` |
| `assets/js/menu.js` | unico JS (Escape nel sottomenu) |
| `assets/fonts/` | Fraunces + DM Sans, woff2 locali (nessuna CDN, GDPR) |

## Trappole che si ripresentano

- **Pattern cambiato → alzare `Version:` in `style.css`**, altrimenti WordPress serve la copia in cache.
- Recapiti e orari **solo** da `sorriso_dambrosio_contacts()` / `sorriso_dambrosio_hours()`.
- `.is-style-hidden-destok` / `.is-style-hidden-mobile` (§16) le usa il menu: non rimuoverle.
- Il blocco iubenda in `patterns/footer.php` non si tocca, ne' il commento che lo spiega.
- Il layout `constrained` di core stringe i figli a `contentSize`: serve `alignwide` o `layout:{"type":"default"}`.
- Gli stili inline dagli attributi del blocco battono il CSS del tema.
- Menu = blocco Navigation, post `wp_navigation` **ID 4** (`wp menu` non serve a nulla).
- Local serve le pagine da una cache: dopo una modifica, `/?cb=1`.

## Verifica minima

```bash
PHP83="$HOME/Library/Application Support/Local/lightning-services/php-8.3.17+1/bin/darwin-arm64/bin/php"
"$PHP83" -n -l <file.php>        # solo il file toccato; -n evita l'errore di xdebug
curl -s -o /dev/null -w '%{http_code}\n' 'http://sorrisodambrosio.local/?cb=1'
```

## Documenti

| File | Quando leggerlo |
| --- | --- |
| `docs/RESPONSIVE.md` | **sempre per primo** per un lavoro di layout: mappa CSS, breakpoint, token, header/menu |
| `docs/TASKS.md` | i compiti di pulizia del CSS, con i prompt pronti |
| `docs/DESIGN-SPEC.md` | *perche'* una sezione e' fatta cosi' (§4.x) o i criteri di accettazione (§6) |
| `../../docs/RIFERIMENTO.md` | guida completa del progetto — raramente serve per il CSS |
| `../../docs/DA-VERIFICARE.md` | decisioni aperte del titolare |
