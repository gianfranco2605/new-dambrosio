<?php
/**
 * Title: Contatti — il modulo
 * Slug: sorriso-dambrosio/contatti-modulo
 * Categories: sorriso-dambrosio
 * Description: Il modulo di richiesta appuntamento a tutta colonna, senza i recapiti accanto.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * E' il gemello del pattern `prenota` usato in homepage, meno la colonna dei
 * recapiti: su /contatti/ i recapiti stanno gia' in cima alla pagina, e
 * ripeterli accanto al modulo li farebbe comparire tre volte nella stessa
 * schermata. Il modulo e' lo stesso, stesso shortcode, stesso post 2063.
 *
 * `core/shortcode` non esegue gli shortcode e i pattern non passano dal filtro
 * `the_content`: serve `do_shortcode()`. I <p> e <br> automatici di CF7 sono
 * spenti in functions.php con `wpcf7_autop_or_not`, altrimenti spezzano la
 * griglia del modulo.
 */
$sd_form = do_shortcode( '[contact-form-7 id="17f113f" title="Modulo contatto" html_class="sd-form-wrapper"]' );
?>
<!-- wp:group {"className":"sd-section sd-prenota sd-prenota--wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-section sd-prenota sd-prenota--wide" id="prenota" style="margin-top:0;margin-bottom:0"><!-- wp:group {"className":"sd-wrap","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap">
<?php echo sorriso_dambrosio_section_head( 'Appuntamenti', 'Prenota un appuntamento' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:paragraph {"className":"sd-prenota__lede","fontSize":"large"} -->
<p class="sd-prenota__lede has-large-font-size">I nostri pazienti vengono accolti in un ambiente elegante ed amichevole, con professionalit&agrave;, competenza ed igiene.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<?php echo $sd_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
