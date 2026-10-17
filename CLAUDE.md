# CLAUDE.md — Sito Studio Odontoiatrico D'Ambrosio

`wp-content/` di `sorrisodambrosio.it` (WordPress, Local: `http://sorrisodambrosio.local`).
**La radice del repo e' `wp-content/`.** Si lavora solo in:

- `themes/sorriso-dambrosio/` — child block theme di Ollie (theme.json v3, `style.css`, `templates/`, `parts/`, `patterns/`)
- `plugins/sorriso-dambrosio-core/` — tipi di contenuto, URL, orari/recapiti (`SD\Core\Studio`), blocchi `sd/*`, JSON-LD

Tutto il resto (core, `ollie`, vecchio tema `sorrisodambrosio`, plugin di terzi, uploads, `vendor/`) **non si legge e non si tocca**.

## Regole di lavoro (risparmio token — obbligatorie)

1. **Un compito per sessione.** Fare solo cio' che viene chiesto, poi fermarsi e riferire.
2. **Non esplorare.** Niente ricerche sull'intero `wp-content/`. Se il file non e' indicato, `grep -rn` mirato dentro tema o plugin, escludendo `vendor/`.
3. **Mai leggere `style.css` per intero** (140 KB). Prima `grep -n` del selettore o della sezione (`/* == 20` ecc.), poi `Read` con `offset`/`limit`.
4. **I documenti in `docs/` si leggono solo se servono, e solo la sezione utile** (`grep -n '^#' docs/FILE.md` prima).
5. **Playwright/screenshot solo se richiesti**, una larghezza alla volta (390 o 1440), mai pagina intera di tutto il sito. Nessun audit multi-URL senza richiesta esplicita.
6. Niente loop autonomi o "non fermarti finche'…": a fine compito, breve riepilogo e stop.

## Vincoli sempre validi

1. **Colori**: solo la palette di `theme.json`. Nessun hex/rgb/hsl fuori da `theme.json`; nel CSS `var(--wp--preset--color--…)` / `var(--wp--custom--…)`. Niente `clamp()` o `rem` scritti a mano: usare i token (`--wp--custom--space--{xxxs…xxxl}`, `settings.custom.measure`, `settings.custom.frame`).
2. **Testo letterale**: nessuna riscrittura o contenuto inventato (pubblicita' sanitaria). Impaginazione e ordine liberi. Testi accorciati in home solo con link alla pagina completa, e annotati in `docs/DA-VERIFICARE.md`.
3. **Immagini**: solo quelle gia' in Media Library; mai ingrandite oltre la misura naturale.
4. **Non modificare** core, `ollie`, `sorrisodambrosio`, plugin di terzi. **Non cancellare** pagine o contenuti.
5. **Font inclusi nel tema**, nessuna CDN / Google Fonts.
6. **Nessun push, import di DB, search-replace o cancellazione** senza il titolare.

## Convenzioni

- Classi con prefisso `sd-`. Sezioni = pattern PHP in `patterns/`, richiamati con `<!-- wp:pattern {"slug":"sorriso-dambrosio/..."} /-->`.
- Recapiti e orari **solo** da `sorriso_dambrosio_contacts()` / `sorriso_dambrosio_hours()` (delegano al plugin).
- Immagini: `Render::thumb()` + `.sd-plate`, o `<!-- wp:sd/immagine -->`; mai `core/post-featured-image` nelle schede.
- Pagine interne: iniziano con `wp:sd/briciole`, finiscono con `wp:sd/cta-prenota`. Un solo `h1`.
- Pattern nuovo non compare? Alzare la **versione** in `style.css`.
- Menu = blocco Navigation, post `wp_navigation` **ID 4** (non `wp menu`).

## Ambiente e verifica minima

```bash
PHP83="$HOME/Library/Application Support/Local/lightning-services/php-8.3.17+1/bin/darwin-arm64/bin/php"
"$PHP83" -n -l <file.php>                                   # lint del file toccato
: > debug.log && curl -s -o /dev/null -w '%{http_code}\n' http://sorrisodambrosio.local/ && cat debug.log
```

`wp` stampa un errore di `xdebug.so`: filtrare con `2>&1 | grep -v 'xdebug.so'`. Cache: `/?cb=1` o `wp cache flush`.
La verifica completa (phpstan, grep colori, migrazione, criteri Playwright) e' in `docs/RIFERIMENTO.md` → "Verifiche": eseguirla solo prima di un commit/deploy o se richiesta.

## Dove trovare il resto (leggere solo la sezione che serve)

| File | Contenuto |
| --- | --- |
| `docs/RIFERIMENTO.md` | guida completa: sistema di design, componenti, **trappole gia' incontrate**, stato pagine/ID, deploy |
| `docs/ARCHITECTURE.md` | tipi di contenuto, URL, template — prima di toccarli |
| `themes/sorriso-dambrosio/docs/RESPONSIVE.md` | mappa di `style.css`, breakpoint, token, header/menu — per ogni lavoro di layout |
| `themes/sorriso-dambrosio/docs/DESIGN-SPEC.md` | sistema di layout — prima di cambiare il design |
| `docs/DA-VERIFICARE.md` | decisioni aperte del titolare (= cio' che resta da fare) |
| `docs/PROGRESS.md` | punto di ripresa |

Ramo di lavoro: `redesign/altre-pagine` (commit solo locali). Remote: `github.com/gianfranco2605/new-dambrosio`.
