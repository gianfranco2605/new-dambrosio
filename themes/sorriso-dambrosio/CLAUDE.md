# CLAUDE.md — tema `sorriso-dambrosio` (lavoro su layout e responsive)

Si apre la sessione **in questa cartella**. Il `CLAUDE.md` di `wp-content/` (due livelli sopra) viene caricato
anche lui: li' ci sono i vincoli del progetto (colori, testo letterale, immagini, cosa non toccare). Qui solo il tema.

Stato: tema **1.7.0**, child di Ollie, `theme.json` v3. Homepage e pagine interne **finite**; ora si
rifinisce layout e responsive. Ramo git: vedere `git branch --show-current` (repo con radice in `wp-content/`).

## Come lavorare (pochi token)

- **Un compito per sessione**, descritto con pagina + larghezza + cosa non va. Fatto quello: riepilogo breve e stop.
- **`style.css` non si legge mai per intero.** Mappa delle sezioni in `docs/RESPONSIVE.md` §1:
  `grep -n` del selettore o del numero di sezione, poi `Read` con `offset`/`limit`.
- Documenti: leggere **solo la sezione** che serve (`grep -n '^#' docs/FILE.md`).
- Playwright: una pagina, una larghezza, solo se serve vedere. Per l'overflow basta `browser_evaluate`.
- Fuori da questa cartella si legge solo, e solo se indispensabile, il markup dei blocchi nel plugin
  (`../../plugins/sorriso-dambrosio-core/blocks/`, `includes/class-render.php`).

## Dove sta cosa

| | |
| --- | --- |
| `style.css` | tutto il CSS (sezioni §1–§20, vedi `docs/RESPONSIVE.md`) |
| `theme.json` | palette, font, token di spaziatura/misura/proporzione, `contentSize` 1180 / `wideSize` 1320 |
| `templates/` | front-page, page, page-lo-studio, page-contatti, archivi e singoli dei 4 tipi, `taxonomy-sd_area`, 404 |
| `parts/` | header, footer, mobile-cta (richiamano i pattern) |
| `patterns/` | sezioni PHP: hero · lo-studio · orari · professionisti · servizi · tecnologie · perche-noi · prenota · vieni-a-trovarci · header · footer · contatti-* · lo-studio-pagina |
| `functions.php` | helper: `sorriso_dambrosio_section_head()`, `_image()`, `_image_url()`, `_contacts()`, `_hours()`, `_icon()` |
| `assets/js/menu.js` | unico JS (Escape nel sottomenu) |
| `assets/fonts/` | Fraunces + DM Sans, woff2 locali |

## Documenti in `docs/`

| File | Quando leggerlo |
| --- | --- |
| `docs/RESPONSIVE.md` | **sempre per primo** per un lavoro di layout: mappa CSS, breakpoint, token, header/menu, trappole, verifica |
| `docs/TASKS.md` | **lista dei compiti di pulizia CSS**, con prompt pronti: fare il primo non spuntato |
| `docs/DESIGN-SPEC.md` | per capire *perche'* una sezione e' fatta cosi' (§4.x per sezione) o i criteri di accettazione (§6) |
| `../../docs/RIFERIMENTO.md` | guida completa del progetto (contenuti, plugin, deploy) — raramente serve per il CSS |
| `../../docs/DA-VERIFICARE.md` | decisioni aperte del titolare |

## Regole che si rompono facilmente

- Nessun colore, `clamp()`, `rem` di max-width o `ch` scritti a mano: solo token (`docs/RESPONSIVE.md` §3).
- Nessun breakpoint nuovo: riusare `599 / 781 / 899 / 1120`.
- Bersagli ≥ 44px, testo ≥ 16px su mobile, un solo `h1`.
- Pattern cambiato → alzare `Version:` in `style.css`, altrimenti WordPress serve la copia in cache.
- `.is-style-hidden-destok` / `.is-style-hidden-mobile` (§16) e il blocco iubenda in `patterns/footer.php` non si toccano.
- Nessun commit/push senza richiesta. Messaggi di commit in italiano, come i precedenti.
