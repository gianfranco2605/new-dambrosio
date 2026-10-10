<?php
/**
 * Title: Contatti — intestazione e recapiti
 * Slug: sorriso-dambrosio/contatti-recapiti
 * Categories: sorriso-dambrosio
 * Description: Il titolo della pagina contatti e tutti i recapiti, letti dall'unica fonte del plugin.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * I recapiti non sono riscritti qui: arrivano dal blocco sd/contatti, che
 * legge `SD\Core\Studio::contacts()`. Se cambia un numero, cambia in un posto
 * solo e si aggiorna header, hero, footer, orari e questa pagina insieme.
 */
?>
<!-- wp:group {"className":"sd-section sd-contactpage","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-contactpage" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap"><!-- wp:group {"className":"sd-contactpage__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-contactpage__grid"><!-- wp:group {"className":"sd-head","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-head"><!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->
<p class="sd-eyebrow has-x-small-font-size">Dove siamo</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Contatti</h1>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:sd/contatti {"variante":"elenco"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
