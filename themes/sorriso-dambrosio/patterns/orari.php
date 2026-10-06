<?php
/**
 * Title: I Nostri Orari
 * Slug: sorriso-dambrosio/orari
 * Categories: sorriso-dambrosio
 * Description: Gli orari in evidenza su fascia scura: i giorni come righe, mattina e pomeriggio separati, oggi evidenziato.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Il titolare ha chiesto che gli orari si vedano. Non piu' una tabella in fondo
 * alla pagina ma una fascia scura subito dopo "Lo Studio": i giorni sono righe,
 * mattina e pomeriggio due caselle distinte, i turni chiusi restano visibili ma
 * spenti, e il giorno corrente e' marcato.
 *
 * L'evidenziazione di oggi si calcola in PHP con il fuso del sito, non in
 * JavaScript: cosi' e' gia' giusta nell'HTML servito e non dipende dall'orologio
 * del visitatore. La pagina e' in cache per giorno (vedi il commento sotto).
 */
$sd_hours = sorriso_dambrosio_hours();
$sd_c     = sorriso_dambrosio_contacts();

// wp_date() usa il fuso configurato nel sito, date() userebbe quello del server.
$sd_today = (int) wp_date( 'N' );
?>
<!-- wp:group {"className":"sd-section sd-section--dark sd-hours-band","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-section--dark sd-hours-band has-base-color has-main-background-color has-text-color has-background has-link-color" id="orari" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap sd-hours-band__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-hours-band__grid"><!-- wp:group {"className":"sd-hours-band__aside","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hours-band__aside">
<?php echo sorriso_dambrosio_section_head( 'Quando siamo aperti', 'I Nostri Orari' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:paragraph {"className":"sd-hours-band__note","textColor":"main-accent","fontSize":"small"} -->
<p class="sd-hours-band__note has-main-accent-color has-text-color has-small-font-size">Sabato-Domenica chiuso</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="sd-hours-band__actions">
	<a class="sd-btn sd-btn--solid" href="<?php echo esc_url( $sd_c['phone_href'] ); ?>">
		<?php echo sorriso_dambrosio_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><?php echo esc_html( $sd_c['phone'] ); ?></span>
	</a>
	<a class="sd-btn sd-btn--ghost" href="#prenota">
		<?php echo sorriso_dambrosio_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span>Prenota un appuntamento</span>
	</a>
</div>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="sd-hours-week">
<?php
foreach ( $sd_hours as $sd_n => $sd_day ) :
	$sd_is_today  = ( $sd_n === $sd_today );
	$sd_all_shut  = ( 'Chiuso' === $sd_day['morning'] && 'Chiuso' === $sd_day['afternoon'] );
	$sd_row_class = 'sd-hours-week__day'
		. ( $sd_is_today ? ' is-today' : '' )
		. ( $sd_all_shut ? ' is-closed' : '' );
	?>
	<li class="<?php echo esc_attr( $sd_row_class ); ?>"<?php echo $sd_is_today ? ' aria-current="date"' : ''; ?>>
		<span class="sd-hours-week__name">
			<?php echo $sd_day['name']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $sd_is_today ? '<span class="sd-hours-week__today">oggi</span>' : ''; ?>
		</span>
		<span class="sd-hours-week__slots">
			<span class="sd-hours-week__slot<?php echo 'Chiuso' === $sd_day['morning'] ? ' is-shut' : ''; ?>"><?php echo $sd_day['morning']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span class="sd-hours-week__slot<?php echo 'Chiuso' === $sd_day['afternoon'] ? ' is-shut' : ''; ?>"><?php echo $sd_day['afternoon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</span>
	</li>
<?php endforeach; ?>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
