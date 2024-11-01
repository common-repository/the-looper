<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://blk-canvas.com
 * @since             1.0.0
 * @package           The_Looper
 *
 * @wordpress-plugin
 * Plugin Name:       The Looper
 * Plugin URI:        https://blk-canvas.com
 * Description:       Intuitive and friendly Admin UI that will create, edit and delete custom post types as well as custom taxonomies.
 * Version:           1.2
 * Author:            Henzly Meghie
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       the-looper
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'The_Looper', '1.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-the-looper-activator.php
 */
function activate_the_looper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-looper-activator.php';
	The_Looper_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-the-looper-deactivator.php
 */
function deactivate_the_looper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-looper-deactivator.php';
	The_Looper_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_the_looper' );
register_deactivation_hook( __FILE__, 'deactivate_the_looper' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-the-looper.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_the_looper() {

	$plugin = new The_Looper();
	$plugin->run();

}
run_the_looper();
