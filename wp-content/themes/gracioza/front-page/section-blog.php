<div class="front_page_section front_page_section_blog<?php
			$gracioza_scheme = gracioza_get_theme_option('front_page_blog_scheme');
			if (!gracioza_is_inherit($gracioza_scheme)) echo ' scheme_'.esc_attr($gracioza_scheme);
			echo ' front_page_section_paddings_'.esc_attr(gracioza_get_theme_option('front_page_blog_paddings'));
		?>"<?php
		$gracioza_css = '';
		$gracioza_bg_image = gracioza_get_theme_option('front_page_blog_bg_image');
		if (!empty($gracioza_bg_image)) 
			$gracioza_css .= 'background-image: url('.esc_url(gracioza_get_attachment_url($gracioza_bg_image)).');';
		if (!empty($gracioza_css))
			echo ' style="' . esc_attr($gracioza_css) . '"';
?>><?php
	// Add anchor
	$gracioza_anchor_icon = gracioza_get_theme_option('front_page_blog_anchor_icon');	
	$gracioza_anchor_text = gracioza_get_theme_option('front_page_blog_anchor_text');	
	if ((!empty($gracioza_anchor_icon) || !empty($gracioza_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_blog"'
										. (!empty($gracioza_anchor_icon) ? ' icon="'.esc_attr($gracioza_anchor_icon).'"' : '')
										. (!empty($gracioza_anchor_text) ? ' title="'.esc_attr($gracioza_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_blog_inner<?php
			if (gracioza_get_theme_option('front_page_blog_fullheight'))
				echo ' gracioza-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$gracioza_css = '';
			$gracioza_bg_mask = gracioza_get_theme_option('front_page_blog_bg_mask');
			$gracioza_bg_color = gracioza_get_theme_option('front_page_blog_bg_color');
			if (!empty($gracioza_bg_color) && $gracioza_bg_mask > 0)
				$gracioza_css .= 'background-color: '.esc_attr($gracioza_bg_mask==1
																	? $gracioza_bg_color
																	: gracioza_hex2rgba($gracioza_bg_color, $gracioza_bg_mask)
																).';';
			if (!empty($gracioza_css))
				echo ' style="' . esc_attr($gracioza_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_blog_content_wrap content_wrap">
			<?php
			// Caption
			$gracioza_caption = gracioza_get_theme_option('front_page_blog_caption');
			if (!empty($gracioza_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><h2 class="front_page_section_caption front_page_section_blog_caption front_page_block_<?php echo !empty($gracioza_caption) ? 'filled' : 'empty'; ?>"><?php echo wp_kses($gracioza_caption, 'gracioza_kses_content'); ?></h2><?php
			}
		
			// Description (text)
			$gracioza_description = gracioza_get_theme_option('front_page_blog_description');
			if (!empty($gracioza_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_description front_page_section_blog_description front_page_block_<?php echo !empty($gracioza_description) ? 'filled' : 'empty'; ?>"><?php echo wp_kses(wpautop($gracioza_description), 'gracioza_kses_content' ); ?></div><?php
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_blog_output"><?php 
				if (is_active_sidebar('front_page_blog_widgets')) {
					dynamic_sidebar( 'front_page_blog_widgets' );
				} else if (current_user_can( 'edit_theme_options' )) {
					if (!gracioza_exists_trx_addons())
						gracioza_customizer_need_trx_addons_message();
					else
						gracioza_customizer_need_widgets_message('front_page_blog_caption', 'ThemeREX Addons - Blogger');
				}
			?></div>
		</div>
	</div>
</div>