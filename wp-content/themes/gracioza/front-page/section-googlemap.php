<div class="front_page_section front_page_section_googlemap<?php
			$gracioza_scheme = gracioza_get_theme_option('front_page_googlemap_scheme');
			if (!gracioza_is_inherit($gracioza_scheme)) echo ' scheme_'.esc_attr($gracioza_scheme);
			echo ' front_page_section_paddings_'.esc_attr(gracioza_get_theme_option('front_page_googlemap_paddings'));
		?>"<?php
		$gracioza_css = '';
		$gracioza_bg_image = gracioza_get_theme_option('front_page_googlemap_bg_image');
		if (!empty($gracioza_bg_image)) 
			$gracioza_css .= 'background-image: url('.esc_url(gracioza_get_attachment_url($gracioza_bg_image)).');';
		if (!empty($gracioza_css))
			echo ' style="' . esc_attr($gracioza_css) . '"';
?>><?php
	// Add anchor
	$gracioza_anchor_icon = gracioza_get_theme_option('front_page_googlemap_anchor_icon');	
	$gracioza_anchor_text = gracioza_get_theme_option('front_page_googlemap_anchor_text');	
	if ((!empty($gracioza_anchor_icon) || !empty($gracioza_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_googlemap"'
										. (!empty($gracioza_anchor_icon) ? ' icon="'.esc_attr($gracioza_anchor_icon).'"' : '')
										. (!empty($gracioza_anchor_text) ? ' title="'.esc_attr($gracioza_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_googlemap_inner<?php
			if (gracioza_get_theme_option('front_page_googlemap_fullheight'))
				echo ' gracioza-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$gracioza_css = '';
			$gracioza_bg_mask = gracioza_get_theme_option('front_page_googlemap_bg_mask');
			$gracioza_bg_color = gracioza_get_theme_option('front_page_googlemap_bg_color');
			if (!empty($gracioza_bg_color) && $gracioza_bg_mask > 0)
				$gracioza_css .= 'background-color: '.esc_attr($gracioza_bg_mask==1
																	? $gracioza_bg_color
																	: gracioza_hex2rgba($gracioza_bg_color, $gracioza_bg_mask)
																).';';
			if (!empty($gracioza_css))
				echo ' style="' . esc_attr($gracioza_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_googlemap_content_wrap<?php
			$gracioza_layout = gracioza_get_theme_option('front_page_googlemap_layout');
			if ($gracioza_layout != 'fullwidth')
				echo ' content_wrap';
		?>">
			<?php
			// Content wrap with title and description
			$gracioza_caption = gracioza_get_theme_option('front_page_googlemap_caption');
			$gracioza_description = gracioza_get_theme_option('front_page_googlemap_description');
			if (!empty($gracioza_caption) || !empty($gracioza_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				if ($gracioza_layout == 'fullwidth') {
					?><div class="content_wrap"><?php
				}
					// Caption
					if (!empty($gracioza_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
						?><h2 class="front_page_section_caption front_page_section_googlemap_caption front_page_block_<?php echo !empty($gracioza_caption) ? 'filled' : 'empty'; ?>"><?php
							echo wp_kses($gracioza_caption, 'gracioza_kses_content');
						?></h2><?php
					}
				
					// Description (text)
					if (!empty($gracioza_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
						?><div class="front_page_section_description front_page_section_googlemap_description front_page_block_<?php echo !empty($gracioza_description) ? 'filled' : 'empty'; ?>"><?php
							echo wp_kses(wpautop($gracioza_description), 'gracioza_kses_content');
						?></div><?php
					}
				if ($gracioza_layout == 'fullwidth') {
					?></div><?php
				}
			}

			// Content (text)
			$gracioza_content = gracioza_get_theme_option('front_page_googlemap_content');
			if (!empty($gracioza_content) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				if ($gracioza_layout == 'columns') {
					?><div class="front_page_section_columns front_page_section_googlemap_columns columns_wrap">
						<div class="column-1_3">
					<?php
				} else if ($gracioza_layout == 'fullwidth') {
					?><div class="content_wrap"><?php
				}
	
				?><div class="front_page_section_content front_page_section_googlemap_content front_page_block_<?php echo !empty($gracioza_content) ? 'filled' : 'empty'; ?>"><?php
					echo wp_kses($gracioza_content, 'gracioza_kses_content');
				?></div><?php
	
				if ($gracioza_layout == 'columns') {
					?></div><div class="column-2_3"><?php
				} else if ($gracioza_layout == 'fullwidth') {
					?></div><?php
				}
			}
			
			// Widgets output
			?><div class="front_page_section_output front_page_section_googlemap_output"><?php 
				if (is_active_sidebar('front_page_googlemap_widgets')) {
					dynamic_sidebar( 'front_page_googlemap_widgets' );
				} else if (current_user_can( 'edit_theme_options' )) {
					if (!gracioza_exists_trx_addons())
						gracioza_customizer_need_trx_addons_message();
					else
						gracioza_customizer_need_widgets_message('front_page_googlemap_caption', 'ThemeREX Addons - Google map');
				}
			?></div><?php

			if ($gracioza_layout == 'columns' && (!empty($gracioza_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div></div><?php
			}
			?>			
		</div>
	</div>
</div>