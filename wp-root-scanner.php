<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://github.com/wppcprajapat
 * @since             1.0.0
 * @package           Wp_Root_Scanner
 *
 * @wordpress-plugin
 * Plugin Name:       WP Root Scanner
 * Plugin URI:        https://https://github.com/wppcprajapat
 * Description:       Scans the root directory of the WordPress installation and lists the files and directories on a custom admin page.
 * Version:           1.0.0
 * Author:            PCPrajapat
 * Author URI:        https://https://github.com/wppcprajapat/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-root-scanner
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
define( 'WP_ROOT_SCANNER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-root-scanner-activator.php
 */
function activate_wp_root_scanner() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-root-scanner-activator.php';
	Wp_Root_Scanner_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-root-scanner-deactivator.php
 */
function deactivate_wp_root_scanner() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-root-scanner-deactivator.php';
	Wp_Root_Scanner_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_root_scanner' );
register_deactivation_hook( __FILE__, 'deactivate_wp_root_scanner' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-root-scanner.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_root_scanner() {

	$plugin = new Wp_Root_Scanner();
	$plugin->run();
}
run_wp_root_scanner();
