<?php
/**
 * Plugin Name:       Sorriso D'Ambrosio — Contenuti
 * Plugin URI:        https://github.com/gianfranco2605/new-dambrosio
 * Description:       I contenuti dello Studio Odontoiatrico D'Ambrosio: trattamenti, tecnologie, professionisti e casi clinici, piu' gli orari e i recapiti in un posto solo. Qui sta il contenuto; la presentazione sta nel tema.
 * Version:           1.0.0
 * Requires at least: 6.8
 * Requires PHP:      8.3
 * Author:            Studio Odontoiatrico D'Ambrosio
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       sorriso-dambrosio-core
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

const VERSION = '1.0.0';
const FILE    = __FILE__;

require_once __DIR__ . '/includes/class-content-types.php';
require_once __DIR__ . '/includes/class-fields.php';
require_once __DIR__ . '/includes/class-permalinks.php';
require_once __DIR__ . '/includes/class-studio.php';
require_once __DIR__ . '/includes/class-relations.php';
require_once __DIR__ . '/includes/class-render.php';
require_once __DIR__ . '/includes/class-blocks.php';
require_once __DIR__ . '/includes/class-schema.php';

/**
 * Aggancia tutto. Nessun effetto collaterale al caricamento del file:
 * qui si registrano soltanto gli hook.
 */
function bootstrap(): void {
	Content_Types::hooks();
	Fields::hooks();
	Permalinks::hooks();
	Blocks::hooks();
	Schema::hooks();
}

bootstrap();

/**
 * All'attivazione: registrare prima i tipi, poi rigenerare le regole.
 *
 * L'ordine non e' un dettaglio. `flush_rewrite_rules()` serializza le regole
 * esistenti in quel momento: se i tipi non sono ancora registrati, le regole
 * nascono senza di loro e ogni URL nuovo risponde 404 finche' qualcuno non
 * salva di nuovo i permalink.
 */
function activate(): void {
	Content_Types::register();
	Permalinks::add_area_rules();
	flush_rewrite_rules();
}
register_activation_hook( FILE, __NAMESPACE__ . '\\activate' );

/**
 * Alla disattivazione si ripuliscono solo le regole di riscrittura.
 *
 * I contenuti restano: disattivare un plugin non e' chiedere di cancellare
 * trent'anni di trattamenti. Per la stessa ragione non esiste `uninstall.php`.
 */
function deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( FILE, __NAMESPACE__ . '\\deactivate' );
