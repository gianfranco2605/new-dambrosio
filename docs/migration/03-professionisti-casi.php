<?php
/**
 * Migrazione 3 — i tre professionisti, gli otto casi, e la FAQ.
 *
 * Uso:  wp eval-file docs/migration/03-professionisti-casi.php
 *
 * Le biografie sono quelle del pattern `professionisti.php` della homepage,
 * che a loro volta vengono dalla vecchia home: testo invariato. I casi sono le
 * otto gallerie del plugin Before and After gia' in uso; il titolo di ciascuno
 * e' quello che la galleria gia' mostrava ai visitatori.
 *
 * @package SorrisoDambrosio\Core
 */

use SD\Core\Content_Types;
use SD\Core\Fields;

require_once __DIR__ . '/lib-migrazione.php';

/* ------------------------------------------------------------ professionisti */

$medici = array(
	array(
		'slug'  => 'nicoletta-dambrosio',
		'name'  => 'Dott.ssa Nicoletta D&#8217;Ambrosio',
		'image' => 1201,
		'area'  => 'estetica-dentale',
		'bio'   => '<!-- wp:paragraph -->' . "\n"
			. '<p>La Dott.ssa Nicoletta D&#8217;Ambrosio si distingue nell&#8217;ambito della Parodontologia e dell&#8217;estetica del sorriso, offrendo competenze specializzate e personalizzate.</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n\n"
			. '<!-- wp:paragraph -->' . "\n"
			. '<p>La sua attuale pratica &egrave; incentrata su una visione completa della salute orale, integrando approcci avanzati in Parodontologia con l&#8217;obiettivo di migliorare l&#8217;estetica del sorriso.</p>' . "\n"
			. '<!-- /wp:paragraph -->',
	),
	array(
		'slug'  => 'stefania-dambrosio',
		'name'  => 'Dott.ssa Stefania D&#8217;Ambrosio',
		'image' => 1085,
		'area'  => 'ortodonzia',
		'bio'   => '<!-- wp:paragraph -->' . "\n"
			. '<p>La Dott.ssa Stefania D&#8217;Ambrosio si distingue nell&#8217;Ortodonzia per pazienti di tutte le et&agrave;, utilizzando tecniche tradizionali, linguali e invisibili. La sua pratica mira a garantire risultati estetici e funzionali personalizzati, incorporando le ultime innovazioni nel campo ortodontico.</p>' . "\n"
			. '<!-- /wp:paragraph -->',
	),
	array(
		'slug'  => 'giuseppe-dambrosio',
		'name'  => 'Dott. Giuseppe D&#8217;Ambrosio',
		'image' => 75,
		'area'  => 'implantologia',
		'bio'   => '<!-- wp:paragraph -->' . "\n"
			. '<p>Il Dott. Giuseppe D&#8217;Ambrosio eccelle nei campi dell&#8217;Implantologia, Chirurgia Orale e Osseo-Ricostruttiva, Parodontologia e Protesi Estetica, fornendo competenze specializzate e personalizzate a una vasta gamma di pazienti.</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n\n"
			. '<!-- wp:paragraph -->' . "\n"
			. '<p>La sua dedizione si estende oltre la mera pratica, abbracciando un approccio completo che integra tecniche avanzate e metodologie all&#8217;avanguardia in ciascuna di queste discipline.</p>' . "\n"
			. '<!-- /wp:paragraph -->',
	),
);

$id_medico = array();
$ordine    = 0;

foreach ( $medici as $medico ) {
	++$ordine;

	$id = sd_upsert(
		array(
			'post_type'    => Content_Types::PROFESSIONISTA,
			'post_title'   => $medico['name'],
			'post_name'    => $medico['slug'],
			'post_content' => $medico['bio'],
			'menu_order'   => $ordine,
		),
		'professionista:' . $medico['slug']
	);

	$id_medico[ $medico['slug'] ] = $id;

	set_post_thumbnail( $id, $medico['image'] );
	wp_set_object_terms( $id, $medico['area'], Content_Types::AREA );

	WP_CLI::log( sprintf( '%-6d medico      %s', $id, $medico['slug'] ) );
}

/* -------------------------------------------------------------------- casi */

/*
 * Chi ha seguito quale caso non e' una deduzione: nella vecchia pagina
 * `/casi-studi/` i primi quattro stavano sotto il titolo «Dott. Giuseppe
 * D'Ambrosio» e gli altri quattro sotto «Dott.ssa Stefania D'Ambrosio»; le
 * stesse quattro gallerie comparivano rispettivamente su `/estetica-dentale/`
 * e su `/ortodonzia/`.
 */
$casi = array(
	array( 'bafg' => 1598, 'medico' => 'giuseppe-dambrosio', 'area' => 'estetica-dentale' ),
	array( 'bafg' => 1022, 'medico' => 'giuseppe-dambrosio', 'area' => 'estetica-dentale' ),
	array( 'bafg' => 1026, 'medico' => 'giuseppe-dambrosio', 'area' => 'estetica-dentale' ),
	array( 'bafg' => 1030, 'medico' => 'giuseppe-dambrosio', 'area' => 'estetica-dentale' ),
	array( 'bafg' => 966,  'medico' => 'stefania-dambrosio', 'area' => 'ortodonzia' ),
	array( 'bafg' => 980,  'medico' => 'stefania-dambrosio', 'area' => 'ortodonzia' ),
	array( 'bafg' => 1012, 'medico' => 'stefania-dambrosio', 'area' => 'ortodonzia' ),
	array( 'bafg' => 1015, 'medico' => 'stefania-dambrosio', 'area' => 'ortodonzia' ),
);

$ordine = 0;

foreach ( $casi as $caso ) {
	++$ordine;

	$meta  = get_post_meta( $caso['bafg'], 'beaf_meta', true );
	$meta  = is_array( $meta ) ? $meta : array();
	$title = isset( $meta['bafg_slider_title'] ) && '' !== $meta['bafg_slider_title']
		? (string) $meta['bafg_slider_title']
		: (string) get_the_title( $caso['bafg'] );

	$id = sd_upsert(
		array(
			'post_type'    => Content_Types::CASO,
			'post_title'   => $title,
			'post_name'    => sanitize_title( $title ),
			'post_content' => '',
			'menu_order'   => $ordine,
		),
		'caso:' . $caso['bafg']
	);

	update_post_meta( $id, Fields::BAFG, $caso['bafg'] );
	update_post_meta( $id, Fields::PROFESSIONISTA, $id_medico[ $caso['medico'] ] );
	wp_set_object_terms( $id, $caso['area'], Content_Types::AREA );

	WP_CLI::log( sprintf( '%-6d caso        %s', $id, get_post_field( 'post_name', $id ) ) );
}

/* ------------------------------------------------------------- le relazioni */

/*
 * Tutte derivate dal testo gia' pubblicato. Quelle marcate «inferita» in
 * ARCHITECTURE.md §2.3 vanno confermate dal titolare: sono elencate una per
 * una in DA-VERIFICARE.md §11.
 */
$trattamento = static function ( $slug ) {
	$posts = get_posts(
		array(
			'post_type'   => Content_Types::TRATTAMENTO,
			'name'        => $slug,
			'numberposts' => 1,
			'fields'      => 'ids',
			'post_status' => 'any',
		)
	);

	return isset( $posts[0] ) ? (int) $posts[0] : 0;
};

$tecnologia = static function ( $slug ) {
	$posts = get_posts(
		array(
			'post_type'   => Content_Types::TECNOLOGIA,
			'name'        => $slug,
			'numberposts' => 1,
			'fields'      => 'ids',
			'post_status' => 'any',
		)
	);

	return isset( $posts[0] ) ? (int) $posts[0] : 0;
};

/** @var array<string, array<int, string>> $tecnologie_di */
$tecnologie_di = array(
	'chirurgia-computer-guidata' => array( 'software-chirurgia-guidata' ),
	'senza-lembo-e-suture'       => array( 'software-chirurgia-guidata', 'tc-cone-beam' ),
	'post-estrattiva'            => array( 'rx-digitale-intraorale' ),
	'laser-terapia'              => array( 'chirurgia-piezo-elettrica' ),
	'sbiancamento-professionale' => array( 'chirurgia-piezo-elettrica' ),
	'cannini-inclusi'            => array( 'ortopantomografo' ),
);

foreach ( $tecnologie_di as $slug => $tecnologie ) {
	$id = $trattamento( $slug );

	if ( 0 === $id ) {
		WP_CLI::warning( 'trattamento non trovato: ' . $slug );
		continue;
	}

	delete_post_meta( $id, Fields::TECNOLOGIE );

	foreach ( $tecnologie as $tslug ) {
		$tid = $tecnologia( $tslug );

		if ( $tid > 0 ) {
			add_post_meta( $id, Fields::TECNOLOGIE, $tid );
		}
	}

	WP_CLI::log( sprintf( '%-6d tecnologie  %s → %s', $id, $slug, implode( ', ', $tecnologie ) ) );
}

/** @var array<string, array<int, string>> $si_occupa_di */
$si_occupa_di = array(
	'nicoletta-dambrosio' => array( 'parodontologia' ),
	'stefania-dambrosio'  => array( 'ortodonzia-fissa', 'ortodonzia-linguale', 'ortodonzia-invisibile' ),
	'giuseppe-dambrosio'  => array( 'chirurgia-rigenerativa', 'parodontologia', 'protesi-fissa-estetica' ),
);

foreach ( $si_occupa_di as $slug => $trattamenti ) {
	$id = $id_medico[ $slug ];

	delete_post_meta( $id, Fields::TRATTAMENTI );

	foreach ( $trattamenti as $tslug ) {
		$tid = $trattamento( $tslug );

		if ( $tid > 0 ) {
			add_post_meta( $id, Fields::TRATTAMENTI, $tid );
		}
	}

	WP_CLI::log( sprintf( '%-6d si occupa   %s → %s', $id, $slug, implode( ', ', $trattamenti ) ) );
}

/* ---------------------------------------------------- la FAQ di Implantologia */

/*
 * La vecchia pagina `/implantologia/` chiudeva con una FAQ di sei domande
 * (516 parole). Non e' un trattamento e non e' una tecnologia: e' testo
 * dell'area. Finisce nel meta del termine e la pagina d'area lo rende.
 */
$blocchi = sd_blocchi_primo_livello( 32 );
$faq     = '';

foreach ( $blocchi as $blocco ) {
	if ( str_contains( $blocco, '<!-- wp:details' ) ) {
		$faq = $blocco;
	}
}

if ( '' !== $faq ) {
	$area = get_term_by( 'slug', 'implantologia', Content_Types::AREA );

	if ( $area instanceof WP_Term ) {
		// Via i due titoli «FAQ» e «Domande Frequenti»: li rimette la pagina d'area
		// come intestazione di sezione, nel linguaggio del nuovo tema.
		$corpo = (string) preg_replace( '/<!-- wp:heading.*?<!-- \/wp:heading -->/s', '', $faq, 2 );

		update_term_meta( $area->term_id, '_sd_contenuto', $corpo );
		update_term_meta( $area->term_id, '_sd_contenuto_titolo', 'Domande Frequenti' );
		update_term_meta( $area->term_id, '_sd_contenuto_occhiello', 'FAQ' );

		WP_CLI::log( 'FAQ        → area implantologia (' . strlen( $corpo ) . ' caratteri)' );
	}
}

WP_CLI::success( 'Professionisti, casi e relazioni a posto.' );
