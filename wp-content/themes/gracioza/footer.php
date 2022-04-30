<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

						// Widgets area inside page content
						gracioza_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					gracioza_create_widgets_area('widgets_below_page');

					$gracioza_body_style = gracioza_get_theme_option('body_style');
					if ($gracioza_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$gracioza_footer_type = gracioza_get_theme_option("footer_type");
			if ($gracioza_footer_type == 'custom' && !gracioza_is_layouts_available())
				$gracioza_footer_type = 'default';
			get_template_part( "templates/footer-{$gracioza_footer_type}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (gracioza_is_on(gracioza_get_theme_option('debug_mode')) && gracioza_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(gracioza_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>