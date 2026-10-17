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
 * Il `sizes` e' scritto a mano: quello che WordPress deduce dalla misura
 * dichiarava 768px anche sotto i 1024, dove la fotografia e' a tutta fascia.
 */
$sd_media_id = 654;

/*
 * «I Nostri Pazienti» e' lo stesso capitolo, a specchio: la fotografia 1321
 * (1200x694, una sala operativa con la luce della finestra) sta a destra e
 * il testo a sinistra. E' l'ambiente di cui parla il testo — «elegante ed
 * amichevole, con professionalita', competenza ed igiene» — e nella cornice
 * a destra ci entra ridotta, mai ingrandita.
 *
 * Misura `full` e non `large`: la cornice e' 528x636 e per riempirla senza
 * stirare servono 1100px di sorgente. Con `large` (1024x592) il browser
 * sceglieva quella e la ingrandiva del 7%.
 */
$sd_accoglienza_id = 1321;
?>
<!-- wp:group {"className":"sd-section sd-chapter","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-chapter" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-chapter__grid","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__grid"><!-- wp:group {"className":"sd-chapter__media","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__media">
<?php echo sorriso_dambrosio_image( $sd_media_id, 'large', 'sd-chapter__figure', array( 'sizes' => '(max-width: 1023px) 100vw, 528px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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

<!-- wp:paragraph {"className":"sd-pillars","fontSize":"medium"} -->
<p class="sd-pillars has-medium-font-size">Innovazione, passione e tradizione sono i pilastri che guidano il nostro lavoro quotidiano.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

</div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-chapter__grid sd-chapter__grid--mirror sd-welcome","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__grid sd-chapter__grid--mirror sd-welcome"><!-- wp:group {"className":"sd-chapter__media","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__media">
<?php echo sorriso_dambrosio_image( $sd_accoglienza_id, 'full', 'sd-chapter__figure', array( 'sizes' => '(max-width: 1023px) 100vw, 1100px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-chapter__body","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-chapter__body">
<?php echo sorriso_dambrosio_section_head( 'Accoglienza', 'I Nostri Pazienti' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:group {"className":"sd-prose","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-prose"><!-- wp:paragraph -->
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
<!-- /wp:group --></div>
<!-- /wp:group -->
