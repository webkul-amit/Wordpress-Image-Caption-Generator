<?php
/**
 * Plugin Name: WP Image Caption Generator
 * Description: Image caption generator for WordPress will automatically generate captions for all media via batch processing.
 * version: 1.0.0
 * Author: Amit Chauhan
 * Author URI: https://hcl.com
 * Plugin URI: https://hcl.com/plugins
 * Domain Path: /languages
 * License: GPL-2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-icg
 *
 * WC requires at least: 4.0.0
 * WC tested up to: 5.5.x
 *
 * @package Image Caption Generator
 */

use WPICG\Includes;

defined( 'ABSPATH' ) || exit();

define( 'ICG_PATH', plugin_dir_path( __FILE__ ) );
define( 'ICG_URL', plugin_dir_url( __FILE__ ) );
defined( 'ICG_SCRIPT_VERSION' ) || define( 'ICG_SCRIPT_VERSION', '1.0.0' );

require_once ICG_PATH . 'inc/autoload.php';

add_action( 'plugins_loaded', 'icg_load_plugin' );

/**
 * Load plugin
 *
 * @return void
 */
function icg_load_plugin() {
	load_plugin_textdomain( 'wp-icg', false, basename( dirname( __FILE__ ) ) . '/languages' );
	new Includes\WPICG_File_Handler();
}