<?php
/**
 * Title: Header
 * Slug: sorriso-dambrosio/header
 * Categories: sorriso-dambrosio
 * Description: Due piani: in alto a destra il telefono e l'invito a prenotare, sotto il logo a sinistra e la navigazione.
 * Inserter: false
 *
 * @package SorrisoDambrosio
 */

$sd_c = sorriso_dambrosio_contacts();

/*
 * In homepage il modulo e' in pagina, quindi il pulsante punta all'ancora.
 * Altrove non esiste nessun #prenota: puntava nel vuoto e non succedeva
 * niente. Sulle altre pagine va alla pagina dei contatti.
 */
$sd_prenota = is_front_page() ? '#prenota' : home_url( '/contatti/#prenota' );

/*
 * Due piani invece di una riga sola. Prima logo, navigazione e i due
 * pulsanti si dividevano la stessa riga: a 1340px «Prenota» doveva
 * sparire per far stare le sette voci, e il logo non poteva crescere.
 * Separandoli, la riga della navigazione ha tutta la larghezza per se'
 * e il logo puo' tornare leggibile.
 */
?>
<!-- wp:group {"className":"sd-header","style":{"position":{"type":"sticky","top":"0px"},"spacing":{"padding":{"top":"0","bottom":"0","right":"0","left":"0"},"blockGap":"0"}},"backgroundColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group sd-header has-base-background-color has-background" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"className":"sd-header__top","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-header__top"><!-- wp:html -->
<div class="sd-wrap sd-header__actions">
	<a class="sd-btn sd-btn--solid sd-header__phone" href="<?php echo esc_url( $sd_c['phone_href'] ); ?>" aria-label="Chiama lo studio al <?php echo esc_attr( $sd_c['phone'] ); ?>">
		<?php echo sorriso_dambrosio_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="sd-header__phone-text"><?php echo esc_html( $sd_c['phone'] ); ?></span>
	</a>
	<a class="sd-btn sd-btn--outline sd-header__book" href="<?php echo esc_url( $sd_prenota ); ?>">Prenota un appuntamento</a>
</div>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"sd-wrap sd-header__inner","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-wrap sd-header__inner"><!-- wp:site-logo {"width":220,"className":"logo-menu sd-header__logo"} /-->

<!-- wp:navigation {"ref":4,"overlayMenu":"mobile","className":"sd-header__nav","textColor":"main","overlayTextColor":"main","icon":"menu","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"fontSize":"x-small","layout":{"type":"flex","orientation":"horizontal","justifyContent":"right","flexWrap":"nowrap"}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
