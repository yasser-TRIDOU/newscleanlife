<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.10
 */

// Copyright area
$gracioza_footer_scheme =  gracioza_is_inherit(gracioza_get_theme_option('footer_scheme')) ? gracioza_get_theme_option('color_scheme') : gracioza_get_theme_option('footer_scheme');
$gracioza_copyright_scheme = gracioza_is_inherit(gracioza_get_theme_option('copyright_scheme')) ? $gracioza_footer_scheme : gracioza_get_theme_option('copyright_scheme');
?> 
<div class="footer_copyright_wrap scheme_<?php echo esc_attr($gracioza_copyright_scheme); ?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$gracioza_copyright = gracioza_prepare_macros(gracioza_get_theme_option('copyright'));
				if (!empty($gracioza_copyright)) {
					// Replace {date_format} on the current date in the specified format
					if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $gracioza_copyright, $gracioza_matches)) {
						$gracioza_copyright = str_replace($gracioza_matches[1], date_i18n(str_replace(array('{', '}'), '', $gracioza_matches[1])), $gracioza_copyright);
					}
					// Display copyright
					echo wp_kses_data(nl2br($gracioza_copyright));
				}
			?></div>
		</div>
	</div>
</div>
