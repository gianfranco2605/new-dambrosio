<?php
/**
 * I tipi di contenuto e l'unica tassonomia.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Quattro tipi di contenuto e una tassonomia condivisa.
 *
 * La tassonomia `sd_area` vale per i trattamenti, per i professionisti e per i
 * casi: assegnare «Ortodonzia» e' sempre lo stesso gesto, nella stessa casella,
 * e una pagina d'area puo' mostrare in un colpo solo i suoi trattamenti, i suoi
 * medici e i suoi casi senza che nessuno li abbia collegati a mano.
 */
final class Content_Types {

	public const AREA       = 'sd_area';
	public const TRATTAMENTO = 'sd_trattamento';
	public const TECNOLOGIA  = 'sd_tecnologia';
	public const PROFESSIONISTA = 'sd_professionista';
	public const CASO        = 'sd_caso';

	/**
	 * I tipi che portano un'area.
	 *
	 * @return array<int, string>
	 */
	public static function typed_by_area(): array {
		return array( self::TRATTAMENTO, self::PROFESSIONISTA, self::CASO );
	}

	public static function hooks(): void {
		add_action( 'init', array( self::class, 'register' ), 5 );
		add_filter( 'enter_title_here', array( self::class, 'title_placeholder' ), 10, 2 );
	}

	public static function register(): void {
		self::register_area();
		self::register_trattamento();
		self::register_tecnologia();
		self::register_professionista();
		self::register_caso();
	}

	/**
	 * La tassonomia delle aree.
	 *
	 * `rewrite` e' spento: gli URL dei termini li scrive `Permalinks`, una
	 * regola per termine, perche' le aree stanno alla radice del sito
	 * (`/implantologia/`) e una riscrittura con slug vuoto ingoierebbe ogni
	 * indirizzo di primo livello.
	 */
	private static function register_area(): void {
		register_taxonomy(
			self::AREA,
			self::typed_by_area(),
			array(
				'labels'            => array(
					'name'              => 'Aree',
					'singular_name'     => 'Area',
					'menu_name'         => 'Aree',
					'all_items'         => 'Tutte le aree',
					'edit_item'         => 'Modifica area',
					'view_item'         => 'Vedi area',
					'update_item'       => 'Aggiorna area',
					'add_new_item'      => 'Aggiungi una nuova area',
					'new_item_name'     => 'Nome della nuova area',
					'search_items'      => 'Cerca fra le aree',
					'not_found'         => 'Nessuna area trovata',
					'back_to_items'     => '← Torna alle aree',
				),
				'description'       => 'Il grande capitolo a cui appartiene un trattamento, un medico o un caso: Implantologia, Ortodonzia, Estetica Dentale, Denti e postura, Trattamenti.',
				'public'            => true,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => false,
				'capabilities'      => array(
					'manage_terms' => 'edit_pages',
					'edit_terms'   => 'edit_pages',
					'delete_terms' => 'edit_pages',
					'assign_terms' => 'edit_posts',
				),
			)
		);
	}

	/**
	 * I trattamenti.
	 *
	 * `rewrite.slug` e' il segnaposto `%sd_area%`: il singolo vive sotto la sua
	 * area (`/implantologia/carico-immediato/`), che e' l'unica gerarchia che un
	 * paziente si aspetta. Lo sostituisce `Permalinks::post_type_link()`.
	 *
	 * L'archivio non puo' stare su `/trattamenti/` — li' c'e' l'area omonima —
	 * e sta su `/prestazioni/`, parola gia' in uso sul sito.
	 */
	private static function register_trattamento(): void {
		register_post_type(
			self::TRATTAMENTO,
			array(
				'labels'        => self::labels( 'Trattamento', 'Trattamenti', 'il trattamento' ),
				'description'   => 'Una singola prestazione dello studio. Prima ciascuna era un\'ancora dentro una pagina lunghissima; ora ha un indirizzo suo.',
				'public'        => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-heart',
				'menu_position' => 20,
				'hierarchical'  => false,
				'has_archive'   => 'prestazioni',
				'rewrite'       => array(
					'slug'       => '%' . self::AREA . '%',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
				'taxonomies'    => array( self::AREA ),
			)
		);
	}

	private static function register_tecnologia(): void {
		register_post_type(
			self::TECNOLOGIA,
			array(
				'labels'        => self::labels( 'Tecnologia', 'Tecnologie', 'la tecnologia' ),
				'description'   => 'Uno strumento o una tecnica dello studio: la TC Cone-Beam, lo scanner intraorale, la sterilizzazione.',
				'public'        => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-visibility',
				'menu_position' => 21,
				'hierarchical'  => false,
				'has_archive'   => 'tecnologie',
				'rewrite'       => array(
					'slug'       => 'tecnologie',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
			)
		);
	}

	private static function register_professionista(): void {
		register_post_type(
			self::PROFESSIONISTA,
			array(
				'labels'        => self::labels( 'Professionista', 'Professionisti', 'il professionista' ),
				'description'   => 'Un medico dello studio: ritratto, biografia, di che cosa si occupa.',
				'public'        => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-groups',
				'menu_position' => 22,
				'hierarchical'  => false,
				'has_archive'   => 'professionisti',
				'rewrite'       => array(
					'slug'       => 'professionisti',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
				'taxonomies'    => array( self::AREA ),
			)
		);
	}

	private static function register_caso(): void {
		register_post_type(
			self::CASO,
			array(
				'labels'        => self::labels( 'Caso clinico', 'Casi Clinici', 'il caso' ),
				'description'   => 'Un caso prima/dopo. La galleria resta quella del plugin Before and After: qui si sceglie soltanto quale mostrare, chi l\'ha seguito e in che area.',
				'public'        => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-images-alt2',
				'menu_position' => 23,
				'hierarchical'  => false,
				'has_archive'   => 'casi-studi',
				'rewrite'       => array(
					'slug'       => 'casi-studi',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
				'taxonomies'    => array( self::AREA ),
			)
		);
	}

	/**
	 * Le etichette dell'admin, tutte in italiano e tutte coerenti.
	 *
	 * @param string $singular  «Trattamento».
	 * @param string $plural    «Trattamenti».
	 * @param string $the       «il trattamento», per le frasi di servizio.
	 * @return array<string, string>
	 */
	private static function labels( string $singular, string $plural, string $the ): array {
		return array(
			'name'                  => $plural,
			'singular_name'         => $singular,
			'menu_name'             => $plural,
			'add_new'               => 'Aggiungi nuovo',
			'add_new_item'          => 'Aggiungi ' . $the,
			'edit_item'             => 'Modifica ' . $the,
			'new_item'              => 'Nuovo: ' . $singular,
			'view_item'             => 'Vedi ' . $the,
			'view_items'            => 'Vedi ' . lcfirst( $plural ),
			'search_items'          => 'Cerca fra ' . lcfirst( $plural ),
			'not_found'             => 'Nessuna voce trovata',
			'not_found_in_trash'    => 'Nessuna voce nel cestino',
			'all_items'             => 'Tutti: ' . $plural,
			'archives'              => 'Archivio: ' . $plural,
			'featured_image'        => 'Immagine',
			'set_featured_image'    => 'Scegli l\'immagine',
			'remove_featured_image' => 'Togli l\'immagine',
			'use_featured_image'    => 'Usa come immagine',
			'item_published'        => $singular . ' pubblicato.',
			'item_updated'          => $singular . ' aggiornato.',
		);
	}

	/**
	 * Il segnaposto del titolo: dire che cosa ci si aspetta costa una riga.
	 *
	 * @param string   $text Testo attuale.
	 * @param \WP_Post $post Articolo in modifica.
	 */
	public static function title_placeholder( string $text, \WP_Post $post ): string {
		return match ( $post->post_type ) {
			self::TRATTAMENTO    => 'Nome del trattamento',
			self::TECNOLOGIA     => 'Nome della tecnologia',
			self::PROFESSIONISTA => 'Nome e cognome, con il titolo',
			self::CASO           => 'Come si chiama il caso (es. Paziente "F.M" 41 anni)',
			default              => $text,
		};
	}
}
