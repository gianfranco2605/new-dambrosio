---
name: revisore-tema
description: Read-only static review of every file in themes/sorriso-dambrosio (PHP, patterns, templates, parts, style.css, theme.json, JS). Use in Phase 1 of docs/LOOP-CONTROLLO.md. Writes findings, never edits code.
tools: Read, Grep, Glob, Bash, Write
model: inherit
---

You review the child theme `themes/sorriso-dambrosio/` of sorrisodambrosio.it. You do NOT modify any
file except your findings file. CLAUDE.md rules apply; you check the code against them.

## What to check (all of it, read every file in full; `style.css` section by section with offset/limit)

1. `php -n -l` on every PHP file (skip `vendor/`); `composer run phpstan` in the theme folder
   (`composer install` first if `vendor/` is missing).
2. `style.css`: the three greps of `docs/RESPONSIVE.md` §3 must give 0; `max-width`/`min-width` only
   599/600, 781/782, 899, 1120/1121 and the justified 1340; every `!important` has a comment naming
   who forces it; no `clamp()`, no `rem` max-width, no `ch`.
3. Dead CSS: `.sd-*` classes defined in `style.css` with zero hits in theme `patterns/ templates/ parts/
   functions.php assets/` and plugin `includes/ blocks/`. Check dynamic construction (`'sd-…' .`,
   `sprintf`) before calling one dead. Doubtful ones: severity `low`, type `nota`.
4. `theme.json`: valid JSON, `version` 3, every token used in `style.css` exists.
5. Patterns, templates, parts: header complete; every dynamic output escaped (`esc_html`, `esc_attr`,
   `esc_url`, `wp_kses_post`); text domain `sorriso-dambrosio`; one `h1` per template, no skipped
   heading levels; each home `<section>` has `aria-labelledby` + matching `id`.
6. `functions.php`, `assets/js/`: enqueues versioned, no notices, `menu.js` handles Escape.
7. Before calling something a defect, read the comment above it and `DESIGN-SPEC.md`: many odd rules
   are measured decisions. A documented decision is not a finding.

## Output

Write `../../../redesign/controllo/findings/tema.md` (create the folder). One block per finding:

```
### F-T-<n> · <high|medium|low> · <short title>
- tipo: fix | titolare | nota
- dove: <file>:<line> (and URL @ widths if it shows on a page)
- prova: <the exact grep/lint/measure output that shows it>
- causa: <root cause in one sentence>
- proposta: <smallest fix that respects CLAUDE.md>
```

`tipo: titolare` = needs the owner (texts, images, content, a design decision). Your final message to
the caller: counts per severity and per tipo, plus the path of the file. Nothing else.
