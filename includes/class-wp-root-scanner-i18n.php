<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://github.com/wppcprajapat
 * @since      1.0.0
 *
 * @package    Wp_Root_Scanner
 * @subpackage Wp_Root_Scanner/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Root_Scanner
 * @subpackage Wp_Root_Scanner/includes
 * @author     PCPrajapat <dev.pcprajapat@gmail.com>
 */
class Wp_Root_Scanner_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-root-scanner',
			false,
			dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
		);
	}
}
