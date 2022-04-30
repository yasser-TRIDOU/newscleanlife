<?php
/**
 * The template for homepage posts with "Modern" style
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

gracioza_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	gracioza_show_layout(get_query_var('blog_archive_start'));

	?><div class="posts_container"><?php
	
	$gracioza_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$gracioza_sticky_out = false;
	if ($gracioza_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
    if (gracioza_get_theme_option('first_post_large') && !is_paged() && !in_array(gracioza_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
        the_post();
        get_template_part( 'content', 'excerpt' );
    }
	while ( have_posts() ) { the_post(); 
		if ($gracioza_sticky_out && !is_sticky()) {
			$gracioza_sticky_out = false;
			?></div><?php
		}
		get_template_part( 'content', is_sticky() ? 'modern_sticky' : 'modern' );
	}
	if ($gracioza_sticky_out) {
		$gracioza_sticky_out = false;
		?></div><?php
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