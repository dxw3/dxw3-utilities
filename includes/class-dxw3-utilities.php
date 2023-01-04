<?php

class Dxw3_Utilities {

	protected $loader;
	protected $plugin_name;
	protected $version;
	protected $author;

	public function __construct() {
		
		$this->plugin_name = DXW3_NAME;
		$this->version = DXW3_VERSION;
		$this->author = DXW3_AUTHOR;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dxw3-utilities-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dxw3-utilities-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dxw3-utilities-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dxw3-utilities-public.php';
		$this->loader = new Dxw3_Utilities_Loader();
	}

	private function set_locale() {
		$plugin_i18n = new Dxw3_Utilities_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {
		$plugin_admin = new Dxw3_Utilities_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_author() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'dxw3_utilities_menu');

		$this->loader->add_action( 'wp_ajax_enabled_plugins', $plugin_admin, 'dxw3_save_enabled_plugins' );

		$this->loader->add_filter( 'all_plugins', $plugin_admin, 'dxw3_hide_plugins', 1, 99 );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'dxw3_action_links', 10, 2 );
	}

	private function define_public_hooks() {
		$plugin_public = new Dxw3_Utilities_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

	public function get_plugin_author() {
		return $this->author;
	}

}
