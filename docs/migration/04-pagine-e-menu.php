<?php
/**
 * Migrazione 4 — le pagine nuove, le vecchie in bozza, il menu.
 *
 * Uso:  wp eval-file docs/migration/04-pagine-e-menu.php
 *
 * **Niente viene cancellato.** Le vecchie pagine passano in bozza: restano nel
 * database, restano modificabili, e per rimetterle in linea basta ripubblicarle.
 * Il loro indirizzo non si perde, perche' lo raccoglie un archivio o una pagina
 * di tassonomia che mostra lo stesso contenuto.
 *
 * @package SorrisoDambrosio\Core
 */

require_once __DIR__ . '/lib-migrazione.php';

/* ------------------------------------------------- le due pagine di servizio */

/**
 * Crea o aggiorna una pagina che punta a un pattern del tema.
 *
 * Il contenuto della pagina e' un riferimento, non del markup: cosi' la pagina
 * resta versionata nel tema e **gli URL dei media si risolvono a runtime**,
 * senza indirizzi `.local` congelati nel database.
 *
 * @param string $slug     Slug.
 * @param string $title    Titolo.
 * @param string $template Template personalizzato.
 * @param string $content  Contenuto.
 * @return int ID.
 */
function sd_pagina( $slug, $title, $template, $content ) {
	$id = sd_upsert(
		array(
			'post_type'    => 'page',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
		),
		'pagina:' . $slug
	);

	update_post_meta( $id, '_wp_page_template', $template );

	return $id;
}

/* «Il Nostro Studio» — la vecchia pagina 48 passa in bozza e cede lo slug. */
$vecchia = get_post( 48 );

if ( $vecchia instanceof WP_Post && 'lo-studio' === $vecchia->post_name ) {
	wp_update_post(
		array(
			'ID'          => 48,
			'post_name'   => 'lo-studio-originale',
			'post_status' => 'draft',
		)
	);
	WP_CLI::log( '48     in bozza    lo-studio-originale' );
}

$lo_studio = sd_pagina(
	'lo-studio',
	'Il Nostro Studio',
	'page-lo-studio',
	'<!-- wp:pattern {"slug":"sorriso-dambrosio/lo-studio-pagina"} /-->'
);
sd_copia_seo( 48, $lo_studio );
set_post_thumbnail( $lo_studio, 1321 );
WP_CLI::log( sprintf( '%-6d pagina      /lo-studio/', $lo_studio ) );

/* «Contatti» — non esisteva: il sito non aveva una pagina dei contatti. */
$contatti = sd_pagina(
	'contatti',
	'Contatti',
	'page-contatti',
	'<!-- wp:paragraph --><p></p><!-- /wp:paragraph -->'
);
update_post_meta( $contatti, '_yoast_wpseo_title', 'Contatti | Studio Odontoiatrico D\'Ambrosio' );
update_post_meta( $contatti, '_yoast_wpseo_metadesc', 'Studio Odontoiatrico D\'Ambrosio, Lungotevere Flaminio 34, Roma. Telefono, WhatsApp, email, orari di apertura e modulo per richiedere un appuntamento.' );
WP_CLI::log( sprintf( '%-6d pagina      /contatti/', $contatti ) );

/* ------------------------------------------- le vecchie pagine, in bozza */

/*
 * Ciascuna cede il suo indirizzo a qualcosa che mostra lo stesso contenuto:
 * le cinque aree a un termine `sd_area`, le tecnologie e i casi a un archivio.
 */
$da_archiviare = array(
	32   => 'implantologia    → termine sd_area',
	34   => 'ortodonzia       → termine sd_area',
	36   => 'estetica-dentale → termine sd_area',
	38   => 'trattamenti      → termine sd_area',
	40   => 'denti-e-postura  → termine sd_area',
	46   => 'tecnologie       → archivio sd_tecnologia',
	1619 => 'casi-studi       → archivio sd_caso',
);

foreach ( $da_archiviare as $id => $nota ) {
	$post = get_post( $id );

	if ( ! $post instanceof WP_Post ) {
		continue;
	}

	if ( 'draft' !== $post->post_status ) {
		wp_update_post(
			array(
				'ID'          => $id,
				'post_status' => 'draft',
			)
		);
	}

	WP_CLI::log( sprintf( '%-6d in bozza    %s', $id, $nota ) );
}

/* ------------------------------------------------------------------ il menu */

/*
 * Il menu e' il blocco Navigation, cioe' il post `wp_navigation` ID 4 — non un
 * menu classico: `wp menu` non serve a nulla qui, e un blocco Navigation che
 * punta a un ID inesistente non rende nulla e non da' errore.
 *
 * Le 45 voci di secondo livello smettono di essere ancore dentro sette pagine
 * e diventano indirizzi veri.
 */
$nav_id = 4;
$nav    = get_post( $nav_id );

if ( ! $nav instanceof WP_Post ) {
	WP_CLI::error( 'Il post wp_navigation ' . $nav_id . ' non esiste.' );
}

/**
 * Una voce di menu.
 *
 * @param string $label Etichetta.
 * @param string $url   Indirizzo.
 * @param int    $id    ID dell'oggetto, se ce n'e' uno.
 * @param string $kind  Tipo per WordPress.
 * @return string
 */
function sd_voce( $label, $url, $id = 0, $kind = 'custom' ) {
	$attrs = array(
		'label' => $label,
		'type'  => $kind,
		'url'   => $url,
	);

	if ( $id > 0 ) {
		$attrs['id'] = $id;
	}

	$attrs['kind'] = 'custom' === $kind ? 'custom' : ( 'taxonomy' === $kind ? 'taxonomy' : 'post-type' );

	return '<!-- wp:navigation-link ' . wp_json_encode( $attrs ) . ' /-->';
}

/**
 * Una voce con tendina.
 *
 * @param string            $label    Etichetta.
 * @param string            $url      Indirizzo.
 * @param array<int,string> $children Voci figlie gia' serializzate.
 * @return string
 */
function sd_sottomenu( $label, $url, $children ) {
	$attrs = wp_json_encode(
		array(
			'label' => $label,
			'type'  => 'custom',
			'url'   => $url,
			'kind'  => 'custom',
		)
	);

	return '<!-- wp:navigation-submenu ' . $attrs . ' -->' . implode( '', $children ) . '<!-- /wp:navigation-submenu -->';
}

$voci = array();

/*
 * Lo Studio, con la squadra, i casi e i contatti.
 *
 * Sette voci di primo livello, non otto: misurato sulla pagina resa, a 1440px
 * la riga intera (logo 140 + otto voci 716 + telefono 170 + prenota 224 piu' i
 * margini) chiede 1290px contro i 1256 disponibili, e la prima voce finiva
 * **sopra** il logo. «Contatti» scende sotto «Lo Studio»: resta raggiungibile
 * da li', dal pulsante «Prenota un appuntamento» nell'header, dalla fascia di
 * chiusura di ogni pagina interna e dal footer.
 *
 * E' la riorganizzazione che DA-VERIFICARE.md §0.5 lasciava aperta.
 */
$voci[] = sd_sottomenu(
	'Lo Studio',
	home_url( '/lo-studio/' ),
	array(
		sd_voce( 'I Professionisti', (string) get_post_type_archive_link( 'sd_professionista' ) ),
		sd_voce( 'Casi Studi', (string) get_post_type_archive_link( 'sd_caso' ) ),
		sd_voce( 'Contatti', home_url( '/contatti/' ) ),
	)
);

/* Le cinque aree, ciascuna con i suoi trattamenti. */
$aree = get_terms(
	array(
		'taxonomy'   => 'sd_area',
		'hide_empty' => false,
		'meta_key'   => '_sd_ordine',  // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
	)
);

/*
 * Nel menu l'area «Estetica Dentale» si chiama «Estetica», come si chiamava
 * prima: e' l'etichetta che il sito pubblica da sempre, e la riga dell'header
 * e' piena — misurata, chiede 1328px. Il nome dell'area resta per esteso
 * ovunque altro.
 */
$etichette = array( 'estetica-dentale' => 'Estetica' );

foreach ( is_array( $aree ) ? $aree : array() as $area ) {
	if ( ! $area instanceof WP_Term ) {
		continue;
	}

	$figli = array();

	foreach ( SD\Core\Relations::in_area( 'sd_trattamento', (int) $area->term_id ) as $t ) {
		$figli[] = sd_voce( get_the_title( $t ), (string) get_permalink( $t ) );
	}

	$voci[] = sd_sottomenu( $etichette[ $area->slug ] ?? $area->name, (string) get_term_link( $area ), $figli );
}

/* Le tecnologie. */
$figli = array();

foreach ( get_posts( array( 'post_type' => 'sd_tecnologia', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) as $t ) {
	$figli[] = sd_voce( get_the_title( $t ), (string) get_permalink( $t ) );
}

$voci[] = sd_sottomenu( 'Tecnologie', (string) get_post_type_archive_link( 'sd_tecnologia' ), $figli );

wp_update_post(
	array(
		'ID'           => $nav_id,
		'post_content' => implode( "\n\n", $voci ),
	)
);

WP_CLI::log( sprintf( 'menu %d: %d voci di primo livello', $nav_id, count( $voci ) ) );
WP_CLI::success( 'Pagine e menu a posto.' );
