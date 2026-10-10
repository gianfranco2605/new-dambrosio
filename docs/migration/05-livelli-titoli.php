<?php
/**
 * Migrazione 5 — i titoli degli articoli salgono da h3 a h2.
 *
 * Uso:  wp eval-file docs/migration/05-livelli-titoli.php
 *
 * I tre articoli («Magnetic Mallet», «Tooth Transformer», «Impianti Dentali»)
 * hanno il titolo della pagina come `h1` e poi passano direttamente a `h3`:
 * un lettore di schermo che naviga per titoli crede di aver perso una sezione.
 *
 * **Il testo non cambia di una lettera**: cambia solo il livello del titolo,
 * che e' struttura, non contenuto. La grandezza la decide il CSS, non il tag.
 *
 * @package SorrisoDambrosio\Core
 */

$articoli = get_posts(
	array(
		'post_type'   => 'post',
		'post_status' => 'any',
		'numberposts' => -1,
	)
);

$toccati = 0;

foreach ( $articoli as $post ) {
	$nuovo = str_replace(
		array( '<!-- wp:heading {"level":3}', '<h3 class="wp-block-heading"', '</h3>' ),
		array( '<!-- wp:heading', '<h2 class="wp-block-heading"', '</h2>' ),
		$post->post_content
	);

	if ( $nuovo === $post->post_content ) {
		continue;
	}

	wp_update_post(
		array(
			'ID'           => $post->ID,
			'post_content' => $nuovo,
		)
	);

	++$toccati;
	WP_CLI::log( sprintf( '%-6d h3 → h2     %s', $post->ID, $post->post_name ) );
}

WP_CLI::success( 'Articoli sistemati: ' . $toccati );
