<?php
/**
 * Title: Hero homepage
 * Slug: sorriso-dambrosio/hero
 * Categories: sorriso-dambrosio
 * Description: Fotografia dello studio a tutta pagina con la lastra scura del titolo ancorata in basso a sinistra.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * 636 AGA_5209 (2500x1685) sostituisce brother-1 (1260x838): a tutta
 * larghezza la vecchia immagine veniva ingrandita del 14% gia' a 1440px.
 * Sotto i 782px passa l'immagine verticale 1492, che e' nata per quel taglio.
 */
$sd_desktop_id = 636;
$sd_mobile_id  = 1492;

$sd_desktop        = sorriso_dambrosio_image_url( $sd_desktop_id, 'full' );
$sd_mobile         = sorriso_dambrosio_image_url( $sd_mobile_id, 'full' );
$sd_desktop_srcset = wp_get_attachment_image_srcset( $sd_desktop_id, 'full' );
$sd_mobile_srcset  = wp_get_attachment_image_srcset( $sd_mobile_id, 'full' );

$sd_desktop_meta = wp_get_attachment_metadata( $sd_desktop_id );
$sd_width        = isset( $sd_desktop_meta['width'] ) ? (int) $sd_desktop_meta['width'] : 2500;
$sd_height       = isset( $sd_desktop_meta['height'] ) ? (int) $sd_desktop_meta['height'] : 1685;

$sd_alt = get_post_meta( $sd_desktop_id, '_wp_attachment_image_alt', true );
$sd_alt = is_string( $sd_alt ) ? $sd_alt : '';

$sd_whatsapp = 'https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0';
?>
<!-- wp:group {"className":"sd-hero","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0","right":"0","left":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<picture class="sd-hero__media">
	<source media="(max-width: 781px)" sizes="150vw" srcset="<?php echo $sd_mobile_srcset ? esc_attr( $sd_mobile_srcset ) : esc_url( $sd_mobile ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<img src="<?php echo esc_url( $sd_desktop ); ?>"<?php echo $sd_desktop_srcset ? ' sizes="100vw" srcset="' . esc_attr( $sd_desktop_srcset ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> alt="<?php echo esc_attr( $sd_alt ); ?>" fetchpriority="high" decoding="async" width="<?php echo esc_attr( (string) $sd_width ); ?>" height="<?php echo esc_attr( (string) $sd_height ); ?>">
</picture>
<!-- /wp:html -->

<!-- wp:group {"className":"sd-hero__frame sd-shelf","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero__frame sd-shelf"><!-- wp:group {"className":"sd-hero__slab sd-shelf__bleed-start","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"backgroundColor":"main","textColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero__slab sd-shelf__bleed-start has-base-color has-main-background-color has-text-color has-background"><!-- wp:heading {"level":1,"className":"sd-hero__title","fontSize":"xx-large"} -->
<h1 class="wp-block-heading sd-hero__title has-xx-large-font-size">Studio Odontoiatrico D&#8217;Ambrosio</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"sd-hero__lede","fontSize":"medium"} -->
<p class="sd-hero__lede has-medium-font-size">Gli specialisti del sorriso.<br>Tradizione &#8211; passione &#8211; innovazione<br>per prenderci cura del tuo sorriso</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"sd-hero__actions","style":{"spacing":{"blockGap":"var:preset|spacing|small","margin":{"top":"var:preset|spacing|medium"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons sd-hero__actions" style="margin-top:var(--wp--preset--spacing--medium)"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $sd_whatsapp ); ?>" target="_blank" rel="noreferrer noopener">CONTATTI &#8594;</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline sd-button-ghost"} -->
<div class="wp-block-button is-style-outline sd-button-ghost"><a class="wp-block-button__link wp-element-button" href="#chi-siamo">CHI SIAMO &#8594;</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
