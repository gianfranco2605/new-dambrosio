<?php
/**
 * I campi: post meta nativo, piu' i riquadri che lo rendono usabile.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * I campi dei contenuti.
 *
 * Niente ACF: sul sito non c'e' e aggiungere una dipendenza commerciale a un
 * sito che dovra' sopravvivere da solo non vale un paio di caselle. Post meta
 * nativo con `show_in_rest` arriva gratis nell'editor, nella REST API e nella
 * Block Bindings API; i riquadri `add_meta_box` compaiono in fondo all'editor a
 * blocchi e non chiedono di imparare niente.
 *
 * Le relazioni sono salvate **su un lato solo**: due copie dello stesso fatto
 * divergono sempre. Il verso opposto si ricava con una query (vedi `Relations`).
 */
final class Fields {

	public const TECNOLOGIE    = '_sd_tecnologie';
	public const TRATTAMENTI   = '_sd_trattamenti';
	public const PROFESSIONISTA = '_sd_professionista';
	public const BAFG          = '_sd_bafg';
	public const ANCORA        = '_sd_ancora_legacy';

	private const NONCE = 'sd_core_fields';

	public static function hooks(): void {
		add_action( 'init', array( self::class, 'register' ), 7 );

		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( self::class, 'add_meta_boxes' ) );
			add_action( 'save_post', array( self::class, 'save' ), 10, 2 );
		}
	}

	public static function register(): void {
		self::register_ids( Content_Types::TRATTAMENTO, self::TECNOLOGIE );
		self::register_ids( Content_Types::PROFESSIONISTA, self::TRATTAMENTI );

		register_post_meta(
			Content_Types::CASO,
			self::PROFESSIONISTA,
			array(
				'type'              => 'integer',
				'single'            => true,
				'default'           => 0,
				'show_in_rest'      => true,
				'description'       => 'Il medico che ha seguito il caso.',
				'sanitize_callback' => 'absint',
				'auth_callback'     => array( self::class, 'can_edit' ),
			)
		);

		register_post_meta(
			Content_Types::CASO,
			self::BAFG,
			array(
				'type'              => 'integer',
				'single'            => true,
				'default'           => 0,
				'show_in_rest'      => true,
				'description'       => 'La galleria prima/dopo del plugin Before and After Gallery.',
				'sanitize_callback' => 'absint',
				'auth_callback'     => array( self::class, 'can_edit' ),
			)
		);

		foreach ( array( Content_Types::TRATTAMENTO, Content_Types::TECNOLOGIA ) as $type ) {
			register_post_meta(
				$type,
				self::ANCORA,
				array(
					'type'              => 'string',
					'single'            => true,
					'default'           => '',
					'show_in_rest'      => true,
					'description'       => 'L\'ancora che questa voce aveva nella vecchia pagina monolitica: serve a far atterrare ancora i vecchi link.',
					'sanitize_callback' => 'sanitize_title',
					'auth_callback'     => array( self::class, 'can_edit' ),
				)
			);
		}
	}

	/**
	 * Un campo che contiene un elenco di ID.
	 *
	 * `single => false` con `type => integer`: WordPress salva una riga per
	 * valore, che e' il modo nativo di tenere una lista e permette
	 * `meta_query` sul verso opposto della relazione.
	 */
	private static function register_ids( string $post_type, string $key ): void {
		register_post_meta(
			$post_type,
			$key,
			array(
				'type'              => 'integer',
				'single'            => false,
				'show_in_rest'      => true,
				'sanitize_callback' => 'absint',
				'auth_callback'     => array( self::class, 'can_edit' ),
			)
		);
	}

	public static function can_edit(): bool {
		return current_user_can( 'edit_posts' );
	}

	public static function add_meta_boxes(): void {
		add_meta_box(
			'sd-tecnologie',
			'Tecnologie collegate',
			array( self::class, 'box_tecnologie' ),
			Content_Types::TRATTAMENTO,
			'side',
			'default'
		);

		add_meta_box(
			'sd-trattamenti',
			'Si occupa di',
			array( self::class, 'box_trattamenti' ),
			Content_Types::PROFESSIONISTA,
			'normal',
			'default'
		);

		add_meta_box(
			'sd-caso',
			'Galleria e medico',
			array( self::class, 'box_caso' ),
			Content_Types::CASO,
			'side',
			'high'
		);
	}

	public static function box_tecnologie( \WP_Post $post ): void {
		$selected = Relations::ids( $post->ID, self::TECNOLOGIE );
		self::nonce();
		echo '<p class="description">Le tecnologie che questo trattamento impiega. Compaiono in fondo alla sua pagina.</p>';
		self::checkboxes( self::TECNOLOGIE, Content_Types::TECNOLOGIA, $selected );
	}

	public static function box_trattamenti( \WP_Post $post ): void {
		$selected = Relations::ids( $post->ID, self::TRATTAMENTI );
		self::nonce();
		echo '<p class="description">I trattamenti di cui questo medico si occupa. Compaiono sulla sua scheda, e la sua scheda compare sulla pagina di ciascun trattamento.</p>';
		self::checkboxes( self::TRATTAMENTI, Content_Types::TRATTAMENTO, $selected, true );
	}

	public static function box_caso( \WP_Post $post ): void {
		self::nonce();

		$bafg   = (int) get_post_meta( $post->ID, self::BAFG, true );
		$doctor = (int) get_post_meta( $post->ID, self::PROFESSIONISTA, true );

		echo '<p><label for="sd-bafg"><strong>Galleria prima/dopo</strong></label><br>';
		echo '<select name="' . esc_attr( self::BAFG ) . '" id="sd-bafg" style="width:100%">';
		echo '<option value="0">— nessuna —</option>';
		foreach ( self::options( 'bafg' ) as $id => $label ) {
			printf(
				'<option value="%d"%s>%s</option>',
				(int) $id,
				selected( $bafg, (int) $id, false ),
				esc_html( $label )
			);
		}
		echo '</select></p>';
		echo '<p class="description">La galleria si crea come sempre in «Before and After Slider». Qui si sceglie soltanto quale mostrare.</p>';

		echo '<p><label for="sd-medico"><strong>Medico</strong></label><br>';
		echo '<select name="' . esc_attr( self::PROFESSIONISTA ) . '" id="sd-medico" style="width:100%">';
		echo '<option value="0">— nessuno —</option>';
		foreach ( self::options( Content_Types::PROFESSIONISTA ) as $id => $label ) {
			printf(
				'<option value="%d"%s>%s</option>',
				(int) $id,
				selected( $doctor, (int) $id, false ),
				esc_html( $label )
			);
		}
		echo '</select></p>';
	}

	/**
	 * Le voci pubblicate di un tipo, come `ID => titolo`.
	 *
	 * @return array<int, string>
	 */
	private static function options( string $post_type ): array {
		$posts = get_posts(
			array(
				'post_type'        => $post_type,
				'post_status'      => array( 'publish', 'draft', 'pending' ),
				'numberposts'      => -1,
				'orderby'          => 'title',
				'order'            => 'ASC',
				'suppress_filters' => false,
			)
		);

		$out = array();
		foreach ( $posts as $post ) {
			$out[ $post->ID ] = $post->post_title;
		}

		return $out;
	}

	/**
	 * Un elenco di caselle, eventualmente raggruppate per area.
	 *
	 * @param string          $key       Chiave del meta.
	 * @param string          $post_type Tipo da elencare.
	 * @param array<int, int> $selected  ID gia' spuntati.
	 * @param bool            $by_area   Raggruppa per area.
	 */
	private static function checkboxes( string $key, string $post_type, array $selected, bool $by_area = false ): void {
		$posts = get_posts(
			array(
				'post_type'        => $post_type,
				'post_status'      => array( 'publish', 'draft', 'pending' ),
				'numberposts'      => -1,
				'orderby'          => 'menu_order title',
				'order'            => 'ASC',
				'suppress_filters' => false,
			)
		);

		if ( array() === $posts ) {
			echo '<p><em>Non c\'e\' ancora niente da collegare.</em></p>';
			return;
		}

		$groups = array();
		foreach ( $posts as $post ) {
			$label = '';
			if ( $by_area ) {
				$terms = get_the_terms( $post, Content_Types::AREA );
				$label = ( is_array( $terms ) && isset( $terms[0] ) ) ? $terms[0]->name : 'Senza area';
			}
			$groups[ $label ][] = $post;
		}
		ksort( $groups );

		echo '<div class="sd-checklist" style="max-height:30em;overflow:auto">';
		foreach ( $groups as $label => $group ) {
			if ( '' !== $label ) {
				echo '<p style="margin:1em 0 .25em"><strong>' . esc_html( (string) $label ) . '</strong></p>';
			}
			foreach ( $group as $post ) {
				printf(
					'<label style="display:block;margin:.2em 0"><input type="checkbox" name="%s[]" value="%d"%s> %s</label>',
					esc_attr( $key ),
					(int) $post->ID,
					checked( in_array( (int) $post->ID, $selected, true ), true, false ),
					esc_html( $post->post_title )
				);
			}
		}
		echo '</div>';
	}

	private static function nonce(): void {
		static $done = false;

		if ( $done ) {
			return;
		}

		$done = true;
		wp_nonce_field( self::NONCE, self::NONCE );
	}

	/**
	 * Salva i campi.
	 *
	 * @param int      $post_id ID dell'articolo.
	 * @param \WP_Post $post    Articolo.
	 */
	public static function save( int $post_id, \WP_Post $post ): void {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$nonce = isset( $_POST[ self::NONCE ] ) ? sanitize_text_field( wp_unslash( (string) $_POST[ self::NONCE ] ) ) : '';

		if ( '' === $nonce || ! wp_verify_nonce( $nonce, self::NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( Content_Types::TRATTAMENTO === $post->post_type ) {
			self::save_ids( $post_id, self::TECNOLOGIE );
		}

		if ( Content_Types::PROFESSIONISTA === $post->post_type ) {
			self::save_ids( $post_id, self::TRATTAMENTI );
		}

		if ( Content_Types::CASO === $post->post_type ) {
			foreach ( array( self::BAFG, self::PROFESSIONISTA ) as $key ) {
				$value = isset( $_POST[ $key ] ) ? absint( wp_unslash( (string) $_POST[ $key ] ) ) : 0;
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	private static function save_ids( int $post_id, string $key ): void {
		$raw = isset( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : array();
		$ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $raw ) ) ) );

		delete_post_meta( $post_id, $key );

		foreach ( $ids as $id ) {
			add_post_meta( $post_id, $key, $id );
		}
	}
}
