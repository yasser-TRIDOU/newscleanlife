<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

// Page (category, tag, archive, author) title

if ( gracioza_need_page_title() ) {
	gracioza_sc_layouts_showed('title', true);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$gracioza_blog_title = gracioza_get_blog_title();
							$gracioza_blog_title_text = $gracioza_blog_title_class = $gracioza_blog_title_link = $gracioza_blog_title_link_text = '';
							if (is_array($gracioza_blog_title)) {
								$gracioza_blog_title_text = $gracioza_blog_title['text'];
								$gracioza_blog_title_class = !empty($gracioza_blog_title['class']) ? ' '.$gracioza_blog_title['class'] : '';
								$gracioza_blog_title_link = !empty($gracioza_blog_title['link']) ? $gracioza_blog_title['link'] : '';
								$gracioza_blog_title_link_text = !empty($gracioza_blog_title['link_text']) ? $gracioza_blog_title['link_text'] : '';
							} else
								$gracioza_blog_title_text = $gracioza_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($gracioza_blog_title_class); ?>"><?php
								$gracioza_top_icon = gracioza_get_category_icon();
								if (!empty($gracioza_top_icon)) {
									$gracioza_attr = gracioza_getimagesize($gracioza_top_icon);
									?><img src="<?php echo esc_url($gracioza_top_icon); ?>" alt="'.esc_attr__('img', 'gracioza').'" <?php if (!empty($gracioza_attr[3])) gracioza_show_layout($gracioza_attr[3]);?>><?php
								}
								echo wp_kses_post($gracioza_blog_title_text);
							?></h1>
							<?php
							if (!empty($gracioza_blog_title_link) && !empty($gracioza_blog_title_link_text)) {
								?><a href="<?php echo esc_url($gracioza_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($gracioza_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>