<?php
/**
 * Main class.
 *
 * @package Image Caption Generator
 * @version 1.0.0
 */

namespace WPICG\Includes;

defined( 'ABSPATH' ) || exit();

/**
 * Class WPICG_Media_Batch
 */
class WPICG_Media_Batch {

	/**
	 * Unique identifier of each batch
	 * @var string
	 */
	public $id = 'email_post_authors';

	/**
	 * Describe the batch
	 * @var string
	 */
	public $title = 'Email Post Authors';

	/**
	 * To setup the batch data to the queue.
	 *
	 * Note: If the operation of obtaining data is expensive, cache it to avoid slowdowns.
	 *
	 * @return void
	 */
	public function setup($paged) {
		global $wpdb;
		$postsPerPage = 5;
		$offset = 0;
		error_log( print_r( $paged, true ) );
		error_log( print_r( $offset, true ) );
		$media_list = $wpdb->get_results( $wpdb->prepare( "SELECT ID, guid FROM {$wpdb->prefix}posts WHERE post_type=%s AND post_excerpt ='' LIMIT %d OFFSET %d  ", 'attachment', $postsPerPage, $offset ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query is safe, see above.
		return $media_list;
	}

	/**
	 * To get total count.
	 *
	 * @return int
	 */
	public function get_count() {
		global $wpdb;
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type=%s AND post_excerpt='' ", 'attachment' )); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query is safe, see above.
		return $count;
	}

	/**
	 * Update attachment.
	 *
	 * @return int
	 */
	public function update($item) {
		$id = !empty($item['id']) ? intval($item['id']) : '';
		$caption = !empty($item['caption']) ? $item['caption'] : '';
		$post    = get_post( $id, ARRAY_A );
		$post['post_excerpt'] = $caption;
		wp_update_post( $post );
		return true;
	}

}


