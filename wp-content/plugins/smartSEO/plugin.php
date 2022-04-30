<?php
/*
Plugin Name: 		smart SEO - Wordpress plugin
Plugin URI: 		http://codecanyon.net/item/premium-seo-pack-wordpress-plugin/6109437
Description: 		Smart SEO (Search Engine Optimization - Wordpress plugin) - The easyest way to optimize your wordpress website for search engines!
Version: 			4.0
Author: 			AA-Team
Author URI: 		http://codecanyon.net/user/AA-Team/portfolio
Text Domain: 		smartSEO
Domain Path: 		/languages
*/
! defined( 'ABSPATH' ) and exit;

define('PSP_VERSION', '4.0');

// Derive the current path and load up psp - TEST
$plugin_path = dirname(__FILE__) . '/';
if (class_exists('psp') != true) {
	require_once($plugin_path . 'aa-framework/framework.class.php');
}

// Initalize the your plugin
$psp = new psp();

// Add an activation hook
register_activation_hook(__FILE__, array(&$psp, 'activate'));

// load textdomain
add_action( 'plugins_loaded', 'psp_load_textdomain' );
add_action( 'plugins_loaded', 'psp_check_integrity' );

function psp_load_textdomain() {
	load_plugin_textdomain( 'psp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
function psp_check_integrity() {
	$mainObj = psp();
	return is_object($mainObj) ? $mainObj->plugin_integrity_check() : true;
}
