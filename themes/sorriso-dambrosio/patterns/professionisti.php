<?php
/**
 * Title: I Professionisti
 * Slug: sorriso-dambrosio/professionisti
 * Categories: sorriso-dambrosio
 * Description: I tre medici dello studio, ritratto e biografia su tre colonne.
 * Inserter: true
 *
 * @package SorrisoDambrosio
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
<!-- wp:group {"align":"wide","className":"sd-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignwide sd-section" id="chi-siamo" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xx-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium)">
<?php echo sorriso_dambrosio_section_head( 'Dottori', 'I Professionisti' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|large","left":"var:preset|spacing|large"},"margin":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top" style="margin-top:0;margin-bottom:0">
<?php foreach ( $sd_doctors as $sd_doctor ) : ?>
<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
<div class="wp-block-column is-vertically-aligned-top" id="<?php echo esc_attr( $sd_doctor['anchor'] ); ?>">
<?php echo sorriso_dambrosio_image( $sd_doctor['image'], 'large', 'sd-portrait' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:heading {"level":3,"fontSize":"base"} -->
<h3 class="wp-block-heading has-base-font-size"><?php echo $sd_doctor['name']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small","textColor":"secondary"} -->
<p class="has-secondary-color has-text-color has-small-font-size"><?php echo $sd_doctor['bio']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph -->
<?php if ( '' !== $sd_doctor['link'] ) : ?>
<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><a href="<?php echo esc_url( home_url( $sd_doctor['link'] ) ); ?>"><strong>Guarda alcuni dei suoi casi &#8594;</strong></a></p>
<!-- /wp:paragraph -->
<?php endif; ?>
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
