<?php
/**
 * Title: Tecnologie
 * Slug: sorriso-dambrosio/tecnologie
 * Categories: sorriso-dambrosio
 * Description: Titolo e una sola fotografia che restano fermi a sinistra, le sette tecnologie come righe di un elenco.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Sette card quasi identiche diventano sette righe divise da filetti: si
 * leggono in colpo d'occhio e la pagina smette di sembrare un catalogo.
 * A sinistra restano fermi il titolo e una sola fotografia (820, 2560x1707).
 *
 * I testi sono estratti troncati con "..." gia' prima del redesign: restano
 * esattamente quelli, e ogni riga rimanda alla pagina dove sono completi.
 */
$sd_aside_id = 820;

$sd_tech = array(
	array(
		'title' => 'Tac 3D Cone-Beam',
		'text'  => 'Il nostro studio &egrave; dotato delle tecnologie radiologiche pi&ugrave; avanzate, che ci permettono di ridurre al minimo l&#8217;esposizi&#8230;',
		'link'  => '/tecnologie/#tc-cone-beam',
	),
	array(
		'title' => 'Rx Digitale Intraorale',
		'text'  => 'La radiografia endorale, o intraorale, &egrave; un esame diagnostico mirato ai singoli elementi dentali, che va da&#8230;',
		'link'  => '/tecnologie/#rx-intraorale',
	),
	array(
		'title' => 'Scanner Intraorale',
		'text'  => 'Quando si tratta di ottenere un&#8217; impronta perfetta, la precisione &egrave; fondamentale. Attraverso un sistema video avanzato, l&#8217;apparecchio&#8230;',
		'link'  => '/tecnologie/#scanner-intraorale',
	),
	array(
		'title' => 'Rx Ortopantomografo',
		'text'  => 'L&#8217;ortopantomografia &egrave; un esame radiografico utilizzato per identificare diverse condizioni, tra cui malformazioni&#8230;',
		'link'  => '/tecnologie/#ortopantomografo',
	),
	array(
		'title' => 'Laser a Diodo',
		'text'  => 'Il Laser a Diodo rappresenta oggi un supporto clinico di eccellenza in odontoiatria. Pu&ograve; essere impiegato con successo nel trattamento di patologie&#8230;',
		'link'  => '/trattamenti/#laser-terapia',
	),
	array(
		'title' => 'Chirurgia Guidata',
		'text'  => 'Attraverso l&#8217;utilizzo di software innovativi, l&#8217;approccio chirurgico si caratterizza per una precisione al decimo di millimetro&#8230;',
		'link'  => '/tecnologie/#chirurgia-guidata',
	),
	array(
		'title' => 'Igiene e Profilassi Avanzata',
		'text'  => 'Il nuovo Varios Combi Pro di NSK &egrave; un sistema combinato all&#8217;avanguardia per l&#8217;igiene dentale professionale. Questo apparecchio integra&#8230;',
		'link'  => '/estetica-dentale/#igieneprofesionale',
	),
);
?>
<!-- wp:group {"className":"sd-section sd-tech","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-tech" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap sd-tech__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-tech__grid"><!-- wp:group {"className":"sd-tech__aside","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-tech__aside">
<?php echo sorriso_dambrosio_section_head( 'Tendenze', 'Tecnologie', 2, home_url( '/tecnologie/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<?php echo sorriso_dambrosio_image( $sd_aside_id, 'large', 'sd-tech__figure' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-tech__list","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-tech__list">
<?php foreach ( $sd_tech as $sd_item ) : ?>
<!-- wp:group {"className":"sd-tech__row","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-tech__row"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><a href="<?php echo esc_url( home_url( $sd_item['link'] ) ); ?>"><?php echo $sd_item['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small","textColor":"secondary"} -->
<p class="has-secondary-color has-text-color has-small-font-size"><?php echo $sd_item['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"sd-more","fontSize":"x-small"} -->
<p class="sd-more has-x-small-font-size"><a href="<?php echo esc_url( home_url( $sd_item['link'] ) ); ?>">CONTINUA &#8594;</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
