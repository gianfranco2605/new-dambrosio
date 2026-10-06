<?php
/**
 * Migrazione 2 — i 30 trattamenti e le 8 tecnologie.
 *
 * Uso:  wp eval-file docs/migration/02-trattamenti-tecnologie.php
 *
 * Ogni voce era una sezione ancorata dentro una delle vecchie pagine
 * monolitiche. Il contenuto si copia **alla lettera**: si prende il markup a
 * blocchi della sezione e si tolgono soltanto l'involucro (il gruppo con
 * l'ancora), il titolo (che diventa il titolo dell'articolo) e il pulsante
 * «Prenota un appuntamento» (che ora e' la fascia di chiusura, uguale per
 * tutte le pagine). Nessuna parola riscritta, nessuna parola tolta.
 *
 * Gli **slug** non riproducono le vecchie ancore: quelle contengono refusi
 * (`carico-inmediato`, `flapes`, `ortodonzia-invisiblile`). Un refuso dentro
 * un'ancora e' invisibile; dentro un URL resta per sempre. Le vecchie ancore
 * restano pero' sulle righe delle pagine d'area, cosi' i vecchi link esterni
 * atterrano ancora nel punto giusto.
 *
 * Idempotente: si riconosce una voce gia' migrata dal meta `_sd_origine`.
 *
 * @package SorrisoDambrosio\Core
 */

use SD\Core\Content_Types;
use SD\Core\Fields;

require_once __DIR__ . '/lib-migrazione.php';

/** @var array<int, array{page: int, type: string, area: string, voci: array<string, array{slug: string, title: string}>}> $sorgenti */
$sorgenti = array(
	array(
		'page' => 32,
		'type' => Content_Types::TRATTAMENTO,
		'area' => 'implantologia',
		'voci' => array(
			'carico-inmediato'     => array( 'slug' => 'carico-immediato',               'title' => 'Carico Immediato' ),
			'computer-guidata'     => array( 'slug' => 'chirurgia-computer-guidata',     'title' => 'Chirurgia Computer-guidata' ),
			'minimamente-invasiva' => array( 'slug' => 'chirurgia-minimamente-invasiva', 'title' => 'Chirurgia Minimamente Invasiva' ),
			'post-estrattiva'      => array( 'slug' => 'post-estrattiva',                'title' => 'Implantologia Post Estrattiva' ),
			'flapes'               => array( 'slug' => 'senza-lembo-e-suture',           'title' => 'Implantologia Senza Incisione Lembo e Suture' ),
		),
	),
	array(
		'page' => 34,
		'type' => Content_Types::TRATTAMENTO,
		'area' => 'ortodonzia',
		'voci' => array(
			'ortodonzia-fissa'       => array( 'slug' => 'ortodonzia-fissa',      'title' => 'Ortodonzia Fissa' ),
			'ortodonzia-linguale'    => array( 'slug' => 'ortodonzia-linguale',   'title' => 'Ortodonzia Linguale' ),
			'ortodonzia-invisiblile' => array( 'slug' => 'ortodonzia-invisibile', 'title' => 'Ortodonzia Invisibile' ),
			'ortodonzia-mobile'      => array( 'slug' => 'ortodonzia-mobile',     'title' => 'Ortodonzia Mobile' ),
		),
	),
	array(
		'page' => 36,
		'type' => Content_Types::TRATTAMENTO,
		'area' => 'estetica-dentale',
		'voci' => array(
			'sbiancamento-professionale'   => array( 'slug' => 'sbiancamento-professionale',   'title' => 'Sbiancamento Professionale' ),
			'igieneprofesionale'           => array( 'slug' => 'igiene-e-profilassi-avanzata', 'title' => 'Igiene e Profilassi Avanzata' ),
			'facette-estetiche'            => array( 'slug' => 'faccette-estetiche',           'title' => 'Faccette Estetiche' ),
			'intarsi-in-ceramica'          => array( 'slug' => 'intarsi-in-ceramica',          'title' => 'Intarsi in Ceramica' ),
			'inesti-in-ceramica'           => array( 'slug' => 'inesti-in-ceramica',           'title' => 'Inesti in Ceramica' ),
			'corone-estetiche-in-ceramica' => array( 'slug' => 'corone-estetiche-in-ceramica', 'title' => 'Corone Estetiche in Ceramica' ),
		),
	),
	array(
		'page' => 40,
		'type' => Content_Types::TRATTAMENTO,
		'area' => 'denti-e-postura',
		'voci' => array(
			'pedana-stabilometrica'          => array( 'slug' => 'pedana-stabilometrica',   'title' => 'Pedana Stabilometrica' ),
			'trattamenti-specialistici-bite' => array( 'slug' => 'trattamenti-con-il-bite', 'title' => 'Trattamenti Specialistici con il Bite' ),
			'gnatologia'                     => array( 'slug' => 'gnatologia',              'title' => 'Gnatologia' ),
		),
	),
	array(
		'page' => 38,
		'type' => Content_Types::TRATTAMENTO,
		'area' => 'trattamenti',
		'voci' => array(
			'chirurgia-rigenerativa'          => array( 'slug' => 'chirurgia-rigenerativa',          'title' => 'Chirurgia Rigenerativa' ),
			'chirurgia-ortodonzia'            => array( 'slug' => 'chirurgia-per-l-ortodonzia',      'title' => 'Chirurgia per L&#8217;Ortodonzia' ),
			'laser-terapia'                   => array( 'slug' => 'laser-terapia',                   'title' => 'Laser Terapia' ),
			'parodontologia'                  => array( 'slug' => 'parodontologia',                  'title' => 'Parodontologia' ),
			'prevenzione'                     => array( 'slug' => 'prevenzione-e-profilassi',        'title' => 'Prevenzione e Profilassi' ),
			'protesi-fissa-estetica'          => array( 'slug' => 'protesi-fissa-estetica',          'title' => 'Protesi Fissa Estetica' ),
			'protesi-removibili-parziali'     => array( 'slug' => 'protesi-removibili-parziali',     'title' => 'Protesi Removibili Parziali' ),
			'protesi-removibili-totali'       => array( 'slug' => 'protesi-removibili-totali',       'title' => 'Protesi Removibili Totali' ),
			'protesi-fissa-impianto-naturali' => array( 'slug' => 'protesi-fissa-su-impianto',       'title' => 'Protesi Fissa su Impianto o Denti Naturali' ),
			'endodonzia'                      => array( 'slug' => 'endodonzia',                      'title' => 'Endodonzia o Terapia Canalare' ),
			'conservativa'                    => array( 'slug' => 'conservativa',                    'title' => 'Conservativa' ),
			'cannini-inclusi'                 => array( 'slug' => 'cannini-inclusi',                 'title' => 'Chirurgia dei Cannini Inclusi negli Adulti' ),
		),
	),
	array(
		'page' => 46,
		'type' => Content_Types::TECNOLOGIA,
		'area' => '',
		'voci' => array(
			'tc-cone-beam'       => array( 'slug' => 'tc-cone-beam',               'title' => 'TC Cone-Beam' ),
			'rx-intraorale'      => array( 'slug' => 'rx-digitale-intraorale',     'title' => 'RX Digitale Intraorale' ),
			'chirurgia-guidata'  => array( 'slug' => 'software-chirurgia-guidata', 'title' => 'Software di Progettazione per la Chirurgia Guidata' ),
			'scanner-intraorale' => array( 'slug' => 'scanner-intraorale',         'title' => 'Scanner Intraorale' ),
			'piezo'              => array( 'slug' => 'chirurgia-piezo-elettrica',  'title' => 'Chirurgia Piezo Electtrica (Piezo-surgery)' ),
			'fotosan'            => array( 'slug' => 'fotosan',                    'title' => 'Fotosan' ),
			'ortopantomografo'   => array( 'slug' => 'ortopantomografo',           'title' => 'Ortopantomografo' ),
			'sterilizzazione'    => array( 'slug' => 'sterilizzazione',            'title' => 'Sterilizzazione' ),
		),
	),
);

$fatti = 0;

foreach ( $sorgenti as $sorgente ) {
	$sezioni = sd_sezioni_ancorate( (int) $sorgente['page'] );
	$ordine  = 0;

	foreach ( $sorgente['voci'] as $ancora => $voce ) {
		++$ordine;

		if ( ! isset( $sezioni[ $ancora ] ) ) {
			WP_CLI::warning( 'ancora non trovata: #' . $ancora . ' nella pagina ' . $sorgente['page'] );
			continue;
		}

		$sezione = $sezioni[ $ancora ];

		$id = sd_upsert(
			array(
				'post_type'    => $sorgente['type'],
				'post_title'   => $voce['title'],
				'post_name'    => $voce['slug'],
				'post_content' => sd_ripulisci_sezione( $sezione['raw'] ),
				'menu_order'   => $ordine,
			),
			$sorgente['page'] . '#' . $ancora
		);

		update_post_meta( $id, Fields::ANCORA, $ancora );

		if ( '' !== $sorgente['area'] ) {
			wp_set_object_terms( $id, $sorgente['area'], Content_Types::AREA );
		}

		if ( $sezione['image'] > 0 ) {
			set_post_thumbnail( $id, $sezione['image'] );
		}

		++$fatti;
		WP_CLI::log( sprintf( '%-6d %-16s %s', $id, $sorgente['area'] ?: 'tecnologia', $voce['slug'] ) );
	}
}

WP_CLI::success( 'Voci migrate: ' . $fatti );
