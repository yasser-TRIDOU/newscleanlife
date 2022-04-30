<?php
/* Elegro Crypto Payment support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'gracioza_elegro_payment_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'gracioza_elegro_payment_theme_setup9', 9 );
	function gracioza_elegro_payment_theme_setup9() {
		if ( gracioza_exists_elegro_payment() ) {
			add_filter( 'gracioza_filter_merge_styles', 'gracioza_elegro_payment_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'gracioza_filter_tgmpa_required_plugins', 'gracioza_elegro_payment_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'gracioza_elegro_payment_tgmpa_required_plugins' ) ) {
	
	function gracioza_elegro_payment_tgmpa_required_plugins($list=array()) {
		if (gracioza_storage_isset('required_plugins', 'elegro-payment')) {
			$list[] = array(
				'name' 		=> gracioza_storage_get_array('required_plugins', 'elegro-payment'),
				'slug' 		=> 'elegro-payment',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'gracioza_exists_elegro_payment' ) ) {
	function gracioza_exists_elegro_payment() {
		return class_exists( 'WC_Elegro_Payment' );
	}
}


// Merge custom styles
if ( !function_exists( 'gracioza_elegro_payment_merge_styles' ) ) {
	
	function gracioza_elegro_payment_merge_styles($list) {
		$list[] = 'plugins/elegro-payment/elegro-payment.css';
		return $list;
	}
}
?>