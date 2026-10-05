<?php
/**
 * Sorriso D'Ambrosio — child theme di Ollie.
 *
 * Target PHP 7.4: niente match, union types, nullsafe, argomenti con nome,
 * promozione nel costruttore, str_contains/str_starts_with, enum, readonly.
 *
 * @package SorrisoDambrosio
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SORRISO_DAMBROSIO_VERSION' ) ) {
	define( 'SORRISO_DAMBROSIO_VERSION', '1.0.0' );
}

/**
 * Accoda il foglio di stile del child dopo quello del parent.
 *
 * Ollie accoda il proprio foglio con handle sanitize_title( 'Ollie' ) = 'ollie'.
 * Se un giorno cambiasse, la dipendenza verrebbe semplicemente ignorata e il
 * foglio del child resterebbe comunque in coda.
 *
 * @return void
 */
function sorriso_dambrosio_enqueue_styles() {
	$style_path = get_stylesheet_directory() . '/style.css';
	$version    = file_exists( $style_path ) ? (string) filemtime( $style_path ) : SORRISO_DAMBROSIO_VERSION;

	wp_enqueue_style(
		'sorriso-dambrosio-style',
		get_stylesheet_uri(),
		array( 'ollie' ),
		$version
	);
}
add_action( 'wp_enqueue_scripts', 'sorriso_dambrosio_enqueue_styles', 20 );

/**
 * Usa lo stesso foglio di stile anche negli editor, cosi' l'anteprima
 * corrisponde al front-end.
 *
 * @return void
 */
function sorriso_dambrosio_editor_styles() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'sorriso_dambrosio_editor_styles' );

/**
 * Registra la categoria di pattern usata dalle sezioni della homepage.
 *
 * @return void
 */
function sorriso_dambrosio_register_pattern_category() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'sorriso-dambrosio',
		array(
			'label'       => __( 'Sorriso D\'Ambrosio', 'sorriso-dambrosio' ),
			'description' => __( 'Sezioni della homepage dello Studio Odontoiatrico D\'Ambrosio.', 'sorriso-dambrosio' ),
		)
	);
}
add_action( 'init', 'sorriso_dambrosio_register_pattern_category' );

/**
 * Markup dell'intestazione di sezione: occhiello, titolo e filetto.
 *
 * L'occhiello e' un paragrafo, non un heading: nel tema precedente era un
 * secondo <h2> accanto al titolo, cosa che produceva due h2 per sezione.
 * Il testo resta identico, cambia solo l'elemento che lo porta.
 *
 * @param string $eyebrow Testo dell'occhiello.
 * @param string $title   Titolo della sezione.
 * @param int    $level   Livello del titolo (2 o 3).
 * @return string Markup a blocchi.
 */
function sorriso_dambrosio_section_head( $eyebrow, $title, $level = 2 ) {
	$level = (int) $level;
	$tag   = 'h' . $level;

	$heading_attrs = 2 === $level ? '{"level":2}' : '{"level":' . $level . '}';

	return '<!-- wp:group {"align":"wide","className":"sd-section-head","style":{"spacing":{"blockGap":"0.5rem","margin":{"bottom":"var:preset|spacing|medium"}}},"layout":{"type":"constrained"}} -->'
		. '<div class="wp-block-group alignwide sd-section-head" style="margin-bottom:var(--wp--preset--spacing--medium)">'
		. '<!-- wp:paragraph {"className":"sd-eyebrow","textColor":"primary","fontSize":"x-small"} -->'
		. '<p class="sd-eyebrow has-primary-color has-text-color has-x-small-font-size">' . $eyebrow . '</p>'
		. '<!-- /wp:paragraph -->'
		. '<!-- wp:heading ' . $heading_attrs . ' -->'
		. '<' . $tag . ' class="wp-block-heading">' . $title . '</' . $tag . '>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:html --><div class="sd-rule" aria-hidden="true"></div><!-- /wp:html -->'
		. '</div><!-- /wp:group -->';
}

/**
 * Markup di un blocco immagine a partire da un allegato della Media Library.
 *
 * Usa wp_get_attachment_image() cosi' srcset, dimensioni e lazy loading
 * sono generati da WordPress e l'URL si adatta al dominio dell'ambiente
 * (locale, staging, produzione) senza doverlo riscrivere.
 *
 * @param int    $id        ID dell'allegato.
 * @param string $size      Dimensione registrata.
 * @param string $classname Classi aggiuntive sul figure.
 * @param array  $attr      Attributi extra per il tag img.
 * @return string Markup a blocchi, stringa vuota se l'allegato non esiste.
 */
function sorriso_dambrosio_image( $id, $size = 'large', $classname = '', $attr = array() ) {
	$id = (int) $id;

	if ( ! wp_attachment_is_image( $id ) ) {
		return '';
	}

	$img = wp_get_attachment_image( $id, $size, false, $attr );

	if ( '' === $img ) {
		return '';
	}

	$figure_class = trim( 'wp-block-image size-' . $size . ' ' . $classname );

	return '<!-- wp:image {"id":' . $id . ',"sizeSlug":"' . $size . '","linkDestination":"none"'
		. ( '' !== $classname ? ',"className":"' . esc_attr( $classname ) . '"' : '' )
		. '} --><figure class="' . esc_attr( $figure_class ) . '">' . $img . '</figure><!-- /wp:image -->';
}

/**
 * URL di un allegato, con fallback a stringa vuota.
 *
 * @param int    $id   ID dell'allegato.
 * @param string $size Dimensione registrata.
 * @return string
 */
function sorriso_dambrosio_image_url( $id, $size = 'full' ) {
	$url = wp_get_attachment_image_url( (int) $id, $size );

	return is_string( $url ) ? $url : '';
}
