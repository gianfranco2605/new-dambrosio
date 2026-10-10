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
 * Immagine: 636 AGA_5209 (2500x1685), lo studio vero con la luce della finestra.
 * La composizione — il dottore a sinistra, la famiglia a destra — e' quella che
 * serve a questo impianto: il velo scurisce la sinistra e lascia in chiaro il
 * soggetto a destra, come nel riferimento.
 * Sotto i 782px passa l'immagine verticale 1492.
 *
 * Il testo e' quello gia' pubblicato: "Dal 1956", il nome dello studio,
 * "Gli specialisti del sorriso" e il claim. Le combinazioni alternative sono
 * elencate in docs/DA-VERIFICARE.md perche' le scelga il titolare.
 */
$sd_desktop_id = 636;
$sd_mobile_id  = 1492;

$sd_desktop        = sorriso_dambrosio_image_url( $sd_desktop_id, 'full' );
$sd_mobile         = sorriso_dambrosio_image_url( $sd_mobile_id, 'full' );
$sd_desktop_srcset = wp_get_attachment_image_srcset( $sd_desktop_id, 'full' );
$sd_mobile_srcset  = wp_get_attachment_image_srcset( $sd_mobile_id, 'full' );

$sd_meta   = wp_get_attachment_metadata( $sd_desktop_id );
$sd_width  = isset( $sd_meta['width'] ) ? (int) $sd_meta['width'] : 2500;
$sd_height = isset( $sd_meta['height'] ) ? (int) $sd_meta['height'] : 1685;

$sd_alt = get_post_meta( $sd_desktop_id, '_wp_attachment_image_alt', true );
$sd_alt = is_string( $sd_alt ) ? $sd_alt : '';

$sd_c = sorriso_dambrosio_contacts();

// Solo profili che esistono gia' sul sito: nessun account inventato.
$sd_social = array(
	array( 'icon' => 'facebook', 'url' => $sd_c['facebook'], 'label' => 'Facebook dello studio' ),
	array( 'icon' => 'instagram', 'url' => $sd_c['instagram'], 'label' => 'Instagram dello studio' ),
	array( 'icon' => 'whatsapp', 'url' => $sd_c['whatsapp'], 'label' => 'Scrivi allo studio su WhatsApp' ),
);
?>
<!-- wp:group {"className":"sd-hero","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0","right":"0","left":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<picture class="sd-hero__media">
	<source media="(max-width: 781px)" sizes="150vw" srcset="<?php echo $sd_mobile_srcset ? esc_attr( $sd_mobile_srcset ) : esc_url( $sd_mobile ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<img src="<?php echo esc_url( $sd_desktop ); ?>"<?php echo $sd_desktop_srcset ? ' sizes="100vw" srcset="' . esc_attr( $sd_desktop_srcset ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> alt="<?php echo esc_attr( $sd_alt ); ?>" fetchpriority="high" decoding="async" width="<?php echo esc_attr( (string) $sd_width ); ?>" height="<?php echo esc_attr( (string) $sd_height ); ?>">
</picture>
<div class="sd-hero__veil" aria-hidden="true"></div>
<!-- /wp:html -->

<!-- wp:group {"className":"sd-wrap sd-hero__frame","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-hero__frame"><!-- wp:group {"className":"sd-hero__copy","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-hero__copy"><!-- wp:paragraph {"className":"sd-hero__eyebrow","fontSize":"x-small"} -->
<p class="sd-hero__eyebrow has-x-small-font-size">Dal 1956</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"sd-hero__title","fontSize":"xx-large"} -->
<h1 class="wp-block-heading sd-hero__title has-xx-large-font-size">Studio Odontoiatrico D&#8217;Ambrosio</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"sd-hero__lede"} -->
<p class="sd-hero__lede">Gli specialisti del sorriso. Tradizione &#8211; passione &#8211; innovazione per prenderci cura del tuo sorriso.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="sd-hero__bar">
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
	<a class="sd-btn sd-btn--solid sd-hero__bar-cta" href="#prenota">Prenota un appuntamento</a>
</div>

<ul class="sd-hero__social">
<?php foreach ( $sd_social as $sd_item ) : ?>
	<li><a href="<?php echo esc_url( $sd_item['url'] ); ?>" target="_blank" rel="noreferrer noopener" aria-label="<?php echo esc_attr( $sd_item['label'] ); ?>"><?php echo sorriso_dambrosio_icon( $sd_item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></li>
<?php endforeach; ?>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
