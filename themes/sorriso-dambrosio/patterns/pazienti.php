<?php
/**
 * Title: I Nostri Pazienti
 * Slug: sorriso-dambrosio/pazienti
 * Categories: sorriso-dambrosio
 * Description: Approccio ai pazienti, testo su due colonne asimmetriche con fotografia.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

?>
<!-- wp:group {"align":"wide","className":"sd-section sd-section--tinted","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignwide sd-section sd-section--tinted" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xx-large);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium)">
<?php echo sorriso_dambrosio_section_head( 'Dal 1956', 'I Nostri Pazienti' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|large","left":"var:preset|spacing|x-large"},"margin":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top" style="margin-top:0;margin-bottom:0"><!-- wp:column {"verticalAlignment":"top","width":"58%","className":"sd-prose","style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
<div class="wp-block-column is-vertically-aligned-top sd-prose" style="flex-basis:58%"><!-- wp:paragraph -->
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
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"42%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:42%">
<?php echo sorriso_dambrosio_image( 1653, 'large', 'sd-portrait' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
