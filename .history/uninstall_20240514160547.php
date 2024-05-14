<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Deletes all plugin author and status information from the database
$utility_plugins = get_option( 'dxw3_utility_plugins' );
foreach( $utility_plugins as $utility_plugin => $utility_plugin_slug ) delete_option( $utility_plugin );
delete_option( 'dxw3_utility_plugins' );
delete_option( 'all_utility_plugins' );
delete_option( 'dxw3_plugins_author' );
