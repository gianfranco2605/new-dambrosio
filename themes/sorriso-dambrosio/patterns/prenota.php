<?php
/**
 * Title: Prenota un appuntamento
 * Slug: sorriso-dambrosio/prenota
 * Categories: sorriso-dambrosio
 * Description: Il modulo di contatto, con i recapiti accanto. E' la destinazione di tutti i pulsanti "Prenota".
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * L'ancora #prenota e' il bersaglio dei pulsanti nell'header, nell'hero e nella
 * fascia degli orari: se cambia, vanno cambiati anche quelli.
 *
 * Il modulo e' Contact Form 7. Il blocco core/shortcode non esegue gli shortcode
 * (fa solo wpautop) e i pattern non passano dal filtro the_content: qui serve
 * do_shortcode(). I <p> e <br> automatici di CF7 sono disattivati in
 * functions.php con il filtro wpcf7_autop_or_not, altrimenti spezzano la griglia.
 */
$sd_c = sorriso_dambrosio_contacts();

$sd_form = do_shortcode( '[contact-form-7 id="17f113f" title="Modulo contatto" html_class="sd-form-wrapper"]' );
?>
<!-- wp:group {"className":"sd-section sd-prenota","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-prenota" id="prenota" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap sd-prenota__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-prenota__grid"><!-- wp:group {"className":"sd-prenota__aside","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-prenota__aside">
<?php echo sorriso_dambrosio_section_head( 'Contatti', 'Prenota un appuntamento' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>


<!-- wp:html -->
<ul class="sd-prenota__contacts">
	<li>
		<?php echo sorriso_dambrosio_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span>
			<a href="<?php echo esc_url( $sd_c['phone_href'] ); ?>"><?php echo esc_html( $sd_c['phone'] ); ?></a><br>
			<a href="<?php echo esc_url( $sd_c['phone2_href'] ); ?>"><?php echo esc_html( $sd_c['phone2'] ); ?></a><br>
			<a href="<?php echo esc_url( $sd_c['mobile_href'] ); ?>"><?php echo esc_html( $sd_c['mobile'] ); ?></a>
		</span>
	</li>
	<li>
		<?php echo sorriso_dambrosio_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><a href="<?php echo esc_url( $sd_c['email_href'] ); ?>"><?php echo esc_html( $sd_c['email'] ); ?></a></span>
	</li>
	<li>
		<?php echo sorriso_dambrosio_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><a href="<?php echo esc_url( $sd_c['map_href'] ); ?>" target="_blank" rel="noreferrer noopener"><?php echo $sd_c['address']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></span>
	</li>
	<li>
		<?php echo sorriso_dambrosio_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><a href="#orari">I Nostri Orari</a></span>
	</li>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="sd-prenota__form">
<?php echo $sd_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
