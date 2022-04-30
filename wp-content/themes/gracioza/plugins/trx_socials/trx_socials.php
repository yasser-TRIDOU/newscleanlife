<?php
/* ThemeREX Socials support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'gracioza_trx_socials_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'gracioza_trx_socials_theme_setup9', 9 );
	function gracioza_trx_socials_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins', 'gracioza_trx_socials_tgmpa_required_plugins' );
		}
	}
}


// Filter to add in the required plugins list
if ( ! function_exists( 'gracioza_trx_socials_tgmpa_required_plugins' ) ) {
	function gracioza_trx_socials_tgmpa_required_plugins($list=array()) {
		if (gracioza_storage_isset('required_plugins', 'trx_updater')) {
			$path = gracioza_get_file_dir('plugins/trx_socials/trx_socials.zip');
			if (!empty($path) || gracioza_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> gracioza_storage_get_array('required_plugins', 'trx_socials'),
					'slug'     => 'trx_socials',
					'version'  => '1.4.3',
					'source'   => !empty( $path ) ? $path : 'upload://trx_socials.zip',
					'required' => false,
				);
			}	
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gracioza_exists_trx_socials' ) ) {
    function gracioza_exists_trx_socials() {
        return function_exists( 'trx_socials_load_plugin_textdomain' );
    }
}

// Add thumb sizes
if ( !function_exists( 'gracioza_socials_filter_add_thumb_sizes' ) ) {
	add_filter( 'trx_socials_filter_add_thumb_sizes', 'gracioza_socials_filter_add_thumb_sizes' );
	function gracioza_socials_filter_add_thumb_sizes() {
		return array(
			'trx_socials-thumb-avatar-extra' => array(380, 380, true),
		);
	}
}