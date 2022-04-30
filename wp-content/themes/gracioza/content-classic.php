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
$gracioza_columns = empty($gracioza_blog_style[1]) ? 2 : max(2, $gracioza_blog_style[1]);
$gracioza_expanded = !gracioza_sidebar_present() && gracioza_is_on(gracioza_get_theme_option('expand_content'));
$gracioza_post_format = get_post_format();
$gracioza_post_format = empty($gracioza_post_format) ? 'standard' : str_replace('post-format-', '', $gracioza_post_format);
$gracioza_animation = gracioza_get_theme_option('blog_animation');
$gracioza_components = gracioza_is_inherit(gracioza_get_theme_option_from_meta('meta_parts')) 
							? 'categories,date,counters'.($gracioza_columns < 3 ? ',edit' : '')
							: gracioza_array_get_keys_by_value(gracioza_get_theme_option('meta_parts'));
$gracioza_counters = gracioza_is_inherit(gracioza_get_theme_option_from_meta('counters')) 
							? 'comments'
							: gracioza_array_get_keys_by_value(gracioza_get_theme_option('counters'));
$gracioza_meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
$gracioza_services_type = '';
if (defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type==TRX_ADDONS_CPT_SERVICES_PT) {
    $gracioza_services_type = (isset($gracioza_meta['services-type'])?$gracioza_meta['services-type']:'normal');
}

?><div class="<?php echo esc_attr($gracioza_blog_style[0] == 'classic' ? 'column' : 'masonry_item masonry_item'); ?>-1_<?php echo esc_attr($gracioza_columns); ?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($gracioza_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($gracioza_columns)
					. ' post_layout_'.esc_attr($gracioza_blog_style[0]) 
					. ' post_layout_'.esc_attr($gracioza_blog_style[0]).'_'.esc_attr($gracioza_columns)
                    . esc_attr(($gracioza_services_type == 'style-1') ? ' services_style_1' : '')
                    . esc_attr(($gracioza_services_type == 'style-2') ? ' services_style_2' : '')
					); ?>
	<?php echo (!gracioza_is_off($gracioza_animation) ? ' data-animation="'.esc_attr(gracioza_get_animation_classes($gracioza_animation)).'"' : ''); ?>>
	<?php
    if ($gracioza_services_type == 'style-1' || $gracioza_services_type == 'style-2') {
        $gracioza_image_hover = gracioza_get_theme_option('image_hover');
        // Featured image
        gracioza_show_post_featured(array(
            'thumb_size' => gracioza_get_thumb_size('services'),
            'show_no_image' => true,
            'post_info' => $gracioza_image_hover == 'dots' ?
                '<div class="post_info">'
                    .((isset($gracioza_meta['banner']) && $gracioza_meta['banner']!='')
                    ?'<div class="services_banner">'.esc_attr($gracioza_meta['banner']).'</div>':'')
                    .'<h4 class="post_title">'.esc_html(get_the_title()).'</h4>
                    <a class="sc_button sc_button_simple" href="'.esc_url( get_permalink() ).'">'.esc_html__("More details", 'gracioza').'</a>
                </div>' : ''
        ));
    } else {
    // Sticky label
    if ( is_sticky() && !is_paged() ) {
        ?><span class="post_label label_sticky"></span><?php
    }

    // Featured image
    if ($gracioza_services_type == 'style-1' || $gracioza_services_type == 'style-2') {
        $gracioza_image_hover = gracioza_get_theme_option('image_hover');
        // Featured image
        gracioza_show_post_featured(array(
            'thumb_size' => gracioza_get_thumb_size('services'),
            'show_no_image' => true,
            'post_info' => $gracioza_image_hover == 'dots' ? '<div class="post_info"><h4 class="post_title">'.esc_html(get_the_title()).'</h4><a class="sc_button sc_button_simple" href="'.esc_url( get_permalink() ).'">'.esc_html__("Read more", 'gracioza').'</a></div>' : ''
        ));
    } else {
        gracioza_show_post_featured( array( 'thumb_size' => gracioza_get_thumb_size($gracioza_blog_style[0] == 'classic'
            ? (strpos(gracioza_get_theme_option('body_style'), 'full')!==false
                ? ( $gracioza_columns > 2 ? 'big' : 'huge' )
                : (	$gracioza_columns > 2
                    ? ($gracioza_expanded ? 'med' : 'small')
                    : ($gracioza_expanded ? 'big' : 'med')
                )
            )
            : (strpos(gracioza_get_theme_option('body_style'), 'full')!==false
                ? ( $gracioza_columns > 2 ? 'masonry-big' : 'full' )
                : (	$gracioza_columns <= 2 && $gracioza_expanded ? 'masonry-big' : 'masonry')
            )
        ) ) );
    }


    if ( !in_array($gracioza_post_format, array('link', 'aside', 'status', 'quote')) ) {
        ?>
        <div class="post_header entry-header">
            <?php
            do_action('gracioza_action_before_post_title');

            // Post title
            the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
            ?>
        </div><!-- .entry-header -->
        <?php
    }
    ?>

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
                if (!empty($gracioza_components))
                    gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
                            'components' => $gracioza_components,
                            'counters' => $gracioza_counters
                        ), $gracioza_blog_style[0], $gracioza_columns)
                    );
            }
            // More button
            if ( $gracioza_show_learn_more ) {
                ?><p><a class="sc_button sc_button_simple color_style_link3" href="<?php the_permalink(); ?>"><?php esc_html_e('View more', 'gracioza'); ?></a></p><?php
            }

            ?>
        </div><!-- .entry-content --><?php
    }

	?>

</article></div>