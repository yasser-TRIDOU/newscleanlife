<?php
/**
 * The template for homepage posts with "Portfolio" style
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
	
	// Show filters
	$gracioza_cat = gracioza_get_theme_option('parent_cat');
	$gracioza_post_type = gracioza_get_theme_option('post_type');
	$gracioza_taxonomy = gracioza_get_post_type_taxonomy($gracioza_post_type);
	$gracioza_show_filters = gracioza_get_theme_option('show_filters');
	$gracioza_tabs = array();
	if (!gracioza_is_off($gracioza_show_filters)) {
		$gracioza_args = array(
			'type'			=> $gracioza_post_type,
			'child_of'		=> $gracioza_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $gracioza_taxonomy,
			'pad_counts'	=> false
		);
		$gracioza_portfolio_list = get_terms($gracioza_args);
		if (is_array($gracioza_portfolio_list) && count($gracioza_portfolio_list) > 0) {
			$gracioza_tabs[$gracioza_cat] = esc_html__('All', 'gracioza');
			foreach ($gracioza_portfolio_list as $gracioza_term) {
				if (isset($gracioza_term->term_id)) $gracioza_tabs[$gracioza_term->term_id] = $gracioza_term->name;
			}
		}
	}
	if (count($gracioza_tabs) > 0) {
		$gracioza_portfolio_filters_ajax = true;
		$gracioza_portfolio_filters_active = $gracioza_cat;
		$gracioza_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters gracioza_tabs gracioza_tabs_ajax">
			<ul class="portfolio_titles gracioza_tabs_titles">
				<?php
				foreach ($gracioza_tabs as $gracioza_id=>$gracioza_title) {
					?><li><a href="<?php echo esc_url(gracioza_get_hash_link(sprintf('#%s_%s_content', $gracioza_portfolio_filters_id, $gracioza_id))); ?>" data-tab="<?php echo esc_attr($gracioza_id); ?>"><?php echo esc_html($gracioza_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$gracioza_ppp = gracioza_get_theme_option('posts_per_page');
			if (gracioza_is_inherit($gracioza_ppp)) $gracioza_ppp = '';
			foreach ($gracioza_tabs as $gracioza_id=>$gracioza_title) {
				$gracioza_portfolio_need_content = $gracioza_id==$gracioza_portfolio_filters_active || !$gracioza_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $gracioza_portfolio_filters_id, $gracioza_id)); ?>"
					class="portfolio_content gracioza_tabs_content"
					data-blog-template="<?php echo esc_attr(gracioza_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(gracioza_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($gracioza_ppp); ?>"
					data-post-type="<?php echo esc_attr($gracioza_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($gracioza_taxonomy); ?>"
					data-cat="<?php echo esc_attr($gracioza_id); ?>"
					data-parent-cat="<?php echo esc_attr($gracioza_cat); ?>"
					data-need-content="<?php echo (false===$gracioza_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($gracioza_portfolio_need_content) 
						gracioza_show_portfolio_posts(array(
							'cat' => $gracioza_id,
							'parent_cat' => $gracioza_cat,
							'taxonomy' => $gracioza_taxonomy,
							'post_type' => $gracioza_post_type,
							'page' => 1,
							'sticky' => $gracioza_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		gracioza_show_portfolio_posts(array(
			'cat' => $gracioza_cat,
			'parent_cat' => $gracioza_cat,
			'taxonomy' => $gracioza_taxonomy,
			'post_type' => $gracioza_post_type,
			'page' => 1,
			'sticky' => $gracioza_sticky_out
			)
		);
	}

	gracioza_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>