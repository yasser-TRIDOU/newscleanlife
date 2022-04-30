<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($gracioza_columns).' post_format_'.esc_attr($gracioza_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!gracioza_is_off($gracioza_animation) ? ' data-animation="'.esc_attr(gracioza_get_animation_classes($gracioza_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$gracioza_image_hover = gracioza_get_theme_option('image_hover');
	// Featured image
	gracioza_show_post_featured(array(
		'thumb_size' => gracioza_get_thumb_size(strpos(gracioza_get_theme_option('body_style'), 'full')!==false || $gracioza_columns < 3 
								? 'masonry-big' 
								: 'masonry'),
		'show_no_image' => true,
		'class' => $gracioza_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $gracioza_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>