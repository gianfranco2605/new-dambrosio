<?php
/**
 * Gli aiuti condivisi dagli script di migrazione.
 *
 * @package SorrisoDambrosio\Core
 */

/**
 * I blocchi di primo livello di una pagina, in ordine.
 *
 * Il parser e' banale ma corretto: si conta la profondita' dei commenti
 * `<!-- wp:... -->` e si taglia quando torna a zero. Una regex sola non basta,
 * perche' i gruppi sono annidati e il motore non sa contare le parentesi.
 *
 * @param int $page_id ID della pagina.
 * @return array<int, string> Markup di ciascun blocco.
 */
function sd_blocchi_primo_livello( $page_id ) {
	$post = get_post( $page_id );

	if ( ! $post instanceof WP_Post ) {
		return array();
	}

	$pattern = '/<!-- (\/?)wp:([a-z0-9\/-]+)(?:\s+(\{.*?\}))?\s*(\/?)-->/';

	if ( ! preg_match_all( $pattern, $post->post_content, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER ) ) {
		return array();
	}

	$out   = array();
	$depth = 0;
	$start = 0;

	foreach ( $matches as $m ) {
		$is_close = '' !== $m[1][0];
		$is_self  = '' !== $m[4][0];

		if ( $is_close ) {
			--$depth;

			if ( 0 === $depth ) {
				$out[] = substr( $post->post_content, $start, $m[0][1] + strlen( $m[0][0] ) - $start );
			}

			continue;
		}

		if ( 0 === $depth ) {
			$start = $m[0][1];

			if ( $is_self ) {
				$out[] = $m[0][0];
			}
		}

		if ( ! $is_self ) {
			++$depth;
		}
	}

	return $out;
}

/**
 * Spezza una vecchia pagina monolitica nelle sue sezioni ancorate.
 *
 * E' la stessa logica di `extract.py`, qui in PHP perche' serve dentro
 * WordPress. Un blocco di primo livello che porta un `id` HTML e' una sezione.
 *
 * @param int $page_id ID della pagina.
 * @return array<string, array{raw: string, image: int, title: string}> Indicizzato per ancora.
 */
function sd_sezioni_ancorate( $page_id ) {
	$out = array();

	foreach ( sd_blocchi_primo_livello( $page_id ) as $raw ) {
		sd_registra_sezione( $out, $raw );
	}

	return $out;
}

/**
 * @param array<string, array{raw: string, image: int, title: string}> $out Accumulatore.
 * @param string                                                      $raw Markup della sezione.
 */
function sd_registra_sezione( &$out, $raw ) {
	if ( ! preg_match( '/<div[^>]*\bid="([a-z0-9][a-z0-9-]*)"/', $raw, $anchor ) ) {
		return;
	}

	preg_match( '/wp:image\s+\{"id":(\d+)/', $raw, $img );
	preg_match( '/<h[1-6][^>]*>(.*?)<\/h[1-6]>/s', $raw, $head );

	$out[ $anchor[1] ] = array(
		'raw'   => $raw,
		'image' => isset( $img[1] ) ? (int) $img[1] : 0,
		'title' => isset( $head[1] ) ? wp_strip_all_tags( $head[1] ) : '',
	);
}

/**
 * Toglie dalla sezione soltanto l'involucro, il titolo e il pulsante.
 *
 * Il testo non si tocca. Via:
 *   - il gruppo esterno con l'ancora (ora e' l'articolo stesso)
 *   - l'immagine di testa (ora e' l'immagine in evidenza)
 *   - il titolo (ora e' il titolo dell'articolo)
 *   - il pulsante «Prenota un appuntamento» (ora e' la fascia di chiusura,
 *     identica su tutte le pagine interne)
 *
 * @param string $raw Markup della sezione.
 * @return string Markup del contenuto.
 */
function sd_ripulisci_sezione( $raw ) {
	$inner = $raw;

	// Via il gruppo piu' esterno.
	$inner = preg_replace( '/^\s*<!-- wp:group[^>]*-->\s*<div[^>]*>/s', '', $inner, 1 );
	$inner = preg_replace( '/<\/div>\s*<!-- \/wp:group -->\s*$/s', '', (string) $inner, 1 );

	// Via l'immagine di testa e il titolo.
	$inner = preg_replace( '/<!-- wp:image.*?<!-- \/wp:image -->/s', '', (string) $inner, 1 );
	$inner = preg_replace( '/<!-- wp:heading.*?<!-- \/wp:heading -->/s', '', (string) $inner, 1 );

	// Via i pulsanti.
	$inner = preg_replace( '/<!-- wp:buttons.*?<!-- \/wp:buttons -->/s', '', (string) $inner );

	// Via i gruppi rimasti vuoti.
	$inner = (string) $inner;
	for ( $i = 0; $i < 4; $i++ ) {
		$inner = (string) preg_replace( '/<!-- wp:group[^>]*-->\s*<div[^>]*>\s*<\/div>\s*<!-- \/wp:group -->/s', '', $inner );
	}

	return trim( (string) preg_replace( '/\n{3,}/', "\n\n", $inner ) );
}

/**
 * Crea o aggiorna un articolo, riconoscendolo dalla sua origine.
 *
 * `_sd_origine` e' la coppia «pagina#ancora» da cui la voce viene. E' la
 * ragione per cui ogni script si puo' rieseguire senza creare doppioni, ed e'
 * anche la traccia che permette di risalire da una scheda nuova al punto esatto
 * della vecchia pagina da cui e' stata presa.
 *
 * @param array<string, mixed> $args    Argomenti per wp_insert_post.
 * @param string               $origine Chiave d'origine.
 * @return int ID dell'articolo.
 */
function sd_upsert( $args, $origine ) {
	$esistente = get_posts(
		array(
			'post_type'   => $args['post_type'],
			'post_status' => array( 'publish', 'draft', 'pending', 'private' ),
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_sd_origine',   // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query
			'meta_value'  => $origine,        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query
		)
	);

	$args['post_status'] = 'publish';

	if ( isset( $esistente[0] ) ) {
		$args['ID'] = (int) $esistente[0];
		$id         = wp_update_post( $args, true );
	} else {
		$id = wp_insert_post( $args, true );
	}

	if ( is_wp_error( $id ) ) {
		WP_CLI::error( $origine . ': ' . $id->get_error_message() );
	}

	update_post_meta( (int) $id, '_sd_origine', $origine );

	return (int) $id;
}

/**
 * Copia i metadati Yoast da una vecchia pagina a una voce nuova.
 *
 * Senza, title e description si perdono: e' la trappola gia' annotata in
 * CLAUDE.md per le pagine, e vale identica per i tipi di contenuto.
 *
 * @param int    $from ID di origine.
 * @param int    $to   ID di destinazione.
 * @param string $desc Descrizione alternativa, se l'originale non ne ha.
 */
function sd_copia_seo( $from, $to, $desc = '' ) {
	foreach ( array( '_yoast_wpseo_title', '_yoast_wpseo_metadesc', '_yoast_wpseo_focuskw' ) as $key ) {
		$value = get_post_meta( $from, $key, true );

		if ( is_string( $value ) && '' !== $value ) {
			update_post_meta( $to, $key, $value );
		}
	}

	if ( '' !== $desc && '' === (string) get_post_meta( $to, '_yoast_wpseo_metadesc', true ) ) {
		update_post_meta( $to, '_yoast_wpseo_metadesc', $desc );
	}
}
