<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.10
 */

$gracioza_footer_scheme =  gracioza_is_inherit(gracioza_get_theme_option('footer_scheme')) ? gracioza_get_theme_option('color_scheme') : gracioza_get_theme_option('footer_scheme');
$gracioza_footer_id = str_replace('footer-custom-', '', gracioza_get_theme_option("footer_style"));
if ((int) $gracioza_footer_id == 0) {
	$gracioza_footer_id = gracioza_get_post_id(array(
												'name' => $gracioza_footer_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$gracioza_footer_id = apply_filters('gracioza_filter_get_translated_layout', $gracioza_footer_id);
}
$gracioza_footer_meta = get_post_meta($gracioza_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($gracioza_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($gracioza_footer_id))); 
						if (!empty($gracioza_footer_meta['margin']) != '') 
							echo ' '.esc_attr(gracioza_add_inline_css_class('margin-top: '.gracioza_prepare_css_value($gracioza_footer_meta['margin']).';'));
						?> scheme_<?php echo esc_attr($gracioza_footer_scheme); 
						?>">
	<?php
    // Custom footer's layout
    do_action('gracioza_action_show_layout', $gracioza_footer_id);
	?>
</footer><!-- /.footer_wrap -->
