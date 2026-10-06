<?php
/**
 * Title: I Nostri Orari
 * Slug: sorriso-dambrosio/orari
 * Categories: sorriso-dambrosio
 * Description: La fascia scura degli orari: giorni a righe, oggi marcato, due pulsanti.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * La tabella non si scrive piu' qui.
 *
 * Gli orari sono contenuto, non impaginazione: vivono nel plugin
 * (`SD\Core\Studio::hours()`) e li rende il blocco `sd/orari`. Questo pattern
 * resta perche' la front page lo richiama per slug, ed e' il posto giusto per
 * dire che **in homepage il modulo e' in pagina**, quindi il pulsante punta
 * all'ancora `#prenota` e non alla pagina dei contatti.
 *
 * La stessa fascia compare su /contatti/ e su /lo-studio/: e' lo stesso
 * blocco, quindi non possono divergere.
 */
?>
<!-- wp:sd/orari {"ancora":"#prenota"} /-->
