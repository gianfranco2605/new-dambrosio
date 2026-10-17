# CLAUDE.md — `wp-content/` di sorrisodambrosio.it

Il lavoro e' **nel tema**: la sessione si apre in `themes/sorriso-dambrosio/` e la guida
operativa e' il `CLAUDE.md` di quella cartella. Qui solo i vincoli che valgono per tutto il repo.

- Si tocca **solo** `themes/sorriso-dambrosio/`. Core, `ollie`, vecchio tema `sorrisodambrosio`,
  plugin di terzi, uploads, `vendor/`: non si leggono e non si toccano. Il plugin
  `plugins/sorriso-dambrosio-core/` e' nostro, ma si apre solo se il compito lo chiede.
- **Colori** solo dalla palette di `theme.json`. **Testo** letterale (pubblicita' sanitaria):
  niente riscritture ne' contenuto inventato. **Immagini** solo dalla Media Library.
- **Non cancellare** pagine o contenuti. Nessun push, import di DB o `search-replace` senza il titolare.
- Un compito per sessione: fare quello che e' chiesto, riferire in breve, fermarsi.

Guida completa del progetto (contenuti, URL, plugin, trappole, deploy): `docs/RIFERIMENTO.md`.
Leggerne **solo la sezione che serve** (`grep -n '^#' docs/RIFERIMENTO.md` prima).
