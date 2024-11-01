<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Fired during plugin activation
 *
 * @link       https://blk-canvas.com
 * @since      1.0.0
 *
 * @package    The_Looper
 * @subpackage The_Looper/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    The_Looper
 * @subpackage The_Looper/includes
 * @author     Henzly Meghie <henzlym@blk-canvas.com>
 */
class The_Looper_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		flush_rewrite_rules();
		
		$default = array();

		if ( ! get_option( 'the_looper_manage_cpt') ) :

			update_option( 'the_looper_manage_cpt', $default );
			
		endif;

		if ( ! get_option( 'the_looper_manage_taxonomies') ) :

			update_option( 'the_looper_manage_taxonomies', $default );
			
		endif;

		if ( ! get_option( 'the_looper_manage_templates') ) :
			
			update_option( 'the_looper_manage_templates', $default );
			
		endif;


	}

}
