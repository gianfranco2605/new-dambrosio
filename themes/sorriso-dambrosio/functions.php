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
 * L'unico JavaScript del tema: il primo Escape sul menu mobile.
 *
 * Sedici righe, nessuna dipendenza, in coda al documento. Tutto il resto del
 * comportamento del menu lo fa gia' il blocco Navigation di core — vedi il
 * commento in cima a assets/js/menu.js per che cosa non fa.
 */
function sorriso_dambrosio_enqueue_menu_script() {
	$path = get_stylesheet_directory() . '/assets/js/menu.js';

	if ( ! file_exists( $path ) ) {
		return;
	}

	wp_enqueue_script(
		'sorriso-dambrosio-menu',
		get_stylesheet_directory_uri() . '/assets/js/menu.js',
		array(),
		(string) filemtime( $path ),
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);
}
add_action( 'wp_enqueue_scripts', 'sorriso_dambrosio_enqueue_menu_script' );

/**
 * Niente `sizes="auto"` sulle immagini pigre.
 *
 * Da WordPress 6.7 ogni immagine `loading="lazy"` esce con
 * `sizes="auto, …"`. Con `auto` il browser sceglie dalla srcset
 * guardando **solo la larghezza resa** e ignora tutto il resto.
 *
 * Qui dentro non va bene: mezzo sito monta le fotografie con
 * `object-fit: cover` dentro riquadri piu' alti che larghi. Un ritratto
 * reso a 335px di larghezza ne chiede 419 di altezza, ma col calcolo
 * sulla sola larghezza il browser si ferma al file da 335x334 e lo
 * ingrandisce del 25%. Misurato sui tre ritratti dei medici.
 *
 * Tolto `auto`, torna a contare il `sizes` scritto, e con esso il
 * rapporto del riquadro.
 *
 * @return bool
 */
function sorriso_dambrosio_no_auto_sizes() {
	return false;
}
add_filter( 'wp_img_tag_add_auto_sizes', 'sorriso_dambrosio_no_auto_sizes' );

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
 * @param string $anchor  Se valorizzato, diventa l'id del titolo (per aria-labelledby).
 * @return string Markup a blocchi.
 */
function sorriso_dambrosio_section_head( $eyebrow, $title, $level = 2, $href = '', $anchor = '' ) {
	$level = (int) $level;
	$tag   = 'h' . $level;

	$inner = '' !== $href
		? '<a href="' . esc_url( $href ) . '">' . $title . '</a>'
		: $title;

	// L'ancora serve a dare un nome accessibile alla <section> che contiene
	// l'intestazione: la sezione la cita con aria-labelledby. Senza nome, per
	// uno screen reader una <section> vale esattamente un <div>.
	$anchor_attr = '' !== $anchor ? '"anchor":"' . esc_attr( $anchor ) . '",' : '';
	$anchor_id   = '' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';

	return '<!-- wp:group {"className":"sd-head","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->'
		. '<div class="wp-block-group sd-head">'
		. '<!-- wp:paragraph {"className":"sd-eyebrow","fontSize":"x-small"} -->'
		. '<p class="sd-eyebrow has-x-small-font-size">' . $eyebrow . '</p>'
		. '<!-- /wp:paragraph -->'
		. '<!-- wp:heading {' . $anchor_attr . '"level":' . $level . '} -->'
		. '<' . $tag . ' class="wp-block-heading"' . $anchor_id . '>' . $inner . '</' . $tag . '>'
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

/**
 * I recapiti dello studio, in un posto solo.
 *
 * Sono gli stessi che il sito pubblica da sempre: header, hero, orari e footer
 * li leggono da qui invece di riscriverli, cosi' non possono divergere.
 *
 * **La fonte vera e' il plugin** (`SD\Core\Studio::contacts()`): i recapiti
 * sono contenuto, e il contenuto deve sopravvivere a un cambio di tema. Qui
 * resta una copia di scorta, che entra in gioco solo se il plugin e' spento:
 * un tema che va in bianco perche' manca un plugin e' un tema fragile.
 *
 * @return array<string, string>
 */
function sorriso_dambrosio_contacts() {
	if ( class_exists( '\\SD\\Core\\Studio' ) ) {
		return \SD\Core\Studio::contacts();
	}

	return array(
		'phone'       => '+39 06 322 5754',
		'phone_href'  => 'tel:063225754',
		'phone2'      => '+39 06 361 0625',
		'phone2_href' => 'tel:063610625',
		'mobile'      => '+39 375 552 4596',
		'mobile_href' => 'tel:+393755524596',
		'email'       => 'studio@sorrisodambrosio.it',
		'email_href'  => 'mailto:studio@sorrisodambrosio.it',
		'address'     => 'Lungotevere Flaminio n. 34 &#8211; 00196 &#8211; Roma (RM)',
		'address_short' => 'Lungotevere Flaminio 34, Roma',
		'map_href'    => 'https://maps.app.goo.gl/jsVVDPiXFAFLCiE18',
		'whatsapp'    => 'https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0',
		'instagram'   => 'https://www.instagram.com/studioodontoiatricodambrosio/',
		'facebook'    => 'https://www.facebook.com/studioodontoiatricodambrosio/',
	);
}

/**
 * Gli orari di apertura, nel formato in cui il sito li pubblica da sempre.
 *
 * La chiave e' il numero del giorno secondo date('N') (1 = lunedi'), cosi' la
 * sezione puo' evidenziare oggi senza doverlo dedurre dal nome.
 *
 * Come per i recapiti, la fonte vera e' il plugin: qui c'e' solo la scorta.
 *
 * @return array<int, array{name: string, morning: string, afternoon: string}>
 */
function sorriso_dambrosio_hours() {
	if ( class_exists( '\\SD\\Core\\Studio' ) ) {
		return \SD\Core\Studio::hours();
	}

	return array(
		1 => array( 'name' => 'Lunedi', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
		2 => array( 'name' => 'Martedi', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => 'Chiuso' ),
		3 => array( 'name' => 'Mercoled&igrave;', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
		4 => array( 'name' => 'Gioved&igrave;', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
		5 => array( 'name' => 'Venerd&igrave;', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
		6 => array( 'name' => 'Sabato', 'morning' => 'Chiuso', 'afternoon' => 'Chiuso' ),
		7 => array( 'name' => 'Domenica', 'morning' => 'Chiuso', 'afternoon' => 'Chiuso' ),
	);
}

/**
 * Icone di linea, disegnate inline.
 *
 * Inline e non da sprite o da CDN: sono poche, ereditano `currentColor`, non
 * aggiungono una richiesta e rispettano il vincolo "nessuna CDN".
 * Il `viewBox` e' 24: la dimensione resa si governa in CSS, mai sopra i 56px.
 *
 * @param string $name  Nome dell'icona.
 * @param string $class Classi aggiuntive.
 * @return string SVG, stringa vuota se l'icona non esiste.
 */
function sorriso_dambrosio_icon( $name, $class = '' ) {
	$paths = array(
		'mail'      => '<rect x="2.5" y="4.5" width="19" height="15" rx="2.5"/><path d="m3 7 8.3 5.6a1.5 1.5 0 0 0 1.7 0L21 7"/>',
		'phone'     => '<path d="M6.5 3.5h3l1.5 4-2 1.3a12 12 0 0 0 5.2 5.2l1.3-2 4 1.5v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7a2 2 0 0 1 2-2.2Z"/>',
		'pin'       => '<path d="M12 21.5s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11Z"/><circle cx="12" cy="10.5" r="2.75"/>',
		'clock'     => '<circle cx="12" cy="12" r="8.75"/><path d="M12 7.25V12l3 1.75"/>',
		'calendar'  => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
		'facebook'  => '<path d="M14.6 21v-7.6h2.6l.4-3h-3V8.5c0-.9.25-1.5 1.5-1.5H17.7V4.3A20 20 0 0 0 15.4 4.2c-2.3 0-3.9 1.4-3.9 4v2.2H8.9v3h2.6V21Z" fill="currentColor" stroke="none"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1.15" fill="currentColor" stroke="none"/>',
		'whatsapp'  => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0 0 20.465 3.488" fill="currentColor" stroke="none"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$class = trim( 'sd-icon sd-icon--' . $name . ' ' . $class );

	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" width="24" height="24"'
		. ' fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"'
		. ' stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/**
 * Contact Form 7: niente <p> e <br> automatici.
 *
 * Il markup del modulo e' gia' strutturato a `div` con le classi del tema
 * (`.sd-form__row`, `.sd-form__field`...). Se CF7 ci applica `wpautop` sopra,
 * inserisce paragrafi e interruzioni di riga in mezzo alla griglia e la rompe.
 *
 * @return bool
 */
function sorriso_dambrosio_cf7_no_autop() {
	return false;
}
add_filter( 'wpcf7_autop_or_not', 'sorriso_dambrosio_cf7_no_autop' );
