<?php
/**
 * File Handler.
 *
 * @since 1.0.0
 *
 * @package Image Caption Generator
 */

namespace WPICG\Includes;

use WPICG\Templates\WPICG_Manage_Batch;
use WPICG\Includes\WPICG_Media_Batch;

defined( 'ABSPATH' ) || exit;

/**
 * Class WPICG_Admin
 */
class WPICG_Admin {

	/**
	 * Contructor function
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueues admin scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_script( 'icg-tensorflow', '//cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.0.1' );
		wp_register_script( 'icg-tensorflow-models', '//cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet@1.0.0' );
		wp_enqueue_script(
			'wp-icg',
			ICG_URL . 'assets/js/plugin.js',
			array( 'jquery', 'icg-tensorflow', 'icg-tensorflow-models' ),
			ICG_SCRIPT_VERSION,
			true
		);
		wp_enqueue_style(
			'wp-icg-style',
			ICG_URL . 'assets/css/style.css',
			array(),
			ICG_SCRIPT_VERSION,
		);
		wp_localize_script( 'wp-icg', 'icg', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'wp-icg' ),
			'delay'    => apply_filters( 'wp_batch_processing_delay', 0 ), // Set delay in seconds before processing the next item. Default 0. No delay.
			'text'     => array(
				'processing' => __( 'Processing...', 'wp-icg' ),
				'start'      => __( 'Start', 'wp-icg' ),
			)
		) );
	}

	/**
	 * Add menu items
	 *
	 * @return void
	 */
	public function admin_menu() {

		add_menu_page(
			__( 'Media Caption Generator', 'wp-icg' ),
			__( 'Media Caption Generator ', 'wp-icg' ),
			'manage_options', 'caption-generator',
			array( $this, 'caption_generator_callback' ),
			'dashicons-format-gallery', null
		); 
	}

	/**
	 * Handles the plugin page
	 *
	 * @return void
	 */
	public function caption_generator_callback() {
		$media = new WPICG_Media_Batch();
		$obj = new WPICG_Manage_Batch($media);
		$obj->template();
	}
 
}