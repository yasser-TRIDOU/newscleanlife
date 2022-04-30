<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

$gracioza_args = get_query_var('gracioza_logo_args');

// Site logo
$gracioza_logo_type   = isset($gracioza_args['type']) ? $gracioza_args['type'] : '';
$gracioza_logo_image  = gracioza_get_logo_image($gracioza_logo_type);
$gracioza_logo_text   = gracioza_is_on(gracioza_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$gracioza_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($gracioza_logo_image) || !empty($gracioza_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo is_front_page() ? '#' : esc_url(home_url('/')); ?>"><?php
		if (!empty($gracioza_logo_image)) {
			if (empty($gracioza_logo_type) && function_exists('the_custom_logo') && (int) $gracioza_logo_image > 0) {
				the_custom_logo();
			} else {
				$gracioza_attr = gracioza_getimagesize($gracioza_logo_image);
				echo '<img src="'.esc_url($gracioza_logo_image).'" alt="'.esc_attr__('img', 'gracioza').'"'.(!empty($gracioza_attr[3]) ? ' '.wp_kses_data($gracioza_attr[3]) : '').'>';
			}
		} else {
			gracioza_show_layout(gracioza_prepare_macros($gracioza_logo_text), '<span class="logo_text">', '</span>');
			gracioza_show_layout(gracioza_prepare_macros($gracioza_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>