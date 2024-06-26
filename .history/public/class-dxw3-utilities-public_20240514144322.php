<?php
/**
 * Public-facing functionality of the plugin.
 * Public files included in the plugin for future use only.
 */

class Dxw3_Utilities_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dxw3-utilities-public.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dxw3-utilities-public.js', array( 'jquery' ), $this->version, false );
	}

}
