<?php
/**
 * Title: Perche noi
 * Slug: sorriso-dambrosio/perche-noi
 * Categories: sorriso-dambrosio
 * Description: Fascia scura: la dichiarazione in grande e quattro motivi, ciascuno con la sua icona, su quattro colonne divise da filetti.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * L'unica fascia scura in mezzo alla pagina. Il paragrafo introduttivo sale
 * a 34px e fa da dichiarazione; i quattro motivi sono sola tipografia.
 * Le quattro icone del vecchio sito (193-196) tornano sopra i titoli (23/11,
 * scelta del titolare), ma nel colore del testo: il PNG blu fa da maschera
 * (`mask-image`) e il colore lo da' `currentColor`, quindi resta un token
 * della palette. 128px naturali mostrati a 48: non si ingrandiscono.
 * Decorative: `aria-hidden`, il titolo dice gia' tutto.
 *
 * Nessun numero progressivo: i quattro motivi non sono una sequenza.
 */
$sd_reasons = array(
	array(
		'icon'  => 193,
		'title' => 'Team odontoiatrico altamente qualificato.',
		'text'  => 'Il nostro team odontoiatrico &egrave; composto da professionisti altamente qualificati e dedicati alla cura della tua salute dentale.',
	),
	array(
		'icon'  => 194,
		'title' => 'Vasta linea di servizi odontoiatrici.',
		'text'  => 'La nostra vasta gamma di servizi odontoiatrici offre soluzioni complete per tutte le tue esigenze dentali.',
	),
	array(
		'icon'  => 195,
		'title' => 'Strutture e attrezzature avanzate.',
		'text'  => 'Le nostre strutture e attrezzature odontoiatriche all&#8217;avanguardia garantiscono trattamenti di alta qualit&agrave; e comfort per i nostri pazienti.',
	),
	array(
		'icon'  => 196,
		'title' => 'Risultati garantiti e sorrisi pi&ugrave; luminosi.',
		'text'  => 'I nostri trattamenti garantiscono risultati sorprendenti, regalando sorrisi pi&ugrave; luminosi e sicuri.',
	),
);
?>
<!-- wp:group {"tagName":"section","className":"sd-section sd-section--dark sd-why","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"type":"default"}} -->
<section class="wp-block-group sd-section sd-section--dark sd-why has-base-color has-main-background-color has-text-color has-background has-link-color" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap"><!-- wp:group {"className":"sd-split","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-split">
<?php echo sorriso_dambrosio_section_head( 'Fiducia', 'Perch&eacute; noi', 2, '', 'sd-perche-noi-titolo' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-split__body","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-split__body"><!-- wp:paragraph {"className":"sd-why__lede","fontSize":"medium"} -->
<p class="sd-why__lede has-medium-font-size">Crediamo nella tua salute dentale e desideriamo offrirti la migliore cura possibile presso lo Studio Odontoiatrico D&#8217;Ambrosio. Il nostro impegno nei tuoi confronti &egrave; chiaro e profondo, siamo qui per prenderci cura di te al meglio.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-why__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-why__grid">
<?php foreach ( $sd_reasons as $sd_reason ) : ?>
<!-- wp:group {"className":"sd-why__cell","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-why__cell"><!-- wp:html -->
<span class="sd-why__icon" style="--sd-icon:url(<?php echo esc_url( (string) wp_get_attachment_url( $sd_reason['icon'] ) ); ?>)" aria-hidden="true"></span>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php echo $sd_reason['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"main-accent","fontSize":"small"} -->
<p class="has-main-accent-color has-text-color has-small-font-size"><?php echo $sd_reason['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
