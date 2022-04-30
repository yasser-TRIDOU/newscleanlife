<?php
/**
 * The template to show mobile header and menu
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

// Mobile header
if (gracioza_get_theme_option('header_mobile_enabled')) {
	$gracioza_header_css = $gracioza_header_image = '';
	$gracioza_header_image = get_header_image();
	if (gracioza_trx_addons_featured_image_override()) $gracioza_header_image = gracioza_get_current_mode_image($gracioza_header_image);
	?>
	<div class="top_panel_mobile<?php
						echo !empty($gracioza_header_image) ? ' with_bg_image' : ' without_bg_image';
						if ($gracioza_header_image!='') echo ' '.esc_attr(gracioza_add_inline_css_class('background-image: url('.esc_url($gracioza_header_image).');'));
						?> scheme_<?php echo esc_attr(gracioza_is_inherit(gracioza_get_theme_option('header_scheme')) 
														? gracioza_get_theme_option('color_scheme') 
														: gracioza_get_theme_option('header_scheme'));
						?>"><?php
		
		do_action('gracioza_action_before_header_mobile_info');

		// Additional info
		if (gracioza_is_off(gracioza_get_theme_option('header_mobile_hide_info')) && ($gracioza_info=gracioza_get_theme_option('header_mobile_additional_info'))!='') {
			?><div class="top_panel_mobile_info sc_layouts_row sc_layouts_row_type_compact sc_layouts_row_delimiter">
				<div class="content_wrap">
					<div class="columns_wrap">
						<div class="sc_layouts_column sc_layouts_column_align_center sc_layouts_column_icons_position_left column-1_1"><?php
							?><div class="sc_layouts_item"><?php
								gracioza_show_layout($gracioza_info);
							?></div><!-- /.sc_layouts_item -->
						</div><!-- /.sc_layouts_column -->
					</div><!-- /.columns_wrap -->
				</div><!-- /.content_wrap -->
			</div><!-- /.sc_layouts_row --><?php
		}

		do_action('gracioza_action_before_header_mobile_before_navi');

		?><div class="top_panel_mobile_navi sc_layouts_row sc_layouts_row_type_compact sc_layouts_row_delimiter sc_layouts_row_fixed sc_layouts_row_fixed_always">
			<div class="content_wrap">
				<div class="columns_wrap columns_fluid">
					<div class="sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left sc_layouts_column_fluid column-1_3"><?php
						do_action('gracioza_action_before_header_mobile_before_logo');
						// Logo
						if (gracioza_is_off(gracioza_get_theme_option('header_mobile_hide_logo'))) {
							?><div class="sc_layouts_item"><?php
								set_query_var('gracioza_logo_args', array('type' => 'mobile_header'));
								get_template_part( 'templates/header-logo' );
								set_query_var('gracioza_logo_args', array());
							?></div><?php
						}
						do_action('gracioza_action_before_header_mobile_after_logo');
					?></div><?php
					
					// Attention! Don't place any spaces between columns!
					?><div class="sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left sc_layouts_column_fluid  column-2_3"><?php
						if (gracioza_exists_trx_addons()) {
							do_action('gracioza_action_before_header_mobile_before_login');
							// Display login/logout
							if (gracioza_is_off(gracioza_get_theme_option('header_mobile_hide_login'))) {
								ob_start();
								do_action('gracioza_action_login', array('text_login' => false, 'text_logout' => false));
								$gracioza_action_output = ob_get_contents();
								ob_end_clean();
								if (!empty($gracioza_action_output)) {
									?><div class="sc_layouts_item sc_layouts_menu sc_layouts_menu_default"><?php
										gracioza_show_layout($gracioza_action_output);
									?></div><?php
								}
							}
							do_action('gracioza_action_before_header_mobile_before_cart');
							// Display cart button
							if (gracioza_is_off(gracioza_get_theme_option('header_mobile_hide_cart'))) {
								ob_start();
								do_action('gracioza_action_cart');
								$gracioza_action_output = ob_get_contents();
								ob_end_clean();
								if (!empty($gracioza_action_output)) {
									?><div class="sc_layouts_item"><?php
										gracioza_show_layout($gracioza_action_output);
									?></div><?php
								}
							}
							do_action('gracioza_action_before_header_mobile_before_search');
							// Display search field
							if (gracioza_is_off(gracioza_get_theme_option('header_mobile_hide_search'))) {
								ob_start();
								do_action('gracioza_action_search', 'fullscreen', 'header_mobile_search', false);
								$gracioza_action_output = ob_get_contents();
								ob_end_clean();
								if (!empty($gracioza_action_output)) {
									?><div class="sc_layouts_item"><?php
										gracioza_show_layout($gracioza_action_output);
									?></div><?php
								}
							}
						}

						do_action('gracioza_action_before_header_mobile_before_menu_button');
						
						// Mobile menu button
						?><div class="sc_layouts_item">
							<div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
								<a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#">
									<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
								</a>
							</div>
						</div><?php

						do_action('gracioza_action_before_header_mobile_after_menu_button');

					?></div><!-- /.sc_layouts_column -->
				</div><!-- /.columns_wrap -->
			</div><!-- /.content_wrap -->
		</div><!-- /.sc_layouts_row --><?php

		do_action('gracioza_action_before_header_mobile_after_navi');
		
	?></div><!-- /.top_panel_mobile --><?php
}

// Mobile menu
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr(gracioza_get_theme_option('menu_mobile_fullscreen') > 0 ? 'fullscreen' : 'narrow'); ?> scheme_dark">
	<div class="menu_mobile_inner">
		<a class="menu_mobile_close icon-cancel"></a><?php

		// Logo
		set_query_var('gracioza_logo_args', array('type' => 'mobile'));
		get_template_part( 'templates/header-logo' );
		set_query_var('gracioza_logo_args', array());

		// Mobile menu
		$gracioza_menu_mobile = gracioza_get_nav_menu('menu_mobile');
		if (empty($gracioza_menu_mobile)) {
			$gracioza_menu_mobile = apply_filters('gracioza_filter_get_mobile_menu', '');
			if (empty($gracioza_menu_mobile)) $gracioza_menu_mobile = gracioza_get_nav_menu('menu_main');
			if (empty($gracioza_menu_mobile)) $gracioza_menu_mobile = gracioza_get_nav_menu();
		}
		if (!empty($gracioza_menu_mobile)) {
			if (!empty($gracioza_menu_mobile))
				$gracioza_menu_mobile = str_replace(
					array('menu_main', 'id="menu-', 'sc_layouts_menu_nav', 'sc_layouts_hide_on_mobile', 'hide_on_mobile'),
					array('menu_mobile', 'id="menu_mobile-', '', '', ''),
					$gracioza_menu_mobile
					);
			if (strpos($gracioza_menu_mobile, '<nav ')===false)
				$gracioza_menu_mobile = sprintf('<nav class="menu_mobile_nav_area">%s</nav>', $gracioza_menu_mobile);
			gracioza_show_layout(apply_filters('gracioza_filter_menu_mobile_layout', $gracioza_menu_mobile));
		}

		// Search field
		do_action('gracioza_action_search', 'normal', 'search_mobile', false);
		
		// Social icons
		gracioza_show_layout(gracioza_get_socials_links(), '<div class="socials_mobile">', '</div>');
		?>
	</div>
</div>
