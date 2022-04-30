<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

// Header sidebar
$gracioza_header_name = gracioza_get_theme_option('header_widgets');
$gracioza_header_present = !gracioza_is_off($gracioza_header_name) && is_active_sidebar($gracioza_header_name);
if ($gracioza_header_present) { 
	gracioza_storage_set('current_sidebar', 'header');
	$gracioza_header_wide = gracioza_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($gracioza_header_name) ) {
		dynamic_sidebar($gracioza_header_name);
	}
	$gracioza_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($gracioza_widgets_output)) {
		$gracioza_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $gracioza_widgets_output);
		$gracioza_need_columns = strpos($gracioza_widgets_output, 'columns_wrap')===false;
		if ($gracioza_need_columns) {
			$gracioza_columns = max(0, (int) gracioza_get_theme_option('header_columns'));
			if ($gracioza_columns == 0) $gracioza_columns = min(6, max(1, substr_count($gracioza_widgets_output, '<aside ')));
			if ($gracioza_columns > 1)
				$gracioza_widgets_output = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($gracioza_columns).' widget', $gracioza_widgets_output);
			else
				$gracioza_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($gracioza_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$gracioza_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($gracioza_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'gracioza_action_before_sidebar' );
				gracioza_show_layout($gracioza_widgets_output);
				do_action( 'gracioza_action_after_sidebar' );
				if ($gracioza_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$gracioza_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>