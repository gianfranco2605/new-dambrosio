<?php
/**
 * Title: Tecnologie
 * Slug: sorriso-dambrosio/tecnologie
 * Categories: sorriso-dambrosio
 * Description: Griglia delle tecnologie dello studio, ciascuna con immagine e approfondimento.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

$sd_tech = array(
	array(
		'image' => 1301,
		'title' => 'Tac 3D Cone-Beam',
		'text'  => 'Il nostro studio &egrave; dotato delle tecnologie radiologiche pi&ugrave; avanzate, che ci permettono di ridurre al minimo l&#8217;esposizi&#8230;',
		'link'  => '/tecnologie/#tc-cone-beam',
	),
	array(
		'image' => 167,
		'title' => 'Rx Digitale Intraorale',
		'text'  => 'La radiografia endorale, o intraorale, &egrave; un esame diagnostico mirato ai singoli elementi dentali, che va da&#8230;',
		'link'  => '/tecnologie/#rx-intraorale',
	),
	array(
		'image' => 1062,
		'title' => 'Scanner Intraorale',
		'text'  => 'Quando si tratta di ottenere un&#8217; impronta perfetta, la precisione &egrave; fondamentale. Attraverso un sistema video avanzato, l&#8217;apparecchio&#8230;',
		'link'  => '/tecnologie/#scanner-intraorale',
	),
	array(
		'image' => 769,
		'title' => 'Rx Ortopantomografo',
		'text'  => 'L&#8217;ortopantomografia &egrave; un esame radiografico utilizzato per identificare diverse condizioni, tra cui malformazioni&#8230;',
		'link'  => '/tecnologie/#ortopantomografo',
	),
	array(
		'image' => 1059,
		'title' => 'Laser a Diodo',
		'text'  => 'Il Laser a Diodo rappresenta oggi un supporto clinico di eccellenza in odontoiatria. Pu&ograve; essere impiegato con successo nel trattamento di patologie&#8230;',
		'link'  => '/trattamenti/#laser-terapia',
	),
	array(
		'image' => 759,
		'title' => 'Chirurgia Guidata',
		'text'  => 'Attraverso l&#8217;utilizzo di software innovativi, l&#8217;approccio chirurgico si caratterizza per una precisione al decimo di millimetro&#8230;',
		'link'  => '/tecnologie/#chirurgia-guidata',
	),
	array(
		'image' => 1855,
		'title' => 'Igiene e Profilassi Avanzata',
		'text'  => 'Il nuovo Varios Combi Pro di NSK &egrave; un sistema combinato all&#8217;avanguardia per l&#8217;igiene dentale professionale. Questo apparecchio integra&#8230;',
		'link'  => '/estetica-dentale/#igieneprofesionale',
	),
);
?>
<!-- wp:group {"align":"wide","className":"sd-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignwide sd-section" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xx-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium)">
<?php echo sorriso_dambrosio_section_head( 'Tendenze', 'Tecnologie' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-grid sd-grid--tech","layout":{"type":"default"}} -->
<div class="wp-block-group sd-grid sd-grid--tech">
<?php foreach ( $sd_tech as $sd_item ) : ?>
<!-- wp:group {"className":"sd-card","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group sd-card has-base-background-color has-background" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<?php echo sorriso_dambrosio_image( $sd_item['image'], 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-card__body","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","right":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group sd-card__body" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium)"><!-- wp:heading {"level":3,"fontSize":"base"} -->
<h3 class="wp-block-heading has-base-font-size"><?php echo $sd_item['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small","textColor":"secondary"} -->
<p class="has-secondary-color has-text-color has-small-font-size"><?php echo $sd_item['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( $sd_item['link'] ) ); ?>">CONTINUA &#8594;</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
