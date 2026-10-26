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

/*
 * Telefono e WhatsApp dall'unica fonte (`SD\Core\Studio::contacts()` via
 * helper), come header e hero: la barra e' la scorciatoia per chiamare, un
 * numero vecchio qui e' una chiamata persa.
 */
$sd_c = sorriso_dambrosio_contacts();
?>
<!-- wp:group {"className":"sd-mobile-cta","style":{"spacing":{"padding":{"top":"0.6rem","bottom":"0.6rem","right":"0.75rem","left":"0.75rem"},"blockGap":"0.5rem"}},"backgroundColor":"base","layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"}} -->
<div class="wp-block-group sd-mobile-cta has-base-background-color has-background" style="padding-top:0.6rem;padding-right:0.75rem;padding-bottom:0.6rem;padding-left:0.75rem"><!-- wp:buttons {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $sd_c['phone_href'] ); ?>" aria-label="Chiama lo studio al <?php echo esc_attr( $sd_c['phone'] ); ?>"><?php echo esc_html( $sd_c['phone'] ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $sd_c['whatsapp'] ); ?>" target="_blank" rel="noreferrer noopener">CONTATTI &#8594;</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
