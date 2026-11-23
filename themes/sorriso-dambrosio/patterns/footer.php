<?php
/**
 * Title: Footer
 * Slug: sorriso-dambrosio/footer
 * Categories: sorriso-dambrosio
 * Description: Pie' di pagina su quattro colonne: logo e dati dello studio, contatti, menu, social.
 * Inserter: false
 *
 * @package SorrisoDambrosio
 */

/*
 * Recapiti dall'unica fonte: `sorriso_dambrosio_contacts()` delega a
 * `SD\Core\Studio::contacts()`, come fanno header, hero, prenota e
 * vieni-a-trovarci. Il footer sta su ogni pagina: se il titolare cambiava un
 * numero dal pannello, qui restava il vecchio.
 *
 * La tabella degli orari non c'e' piu' (23/11, scelta del titolare): il
 * footer era troppo carico. Al suo posto un collegamento alla fascia degli
 * orari di /contatti/, cosi' l'informazione resta a un clic da ogni pagina.
 *
 * Disposizione (23/11, scelta del titolare): quattro colonne. Nella prima
 * il logo con sotto i dati dello studio (ragione sociale, indirizzo,
 * partita IVA); poi Contatti (telefoni, email, orari), Menu (le voci
 * di primo livello del menu) e Social. In fondo iubenda e la firma.
 * Le voci di Navigazione sono quelle di primo livello del menu (ID 4).
 */
$sd_c   = sorriso_dambrosio_contacts();
$sd_nav = array(
	'Lo Studio'   => '/lo-studio/',
	'Trattamenti' => '/trattamenti/',
	'Tecnologie'  => '/tecnologie/',
	'Casi Studi'  => '/casi-studi/',
	'Contatti'    => '/contatti/',
);
?>
<!-- wp:group {"align":"full","className":"sd-footer","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|medium","right":"var:preset|spacing|medium","left":"var:preset|spacing|medium"},"margin":{"top":"0"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group alignfull sd-footer has-base-color has-main-background-color has-text-color has-background has-link-color" style="margin-top:0;padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium)"><!-- wp:columns {"className":"sd-footer__cols"} -->
<div class="wp-block-columns sd-footer__cols"><!-- wp:column {"className":"sd-footer__col"} -->
<div class="wp-block-column sd-footer__col">
<?php echo sorriso_dambrosio_image( 60, 'full', 'logo-footer' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<!-- wp:paragraph {"className":"sd-footer__info"} -->
<p class="sd-footer__info">Studio Odontoiatrico Specialistico Associato D&#8217;Ambrosio<br><a href="<?php echo esc_url( $sd_c['map_href'] ); ?>" target="_blank" rel="noreferrer noopener"><?php echo wp_kses_post( $sd_c['address'] ); ?></a><br>Partita IVA: 05429801003</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"sd-footer__col"} -->
<div class="wp-block-column sd-footer__col">
<!-- wp:heading {"level":2,"className":"sd-footer__title"} -->
<h2 class="wp-block-heading sd-footer__title">Contatti</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tel: <a href="<?php echo esc_url( $sd_c['phone_href'] ); ?>"><?php echo esc_html( $sd_c['phone'] ); ?></a><br>Tel: <a href="<?php echo esc_url( $sd_c['phone2_href'] ); ?>"><?php echo esc_html( $sd_c['phone2'] ); ?></a><br>Cel: <a href="<?php echo esc_url( $sd_c['mobile_href'] ); ?>"><?php echo esc_html( $sd_c['mobile'] ); ?></a><br><a href="<?php echo esc_url( $sd_c['email_href'] ); ?>"><?php echo esc_html( $sd_c['email'] ); ?></a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( home_url( '/contatti/#orari' ) ); ?>">I Nostri Orari</a> &#8594;</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"sd-footer__col"} -->
<div class="wp-block-column sd-footer__col">
<!-- wp:heading {"level":2,"className":"sd-footer__title"} -->
<h2 class="wp-block-heading sd-footer__title">Menu</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"sd-footer__nav"} -->
<ul class="wp-block-list sd-footer__nav"><?php foreach ( $sd_nav as $sd_label => $sd_path ) : ?><!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( $sd_path ) ); ?>"><?php echo esc_html( $sd_label ); ?></a></li>
<!-- /wp:list-item --><?php endforeach; ?></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"sd-footer__col"} -->
<div class="wp-block-column sd-footer__col">
<!-- wp:heading {"level":2,"className":"sd-footer__title"} -->
<h2 class="wp-block-heading sd-footer__title">Social</h2>
<!-- /wp:heading -->

<!-- wp:social-links {"openInNewTab":true,"className":"sd-social","style":{"spacing":{"blockGap":{"top":"16px","left":"16px"}}},"layout":{"type":"flex"}} -->
<ul class="wp-block-social-links sd-social"><!-- wp:social-link {"url":"<?php echo esc_url( $sd_c['whatsapp'] ); ?>","service":"whatsapp"} /-->

<!-- wp:social-link {"url":"https://www.instagram.com/studioodontoiatricodambrosio/","service":"instagram"} /-->

<!-- wp:social-link {"url":"https://www.facebook.com/studioodontoiatricodambrosio/","service":"facebook"} /--></ul>
<!-- /wp:social-links -->
</div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"sd-footer__bottom","layout":{"type":"default"}} -->
<div class="wp-block-group sd-footer__bottom"><!-- wp:html -->
<?php
/*
 * Blocco legale iubenda.
 *
 * Il plugin cerca un pattern chiamato "<stylesheet>/footer" e, se non vi
 * trova la stringa [iub-wp-block-buttons], ci inietta dentro un proprio
 * <div> come primo figlio del secondo <div> del footer: nel nostro caso
 * finirebbe dentro le colonne, rompendo la riga.
 *
 * Il blocco core/shortcode non espande gli shortcode (fa solo wpautop) e le
 * parti template non passano dal filtro the_content: lo shortcode va quindi
 * eseguito qui. Il commento HTML qui sotto mantiene la stringa letterale nel
 * contenuto del pattern, cosi' il plugin riconosce che il blocco legale e'
 * gia' presente e non lo inietta una seconda volta.
 */
?>
<!-- blocco legale iubenda gestito dal tema: [iub-wp-block-buttons] -->
<div class="sd-footer__legal"><?php echo do_shortcode( '[iub-wp-block-buttons]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"sd-footer__credit"} -->
<p class="sd-footer__credit">&copy;Copyright 2026 -&nbsp;<a href="https://elpuasdigitalcrafts.com/" target="_blank" rel="noreferrer noopener">Build by ElPuasDigitalCrafts</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
