# LOOP-CONTROLLO — controllo completo di tema, plugin e responsive

Un giro autonomo che controlla **tutti** i file di `themes/sorriso-dambrosio/` e
`plugins/sorriso-dambrosio-core/`, poi **tutte** le pagine a **tutti** i breakpoint, corregge
quello che può da solo e fa un commit per ogni correzione. Quello che tocca testi, immagini o
decisioni del titolare lo mette in un elenco, senza toccarlo.

## Come si lancia

1. App **Local** → sito → **Open site shell** (serve per WP-CLI: fuori da lì non vede il database).
2. `cd app/public/wp-content` poi `claude`.
3. Incollare:

   ```
   Read docs/LOOP-CONTROLLO.md and execute the prompt in it, phase by phase, until the end.
   ```

   Se la sessione si interrompe, lo stesso messaggio riparte da dove era arrivato (lo stato sta in
   `docs/CONTROLLO.md`). In alternativa, a passi: `/loop Read docs/LOOP-CONTROLLO.md and do the next unchecked step.`

Permessi: `.claude/settings.json` lascia fare tutto il lavoro senza conferme. Chiedono conferma solo
push, merge, reset e le scritture nel database. Skill del progetto: `.claude/skills/` (9 skill WordPress).

**Priorita': un lavoro fatto bene, non il risparmio.** Questo giro sospende le regole «pochi token»
di `CLAUDE.md` (legge i file per intero, guarda le pagine, conferma a vista), ma senza sprechi.
Modello consigliato: il piu' capace disponibile (Opus). Il giro completo dura a lungo.

---

## Prompt (in inglese, per Claude Code)

You are running an autonomous quality pass on this repo. Quality is the priority, not token savings:
be thorough, read what you need, look at the rendered pages, verify every fix — just do not waste.
For this pass the "Come lavorare (pochi token)" section of CLAUDE.md is suspended; every other
CLAUDE.md rule applies in full. Work phase by phase. Keep chat output short: one line per finished
step. All detail goes into files.

### State and resume

- State file: `docs/CONTROLLO.md`. Create it in Phase 0 with the checklist below and the sections
  `## Correzioni fatte`, `## Da decidere (titolare)`, `## Note`. Tick each step as you finish it.
- On start, if `docs/CONTROLLO.md` exists, read only its checklist and resume at the first unchecked step.
- Raw outputs (metrics JSON, screenshots, logs) go to `../../../redesign/controllo/` — never in the repo.

### Hard limits of this pass

- Allowed to change: files in `themes/sorriso-dambrosio/` and `plugins/sorriso-dambrosio-core/`,
  `docs/`, `docs/audit/` scripts. Nothing else on disk, nothing in the database.
- WP-CLI read-only (`wp post list`, `wp option get`, `wp eval` is not needed and not allowed).
- Never change: literal texts, images, colors outside `theme.json`, the breakpoint scale
  (599 / 781 / 899 / 1120, plus the justified 1340), content in the database, other plugins/themes.
- One fix = one commit, Italian message in the style of `git log`, ending with the attribution line
  used by previous commits. No push, no merge, no rebase.
- Max 3 attempts per finding. If still failing: `git restore` the files, log it under
  `## Da decidere (titolare)` or `## Note` with what you measured, move on.
- Read every theme and plugin file in full during Phase 1 (skip `vendor/`). `style.css` is too big for
  one read: go through it section by section with offset/limit (map in `docs/RESPONSIVE.md` §1), all of it.
- Before fixing a section, read why it is built that way (`DESIGN-SPEC.md` §4.x, the comments in
  `style.css`): many odd-looking rules are measured decisions. Do not undo a documented decision.
- Measurements run through `node docs/audit/audit.mjs` (headless, cheap). Use screenshots to judge
  what metrics cannot: the screenshots of Phase 2.4, and one before/after element screenshot for
  every visual fix. Avoid `browser_snapshot` (huge output, adds nothing here).

### Checklist

#### Phase 0 — Setup
- [ ] 0.1 `git switch main && git switch -c controllo/<YYYY-MM-DD>`; working tree must be clean.
- [ ] 0.2 `cd docs/audit && npm install` if `node_modules` is missing (Playwright is pinned there).
- [ ] 0.3 Refresh `docs/audit/urls.txt` from the Yoast sitemaps (`/sitemap_index.xml`, skip
      `author-`), keep `/pagina-che-non-esiste-404/` as last line. Every URL must answer 200 except that one.
- [ ] 0.4 Extend `docs/audit/audit.mjs` METRICS with three checks it lacks, then commit:
      **overlap** — inside `header` and every `main` grid/flex container, visible siblings whose
      boxes intersect by more than 2px (this is how T9 hid: no overflow, logo covered);
      **truncation** — elements with `text-overflow: ellipsis` where `scrollWidth > clientWidth`;
      **console** — collect `console.error` and page errors per URL.
      Extend `docs/audit/analyze.py` so these three appear with severity (overlap/console = high).
- [ ] 0.5 Baseline: run the full measurement (Phase 2 command) and save the issue summary counts
      under `## Note` as "Prima".

#### Phase 1 — Static checks (all files)
- [ ] 1.1 PHP: `php -n -l` on every PHP file of theme and plugin (skip `vendor/`).
- [ ] 1.2 PHPStan level 5 in both: `composer install` if `vendor/` is missing, then `composer run phpstan`.
- [ ] 1.3 Theme CSS: the three `grep -c` of `RESPONSIVE.md` §3 = 0; `max-width`/`min-width` values
      only from the scale; every remaining `!important` has a comment saying who forces it.
- [ ] 1.4 Dead CSS: list `.sd-*` classes defined in `style.css` with zero hits in theme
      `patterns/ templates/ parts/ functions.php assets/` and plugin `includes/ blocks/`. Before
      removing one, grep for its prefix built dynamically (`'sd-…' .`, `sprintf`). Remove only true zero-hit
      rules; list the doubtful ones under `## Note`.
- [ ] 1.5 `theme.json`: valid JSON, `version` 3, every color/size used in `style.css` exists as a preset/token.
- [ ] 1.6 Patterns, templates, parts: file header complete (Title, Slug, Categories where needed);
      every dynamic output escaped (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`); text domain
      `sorriso-dambrosio`; one `h1` per template, no skipped heading levels; each home `<section>` named
      (`aria-labelledby` + `id`, see CLAUDE.md). If a pattern changes, bump `Version:` in `style.css`.
- [ ] 1.7 `functions.php` and `assets/js/`: enqueues versioned, no PHP notices, `menu.js` still handles Escape.
- [ ] 1.8 Plugin security: `defined( 'ABSPATH' ) || exit;` in every PHP file; sanitize on save,
      escape on output; nonce + capability on every meta/form save; `$wpdb` only with `prepare()`;
      `block.json` valid (`apiVersion` 3) and every render escapes.
- [ ] 1.9 URLs: `bash docs/migration/verifica-url.sh http://sorrisodambrosio.local` — every line 200
      (directly or after one 301).
- [ ] 1.10 After the fixes of this phase: `truncate -s 0 debug.log`, load every URL of `urls.txt`
      with `curl`, `debug.log` must stay empty.

#### Phase 2 — Responsive at every breakpoint
- [ ] 2.1 Run, from `wp-content/`:
      `node docs/audit/audit.mjs docs/audit/urls.txt ../../../redesign/controllo/run <widths> no-shots`
      with widths `320,390,599,600,781,782,899,900,1024,1120,1121,1180,1280,1340,1341,1440`
      (both sides of every threshold), then `python3 docs/audit/analyze.py ../../../redesign/controllo/metrics-run.json`.
      Read only the summary and the per-type examples, never the raw JSON in full.
- [ ] 2.2 Group findings by root cause (same selector / same section), not by URL. Order: overlap,
      horizontal overflow, console errors, truncation, more than one `h1`, upscaled images, tap target
      < 44px, text < 16px on mobile, empty band > 200px, line length.
- [ ] 2.3 Fix each root cause in the theme (plugin only for block markup), one commit each. After
      each fix, re-run `audit.mjs` on the affected URLs at the affected widths only, and confirm with one
      MCP element screenshot when the fix is visual.
- [ ] 2.4 Visual review — what metrics miss (alignment, rhythm, crowding, odd wraps, orphan words,
      images badly cropped, a section that looks broken). Pick one URL per template (home, a page, an
      area, a treatment, a technology, a professional, a case, `/casi-studi/`, `/contatti/`, `/lo-studio/`,
      the 404) and run `audit.mjs` with screenshots at `390,781,1024,1121,1440` into
      `../../../redesign/controllo/shots/`. Look at every screenshot. Compare against `DESIGN-SPEC.md`
      (§4, §6). Log each problem with page + width + section, fix what is layout, log the rest.
- [ ] 2.5 Keyboard pass on home and one inner page at 390 and 1440 (MCP): Tab through header, menu,
      submenu, panel, form; focus always visible, Escape closes the panel, nothing trapped.
- [ ] 2.6 Known exceptions, do not "fix": iubenda privacy link height (plugin-injected, `DA-VERIFICARE` §15.1);
      `.sd-case__meta-label` / `x-small` labels at 15px on mobile (deliberate, see `style.css` 19.2) —
      list them under `## Note` only.

#### Phase 3 — Open tasks in `themes/sorriso-dambrosio/docs/TASKS.md`
- [ ] 3.1 T6 remainder: line `max-width: 100% !important` on `.sd-cases__media` has no effect (BEAF
      `max-width: unset !important` wins). Either strengthen the selector so it applies and verify on
      `/casi-studi/` at 390 and 1440, or remove it if nothing changes. Then split the mixed selector groups
      in 19.2 (preset classes keep `!important`, theme classes lose it) — computed font-size must be
      identical before/after on the affected pages at 390.
- [ ] 3.2 T7: move 19.7 / 20.5 / 20.6 next to their sections only if no rule in between shares a
      selector; otherwise leave them and say why. Update `RESPONSIVE.md` §1.
- [ ] 3.3 Tick T6/T7 in `TASKS.md` with a short "Fatto" paragraph, as for T3–T9.

#### Phase 4 — Close
- [ ] 4.1 Re-run the full Phase 2 measurement and the 2.4 screenshots; look at them again.
      Write "Dopo" counts next to "Prima" in `## Note`.
- [ ] 4.2 Update the line numbers of the map in `RESPONSIVE.md` §1 and the theme version there.
- [ ] 4.3 Final checks: PHP lint, PHPStan both, the three CSS greps, `debug.log` empty, `verifica-url.sh` clean.
- [ ] 4.4 Write at the top of `docs/CONTROLLO.md` a summary in Italian, max 15 lines: what was fixed
      (with commit hashes), what is left for the owner, before/after counts. Commit.
- [ ] 4.5 Stop. Do not push. Reply in chat with the summary only.
