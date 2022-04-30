<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

if (gracioza_sidebar_present()) {
	ob_start();
	$gracioza_sidebar_name = gracioza_get_theme_option('sidebar_widgets');
	gracioza_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($gracioza_sidebar_name) ) {
		dynamic_sidebar($gracioza_sidebar_name);
	}
	$gracioza_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($gracioza_out)) {
		$gracioza_sidebar_position = gracioza_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($gracioza_sidebar_position); ?> widget_area<?php if (!gracioza_is_inherit(gracioza_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(gracioza_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'gracioza_action_before_sidebar' );
				gracioza_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $gracioza_out));
				do_action( 'gracioza_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>