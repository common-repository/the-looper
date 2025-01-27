<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Fired during plugin deactivation
 *
 * @link       https://blk-canvas.com
 * @since      1.0.0
 *
 * @package    The_Looper
 * @subpackage The_Looper/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    The_Looper
 * @subpackage The_Looper/includes
 * @author     Henzly Meghie <henzlym@blk-canvas.com>
 */
class The_Looper_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		flush_rewrite_rules();
		
	}

}
