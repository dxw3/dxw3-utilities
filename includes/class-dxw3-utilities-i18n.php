<?php
/** 
 *  Internationalization file for the plugin.
 */

class Dxw3_Utilities_i18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			DXW3_NAME,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
