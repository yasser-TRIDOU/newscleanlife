<?php
/**
 * The default template to display the content of the single post, page or attachment
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_seo = gracioza_is_on(gracioza_get_theme_option('seo_snippets'));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_'.esc_attr(get_post_type()) 
												. ' post_format_'.esc_attr(str_replace('post-format-', '', get_post_format())) 
												);
		if ($gracioza_seo) {
			?> itemscope="itemscope" 
			   itemprop="articleBody" 
			   itemtype="//schema.org/<?php echo esc_attr(gracioza_get_markup_schema()); ?>"
			   itemid="<?php echo esc_url(get_the_permalink()); ?>"
			   content="<?php the_title_attribute(); ?>"<?php
		}
?>><?php

	do_action('gracioza_action_before_post_data'); 

	// Structured data snippets
	if ($gracioza_seo)
		get_template_part('templates/seo');

	// Featured image
	if ( gracioza_is_off(gracioza_get_theme_option('hide_featured_on_single'))
			&& !gracioza_sc_layouts_showed('featured') 
			&& strpos(get_the_content(), '[trx_widget_banner]')===false) {
		do_action('gracioza_action_before_post_featured'); 
		gracioza_show_post_featured();
		do_action('gracioza_action_after_post_featured'); 
	} else if (has_post_thumbnail()) {
		?><meta itemprop="image" itemtype="//schema.org/ImageObject" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>"><?php
	}

    ?><div class="post_excerpt_content_container"><?php

    gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
            'components' => 'categories',
            'counters' => '',
            'seo' => false
        ), 'excerpt', 1)
    );

	// Title and post meta
	if ( (!gracioza_sc_layouts_showed('title')) && !in_array(get_post_format(), array('link', 'aside', 'status', 'quote')) ) {
		do_action('gracioza_action_before_post_title'); 
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if (!gracioza_sc_layouts_showed('title')) {
				the_title( '<h3 class="post_title entry-title"'.($gracioza_seo ? ' itemprop="headline"' : '').'>', '</h3>' );
			}
			?>
		</div><!-- .post_header -->
		<?php
		do_action('gracioza_action_after_post_title'); 
	}

	do_action('gracioza_action_before_post_content'); 

	// Post content
	?>
	<div class="post_content entry-content" itemprop="mainEntityOfPage">
		<?php
		the_content( );

		do_action('gracioza_action_before_post_pagination'); 

		wp_link_pages( array(
			'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'gracioza' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'gracioza' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );

		?><div class="post_excerpt_meta"><?php

        // Post meta
        if (!gracioza_sc_layouts_showed('postmeta') && gracioza_is_on(gracioza_get_theme_option('show_post_meta'))) {
            gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
                    'components' => gracioza_array_get_keys_by_value(gracioza_get_theme_option('meta_parts')),
                    'counters' => gracioza_array_get_keys_by_value(gracioza_get_theme_option('counters')),
                    'seo' => gracioza_is_on(gracioza_get_theme_option('seo_snippets'))
                ), 'single', 1)
            );
        }

		// Taxonomies and share
		if ( is_single() && !is_attachment() ) {
			
			do_action('gracioza_action_before_post_meta');

            // Post taxonomies
            the_tags( '<span class="post_meta_item post_tags"><span class="post_meta_label">'.esc_html__('Tags:', 'gracioza').'</span> ', ' ', '</span>' );

			?><div class="post_meta post_meta_single"><?php

				// Share
				if (gracioza_is_on(gracioza_get_theme_option('show_share_links'))) {
					gracioza_show_share_links(array(
							'type' => 'block',
							'caption' => esc_html__('Share: ','gracioza'),
							'before' => '<span class="post_meta_item post_share">',
							'after' => '</span>'
						));
				}
			?></div><?php

			do_action('gracioza_action_after_post_meta'); 
		}
        ?></div><?php
        ?></div><?php
		?>
	</div><!-- .entry-content -->
	

	<?php
	do_action('gracioza_action_after_post_content'); 

	// Author bio.
	if ( gracioza_get_theme_option('show_author_info')==1 && is_single() && !is_attachment() && get_the_author_meta( 'description' ) ) {
		do_action('gracioza_action_before_post_author'); 
		get_template_part( 'templates/author-bio' );
		do_action('gracioza_action_after_post_author'); 
	}

	do_action('gracioza_action_after_post_data'); 
	?>
</article>
