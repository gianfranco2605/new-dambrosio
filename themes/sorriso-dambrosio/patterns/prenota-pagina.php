<?php
/**
 * Title: Prenota un appuntamento — pagina Contatti
 * Slug: sorriso-dambrosio/prenota-pagina
 * Categories: sorriso-dambrosio
 * Description: La stessa sezione "Prenota" della homepage, con il titolo della pagina: h1 «Contatti».
 * Inserter: false
 *
 * @package SorrisoDambrosio
 */

/*
 * E' il pattern `prenota`, non un gemello: entrambi stampano
 * sorriso_dambrosio_prenota(). Qui l'intestazione diventa l'h1 della pagina,
 * perche' su /contatti/ questa sezione e' la prima.
 */
echo sorriso_dambrosio_prenota( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
