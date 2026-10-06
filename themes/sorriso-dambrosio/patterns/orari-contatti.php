<?php
/**
 * Title: Orari e Vieni a Trovarci
 * Slug: sorriso-dambrosio/orari-contatti
 * Categories: sorriso-dambrosio
 * Description: Mappa a tutta larghezza con il pannello scuro di orari e indirizzo sovrapposto a sinistra.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * La mappa esce da entrambi i bordi e il pannello scuro ci galleggia sopra,
 * a sinistra. Sotto i 900px il pannello sale sopra la mappa, a tutta
 * larghezza: niente testo sovrapposto su uno schermo stretto.
 */
$sd_hours = array(
	array( 'Lunedi', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
	array( 'Martedi', '9:00 &#8211; 13:00', 'Chiuso' ),
	array( 'Mercoled&igrave;', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
	array( 'Gioved&igrave;', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
	array( 'Venerd&igrave;', '9:00 &#8211; 13:00', '14:00 &#8211; 19:00' ),
);

$sd_map = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2968.633916371706!2d12.468092614968212!3d41.922227870631446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x132f60ef1aca4077%3A0xc71223dd3dc0b35e!2sLungotevere%20Flaminio%2C%2034%2C%2000196%20Roma%20RM!5e0!3m2!1sit!2sit!4v1627212137112!5m2!1sit!2sit';
?>
<!-- wp:group {"className":"sd-visit","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-visit" style="margin-top:0;margin-bottom:0"><!-- wp:html -->
<div class="sd-visit__map">
	<iframe title="Mappa dello Studio Odontoiatrico D&#8217;Ambrosio, Lungotevere Flaminio 34, Roma" src="<?php echo esc_url( $sd_map ); ?>" width="800" height="450" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
</div>
<!-- /wp:html -->

<!-- wp:group {"className":"sd-wrap sd-visit__layer","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-visit__layer"><!-- wp:group {"className":"sd-visit__panel","style":{"spacing":{"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group sd-visit__panel has-base-color has-main-background-color has-text-color has-background has-link-color">
<?php echo sorriso_dambrosio_section_head( 'Dal 1956', 'I Nostri Orari' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:table {"className":"sd-hours"} -->
<figure class="wp-block-table sd-hours"><table><thead><tr><th>Giorno</th><th>Mattina</th><th>Pomeriggio</th></tr></thead><tbody>
<?php foreach ( $sd_hours as $sd_row ) : ?>
<tr><td><?php echo $sd_row[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td><td><?php echo $sd_row[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td><td><?php echo $sd_row[2]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td></tr>
<?php endforeach; ?>
</tbody></table><figcaption class="wp-element-caption">Sabato-Domenica chiuso</figcaption></figure>
<!-- /wp:table -->

<?php echo sorriso_dambrosio_section_head( 'Contatti', 'Vieni a Trovarci' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><a href="https://maps.app.goo.gl/jsVVDPiXFAFLCiE18" target="_blank" rel="noreferrer noopener">Lungotevere Flaminio n. 34 &#8211; 00196 &#8211; Roma (RM)</a><br><a href="tel:063225754">+39 06 322 5754</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
