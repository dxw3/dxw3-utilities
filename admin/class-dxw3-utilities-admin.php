<?php

class Dxw3_Utilities_Admin {

	private $plugin_name;
	private $version;
	private $pauthor;

	public function __construct() {
		$this->plugin_name = DXW3_NAME;
		$this->version = DXW3_VERSION;
		$this->author = DXW3_AUTHOR;
		
		if( ! function_exists( 'get_plugins' ) ) { 
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$utility_plugins = []; $utility_plugins_slugs = []; $enabled_plugins = [];
		$all_plugins = get_plugins();
		foreach( $all_plugins as $key => $plugin ) {
			if( $plugin[ "Author" ] === $this->author && $plugin[ "Name" ] !== $this->plugin_name ) {
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
	}

	public function dxw3_hide_plugins( $all_plugins ) {		
		$hidden = get_option( 'dxw3_utility_plugins' );
		if( ! is_array( $hidden ) ) $hidden = [];
		foreach( $hidden as $plugin => $slug ) { unset( $all_plugins[ $slug ] ); }		
		return $all_plugins;
	}

	public function dxw3_action_links( $actions, $plugin_file ) {
		if( 'dxw3-utilities/dxw3-utilities.php' === $plugin_file ) {
			$actions[] = '<a href="'. admin_url( 'admin.php?page=dxw3-utilities' ) .'">Settings</a>';
		}
		return $actions;
	}

	public function dxw3_utilities_menu() {
		add_menu_page(
			__( 'dxw3 Utilities', 'dxw3-utilities' ),
			'dxw3 Utilities',
			'manage_options',
			'dxw3-utilities',
			array( $this, 'dxw3_utility_plugins_settings' ),
			'dashicons-dashboard',
			100
		);
	}

	public function dxw3_utility_plugins_settings() {
		include_once 'partials/dxw3-utilities-admin-display.php';
	}

	public function dxw3_save_enabled_plugins() {
		if( isset( $_POST[ 'plugins' ] ) ) {
			$enabled_plugins = []; $utility_plugins = [];
			$utility_plugins = get_option( 'dxw3_utility_plugins' );
			$enabled_plugins = json_decode( stripslashes( $_POST[ 'plugins' ] ) );
			$this->dxw3_loop_enabled_plugins( $utility_plugins, $enabled_plugins );
			$refresh = get_option( 'dxw3_plugins_author' ) !== $_POST[ 'pluginsauthor' ] ? true : false;
			update_option( 'dxw3_plugins_author', $_POST[ 'pluginsauthor' ] );
		}
		wp_send_json( $refresh );
	}

	private function dxw3_loop_enabled_plugins( $utility_plugins = [], $enabled_plugins = [] ) {
		update_option('d',$utility_plugins); update_option('d2',$enabled_plugins);
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
