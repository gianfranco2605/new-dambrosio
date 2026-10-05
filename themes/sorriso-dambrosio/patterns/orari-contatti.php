<?php
/**
 * Title: Orari e Vieni a Trovarci
 * Slug: sorriso-dambrosio/orari-contatti
 * Categories: sorriso-dambrosio
 * Description: Tabella degli orari di apertura affiancata alla mappa dello studio.
 * Inserter: true
 *
 * @package SorrisoDambrosio
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
<!-- wp:group {"align":"wide","className":"sd-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignwide sd-section" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xx-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium)"><!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|x-large","left":"var:preset|spacing|x-large"},"margin":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top" style="margin-top:0;margin-bottom:0"><!-- wp:column {"verticalAlignment":"top","width":"42%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:42%">
<?php echo sorriso_dambrosio_section_head( 'Dal 1956', 'I Nostri Orari' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:table {"className":"sd-hours"} -->
<figure class="wp-block-table sd-hours"><table><thead><tr><th>Giorno</th><th>Mattina</th><th>Pomeriggio</th></tr></thead><tbody>
<?php foreach ( $sd_hours as $sd_row ) : ?>
<tr><td><?php echo $sd_row[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td><td><?php echo $sd_row[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td><td><?php echo $sd_row[2]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td></tr>
<?php endforeach; ?>
</tbody></table><figcaption class="wp-element-caption">Sabato-Domenica chiuso</figcaption></figure>
<!-- /wp:table --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"58%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:58%">
<?php echo sorriso_dambrosio_section_head( 'Contatti', 'Vieni a Trovarci' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:html -->
<div class="sd-map">
	<iframe title="Mappa dello Studio Odontoiatrico D&#8217;Ambrosio, Lungotevere Flaminio 34, Roma" src="<?php echo esc_url( $sd_map ); ?>" width="800" height="450" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
</div>
<!-- /wp:html --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
