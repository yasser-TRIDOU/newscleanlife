<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.10
 */

// Footer sidebar
$gracioza_footer_name = gracioza_get_theme_option('footer_widgets');
$gracioza_footer_present = !gracioza_is_off($gracioza_footer_name) && is_active_sidebar($gracioza_footer_name);
if ($gracioza_footer_present) { 
	gracioza_storage_set('current_sidebar', 'footer');
	$gracioza_footer_wide = gracioza_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($gracioza_footer_name) ) {
		dynamic_sidebar($gracioza_footer_name);
	}
	$gracioza_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($gracioza_out)) {
		$gracioza_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $gracioza_out);
		$gracioza_need_columns = true;	//or check: strpos($gracioza_out, 'columns_wrap')===false;
		if ($gracioza_need_columns) {
			$gracioza_columns = max(0, (int) gracioza_get_theme_option('footer_columns'));
			if ($gracioza_columns == 0) $gracioza_columns = min(4, max(1, substr_count($gracioza_out, '<aside ')));
			if ($gracioza_columns > 1)
				$gracioza_out = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($gracioza_columns).' widget', $gracioza_out);
			else
				$gracioza_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($gracioza_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row  sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$gracioza_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($gracioza_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'gracioza_action_before_sidebar' );
				gracioza_show_layout($gracioza_out);
				do_action( 'gracioza_action_after_sidebar' );
				if ($gracioza_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$gracioza_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>