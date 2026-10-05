<?php
/**
 * Title: Footer
 * Slug: sorriso-dambrosio/footer
 * Categories: sorriso-dambrosio
 * Description: Pie' di pagina con logo, recapiti, dati societari, social e orari.
 * Inserter: false
 *
 * @package SorrisoDambrosio
 */

$sd_whatsapp = 'https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0';
$sd_hours    = array(
	array( 'Lunedi', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
	array( 'Martedi', '9:00 &#8211; 13:00', 'Chiuso' ),
	array( 'Mercoled&igrave;', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
	array( 'Gioved&igrave;', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
	array( 'Venerd&igrave;', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
);
?>
<!-- wp:group {"align":"full","className":"sd-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|medium","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0"},"blockGap":"var:preset|spacing|large"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignfull sd-footer has-base-color has-main-background-color has-text-color has-background has-link-color" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium)"><!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|large","left":"var:preset|spacing|x-large"},"margin":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top" style="margin-top:0;margin-bottom:0"><!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
<div class="wp-block-column is-vertically-aligned-top">
<?php echo sorriso_dambrosio_image( 60, 'full', 'logo-footer' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><a href="mailto:studio@sorrisodambrosio.it">studio@sorrisodambrosio.it</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size">Tel: <a href="tel:063225754">+39 06 322 5754</a> &#8594;&nbsp;<br>Tel: <a href="tel:063610625">+39 06 361 0625 &#8594;</a><br>Cel: <a href="tel:+393755524596">+39 375 552 4596</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"main-accent","fontSize":"x-small"} -->
<p class="has-main-accent-color has-text-color has-x-small-font-size">Rag. Sociale: Studio Odontoiatrico Specialistico Associato D&#8217;Ambrosio<br>Indirizzo: Lungotevere Flaminio n. 34 &#8211; 00196 &#8211; Roma (RM)<br>Partita IVA: 05429801003</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"openInNewTab":true,"className":"sd-social","style":{"spacing":{"blockGap":{"top":"16px","left":"16px"},"margin":{"top":"var:preset|spacing|small"}}},"layout":{"type":"flex"}} -->
<ul class="wp-block-social-links sd-social" style="margin-top:var(--wp--preset--spacing--small)"><!-- wp:social-link {"url":"<?php echo esc_url( $sd_whatsapp ); ?>","service":"whatsapp"} /-->

<!-- wp:social-link {"url":"https://www.instagram.com/studioodontoiatricodambrosio/","service":"instagram"} /-->

<!-- wp:social-link {"url":"https://www.facebook.com/studioodontoiatricodambrosio/","service":"facebook"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
<div class="wp-block-column is-vertically-aligned-top"><!-- wp:heading {"level":2,"fontSize":"medium","textColor":"base"} -->
<h2 class="wp-block-heading has-base-color has-text-color has-medium-font-size">I Nostri Orari</h2>
<!-- /wp:heading -->

<!-- wp:table {"className":"sd-hours sd-hours--footer"} -->
<figure class="wp-block-table sd-hours sd-hours--footer"><table><thead><tr><th>Giorno</th><th>Mattina</th><th>Pomeriggio</th></tr></thead><tbody>
<?php foreach ( $sd_hours as $sd_row ) : ?>
<tr><td><?php echo $sd_row[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td><td><?php echo $sd_row[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td><td><?php echo $sd_row[2]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td></tr>
<?php endforeach; ?>
</tbody></table></figure>
<!-- /wp:table -->

<!-- wp:paragraph {"textColor":"main-accent","fontSize":"small"} -->
<p class="has-main-accent-color has-text-color has-small-font-size">Sabato-Domenica chiuso</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"sd-footer__rule","backgroundColor":"secondary"} -->
<hr class="wp-block-separator has-text-color has-secondary-color has-alpha-channel-opacity has-secondary-background-color has-background sd-footer__rule"/>
<!-- /wp:separator -->

<!-- wp:html -->
<?php
/*
 * Blocco legale iubenda.
 *
 * Il plugin cerca un pattern chiamato "<stylesheet>/footer" e, se non vi
 * trova la stringa [iub-wp-block-buttons], ci inietta dentro un proprio
 * <div> come primo figlio del secondo <div> del footer: nel nostro caso
 * finirebbe dentro le colonne, rompendo la riga.
 *
 * Il blocco core/shortcode non espande gli shortcode (fa solo wpautop) e le
 * parti template non passano dal filtro the_content: lo shortcode va quindi
 * eseguito qui. Il commento HTML qui sotto mantiene la stringa letterale nel
 * contenuto del pattern, cosi' il plugin riconosce che il blocco legale e'
 * gia' presente e non lo inietta una seconda volta.
 */
?>
<!-- blocco legale iubenda gestito dal tema: [iub-wp-block-buttons] -->
<div class="sd-footer__legal"><?php echo do_shortcode( '[iub-wp-block-buttons]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
<!-- /wp:html -->

<!-- wp:paragraph {"align":"center","textColor":"main-accent","fontSize":"x-small"} -->
<p class="has-text-align-center has-main-accent-color has-text-color has-x-small-font-size">&copy;Copyright 2026 -&nbsp;<a href="https://elpuasdigitalcrafts.com/" target="_blank" rel="noreferrer noopener">Build by ElPuasDigitalCrafts</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
