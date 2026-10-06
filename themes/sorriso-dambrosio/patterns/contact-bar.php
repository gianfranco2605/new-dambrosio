<?php
/**
 * Title: Barra contatti
 * Slug: sorriso-dambrosio/contact-bar
 * Categories: sorriso-dambrosio
 * Description: Rail scuro a tutta larghezza con indirizzo, telefoni ed email, in continuita' con la lastra dell'hero.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Non e' piu' una scheda bianca con l'ombra che galleggia sull'hero: la
 * lastra del titolo e questa barra sono la stessa forma scura, continua.
 * Le etichette ("Indirizzo:", "Tel:", "Cel:") sono il testo esistente.
 */
$sd_cells = array(
	array(
		'label' => 'Indirizzo:',
		'value' => '<a href="https://maps.app.goo.gl/jsVVDPiXFAFLCiE18" target="_blank" rel="noreferrer noopener">Lungotevere Flaminio n. 34 &#8211; 00196 &#8211; Roma (RM)</a>',
	),
	array(
		'label' => 'Tel:',
		'value' => '<a href="tel:063225754">+39 06 322 5754</a><br><a href="tel:063610625">+39 06 361 0625</a>',
	),
	array(
		'label' => 'Cel:',
		'value' => '<a href="tel:+393755524596">+39 375 552 4596</a>',
	),
	array(
		'label' => 'Email:',
		'value' => '<a href="mailto:studio@sorrisodambrosio.it">studio@sorrisodambrosio.it</a>',
	),
);
?>
<!-- wp:group {"className":"sd-rail","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group sd-rail has-base-color has-main-background-color has-text-color has-background has-link-color" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-rail__inner","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-rail__inner">
<?php foreach ( $sd_cells as $sd_cell ) : ?>
<!-- wp:group {"className":"sd-rail__cell","style":{"spacing":{"blockGap":"0.3rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-rail__cell"><!-- wp:paragraph {"className":"sd-rail__label","textColor":"main-accent","fontSize":"x-small"} -->
<p class="sd-rail__label has-main-accent-color has-text-color has-x-small-font-size"><?php echo $sd_cell['label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo $sd_cell['value']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
