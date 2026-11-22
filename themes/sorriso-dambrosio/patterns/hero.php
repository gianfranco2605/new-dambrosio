<?php
/**
 * Title: Hero homepage
 * Slug: sorriso-dambrosio/hero
 * Categories: sorriso-dambrosio
 * Description: Fotografia a tutta pagina con velo dalla sinistra, testo bianco allineato a sinistra, scheda contatti bianca in basso e social a destra.
 * Inserter: true
 *
 * @package SorrisoDambrosio
 */

/*
 * Immagine: 2225 "Team Studio D'Ambrosio" (2500x1000, 5:2), i tre dottori
 * sulla destra, finestra e orchidee sulla sinistra.
 *
 * Una sola immagine per tutte le larghezze (niente piu' il secondo file
 * verticale sotto i 782px): e' larga abbastanza (5:2) da restare leggibile
 * anche ritagliata al 3:2 in pila. Ancorata a destra (`object-position`)
 * cosi' il ritaglio — a tutta pagina sopra i 1120px, nel suo 3:2 impilato
 * sotto — toglie sempre dalla finestra a sinistra, mai da un dottore.
 */
$sd_photo_id = 2225;

$sd_photo        = sorriso_dambrosio_image_url( $sd_photo_id, 'full' );
$sd_photo_srcset = wp_get_attachment_image_srcset( $sd_photo_id, 'full' );
// `sizes`: fino a 1120px la foto (2,5:1) riempie un riquadro 7:5 con
// `object-fit: cover`, quindi e' larga 2,5 / 1,4 = 1,79 volte lo schermo.
// Con `100vw` il browser sceglieva un file troppo piccolo e lo ingrandiva
// di 1,7 volte (misurato a 390px): sfocato su telefono e tablet.

$sd_meta   = wp_get_attachment_metadata( $sd_photo_id );
$sd_width  = isset( $sd_meta['width'] ) ? (int) $sd_meta['width'] : 2500;
$sd_height = isset( $sd_meta['height'] ) ? (int) $sd_meta['height'] : 1000;

$sd_alt = get_post_meta( $sd_photo_id, '_wp_attachment_image_alt', true );
$sd_alt = is_string( $sd_alt ) ? $sd_alt : '';

$sd_c = sorriso_dambrosio_contacts();

// Solo profili che esistono gia' sul sito: nessun account inventato.
$sd_social = array(
	array( 'icon' => 'facebook', 'url' => $sd_c['facebook'], 'label' => 'Facebook dello studio' ),
	array( 'icon' => 'instagram', 'url' => $sd_c['instagram'], 'label' => 'Instagram dello studio' ),
	array( 'icon' => 'whatsapp', 'url' => $sd_c['whatsapp'], 'label' => 'Scrivi allo studio su WhatsApp' ),
);
?>
<!-- wp:group {"tagName":"section","className":"sd-hero","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0","right":"0","left":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<section class="wp-block-group sd-hero" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<picture class="sd-hero__media">
	<img src="<?php echo esc_url( $sd_photo ); ?>"<?php echo $sd_photo_srcset ? ' sizes="(max-width: 1120px) 179vw, 100vw" srcset="' . esc_attr( $sd_photo_srcset ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> alt="<?php echo esc_attr( $sd_alt ); ?>" fetchpriority="high" decoding="async" width="<?php echo esc_attr( (string) $sd_width ); ?>" height="<?php echo esc_attr( (string) $sd_height ); ?>">
</picture>
<div class="sd-hero__veil" aria-hidden="true"></div>
<!-- /wp:html -->

<!-- wp:group {"className":"sd-wrap sd-hero__frame","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-hero__frame"><!-- wp:group {"className":"sd-hero__copy","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero__copy"><!-- wp:paragraph {"className":"sd-hero__eyebrow","fontSize":"x-small"} -->
<p class="sd-hero__eyebrow has-x-small-font-size">Dal 1956</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"anchor":"sd-hero-titolo","level":1,"className":"sd-hero__title","fontSize":"xx-large"} -->
<h1 id="sd-hero-titolo" class="wp-block-heading sd-hero__title has-xx-large-font-size">Studio Odontoiatrico D&#8217;Ambrosio</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"sd-hero__lede"} -->
<p class="sd-hero__lede">Gli specialisti del sorriso. Tradizione &#8211; passione &#8211; innovazione per prenderci cura del tuo sorriso.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="sd-hero__tail">
<div class="sd-hero__bar">
	<div class="sd-hero__bar-info">
		<a class="sd-hero__bar-item" href="<?php echo esc_url( $sd_c['email_href'] ); ?>">
			<?php echo sorriso_dambrosio_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="sd-hero__bar-text">
				<span class="sd-hero__bar-label">Email</span>
				<span class="sd-hero__bar-value"><?php echo esc_html( $sd_c['email'] ); ?></span>
			</span>
		</a>
		<a class="sd-hero__bar-item" href="<?php echo esc_url( $sd_c['phone_href'] ); ?>">
			<?php echo sorriso_dambrosio_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="sd-hero__bar-text">
				<span class="sd-hero__bar-label">Telefono</span>
				<span class="sd-hero__bar-value"><?php echo esc_html( $sd_c['phone'] ); ?></span>
			</span>
		</a>
		<a class="sd-hero__bar-item" href="<?php echo esc_url( $sd_c['map_href'] ); ?>" target="_blank" rel="noreferrer noopener">
			<?php echo sorriso_dambrosio_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="sd-hero__bar-text">
				<span class="sd-hero__bar-label">Indirizzo</span>
				<span class="sd-hero__bar-value"><?php echo esc_html( $sd_c['address_short'] ); ?></span>
			</span>
		</a>
	</div>
	<a class="sd-btn sd-btn--solid sd-hero__bar-cta" href="#prenota">Prenota un appuntamento</a>
</div>

<ul class="sd-hero__social">
<?php foreach ( $sd_social as $sd_item ) : ?>
	<li><a href="<?php echo esc_url( $sd_item['url'] ); ?>" target="_blank" rel="noreferrer noopener" aria-label="<?php echo esc_attr( $sd_item['label'] ); ?>"><?php echo sorriso_dambrosio_icon( $sd_item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></li>
<?php endforeach; ?>
</ul>
</div>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
