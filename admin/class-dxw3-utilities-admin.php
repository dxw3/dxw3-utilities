<?php

class Dxw3_Utilities_Admin {

	private $plugin_name;
	private $version;
	private $author;

	public function __construct() {
		$this->plugin_name = DXW3_NAME;
		$this->version = DXW3_VERSION;
		$this->author = get_option( 'dxw3_plugins_author' );														// Get previously saved plugin author name
		
		if( ! function_exists( 'get_plugins' ) ) { 																	// Make sure plugins can be read
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Get and set current plugins initial status
		$utility_plugins = []; $utility_plugins_slugs = []; $enabled_plugins = [];
		$all_plugins = get_plugins();
		foreach( $all_plugins as $key => $plugin ) {
			if( $this->author !== '' && $plugin[ "Author" ] === $this->author && $plugin[ "Name" ] !== $this->plugin_name ) {
				$utility_plugins[ $key ] = $plugin;
				$slug = strtok( $key, '/' );
				$utility_plugins_slugs[ $slug ] = strtok( $key, '/' );
				if( is_plugin_active( $key ) ) $enabled_plugins[] = strtok( $key, '/' );
			}
		}
		update_option( 'all_utility_plugins', $utility_plugins );
		$this->dxw3_loop_enabled_plugins( $utility_plugins_slugs, $enabled_plugins );
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dxw3-utilities-admin.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dxw3-utilities-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'nce', array( 'sec' => wp_create_nonce( 'author_input' ) ) );			// Add nonce for ajax security
	}

	// Selected author's plugins need to be hidden
	public function dxw3_hide_plugins( $all_plugins ) {		
		$hidden = get_option( 'dxw3_utility_plugins' );
		if( ! is_array( $hidden ) ) $hidden = [];
		foreach( $hidden as $slug ) { unset( $all_plugins[ $slug ] ); }		
		return $all_plugins;
	}

	// Show quick toggles under the plugin name
	public function dxw3_show_hide_grouped_plugins( $plugin_file, $plugin_data, $status ) {
		include_once 'partials/dxw3-utilities-admin-display-quick.php';
	}

	// Add settings link and toggeles open/closefor this plugin
	public function dxw3_action_links( $actions, $plugin_file ) {
		if( 'dxw3-utilities/dxw3-utilities.php' === $plugin_file ) {
			$author = $this->get_selected_author();
			$grouped_plugins = $this->get_current_grouped_plugins_files();
			$updates = $this->get_all_update_files();
			if( array_intersect( $grouped_plugins, $updates ) ) $updates_exist = '<div>new updates available</div>';
			else $updates_exist = '';
			$actions[] = '<a href="'. admin_url( 'admin.php?page=dxw3-utilities' ) .'">Settings</a>';
			$actions[] = '<div class="dxw3-toggles">' . $author . ' - <span>open</span><span class="toggles-hidden">close</span> toggles</div>' . $updates_exist;
		}
		return $actions;
	}

	private function get_selected_author() {
		$author = get_option( 'dxw3_plugins_author' );
		return $author;
	}

	private function get_current_grouped_plugins_files() {
		$utility_plugins = [];
		$utility_plugins = is_array( get_option( 'all_utility_plugins' ) ) ? get_option( 'all_utility_plugins' ) : [];
		$utility_plugins = array_keys( $utility_plugins );
		return $utility_plugins;
	}

	// Get the pending updates for all author's plugins
	private function get_all_update_files() {
		$updates = [];

		// Make sure the core admin update function is loaded
		if ( ! function_exists( 'get_plugin_updates' ) ) {
			require_once ABSPATH . 'wp-admin/includes/update.php';
		}

		if ( function_exists( 'get_plugin_updates' ) ) {
			$updates = get_plugin_updates();
			$updates = array_keys( $updates );
		}

		return $updates;
	}

	public function dxw3_utilities_menu() {
		add_menu_page(
			__( 'Grouped Plugins', 'dxw3-utilities' ),
			'Grouped Plugins',
			'manage_options',
			'dxw3-utilities',
			array( $this, 'dxw3_utility_plugins_settings' ),
			'dashicons-dashboard',
			100
		);
	}

	// UI for the separate settings page containing the selected author
	public function dxw3_utility_plugins_settings() {
		include_once 'partials/dxw3-utilities-admin-display.php';
	}

	// Get and save author and plugin status changes by the user in the settings page
	public function dxw3_save_enabled_plugins() {
		check_ajax_referer( 'author_input', 'security' );
		if( isset( $_POST[ 'plugins' ] ) ) {
			$enabled_plugins = []; $utility_plugins = [];
			$utility_plugins = get_option( 'dxw3_utility_plugins' );
			$enabled_plugins = array_map( 'sanitize_text_field', json_decode( stripslashes( $_POST[ 'plugins' ] ) ) );
			$this->dxw3_loop_enabled_plugins( $utility_plugins, $enabled_plugins );
			$author = isset( $_POST[ 'pluginsauthor' ] ) ? sanitize_text_field( $_POST[ 'pluginsauthor' ] ) : get_option( 'dxw3_plugins_author' );
			$refresh = get_option( 'dxw3_plugins_author' ) !== $author ? true : false;
			if( $refresh ) update_option( 'dxw3_plugins_author', $author );
		}
		wp_send_json( $refresh );
	}

	// Return enabled plugins from the list of all author's plugins
	private function dxw3_loop_enabled_plugins( $utility_plugins = [], $enabled_plugins = [] ) {

		foreach( $utility_plugins as $utility_plugin => $utility_plugin_slug ) {
			if( in_array( $utility_plugin, $enabled_plugins ) ) { 
				update_option( $utility_plugin, 1 );
				activate_plugin( $utility_plugin_slug );
			} else {
				update_option( $utility_plugin, 0 );
				deactivate_plugins( $utility_plugin_slug );
			}
		}
	}

}
