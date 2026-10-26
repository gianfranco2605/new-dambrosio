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

	// I campi dell'area stanno sul **termine**, non su una pagina: vedi
	// `Blocks::area_intestazione()` e `Blocks::area_contenuto()`.
	public const COVER               = '_sd_cover';
	public const ORDINE              = '_sd_ordine';
	public const CONTENUTO           = '_sd_contenuto';
	public const CONTENUTO_TITOLO    = '_sd_contenuto_titolo';
	public const CONTENUTO_OCCHIELLO = '_sd_contenuto_occhiello';

	private const NONCE      = 'sd_core_fields';
	private const NONCE_AREA = 'sd_core_area';

	public static function hooks(): void {
		add_action( 'init', array( self::class, 'register' ), 7 );

		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( self::class, 'add_meta_boxes' ) );
			add_action( 'save_post', array( self::class, 'save' ), 10, 2 );

			// La schermata del termine non ha `add_meta_box`: i campi si
			// appendono alla tabella del modulo di modifica. Il modulo di
			// creazione non li mostra (un'area si crea e poi si compila),
			// ma `created_` resta agganciato: senza nonce non salva nulla.
			add_action( Content_Types::AREA . '_edit_form_fields', array( self::class, 'area_fields' ) );
			add_action( 'edited_' . Content_Types::AREA, array( self::class, 'save_area' ) );
			add_action( 'created_' . Content_Types::AREA, array( self::class, 'save_area' ) );
		}
	}

	public static function register(): void {
		self::register_area_meta();
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

	/**
	 * Chi puo' scrivere i campi di un articolo.
	 *
	 * `auth_callback` e' il cancello di `edit_post_meta` per le chiavi
	 * protette (tutte le nostre iniziano per `_`): la domanda e' se questo
	 * utente puo' modificare **questo** articolo, non se puo' modificare
	 * articoli in generale. `edit_posts` lo ha anche un collaboratore sui
	 * contenuti altrui, quindi si chiede `edit_post` con l'ID che il filtro
	 * passa come terzo argomento.
	 *
	 * Il permesso si chiede per l'utente che il filtro indica (`$user_id`), non
	 * per quello collegato: in REST sono lo stesso, ma un `user_can()` su un
	 * terzo utente con `current_user_can()` avrebbe risposto per il visitatore.
	 *
	 * @param bool   $allowed Permesso calcolato da WordPress.
	 * @param string $key     Chiave del campo.
	 * @param int    $post_id ID dell'articolo su cui si scrive.
	 * @param int    $user_id ID dell'utente di cui si chiede il permesso.
	 */
	public static function can_edit( bool $allowed, string $key, int $post_id, int $user_id ): bool {
		return user_can( $user_id, 'edit_post', $post_id );
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

	/* ---------------------------------------------------------------------
	 * L'area: cinque campi sul termine.
	 * ------------------------------------------------------------------ */

	/**
	 * I cinque campi dell'area.
	 *
	 * Esistevano solo perche' li aveva scritti lo script di migrazione: nessun
	 * `type`, nessuna sanificazione, nessun permesso, e per cambiare la
	 * copertina di un'area bisognava aprire il database. Registrarli qui da'
	 * all'area la stessa dignita' di un articolo.
	 *
	 * `_sd_ordine` e' l'unico senza `default`: `Blocks::aree_ordinate()` manda
	 * in coda l'area che **non ha** il numero, e un default di 0 la farebbe
	 * invece scavalcare le cinque scelte del titolare.
	 */
	private static function register_area_meta(): void {
		register_term_meta(
			Content_Types::AREA,
			self::COVER,
			array(
				'type'              => 'integer',
				'single'            => true,
				'default'           => 0,
				'show_in_rest'      => true,
				'description'       => 'L\'immagine di copertina dell\'area, dalla Libreria media.',
				'sanitize_callback' => 'absint',
				'auth_callback'     => array( self::class, 'can_edit_area' ),
			)
		);

		register_term_meta(
			Content_Types::AREA,
			self::ORDINE,
			array(
				'type'              => 'integer',
				'single'            => true,
				'show_in_rest'      => true,
				'description'       => 'La posizione dell\'area negli elenchi, da 1 a 5. Vuoto: in coda.',
				'sanitize_callback' => 'absint',
				'auth_callback'     => array( self::class, 'can_edit_area' ),
			)
		);

		register_term_meta(
			Content_Types::AREA,
			self::CONTENUTO,
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'show_in_rest'      => true,
				'description'       => 'Il testo libero dell\'area, in blocchi: oggi la FAQ di Implantologia.',
				'sanitize_callback' => 'wp_kses_post',
				'auth_callback'     => array( self::class, 'can_edit_area' ),
			)
		);

		foreach ( array( self::CONTENUTO_TITOLO, self::CONTENUTO_OCCHIELLO ) as $key ) {
			register_term_meta(
				Content_Types::AREA,
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'default'           => '',
					'show_in_rest'      => true,
					'description'       => 'L\'intestazione del testo libero dell\'area.',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => array( self::class, 'can_edit_area' ),
				)
			);
		}
	}

	/**
	 * Chi puo' modificare i campi di un'area.
	 *
	 * Non `edit_posts`: le aree hanno capability proprie
	 * (`Content_Types::register_area()`, oggi `edit_terms => edit_pages`) e il
	 * permesso si chiede alla tassonomia, non si indovina. Lo si chiede con
	 * `edit_term` e l'ID che il filtro passa: `map_meta_cap()` risale dal
	 * termine alla sua tassonomia e arriva allo stesso `edit_terms` di prima,
	 * ma legato all'area che si sta scrivendo — come fa `save_area()`.
	 *
	 * Come `can_edit()`, il permesso e' quello di `$user_id`.
	 *
	 * @param bool   $allowed Permesso calcolato da WordPress.
	 * @param string $key     Chiave del campo.
	 * @param int    $term_id ID dell'area su cui si scrive.
	 * @param int    $user_id ID dell'utente di cui si chiede il permesso.
	 */
	public static function can_edit_area( bool $allowed, string $key, int $term_id, int $user_id ): bool {
		return user_can( $user_id, 'edit_term', $term_id );
	}

	/**
	 * I campi dell'area in «Aree → Modifica area».
	 *
	 * Righe `<tr class="form-field">` appese alla tabella del modulo: e' il
	 * solo modo che una schermata di termine offre.
	 *
	 * @param \WP_Term $term L'area in modifica.
	 */
	public static function area_fields( \WP_Term $term ): void {
		$cover     = (int) get_term_meta( $term->term_id, self::COVER, true );
		$ordine    = get_term_meta( $term->term_id, self::ORDINE, true );
		$titolo    = (string) get_term_meta( $term->term_id, self::CONTENUTO_TITOLO, true );
		$occhiello = (string) get_term_meta( $term->term_id, self::CONTENUTO_OCCHIELLO, true );
		$contenuto = (string) get_term_meta( $term->term_id, self::CONTENUTO, true );

		// Il nonce sta dentro una cella: un `input` figlio diretto di `table`
		// il browser lo sposta fuori dalla tabella.
		echo '<tr class="form-field"><th colspan="2"><h2 style="margin:1em 0 0">La pagina dell\'area</h2>';
		wp_nonce_field( self::NONCE_AREA . '_' . $term->term_id, self::NONCE_AREA );
		echo '</th></tr>';

		echo '<tr class="form-field">';
		echo '<th scope="row"><label for="sd-cover">Immagine di copertina</label></th>';
		echo '<td><input type="number" name="' . esc_attr( self::COVER ) . '" id="sd-cover" value="' . esc_attr( (string) $cover ) . '" min="0" step="1" class="small-text">';
		echo '<p class="description">L\'ID dell\'immagine nella Libreria media (si legge nell\'indirizzo della scheda del file). 0: nessuna copertina.</p>';

		if ( $cover > 0 ) {
			echo wp_get_attachment_image( $cover, 'thumbnail', false, array( 'style' => 'margin-top:.5em;height:auto' ) );
		}

		echo '</td></tr>';

		echo '<tr class="form-field">';
		echo '<th scope="row"><label for="sd-ordine">Posizione negli elenchi</label></th>';
		echo '<td><input type="number" name="' . esc_attr( self::ORDINE ) . '" id="sd-ordine" value="' . esc_attr( is_scalar( $ordine ) ? (string) $ordine : '' ) . '" min="1" step="1" class="small-text">';
		echo '<p class="description">Da 1 a 5, l\'ordine in cui le aree compaiono. Lasciando vuoto, l\'area va in coda.</p>';
		echo '</td></tr>';

		echo '<tr class="form-field">';
		echo '<th scope="row"><label for="sd-contenuto-occhiello">Sopratitolo del testo libero</label></th>';
		echo '<td><input type="text" name="' . esc_attr( self::CONTENUTO_OCCHIELLO ) . '" id="sd-contenuto-occhiello" value="' . esc_attr( $occhiello ) . '" class="regular-text">';
		echo '</td></tr>';

		echo '<tr class="form-field">';
		echo '<th scope="row"><label for="sd-contenuto-titolo">Titolo del testo libero</label></th>';
		echo '<td><input type="text" name="' . esc_attr( self::CONTENUTO_TITOLO ) . '" id="sd-contenuto-titolo" value="' . esc_attr( $titolo ) . '" class="regular-text">';
		echo '<p class="description">Senza titolo, il testo libero non viene mostrato.</p>';
		echo '</td></tr>';

		echo '<tr class="form-field">';
		echo '<th scope="row"><label for="sd-contenuto">Testo libero</label></th>';
		echo '<td><textarea name="' . esc_attr( self::CONTENUTO ) . '" id="sd-contenuto" rows="12" class="large-text code">' . esc_textarea( $contenuto ) . '</textarea>';
		echo '<p class="description">Il testo che appartiene all\'area e non a un singolo trattamento (oggi la FAQ di Implantologia). E\' marcatura a blocchi: conviene scriverla nell\'editor di una bozza e incollarla qui.</p>';
		echo '</td></tr>';
	}

	/**
	 * Salva i campi dell'area.
	 *
	 * Stessa forma di `save()`: nonce, capability, poi i valori. La
	 * sanificazione e' ripetuta qui perche' `sanitize_callback` protegge
	 * `update_term_meta()` ma non le scritture di questo modulo.
	 *
	 * @param int $term_id ID dell'area.
	 */
	public static function save_area( int $term_id ): void {
		$nonce = isset( $_POST[ self::NONCE_AREA ] ) ? sanitize_text_field( wp_unslash( (string) $_POST[ self::NONCE_AREA ] ) ) : '';

		// Il nonce e' legato all'ID dell'area: uno preso dal modulo di un'altra
		// area non vale qui. Il nonce degli articoli (`sd_core_fields`, verificato
		// in `save()`) non e' invece ancora legato al `$post_id`: l'asimmetria e'
		// nota, non una dimenticanza.
		if ( '' === $nonce || ! wp_verify_nonce( $nonce, self::NONCE_AREA . '_' . $term_id ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_term', $term_id ) ) {
			return;
		}

		/*
		 * Zero si cancella, non si salva: `Blocks::aree_ordinate()` manda in
		 * coda l'area **priva** di `_sd_ordine`, mentre uno 0 la porterebbe in
		 * testa, davanti ai cinque numeri scelti dal titolare.
		 */
		foreach ( array( self::COVER, self::ORDINE ) as $key ) {
			$value = isset( $_POST[ $key ] ) ? absint( wp_unslash( (string) $_POST[ $key ] ) ) : 0;

			if ( 0 === $value ) {
				delete_term_meta( $term_id, $key );
				continue;
			}

			update_term_meta( $term_id, $key, $value );
		}

		foreach ( array( self::CONTENUTO_TITOLO, self::CONTENUTO_OCCHIELLO ) as $key ) {
			$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( (string) $_POST[ $key ] ) ) : '';
			update_term_meta( $term_id, $key, $value );
		}

		// Marcatura a blocchi: `wp_kses_post()` lascia passare i commenti
		// `<!-- wp:… -->` e con la FAQ di Implantologia non cambia un byte.
		$contenuto = isset( $_POST[ self::CONTENUTO ] ) ? wp_kses_post( wp_unslash( (string) $_POST[ self::CONTENUTO ] ) ) : '';
		update_term_meta( $term_id, self::CONTENUTO, $contenuto );
	}
}
