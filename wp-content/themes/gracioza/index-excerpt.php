<?php
/**
 * The template for homepage posts with "Excerpt" style
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
	$gracioza_sticky_out = gracioza_get_theme_option('sticky_style')=='columns' 
							&& is_array($gracioza_stickies) && count($gracioza_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($gracioza_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
    $gracioza_sticky_count = 0;
	while ( have_posts() ) { the_post();

	if (is_sticky()) {
        $gracioza_sticky_count++;
    }
		if ($gracioza_sticky_out && !is_sticky()) {
			$gracioza_sticky_out = false;
			?></div><?php
		}
        if(is_sticky() && gracioza_get_theme_option('show_one_sticky_blog')==1 &&  $gracioza_sticky_count > 1 ) continue;
		get_template_part( 'content', $gracioza_sticky_out && is_sticky() ? 'sticky' : 'excerpt' );
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