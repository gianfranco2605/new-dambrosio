<?php
/**
 * I blocchi a resa server.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * I blocchi dinamici.
 *
 * La presentazione sta nel tema, ma **la logica che sceglie i contenuti sta
 * qui**: cosi' sopravvive a un cambio di tema. Sono tutti a resa server —
 * nessun JavaScript, nessuna build, nessun pacchetto da aggiornare — perche'
 * non c'e' niente in queste sezioni che debba succedere nel browser.
 */
final class Blocks {

	public static function hooks(): void {
		add_action( 'init', array( self::class, 'register' ), 20 );
		add_filter( 'block_categories_all', array( self::class, 'category' ) );
	}

	/**
	 * Una categoria tutta per i blocchi del sito.
	 *
	 * Senza, finirebbero sotto «Widget» insieme al calendario e all'archivio,
	 * e il cliente non li troverebbe mai.
	 *
	 * @param array<int, array<string, mixed>> $categories Categorie.
	 * @return array<int, array<string, mixed>>
	 */
	public static function category( array $categories ): array {
		array_unshift(
			$categories,
			array(
				'slug'  => 'sorriso-dambrosio',
				'title' => 'Studio D\'Ambrosio',
				'icon'  => null,
			)
		);

		return $categories;
	}

	public static function register(): void {
		$blocks = array(
			'briciole'            => array( self::class, 'briciole' ),
			'elenco-trattamenti'  => array( self::class, 'elenco_trattamenti' ),
			'tecnologie-collegate' => array( self::class, 'tecnologie_collegate' ),
			'professionisti'      => array( self::class, 'professionisti' ),
			'casi'                => array( self::class, 'casi' ),
			'orari'               => array( self::class, 'orari' ),
			'contatti'            => array( self::class, 'contatti' ),
			'cta-prenota'         => array( self::class, 'cta_prenota' ),
			'intestazione'        => array( self::class, 'intestazione' ),
		);

		foreach ( $blocks as $name => $callback ) {
			$dir = __DIR__ . '/../blocks/' . $name;

			if ( ! is_dir( $dir ) ) {
				continue;
			}

			register_block_type( $dir, array( 'render_callback' => $callback ) );
		}
	}

	/* ---------------------------------------------------------------- aiuti */

	/**
	 * L'articolo in pagina, se ce n'e' uno singolo.
	 */
	private static function current_post(): ?\WP_Post {
		$post = get_post();

		return ( $post instanceof \WP_Post && is_singular() ) ? $post : null;
	}

	/**
	 * L'area di cui si sta parlando: quella richiesta, quella della pagina
	 * di tassonomia, o quella dell'articolo in pagina.
	 */
	private static function current_area( string $slug = '' ): ?\WP_Term {
		if ( '' !== $slug ) {
			$term = get_term_by( 'slug', $slug, Content_Types::AREA );

			return $term instanceof \WP_Term ? $term : null;
		}

		if ( is_tax( Content_Types::AREA ) ) {
			$queried = get_queried_object();

			return $queried instanceof \WP_Term ? $queried : null;
		}

		$post = self::current_post();

		return $post instanceof \WP_Post ? Relations::area( $post->ID ) : null;
	}

	private static function current_area_id( string $slug = '' ): int {
		$area = self::current_area( $slug );

		return $area instanceof \WP_Term ? (int) $area->term_id : 0;
	}

	/**
	 * @param array<string, mixed> $attrs Attributi del blocco.
	 */
	private static function attr( array $attrs, string $key, string $default = '' ): string {
		return isset( $attrs[ $key ] ) && is_scalar( $attrs[ $key ] ) ? (string) $attrs[ $key ] : $default;
	}

	/* --------------------------------------------------------------- blocchi */

	/**
	 * Le briciole di pane.
	 *
	 * Il JSON-LD `BreadcrumbList` nasce dalle stesse briciole che si vedono:
	 * due elenchi scritti separatamente divergono alla prima modifica.
	 */
	public static function briciole(): string {
		$crumbs = Schema::crumbs();

		if ( count( $crumbs ) < 2 ) {
			return '';
		}

		$items = array();
		$last  = count( $crumbs ) - 1;

		foreach ( $crumbs as $i => $crumb ) {
			$items[] = $i === $last
				? '<li class="sd-crumbs__item" aria-current="page">' . esc_html( $crumb['name'] ) . '</li>'
				: '<li class="sd-crumbs__item"><a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['name'] ) . '</a></li>';
		}

		return '<nav class="sd-crumbs" aria-label="Dove ti trovi"><div class="sd-wrap">'
			. '<ol class="sd-crumbs__list">' . implode( '', $items ) . '</ol>'
			. '</div></nav>';
	}

	/**
	 * L'intestazione di sezione, come blocco.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function intestazione( array $attrs ): string {
		$title = self::attr( $attrs, 'titolo' );

		if ( '' === $title ) {
			return '';
		}

		return '<div class="sd-wrap">'
			. Render::head( self::attr( $attrs, 'occhiello' ), $title, (int) self::attr( $attrs, 'livello', '2' ) )
			. '</div>';
	}

	/**
	 * I trattamenti di un'area.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function elenco_trattamenti( array $attrs ): string {
		$area     = self::current_area( self::attr( $attrs, 'area' ) );
		$layout   = self::attr( $attrs, 'impianto', 'zigzag' );
		$eyebrow  = self::attr( $attrs, 'occhiello' );
		$title    = self::attr( $attrs, 'titolo' );
		$exclude  = 'si' === self::attr( $attrs, 'escludiCorrente', 'no' );
		$current  = self::current_post();

		$posts = Relations::in_area( Content_Types::TRATTAMENTO, $area instanceof \WP_Term ? (int) $area->term_id : 0 );

		if ( $exclude && $current instanceof \WP_Post ) {
			$posts = array_values(
				array_filter( $posts, static fn ( \WP_Post $p ): bool => $p->ID !== $current->ID )
			);
		}

		if ( array() === $posts ) {
			return '';
		}

		$body = '' !== $title ? Render::head( $eyebrow, $title ) : '';

		$body .= match ( $layout ) {
			'righe'   => Render::rows( $posts ),
			'matrice' => Render::matrix( $posts ),
			default   => Render::zigzag( $posts ),
		};

		return Render::section( $body, self::attr( $attrs, 'fondo' ) );
	}

	/**
	 * Le tecnologie di un trattamento — o, su una tecnologia, i trattamenti
	 * che la usano. Stesso campo, letto nei due versi.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function tecnologie_collegate( array $attrs ): string {
		$post = self::current_post();

		if ( ! $post instanceof \WP_Post ) {
			return '';
		}

		if ( Content_Types::TECNOLOGIA === $post->post_type ) {
			$posts   = Relations::referring( Content_Types::TRATTAMENTO, Fields::TECNOLOGIE, $post->ID );
			$title   = self::attr( $attrs, 'titolo', 'Dove la usiamo' );
			$eyebrow = self::attr( $attrs, 'occhiello', 'Trattamenti' );
		} else {
			$posts   = Relations::posts( Relations::ids( $post->ID, Fields::TECNOLOGIE ) );
			$title   = self::attr( $attrs, 'titolo', 'Con quali tecnologie' );
			$eyebrow = self::attr( $attrs, 'occhiello', 'Strumenti' );
		}

		if ( array() === $posts ) {
			return '';
		}

		return Render::section(
			Render::head( $eyebrow, $title ) . Render::rows( $posts, 'SCHEDA' ),
			self::attr( $attrs, 'fondo' )
		);
	}

	/**
	 * I professionisti: tutti, quelli dell'area, o quelli che si occupano di
	 * questo trattamento.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function professionisti( array $attrs ): string {
		$source = self::attr( $attrs, 'fonte', 'tutti' );
		$post   = self::current_post();

		$posts = match ( $source ) {
			'trattamento' => $post instanceof \WP_Post
				? Relations::referring( Content_Types::PROFESSIONISTA, Fields::TRATTAMENTI, $post->ID )
				: array(),
			'area'        => Relations::in_area( Content_Types::PROFESSIONISTA, self::current_area_id() ),
			default       => Relations::in_area( Content_Types::PROFESSIONISTA, 0 ),
		};

		if ( array() === $posts ) {
			return '';
		}

		$eyebrow = self::attr( $attrs, 'occhiello', 'Dottori' );
		$title   = self::attr( $attrs, 'titolo', 'Chi se ne occupa' );
		$body    = Render::head( $eyebrow, $title ) . '<div class="sd-people">';

		foreach ( $posts as $doctor ) {
			$link = (string) get_permalink( $doctor );

			$body .= '<article class="sd-people__row">'
				. Render::thumb( $doctor->ID, 'medium', 'sd-people__portrait' )
				. '<div class="sd-people__text">'
				. '<h3 class="sd-people__name"><a href="' . esc_url( $link ) . '">' . esc_html( get_the_title( $doctor ) ) . '</a></h3>'
				. '<p class="sd-people__bio">' . esc_html( Render::excerpt( $doctor, 40 ) ) . '</p>'
				. '<p class="sd-more"><a href="' . esc_url( $link ) . '">LA SCHEDA &rarr;</a></p>'
				. '</div></article>';
		}

		return Render::section( $body . '</div>', self::attr( $attrs, 'fondo' ) );
	}

	/**
	 * I casi clinici. La galleria resta quella del plugin Before and After.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function casi( array $attrs ): string {
		$source = self::attr( $attrs, 'fonte', 'area' );
		$post   = self::current_post();

		$posts = match ( $source ) {
			'professionista' => $post instanceof \WP_Post
				? Relations::referring( Content_Types::CASO, Fields::PROFESSIONISTA, $post->ID )
				: array(),
			'tutti'          => Relations::in_area( Content_Types::CASO, 0 ),
			default          => Relations::in_area( Content_Types::CASO, self::current_area_id() ),
		};

		if ( array() === $posts ) {
			return '';
		}

		$grouped = 'si' === self::attr( $attrs, 'raggruppa', 'no' );
		$eyebrow = self::attr( $attrs, 'occhiello', 'Prima e dopo' );
		$title   = self::attr( $attrs, 'titolo', 'I nostri casi' );
		$body    = Render::head( $eyebrow, $title );

		if ( $grouped ) {
			$groups = array();

			foreach ( $posts as $case ) {
				$doctor = (int) get_post_meta( $case->ID, Fields::PROFESSIONISTA, true );
				$label  = $doctor > 0 ? get_the_title( $doctor ) : 'Altri casi';
				$groups[ $label ][] = $case;
			}

			foreach ( $groups as $label => $group ) {
				$body .= '<h3 class="sd-cases__group">' . esc_html( (string) $label ) . '</h3>' . self::cases_grid( $group );
			}
		} else {
			$body .= self::cases_grid( $posts );
		}

		return Render::section( $body, self::attr( $attrs, 'fondo' ) );
	}

	/**
	 * @param array<int, \WP_Post> $posts Casi.
	 */
	private static function cases_grid( array $posts ): string {
		$out = '<div class="sd-cases">';

		foreach ( $posts as $case ) {
			$bafg = (int) get_post_meta( $case->ID, Fields::BAFG, true );

			// `core/shortcode` non esegue gli shortcode e i template non passano
			// dal filtro `the_content`: qui serve `do_shortcode()` a mano.
			$gallery = $bafg > 0 ? do_shortcode( '[bafg id="' . $bafg . '"]' ) : Render::thumb( $case->ID, 'large' );

			$out .= '<article class="sd-cases__item">'
				. '<div class="sd-cases__media">' . $gallery . '</div>'
				. '<p class="sd-cases__caption"><a href="' . esc_url( (string) get_permalink( $case ) ) . '">'
				. esc_html( get_the_title( $case ) ) . '</a></p>'
				. '</article>';
		}

		return $out . '</div>';
	}

	/**
	 * Gli orari. Unica fonte: `Studio::hours()`.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function orari( array $attrs ): string {
		$today   = Studio::today();
		$variant = self::attr( $attrs, 'variante', 'fascia' );
		$rows    = '';

		foreach ( Studio::hours() as $n => $day ) {
			$is_today = ( $n === $today );

			$rows .= '<div class="sd-hours-week__day' . ( $is_today ? ' sd-hours-week__day--today' : '' ) . '">'
				. '<span class="sd-hours-week__name">' . wp_kses_post( $day['name'] )
				// Il colore da solo non basta: «oggi» e' anche scritto.
				. ( $is_today ? ' <span class="sd-hours-week__today">oggi</span>' : '' )
				. '</span><span class="sd-hours-week__slots">'
				. self::slot( $day['morning'] ) . self::slot( $day['afternoon'] )
				. '</span></div>';
		}

		$table = '<div class="sd-hours-week">' . $rows . '</div>';

		if ( 'compatta' === $variant ) {
			return '<div class="sd-hours-compact">' . $table . '</div>';
		}

		$contacts = Studio::contacts();

		$aside = '<div class="sd-hours-band__aside">'
			. Render::head( 'Quando siamo aperti', 'I Nostri Orari' )
			. '<p class="sd-hours-band__note has-small-font-size">Sabato e domenica lo studio &egrave; chiuso.</p>'
			. '<div class="sd-hours-band__actions">'
			. '<a class="sd-btn sd-btn--solid" href="' . esc_url( $contacts['phone_href'] ) . '">'
			. Render::icon( 'phone' ) . '<span>' . esc_html( $contacts['phone'] ) . '</span></a>'
			. '<a class="sd-btn sd-btn--ghost" href="' . esc_url( home_url( '/contatti/#prenota' ) ) . '">'
			. Render::icon( 'calendar' ) . '<span>Prenota</span></a>'
			. '</div></div>';

		return Render::section(
			'<div class="sd-hours-band__grid">' . $aside . $table . '</div>',
			'sd-section--dark',
			'orari'
		);
	}

	private static function slot( string $value ): string {
		$closed = false !== stripos( wp_strip_all_tags( $value ), 'chiuso' );

		// Un turno chiuso resta visibile ma spento: dire «chiuso» e'
		// informazione, nasconderla no.
		return '<span class="sd-hours-week__slot' . ( $closed ? ' sd-hours-week__slot--closed' : '' ) . '">'
			. wp_kses_post( $value ) . '</span>';
	}

	/**
	 * I recapiti. Unica fonte: `Studio::contacts()`.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function contatti( array $attrs ): string {
		$c    = Studio::contacts();
		$rows = array(
			array( 'pin', 'Indirizzo', $c['address'], $c['map_href'] ),
			array( 'phone', 'Telefono', $c['phone'], $c['phone_href'] ),
			array( 'phone', 'Secondo numero', $c['phone2'], $c['phone2_href'] ),
			array( 'whatsapp', 'WhatsApp', $c['mobile'], $c['whatsapp'] ),
			array( 'mail', 'Email', $c['email'], $c['email_href'] ),
		);

		$out = '<ul class="sd-contacts' . ( 'linea' === self::attr( $attrs, 'variante', 'elenco' ) ? ' sd-contacts--inline' : '' ) . '">';

		foreach ( $rows as [ $icon, $label, $value, $href ] ) {
			$out .= '<li class="sd-contacts__item">' . Render::icon( $icon, 'sd-contacts__icon' )
				. '<span class="sd-contacts__text">'
				. '<span class="sd-contacts__label">' . esc_html( $label ) . '</span>'
				. '<a class="sd-contacts__value" href="' . esc_url( $href ) . '">' . wp_kses_post( $value ) . '</a>'
				. '</span></li>';
		}

		return $out . '</ul>';
	}

	/**
	 * La fascia di prenotazione che chiude ogni pagina interna.
	 *
	 * Una volta sola, qui: se cambia il testo o l'indirizzo del modulo, cambia
	 * in tutte le pagine insieme.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function cta_prenota( array $attrs ): string {
		$c = Studio::contacts();

		$body = '<div class="sd-cta__grid">'
			. '<div class="sd-cta__text">'
			. Render::head( self::attr( $attrs, 'occhiello', 'Contatti' ), self::attr( $attrs, 'titolo', 'Prenota un appuntamento' ) )
			. '<p class="sd-cta__lede">I nostri pazienti vengono accolti in un ambiente elegante ed amichevole, con professionalit&agrave;, competenza ed igiene.</p>'
			. '</div>'
			. '<div class="sd-cta__actions">'
			. '<a class="sd-btn sd-btn--solid" href="' . esc_url( home_url( '/contatti/#prenota' ) ) . '">'
			. Render::icon( 'calendar' ) . '<span>Prenota un appuntamento</span></a>'
			. '<a class="sd-btn sd-btn--outline" href="' . esc_url( $c['phone_href'] ) . '">'
			. Render::icon( 'phone' ) . '<span>' . esc_html( $c['phone'] ) . '</span></a>'
			. '<a class="sd-btn sd-btn--outline" href="' . esc_url( $c['whatsapp'] ) . '" rel="noopener">'
			. Render::icon( 'whatsapp' ) . '<span>WhatsApp</span></a>'
			. '</div></div>';

		return Render::section( $body, 'sd-section--dark sd-cta', 'prenota-cta' );
	}
}
