<?php
/**
 * Migrazione 1 — le cinque aree.
 *
 * Uso:  wp eval-file docs/migration/01-aree.php
 *
 * Nota: `wp eval-file` esegue il contenuto con `eval()`, non con `include`.
 * Per questo gli script di migrazione non dichiarano `strict_types`: in un
 * `eval()` quella direttiva non e' la prima istruzione del file e sarebbe un
 * errore fatale.
 *
 * I nomi sono quelli che il sito pubblica da sempre; gli slug sono gli
 * indirizzi che le vecchie pagine avevano, cosi' `/implantologia/` continua a
 * rispondere 200 senza nemmeno un redirect.
 *
 * Idempotente: rieseguirlo non crea doppioni.
 *
 * @package SorrisoDambrosio\Core
 */

use SD\Core\Content_Types;

/** @var array<int, array{name: string, slug: string, order: int, page: int}> $aree */
$aree = array(
	array( 'name' => 'Implantologia',    'slug' => 'implantologia',    'order' => 1, 'page' => 32 ),
	array( 'name' => 'Ortodonzia',       'slug' => 'ortodonzia',       'order' => 2, 'page' => 34 ),
	array( 'name' => 'Estetica Dentale', 'slug' => 'estetica-dentale', 'order' => 3, 'page' => 36 ),
	array( 'name' => 'Denti e postura',  'slug' => 'denti-e-postura',  'order' => 4, 'page' => 40 ),
	array( 'name' => 'Trattamenti',      'slug' => 'trattamenti',      'order' => 5, 'page' => 38 ),
);

/*
 * L'introduzione e l'immagine di ciascuna area sono testo e immagini gia'
 * pubblicati: i quattro pannelli «Nostri servizi» della homepage piu' la
 * descrizione Yoast della vecchia pagina. Niente e' stato scritto qui.
 *
 * «Denti e postura» non ha nessun testo introduttivo da nessuna parte: la sua
 * pagina elenchera' i trattamenti senza occhiello descrittivo, e il punto e'
 * segnalato in DA-VERIFICARE.md.
 */
$intro = array(
	'estetica-dentale' => 'Scopri il piacere di riscoprire e riabbracciare la bellezza del tuo sorriso attraverso l&#8217;ampia gamma dei nostri servizi di estetica dentale di prima classe.',
	'ortodonzia'       => 'Ci dedichiamo all&#8217;ortodonzia di alta qualit&agrave;, mettendo in primo piano il sorriso e la salute dentale per i nostri pazienti.',
	'implantologia'    => 'Lo studio D&#8217;Ambrosio &egrave; la scelta perfetta per coloro che cercano soluzioni personalizzate in implantologia di alta qualit&agrave;.',
	'trattamenti'      => 'Esplora la gamma completa di servizi dentali personalizzati per soddisfare le tue esigenze specifiche e un sorriso luminoso.',
);

/* Le immagini sono quelle che le vecchie pagine avevano in evidenza, piu' le
 * due che la homepage aveva gia' sostituito perche' troppo piccole. */
$cover = array(
	'implantologia'    => 1226,
	'ortodonzia'       => 527,
	'estetica-dentale' => 604,
	'denti-e-postura'  => 1152,
	'trattamenti'      => 797,
);

foreach ( $aree as $area ) {
	$term = get_term_by( 'slug', $area['slug'], Content_Types::AREA );

	if ( ! $term instanceof WP_Term ) {
		$created = wp_insert_term(
			$area['name'],
			Content_Types::AREA,
			array(
				'slug'        => $area['slug'],
				'description' => $intro[ $area['slug'] ] ?? '',
			)
		);

		if ( is_wp_error( $created ) ) {
			WP_CLI::warning( $area['name'] . ': ' . $created->get_error_message() );
			continue;
		}

		$term_id = (int) $created['term_id'];
		WP_CLI::log( 'creata  ' . $area['name'] );
	} else {
		$term_id = (int) $term->term_id;
		wp_update_term( $term_id, Content_Types::AREA, array( 'description' => $intro[ $area['slug'] ] ?? '' ) );
		WP_CLI::log( 'esiste  ' . $area['name'] );
	}

	update_term_meta( $term_id, '_sd_ordine', $area['order'] );
	update_term_meta( $term_id, '_sd_cover', $cover[ $area['slug'] ] ?? 0 );
	update_term_meta( $term_id, '_sd_pagina_origine', $area['page'] );

	/* I metadati SEO della vecchia pagina non si perdono: Yoast li tiene per i
	 * termini in un'unica opzione serializzata, non nel meta del termine. */
	$titolo = (string) get_post_meta( $area['page'], '_yoast_wpseo_title', true );
	$desc   = (string) get_post_meta( $area['page'], '_yoast_wpseo_metadesc', true );

	$tax_meta = get_option( 'wpseo_taxonomy_meta' );
	$tax_meta = is_array( $tax_meta ) ? $tax_meta : array();

	$tax_meta[ Content_Types::AREA ][ $term_id ] = array_filter(
		array(
			'wpseo_title'    => $titolo,
			'wpseo_desc'     => $desc,
			'wpseo_focuskw'  => (string) get_post_meta( $area['page'], '_yoast_wpseo_focuskw', true ),
		)
	);

	update_option( 'wpseo_taxonomy_meta', $tax_meta );
}

WP_CLI::success( 'Aree: ' . count( $aree ) );
