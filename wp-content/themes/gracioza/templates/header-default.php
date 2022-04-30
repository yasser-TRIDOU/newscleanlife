<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */


$gracioza_header_css = $gracioza_header_image = '';
$gracioza_header_video = gracioza_get_header_video();
if (true || empty($gracioza_header_video)) {
	$gracioza_header_image = get_header_image();
	if (gracioza_trx_addons_featured_image_override()) $gracioza_header_image = gracioza_get_current_mode_image($gracioza_header_image);
}

?><header class="top_panel top_panel_default<?php
					echo !empty($gracioza_header_image) || !empty($gracioza_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($gracioza_header_video!='') echo ' with_bg_video';
					if ($gracioza_header_image!='') echo ' '.esc_attr(gracioza_add_inline_css_class('background-image: url('.esc_url($gracioza_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (gracioza_is_on(gracioza_get_theme_option('header_fullheight'))) echo ' header_fullheight gracioza-full-height';
					?> scheme_<?php echo esc_attr(gracioza_is_inherit(gracioza_get_theme_option('header_scheme')) 
													? gracioza_get_theme_option('color_scheme') 
													: gracioza_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($gracioza_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (gracioza_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

?></header>