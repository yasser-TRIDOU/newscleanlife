<div class="front_page_section front_page_section_contacts<?php
			$gracioza_scheme = gracioza_get_theme_option('front_page_contacts_scheme');
			if (!gracioza_is_inherit($gracioza_scheme)) echo ' scheme_'.esc_attr($gracioza_scheme);
			echo ' front_page_section_paddings_'.esc_attr(gracioza_get_theme_option('front_page_contacts_paddings'));
		?>"<?php
		$gracioza_css = '';
		$gracioza_bg_image = gracioza_get_theme_option('front_page_contacts_bg_image');
		if (!empty($gracioza_bg_image)) 
			$gracioza_css .= 'background-image: url('.esc_url(gracioza_get_attachment_url($gracioza_bg_image)).');';
		if (!empty($gracioza_css))
			echo ' style="' . esc_attr($gracioza_css) . '"';
?>><?php
	// Add anchor
	$gracioza_anchor_icon = gracioza_get_theme_option('front_page_contacts_anchor_icon');	
	$gracioza_anchor_text = gracioza_get_theme_option('front_page_contacts_anchor_text');	
	if ((!empty($gracioza_anchor_icon) || !empty($gracioza_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_contacts"'
										. (!empty($gracioza_anchor_icon) ? ' icon="'.esc_attr($gracioza_anchor_icon).'"' : '')
										. (!empty($gracioza_anchor_text) ? ' title="'.esc_attr($gracioza_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_contacts_inner<?php
			if (gracioza_get_theme_option('front_page_contacts_fullheight'))
				echo ' gracioza-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$gracioza_css = '';
			$gracioza_bg_mask = gracioza_get_theme_option('front_page_contacts_bg_mask');
			$gracioza_bg_color = gracioza_get_theme_option('front_page_contacts_bg_color');
			if (!empty($gracioza_bg_color) && $gracioza_bg_mask > 0)
				$gracioza_css .= 'background-color: '.esc_attr($gracioza_bg_mask==1
																	? $gracioza_bg_color
																	: gracioza_hex2rgba($gracioza_bg_color, $gracioza_bg_mask)
																).';';
			if (!empty($gracioza_css))
				echo ' style="' . esc_attr($gracioza_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$gracioza_caption = gracioza_get_theme_option('front_page_contacts_caption');
			$gracioza_description = gracioza_get_theme_option('front_page_contacts_description');
			if (!empty($gracioza_caption) || !empty($gracioza_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($gracioza_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo !empty($gracioza_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses($gracioza_caption, 'gracioza_kses_content');
					?></h2><?php
				}
			
				// Description
				if (!empty($gracioza_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo !empty($gracioza_description) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses(wpautop($gracioza_description), 'gracioza_kses_content');
					?></div><?php
				}
			}

			// Content (text)
			$gracioza_content = gracioza_get_theme_option('front_page_contacts_content');
			$gracioza_layout = gracioza_get_theme_option('front_page_contacts_layout');
			if ($gracioza_layout == 'columns' && (!empty($gracioza_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ((!empty($gracioza_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo !empty($gracioza_content) ? 'filled' : 'empty'; ?>"><?php
					echo wp_kses($gracioza_content, 'gracioza_kses_content');
				?></div><?php
			}

			if ($gracioza_layout == 'columns' && (!empty($gracioza_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div><div class="column-2_3"><?php
			}
		
			// Shortcode output
			$gracioza_sc = gracioza_get_theme_option('front_page_contacts_shortcode');
			if (!empty($gracioza_sc) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo !empty($gracioza_sc) ? 'filled' : 'empty'; ?>"><?php
					gracioza_show_layout(do_shortcode($gracioza_sc));
				?></div><?php
			}

			if ($gracioza_layout == 'columns' && (!empty($gracioza_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div></div><?php
			}
			?>			
		</div>
	</div>
</div>