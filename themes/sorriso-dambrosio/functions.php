<?php
/**
 * Sorriso D'Ambrosio — child theme di Ollie.
 *
 * Target PHP 8.3, in locale e in produzione.
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
 * Markup dell'intestazione di sezione.
 *
 * Il filetto corre SOPRA la sezione, da bordo a bordo della colonna, e
 * l'occhiello — che e' contenuto esistente ("Dal 1956", "Dottori",
 * "Prestazioni"...) — si siede in coda al filetto, a destra. Il titolo
 * sta sotto, a sinistra. E' il contrario dell'impianto precedente
 * (occhiello centrato, titolo, filetto rosso sotto), che ripeteva nove
 * volte la stessa figura e faceva somigliare la pagina al vecchio sito.
 *
 * Il filetto vero e' disegnato in CSS con ::before sull'occhiello: cosi'
 * si allunga da solo fino a riempire lo spazio che l'occhiello lascia.
 *
 * @param string $eyebrow Testo dell'occhiello.
 * @param string $title   Titolo della sezione.
 * @param int    $level   Livello del titolo (2 o 3).
 * @param string $href    Se valorizzato, il titolo diventa un collegamento.
 * @return string Markup a blocchi.
 */
function sorriso_dambrosio_section_head( $eyebrow, $title, $level = 2, $href = '' ) {
	$level = (int) $level;
	$tag   = 'h' . $level;

	$inner = '' !== $href
		? '<a href="' . esc_url( $href ) . '">' . $title . '</a>'
		: $title;

	return '<!-- wp:group {"className":"sd-head","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->'
		. '<div class="wp-block-group sd-head">'
		. '<!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->'
		. '<p class="sd-eyebrow has-x-small-font-size">' . $eyebrow . '</p>'
		. '<!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":' . $level . '} -->'
		. '<' . $tag . ' class="wp-block-heading">' . $inner . '</' . $tag . '>'
		. '<!-- /wp:heading -->'
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

/**
 * Precarica il solo font display.
 *
 * Fraunces disegna il titolo dell'hero, che e' il primo elemento di testo
 * visibile: anticiparne il download riduce lo scambio di carattere.
 * DM Sans non viene precaricato per non competere con l'immagine dell'hero.
 *
 * @return void
 */
function sorriso_dambrosio_preload_display_font() {
	$relative = '/assets/fonts/Fraunces-VariableFont_SOFT,WONK,opsz,wght.woff2';

	if ( ! file_exists( get_stylesheet_directory() . $relative ) ) {
		return;
	}

	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_stylesheet_directory_uri() . $relative )
	);
}
add_action( 'wp_head', 'sorriso_dambrosio_preload_display_font', 1 );
