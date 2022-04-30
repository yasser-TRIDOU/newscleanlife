<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_link = get_permalink();
$gracioza_post_format = get_post_format();
$gracioza_post_format = empty($gracioza_post_format) ? 'standard' : str_replace('post-format-', '', $gracioza_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($gracioza_post_format) ); ?>><?php
	gracioza_show_post_featured(array(
		'thumb_size' => gracioza_get_thumb_size( (int) gracioza_get_theme_option('related_posts') == 1 ? 'huge' : 'big' ),
		'show_no_image' => false,
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">'.wp_kses(gracioza_get_post_categories(''), 'gracioza_kses_content').'</div>'
							. '<h6 class="post_title entry-title"><a href="'.esc_url($gracioza_link).'">'.esc_html(get_the_title()).'</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="'.esc_url($gracioza_link).'">'.wp_kses_data(gracioza_get_date()).'</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>