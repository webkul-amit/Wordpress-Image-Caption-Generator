<?php
/**
 * File Handler.
 *
 * @since 1.0.0
 *
 * @package Image Caption Generator
 */

namespace WPICG\Includes;

defined( 'ABSPATH' ) || exit;

/**
 * File handler class.
 */
class WPICG_File_Handler {

	/**
	 * File handler construct.
	 */
	public function __construct() {
		if ( is_admin() ) {
			new WPICG_Admin();
		} 
		new WPICG_Ajax();
	}
}
