<?php
/* gdpr-compliance support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gracioza_gdpr_theme_setup9')) {
	add_action( 'after_setup_theme', 'gracioza_gdpr_theme_setup9', 9 );
	function gracioza_gdpr_theme_setup9() {
		if (is_admin()) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins',		'gracioza_gdpr_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gracioza_exists_wp_gdpr_compliance' ) ) {
	function gracioza_exists_wp_gdpr_compliance() {
		return defined( 'WP_GDPR_C_ROOT_FILE' ) || defined( 'WPGDPRC_ROOT_FILE' );
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gracioza_gdpr_tgmpa_required_plugins' ) ) {
	
	function gracioza_gdpr_tgmpa_required_plugins($list=array()) {
            $list[] = array(
                'name' 		=> gracioza_storage_get_array('required_plugins', 'wp-gdpr-compliance'),
                'slug' 		=> 'wp-gdpr-compliance',
                'required' 	=> false
            );
		return $list;
	}
}

