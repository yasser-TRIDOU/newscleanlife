<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WordPress editor or any Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$gracioza_content = '';
$gracioza_blog_archive_mask = '%%CONTENT%%';
$gracioza_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $gracioza_blog_archive_mask);
if ( have_posts() ) {
	the_post();
	if ( ! empty($GLOBALS['post']) ) {
		gracioza_storage_set('blog_archive_template_post', $GLOBALS['post']);
	}
	if (($gracioza_content = apply_filters('the_content', get_the_content())) != '') {
		if (($gracioza_pos = strpos($gracioza_content, $gracioza_blog_archive_mask)) !== false) {
			$gracioza_content = preg_replace('/(\<p\>\s*)?'.$gracioza_blog_archive_mask.'(\s*\<\/p\>)/i', $gracioza_blog_archive_subst, $gracioza_content);
		} else
			$gracioza_content .= $gracioza_blog_archive_subst;
		$gracioza_content = explode($gracioza_blog_archive_mask, $gracioza_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) gracioza_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$gracioza_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$gracioza_args = gracioza_query_add_posts_and_cats($gracioza_args, '', gracioza_get_theme_option('post_type'), gracioza_get_theme_option('parent_cat'));
$gracioza_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($gracioza_page_number > 1) {
	$gracioza_args['paged'] = $gracioza_page_number;
	$gracioza_args['ignore_sticky_posts'] = true;
}
$gracioza_ppp = gracioza_get_theme_option('posts_per_page');
if ((int) $gracioza_ppp != 0)
	$gracioza_args['posts_per_page'] = (int) $gracioza_ppp;
// Make a new main query
$GLOBALS['wp_the_query']->query($gracioza_args);


// Add internal query vars in the new query!
if (is_array($gracioza_content) && count($gracioza_content) == 2) {
	set_query_var('blog_archive_start', $gracioza_content[0]);
	set_query_var('blog_archive_end', $gracioza_content[1]);
}

get_template_part('index');
?>