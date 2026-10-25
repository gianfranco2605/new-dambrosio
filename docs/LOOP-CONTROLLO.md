# LOOP-CONTROLLO — controllo completo di tema, plugin e responsive, con una squadra di agenti

Un giro autonomo che controlla **tutti** i file di `themes/sorriso-dambrosio/` e
`plugins/sorriso-dambrosio-core/`, poi **tutte** le pagine a **tutti** i breakpoint, corregge
quello che può e fa un commit per ogni correzione verificata. Quello che tocca testi, immagini o
decisioni del titolare lo mette in un elenco, senza toccarlo.

## La squadra

La sessione principale fa da **capo**: legge questo file, assegna il lavoro, tiene lo stato e fa
commit e push. Non scrive codice. Gli altri cinque sono in `.claude/agents/`:

| agente | fa | scrive codice? |
| --- | --- | --- |
| `revisore-tema` | controlla ogni file del tema | no |
| `revisore-plugin` | controlla ogni file del plugin, sicurezza compresa | no |
| `misuratore` | misura 69 pagine a 16 larghezze, guarda gli screenshot, prova la tastiera | no |
| `correttore` | corregge **un** problema alla volta | sì, solo lui |
| `verificatore` | ricontrolla la correzione senza fidarsi: rimisura, regole, prima/dopo | no |

**Come si dividono il lavoro.** I tre che leggono partono insieme, in parallelo. Le correzioni
vanno una alla volta: c'è un solo agente che scrive, così due non toccano mai lo stesso file.

**Come si passano le informazioni.** Con file dal formato fisso, in `redesign/controllo/`
(fuori dal repo): `findings/` (i problemi, un blocco `F-<area>-<n>` ciascuno), `fix/` (cosa ha
cambiato il correttore), `verifica/` (le prove del verificatore). Il capo riassume tutto in
`docs/CONTROLLO.md`, che è anche il punto da cui si riparte.

**Come si verifica.** Il capo committa solo se il verificatore dice `PASSA`. Con `NON PASSA` il
problema torna al correttore insieme al motivo, al massimo 3 volte; poi si annulla e va in
«Da decidere».

## Come si lancia

1. App **Local** → sito → **Open site shell**.
2. `cd app/public/wp-content` poi `claude`. Dentro Claude Code, `/model`: il modello più capace e
   l'effort alto (l'impostazione generale di questo Mac è Sonnet con effort basso).
3. Incollare:

   ```
   Read docs/LOOP-CONTROLLO.md and act as the capo: run the team, phase by phase, until the end.
   ```

   Se la sessione si interrompe, lo stesso messaggio riparte dal primo passo non spuntato di
   `docs/CONTROLLO.md`.

Permessi: `.claude/settings.json` lascia fare tutto senza conferme, compresi commit e push del ramo
di controllo. Chiedono conferma merge, reset, rebase e le scritture nel database; il push su `main` e
quello forzato sono bloccati. Skill: le 14 skill WordPress del tuo utente (`~/.claude/skills`), gli agenti le usano.

**Priorità: un lavoro fatto bene, non il risparmio**, ma senza sprechi. Il giro è lungo.

---

## Prompt (in inglese, per Claude Code)

You are the **capo** of a five-agent team running an autonomous quality pass on this repo. You
coordinate; the agents in `.claude/agents/` do the work. Quality is the priority, not token savings —
just do not waste. For this pass the "Come lavorare (pochi token)" section of CLAUDE.md is
suspended; every other CLAUDE.md rule applies in full. Chat output: one line per finished step.

### How you run the team

- You never edit theme or plugin code yourself. You edit only `docs/CONTROLLO.md`,
  `themes/sorriso-dambrosio/docs/TASKS.md`, `themes/sorriso-dambrosio/docs/RESPONSIVE.md`, and you
  are the only one who runs `git commit` and `git push`.
- Call agents with the Agent tool, in the foreground, and wait for their answer. Never hand the whole
  pass to a single agent, never run the team in the background.
- Parallel only for read-only agents (`revisore-tema`, `revisore-plugin`, `misuratore`): one message,
  several Agent calls. `correttore` always runs alone, and `verificatore` runs after it, never at the same time.
- Give each agent only what it needs: the finding block, file paths, the run name. Do not paste big files.
- If an agent fails or returns something unusable, call it once more with a clearer brief; if it fails
  again, log it under `## Note` and move on.

### State and resume

- State: `docs/CONTROLLO.md` — checklist, `## Coda` (the queue), `## Correzioni fatte`,
  `## Da decidere (titolare)`, `## Note`. Tick each step as you finish it.
- On start: if `docs/CONTROLLO.md` exists, read it and resume at the first unchecked step (and, in the
  queue, at the first row not `fatta`/`fallita`). Check `git branch --show-current` is the
  `controllo/<date>` branch and `git status` is clean; if a fix was left uncommitted by an interrupted
  run, send it to `verificatore` before anything else.
- Raw outputs live in `../../../redesign/controllo/` — never in the repo.

### Hard limits of this pass

- Code changes only in `themes/sorriso-dambrosio/` and `plugins/sorriso-dambrosio-core/` (by
  `correttore`); docs and `docs/audit/` scripts as needed. Nothing else on disk, nothing in the database.
- WP-CLI read-only. Never change literal texts, images, colors outside `theme.json`, the breakpoint
  scale (599 / 781 / 899 / 1120, plus the justified 1340), database content, other plugins/themes.
- One verified fix = one commit, Italian message in the style of `git log` (start from the one the
  fixer proposes), ending with the attribution line of previous commits. After every commit
  `git push` the branch. Never push `main`, never force-push, no merge, no rebase.
- The owner is away and will not answer. Never stop to ask. Decisions go under
  `## Da decidere (titolare)`. Do not run commands that need approval: they would block the pass.

### Checklist

#### Phase 0 — Setup
- [ ] 0.1 Branch `controllo/<YYYY-MM-DD>` from a clean `main`.
- [ ] 0.2 `docs/audit/node_modules` present (`npm install` there if not).
- [ ] 0.3 `docs/audit/urls.txt` from the Yoast sitemaps, `/pagina-che-non-esiste-404/` last; all 200 but that one.
- [ ] 0.4 `audit.mjs` / `analyze.py` measure overlap, truncation and console errors.

#### Phase 1 — Survey (three read-only agents in parallel)
- [ ] 1.1 In ONE message call `revisore-tema`, `revisore-plugin` and `misuratore` (run `full`). Wait for all three.
- [ ] 1.2 Read the three findings files. Build `## Coda` in `docs/CONTROLLO.md`: one row per finding —
      `id · gravità · tipo · titolo · stato` (stato starts `da fare`). Order: security, overlap, overflow,
      console, other `high`, then `medium`, then `low`. Merge duplicates found by two agents into one row.
      `tipo: titolare` rows go to `## Da decidere (titolare)`; `tipo: nota` to `## Note`.
      Put the misuratore totals under `## Note` as **Prima**. Commit and push the state.

#### Phase 2 — Fix loop (one finding at a time)
- [ ] 2.1 For each `fix` row of the queue, in order:
      1. `correttore` with the finding block → it answers `pronto` or `serve il titolare`.
         `serve il titolare` → row `titolare`, move it to Da decidere, next row.
      2. `verificatore` with the finding block and the fixer's report path → `PASSA` or `NON PASSA`.
      3. `PASSA` → check `git diff --stat` matches the fixer's report, commit, push, row `fatta` +
         commit hash, one line in `## Correzioni fatte`.
         `NON PASSA` → back to `correttore` with the verifier's reason. After 3 failed attempts:
         `git restore` the touched files, row `fallita`, log the verifier's evidence in Da decidere.
      4. Update `docs/CONTROLLO.md` (commit it with the fix, or on its own when nothing was committed).
- [ ] 2.2 Every 5 committed fixes, and at the end of the queue: all URLs of `urls.txt` answer as
      expected (`curl`), `debug.log` empty after `truncate -s 0 debug.log`. A regression becomes a new
      `high` row at the top of the queue.

#### Phase 3 — Open tasks of `themes/sorriso-dambrosio/docs/TASKS.md` (same loop)
- [ ] 3.1 Write them as findings and run them through 2.1:
      **F-X-1** (T6 rest): `max-width: 100% !important` on `.sd-cases__media` has no effect (BEAF's
      `max-width: unset !important` wins) — make it apply and verify on `/casi-studi/` at 390 and 1440, or
      remove it if nothing changes; then split the mixed selector groups in 19.2 (preset classes keep
      `!important`, theme classes lose it), computed font-size identical before/after at 390.
      **F-X-2** (T7): move 19.7 / 20.5 / 20.6 next to their sections only if no rule in between shares a
      selector; otherwise leave them and say why.
- [ ] 3.2 Tick T6/T7 in `TASKS.md` with a short "Fatto" paragraph like T3–T9; update `RESPONSIVE.md` §1.

#### Phase 4 — Close
- [ ] 4.1 `misuratore` run `final`. Put its totals under `## Note` as **Dopo**, next to **Prima**.
      New `high` findings → one more pass of 2.1 for those only.
- [ ] 4.2 Update the line numbers of the map in `RESPONSIVE.md` §1 and the theme version there.
- [ ] 4.3 Final checks yourself: `php -n -l` on all theme/plugin PHP, `composer run phpstan` in both,
      the three CSS greps of `RESPONSIVE.md` §3, `debug.log` empty, `bash docs/migration/verifica-url.sh`.
- [ ] 4.4 At the top of `docs/CONTROLLO.md`, a summary in Italian, max 15 lines: fixed (with hashes),
      failed, left for the owner, Prima → Dopo. Commit, push.
- [ ] 4.5 Stop. Do not merge into `main`. Reply in chat with the summary only.
