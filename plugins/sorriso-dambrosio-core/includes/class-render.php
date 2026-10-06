<?php
/**
 * I pezzi di markup che piu' blocchi condividono.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Markup condiviso.
 *
 * Qui esce HTML, non blocchi: sono blocchi a resa server, quindi la loro resa
 * e' il loro valore. Le classi sono quelle del tema (`sd-`), perche' il tema
 * resta l'unico posto in cui si decide come appare una cosa: se domani si
 * cambia tema, questo markup resta valido e perde soltanto il vestito.
 */
final class Render {

	/**
	 * Il tag di un titolo, tenuto fra h1 e h6.
	 *
	 * I livelli non si scelgono guardando la grandezza: un lettore di schermo
	 * naviga per titoli, e un salto da h1 a h3 gli fa credere di aver perso un
	 * pezzo di pagina. La grandezza la decide il CSS.
	 */
	public static function tag( int $level ): string {
		return 'h' . max( 1, min( 6, $level ) );
	}

	/**
	 * Un testo senza entita' HTML, per chi non sa leggerle.
	 *
	 * `wptexturize` trasforma l'apostrofo in `&rsquo;` prima di ogni resa, e
	 * `&rsquo;` e' un'entita' **nominata di HTML5**: senza `ENT_HTML5` la
	 * tabella di default (HTML 4.01) non la conosce e la lascia li' tale e
	 * quale. In una pagina HTML non si vede; dentro un JSON-LD si', ed e'
	 * esattamente il posto in cui non deve esserci.
	 */
	public static function plain( string $text ): string {
		return html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	}

	/**
	 * L'intestazione di sezione: filetto sopra, occhiello a destra, titolo sotto.
	 *
	 * E' la stessa figura di `sorriso_dambrosio_section_head()` nel tema, ma in
	 * HTML puro invece che in markup a blocchi.
	 */
	public static function head( string $eyebrow, string $title, int $level = 2, string $href = '' ): string {
		$tag   = 'h' . max( 1, min( 4, $level ) );
		$inner = '' !== $href ? '<a href="' . esc_url( $href ) . '">' . esc_html( $title ) . '</a>' : esc_html( $title );

		return '<div class="sd-head">'
			. ( '' !== $eyebrow ? '<p class="sd-eyebrow has-x-small-font-size">' . esc_html( $eyebrow ) . '</p>' : '' )
			. '<' . $tag . ' class="wp-block-heading">' . $inner . '</' . $tag . '>'
			. '</div>';
	}

	/**
	 * L'immagine in evidenza di un articolo, o niente.
	 *
	 * `wp_get_attachment_image()` e non un URL scritto a mano: srcset,
	 * dimensioni, `alt` e caricamento pigro arrivano gratis, e l'indirizzo si
	 * adatta al dominio dell'ambiente senza search-replace al deploy.
	 */
	public static function thumb( int $post_id, string $size = 'large', string $class = '' ): string {
		$id = get_post_thumbnail_id( $post_id );

		if ( ! $id ) {
			return '';
		}

		$img = wp_get_attachment_image( (int) $id, $size, false, array( 'class' => 'sd-img' ) );

		return '<figure class="' . esc_attr( trim( 'sd-figure ' . $class ) ) . '">' . $img . '</figure>';
	}

	/**
	 * Un estratto corto, che non spezza una parola a meta'.
	 *
	 * Troncare e' permesso solo se il testo intero resta raggiungibile: ogni
	 * elenco che usa questo metodo porta accanto il collegamento alla scheda.
	 */
	public static function excerpt( \WP_Post $post, int $words = 28 ): string {
		$raw = '' !== $post->post_excerpt
			? $post->post_excerpt
			: wp_strip_all_tags( strip_shortcodes( $post->post_content ) );

		return wp_trim_words( $raw, $words, '&hellip;' );
	}

	/**
	 * Un elenco di righe divise da filetti: titolo, estratto, «Continua».
	 *
	 * @param array<int, \WP_Post> $posts Articoli.
	 */
	public static function rows( array $posts, string $more = 'CONTINUA', int $level = 3 ): string {
		if ( array() === $posts ) {
			return '';
		}

		$tag = self::tag( $level );
		$out = '<div class="sd-rows">';

		foreach ( $posts as $post ) {
			$anchor = (string) get_post_meta( $post->ID, Fields::ANCORA, true );

			$out .= '<article class="sd-rows__row"' . ( '' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . '>'
				. '<' . $tag . ' class="sd-rows__title"><a href="' . esc_url( (string) get_permalink( $post ) ) . '">'
				. esc_html( get_the_title( $post ) ) . '</a></' . $tag . '>'
				. '<p class="sd-rows__text">' . esc_html( self::excerpt( $post, 24 ) ) . '</p>'
				. '<p class="sd-more sd-rows__more"><a href="' . esc_url( (string) get_permalink( $post ) ) . '">'
				. esc_html( $more ) . ' &rarr;</a></p>'
				. '</article>';
		}

		return $out . '</div>';
	}

	/**
	 * Lo zig-zag: righe alternate, immagine che esce dal bordo opposto.
	 *
	 * E' l'impianto delle pagine d'area. Non compare in homepage: la regola di
	 * DESIGN FIRST e' che nessuna sezione riusi l'impianto di un'altra.
	 *
	 * @param array<int, \WP_Post> $posts Articoli.
	 */
	public static function zigzag( array $posts, int $level = 2 ): string {
		if ( array() === $posts ) {
			return '';
		}

		$tag = self::tag( $level );
		$out = '<div class="sd-zig">';
		$i   = 0;

		foreach ( $posts as $post ) {
			$side   = 0 === $i % 2 ? 'start' : 'end';
			$anchor = (string) get_post_meta( $post->ID, Fields::ANCORA, true );
			$link   = (string) get_permalink( $post );

			$out .= '<article class="sd-zig__row sd-zig__row--' . esc_attr( $side ) . '"'
				. ( '' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . '>'
				. self::thumb( $post->ID, 'medium_large', 'sd-zig__figure' )
				. '<div class="sd-zig__body">'
				. '<' . $tag . ' class="sd-zig__title"><a href="' . esc_url( $link ) . '">' . esc_html( get_the_title( $post ) ) . '</a></' . $tag . '>'
				. '<p class="sd-zig__text">' . esc_html( self::excerpt( $post, 34 ) ) . '</p>'
				. '<p class="sd-more"><a href="' . esc_url( $link ) . '">CONTINUA &rarr;</a></p>'
				. '</div></article>';

			++$i;
		}

		return $out . '</div>';
	}

	/**
	 * La matrice a filetti: celle divise da linee, niente riquadri.
	 *
	 * E' la figura che `DESIGN-SPEC.md` §1 aveva isolato nel riferimento Prism
	 * e che fin qui non era stata usata da nessuna parte.
	 *
	 * @param array<int, \WP_Post> $posts Articoli.
	 */
	public static function matrix( array $posts, int $level = 3 ): string {
		if ( array() === $posts ) {
			return '';
		}

		$tag = self::tag( $level );
		$out = '<div class="sd-matrix">';

		foreach ( $posts as $post ) {
			$anchor = (string) get_post_meta( $post->ID, Fields::ANCORA, true );
			$link   = (string) get_permalink( $post );

			$out .= '<article class="sd-matrix__cell"' . ( '' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . '>'
				. self::thumb( $post->ID, 'medium', 'sd-matrix__figure' )
				. '<' . $tag . ' class="sd-matrix__title"><a href="' . esc_url( $link ) . '">' . esc_html( get_the_title( $post ) ) . '</a></' . $tag . '>'
				. '<p class="sd-matrix__text">' . esc_html( self::excerpt( $post, 22 ) ) . '</p>'
				. '</article>';
		}

		return $out . '</div>';
	}

	/**
	 * Un pugno di collegamenti in linea, divisi da filetti verticali.
	 *
	 * @param array<int, \WP_Post> $posts Articoli.
	 */
	public static function chips( array $posts ): string {
		if ( array() === $posts ) {
			return '';
		}

		$out = '<ul class="sd-chips">';

		foreach ( $posts as $post ) {
			$out .= '<li class="sd-chips__item"><a href="' . esc_url( (string) get_permalink( $post ) ) . '">'
				. esc_html( get_the_title( $post ) ) . '</a></li>';
		}

		return $out . '</ul>';
	}

	/**
	 * Un'icona del tema, se il tema c'e'.
	 *
	 * Il plugin non porta icone proprie: sono presentazione. Se il tema non
	 * dichiara l'helper, il blocco esce senza icona invece che con un errore.
	 */
	public static function icon( string $name, string $class = '' ): string {
		if ( ! function_exists( 'sorriso_dambrosio_icon' ) ) {
			return '';
		}

		return (string) sorriso_dambrosio_icon( $name, $class );
	}

	/**
	 * L'involucro di una sezione: fondo, contenitore, contenuto.
	 */
	public static function section( string $inner, string $modifier = '', string $anchor = '' ): string {
		$class = trim( 'sd-section ' . $modifier );

		return '<section class="' . esc_attr( $class ) . '"' . ( '' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . '>'
			. '<div class="sd-wrap">' . $inner . '</div></section>';
	}
}
