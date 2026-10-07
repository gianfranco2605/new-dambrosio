<?php
/**
 * Migrazione 6 — telefono e «Prenota» dentro il menu mobile.
 *
 * Uso:  wp eval-file docs/migration/06-menu-azioni.php
 *
 * Il brief chiede che il pannello del menu contenga anche il telefono e
 * l'invito a prenotare. Si potrebbe iniettarli con JavaScript; si fa invece
 * aggiungendoli come **voci vere del menu**, con una classe che le nasconde
 * nella barra orizzontale e le veste da pulsante dentro il pannello.
 *
 * Costo: zero JavaScript, e restano modificabili dal pannello di WordPress
 * come qualsiasi altra voce.
 *
 * Idempotente: se le due voci ci sono gia', non le duplica.
 *
 * @package SorrisoDambrosio
 */

$nav_id = 4;
$nav    = get_post( $nav_id );

if ( ! $nav instanceof WP_Post ) {
	WP_CLI::error( 'Il post wp_navigation ' . $nav_id . ' non esiste.' );
}

$contenuto = $nav->post_content;

if ( str_contains( $contenuto, 'sd-nav-cta' ) ) {
	WP_CLI::log( 'Le voci di azione ci sono gia\'.' );
	return;
}

$c = function_exists( 'sorriso_dambrosio_contacts' )
	? sorriso_dambrosio_contacts()
	: SD\Core\Studio::contacts();

$voci = array(
	array(
		'label'     => $c['phone'],
		'url'       => $c['phone_href'],
		'className' => 'sd-nav-cta sd-nav-cta--phone',
	),
	array(
		'label'     => 'Prenota un appuntamento',
		'url'       => home_url( '/contatti/#prenota' ),
		'className' => 'sd-nav-cta sd-nav-cta--book',
	),
);

foreach ( $voci as $voce ) {
	$contenuto .= "\n\n" . '<!-- wp:navigation-link ' . wp_json_encode(
		array(
			'label'     => $voce['label'],
			'type'      => 'custom',
			'url'       => $voce['url'],
			'kind'      => 'custom',
			'className' => $voce['className'],
		)
	) . ' /-->';
}

wp_update_post(
	array(
		'ID'           => $nav_id,
		'post_content' => $contenuto,
	)
);

WP_CLI::success( 'Telefono e «Prenota» aggiunti al menu.' );
