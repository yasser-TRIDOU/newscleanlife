<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('gracioza_mailchimp_get_css')) {
	add_filter('gracioza_filter_get_css', 'gracioza_mailchimp_get_css', 10, 4);
	function gracioza_mailchimp_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
form.mc4wp-form .mc4wp-form-fields input[type="email"] {
	{$fonts['input_font-family']}
	
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
CSS;
		
			
			$rad = gracioza_get_border_radius();
			$css['fonts'] .= <<<CSS

form.mc4wp-form .mc4wp-form-fields input[type="email"],
form.mc4wp-form .mc4wp-form-fields input[type="submit"] {

}

CSS;
		}

		
		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS
form.mc4wp-form {
}
.scheme_dark form.mc4wp-form input[type="email"] {
	color: {$colors['bg_color']};
}
.scheme_dark form.mc4wp-form input[type="email"]::-webkit-input-placeholder { color: {$colors['bg_color']}; }
.scheme_dark form.mc4wp-form input[type="email"]::-moz-placeholder { color: {$colors['bg_color']}; }
.scheme_dark form.mc4wp-form input[type="email"]:-ms-input-placeholder { color: {$colors['bg_color']}; }
.scheme_dark form.mc4wp-form input[type="email"]::placeholder { color: {$colors['bg_color']}; }

form.mc4wp-form input[type="email"] {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bg_color']};
	color: {$colors['text']};
}
form.mc4wp-form .mc4wp-alert {
	background-color: {$colors['text_link']};
	border-color: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}
CSS;
		}

		return $css;
	}
}
?>