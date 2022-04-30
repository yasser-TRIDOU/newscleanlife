<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.10
 */

// Footer menu
$gracioza_menu_footer = gracioza_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($gracioza_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php gracioza_show_layout($gracioza_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>