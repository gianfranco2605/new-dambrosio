<?php
/**
 * Title: CTA mobile
 * Slug: sorriso-dambrosio/mobile-cta
 * Categories: sorriso-dambrosio
 * Description: Barra fissa con telefono e WhatsApp, visibile solo sotto i 782px.
 * Inserter: false
 *
 * @package SorrisoDambrosio
 */

$sd_whatsapp = 'https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0';
?>
<!-- wp:group {"className":"sd-mobile-cta","style":{"spacing":{"padding":{"top":"0.6rem","bottom":"0.6rem","right":"0.75rem","left":"0.75rem"},"blockGap":"0.5rem"}},"backgroundColor":"base","layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"}} -->
<div class="wp-block-group sd-mobile-cta has-base-background-color has-background" style="padding-top:0.6rem;padding-right:0.75rem;padding-bottom:0.6rem;padding-left:0.75rem"><!-- wp:buttons {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="tel:063225754" aria-label="Chiama lo studio al +39 06 322 5754">+39 06 322 5754</a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $sd_whatsapp ); ?>" target="_blank" rel="noreferrer noopener">CONTATTI &#8594;</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
