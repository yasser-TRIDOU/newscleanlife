<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'gracioza_vc_get_css' ) ) {
	add_filter( 'gracioza_filter_get_css', 'gracioza_vc_get_css', 10, 4 );
	function gracioza_vc_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
	
.vc_message_box p,		
.vc_progress_bar.vc_progress_bar_narrow[class*="vc_custom_"] .vc_single_bar .vc_label .vc_label_units,
.vc_tta.vc_tta-accordion .vc_tta-panel-title .vc_tta-title-text {
	{$fonts['h5_font-family']}
}
.vc_progress_bar.vc_progress_bar_narrow[class*="vc_custom_"] .vc_single_bar .vc_label {
	{$fonts['logo_font-family']}
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units {
	{$fonts['info_font-family']}
}

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Row and columns */
.scheme_self.vc_section,
.scheme_self.wpb_row,
.scheme_self.wpb_column > .vc_column-inner > .wpb_wrapper,
.scheme_self.wpb_text_column {
	color: {$colors['text']};
}


/* Accordion */
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon {
	color: {$colors['inverse_link']};
	background-color: {$colors['extra_bg_color']};
}
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:before,
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:after {
	border-color: {$colors['inverse_link']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a {
	color: {$colors['text_dark']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover {
	color: {$colors['text_link']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover .vc_tta-controls-icon {
	color: {$colors['inverse_link']};
	background-color: {$colors['extra_bg_color']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:before,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:after {
	border-color: {$colors['inverse_link']};
}

/* Tabs */
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab > a {
	color: {$colors['text']};
	background-color: {$colors['bg_color_0']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab > a:hover,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tabs-list .vc_tta-tab.vc_active > a {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color_0']};
}
.vc_tta.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a .vc_tta-title-text:before {
    background-color: {$colors['text_dark']};
}

/* Separator */
.vc_separator.vc_sep_color_grey .vc_sep_line {
	border-color: {$colors['bd_color']};
}

/* Progress bar */
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar {
	background-color: {$colors['input_bg_color']};
}
.vc_progress_bar.vc_progress_bar_narrow.vc_progress-bar-color-bar_red .vc_single_bar .vc_bar {
	background-color: {$colors['extra_bg_color']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label {
	color: {$colors['text_dark']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units {
	color: {$colors['text_dark']};
}
.vc_progress_bar .vc_single_bar .vc_bar {
	background-color: {$colors['extra_bg_color']};
}

.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion .vc_tta-panel {
    background-color: {$colors['input_bg_color']};
}
.vc_tta.vc_tta-accordion .vc_tta-panel-body {
    background-color: {$colors['bg_color']}!important;
}
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic.vc_tta-accordion .vc_tta-panel .vc_tta-panel-title>a,
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic.vc_tta-accordion .vc_tta-panel.vc_active .vc_tta-panel-title>a {
	color: {$colors['text_dark']};
}

.vc_progress_bar.vc_progress_bar_narrow[class*="vc_custom_"] .vc_single_bar .vc_bar {
    background-color: {$colors['text_dark']};
}
.vc_progress_bar.vc_progress_bar_narrow[class*="vc_custom_"] .vc_single_bar .vc_label .vc_label_units {
    color: {$colors['alter_link']};
}

CSS;
		}
		
		return $css;
	}
}
?>