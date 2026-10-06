<?php
/**
 * Title: Vieni a Trovarci
 * Slug: sorriso-dambrosio/vieni-a-trovarci
 * Categories: sorriso-dambrosio
 * Description: Mappa a tutta larghezza con il pannello scuro dell'indirizzo sovrapposto a sinistra.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Prima questa sezione portava anche la tabella degli orari. Ora gli orari hanno
 * una fascia dedicata subito dopo "Lo Studio" (pattern sorriso-dambrosio/orari):
 * qui resterebbero una seconda volta, quindi il pannello tiene solo l'indirizzo
 * e i recapiti, e rimanda agli orari con un collegamento.
 */
$sd_c = sorriso_dambrosio_contacts();

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
<?php echo sorriso_dambrosio_section_head( 'Contatti', 'Vieni a Trovarci' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:html -->
<ul class="sd-visit__list">
	<li>
		<?php echo sorriso_dambrosio_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><a href="<?php echo esc_url( $sd_c['map_href'] ); ?>" target="_blank" rel="noreferrer noopener"><?php echo $sd_c['address']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></span>
	</li>
	<li>
		<?php echo sorriso_dambrosio_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><a href="<?php echo esc_url( $sd_c['phone_href'] ); ?>"><?php echo esc_html( $sd_c['phone'] ); ?></a></span>
	</li>
	<li>
		<?php echo sorriso_dambrosio_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><a href="#orari">I Nostri Orari</a></span>
	</li>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
