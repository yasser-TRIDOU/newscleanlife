<?php
/* TRX Updater support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gracioza_trx_updater_theme_setup9')) {
	add_action( 'after_setup_theme', 'gracioza_trx_updater_theme_setup9', 9 );
	function gracioza_trx_updater_theme_setup9() {
		if (is_admin()) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins','gracioza_trx_updater_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gracioza_trx_updater_tgmpa_required_plugins' ) ) {
	
	function gracioza_trx_updater_tgmpa_required_plugins($list=array()) {
		if (gracioza_storage_isset('required_plugins', 'trx_updater')) {
			$path = gracioza_get_file_dir('plugins/trx_updater/trx_updater.zip');
			if (!empty($path) || gracioza_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> gracioza_storage_get_array('required_plugins', 'trx_updater'),
					'slug' 		=> 'trx_updater',
					'version'	=> '1.9.6',
					'source'	=> !empty($path) ? $path : 'upload://trx_updater.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( !function_exists( 'gracioza_exists_trx_updater' ) ) {
	function gracioza_exists_trx_updater() {
		return function_exists( 'trx_updater_load_plugin_textdomain' );
	}
}
?>