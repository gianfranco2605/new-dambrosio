# TASKS — pulizia del CSS (una chat per compito)

Ordine consigliato: dal piu' sicuro al piu' delicato. **Un compito = una chat**, poi `/clear`.
Ogni compito ha il prompt pronto da incollare. Spuntare qui quando e' fatto e committato.
Dati raccolti il 2026-10-17 su `style.css` 1.7.0 (i numeri di riga si spostano dopo ogni modifica:
ritrovarli con `grep -n 'max-width: 860px' style.css`).

Scala di arrivo: **599 · 781 · 899 · 1120** (vedi `RESPONSIVE.md` §2).

---

## [x] T1 — `600px` → `599px` (sicuro)

| riga | sezione |
| --- | --- |
| 2961 | 18.5 Matrice a filetti (indice tecnologie) |
| 3199 | 18.8 Professionisti fuori dalla home |

Perche': core usa `min-width: 600px`; con `max-width: 600px` a 600px esatti valgono entrambe.
Cambia solo la larghezza 600px: nessuna verifica visiva.

> In style.css cambia le 2 media query `max-width: 600px` in `max-width: 599px` (sezioni 18.5 e 18.8).
> Nessuna verifica visiva. Poi spunta T1 in docs/TASKS.md.

## [x] T2 — `900px` → `899px` (sicuro)

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

## [x] T3 — `860px` → `899px` (da verificare)

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

**Fatto.** A 880px `/implantologia/` e `/contatti/`: nessun overflow, nessuna colonna sotto i 300px;
zig-zag, fascia prenota e griglia contatti sono a una colonna (830px).

## [x] T4 — `1023px` → `1120px` (impilate)

Erano 3 `max-width: 1023px` piu' la gemella `min-width: 1024px` della 6.2, che va spostata con
loro: fra 1024 e 1120 il capitolo a specchio sarebbe rimasto senza griglia ma con le regole del
rovesciamento. Quattro query, non tre.

| riga | sezione | pagina per controllare |
| --- | --- | --- |
| 553 | 4 Hero | `/` |
| 1383 | 6.2 Capitolo a specchio — `min-width: 1121px` | `/` e `/lo-studio/` |
| 1493 | 6.2 Spaccatura (`.sd-split`) | `/` e `/lo-studio/` |
| 1536 | 6.1 Colonna di lettura | `/` e `/lo-studio/` |

**Impilate, e la soglia e' 1120** — quella del menu, cosi' un tablet orizzontale a 1024px prende
la stessa forma in tutte e due le cose.

Capitoli: la griglia chiede 475px alla fotografia e 845 al testo (36/64 di `--sd-max`), e sotto
i ~1004px non bastano a nessuna delle due — `minmax()` divide a meta' e il 36/64 non esiste piu'.
Il testo che resta fa 39 caratteri a 900px, 42 a 960, 45 a 1024, contro i 64 della misura, che
arriva solo a ~1220px. Impilato ne fa 64.

Hero: il commento diceva che da 1024 in su la scheda contatti sta su una riga sola. Misurando,
no — entra solo da ~1230 (vedi T8). L'arrotondamento e' comunque a 1120 e non a 899 perche' e'
quello che riduce il danno: la fascia con i puntini passa da 1024-1230 a 1121-1230, mentre 899
l'avrebbe allargata fino a 900.

## [x] T5 — verifica finale dei breakpoint

Fuori elenco, trovati facendo T1/T2: **479px**, **700px** e **1340px** (uno ciascuno). Non sono nella
scala: in T5 vanno portati alla misura vicina o giustificati con un commento.

> Controlla che in style.css restino solo `max-width` 599 / 781 / 899 / 1120:
> `grep -oE 'max-width: [0-9]+px\)' style.css | sort | uniq -c`.
> Poi Playwright, solo controllo overflow (`document.documentElement.scrollWidth > innerWidth`) senza
> screenshot, su / /implantologia/ /contatti/ /professionisti/ a 390, 768, 1024, 1280.
> Alza `Version:` in style.css, aggiorna la tabella §2 di docs/RESPONSIVE.md, spunta T5.

**Fatto (tema 1.8.3).** Restano `max-width` 599 / 781 / 899 / 1120, piu' 1340 giustificato.

| soglia | decisione | perche' (misurato) |
| --- | --- | --- |
| 479 | **tolta** | logo 148px sotto i 480. A 170px ci sta ovunque: 84px di stacco a 390, 20 a 320 |
| 700 | **→ 781** | indice aree (`/prestazioni/` e la pagina 404): a 781 le colonne sono 200/515, impilare non perde niente; 599 le avrebbe lasciate a 151/389 |
| 1340 | **resta, con commento** | misura della riga dell'header, non layout: senza, a 1240 il menu copre il logo di 10px |

Overflow: nessuno su / /implantologia/ /contatti/ /professionisti/ a 390, 768, 1024, 1280.
Il controllo dell'overflow non vede le sovrapposizioni: guardando si e' trovato T9.

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

## [x] T8 — la scheda dell'hero tronca fra 1121 e ~1230px

Trovato facendo T4, e piu' vecchio di T4: su una riga sola la scheda contatti non ci sta fino a
~1230px, quindi `text-overflow: ellipsis` mangia la fine dell'indirizzo (e sotto i ~1150 anche
dell'email). Misurato a 1180px: «Lungotevere Flaminio 34, Roma» chiede 227px e ne ha 213.

| larghezza | email | telefono | indirizzo |
| --- | --- | --- | --- |
| 1121 | troncata | ok | troncato |
| 1180 | ok (204/204) | ok | **troncato** (227/213) |
| 1240 | ok | ok | ok |

Non e' un problema di soglia: ~1230 non sta nella scala e non si aggiunge (T5). Va stretto il
contenuto o il passo. Le vie, da decidere: accorciare `address_short` nel plugin
(`class-studio.php`); togliere la parola «Roma», che e' gia' implicita; ridurre il `gap` della
scheda da `space--md` a `space--sm` nella sola fascia 1121-1230; lasciare che la CTA si stringa
(oggi `flex: 0 0 auto`).

> Guarda la scheda dell'hero di `/` a **1180px**: l'indirizzo finisce con i puntini. Non toccare
> le soglie. Proponi quale delle quattro vie costa meno e dimmi cosa cambia a 1121, 1180, 1240 e
> 1440 — poi scelgo io. Niente modifiche prima.

**Fatto (tema 1.8.2) con una quinta via.** Non mancava spazio: le tre voci avevano la stessa
larghezza (`flex: 1 1 0`), e il telefono ne avanzava. Con `flex: 1 1 auto` su `.sd-hero__bar-item`
ogni voce parte dalla misura del suo testo. Nessun contenuto, soglia o media query cambiati.

| via | 1121 | 1180 | 1240 | 1440 |
| --- | --- | --- | --- | --- |
| togliere «Roma» | email troncata | ok | ok | ok |
| `gap` a `space--sm` | indirizzo troncato | indirizzo troncato | ok | ok |
| CTA che si stringe | nessun effetto | nessun effetto | ok | ok |
| **voci `flex: 1 1 auto`** | **ok** | **ok** | **ok** | **ok** |

A 1121 restano ~15px di margine all'indirizzo e ~16 all'email.

## [ ] T9 — il menu copre il logo fra 1121 e ~1180px

Trovato facendo T5, piu' vecchio di T5. Sopra i 1120 il menu e' in linea, e la prima voce
(«Lo Studio») parte prima che finisca il logo (220px, la sua misura naturale): copre la scritta
«Studio Odontoiatrico». Nessun overflow orizzontale, quindi `scrollWidth` non lo vede.

| larghezza | spazio logo → menu oggi | con logo a 140px |
| --- | --- | --- |
| 1121 | **−56px** (sovrapposti) | 24px |
| 1150 | **−28px** | 52px |
| 1180 | 2px | 82px |
| 1240 | 62px | 142px |
| 1341 | 64px (stacco 28) | 144px |

Una via misurata: logo a 140px dentro la `max-width: 1340px` che c'e' gia' (nessuna soglia nuova).
Cambia la misura del marchio su desktop fra 1121 e 1340: decisione del titolare. Altre vie da
valutare: stringere `--sd-nav-gap` sotto 16px, o portare la tendina oltre 1120 (ma 1120 e' la
soglia della scala, e la condividono hero e capitoli).

> Guarda l'header di `/` a **1121px**: «Lo Studio» copre la scritta del logo. Non toccare le
> soglie. Proponi la via che costa meno e dimmi lo spazio logo → menu a 1121, 1180, 1240, 1341 e
> 1440 — poi scelgo io. Niente modifiche prima.

---

Dopo ogni compito: `git diff --stat`, poi commit (in italiano) solo se lo chiedo.
