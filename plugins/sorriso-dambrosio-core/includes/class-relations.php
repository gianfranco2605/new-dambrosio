<?php
/**
 * Le relazioni fra i contenuti, lette sempre dallo stesso lato.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Interroga le relazioni.
 *
 * Ogni relazione e' salvata su un lato solo; qui si leggono tutti e due i versi
 * — quello diretto dal meta, quello inverso da una `meta_query`. Nessun dato
 * duplicato, quindi niente che possa divergere.
 */
final class Relations {

	/**
	 * Gli ID contenuti in un campo-elenco.
	 *
	 * @return array<int, int>
	 */
	public static function ids( int $post_id, string $key ): array {
		$raw = get_post_meta( $post_id, $key, false );

		return array_values( array_filter( array_map( 'intval', (array) $raw ) ) );
	}

	/**
	 * Gli articoli pubblicati con questi ID, nell'ordine deciso dal cliente.
	 *
	 * @param array<int, int> $ids ID.
	 * @return array<int, \WP_Post>
	 */
	public static function posts( array $ids ): array {
		if ( array() === $ids ) {
			return array();
		}

		return get_posts(
			array(
				'post_type'        => 'any',
				'post__in'         => $ids,
				'numberposts'      => -1,
				'orderby'          => 'menu_order title',
				'order'            => 'ASC',
				'post_status'      => 'publish',
				'suppress_filters' => false,
			)
		);
	}

	/**
	 * Il verso inverso: chi punta a questo articolo.
	 *
	 * @param string $post_type Tipo di chi punta.
	 * @param string $key       Chiave del meta che contiene i riferimenti.
	 * @param int    $target    ID puntato.
	 * @return array<int, \WP_Post>
	 */
	public static function referring( string $post_type, string $key, int $target ): array {
		if ( $target <= 0 ) {
			return array();
		}

		return get_posts(
			array(
				'post_type'        => $post_type,
				'numberposts'      => -1,
				'orderby'          => 'menu_order title',
				'order'            => 'ASC',
				'post_status'      => 'publish',
				'suppress_filters' => false,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_meta_query -- sono decine di voci, non decine di migliaia.
				'meta_query'       => array(
					array(
						'key'     => $key,
						'value'   => (string) $target,
						'compare' => '=',
					),
				),
			)
		);
	}

	/**
	 * Gli articoli di un tipo che stanno in un'area.
	 *
	 * @param string $post_type Tipo.
	 * @param int    $term_id   Termine `sd_area`; 0 per tutti.
	 * @param int    $limit     Quanti al massimo; -1 per tutti.
	 * @return array<int, \WP_Post>
	 */
	public static function in_area( string $post_type, int $term_id, int $limit = -1 ): array {
		$args = array(
			'post_type'        => $post_type,
			'numberposts'      => $limit,
			'orderby'          => 'menu_order title',
			'order'            => 'ASC',
			'post_status'      => 'publish',
			'suppress_filters' => false,
		);

		if ( $term_id > 0 ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query -- e' una tassonomia con cinque termini.
			$args['tax_query'] = array(
				array(
					'taxonomy' => Content_Types::AREA,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			);
		}

		return get_posts( $args );
	}

	/**
	 * L'area di un articolo, se ne ha una.
	 */
	public static function area( int $post_id ): ?\WP_Term {
		$terms = get_the_terms( $post_id, Content_Types::AREA );

		return ( is_array( $terms ) && isset( $terms[0] ) ) ? $terms[0] : null;
	}
}
