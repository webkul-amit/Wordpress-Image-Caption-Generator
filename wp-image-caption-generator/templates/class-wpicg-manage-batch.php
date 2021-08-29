<?php
/**
 * Batch View
 *
 * @package Image Caption Generator
 * @version 1.0.0
 */

namespace WPICG\Templates;

defined( 'ABSPATH' ) || exit();

/**
 * Class WP_Batch_Processor
 */
class WPICG_Manage_Batch { 

	protected $batch;

	/**
	 * Contructor function
	 */
	public function __construct($batch) {
		$this->batch = $batch->get_count();
	}

	/**
	 * template function
	 */
	public function template() {
		?>
		<h1><?php echo __('Media Caption Generator', 'wp-icg'); ?></a></h1>
		<div class="batch-process">
			<div class="batch-process-main">
				<ul class="batch-process-stats">
					<li><strong><?php echo __('Total:', 'wp-icg'); ?></strong> <span id="batch-process-total"><?php echo $this->batch; ?></span></li>
					<li><strong><?php echo __('Processed:', 'wp-icg'); ?></strong> <span id="batch-process-processed"><?php echo '0'; ?></span> <span id="batch-process-percentage">(<?php echo '0'; ?>%)</span></li>
				</ul>
				<div class="batch-process-progress-bar">
					<?php
					$style = '';
					?>
					<div class="batch-process-progress-bar-inner" style="<?php echo $style; ?>"></div>
				</div>
				<div class="batch-process-current-item">
				</div>
			</div>
			<div class="batch-process-actions">
				<button class="button-primary" id="batch-process-start"><?php echo __('Start', 'wp-icg'); ?></button>
				<button class="button" id="batch-process-stop"><?php echo __('Stop', 'wp-icg'); ?></button>
			</div>
		</div>
		<?php
	}
} 