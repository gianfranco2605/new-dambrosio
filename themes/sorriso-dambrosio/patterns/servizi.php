<?php
/**
 * Title: Nostri servizi
 * Slug: sorriso-dambrosio/servizi
 * Categories: sorriso-dambrosio
 * Description: Quattro pannelli fotografici a pieno sanguinamento, testo in basso sopra la fotografia.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Le fotografie sono i pannelli: niente card, niente bordi, niente ombre e
 * soprattutto niente icone. Le quattro clipart blu (801, 803, 804, 799)
 * uscivano a 230x153px accanto a fotografie vere e non aggiungevano nulla.
 *
 * Due immagini sostituite perche' troppo piccole per un pannello alto 520px
 * (entrambe gia' nella Media Library, stesso soggetto):
 *   Ortodonzia    1096  640x427  -> 527   2560x2080
 *   Implantologia  156  300x200  -> 1226  2560x1707
 */
$sd_services = array(
	array(
		'image' => 85,
		'title' => 'Estetica',
		'text'  => 'Scopri il piacere di riscoprire e riabbracciare la bellezza del tuo sorriso attraverso l&#8217;ampia gamma dei nostri servizi di estetica dentale di prima classe.',
		'link'  => '/estetica/',
	),
	array(
		'image' => 527,
		'title' => 'Ortodonzia',
		'text'  => 'Ci dedichiamo all&#8217;ortodonzia di alta qualit&agrave;, mettendo in primo piano il sorriso e la salute dentale per i nostri pazienti.',
		'link'  => '/ortodonzia/',
	),
	array(
		'image' => 1226,
		'title' => 'Implantologia',
		'text'  => 'Lo studio D&#8217;Ambrosio &egrave; la scelta perfetta per coloro che cercano soluzioni personalizzate in implantologia di alta qualit&agrave;.',
		'link'  => '/implantologia/',
	),
	array(
		'image' => 817,
		'title' => 'Altri Servizi',
		'text'  => 'Esplora la gamma completa di servizi dentali personalizzati per soddisfare le tue esigenze specifiche e un sorriso luminoso.',
		'link'  => '/trattamenti/',
	),
);
?>
<!-- wp:group {"tagName":"section","className":"sd-section sd-section--flush","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<section class="wp-block-group sd-section sd-section--flush" aria-labelledby="sd-servizi-titolo" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap">
<?php echo sorriso_dambrosio_section_head( 'Prestazioni', 'Nostri servizi', 2, '', 'sd-servizi-titolo' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-panels","style":{"spacing":{"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group sd-panels has-base-color has-text-color has-link-color">
<?php foreach ( $sd_services as $sd_service ) : ?>
<!-- wp:group {"className":"sd-panel","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-panel">
<?php echo sorriso_dambrosio_image( $sd_service['image'], 'large', 'sd-panel__figure' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-panel__body","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-panel__body"><!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $sd_service['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo $sd_service['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"sd-more","fontSize":"x-small"} -->
<p class="sd-more has-x-small-font-size"><a href="<?php echo esc_url( home_url( $sd_service['link'] ) ); ?>">DI PI&Ugrave;</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
