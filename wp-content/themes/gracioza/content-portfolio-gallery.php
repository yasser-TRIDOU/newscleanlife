<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_blog_style = explode('_', gracioza_get_theme_option('blog_style'));
$gracioza_columns = empty($gracioza_blog_style[1]) ? 2 : max(2, $gracioza_blog_style[1]);
$gracioza_post_format = get_post_format();
$gracioza_post_format = empty($gracioza_post_format) ? 'standard' : str_replace('post-format-', '', $gracioza_post_format);
$gracioza_animation = gracioza_get_theme_option('blog_animation');
$gracioza_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($gracioza_columns).' post_format_'.esc_attr($gracioza_post_format) ); ?>
	<?php echo (!gracioza_is_off($gracioza_animation) ? ' data-animation="'.esc_attr(gracioza_get_animation_classes($gracioza_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($gracioza_image[1]) && !empty($gracioza_image[2])) echo intval($gracioza_image[1]) .'x' . intval($gracioza_image[2]); ?>"
	data-src="<?php if (!empty($gracioza_image[0])) echo esc_url($gracioza_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$gracioza_image_hover = 'icon';
	if (in_array($gracioza_image_hover, array('icons', 'zoom'))) $gracioza_image_hover = 'dots';
	$gracioza_components = gracioza_is_inherit(gracioza_get_theme_option_from_meta('meta_parts')) 
								? 'categories,date,counters,share'
								: gracioza_array_get_keys_by_value(gracioza_get_theme_option('meta_parts'));
	$gracioza_counters = gracioza_is_inherit(gracioza_get_theme_option_from_meta('counters')) 
								? 'comments'
								: gracioza_array_get_keys_by_value(gracioza_get_theme_option('counters'));
	gracioza_show_post_featured(array(
		'hover' => $gracioza_image_hover,
		'thumb_size' => gracioza_get_thumb_size( strpos(gracioza_get_theme_option('body_style'), 'full')!==false || $gracioza_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($gracioza_components)
										? gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
											'components' => $gracioza_components,
											'counters' => $gracioza_counters,
											'seo' => false,
											'echo' => false
											), $gracioza_blog_style[0], $gracioza_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'gracioza') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>