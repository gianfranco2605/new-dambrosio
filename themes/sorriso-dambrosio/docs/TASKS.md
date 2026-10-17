# TASKS — pulizia del CSS (una chat per compito)

Ordine consigliato: dal piu' sicuro al piu' delicato. **Un compito = una chat**, poi `/clear`.
Ogni compito ha il prompt pronto da incollare. Spuntare qui quando e' fatto e committato.
Dati raccolti il 2026-10-17 su `style.css` 1.7.0 (i numeri di riga si spostano dopo ogni modifica:
ritrovarli con `grep -n 'max-width: 860px' style.css`).

Scala di arrivo: **599 · 781 · 899 · 1120** (vedi `RESPONSIVE.md` §2).

---

## [ ] T1 — `600px` → `599px` (sicuro)

| riga | sezione |
| --- | --- |
| 2961 | 18.5 Matrice a filetti (indice tecnologie) |
| 3199 | 18.8 Professionisti fuori dalla home |

Perche': core usa `min-width: 600px`; con `max-width: 600px` a 600px esatti valgono entrambe.
Cambia solo la larghezza 600px: nessuna verifica visiva.

> In style.css cambia le 2 media query `max-width: 600px` in `max-width: 599px` (sezioni 18.5 e 18.8).
> Nessuna verifica visiva. Poi spunta T1 in docs/TASKS.md.

## [ ] T2 — `900px` → `899px` (sicuro)

| riga | sezione | pagina per controllare |
| --- | --- | --- |
| 2693 | 18.2 Intestazione pagina d'area | `/implantologia/` |
| 2940 | 18.5 Matrice a filetti | `/tecnologie/` |
| 3071 | 18.6 La scheda | un trattamento singolo |
| 3302 | 18.9 Casi prima/dopo | `/casi-studi/` |
| 4597 | 20.5 Tetto delle cornici | qualsiasi pagina con immagini |

Cambia solo la larghezza 900px esatta.

> In style.css cambia le 5 media query `max-width: 900px` in `max-width: 899px` (sezioni 18.2, 18.5, 18.6,
> 18.9, 20.5). Nessuna verifica visiva. Poi spunta T2 in docs/TASKS.md.

## [ ] T3 — `860px` → `899px` (da verificare)

| riga | sezione | pagina per controllare |
| --- | --- | --- |
| 2790 | 18.3 Zig-zag trattamenti | `/implantologia/` |
| 2868 | 18.4 Righe a filetti | `/prestazioni/` |
| 3182 | 18.8 Professionisti | `/professionisti/` |
| 3348 | 18.10 Scheda del medico | un professionista singolo |
| 3478 | 18.12 Fascia prenota | fondo di qualsiasi pagina interna |
| 3549 | 18.13 Contatti | `/contatti/` |

Cambia il layout fra **861 e 899px**: queste sezioni passeranno alla versione impilata 40px prima.
E' quasi sempre un miglioramento (a 870px due colonne sono strette), ma va guardato.

> Leggi docs/RESPONSIVE.md §2. In style.css cambia le 6 media query `max-width: 860px` in `max-width: 899px`
> (sezioni 18.3, 18.4, 18.8, 18.10, 18.12, 18.13). Poi verifica con Playwright a **880px** solo
> /implantologia/ e /contatti/: nessun overflow orizzontale, niente colonne sotto i 300px.
> Riferisci in breve e spunta T3 in docs/TASKS.md.

## [ ] T4 — `1023px` (decidere: → 899 o → 1120)

| riga | sezione | pagina per controllare |
| --- | --- | --- |
| 547 | 4 Hero | `/` |
| 1487 | 6.2 Capitolo a specchio (Lo Studio) | `/` e `/lo-studio/` |
| 1522 | 6.1 Colonna di lettura (Lo Studio) | `/` e `/lo-studio/` |

Domanda da risolvere guardando: fra 900 e 1023px queste sezioni stanno meglio impilate (→ **1120**,
come il menu) o affiancate (→ **899**)? Tablet orizzontale = 1024px, quindi la scelta conta.

> Leggi docs/RESPONSIVE.md §2. In style.css ci sono 3 media query `max-width: 1023px` (sezioni 4 Hero,
> 6.1, 6.2). Fai uno screenshot di / a **960px** cosi' com'e' ora, solo hero e capitolo Lo Studio.
> Dimmi se a quella larghezza stanno meglio affiancati o impilati e proponi 899 o 1120. Non modificare
> nulla finche' non scelgo. Poi applica e spunta T4 in docs/TASKS.md.

## [ ] T5 — verifica finale dei breakpoint

> Controlla che in style.css restino solo `max-width` 599 / 781 / 899 / 1120:
> `grep -oE 'max-width: [0-9]+px\)' style.css | sort | uniq -c`.
> Poi Playwright, solo controllo overflow (`document.documentElement.scrollWidth > innerWidth`) senza
> screenshot, su / /implantologia/ /contatti/ /professionisti/ a 390, 768, 1024, 1280.
> Alza `Version:` in style.css, aggiorna la tabella §2 di docs/RESPONSIVE.md, spunta T5.

## [ ] T6 — i 39 `!important`

Le regole del progetto ne ammettono 3 casi (preset `font-size` di core, fogli CF7 e iubenda caricati dopo),
piu' quelli contro il layout di core (`display:none` su un Group flex, override di `constrained`).
Ce ne sono **39**: alcuni probabilmente non servono piu'.

> Elenca i `!important` di style.css con `grep -n '!important' style.css` (solo quelle righe, non leggere
> il file). Classificali: (a) giustificato con commento, (b) giustificato ma senza commento, (c) forse
> rimovibile. Non modificare nulla: dammi solo la tabella. Poi decido io quali togliere, a gruppi.

## [ ] T7 — ordine del file

19.7, 20.5 e 20.6 stanno in fondo a `style.css`, dopo 20.4, lontano dalle loro sezioni. Spostarli
rende piu' facile trovarli. Rischio: lo spostamento cambia l'ordine della cascata.

> In style.css i blocchi 19.7, 20.5, 20.6 stanno in fondo al file. Spostali ognuno alla fine della propria
> sezione (19.7 dopo 19.6, 20.5 e 20.6 dopo 20.4) **solo se** nessuna regola fra la posizione vecchia e
> quella nuova ha gli stessi selettori. Se c'e' conflitto, lasciali e dimmelo. Aggiorna la mappa §1 di
> docs/RESPONSIVE.md e spunta T7.

---

Dopo ogni compito: `git diff --stat`, poi commit (in italiano) solo se lo chiedo.
