# PROGRESS — Iterazione 4: architettura dell'informazione e pagine interne

> Questo file e' il punto di ripresa. Se la sessione si interrompe, una nuova
> sessione deve poter ripartire da qui e da `git log`, senza altro contesto.

Aggiornato: 2026-10-07 — **iterazione completa.**

## Stato

| Fase | Che cosa | Stato |
|---|---|---|
| A | Inventario e architettura dell'informazione | **fatto** — `docs/ARCHITECTURE.md` |
| B | Plugin `sorriso-dambrosio-core`: CPT, tassonomia, campi, blocchi | **fatto** |
| C | Template e pattern nel tema figlio | **fatto** — 10 template nuovi, §18 di `style.css` |
| D | Migrazione dei contenuti, URL, menu | **fatto** — 49 voci, 71 URL verificati |
| E | Dati strutturati JSON-LD | **fatto** |
| F | Verifiche, screenshot, documentazione | **fatto** — `docs/REPORT.md` §12 |

## Che cosa c'e' adesso

```
plugins/sorriso-dambrosio-core/     IL CONTENUTO
  includes/class-content-types.php  4 tipi + tassonomia sd_area
  includes/class-fields.php         post meta + riquadri dell'admin
  includes/class-permalinks.php     regole d'area, permalink, redirect
  includes/class-studio.php         orari e recapiti — UNICA FONTE
  includes/class-relations.php      le relazioni, lette dai due versi
  includes/class-blocks.php         13 blocchi a resa server
  includes/class-schema.php         JSON-LD
  includes/class-render.php         markup condiviso
  blocks/*/block.json               i manifesti

themes/sorriso-dambrosio/           LA PRESENTAZIONE
  templates/                        10 template nuovi + front-page
  patterns/                         + lo-studio-pagina, contatti-recapiti,
                                      contatti-modulo; orari ora chiama sd/orari
  style.css §18                     l'impianto delle pagine interne

docs/
  ARCHITECTURE.md                   il modello dei contenuti e gli URL
  GUIDA-CLIENTE.md                  come lavorarci, per lo studio
  REPORT.md §12                     verifiche e autocritica
  DA-VERIFICARE.md §11-13           le decisioni ancora in mano al titolare
  migration/                        gli script, rieseguibili e idempotenti
```

## Rieseguire la migrazione

Gli script sono idempotenti: riconoscono le voci gia' migrate dal meta
`_sd_origine` e le aggiornano invece di duplicarle.

```bash
wp eval-file docs/migration/01-aree.php
wp eval-file docs/migration/02-trattamenti-tecnologie.php
wp eval-file docs/migration/03-professionisti-casi.php
wp eval-file docs/migration/04-pagine-e-menu.php
wp eval-file docs/migration/05-livelli-titoli.php
wp rewrite flush --hard

# verifiche
wp eval-file docs/migration/verifica-testi.php    # ogni frase e' arrivata
bash docs/migration/verifica-url.sh               # nessun 404
```

Backup pre-migrazione: `../../../backups/pre-architecture.sql`.

## Da fare, se si riprende

Niente di bloccante. Quel che resta sono **decisioni del titolare**, tutte in
`docs/DA-VERIFICARE.md`:

- §11.3 — cinque relazioni inferite da confermare (si fa dal pannello)
- §12 — orari e recapiti modificabili dall'admin? Oggi serve uno sviluppatore
- §13.1 — «Denti e postura» non ha un testo introduttivo
- §13.2 — i testi alternativi delle gallerie prima/dopo sono inutilizzabili
- §13.4 — i refusi «Inesti» e «Cannini» nei titoli
- §13.5 — `/70-anni-attivita/` e' pubblicata e vuota

## Trappole trovate in questa iterazione

- **`wp eval-file` esegue con `eval()`, non con `include`**: uno script di
  migrazione non puo' dichiarare `strict_types`, non sarebbe la prima
  istruzione del file.
- **Il pattern nuovo non compare** finche' non cambia la versione del tema:
  WordPress tiene in cache l'elenco dei pattern per versione.
- **`block.json` dichiara i valori di partenza**, quindi un attributo arriva
  sempre valorizzato, anche a `''`: un `isset()` secco fa vincere il vuoto sul
  ripiego scritto in PHP.
- **iubenda rimonta tutta la pagina con un parser DOM** e converte ogni
  carattere non ASCII in entita' numerica — nel JSON-LD diventa testo
  letterale. Si aggira scrivendo il JSON tutto in `\uXXXX`.
- **`&rsquo;` e' un'entita' nominata di HTML5**: `html_entity_decode` senza
  `ENT_HTML5` non la conosce.
- **Il plugin BEAF stampa il titolo del caso in un `h2` suo**, allo stesso
  livello dei titoli che lo raggruppano.
- **Le gallerie BEAF salvano URL assoluti** nel meta `beaf_meta`: al deploy
  serve un `wp search-replace`. Non e' un problema introdotto qui.
