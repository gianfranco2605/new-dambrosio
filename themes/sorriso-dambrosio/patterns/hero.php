<?php
/**
 * Title: Hero homepage
 * Slug: sorriso-dambrosio/hero
 * Categories: sorriso-dambrosio
 * Description: Immagine a tutta larghezza, occhiello, titolo principale e due inviti all'azione.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

$sd_desktop = sorriso_dambrosio_image_url( 1207, 'full' );
$sd_mobile  = sorriso_dambrosio_image_url( 1492, 'full' );
$sd_whatsapp = 'https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0';
?>
<!-- wp:group {"className":"sd-hero","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0","right":"0","left":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<picture class="sd-hero__media">
	<source media="(max-width: 781px)" srcset="<?php echo esc_url( $sd_mobile ); ?>">
	<img src="<?php echo esc_url( $sd_desktop ); ?>" alt="" fetchpriority="high" decoding="async" width="1600" height="900">
</picture>
<div class="sd-hero__scrim" aria-hidden="true"></div>
<!-- /wp:html -->

<!-- wp:group {"className":"sd-hero__content","style":{"spacing":{"padding":{"top":"var:preset|spacing|xxx-large","bottom":"var:preset|spacing|xxx-large","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group sd-hero__content" style="padding-top:var(--wp--preset--spacing--xxx-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--xxx-large);padding-left:var(--wp--preset--spacing--medium)"><!-- wp:group {"className":"sd-hero__inner","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group sd-hero__inner"><!-- wp:paragraph {"className":"sd-eyebrow","textColor":"base","fontSize":"small"} -->
<p class="sd-eyebrow has-base-color has-text-color has-small-font-size">Gli specialisti del sorriso</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"sd-hero__title","textColor":"base","fontSize":"display"} -->
<h1 class="wp-block-heading sd-hero__title has-base-color has-text-color has-display-font-size">Studio Odontoiatrico D&#8217;Ambrosio</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"sd-hero__claim","textColor":"base","fontSize":"medium"} -->
<p class="sd-hero__claim has-base-color has-text-color has-medium-font-size">Tradizione &#8211; passione &#8211; innovazione<br>per prenderci cura del tuo sorriso</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|small","margin":{"top":"var:preset|spacing|medium"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--medium)"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $sd_whatsapp ); ?>" target="_blank" rel="noreferrer noopener">CONTATTI &#8594;</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline sd-button-ghost"} -->
<div class="wp-block-button is-style-outline sd-button-ghost"><a class="wp-block-button__link wp-element-button" href="#chi-siamo">CHI SIAMO &#8594;</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
