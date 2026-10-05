<?php
/**
 * Title: Perche noi
 * Slug: sorriso-dambrosio/perche-noi
 * Categories: sorriso-dambrosio
 * Description: Quattro motivi di fiducia, ciascuno con icona, titolo e descrizione.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

$sd_reasons = array(
	array(
		'icon'  => 193,
		'title' => 'Team odontoiatrico altamente qualificato.',
		'text'  => 'Il nostro team odontoiatrico &egrave; composto da professionisti altamente qualificati e dedicati alla cura della tua salute dentale.',
	),
	array(
		'icon'  => 194,
		'title' => 'Vasta linea di servizi odontoiatrici.',
		'text'  => 'La nostra vasta gamma di servizi odontoiatrici offre soluzioni complete per tutte le tue esigenze dentali.',
	),
	array(
		'icon'  => 195,
		'title' => 'Strutture e attrezzature avanzate.',
		'text'  => 'Le nostre strutture e attrezzature odontoiatriche all&#8217;avanguardia garantiscono trattamenti di alta qualit&agrave; e comfort per i nostri pazienti.',
	),
	array(
		'icon'  => 196,
		'title' => 'Risultati garantiti e sorrisi pi&ugrave; luminosi.',
		'text'  => 'I nostri trattamenti garantiscono risultati sorprendenti, regalando sorrisi pi&ugrave; luminosi e sicuri.',
	),
);
?>
<!-- wp:group {"align":"wide","className":"sd-section sd-section--tinted","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignwide sd-section sd-section--tinted" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xx-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium)">
<?php echo sorriso_dambrosio_section_head( 'Fiducia', 'Perch&eacute; noi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-prose","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|large"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group sd-prose" style="margin-bottom:var(--wp--preset--spacing--large)"><!-- wp:paragraph -->
<p>Crediamo nella tua salute dentale e desideriamo offrirti la migliore cura possibile presso lo Studio Odontoiatrico D&#8217;Ambrosio. Il nostro impegno nei tuoi confronti &egrave; chiaro e profondo, siamo qui per prenderci cura di te al meglio.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-grid sd-grid--reasons","layout":{"type":"default"}} -->
<div class="wp-block-group sd-grid sd-grid--reasons">
<?php foreach ( $sd_reasons as $sd_reason ) : ?>
<!-- wp:group {"className":"sd-reason","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group sd-reason">
<?php echo sorriso_dambrosio_image( $sd_reason['icon'], 'full', 'sd-reason__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:heading {"level":3,"fontSize":"base"} -->
<h3 class="wp-block-heading has-base-font-size"><?php echo $sd_reason['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small","textColor":"secondary"} -->
<p class="has-secondary-color has-text-color has-small-font-size"><?php echo $sd_reason['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
