<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WP_Root_Scanner_List_Table' ) ) {
	class WP_Root_Scanner_List_Table extends WP_List_Table {

		private $table_name;

		public function __construct( $table_name ) {
			parent::__construct(
				array(
					'singular' => __( 'Scan Result', 'wp-root-scanner' ),
					'plural'   => __( 'Scan Results', 'wp-root-scanner' ),
					'ajax'     => false,
				)
			);
			$this->table_name = $table_name;
		}

		public function get_columns() {
			$columns = array(
				'cb'          => '<input type="checkbox" />',
				'type'        => __( 'Type', 'wp-root-scanner' ),
				'size'        => __( 'Size', 'wp-root-scanner' ),
				'nodes'       => __( 'Nodes', 'wp-root-scanner' ),
				'path'        => __( 'Path', 'wp-root-scanner' ),
				'name'        => __( 'Name', 'wp-root-scanner' ),
				'extension'   => __( 'Extension', 'wp-root-scanner' ),
				'permissions' => __( 'Permissions', 'wp-root-scanner' ),
			);
			return $columns;
		}

		public function get_sortable_columns() {
			$sortable_columns = array(
				'type'        => array( 'type', false ),
				'size'        => array( 'size', false ),
				'nodes'       => array( 'nodes', false ),
				'name'        => array( 'name', false ),
				'extension'   => array( 'extension', false ),
				'permissions' => array( 'permissions', false ),
			);
			return $sortable_columns;
		}

		public function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'type':
				case 'size':
				case 'nodes':
				case 'path':
				case 'name':
				case 'extension':
				case 'permissions':
					return $item[ $column_name ];
				default:
					return print_r( $item, true );
			}
		}

		public function column_cb( $item ) {
			return sprintf( '<input type="checkbox" name="ID[]" value="%s" />', $item['id'] );
		}

		public function prepare_items() {
			global $wpdb;

			$per_page     = $this->get_items_per_page( 'results_per_page', 10 );
			$current_page = $this->get_pagenum();
			$offset       = ( $current_page - 1 ) * $per_page;

			// Check if the table exists
			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$this->table_name}'" ) !== $this->table_name ) {
				$this->recreate_table();
			}

			// Escaping the table name manually
			$table_name = esc_sql( $this->table_name );

			// Building the total items query without using prepare
			$total_items_query = "SELECT COUNT(*) FROM `$table_name`";
			$total_items       = $wpdb->get_var( $total_items_query );

			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'per_page'    => $per_page,
				)
			);

			$columns               = $this->get_columns();
			$hidden                = array();
			$sortable              = $this->get_sortable_columns();
			$this->_column_headers = array( $columns, $hidden, $sortable );

			// Using wpdb->prepare correctly for data retrieval with placeholders
			$data_query  = $wpdb->prepare( "SELECT * FROM `$table_name` LIMIT %d OFFSET %d", $per_page, $offset );
			$data        = $wpdb->get_results( $data_query, ARRAY_A );
			$this->items = $data;
		}

		protected function extra_tablenav( $which ) {
			if ( 'top' !== $which ) {
				return;
			}
			echo '
            <div class="alignleft actions">
                <form method="post" action="">
                    <input type="submit" name="scan" value="Scan Root Directory" class="button button-primary" />
                </form>
            </div>';
		}

		private function recreate_table() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $this->table_name (
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
}
