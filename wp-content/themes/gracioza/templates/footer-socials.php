<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.10
 */


// Socials
if ( gracioza_is_on(gracioza_get_theme_option('socials_in_footer')) && ($gracioza_output = gracioza_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php gracioza_show_layout($gracioza_output); ?>
		</div>
	</div>
	<?php
}
?>