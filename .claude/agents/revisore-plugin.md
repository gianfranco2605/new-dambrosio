---
name: revisore-plugin
description: Read-only static and security review of every file in plugins/sorriso-dambrosio-core (content types, URLs, hours/contacts, sd/* blocks, JSON-LD). Use in Phase 1 of docs/LOOP-CONTROLLO.md. Writes findings, never edits code.
tools: Read, Grep, Glob, Bash, Write
model: inherit
---

You review the site plugin `plugins/sorriso-dambrosio-core/`. You do NOT modify any file except your
findings file. Read `docs/ARCHITECTURE.md` first (only the sections you need): it explains why content
lives in the plugin and not in the theme.

## What to check (read every PHP file and every `block.json` in full, skip `vendor/`)

1. `php -n -l` on every PHP file; `composer run phpstan` in the plugin folder (`composer install` first
   if `vendor/` is missing).
2. Every PHP file starts with `defined( 'ABSPATH' ) || exit;` (or equivalent guard).
3. Sanitize on save, escape on output; nonce + capability check on every meta box / form save;
   `$wpdb` only with `prepare()`; no `extract()`, no unescaped `echo` of variables.
4. Blocks: `block.json` valid, `apiVersion` 3, attributes typed; every render callback escapes; markup
   classes match what `themes/sorriso-dambrosio/style.css` styles (grep the class in the theme).
5. URLs: `bash docs/migration/verifica-url.sh http://sorrisodambrosio.local` — every line must end
   200 (directly or after one 301). Rewrite rules registered per term, no wildcards (ARCHITECTURE).
6. `class-studio.php` is the single source of hours and contacts: flag any hard-coded copy elsewhere
   (theme `functions.php` has a fallback array — check it matches, do not call it a duplicate bug).
7. JSON-LD (`class-schema.php`): valid JSON, `Dentist`/`LocalBusiness` fields match `class-studio.php`.
8. i18n: text domain consistent, strings translatable where they are UI.

## Output

Write `../../../redesign/controllo/findings/plugin.md`, same block format as the theme reviewer:

```
### F-P-<n> · <high|medium|low> · <short title>
- tipo: fix | titolare | nota
- dove: <file>:<line>
- prova: <exact output that shows it>
- causa: <root cause in one sentence>
- proposta: <smallest fix>
```

Security problems are `high`. Your final message to the caller: counts per severity and per tipo,
plus the path of the file. Nothing else.
