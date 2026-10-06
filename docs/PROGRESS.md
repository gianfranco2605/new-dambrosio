# PROGRESS — Iterazione 4: architettura dell'informazione e pagine interne

> Questo file e' il punto di ripresa. Se la sessione si interrompe, una nuova
> sessione deve poter ripartire da qui e da `git log`, senza altro contesto.

Aggiornato: 2026-10-06

## Stato

| Fase | Che cosa | Stato |
|---|---|---|
| A | Inventario e architettura dell'informazione | **fatto** |
| B | Plugin `sorriso-dambrosio-core`: CPT, tassonomia, campi, blocchi | da fare |
| C | Template e pattern nel tema figlio | da fare |
| D | Migrazione dei contenuti, URL, menu | da fare |
| E | Dati strutturati JSON-LD | da fare |
| F | Verifiche, screenshot, report | da fare |

## Fatto finora

- Inventario completo delle 14 pagine e 3 articoli pubblicati: ID, URL, template,
  parole, immagini, gallerie before/after, metadati Yoast.
- Scoperta portante: **il menu ha 45 voci di secondo livello che puntano ad ancore
  dentro 7 pagine monolitiche**. Quelle 45 ancore sono i veri contenuti del sito.
- Estrattore dei contenuti: `docs/migration/extract.py` — spezza una vecchia pagina nelle
  sue sezioni ancorate (titolo, immagine, paragrafi letterali, pulsanti).
- Screenshot «prima» di tutte le pagine a 1440px in
  `../../../redesign/screenshots/before-iter4/` (vanno presi ora: le vecchie pagine passeranno in bozza).
- `docs/ARCHITECTURE.md` scritto.

## Prossimo passo

Fase B — creare `plugins/sorriso-dambrosio-core/`.

## Problemi aperti

- Le gallerie before/after (plugin BEAF) salvano gli **URL assoluti** delle immagini
  nel meta `beaf_meta` (`http://sorrisodambrosio.local/...`). In produzione servira'
  un search-replace: non e' un problema introdotto da questo lavoro, ma va segnalato.
- L'area «Denti e postura» non ha nessun testo introduttivo esistente: la sua pagina
  elenchera' i trattamenti senza occhiello descrittivo. Vedi `docs/DA-VERIFICARE.md`.
