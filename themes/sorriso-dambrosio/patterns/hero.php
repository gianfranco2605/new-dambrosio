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

$sd_desktop_id = 1207;
$sd_mobile_id  = 1492;

$sd_desktop = sorriso_dambrosio_image_url( $sd_desktop_id, 'full' );
$sd_mobile  = sorriso_dambrosio_image_url( $sd_mobile_id, 'full' );

// srcset per non far scaricare l'immagine intera a chi sta su mobile.
$sd_desktop_srcset = wp_get_attachment_image_srcset( $sd_desktop_id, 'full' );
$sd_mobile_srcset  = wp_get_attachment_image_srcset( $sd_mobile_id, 'full' );

$sd_desktop_meta = wp_get_attachment_metadata( $sd_desktop_id );
$sd_width        = isset( $sd_desktop_meta['width'] ) ? (int) $sd_desktop_meta['width'] : 1600;
$sd_height       = isset( $sd_desktop_meta['height'] ) ? (int) $sd_desktop_meta['height'] : 900;

$sd_whatsapp = 'https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0';
?>
<!-- wp:group {"className":"sd-hero","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0","right":"0","left":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<picture class="sd-hero__media">
	<source media="(max-width: 781px)" sizes="100vw" srcset="<?php echo $sd_mobile_srcset ? esc_attr( $sd_mobile_srcset ) : esc_url( $sd_mobile ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<img src="<?php echo esc_url( $sd_desktop ); ?>"<?php echo $sd_desktop_srcset ? ' sizes="100vw" srcset="' . esc_attr( $sd_desktop_srcset ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> alt="" fetchpriority="high" decoding="async" width="<?php echo esc_attr( (string) $sd_width ); ?>" height="<?php echo esc_attr( (string) $sd_height ); ?>">
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
