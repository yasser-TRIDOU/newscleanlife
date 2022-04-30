<?php
/**
 * The template for homepage posts with "Chess" style
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

gracioza_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	gracioza_show_layout(get_query_var('blog_archive_start'));

	$gracioza_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$gracioza_sticky_out = gracioza_get_theme_option('sticky_style')=='columns' 
							&& is_array($gracioza_stickies) && count($gracioza_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($gracioza_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$gracioza_sticky_out) {
		?><div class="chess_wrap posts_container"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($gracioza_sticky_out && !is_sticky()) {
			$gracioza_sticky_out = false;
			?></div><div class="chess_wrap posts_container"><?php
		}
		get_template_part( 'content', $gracioza_sticky_out && is_sticky() ? 'sticky' :'chess' );
	}
	
	?></div><?php

	gracioza_show_pagination();

	gracioza_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>