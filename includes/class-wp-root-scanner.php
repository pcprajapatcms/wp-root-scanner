<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://github.com/wppcprajapat
 * @since      1.0.0
 *
 * @package    Wp_Root_Scanner
 * @subpackage Wp_Root_Scanner/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Root_Scanner
 * @subpackage Wp_Root_Scanner/includes
 * @author     PCPrajapat <dev.pcprajapat@gmail.com>
 */
class Wp_Root_Scanner {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Root_Scanner_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		global $wpdb;
		if ( defined( 'WP_ROOT_SCANNER_VERSION' ) ) {
			$this->version = WP_ROOT_SCANNER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-root-scanner';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->table_name = $wpdb->prefix . 'root_scan_results';
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'screen_option' ) );
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-wp-root-scanner-list-table.php';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Root_Scanner_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Root_Scanner_i18n. Defines internationalization functionality.
	 * - Wp_Root_Scanner_Admin. Defines all hooks for the admin area.
	 * - Wp_Root_Scanner_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wp-root-scanner-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wp-root-scanner-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-wp-root-scanner-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-wp-root-scanner-public.php';

		$this->loader = new Wp_Root_Scanner_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Root_Scanner_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Root_Scanner_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Root_Scanner_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Root_Scanner_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Root_Scanner_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}



	private $table_name;
	private $list_table;


	public function add_menu() {
		$hook = add_menu_page( 'Root Scanner', 'Root Scanner', 'manage_options', 'wp-root-scanner', array( $this, 'admin_page' ), 'dashicons-search' );
		add_action( "load-$hook", array( $this, 'screen_option' ) );
	}

	public function screen_option() {
		$option = 'per_page';
		$args   = array(
			'label'   => 'Results per page',
			'default' => 10,
			'option'  => 'results_per_page',
		);
		add_screen_option( $option, $args );

		$this->list_table = new WP_Root_Scanner_List_Table( $this->table_name );
	}

	public function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-root-scanner' ) );
		}

		echo '<div class="wrap"><h1>Root Scanner</h1>';

		if ( isset( $_POST['scan'] ) ) {
			$this->scan_directory();
		}

		$this->display_results();
		echo '</div>';
	}

	private function scan_directory() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'root_scan_results';

		// Check if the table exists

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) !== $table_name ) {
			$this->create_table();
		}

		$sql = "TRUNCATE TABLE `{$table_name}`";
		$wpdb->query( $sql );

		$root_dir = ABSPATH;
		$results  = $this->recursive_scan( $root_dir );

		foreach ( $results as $result ) {
			$wpdb->insert(
				$this->table_name,
				array(
					'type'        => $result['type'],
					'size'        => $result['size'],
					'nodes'       => $result['nodes'],
					'path'        => $result['path'],
					'name'        => $result['name'],
					'extension'   => $result['extension'],
					'permissions' => $result['permissions'],
				)
			);
		}
	}

	private function recursive_scan( $dir ) {
		$results = array();
		$files   = array_diff( scandir( $dir ), array( '.', '..' ) );

		foreach ( $files as $file ) {
			$path      = $dir . DIRECTORY_SEPARATOR . $file;
			$type      = is_dir( $path ) ? 'directory' : 'file';
			$size      = 'file' === $type ? $this->format_size( filesize( $path ) ) : '';
			$nodes     = 'directory' === $type ? count( scandir( $path ) ) - 2 : 0;
			$extension = 'file' === $type ? pathinfo( $path, PATHINFO_EXTENSION ) : '';

			$permissions = substr( sprintf( '%o', fileperms( $path ) ), -4 );

			$results[] = array(
				'type'        => $type,
				'size'        => $size,
				'nodes'       => $nodes,
				'path'        => $path,
				'name'        => $file,
				'extension'   => $extension,
				'permissions' => $permissions,
			);

			if ( 'directory' === $type ) {
				$results = array_merge( $results, $this->recursive_scan( $path ) );
			}
		}

		return $results;
	}

	private function format_size( $size ) {
		$units      = array( 'B', 'KB', 'MB', 'GB', 'TB' );
		$unit_index = 0;

		while ( $size >= 1024 && $unit_index < count( $units ) - 1 ) {
			$size /= 1024;
			++$unit_index;
		}

		return round( $size, 2 ) . ' ' . $units[ $unit_index ];
	}

	private function display_results() {
		$this->list_table->prepare_items();
		$this->list_table->display();
	}
}