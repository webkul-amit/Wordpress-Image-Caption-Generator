<?php
/**
 * Ajax Hooks.
 *
 * @package Image Caption Generator
 * @since 1.0.0
 */

namespace WPICG\Includes;
use WPICG\Includes\WPICG_Media_Batch;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPICG_Ajax' ) ) {
	/**
	 * Ajax hooks class.
	 */
	class WPICG_Ajax {
		/**
		 * Ajax hooks construct.
		 */
		public function __construct() {
			add_action( 'wp_ajax_icg_process_next_batch_item', array( $this, 'process_next_item' ) );
			add_action( 'wp_ajax_icg_process_item_caption', array( $this, 'update_caption' ) );
		}

        /**
         * This is used to handle the processing of each item
         * and return the status to inform the user.
         */
        public function process_next_item() {
            // Check ajax referrer
            if ( !check_ajax_referer( 'wp-icg', 'nonce', false ) ) {
                wp_send_json_error( array(
                    'message' => 'Permission denied.',
                ) );
                exit();
            }
            $paged = $_POST['paged']?intval($_POST['paged']):''; 
            if(!empty($paged)) {
                // Get the batch object
                $media = new WPICG_Media_Batch();
                $next_item = $media->setup($paged);
                if ( !empty($next_item) ) {
                    wp_send_json_success( array(
                        'message'         => __( 'Processing finished.', 'wp-icg' ),
                        'data'     => $next_item,
                    ) );
                   
                } else {
                    $error_message = __( 'Error processing data ', 'wp-icg');
                    wp_send_json_error( array(
                        'message'         => $error_message,
                        'data'      => [],
                    ) );
                    exit;
                }
            }
        }

        /**
         * This is used to update image caption
         * and return the status to inform the user.
         */
        public function update_caption() {
            // Check ajax referrer
            if ( !check_ajax_referer( 'wp-icg', 'nonce', false ) ) {
                wp_send_json_error( array(
                    'message' => 'Permission denied.',
                ) );
                exit();
            }
            $item = $_POST['item']?$_POST['item']:''; 
            if(!empty($item)) {
                // Get the batch object
                $media = new WPICG_Media_Batch();
                $response = $media->update($item);
                if ( !empty($response) ) {
                    wp_send_json_success( array(
                        'message'         => __( 'Processing finished.', 'wp-icg' ),
                        'data'     => true,
                    ) );
                   
                } else {
                    $error_message = __( 'Error processing data ', 'wp-icg');
                    wp_send_json_error( array(
                        'message'         => $error_message,
                        'data'      => false,
                    ) );
                    exit;
                }
            }
        }

	}
}
