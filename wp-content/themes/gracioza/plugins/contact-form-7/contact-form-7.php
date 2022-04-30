<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gracioza_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'gracioza_cf7_theme_setup9', 9 );
	function gracioza_cf7_theme_setup9() {
		if (gracioza_exists_cf7()) {
			add_action( 'wp_enqueue_scripts',							'gracioza_cf7_frontend_scripts', 1100 );
			add_filter( 'gracioza_filter_merge_styles',					'gracioza_cf7_merge_styles');
		}
		if (is_admin()) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins',		'gracioza_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gracioza_cf7_tgmpa_required_plugins' ) ) {
	
	function gracioza_cf7_tgmpa_required_plugins($list=array()) {
		if (gracioza_storage_isset('required_plugins', 'contact-form-7')) {
			$list[] = array(
				'name' 		=> gracioza_storage_get_array('required_plugins', 'contact-form-7'),
				'slug' 		=> 'contact-form-7',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gracioza_exists_cf7' ) ) {
	function gracioza_exists_cf7() {
		return class_exists('WPCF7');
	}
}

// Custom styles and scripts
//------------------------------------------------------------------------
// Enqueue custom styles

if ( !function_exists( 'gracioza_cf7_frontend_scripts' ) ) {
	
	function gracioza_cf7_frontend_scripts() {
		if (gracioza_exists_cf7()) {
			if (gracioza_is_on(gracioza_get_theme_option('debug_mode')) && gracioza_get_file_dir('plugins/contact-form-7/contact-form-7.css')!='')
				wp_enqueue_style( 'gracioza-contact-form-7',  gracioza_get_file_url('plugins/contact-form-7/contact-form-7.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'gracioza_cf7_merge_styles' ) ) {
	
	function gracioza_cf7_merge_styles($list) {
		$list[] = 'plugins/contact-form-7/contact-form-7.css';
		return $list;
	}
}


?>