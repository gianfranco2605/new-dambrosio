<?php
/**
 * Title: Nostri servizi
 * Slug: sorriso-dambrosio/servizi
 * Categories: sorriso-dambrosio
 * Description: Quattro aree di intervento in schede con immagine, icona e collegamento.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

$sd_services = array(
	array(
		'image' => 85,
		'icon'  => 801,
		'title' => 'Estetica',
		'text'  => 'Scopri il piacere di riscoprire e riabbracciare la bellezza del tuo sorriso attraverso l&#8217;ampia gamma dei nostri servizi di estetica dentale di prima classe.',
		'link'  => '/estetica/',
	),
	array(
		'image' => 1096,
		'icon'  => 803,
		'title' => 'Ortodonzia',
		'text'  => 'Ci dedichiamo all&#8217;ortodonzia di alta qualit&agrave;, mettendo in primo piano il sorriso e la salute dentale per i nostri pazienti.',
		'link'  => '/ortodonzia/',
	),
	array(
		'image' => 156,
		'icon'  => 804,
		'title' => 'Implantologia',
		'text'  => 'Lo studio D&#8217;Ambrosio &egrave; la scelta perfetta per coloro che cercano soluzioni personalizzate in implantologia di alta qualit&agrave;.',
		'link'  => '/implantologia/',
	),
	array(
		'image' => 817,
		'icon'  => 799,
		'title' => 'Altri Servizi',
		'text'  => 'Esplora la gamma completa di servizi dentali personalizzati per soddisfare le tue esigenze specifiche e un sorriso luminoso.',
		'link'  => '/trattamenti/',
	),
);
?>
<!-- wp:group {"align":"wide","className":"sd-section sd-section--tinted","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignwide sd-section sd-section--tinted" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xx-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium)">
<?php echo sorriso_dambrosio_section_head( 'Prestazioni', 'Nostri servizi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:columns {"className":"sd-cards","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-columns sd-cards" style="margin-top:0;margin-bottom:0">
<?php foreach ( $sd_services as $sd_service ) : ?>
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sd-card","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group sd-card has-base-background-color has-background" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<?php echo sorriso_dambrosio_image( $sd_service['image'], 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-card__body","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","right":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group sd-card__body" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium)">
<?php echo sorriso_dambrosio_image( $sd_service['icon'], 'full', 'sd-card__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:heading {"level":3,"fontSize":"base"} -->
<h3 class="wp-block-heading has-base-font-size"><?php echo $sd_service['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small","textColor":"secondary"} -->
<p class="has-secondary-color has-text-color has-small-font-size"><?php echo $sd_service['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( $sd_service['link'] ) ); ?>">DI PI&Ugrave;</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
