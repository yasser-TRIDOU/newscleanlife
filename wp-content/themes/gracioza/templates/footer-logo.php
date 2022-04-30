<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.10
 */

// Logo
if (gracioza_is_on(gracioza_get_theme_option('logo_in_footer'))) {
	$gracioza_logo_image = '';
	if (gracioza_is_on(gracioza_get_theme_option('logo_retina_enabled')) && gracioza_get_retina_multiplier(2) > 1)
		$gracioza_logo_image = gracioza_get_theme_option( 'logo_footer_retina' );
	if (empty($gracioza_logo_image)) 
		$gracioza_logo_image = gracioza_get_theme_option( 'logo_footer' );
	$gracioza_logo_text   = get_bloginfo( 'name' );
	if (!empty($gracioza_logo_image) || !empty($gracioza_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($gracioza_logo_image)) {
					$gracioza_attr = gracioza_getimagesize($gracioza_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($gracioza_logo_image).'" class="logo_footer_image" alt="'.esc_attr__('img', 'gracioza').'"'.(!empty($gracioza_attr[3]) ? ' ' . wp_kses_data($gracioza_attr[3]) : '').'></a>' ;
				} else if (!empty($gracioza_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($gracioza_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>