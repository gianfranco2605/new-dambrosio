# CLAUDE.md — sorrisodambrosio.it

**La sessione si apre qui**, in `wp-content/`: è la radice del repo, e il lavoro sta in due
cartelle, non in una. Questa è la guida unica — non ce ne sono altre.

```
themes/sorriso-dambrosio/        il disegno      (child di Ollie, theme.json v3, tema 1.8.0)
plugins/sorriso-dambrosio-core/  i contenuti     (tipi, URL, orari, blocchi sd/*, JSON-LD — 1.0.0)
```

Tutto il resto (core, `ollie`, vecchio tema `sorrisodambrosio` tenuto per il rollback, plugin di
terzi, uploads, `vendor/`) **non si legge e non si tocca**.

## Tema o plugin? Tre domande

| La modifica riguarda… | Dove | File |
| --- | --- | --- |
| colore, spazio, tipografia, breakpoint, ordine degli elementi | **tema** | `style.css`, `theme.json`, `patterns/`, `templates/` |
| struttura o classi di un blocco `sd/*` (orari, elenco trattamenti, schede) | **plugin** | `includes/class-blocks.php`, `class-render.php` |
| un trattamento, un orario, un recapito, una pagina | **nessun file** | pannello di WordPress |

**Perché sono separati:** dal plugin dipendono 49 contenuti, 5 aree e **217 regole di URL**.
Registrarli nel tema vorrebbe dire che cambiare tema = 404 su tutte le pagine interne.
La regola, in una riga: *se cancellando il tema si perde un'informazione, quell'informazione
sta nel posto sbagliato.* Nove modifiche su dieci sono nel tema; il plugin si apre per la
domanda 2, cioè raramente.

## Come lavorare (pochi token — obbligatorio)

- **Un compito per sessione**, descritto con pagina + larghezza + cosa non va. Fatto quello:
  riepilogo breve e stop. Niente loop autonomi, niente esplorazione del repo.
- **`style.css` non si legge mai per intero** (140 KB). Mappa delle sezioni in
  `themes/sorriso-dambrosio/docs/RESPONSIVE.md` §1: `grep -n` del selettore o del numero di
  sezione, poi lettura con `offset`/`limit`. Lo stesso per `class-blocks.php` (739 righe).
- Documenti: **solo la sezione utile** (`grep -n '^#' <file>` prima).
- Playwright: una pagina, una larghezza, solo se serve vedere; per l'overflow basta
  `browser_evaluate`. Screenshot dell'elemento, non della pagina intera.

## Vincoli che non si negoziano

- **Colori**: solo la palette di `theme.json`. Nessun hex/rgb/hsl, nessun `clamp()`, nessun `rem`
  di max-width, mai `ch`: solo token (`--wp--custom--space--{xxxs…xxxl}`, `settings.custom.measure`,
  `settings.custom.frame`). **Nessun breakpoint nuovo**: riusare `599 / 781 / 899 / 1120`.
- **Testo letterale** (pubblicità sanitaria): niente riscritture né contenuto inventato.
  Ordine e impaginazione sono liberi. I testi originali: `../../../redesign/originali/`.
- **Immagini** solo dalla Media Library, mai ingrandite oltre la misura naturale.
- Bersagli ≥ 44px, testo ≥ 16px su mobile, un solo `h1` per pagina.
- **Non cancellare** pagine o contenuti. Nessun push, import di DB o `search-replace` senza il titolare.
- Nessun commit senza richiesta. Messaggi di commit **in italiano**, come i precedenti.

## Dove sta cosa

| | |
| --- | --- |
| `themes/…/style.css` | tutto il CSS (sezioni §1–§20) |
| `themes/…/theme.json` | palette, font, token; `contentSize` 1180 / `wideSize` 1320 |
| `themes/…/templates/` · `parts/` | front-page, page-lo-studio, page-contatti, archivi e singoli, 404 · header, footer, mobile-cta |
| `themes/…/patterns/` | le sezioni: hero · lo-studio · orari · professionisti · servizi · tecnologie · perche-noi · prenota · vieni-a-trovarci · contatti-* |
| `themes/…/functions.php` | `sorriso_dambrosio_section_head()`, `_image()`, `_contacts()`, `_hours()`, `_icon()` |
| `plugins/…/class-content-types.php` · `class-fields.php` · `class-relations.php` | i 4 tipi, la tassonomia `sd_area`, i campi, le relazioni |
| `plugins/…/class-permalinks.php` | URL, regole per termine, redirect |
| `plugins/…/class-studio.php` | **orari e recapiti: unica fonte** (gli helper del tema delegano qui) |
| `plugins/…/class-blocks.php` · `class-render.php` | i 13 blocchi `sd/*` e il loro HTML |
| `plugins/…/class-schema.php` | JSON-LD |

## Trappole che si ripresentano

- **Pattern modificato → alzare `Version:` in `style.css`**, altrimenti WordPress serve la cache.
- Una sezione della home è una `<section>` (`"tagName":"section"` sul Group) **con nome**:
  `aria-labelledby` + `id` sull'h2 (5° parametro di `section_head()` e di `Render::head()`).
  Senza nome, una `<section>` vale un `<div>`. I wrapper interni restano `div`.
- Il `div.entry-content` dentro `<main>` è di core (blocco `post-content`): non si toglie, Ollie ci appoggia i fogli degli elenchi.
- `.is-style-hidden-destok` / `.is-style-hidden-mobile` le usa il menu: non rimuoverle.
- Il blocco iubenda in `patterns/footer.php` non si tocca, né il commento che lo spiega.
- Il layout `constrained` di core stringe i figli a `contentSize`: `alignwide` o `layout:{"type":"default"}`.
- Gli stili inline dagli attributi del blocco battono il CSS del tema.
- Menu = blocco Navigation, post `wp_navigation` **ID 4** (`wp menu` non serve a nulla).
- Local serve da cache: dopo una modifica, `/?cb=1`.

## Verifica minima

```bash
PHP83="$HOME/Library/Application Support/Local/lightning-services/php-8.3.17+1/bin/darwin-arm64/bin/php"
"$PHP83" -n -l <file.php>                 # solo il file toccato
( cd themes/sorriso-dambrosio && composer run phpstan )        # se hai toccato il tema
( cd plugins/sorriso-dambrosio-core && composer run phpstan )  # se hai toccato il plugin
: > debug.log && curl -s -o /dev/null -w '%{http_code}\n' 'http://sorrisodambrosio.local/?cb=1' && cat debug.log
```

## Documenti (leggere solo la sezione che serve)

| File | Quando |
| --- | --- |
| `themes/…/docs/RESPONSIVE.md` | **primo** per un lavoro di layout: mappa CSS, breakpoint, token |
| `themes/…/docs/TASKS.md` | i compiti aperti di pulizia CSS, con i prompt pronti |
| `themes/…/docs/DESIGN-SPEC.md` | perché una sezione è fatta così (§4.x), criteri di accettazione (§6) |
| `docs/RIFERIMENTO.md` | **guida completa** del progetto: contenuti, URL, deploy, trappole storiche |
| `docs/ARCHITECTURE.md` | il modello dei contenuti — prima di toccare tipi, URL o template |
| `docs/DA-VERIFICARE.md` | decisioni ancora in mano al titolare |
| `docs/audit/` | gli script di misura (`audit.js` per i criteri di design) |

Ramo di lavoro: `git branch --show-current`. Remote: `github.com/gianfranco2605/new-dambrosio`
(nessuna CI: il push non pubblica niente).
