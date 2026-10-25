<?php
/**
 * Gli indirizzi: regole d'area, permalink dei trattamenti, redirect.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Tiene gli indirizzi esistenti, senza jolly.
 *
 * Le cinque aree stanno alla radice (`/implantologia/`), dove prima stavano le
 * pagine omonime. Una tassonomia con slug vuoto avrebbe risolto il problema
 * ingoiando pero' ogni URL di primo livello del sito; qui si scrive invece
 * **una regola per ciascun termine esistente**, generata dall'elenco reale.
 */
final class Permalinks {

	/** Opzione che ricorda per quali termini sono state scritte le regole. */
	private const SIGNATURE_OPTION = 'sd_core_area_rules';

	/**
	 * Vecchi indirizzi che non devono diventare un 404.
	 *
	 * Sono le voci rotte del vecchio menu: tre percorsi raddoppiati e una
	 * pagina che non e' mai esistita. Oggi rispondono 301 soltanto perche'
	 * WordPress prova a indovinare; qui la destinazione e' dichiarata.
	 *
	 * @return array<string, string> Percorso di partenza => percorso d'arrivo.
	 */
	public static function redirects(): array {
		return array(
			'/estetica/'                   => '/estetica-dentale/',
			'/implantologia/implantologia/' => '/implantologia/',
			'/ortodonzia/ortodonzia/'      => '/ortodonzia/',
			'/trattamenti/trattamenti/'    => '/trattamenti/',
		);
	}

	public static function hooks(): void {
		add_action( 'init', array( self::class, 'add_area_rules' ), 6 );
		add_action( 'wp_loaded', array( self::class, 'flush_when_areas_change' ) );
		add_action( 'created_' . Content_Types::AREA, array( self::class, 'forget_signature' ) );
		add_action( 'edited_' . Content_Types::AREA, array( self::class, 'forget_signature' ) );
		add_action( 'delete_' . Content_Types::AREA, array( self::class, 'forget_signature' ) );
		add_filter( 'post_type_link', array( self::class, 'post_type_link' ), 10, 2 );
		add_filter( 'term_link', array( self::class, 'term_link' ), 10, 3 );
		add_action( 'template_redirect', array( self::class, 'legacy_redirects' ), 1 );
	}

	/**
	 * Gli slug dei termini d'area, in ordine.
	 *
	 * @return array<int, string>
	 */
	private static function area_slugs(): array {
		$terms = get_terms(
			array(
				'taxonomy'   => Content_Types::AREA,
				'hide_empty' => false,
				'fields'     => 'slugs',
			)
		);

		return is_array( $terms ) ? array_values( array_filter( $terms, 'is_string' ) ) : array();
	}

	/**
	 * Una regola per ciascuna area, piu' la paginazione e i suoi trattamenti.
	 *
	 * Il `rewrite.slug` del tipo e' il segnaposto `%sd_area%`, e WordPress lo
	 * mette **alla lettera** nelle regole che genera: `post_type_link()`
	 * sistema l'indirizzo in uscita, ma in entrata `/implantologia/carico-immediato/`
	 * non corrisponderebbe a niente. Le regole vere si scrivono qui, una per
	 * area, come per i termini — e per la stessa ragione: niente jolly.
	 */
	public static function add_area_rules(): void {
		$slugs   = self::area_slugs();
		$archive = 'prestazioni';

		foreach ( array_merge( $slugs, array( $archive ) ) as $slug ) {
			$quoted = preg_quote( $slug, '/' );

			// Prima il singolo, poi l'area: `^slug/?$` non puo' catturare un
			// percorso a due segmenti, quindi l'ordine fra i due e' indifferente,
			// ma tenerlo esplicito evita sorprese se un giorno cambiano.
			add_rewrite_rule(
				'^' . $quoted . '/([^/]+)/?$',
				'index.php?' . Content_Types::TRATTAMENTO . '=$matches[1]&post_type=' . Content_Types::TRATTAMENTO,
				'top'
			);

			if ( $archive === $slug ) {
				continue;
			}

			add_rewrite_rule(
				'^' . $quoted . '/?$',
				'index.php?' . Content_Types::AREA . '=' . $slug,
				'top'
			);
			add_rewrite_rule(
				'^' . $quoted . '/page/([0-9]{1,})/?$',
				'index.php?' . Content_Types::AREA . '=' . $slug . '&paged=$matches[1]',
				'top'
			);
		}
	}

	/**
	 * Rigenera le regole quando l'elenco delle aree cambia.
	 *
	 * `flush_rewrite_rules()` e' costoso e non va chiamato a ogni caricamento:
	 * si confronta una firma dell'elenco con quella salvata e si rigenera solo
	 * se e' cambiata. Cosi' il cliente puo' aggiungere un'area dall'admin senza
	 * che nessuno debba «salvare i permalink».
	 */
	public static function flush_when_areas_change(): void {
		$signature = md5( implode( '|', self::area_slugs() ) );

		if ( get_option( self::SIGNATURE_OPTION ) === $signature ) {
			return;
		}

		update_option( self::SIGNATURE_OPTION, $signature, false );
		flush_rewrite_rules();
	}

	public static function forget_signature(): void {
		delete_option( self::SIGNATURE_OPTION );
	}

	/**
	 * Sostituisce `%sd_area%` con l'area del trattamento.
	 *
	 * Un trattamento senza area finirebbe su `/%sd_area%/slug/`: in quel caso
	 * si ripiega su `prestazioni`, che e' l'archivio e risponde sempre.
	 *
	 * @param string   $link Permalink costruito da WordPress.
	 * @param \WP_Post $post Articolo.
	 */
	public static function post_type_link( string $link, \WP_Post $post ): string {
		if ( Content_Types::TRATTAMENTO !== $post->post_type ) {
			return $link;
		}

		$terms = get_the_terms( $post, Content_Types::AREA );
		$slug  = ( is_array( $terms ) && isset( $terms[0] ) ) ? $terms[0]->slug : 'prestazioni';

		return str_replace( '%' . Content_Types::AREA . '%', $slug, $link );
	}

	/**
	 * L'indirizzo di un'area e' il suo slug alla radice.
	 *
	 * La tassonomia e' registrata con `rewrite => false`, quindi senza questo
	 * filtro `get_term_link()` restituirebbe `?sd_area=implantologia`.
	 *
	 * @param string   $link     Indirizzo calcolato da WordPress.
	 * @param \WP_Term $term     Termine.
	 * @param string   $taxonomy Tassonomia.
	 */
	public static function term_link( string $link, \WP_Term $term, string $taxonomy ): string {
		if ( Content_Types::AREA !== $taxonomy ) {
			return $link;
		}

		return user_trailingslashit( home_url( '/' . $term->slug ) );
	}

	/**
	 * I redirect dei vecchi indirizzi rotti, e l'unico indirizzo d'un trattamento.
	 */
	public static function legacy_redirects(): void {
		if ( is_admin() ) {
			return;
		}

		$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		$request = is_string( $request ) ? $request : '';
		$path    = (string) wp_parse_url( $request, PHP_URL_PATH );
		$path    = user_trailingslashit( '/' . trim( $path, '/' ) );

		$map = self::redirects();

		if ( isset( $map[ $path ] ) ) {
			wp_safe_redirect( home_url( $map[ $path ] ), 301 );
			exit;
		}

		self::canonical_trattamento( $path, $request );
	}

	/**
	 * Un trattamento ha un indirizzo solo: quello sotto la sua area.
	 *
	 * La regola d'entrata `^area/([^/]+)/?$` si scrive per ciascuna delle cinque
	 * aree piu' `prestazioni`, e il secondo segmento e' un jolly che non verifica
	 * l'area: 30 trattamenti per 6 prefissi facevano 180 indirizzi che rispondono
	 * 200 per 30 pagine, identiche fra loro. `redirect_canonical` non interviene,
	 * perche' la richiesta corrisponde a una regola vera, e il `rel=canonical`
	 * resta un suggerimento, non una regola. Il 301 si decide qui, confrontando
	 * i **percorsi** e non gli URL interi, cosi' la query (`?s=`, `?cb=1`) passa
	 * intatta e la richiesta canonica non salta da nessuna parte: un hop solo.
	 *
	 * Riscrivere le 217 regole costerebbe molto di piu' e rischierebbe un 404 di
	 * massa; le regole restano come sono, e qui si tiene un indirizzo per pagina.
	 * Un trattamento senza area ha per permalink `/prestazioni/<slug>/`: quel
	 * ripiego continua a rispondere 200 direttamente.
	 *
	 * @param string $path    Percorso richiesto, normalizzato.
	 * @param string $request REQUEST_URI, da cui si riprende la query.
	 */
	private static function canonical_trattamento( string $path, string $request ): void {
		if ( ! is_singular( Content_Types::TRATTAMENTO ) ) {
			return;
		}

		$permalink = get_permalink();
		$canonical = is_string( $permalink ) ? (string) wp_parse_url( $permalink, PHP_URL_PATH ) : '';
		$canonical = user_trailingslashit( '/' . trim( $canonical, '/' ) );

		// Permalink non riscritti (`?sd_trattamento=slug`): il percorso e' la
		// radice, e un 301 li' sarebbe un giro chiuso sulla home.
		if ( user_trailingslashit( '/' ) === $canonical || $canonical === $path ) {
			return;
		}

		$query = (string) wp_parse_url( $request, PHP_URL_QUERY );

		wp_safe_redirect( home_url( $canonical ) . ( '' === $query ? '' : '?' . $query ), 301 );
		exit;
	}
}
