# new-dambrosio

Sito dello **Studio Odontoiatrico D'Ambrosio** (`sorrisodambrosio.it`, Roma).

La radice di questo repository e' la cartella **`wp-content/`** di un'installazione
WordPress, non la radice di WordPress. Git traccia soltanto quello che fa parte del
progetto: il child theme, la documentazione e la configurazione di Claude Code.
Core, altri temi, plugin e `uploads/` sono deliberatamente ignorati.

```
.
├── CLAUDE.md                      guida operativa (ambiente, vincoli, trappole, verifiche)
├── docs/
│   ├── TRIAGE.md                  analisi dello stato di partenza
│   ├── DESIGN-SPEC.md             il sistema di layout
│   ├── REPORT.md                  che cosa e' stato costruito, le verifiche, l'autocritica
│   └── DA-VERIFICARE.md           decisioni in sospeso del titolare
└── themes/sorriso-dambrosio/      il tema (child di Ollie 1.6.3)
    ├── theme.json                 palette, tipografia, misure — schema v3
    ├── style.css                  foglio unico, zero valori di colore
    ├── functions.php              minimale, PHP 7.4, piu' tre helper per i pattern
    ├── assets/fonts/              Fraunces + DM Sans (woff2 variabili, licenza SIL OFL)
    ├── templates/front-page.html
    ├── parts/                     header, footer, barra CTA mobile
    └── patterns/                  una sezione per file, richiamata dalla pagina
```

## Come e' fatta la homepage

Ogni sezione e' un **pattern PHP** nel tema; la pagina «Home» (post 2059) e' un guscio
sottile di blocchi `core/pattern`. Cosi' la homepage e' versionata qui e **gli URL dei
media si risolvono a runtime**: niente URL `.local` congelati nel database, nessun
search-replace al deploy, e `srcset`/`width`/`height`/lazy loading gratis.

## Prima di modificare il layout

Leggere **`docs/DESIGN-SPEC.md`**. Contiene il sistema (misure, scala tipografica, fondi,
intestazione di sezione) e l'impianto di ciascuna sezione. La regola che lo regge:
*nessuna sezione riusa l'impianto di un'altra*. I punti ancora deboli sono elencati
onestamente in `docs/REPORT.md` §6.

I vincoli di contenuto, colore e immagini stanno in **`CLAUDE.md`**: vanno letti prima
di scrivere, non dopo.

## Ambiente

- Local by Flywheel, Apache. Il sito gira su **PHP 7.4.30**; WP-CLI gira su PHP 8.3, quindi
  non e' un giudice attendibile sulla compatibilita' PHP.
- `composer run phpstan` nel tema — livello 5, `phpVersion: 70400`.
- Le verifiche si fanno **sul sito in esecuzione**, non sui sorgenti: il comando per ognuna
  e' in `CLAUDE.md`.

## Deploy

GitHub Actions → FTP. **Non vanno in produzione**: `themes/sorriso-dambrosio/vendor/`,
`composer.json`, `composer.lock`, `phpstan.neon.dist`, `docs/`, `README.md`, `CLAUDE.md`
e `.claude/` — sono strumenti e documentazione di sviluppo.
