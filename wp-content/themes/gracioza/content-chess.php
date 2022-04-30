<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_blog_style = explode('_', gracioza_get_theme_option('blog_style'));
$gracioza_columns = empty($gracioza_blog_style[1]) ? 1 : max(1, $gracioza_blog_style[1]);
$gracioza_expanded = !gracioza_sidebar_present() && gracioza_is_on(gracioza_get_theme_option('expand_content'));
$gracioza_post_format = get_post_format();
$gracioza_post_format = empty($gracioza_post_format) ? 'standard' : str_replace('post-format-', '', $gracioza_post_format);
$gracioza_animation = gracioza_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($gracioza_columns).' post_format_'.esc_attr($gracioza_post_format) ); ?>
	<?php echo (!gracioza_is_off($gracioza_animation) ? ' data-animation="'.esc_attr(gracioza_get_animation_classes($gracioza_animation)).'"' : ''); ?>>

	<?php
	// Add anchor
	if ($gracioza_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.the_title_attribute( array( 'echo' => false ) ).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	gracioza_show_post_featured( array(
											'class' => $gracioza_columns == 1 ? 'gracioza-full-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => gracioza_get_thumb_size(
																	strpos(gracioza_get_theme_option('body_style'), 'full')!==false
																		? ( $gracioza_columns > 1 ? 'huge' : 'original' )
																		: (	$gracioza_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('gracioza_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h5 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h5>' );
			
			do_action('gracioza_action_before_post_meta'); 

			// Post meta
			$gracioza_components = gracioza_is_inherit(gracioza_get_theme_option_from_meta('meta_parts')) 
										? 'date'.($gracioza_columns < 3 ? ',counters' : '')
										: gracioza_array_get_keys_by_value(gracioza_get_theme_option('meta_parts'));
			$gracioza_counters = gracioza_is_inherit(gracioza_get_theme_option_from_meta('counters')) 
										? 'comments'
										: gracioza_array_get_keys_by_value(gracioza_get_theme_option('counters'));
			$gracioza_post_meta = empty($gracioza_components) 
										? '' 
										: gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
												'components' => $gracioza_components,
												'counters' => $gracioza_counters,
												'seo' => false,
												'echo' => false
												), $gracioza_blog_style[0], $gracioza_columns)
											);
			gracioza_show_layout($gracioza_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$gracioza_show_learn_more = !in_array($gracioza_post_format, array('link', 'aside', 'status', 'quote'));
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
				?>
			</div>
			<?php
			// Post meta
			if (in_array($gracioza_post_format, array('link', 'aside', 'status', 'quote'))) {
				gracioza_show_layout($gracioza_post_meta);
			}
			// More button
			if ( $gracioza_show_learn_more ) {
				?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'gracioza'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>