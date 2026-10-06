<?php
/**
 * Title: Lo Studio e I Nostri Pazienti
 * Slug: sorriso-dambrosio/lo-studio
 * Categories: sorriso-dambrosio
 * Description: Un capitolo solo: la storia dal 1956 e l'accoglienza, accanto a una fotografia che resta ferma sul bordo sinistro.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Due sezioni del vecchio sito diventano un capitolo solo. Il testo e'
 * integrale e letterale: cambia il raggruppamento, non le parole.
 * L'immagine 654 (1920x2560, il bancone con il marchio e "Dal 1956") esce
 * dal bordo sinistro della finestra e resta ferma mentre scorre il testo.
 */
$sd_media_id = 654;
?>
<!-- wp:group {"className":"sd-section sd-chapter","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-chapter" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-chapter__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__grid"><!-- wp:group {"className":"sd-chapter__media","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__media">
<?php echo sorriso_dambrosio_image( $sd_media_id, 'large', 'sd-chapter__figure' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-chapter__body","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__body">
<?php echo sorriso_dambrosio_section_head( 'Dal 1956', 'Lo Studio', 2, home_url( '/lo-studio/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-prose","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-prose"><!-- wp:paragraph -->
<p>Lo Studio Dentistico D&#8217;Ambrosio &egrave; stato fondato nel 1956 dal Dottor Venanzio D&#8217;Ambrosio, capace professionista, estroso e poliedrico (&egrave; stato anche pittore e scultore).</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Fin dai suoi esordi, lo studio si &egrave; distinto per l&#8217;attenzione al paziente, la professionalit&agrave; e un approccio innovativo nel campo delle cure dentistiche.Inizialmente situato nel cuore di Roma, a Via del Tritone, si &egrave; trasferito nel 1978 a <a href="https://maps.app.goo.gl/jsVVDPiXFAFLCiE18" target="_blank" rel="noreferrer noopener"><strong>Lungotevere Flaminio 34</strong></a>, in una struttura di design con innovazioni tecnologiche in quel tempo &#8220;futuristiche&#8221;.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ognuno di loro, con un&#8217;esperienza consolidata e un continuo impegno nel miglioramento professionale, porta avanti la tradizione di eccellenza e cura che ha sempre contraddistinto lo studio.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"sd-pillars","fontSize":"large"} -->
<p class="sd-pillars has-large-font-size"><strong>Innovazione, Passione e Tradizione</strong>&nbsp;sono i pilastri che guidano il nostro lavoro quotidiano.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

</div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-wrap sd-welcome","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-welcome">
<?php echo sorriso_dambrosio_section_head( 'Accoglienza', 'I Nostri Pazienti' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-columns3","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-columns3"><!-- wp:paragraph -->
<p>I nostri pazienti vengono accolti in un ambiente elegante ed amichevole, con professionalit&agrave;, competenza ed igiene.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Non dimentichiamo mai l&#8217;importanza della relazione personale e della fiducia che ci lega ai nostri pazienti, rispettando il valore dell&#8217;accoglienza e dell&#8217; onest&agrave;.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>L&#8217;approccio alle terapie &egrave; sempre individuale e personalizzato, con una forte attenzione alle esigenze di ciascuno.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Il nostro obiettivo non &egrave; solo risolvere i problemi dentali, ma anche la prevenzione e l&#8217;educazione dei pazienti a una corretta igiene orale, per mantenerli in salute nel lungo periodo e per il mantenimento nel tempo delle terapie effettuate.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Lo Studio Dentistico D&#8217;Ambrosio &egrave; oggi un punto di riferimento per chi cerca professionalit&agrave;, competenza e un ambiente amichevole, dove ogni paziente &egrave; seguito con attenzione e responsabilit&agrave;.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
