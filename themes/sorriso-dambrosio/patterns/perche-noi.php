<?php
/**
 * Title: Perche noi
 * Slug: sorriso-dambrosio/perche-noi
 * Categories: sorriso-dambrosio
 * Description: Fascia scura di sola tipografia: la dichiarazione in grande e quattro motivi su quattro colonne divise da filetti.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * L'unica fascia scura in mezzo alla pagina. Il paragrafo introduttivo sale
 * a 34px e fa da dichiarazione; i quattro motivi sono sola tipografia.
 * Le quattro icone (193-196) spariscono: erano clipart blu da 44px che non
 * dicevano niente che il titolo non dicesse gia'.
 *
 * Nessun numero progressivo: i quattro motivi non sono una sequenza.
 */
$sd_reasons = array(
	array(
		'title' => 'Team odontoiatrico altamente qualificato.',
		'text'  => 'Il nostro team odontoiatrico &egrave; composto da professionisti altamente qualificati e dedicati alla cura della tua salute dentale.',
	),
	array(
		'title' => 'Vasta linea di servizi odontoiatrici.',
		'text'  => 'La nostra vasta gamma di servizi odontoiatrici offre soluzioni complete per tutte le tue esigenze dentali.',
	),
	array(
		'title' => 'Strutture e attrezzature avanzate.',
		'text'  => 'Le nostre strutture e attrezzature odontoiatriche all&#8217;avanguardia garantiscono trattamenti di alta qualit&agrave; e comfort per i nostri pazienti.',
	),
	array(
		'title' => 'Risultati garantiti e sorrisi pi&ugrave; luminosi.',
		'text'  => 'I nostri trattamenti garantiscono risultati sorprendenti, regalando sorrisi pi&ugrave; luminosi e sicuri.',
	),
);
?>
<!-- wp:group {"className":"sd-section sd-section--dark sd-why","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-section--dark sd-why has-base-color has-main-background-color has-text-color has-background has-link-color" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap"><!-- wp:group {"className":"sd-split","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-split">
<?php echo sorriso_dambrosio_section_head( 'Fiducia', 'Perch&eacute; noi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

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
<div class="wp-block-group sd-why__cell"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php echo $sd_reason['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"main-accent","fontSize":"small"} -->
<p class="has-main-accent-color has-text-color has-small-font-size"><?php echo $sd_reason['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
