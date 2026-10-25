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
			'area-intestazione'   => array( self::class, 'area_intestazione' ),
			'area-contenuto'      => array( self::class, 'area_contenuto' ),
			'indice'              => array( self::class, 'indice' ),
			'caso'                => array( self::class, 'caso' ),
			'immagine'            => array( self::class, 'immagine' ),
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

	/**
	 * Il livello del titolo di sezione.
	 *
	 * Su un archivio la sezione **e'** la pagina, quindi il suo titolo e' un
	 * `h1`; dentro una pagina singola e' un `h2`. Un `h1` per pagina, mai due.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	private static function livello( array $attrs ): int {
		return max( 1, min( 4, (int) self::attr( $attrs, 'livello', '2' ) ) );
	}

	private static function current_area_id( string $slug = '' ): int {
		$area = self::current_area( $slug );

		return $area instanceof \WP_Term ? (int) $area->term_id : 0;
	}

	/**
	 * Un attributo del blocco, con il suo ripiego.
	 *
	 * **Una stringa vuota conta come «non dato».** `block.json` dichiara i
	 * valori di partenza, quindi l'attributo arriva sempre valorizzato — anche
	 * a `''` — e un `isset()` secco farebbe vincere quel vuoto sul ripiego
	 * scritto qui: il titolo di «Dove la usiamo» usciva come un `<h2></h2>`
	 * vuoto, visibile solo guardando la pagina resa.
	 *
	 * @param array<string, mixed> $attrs Attributi del blocco.
	 */
	private static function attr( array $attrs, string $key, string $default = '' ): string {
		if ( ! isset( $attrs[ $key ] ) || ! is_scalar( $attrs[ $key ] ) ) {
			return $default;
		}

		$value = (string) $attrs[ $key ];

		return '' !== $value ? $value : $default;
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
	 * L'intestazione di una pagina d'area.
	 *
	 * Titolo, introduzione e immagine stanno sul **termine**, non su una
	 * pagina: aggiungere un'area e' creare un termine, non costruire una
	 * pagina da zero.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function area_intestazione( array $attrs ): string {
		$area = self::current_area();

		if ( ! $area instanceof \WP_Term ) {
			return '';
		}

		$cover = (int) get_term_meta( $area->term_id, '_sd_cover', true );
		$count = count( Relations::in_area( Content_Types::TRATTAMENTO, (int) $area->term_id ) );

		$eyebrow = $count > 0
			? $count . ( 1 === $count ? ' trattamento' : ' trattamenti' )
			: 'Area';

		$body = '<div class="sd-areahead__grid">'
			. '<div class="sd-areahead__text">'
			. Render::head( $eyebrow, $area->name, 1 )
			. ( '' !== $area->description ? '<p class="sd-areahead__lede">' . wp_kses_post( $area->description ) . '</p>' : '' )
			. '</div>';

		if ( $cover > 0 ) {
			/*
			 * `large` e' 1024px e il browser sceglieva il taglio da 768 per uno
			 * spazio da 744: nessun margine per uno schermo retina. `sizes`
			 * dice quanto spazio occupa davvero, cosi' srcset sceglie bene.
			 */
			$img = wp_get_attachment_image(
				$cover,
				'full',
				false,
				array(
					'class' => 'sd-img',
					'sizes' => '(max-width: 900px) 100vw, 62vw',
				)
			);

			$meta    = wp_get_attachment_metadata( $cover );
			$natural = isset( $meta['width'] ) ? (int) $meta['width'] : 0;
			$mode    = $natural >= 1190 ? 'sd-plate--cover' : 'sd-plate--contain';

			$body .= '<figure class="sd-plate ' . esc_attr( $mode ) . ' sd-areahead__figure">' . $img . '</figure>';
		}

		return Render::section( $body . '</div>', trim( 'sd-areahead ' . self::attr( $attrs, 'fondo', '' ) ) );
	}

	/**
	 * Il testo che appartiene all'area e non a un singolo trattamento.
	 *
	 * Oggi serve a una cosa sola: la FAQ di sei domande che chiudeva la vecchia
	 * pagina `/implantologia/` e che non e' ne' un trattamento ne' una
	 * tecnologia. Senza questo blocco si sarebbe persa.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function area_contenuto( array $attrs ): string {
		$area = self::current_area();

		if ( ! $area instanceof \WP_Term ) {
			return '';
		}

		$raw = (string) get_term_meta( $area->term_id, '_sd_contenuto', true );

		if ( '' === $raw ) {
			return '';
		}

		$title   = (string) get_term_meta( $area->term_id, '_sd_contenuto_titolo', true );
		$eyebrow = (string) get_term_meta( $area->term_id, '_sd_contenuto_occhiello', true );

		$body = ( '' !== $title ? Render::head( $eyebrow, $title ) : '' )
			. '<div class="sd-prose sd-areatext">' . do_blocks( $raw ) . '</div>';

		return Render::section( $body, self::attr( $attrs, 'fondo' ) );
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

		$level = self::livello( $attrs );
		$item  = '' !== $title ? $level + 1 : 2;
		$body  = '' !== $title ? Render::head( $eyebrow, $title, $level ) : '';

		$body .= match ( $layout ) {
			'righe'   => Render::rows( $posts, 'CONTINUA', $item ),
			'matrice' => Render::matrix( $posts, $item ),
			default   => Render::zigzag( $posts, $item ),
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
			Render::head( $eyebrow, $title, self::livello( $attrs ) )
				. Render::rows( $posts, 'SCHEDA', self::livello( $attrs ) + 1 ),
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
		$layout  = self::attr( $attrs, 'impianto', 'righe' );
		$tag     = Render::tag( self::livello( $attrs ) + 1 );
		$body    = Render::head( $eyebrow, $title, self::livello( $attrs ) )
			. '<div class="sd-people sd-people--' . esc_attr( $layout ) . '">';

		foreach ( $posts as $doctor ) {
			$link = (string) get_permalink( $doctor );

			$body .= '<article class="sd-people__row">'
				. Render::thumb( $doctor->ID, 'medium_large', 'sd-people__portrait', 216 )
				. '<div class="sd-people__text">'
				. '<' . $tag . ' class="sd-people__name"><a href="' . esc_url( $link ) . '">' . esc_html( get_the_title( $doctor ) ) . '</a></' . $tag . '>'
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
		$body    = Render::head( $eyebrow, $title, self::livello( $attrs ) );

		if ( $grouped ) {
			$groups = array();

			foreach ( $posts as $case ) {
				$doctor = (int) get_post_meta( $case->ID, Fields::PROFESSIONISTA, true );
				$label  = $doctor > 0 ? get_the_title( $doctor ) : 'Altri casi';
				$groups[ $label ][] = $case;
			}

			foreach ( $groups as $label => $group ) {
				$tag   = Render::tag( self::livello( $attrs ) + 1 );
				$body .= '<' . $tag . ' class="sd-cases__group">' . esc_html( (string) $label ) . '</' . $tag . '>'
					. self::cases_grid( $group );
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
			$gallery = $bafg > 0 ? do_shortcode( '[bafg id="' . $bafg . '"]' ) : Render::thumb( $case->ID, 'large', '', 608 );

			$out .= '<article class="sd-cases__item">'
				. '<div class="sd-cases__media">' . $gallery . '</div>'
				. '<p class="sd-cases__caption"><a href="' . esc_url( (string) get_permalink( $case ) ) . '">'
				. esc_html( get_the_title( $case ) ) . '</a></p>'
				. '</article>';
		}

		return $out . '</div>';
	}

	/**
	 * L'indice di un tipo di contenuto.
	 *
	 * Due impianti, nessuno dei quali compare in homepage: la **matrice a
	 * filetti** (celle divise da linee, niente riquadri e niente ombre — la
	 * figura isolata nel riferimento Prism) per le tecnologie, e il
	 * raggruppamento **per area** per tutti i trattamenti.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function indice( array $attrs ): string {
		$type    = self::attr( $attrs, 'tipo', Content_Types::TRATTAMENTO );
		$layout  = self::attr( $attrs, 'impianto', 'matrice' );
		$eyebrow = self::attr( $attrs, 'occhiello' );
		$title   = self::attr( $attrs, 'titolo' );
		$level   = self::livello( $attrs );

		if ( 'aree' === $layout ) {
			$body = '' !== $title ? Render::head( $eyebrow, $title, $level ) : '';
			$tag  = Render::tag( $level + 1 );

			// `meta_key` in `get_terms()` non ordina: filtra. WP_Term_Query lo
			// traduce in una `meta_query` di esistenza, quindi un'area creata dal
			// pannello — che non ha `_sd_ordine` — spariva dall'indice senza
			// errore. Si chiedono i termini per nome e si riordinano in PHP.
			$terms = get_terms(
				array(
					'taxonomy'   => Content_Types::AREA,
					'hide_empty' => true,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);

			if ( ! is_array( $terms ) ) {
				return '';
			}

			$terms = self::aree_ordinate( $terms );

			foreach ( $terms as $term ) {
				if ( ! $term instanceof \WP_Term ) {
					continue;
				}

				$posts = Relations::in_area( $type, (int) $term->term_id );

				if ( array() === $posts ) {
					continue;
				}

				$body .= '<div class="sd-index__area">'
					. '<' . $tag . ' class="sd-index__areaname">'
					. '<a href="' . esc_url( (string) get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>'
					. '</' . $tag . '>'
					. Render::chips( $posts )
					. '</div>';
			}

			return Render::section( $body, trim( 'sd-index ' . self::attr( $attrs, 'fondo' ) ) );
		}

		$posts = get_posts(
			array(
				'post_type'        => $type,
				'numberposts'      => -1,
				'orderby'          => 'menu_order title',
				'order'            => 'ASC',
				'post_status'      => 'publish',
				'suppress_filters' => false,
			)
		);

		if ( array() === $posts ) {
			return '';
		}

		$body = ( '' !== $title ? Render::head( $eyebrow, $title, $level ) : '' )
			. match ( $layout ) {
				'righe'  => Render::rows( $posts, 'CONTINUA', $level + 1 ),
				'zigzag' => Render::zigzag( $posts, $level + 1 ),
				default  => Render::matrix( $posts, $level + 1 ),
			};

		return Render::section( $body, self::attr( $attrs, 'fondo' ) );
	}

	/**
	 * Le aree nell'ordine deciso dal titolare (`_sd_ordine`, da 1 a 5).
	 *
	 * Un'area senza quel numero — qualunque area nuova creata dal pannello —
	 * va **in coda**: i cinque numeri sono una scelta redazionale e un'area
	 * appena aggiunta non puo' scavalcarli. A pari valore l'ordine resta
	 * quello della query (per nome): `usort()` e' stabile da PHP 8.0.
	 *
	 * @param array<int, mixed> $terms I termini restituiti da `get_terms()`.
	 * @return array<int, mixed> Gli stessi termini, riordinati.
	 */
	private static function aree_ordinate( array $terms ): array {
		$rank = static function ( $term ): int {
			if ( ! $term instanceof \WP_Term ) {
				return PHP_INT_MAX;
			}

			$order = get_term_meta( $term->term_id, '_sd_ordine', true );

			return ( is_scalar( $order ) && '' !== $order ) ? (int) $order : PHP_INT_MAX;
		};

		usort(
			$terms,
			static fn( $a, $b ): int => $rank( $a ) <=> $rank( $b )
		);

		return $terms;
	}

	/**
	 * Un caso, in pagina.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function caso( array $attrs ): string {
		$post = self::current_post();

		if ( ! $post instanceof \WP_Post ) {
			return '';
		}

		$bafg   = (int) get_post_meta( $post->ID, Fields::BAFG, true );
		$doctor = (int) get_post_meta( $post->ID, Fields::PROFESSIONISTA, true );
		$area   = Relations::area( $post->ID );

		$eyebrow = $area instanceof \WP_Term ? $area->name : 'Caso clinico';

		$body = Render::head( $eyebrow, get_the_title( $post ), 1 )
			. '<div class="sd-case">'
			. ( $bafg > 0 ? do_shortcode( '[bafg id="' . $bafg . '"]' ) : Render::thumb( $post->ID, 'large', '', 768 ) )
			. '</div>';

		$meta = array();

		if ( $doctor > 0 ) {
			$meta[] = '<li class="sd-case__meta-item"><span class="sd-case__meta-label">Seguito da</span>'
				. '<a href="' . esc_url( (string) get_permalink( $doctor ) ) . '">' . esc_html( get_the_title( $doctor ) ) . '</a></li>';
		}

		if ( $area instanceof \WP_Term ) {
			$meta[] = '<li class="sd-case__meta-item"><span class="sd-case__meta-label">Area</span>'
				. '<a href="' . esc_url( (string) get_term_link( $area ) ) . '">' . esc_html( $area->name ) . '</a></li>';
		}

		if ( array() !== $meta ) {
			$body .= '<ul class="sd-case__meta">' . implode( '', $meta ) . '</ul>';
		}

		return Render::section( $body, self::attr( $attrs, 'fondo' ) );
	}

	/**
	 * L'immagine in evidenza dell'articolo in pagina, dentro la lastra.
	 *
	 * Sostituisce `core/post-featured-image` nei template delle schede: quello
	 * non sa niente di quanto e' grande l'originale, e su `.sd-entry__figure`
	 * rendeva un 300×215 a 652×489 — un ingrandimento di oltre due volte.
	 * `Render::thumb()` conosce il dato e non lo supera mai.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function immagine( array $attrs ): string {
		$post = self::current_post();

		if ( ! $post instanceof \WP_Post ) {
			return '';
		}

		return Render::thumb(
			$post->ID,
			self::attr( $attrs, 'dimensione', 'large' ),
			self::attr( $attrs, 'classe' ),
			(int) self::attr( $attrs, 'slot', '0' )
		);
	}

	/**
	 * Gli orari. Unica fonte: `Studio::hours()`.
	 *
	 * Il markup e' **esattamente** quello che il pattern `orari` del tema
	 * produceva a mano: stesse classi, stesso `ul`, stessi `is-today` e
	 * `is-shut`. Non per nostalgia, ma perche' il foglio di stile del tema li
	 * conosce gia' — e perche' ora il pattern chiama questo blocco invece di
	 * riscriverli, cosi' la fascia in homepage, quella in «Lo Studio» e quella
	 * nei contatti sono lo stesso oggetto.
	 *
	 * @param array<string, mixed> $attrs Attributi.
	 */
	public static function orari( array $attrs ): string {
		$today = Studio::today();
		$rows  = '';

		foreach ( Studio::hours() as $n => $day ) {
			$is_today = ( $n === $today );
			$all_shut = ( 'Chiuso' === $day['morning'] && 'Chiuso' === $day['afternoon'] );

			$rows .= '<li class="sd-hours-week__day' . ( $is_today ? ' is-today' : '' ) . ( $all_shut ? ' is-closed' : '' ) . '"'
				. ( $is_today ? ' aria-current="date"' : '' ) . '>'
				. '<span class="sd-hours-week__name">' . wp_kses_post( $day['name'] )
				// Il colore da solo non basta: «oggi» e' anche scritto.
				. ( $is_today ? '<span class="sd-hours-week__today">oggi</span>' : '' )
				. '</span><span class="sd-hours-week__slots">'
				. self::slot( $day['morning'] ) . self::slot( $day['afternoon'] )
				. '</span></li>';
		}

		$table = '<ul class="sd-hours-week">' . $rows . '</ul>';

		if ( 'compatta' === self::attr( $attrs, 'variante', 'fascia' ) ) {
			return $table;
		}

		$c    = Studio::contacts();
		$href = self::attr( $attrs, 'ancora', '' );
		$href = '' !== $href ? $href : home_url( '/contatti/#prenota' );

		$aside = '<div class="sd-hours-band__aside">'
			. Render::head( 'Quando siamo aperti', 'I Nostri Orari', self::livello( $attrs ), '', 'sd-orari-titolo' )
			. '<p class="sd-hours-band__note has-main-accent-color has-text-color has-small-font-size">Sabato-Domenica chiuso</p>'
			. '<div class="sd-hours-band__actions">'
			. '<a class="sd-btn sd-btn--solid" href="' . esc_url( $c['phone_href'] ) . '">'
			. Render::icon( 'phone' ) . '<span>' . esc_html( $c['phone'] ) . '</span></a>'
			. '<a class="sd-btn sd-btn--ghost" href="' . esc_url( $href ) . '">'
			. Render::icon( 'calendar' ) . '<span>Prenota un appuntamento</span></a>'
			. '</div></div>';

		return '<section class="wp-block-group sd-section sd-section--dark sd-hours-band has-base-color'
			. ' has-main-background-color has-text-color has-background has-link-color" id="orari"'
			. ' aria-labelledby="sd-orari-titolo">'
			. '<div class="sd-wrap sd-hours-band__grid">' . $aside . $table . '</div></section>';
	}

	private static function slot( string $value ): string {
		$shut = ( 'Chiuso' === wp_strip_all_tags( $value ) );

		// Un turno chiuso resta visibile ma spento: dire «chiuso» e'
		// informazione, nasconderla no.
		return '<span class="sd-hours-week__slot' . ( $shut ? ' is-shut' : '' ) . '">'
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
