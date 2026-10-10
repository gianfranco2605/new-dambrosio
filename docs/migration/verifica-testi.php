<?php
/**
 * Verifica — ogni frammento di testo dell'originale e' arrivato a destinazione.
 *
 * Uso:  wp eval-file docs/migration/verifica-testi.php
 *
 * Non confronta i blocchi: confronta le **frasi**. Prende il testo della
 * vecchia sezione, lo spezza in frasi, e per ciascuna verifica che compaia nel
 * contenuto nuovo (titolo compreso). Le frasi mancanti sono elencate.
 *
 * @package SorrisoDambrosio\Core
 */

require_once __DIR__ . '/lib-migrazione.php';

/**
 * Normalizza un testo per il confronto: via i tag, le entita' e gli spazi.
 *
 * @param string $html HTML.
 * @return string
 */
function sd_normalizza( $html ) {
	$text = wp_strip_all_tags( strip_shortcodes( $html ) );
	$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = str_replace( array( "\u{2018}", "\u{2019}", "\u{201C}", "\u{201D}", "\u{2013}", "\u{2014}", "\u{00A0}", "\u{200B}" ), array( "'", "'", '"', '"', '-', '-', ' ', '' ), $text );

	return trim( (string) preg_replace( '/\s+/u', ' ', $text ) );
}

$mancanti = 0;
$frasi    = 0;
$voci     = 0;

$posts = get_posts(
	array(
		'post_type'   => array( 'sd_trattamento', 'sd_tecnologia', 'sd_professionista', 'sd_caso' ),
		'post_status' => 'any',
		'numberposts' => -1,
	)
);

foreach ( $posts as $post ) {
	$origine = (string) get_post_meta( $post->ID, '_sd_origine', true );

	if ( '' === $origine || ! str_contains( $origine, '#' ) ) {
		continue;
	}

	[ $page_id, $ancora ] = explode( '#', $origine, 2 );
	$sezioni              = sd_sezioni_ancorate( (int) $page_id );

	if ( ! isset( $sezioni[ $ancora ] ) ) {
		continue;
	}

	++$voci;

	$originale = sd_normalizza( $sezioni[ $ancora ]['raw'] );
	$nuovo     = sd_normalizza( $post->post_title . ' ' . $post->post_content );

	/* Si ignorano le etichette dei pulsanti: sono diventate la fascia di
	 * chiusura, identica su tutte le pagine interne. */
	$ignora = array( "Prenota un'appuntamento", 'Prenota un appuntamento', 'Prenota' );

	foreach ( preg_split( '/(?<=[.!?:])\s+/u', $originale ) ?: array() as $frase ) {
		$frase = trim( $frase );

		if ( mb_strlen( $frase ) < 12 || in_array( $frase, $ignora, true ) ) {
			continue;
		}

		++$frasi;

		if ( ! str_contains( $nuovo, $frase ) ) {
			++$mancanti;
			WP_CLI::warning( sprintf( "%s (#%s)\n    MANCA: %s", $post->post_name, $ancora, mb_substr( $frase, 0, 110 ) ) );
		}
	}
}

WP_CLI::log( sprintf( 'Voci confrontate: %d · frasi: %d · mancanti: %d', $voci, $frasi, $mancanti ) );

if ( 0 === $mancanti ) {
	WP_CLI::success( 'Nessun frammento perso.' );
} else {
	WP_CLI::error( $mancanti . ' frammenti mancanti.', false );
}
