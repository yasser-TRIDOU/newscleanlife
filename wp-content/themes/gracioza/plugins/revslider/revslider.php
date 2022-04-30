<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gracioza_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'gracioza_revslider_theme_setup9', 9 );
	function gracioza_revslider_theme_setup9() {
		if (gracioza_exists_revslider()) {
			add_action( 'wp_enqueue_scripts', 					'gracioza_revslider_frontend_scripts', 1100 );
			add_filter( 'gracioza_filter_merge_styles',			'gracioza_revslider_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins','gracioza_revslider_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gracioza_revslider_tgmpa_required_plugins' ) ) {
	
	function gracioza_revslider_tgmpa_required_plugins($list=array()) {
		if (gracioza_storage_isset('required_plugins', 'revslider')) {
			$path = gracioza_get_file_dir('plugins/revslider/revslider.zip');
			if (!empty($path) || gracioza_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> gracioza_storage_get_array('required_plugins', 'revslider'),
					'slug' 		=> 'revslider',
					'version'	=> '6.5.11',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'gracioza_exists_revslider' ) ) {
	function gracioza_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'gracioza_revslider_frontend_scripts' ) ) {
	
	function gracioza_revslider_frontend_scripts() {
		if (gracioza_is_on(gracioza_get_theme_option('debug_mode')) && gracioza_get_file_dir('plugins/revslider/revslider.css')!='')
			wp_enqueue_style( 'gracioza-revslider',  gracioza_get_file_url('plugins/revslider/revslider.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'gracioza_revslider_merge_styles' ) ) {
	
	function gracioza_revslider_merge_styles($list) {
		$list[] = 'plugins/revslider/revslider.css';
		return $list;
	}
}
?>