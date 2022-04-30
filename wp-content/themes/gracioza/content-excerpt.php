<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_post_format = get_post_format();
$gracioza_post_format = empty($gracioza_post_format) ? 'standard' : str_replace('post-format-', '', $gracioza_post_format);
$gracioza_animation = gracioza_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($gracioza_post_format) ); ?>
	<?php echo (!gracioza_is_off($gracioza_animation) ? ' data-animation="'.esc_attr(gracioza_get_animation_classes($gracioza_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"><?php esc_attr_e('sticky','gracioza');?></span><?php
	}

	// Featured image
	gracioza_show_post_featured(array( 'thumb_size' => gracioza_get_thumb_size( strpos(gracioza_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

    if (!is_sticky()) {
        ?><div class="post_excerpt_content_container"><?php
        gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
                'components' => 'categories',
                'counters' => '',
                'seo' => false
            ), 'excerpt', 1)
        );
    }

	// Title and post meta
	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php
			do_action('gracioza_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );

			?>
		</div><!-- .post_header --><?php
	}
	
	// Post content
	?><div class="post_content entry-content"><?php
		if (gracioza_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'gracioza' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'gracioza' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$gracioza_show_learn_more = !in_array($gracioza_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
				if (has_excerpt()) {
					the_excerpt();
				} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
					the_content( '' );
				} else if (in_array($gracioza_post_format, array('link', 'aside', 'status'))) {
					the_content();
				} else if ($gracioza_post_format == 'quote') {
					if (($quote = gracioza_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
						gracioza_show_layout(wpautop($quote));
					else
						the_excerpt();
				} else if (substr(get_the_content(), 0, 1)!='[') {
					the_excerpt();
				}
			?></div><?php

            do_action('gracioza_action_before_post_meta');

            // Post meta
            $gracioza_components = gracioza_is_inherit(gracioza_get_theme_option_from_meta('meta_parts'))
                ? 'date,author,counters'
                : gracioza_array_get_keys_by_value(gracioza_get_theme_option('meta_parts'));
            $gracioza_counters = gracioza_is_inherit(gracioza_get_theme_option_from_meta('counters'))
                ? 'comments'
                : gracioza_array_get_keys_by_value(gracioza_get_theme_option('counters'));

            if (!empty($gracioza_components))
                gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
                        'components' => $gracioza_components,
                        'counters' => $gracioza_counters,
                        'seo' => false
                    ), 'excerpt', 1)
                );
            if (!is_sticky()) {
                // Post taxonomies
                the_tags('<span class="post_meta_item post_tags"><span class="post_meta_label">' . esc_html__('Tags:', 'gracioza') . '</span> ', ' ', '</span>');
            }
			// More button
			if ( $gracioza_show_learn_more && is_sticky() ) {
				?><div class="clearfix"></div><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'gracioza'); ?></a></p><?php
			}

		}

            if (!is_sticky()) {
            ?></div><?php
                }

                ?></div><!-- .entry-content -->
</article>