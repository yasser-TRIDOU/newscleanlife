<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.06
 */

$gracioza_header_css = $gracioza_header_image = '';
$gracioza_header_video = gracioza_get_header_video();
if (true || empty($gracioza_header_video)) {
	$gracioza_header_image = get_header_image();
	if (gracioza_trx_addons_featured_image_override()) $gracioza_header_image = gracioza_get_current_mode_image($gracioza_header_image);
}

$gracioza_header_id = str_replace('header-custom-', '', gracioza_get_theme_option("header_style"));
if ((int) $gracioza_header_id == 0) {
	$gracioza_header_id = gracioza_get_post_id(array(
												'name' => $gracioza_header_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$gracioza_header_id = apply_filters('gracioza_filter_get_translated_layout', $gracioza_header_id);
}
$gracioza_header_meta = get_post_meta($gracioza_header_id, 'trx_addons_options', true);

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($gracioza_header_id); 
				?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($gracioza_header_id)));
				echo !empty($gracioza_header_image) || !empty($gracioza_header_video) 
					? ' with_bg_image' 
					: ' without_bg_image';
				if ($gracioza_header_video!='') 
					echo ' with_bg_video';
				if ($gracioza_header_image!='') 
					echo ' '.esc_attr(gracioza_add_inline_css_class('background-image: url('.esc_url($gracioza_header_image).');'));
				if (!empty($gracioza_header_meta['margin']) != '') 
					echo ' '.esc_attr(gracioza_add_inline_css_class('margin-bottom: '.esc_attr(gracioza_prepare_css_value($gracioza_header_meta['margin'])).';'));
				if (is_single() && has_post_thumbnail()) 
					echo ' with_featured_image';
				if (gracioza_is_on(gracioza_get_theme_option('header_fullheight'))) 
					echo ' header_fullheight gracioza-full-height';
				?> scheme_<?php echo esc_attr(gracioza_is_inherit(gracioza_get_theme_option('header_scheme')) 
												? gracioza_get_theme_option('color_scheme') 
												: gracioza_get_theme_option('header_scheme'));
				?>"><?php

	// Background video
	if (!empty($gracioza_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('gracioza_action_show_layout', $gracioza_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>