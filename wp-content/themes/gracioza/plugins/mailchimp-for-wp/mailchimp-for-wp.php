<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gracioza_mailchimp_theme_setup9')) {
	add_action( 'after_setup_theme', 'gracioza_mailchimp_theme_setup9', 9 );
	function gracioza_mailchimp_theme_setup9() {
		if (gracioza_exists_mailchimp()) {
			add_action( 'wp_enqueue_scripts',							'gracioza_mailchimp_frontend_scripts', 1100 );
			add_filter( 'gracioza_filter_merge_styles',					'gracioza_mailchimp_merge_styles');
		}
		if (is_admin()) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins',		'gracioza_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gracioza_mailchimp_tgmpa_required_plugins' ) ) {
	
	function gracioza_mailchimp_tgmpa_required_plugins($list=array()) {
		if (gracioza_storage_isset('required_plugins', 'mailchimp-for-wp')) {
			$list[] = array(
				'name' 		=> gracioza_storage_get_array('required_plugins', 'mailchimp-for-wp'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gracioza_exists_mailchimp' ) ) {
	function gracioza_exists_mailchimp() {
		return function_exists('__mc4wp_load_plugin') || defined('MC4WP_VERSION');
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue custom styles
if ( !function_exists( 'gracioza_mailchimp_frontend_scripts' ) ) {
	
	function gracioza_mailchimp_frontend_scripts() {
		if (gracioza_exists_mailchimp()) {
			if (gracioza_is_on(gracioza_get_theme_option('debug_mode')) && gracioza_get_file_dir('plugins/mailchimp-for-wp/mailchimp-for-wp.css')!='')
				wp_enqueue_style( 'gracioza-mailchimp-for-wp',  gracioza_get_file_url('plugins/mailchimp-for-wp/mailchimp-for-wp.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'gracioza_mailchimp_merge_styles' ) ) {
	
	function gracioza_mailchimp_merge_styles($list) {
		$list[] = 'plugins/mailchimp-for-wp/mailchimp-for-wp.css';
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (gracioza_exists_mailchimp()) { require_once GRACIOZA_THEME_DIR . 'plugins/mailchimp-for-wp/mailchimp-for-wp.styles.php'; }
?>