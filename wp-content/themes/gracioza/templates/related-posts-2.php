<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_link = get_permalink();
$gracioza_post_format = get_post_format();
$gracioza_post_format = empty($gracioza_post_format) ? 'standard' : str_replace('post-format-', '', $gracioza_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_2 post_format_'.esc_attr($gracioza_post_format) ); ?>><?php
	gracioza_show_post_featured(array(
		'thumb_size' => gracioza_get_thumb_size( (int) gracioza_get_theme_option('related_posts') == 1 ? 'huge' : 'related' ),
		'show_no_image' => false,
		'singular' => false
		)
	);
	?><div class="post_header entry-header">
		<h6 class="post_title entry-title"><a href="<?php echo esc_url($gracioza_link); ?>"><?php the_title(); ?></a></h6>
        <?php
        if ( in_array(get_post_type(), array( 'post', 'attachment' ) ) ) {
            ?><div class="post_content"><?php
            the_excerpt();
            ?></div><?php
            gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(
                    'components' => 'date,author,counters',
                    'counters' => 'comments',
                    'seo' => false
                ), 'related', 1)
            );
        }
        ?>
	</div>
</div>