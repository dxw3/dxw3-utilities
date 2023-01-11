<?php
/**
 * Plugin Name:       dxw3 Utilities
 * Plugin URI:        https://dx-w3.com/wordpress-plugins/
 * Description:       Group all the plugins of the same author in one.
 * Version:           1.0.0
 * Author:            dxw3
 * Author URI:        https://dx-w3.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dxw3-utilities
 * Domain Path:       /languages
 */

if( ! defined( 'WPINC' ) ) { die; }
if( ! function_exists( 'get_plugin_data' ) ) { require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }
$nme = get_plugin_data( __FILE__ )[ 'Name' ]; // Plugin name
$ver = get_plugin_data( __FILE__ )[ 'Version' ]; // Plugin version
$ath = get_option( 'dxw3_plugins_author' ) ? get_option( 'dxw3_plugins_author' ) : get_plugin_data( __FILE__ )[ 'Author' ]; // Author field of the plugin determines if the plugin will be grouped

defined( 'DXW3_NAME' ) or define( 'DXW3_NAME', $nme );
defined( 'DXW3_VERSION' ) or define( 'DXW3_VERSION', $ver );
defined( 'DXW3_AUTHOR' ) or define( 'DXW3_AUTHOR', strip_tags( $ath ) );

function activate_dxw3_utilities() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dxw3-utilities-activator.php';
	Dxw3_Utilities_Activator::activate();
}

function deactivate_dxw3_utilities() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dxw3-utilities-deactivator.php';
	Dxw3_Utilities_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dxw3_utilities' );
register_deactivation_hook( __FILE__, 'deactivate_dxw3_utilities' );

require plugin_dir_path( __FILE__ ) . 'includes/class-dxw3-utilities.php';


function run_dxw3_utilities() {

	$plugin = new Dxw3_Utilities();
	$plugin->run();

}
run_dxw3_utilities();
