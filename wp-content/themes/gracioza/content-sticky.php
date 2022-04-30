<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$gracioza_post_format = get_post_format();
$gracioza_post_format = empty($gracioza_post_format) ? 'standard' : str_replace('post-format-', '', $gracioza_post_format);
$gracioza_animation = gracioza_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($gracioza_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($gracioza_post_format) ); ?>
	<?php echo (!gracioza_is_off($gracioza_animation) ? ' data-animation="'.esc_attr(gracioza_get_animation_classes($gracioza_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	gracioza_show_post_featured(array(
		'thumb_size' => gracioza_get_thumb_size($gracioza_columns==1 ? 'big' : ($gracioza_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($gracioza_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			gracioza_show_post_meta(apply_filters('gracioza_filter_post_meta_args', array(), 'sticky', $gracioza_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>