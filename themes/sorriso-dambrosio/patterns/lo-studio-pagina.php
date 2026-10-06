<?php
/**
 * Title: Lo Studio — la pagina
 * Slug: sorriso-dambrosio/lo-studio-pagina
 * Categories: sorriso-dambrosio
 * Description: La storia dello studio: ritratto del bancone che esce dal bordo sinistro, testo in colonna di lettura, tre pilastri in chiusura.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * NOTA: la griglia `.sd-chapter__grid` e' a tutta larghezza — porta le sue
 * colonne di margine — quindi **non** va avvolta in `.sd-wrap`, altrimenti
 * la fotografia non arriva al bordo della finestra.
 *
 * Il testo e' quello della pagina 48 (`/lo-studio/`), parola per parola.
 * Cambia soltanto come e' impaginato: i due titoli decorativi del vecchio
 * impianto — «Storia / Dal 1956» e «Storia / Ad oggi» — diventano gli
 * occhielli delle due parti in cui il racconto si divide, che e' il lavoro
 * che facevano gia'.
 *
 * In homepage lo stesso capitolo compare accorciato, e il suo titolo rimanda
 * qui: e' la regola 2 di CLAUDE.md (si accorcia solo rimandando al testo
 * completo). Qui il testo e' integrale, compreso il paragrafo «A distanza di
 * quasi 70 anni...» che in homepage non c'e'.
 *
 * L'immagine 645 e' quella che la pagina aveva gia'; la 654 (il bancone con
 * «Dal 1956») e' la stessa usata dal capitolo in homepage.
 */
$sd_c = sorriso_dambrosio_contacts();
?>
<!-- wp:group {"className":"sd-section sd-chapter","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-chapter" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-chapter__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__grid"><!-- wp:group {"className":"sd-chapter__media","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__media">
<?php echo sorriso_dambrosio_image( 654, 'large', 'sd-chapter__figure' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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

<!-- wp:group {"className":"sd-head sd-head--minor","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-head sd-head--minor"><!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->
<p class="sd-eyebrow has-x-small-font-size">Dal 1956</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"fontSize":"medium"} -->
<h2 class="wp-block-heading has-medium-font-size">Storia</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-prose","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-prose"><!-- wp:paragraph -->
<p>Lo Studio Dentistico D&#8217;Ambrosio &egrave; stato fondato nel 1956 dal Dottor Venanzio D&#8217;Ambrosio, capace professionista, estroso e poliedrico (&egrave; stato anche pilota di aerei, fotografo e pittore), che con dedizione e passione ha saputo coniugare l&#8217;arte della medicina con l&#8217;attenzione al dettaglio.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Fin dai suoi esordi, lo studio si &egrave; distinto per l&#8217;attenzione al paziente, la professionalit&agrave; e un approccio innovativo nel campo delle cure dentistiche.Inizialmente situato in Via Flaminia, lo studio si &egrave; poi trasferito nella sede attuale di Lungotevere Flaminio.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-head sd-head--minor","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-head sd-head--minor"><!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->
<p class="sd-eyebrow has-x-small-font-size">Ad oggi</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"fontSize":"medium"} -->
<h2 class="wp-block-heading has-medium-font-size">Storia</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-prose","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-prose"><!-- wp:paragraph -->
<p>A distanza di quasi 70 anni, lo studio continua a mantenere viva quella stessa passione, con l&#8217;apporto e la competenza dei figli Nicoletta, Stefania e Giuseppe.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ognuno di loro, con un&#8217;esperienza consolidata e un continuo impegno nel miglioramento professionale, porta avanti la tradizione di eccellenza e cura che caratterizza lo Studio Dentistico D&#8217;Ambrosio.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"sd-chapter__pillars","fontSize":"large"} -->
<p class="sd-chapter__pillars has-large-font-size">Innovazione, Passione e Tradizione sono i pilastri che guidano il nostro lavoro quotidiano.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"sd-more","fontSize":"x-small"} -->
<p class="sd-more has-x-small-font-size"><a href="<?php echo esc_url( $sd_c['map_href'] ); ?>" rel="noopener">VIENI A TROVARCI &#8212; <?php echo esc_html( $sd_c['address_short'] ); ?> &rarr;</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
