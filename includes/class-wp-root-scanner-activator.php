<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://github.com/wppcprajapat
 * @since      1.0.0
 *
 * @package    Wp_Root_Scanner
 * @subpackage Wp_Root_Scanner/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Root_Scanner
 * @subpackage Wp_Root_Scanner/includes
 * @author     PCPrajapat <dev.pcprajapat@gmail.com>
 */
class Wp_Root_Scanner_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Call the create_table function
		self::create_table();
	}

	private static function create_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . 'your_table_name'; // Adjust table name accordingly
		$sql             = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			type varchar(20) NOT NULL,
			size varchar(20) NOT NULL,
			nodes int NOT NULL,
			path varchar(255) NOT NULL,
			name varchar(255) NOT NULL,
			extension varchar(10) NOT NULL,
			permissions varchar(10) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}
