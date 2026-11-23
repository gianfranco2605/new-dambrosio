<?php
/**
 * Title: Lo Studio — la pagina
 * Slug: sorriso-dambrosio/lo-studio-pagina
 * Categories: sorriso-dambrosio
 * Description: La storia dello studio: ritratto del bancone che esce dal bordo sinistro, le tappe, il racconto in due tempi con le loro fotografie, tre pilastri in chiusura.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * NOTA: la griglia `.sd-chapter__grid` e' a tutta larghezza — porta le sue
 * colonne di margine — quindi **non** va avvolta in `.sd-wrap`, altrimenti
 * la fotografia non arriva al bordo della finestra.
 *
 * Il testo e' quello di www.sorrisodambrosio.it/lo-studio/, parola per
 * parola (confrontato il 20/11/2026). Cambia soltanto come e' impaginato:
 * i due titoli decorativi del vecchio impianto — «Storia / Dal 1956» e
 * «Storia / Ad oggi» — diventano gli occhielli delle due parti in cui il
 * racconto si divide, che e' il lavoro che facevano gia'.
 *
 * «Le tappe» non aggiunge nulla: tre date e tre fatti presi dal racconto
 * che segue, messi in fila perche' la storia si legga prima di leggerla.
 *
 * In homepage lo stesso capitolo compare accorciato, e il suo titolo rimanda
 * qui: e' la regola 2 di CLAUDE.md (si accorcia solo rimandando al testo
 * completo). Qui il testo e' integrale, compreso il paragrafo «A distanza di
 * quasi 70 anni...» che in homepage non c'e'.
 *
 * La 654 (il bancone con «Dal 1956») e' la stessa usata dal capitolo in
 * homepage. Sotto ciascuna delle due parti, le sue quattro fotografie: le
 * stesse otto che la pagina originale aveva sotto «Dal 1956» e «Ad oggi»,
 * senza gli anni nelle didascalie. Misura: la piu' grande registrata,
 * `1536x1536`; la 771 (la targa) non ce l'ha e si serve intera, 1459px.
 * La lightbox di core apre l'originale.
 */
$sd_c = sorriso_dambrosio_contacts();

$sd_strip = static function ( $caption, $shots ) {
	$out = '<!-- wp:group {"className":"sd-strip","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->'
		. '<div class="wp-block-group sd-strip">'
		. '<!-- wp:paragraph {"className":"sd-strip__caption","fontSize":"x-small"} -->'
		. '<p class="sd-strip__caption has-x-small-font-size">' . $caption . '</p>'
		. '<!-- /wp:paragraph -->'
		. '<!-- wp:group {"className":"sd-strip__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->'
		. '<div class="wp-block-group sd-strip__grid">';

	foreach ( $shots as $shot ) {
		list( $id, $size, $alt ) = $shot;

		$out .= '<!-- wp:image {"lightbox":{"enabled":true},"id":' . (int) $id . ',"sizeSlug":"' . esc_attr( $size ) . '","linkDestination":"none","className":"sd-strip__shot"} -->'
			. '<figure class="wp-block-image size-' . esc_attr( $size ) . ' sd-strip__shot"><img src="' . esc_url( sorriso_dambrosio_image_url( $id, $size ) ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . (int) $id . '"/></figure>'
			. '<!-- /wp:image -->';
	}

	return $out . '</div><!-- /wp:group --></div><!-- /wp:group -->';
};
?>
<!-- wp:group {"className":"sd-section sd-chapter","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-chapter" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-chapter__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__grid"><!-- wp:group {"className":"sd-chapter__media","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__media">
<?php echo sorriso_dambrosio_image( 654, '1536x1536', 'sd-chapter__figure', array( 'loading' => 'eager', 'sizes' => '(max-width: 1023px) 100vw, 800px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-chapter__body","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__body"><!-- wp:group {"className":"sd-head","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-head"><!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->
<p class="sd-eyebrow has-x-small-font-size">Sorriso D&#8217;Ambrosio</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Il Nostro Studio</h1>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"sd-chapter__lede","fontSize":"large"} -->
<p class="sd-chapter__lede has-large-font-size">Pilastro dell&#8217;odontoiatria a Roma da oltre 68 anni, eccellenza odontoiatrica, il vostro sorriso, la nostra passione.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"sd-tappe","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-tappe"><!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->
<p class="sd-eyebrow has-x-small-font-size">Le tappe</p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true,"className":"sd-tappe__list"} -->
<ol class="wp-block-list sd-tappe__list"><!-- wp:list-item -->
<li><span class="sd-tappe__year">1956</span> <span class="sd-tappe__text">Fondato dal Dottor Venanzio D&#8217;Ambrosio, a Via del Tritone.</span></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><span class="sd-tappe__year">1978</span> <span class="sd-tappe__text">Il trasferimento a Lungotevere Flaminio 34.</span></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><span class="sd-tappe__year">Oggi</span> <span class="sd-tappe__text">Con i figli Nicoletta, Stefania e Giuseppe.</span></li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-head sd-head--minor","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-head sd-head--minor"><!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->
<p class="sd-eyebrow has-x-small-font-size">Storia</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"fontSize":"medium"} -->
<h2 class="wp-block-heading has-medium-font-size">Dal 1956</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-prose","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-prose"><!-- wp:paragraph -->
<p>Lo Studio Dentistico D&#8217;Ambrosio &egrave; stato fondato nel 1956 dal Dottor Venanzio D&#8217;Ambrosio, capace professionista, estroso e poliedrico (&egrave; stato anche pittore e scultore).</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Fin dai suoi esordi, lo studio si &egrave; distinto per l&#8217;attenzione al paziente, la professionalit&agrave; e un approccio innovativo nel campo delle cure dentistiche. Inizialmente situato nel cuore di Roma, a Via del Tritone, si &egrave; trasferito nel 1978 a Lungotevere Flaminio 34, in una struttura di design con innovazioni tecnologiche in quel tempo &#8220;futuristiche&#8221;.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $sd_strip(
	'Lo studio com&#8217;era',
	array(
		array( 1315, '1536x1536', 'Il corridoio dello studio con la targa &laquo;dr. venanzio&raquo;, anni Settanta' ),
		array( 1316, '1536x1536', 'La reception dello studio negli anni Settanta' ),
		array( 1317, '1536x1536', 'La console di comando dello studio negli anni Settanta' ),
		array( 1318, '1536x1536', 'Lo studio visto da un obl&ograve;, anni Settanta' ),
	)
);
?>

<!-- wp:group {"className":"sd-head sd-head--minor","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-head sd-head--minor"><!-- wp:heading {"level":2,"fontSize":"medium"} -->
<h2 class="wp-block-heading has-medium-font-size">Ad oggi</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-prose","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-prose"><!-- wp:paragraph -->
<p>A distanza di quasi 70 anni, lo studio continua a mantenere viva quella stessa passione, con l&#8217;apporto e la competenza dei figli Nicoletta, Stefania e Giuseppe.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ognuno di loro, con un&#8217;esperienza consolidata e un continuo impegno nel miglioramento professionale, porta avanti la tradizione di eccellenza e cura che ha sempre contraddistinto lo studio.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $sd_strip(
	'Lo studio oggi',
	array(
		array( 655, '1536x1536', 'La porta di vetro dello studio con la targa &laquo;dr. venanzio&raquo;' ),
		array( 654, '1536x1536', 'La reception di oggi con il bancone blu' ),
		array( 1496, '1536x1536', 'Una sala di cura di oggi' ),
		array( 771, 'full', 'La targa &laquo;68 anni di attivit&agrave;, 1956/2024&raquo;' ),
	)
);
?>

<!-- wp:paragraph {"className":"sd-chapter__pillars","fontSize":"medium"} -->
<p class="sd-chapter__pillars has-medium-font-size">Innovazione, Passione e Tradizione sono i pilastri che guidano il nostro lavoro quotidiano.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"sd-more","fontSize":"x-small"} -->
<p class="sd-more has-x-small-font-size"><a href="<?php echo esc_url( $sd_c['map_href'] ); ?>" rel="noopener">VIENI A TROVARCI &#8212; <?php echo esc_html( $sd_c['address_short'] ); ?> &rarr;</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
