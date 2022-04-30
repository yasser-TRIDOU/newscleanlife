<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_post_id    = get_the_ID();
$gracioza_post_date  = gracioza_get_date();
$gracioza_post_title = get_the_title();
$gracioza_post_link  = get_permalink();
$gracioza_post_author_id   = get_the_author_meta('ID');
$gracioza_post_author_name = get_the_author_meta('display_name');
$gracioza_post_author_url  = get_author_posts_url($gracioza_post_author_id, '');

$gracioza_args = get_query_var('gracioza_args_widgets_posts');
$gracioza_show_date = isset($gracioza_args['show_date']) ? (int) $gracioza_args['show_date'] : 1;
$gracioza_show_image = isset($gracioza_args['show_image']) ? (int) $gracioza_args['show_image'] : 1;
$gracioza_show_author = isset($gracioza_args['show_author']) ? (int) $gracioza_args['show_author'] : 1;
$gracioza_show_counters = isset($gracioza_args['show_counters']) ? (int) $gracioza_args['show_counters'] : 1;
$gracioza_show_categories = isset($gracioza_args['show_categories']) ? (int) $gracioza_args['show_categories'] : 1;

$gracioza_output = gracioza_storage_get('gracioza_output_widgets_posts');

$gracioza_post_counters_output = '';
if ( $gracioza_show_counters ) {
	$gracioza_post_counters_output = '<span class="post_info_item post_info_counters">'
								. gracioza_get_post_counters('comments')
							. '</span>';
}


$gracioza_output .= '<article class="post_item with_thumb">';

if ($gracioza_show_image) {
	$gracioza_post_thumb = get_the_post_thumbnail($gracioza_post_id, gracioza_get_thumb_size('tiny'), array(
		'alt' => the_title_attribute( array( 'echo' => false ) )
	));
	if ($gracioza_post_thumb) $gracioza_output .= '<div class="post_thumb">' . ($gracioza_post_link ? '<a href="' . esc_url($gracioza_post_link) . '">' : '') . ($gracioza_post_thumb) . ($gracioza_post_link ? '</a>' : '') . '</div>';
}

$gracioza_output .= '<div class="post_content">'
			. ($gracioza_show_categories 
					? '<div class="post_categories">'
						. gracioza_get_post_categories()
						. $gracioza_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($gracioza_post_link ? '<a href="' . esc_url($gracioza_post_link) . '">' : '') . ($gracioza_post_title) . ($gracioza_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('gracioza_filter_get_post_info', 
								'<div class="post_info">'
									. ($gracioza_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($gracioza_post_link ? '<a href="' . esc_url($gracioza_post_link) . '" class="post_info_date">' : '') 
											. esc_html($gracioza_post_date) 
											. ($gracioza_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($gracioza_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'gracioza') . ' ' 
											. ($gracioza_post_link ? '<a href="' . esc_url($gracioza_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($gracioza_post_author_name) 
											. ($gracioza_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$gracioza_show_categories && $gracioza_post_counters_output
										? $gracioza_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
gracioza_storage_set('gracioza_output_widgets_posts', $gracioza_output);
?>