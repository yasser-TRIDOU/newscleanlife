<?php
/* Calculate Fields Form support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('gracioza_calculated_fields_form_theme_setup9')) {
	add_action( 'after_setup_theme', 'gracioza_calculated_fields_form_theme_setup9', 9 );
	function gracioza_calculated_fields_form_theme_setup9() {
		if (gracioza_exists_calculated_fields_form()) {
			add_action( 'wp_enqueue_scripts', 							'gracioza_calculated_fields_form_frontend_scripts', 1100 );
			add_filter( 'gracioza_filter_merge_styles',					'gracioza_calculated_fields_form_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins',		'gracioza_calculated_fields_form_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gracioza_calculated_fields_form_tgmpa_required_plugins' ) ) {
	
	function gracioza_calculated_fields_form_tgmpa_required_plugins($list=array()) {
		if (gracioza_storage_isset('required_plugins', 'calculated-fields-form')) {
			$list[] = array(
					'name' 		=> gracioza_storage_get_array('required_plugins', 'calculated-fields-form'),
					'slug' 		=> 'calculated-fields-form',
					'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'gracioza_exists_calculated_fields_form' ) ) {
	function gracioza_exists_calculated_fields_form() {
		return class_exists('CP_SESSION') || class_exists( 'CPCFF_MAIN' ) || defined( 'CP_CALCULATEDFIELDSF_VERSION' );
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'gracioza_calculated_fields_form_frontend_scripts' ) ) {
	
	function gracioza_calculated_fields_form_frontend_scripts() {
		// Remove jquery_ui from frontend
		if (gracioza_get_theme_setting('disable_jquery_ui')) {
			global $wp_styles;
			$wp_styles->done[] = 'cpcff_jquery_ui';
		}
		if (gracioza_is_on(gracioza_get_theme_option('debug_mode')) && gracioza_get_file_dir('plugins/calculated-fields-form/calculated-fields-form.css')!='')
			wp_enqueue_style( 'gracioza-calculated-fields-form',  gracioza_get_file_url('plugins/calculated-fields-form/calculated-fields-form.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'gracioza_calculated_fields_form_merge_styles' ) ) {
	
	function gracioza_calculated_fields_form_merge_styles($list) {
		$list[] = 'plugins/calculated-fields-form/calculated-fields-form.css';
		return $list;
	}
}
?>