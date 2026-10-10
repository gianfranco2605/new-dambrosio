<?php
/**
 * Dati strutturati JSON-LD.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * JSON-LD, da dati esistenti e basta.
 *
 * Nessun voto, nessun prezzo, nessuna recensione, nessun campo inventato:
 * dichiarare `aggregateRating` senza recensioni vere e' una penalizzazione
 * garantita, oltre che falso. Le briciole del JSON-LD sono **le stesse** che
 * si vedono in pagina (`Blocks::briciole()`): due elenchi scritti separatamente
 * divergono alla prima modifica.
 */
final class Schema {

	public static function hooks(): void {
		add_action( 'wp_head', array( self::class, 'output' ), 20 );
	}

	public static function output(): void {
		$graph = array( self::dentist() );

		$crumbs = self::crumbs();
		if ( count( $crumbs ) > 1 ) {
			$graph[] = self::breadcrumbs( $crumbs );
		}

		$entity = self::entity();
		if ( array() !== $entity ) {
			$graph[] = $entity;
		}

		/*
		 * `JSON_UNESCAPED_UNICODE` sarebbe piu' leggibile, ma non si puo' usare.
		 *
		 * iubenda monta un buffer di output su tutta la pagina e la ripassa da
		 * un parser DOM: ogni carattere non ASCII torna indietro come entita'
		 * numerica, e `D’Ambrosio` dentro il JSON-LD diventa `D&#8217;Ambrosio`
		 * — che per un lettore di dati strutturati e' testo letterale, non un
		 * apostrofo. Il plugin non si tocca (regola 4 di CLAUDE.md).
		 *
		 * Scrivendo `\u2019` il JSON resta tutto ASCII, il parser di iubenda
		 * non ha niente da convertire, e chi legge il JSON lo rimette a posto
		 * da se'. Verificato con `?iub_no_parse=1`, che spegne quel passaggio.
		 */
		$json = wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES
		);

		if ( false === $json ) {
			return;
		}

		echo "\n<script type=\"application/ld+json\" id=\"sd-schema\">" . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode produce JSON valido e gia' sfuggito.
	}

	/**
	 * Lo studio, su ogni pagina.
	 *
	 * @return array<string, mixed>
	 */
	private static function dentist(): array {
		$c = Studio::contacts();

		$hours = array();
		foreach ( Studio::hours_machine() as $shift ) {
			$hours[] = array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => 'https://schema.org/' . $shift['day'],
				'opens'     => $shift['opens'],
				'closes'    => $shift['closes'],
			);
		}

		return array(
			'@type'                      => 'Dentist',
			'@id'                        => home_url( '/#studio' ),
			'name'                       => $c['name'],
			'url'                        => home_url( '/' ),
			'telephone'                  => array( $c['phone'], $c['phone2'], $c['mobile'] ),
			'email'                      => $c['email'],
			'address'                    => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $c['street'],
				'postalCode'      => $c['postal_code'],
				'addressLocality' => $c['city'],
				'addressRegion'   => $c['region'],
				'addressCountry'  => $c['country'],
			),
			'hasMap'                     => $c['map_href'],
			'sameAs'                     => array( $c['facebook'], $c['instagram'] ),
			'openingHoursSpecification'  => $hours,
		);
	}

	/**
	 * L'entita' della pagina corrente, se ne ha una.
	 *
	 * @return array<string, mixed>
	 */
	private static function entity(): array {
		if ( ! is_singular() ) {
			return array();
		}

		$post = get_post();

		if ( ! $post instanceof \WP_Post ) {
			return array();
		}

		$type = match ( $post->post_type ) {
			Content_Types::TRATTAMENTO    => 'MedicalProcedure',
			Content_Types::PROFESSIONISTA => 'Physician',
			default                       => '',
		};

		if ( '' === $type ) {
			return array();
		}

		$entity = array(
			'@type' => $type,
			'@id'   => get_permalink( $post ) . '#entita',
			'name'  => Render::plain( get_the_title( $post ) ),
			'url'   => get_permalink( $post ),
		);

		$description = wp_strip_all_tags( Render::excerpt( $post, 40 ) );
		if ( '' !== $description ) {
			$entity['description'] = Render::plain( $description );
		}

		$image = get_the_post_thumbnail_url( $post, 'large' );
		if ( is_string( $image ) && '' !== $image ) {
			$entity['image'] = $image;
		}

		if ( 'Physician' === $type ) {
			$entity['worksFor'] = array( '@id' => home_url( '/#studio' ) );
		}

		return $entity;
	}

	/**
	 * Le briciole: una sola definizione per il markup e per il JSON-LD.
	 *
	 * @return array<int, array{name: string, url: string}>
	 */
	public static function crumbs(): array {
		$crumbs = array(
			array(
				'name' => 'Home',
				'url'  => home_url( '/' ),
			),
		);

		if ( is_front_page() ) {
			return $crumbs;
		}

		if ( is_tax( Content_Types::AREA ) ) {
			$term = get_queried_object();

			if ( $term instanceof \WP_Term ) {
				$crumbs[] = array(
					'name' => $term->name,
					'url'  => (string) get_term_link( $term ),
				);
			}

			return $crumbs;
		}

		if ( is_post_type_archive() ) {
			$object = get_queried_object();

			if ( $object instanceof \WP_Post_Type ) {
				$crumbs[] = array(
					'name' => $object->labels->name,
					'url'  => (string) get_post_type_archive_link( $object->name ),
				);
			}

			return $crumbs;
		}

		if ( ! is_singular() ) {
			return $crumbs;
		}

		$post = get_post();

		if ( ! $post instanceof \WP_Post ) {
			return $crumbs;
		}

		// Un trattamento vive sotto la sua area; gli altri tipi sotto il loro
		// archivio. Un articolo o una pagina non hanno nessuno dei due.
		if ( Content_Types::TRATTAMENTO === $post->post_type ) {
			$area = Relations::area( $post->ID );

			if ( $area instanceof \WP_Term ) {
				$crumbs[] = array(
					'name' => $area->name,
					'url'  => (string) get_term_link( $area ),
				);
			}
		} else {
			$archive = get_post_type_archive_link( $post->post_type );
			$object  = get_post_type_object( $post->post_type );

			if ( is_string( $archive ) && $object instanceof \WP_Post_Type ) {
				$crumbs[] = array(
					'name' => $object->labels->name,
					'url'  => $archive,
				);
			}
		}

		$crumbs[] = array(
			'name' => get_the_title( $post ),
			'url'  => (string) get_permalink( $post ),
		);

		return $crumbs;
	}

	/**
	 * @param array<int, array{name: string, url: string}> $crumbs Briciole.
	 * @return array<string, mixed>
	 */
	private static function breadcrumbs( array $crumbs ): array {
		$items = array();

		foreach ( $crumbs as $i => $crumb ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => Render::plain( $crumb['name'] ),
				'item'     => $crumb['url'],
			);
		}

		return array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);
	}
}
