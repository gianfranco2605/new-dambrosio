# CONTROLLO — stato del giro di controllo (2026-10-25)

Ramo: `controllo/2026-10-25`

## Checklist

#### Phase 0 — Setup
- [x] 0.1 branch creato da main, working tree pulita
- [x] 0.2 node_modules presente in docs/audit (nessuna installazione necessaria)
- [x] 0.3 refresh urls.txt dai sitemap (invariato: 68 × 200 + la 404 di prova)
- [x] 0.4 estendere audit.mjs con overlap/truncation/console (prova su / a 390 e 1440: 0 falsi allarmi)
- [ ] 0.5 baseline misurazione completa

#### Phase 1 — Static checks
- [ ] 1.1 php -l tutti i file
- [ ] 1.2 phpstan tema+plugin
- [ ] 1.3 grep RESPONSIVE.md §3
- [ ] 1.4 CSS morto
- [ ] 1.5 theme.json valido
- [ ] 1.6 pattern/template/part header + escaping + heading
- [ ] 1.7 functions.php / assets/js
- [ ] 1.8 sicurezza plugin
- [ ] 1.9 verifica-url.sh
- [ ] 1.10 debug.log vuoto dopo curl di tutte le urls

#### Phase 2 — Responsive
- [ ] 2.1 misurazione a tutte le larghezze
- [ ] 2.2 raggruppamento findings
- [ ] 2.3 fix per root cause
- [ ] 2.4 revisione visiva screenshot
- [ ] 2.5 keyboard pass
- [ ] 2.6 eccezioni note

#### Phase 3 — TASKS.md T6/T7
- [ ] 3.1 T6 remainder
- [ ] 3.2 T7
- [ ] 3.3 tick TASKS.md

#### Phase 4 — Close
- [ ] 4.1 re-run misurazione finale
- [ ] 4.2 aggiornare RESPONSIVE.md §1
- [ ] 4.3 controlli finali
- [ ] 4.4 riepilogo in italiano
- [ ] 4.5 push e stop

## Correzioni fatte

(nessuna ancora)

## Da decidere (titolare)

(nessuna ancora)

## Note

(nessuna ancora)
