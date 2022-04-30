<?php
/**
 * The "News Magazine" template to show post's content
 *
 * Used in the widget Recent News.
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */
 
$widget_args = get_query_var('trx_addons_args_recent_news');
$style = $widget_args['style'];
$number = $widget_args['number'];
$count = $widget_args['count'];
$columns = $widget_args['columns'];
$featured = $widget_args['featured'];
$post_format = get_post_format();
$post_format = empty($post_format) ? 'standard' : str_replace('post-format-', '', $post_format);
$animation = apply_filters('trx_addons_blog_animation', '');

if ($number==$featured+1 && $number > 1 && $featured < $count && $featured!=$columns-1) {
	?><div class="post_delimiter<?php if ($columns > 1) echo ' '.esc_attr(trx_addons_get_column_class(1, 1)); ?>"></div><?php
}
if ($columns > 1 && !($featured==$columns-1 && $number>$featured+1)) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $columns)); ?>"><?php
}
?><article 
	<?php post_class( 'post_item post_layout_'.esc_attr($style)
					.' post_format_'.esc_attr($post_format)
					.' post_accented_'.($number<=$featured ? 'on' : 'off') 
					.($featured == $count && $featured > $columns ? ' post_accented_border' : '')
					); ?>
	<?php echo (!empty($animation) ? ' data-animation="'.esc_attr($animation).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}
	
	trx_addons_get_template_part('templates/tpl.featured.php',
								'trx_addons_args_featured',
								apply_filters('trx_addons_filter_args_featured', array(
												'post_info' => '',
												'thumb_size' => trx_addons_get_thumb_size($number<=$featured ? 'big' : 'magazine')
												), 'recent_news-magazine')
								);

	if ( !in_array($post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
            <div class="post_info"><span class="post_categories"><?php gracioza_show_layout(trx_addons_get_post_categories()); ?></span></div>
			<?php
			
			the_title( ($number<=$featured ? '<h5' : '<h6').' class="post_title entry-title"><a href="'.esc_url(get_permalink()).'" rel="bookmark">', '</a>'.($number<=$featured ? '</h5>' : '</h6>') );

            if ($number<=$featured) {
                // Post content area
                ?><div class="post_content_inner"><?php
                if (has_excerpt()) {
                    the_excerpt();
                } else if (strpos(get_the_content('!--more'), '!--more')!==false) {
                    the_content( '' );
                } else if (in_array($post_format, array('link', 'aside', 'status'))) {
                    the_content();
                } else if ($post_format == 'quote') {
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
            }
			?>
		</div><!-- .entry-header -->
		<?php
	}

?>
</article><?php

if ($columns > 1 && !($featured==$columns-1 && $featured<$number && $number<$count)) {
	?></div><?php
}
?>