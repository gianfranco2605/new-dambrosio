<?php
/**
 * Title: I Professionisti
 * Slug: sorriso-dambrosio/professionisti
 * Categories: sorriso-dambrosio
 * Description: I tre medici come righe di un elenco redazionale, ritratto stretto a sinistra e biografia a destra.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Non tre card affiancate ma tre righe divise da filetti: 30/70, ritratto
 * a sinistra e biografia a destra. I ritratti scendono da ~390 a 260px,
 * cosi' i tre originali (420x554, 420x419, 486x642) tornano nitidi anche
 * su Retina senza chiedere al titolare scatti nuovi.
 */
$sd_doctors = array(
	array(
		'anchor' => 'nicoletta',
		'image'  => 1201,
		'name'   => 'Dott.ssa Nicoletta D&#8217;Ambrosio',
		'bio'    => 'La Dott.ssa Nicoletta D&#8217;Ambrosio si distingue nell&#8217;ambito della Parodontologia e dell&#8217;estetica del sorriso, offrendo competenze specializzate e personalizzate.<br>La sua attuale pratica &egrave; incentrata su una visione completa della salute orale, integrando approcci avanzati in Parodontologia con l&#8217;obiettivo di migliorare l&#8217;estetica del sorriso.',
		'link'   => '',
	),
	array(
		'anchor' => 'stefania',
		'image'  => 1085,
		'name'   => 'Dott.ssa Stefania D&#8217;Ambrosio',
		'bio'    => 'La Dott.ssa Stefania D&#8217;Ambrosio si distingue nell&#8217;Ortodonzia per pazienti di tutte le et&agrave;, utilizzando tecniche tradizionali, linguali e invisibili. La sua pratica mira a garantire risultati estetici e funzionali personalizzati, incorporando le ultime innovazioni nel campo ortodontico.',
		'link'   => '/ortodonzia/#sty-casi',
	),
	array(
		'anchor' => 'giuseppe',
		'image'  => 75,
		'name'   => 'Dott. Giuseppe D&#8217;Ambrosio',
		'bio'    => 'Il Dott. Giuseppe D&#8217;Ambrosio eccelle nei campi dell&#8217;Implantologia, Chirurgia Orale e Osseo-Ricostruttiva, Parodontologia e Protesi Estetica, fornendo competenze specializzate e personalizzate a una vasta gamma di pazienti.<br>La sua dedizione si estende oltre la mera pratica, abbracciando un approccio completo che integra tecniche avanzate e metodologie all&#8217;avanguardia in ciascuna di queste discipline.',
		'link'   => '/estetica-dentale/#casi-giuseppe',
	),
);
?>
<!-- wp:group {"className":"sd-section sd-section--tint","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-section--tint" id="chi-siamo" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap">
<?php echo sorriso_dambrosio_section_head( 'Dottori', 'I Professionisti' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-roster","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-roster">
<?php foreach ( $sd_doctors as $sd_doctor ) : ?>
<!-- wp:group {"className":"sd-roster__row","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-roster__row" id="<?php echo esc_attr( $sd_doctor['anchor'] ); ?>">
<?php echo sorriso_dambrosio_image( $sd_doctor['image'], 'medium_large', 'sd-roster__portrait' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-roster__text","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-roster__text"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php echo $sd_doctor['name']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo $sd_doctor['bio']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph -->
<?php if ( '' !== $sd_doctor['link'] ) : ?>
<!-- wp:paragraph {"className":"sd-more","fontSize":"small"} -->
<p class="sd-more has-small-font-size"><a href="<?php echo esc_url( home_url( $sd_doctor['link'] ) ); ?>">Guarda alcuni dei suoi casi &#8594;</a></p>
<!-- /wp:paragraph -->
<?php endif; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
