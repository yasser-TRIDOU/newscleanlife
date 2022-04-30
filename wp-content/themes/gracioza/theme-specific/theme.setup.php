<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.22
 */

if (!defined("GRACIOZA_THEME_FREE")) define("GRACIOZA_THEME_FREE", false);
if (!defined("GRACIOZA_THEME_FREE_WP")) define("GRACIOZA_THEME_FREE_WP", false);

// Theme storage
$GRACIOZA_STORAGE = array(
	// Theme required plugin's slugs
	'required_plugins' => array_merge(

		// List of plugins for both - FREE and PREMIUM versions
		//-----------------------------------------------------
		array(
			// Required plugins
			// DON'T COMMENT OR REMOVE NEXT LINES!
			'trx_addons'					=> esc_html__('ThemeREX Addons', 'gracioza'),

			// Recommended (supported) plugins fot both (lite and full) versions
			// If plugin not need - comment (or remove) it
			'contact-form-7'				=> esc_html__('Contact Form 7', 'gracioza'),
			'elegro-payment'				=> esc_html__('elegro Crypto Payment', 'gracioza'),
			'mailchimp-for-wp'				=> esc_html__('MailChimp for WP', 'gracioza'),
			'trx_updater'					=> esc_html__('ThemeREX Updater', 'gracioza'),
			'trx_socials'					=> esc_html__('ThemeREX Socials', 'gracioza'),
			'woocommerce'					=> esc_html__('WooCommerce', 'gracioza'),
            'wp-gdpr-compliance'            => esc_html__('Cookie Information', 'gracioza'),
		),

		// List of plugins for PREMIUM version only
		//-----------------------------------------------------
		GRACIOZA_THEME_FREE 
			? array(
					// Recommended (supported) plugins for the FREE (lite) version
					)
			: array(
					// Recommended (supported) plugins for the PRO (full) version
					// If plugin not need - comment (or remove) it
					'calculated-fields-form'	=> esc_html__('Calculated Fields Form', 'gracioza'),
					'essential-grid'			=> esc_html__('Essential Grid', 'gracioza'),
					'revslider'					=> esc_html__('Revolution Slider', 'gracioza'),
					'js_composer'				=> esc_html__('WPBakery Page Builder', 'gracioza'),
				)
	),
	
	// Theme-specific URLs (will be escaped in place of the output)
	'theme_demo_url'	=> 'http://gracioza.ancorathemes.com',
	'theme_doc_url'		=> 'http://gracioza.ancorathemes.com/doc',
	'theme_download_url'=> 'https://themeforest.net/item/gracioza-weight-loss-blog-wordpress-theme/21532410',

	'theme_support_url'	=> 'https://themerex.net/support/',

	'theme_video_url'	=> 'https://www.youtube.com/channel/UCdIjRh7-lPVHqTTKpaf8PLA',
);

// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)

if ( !function_exists('gracioza_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'gracioza_customizer_theme_setup1', 1 );
	function gracioza_customizer_theme_setup1() {

		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		gracioza_storage_set('settings', array(
			
			'duplicate_options'		=> 'child',		// none  - use separate options for template and child-theme
													// child - duplicate theme options from the main theme to the child-theme only
													// both  - sinchronize changes in the theme options between main and child themes
			
			'custmize_refresh'		=> 'auto',		// Refresh method for preview area in the Appearance - Customize:
													// auto - refresh preview area on change each field with Theme Options
													// manual - refresh only obn press button 'Refresh' at the top of Customize frame
		
			'max_load_fonts'		=> 5,			// Max fonts number to load from Google fonts or from uploaded fonts
		
			'comment_maxlength'		=> 1000,		// Max length of the message from contact form

			'comment_after_name'	=> true,		// Place 'comment' field before the 'name' and 'email'
			
			'socials_type'			=> 'icons',		// Type of socials:
													// icons - use font icons to present social networks
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_type'			=> 'icons',		// Type of other icons:
													// icons - use font icons to present icons
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_selector'		=> 'internal',	// Icons selector in the shortcodes:
													// vc (default) - standard VC icons selector (very slow and don't support images)
													// internal - internal popup with plugin's or theme's icons list (fast)
			'check_min_version'		=> true,		// Check if exists a .min version of .css and .js and return path to it
													// instead the path to the original file
													// (if debug_mode is off and modification time of the original file < time of the .min file)
			'autoselect_menu'		=> false,		// Show any menu if no menu selected in the location 'main_menu'
													// (for example, the theme is just activated)
			'disable_jquery_ui'		=> false,		// Prevent loading custom jQuery UI libraries in the third-party plugins
		
			'use_mediaelements'		=> true,		// Load script "Media Elements" to play video and audio
			
			'tgmpa_upload'			=> false		// Allow upload not pre-packaged plugins via TGMPA
		));


		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		
		gracioza_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Overpass',
				'family' => 'sans-serif',
				'styles' => '400,400italic,700,700italic'
				),
            array(
                'name'	 => 'Covered By Your Grace',
                'family' => 'cursive',
                'styles' => '400'
            ),
			array(
				'name'   => 'Rubik',
				'family' => 'sans-serif',
                'styles' => '400,400italic,500,500italic,700,700italic'
				)
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		gracioza_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		gracioza_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'gracioza'),
				'description'		=> esc_html__('Font settings of the main text of the site', 'gracioza'),
				'font-family'		=> '"Overpass",sans-serif',
				'font-size' 		=> '1em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.54',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '',
				'margin-top'		=> '0em',
				'margin-bottom'		=> '1.54em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'gracioza'),
				'font-family'		=> '"Rubik",sans-serif',
				'font-size' 		=> '5.294em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.9583em',
				'margin-bottom'		=> '0.62em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'gracioza'),
				'font-family'		=> '"Rubik",sans-serif',
				'font-size' 		=> '4.706em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.475em',
				'margin-bottom'		=> '0.65em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'gracioza'),
				'font-family'		=> '"Rubik",sans-serif',
				'font-size' 		=> '3.824em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.09',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.85em',
				'margin-bottom'		=> '0.83em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'gracioza'),
				'font-family'		=> '"Rubik",sans-serif',
				'font-size' 		=> '2.941em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.2',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '2.25em',
				'margin-bottom'		=> '0.78em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'gracioza'),
				'font-family'		=> '"Rubik",sans-serif',
				'font-size' 		=> '2.353em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.15',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '2.8em',
				'margin-bottom'		=> '0.725em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'gracioza'),
				'font-family'		=> '"Rubik",sans-serif',
				'font-size' 		=> '1.647em',
				'font-weight'		=> '500',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '3.9em',
				'margin-bottom'		=> '0.95em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'gracioza'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'gracioza'),
				'font-family'		=> '"Covered By Your Grace",cursive',
				'font-size' 		=> '1.8em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1px'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'gracioza'),
				'font-family'		=> '"Overpass",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1.4px'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'gracioza'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'gracioza'),
				'font-family'		=> 'inherit',
				'font-size' 		=> '16px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',	// Attention! Firefox don't allow line-height less then 1.5em in the select
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'gracioza'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'gracioza'),
				'font-family'		=> 'inherit',
				'font-size' 		=> '14px',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1.4px',
				'margin-top'		=> '0.4em',
				'margin-bottom'		=> ''
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'gracioza'),
				'description'		=> esc_html__('Font settings of the main menu items', 'gracioza'),
				'font-family'		=> '"Overpass",sans-serif',
				'font-size' 		=> '16px',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0.8px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'gracioza'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'gracioza'),
                'font-family'		=> '"Overpass",sans-serif',
                'font-size' 		=> '16px',
                'font-weight'		=> '700',
                'font-style'		=> 'normal',
                'line-height'		=> 'normal',
                'text-decoration'	=> 'none',
                'text-transform'	=> 'uppercase',
                'letter-spacing'	=> '0.8px'
				)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		gracioza_storage_set('scheme_color_groups', array(
			'main'	=> array(
							'title'			=> esc_html__('Main', 'gracioza'),
							'description'	=> esc_html__('Colors of the main content area', 'gracioza')
							),
			'alter'	=> array(
							'title'			=> esc_html__('Alter', 'gracioza'),
							'description'	=> esc_html__('Colors of the alternative blocks (sidebars, etc.)', 'gracioza')
							),
			'extra'	=> array(
							'title'			=> esc_html__('Extra', 'gracioza'),
							'description'	=> esc_html__('Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'gracioza')
							),
			'inverse' => array(
							'title'			=> esc_html__('Inverse', 'gracioza'),
							'description'	=> esc_html__('Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'gracioza')
							),
			'input'	=> array(
							'title'			=> esc_html__('Input', 'gracioza'),
							'description'	=> esc_html__('Colors of the form fields (text field, textarea, select, etc.)', 'gracioza')
							),
			)
		);
		gracioza_storage_set('scheme_color_names', array(
			'bg_color'	=> array(
							'title'			=> esc_html__('Background color', 'gracioza'),
							'description'	=> esc_html__('Background color of this block in the normal state', 'gracioza')
							),
			'bg_hover'	=> array(
							'title'			=> esc_html__('Background hover', 'gracioza'),
							'description'	=> esc_html__('Background color of this block in the hovered state', 'gracioza')
							),
			'bd_color'	=> array(
							'title'			=> esc_html__('Border color', 'gracioza'),
							'description'	=> esc_html__('Border color of this block in the normal state', 'gracioza')
							),
			'bd_hover'	=>  array(
							'title'			=> esc_html__('Border hover', 'gracioza'),
							'description'	=> esc_html__('Border color of this block in the hovered state', 'gracioza')
							),
			'text'		=> array(
							'title'			=> esc_html__('Text', 'gracioza'),
							'description'	=> esc_html__('Color of the plain text inside this block', 'gracioza')
							),
			'text_dark'	=> array(
							'title'			=> esc_html__('Text dark', 'gracioza'),
							'description'	=> esc_html__('Color of the dark text (bold, header, etc.) inside this block', 'gracioza')
							),
			'text_light'=> array(
							'title'			=> esc_html__('Text light', 'gracioza'),
							'description'	=> esc_html__('Color of the light text (post meta, etc.) inside this block', 'gracioza')
							),
			'text_link'	=> array(
							'title'			=> esc_html__('Link', 'gracioza'),
							'description'	=> esc_html__('Color of the links inside this block', 'gracioza')
							),
			'text_hover'=> array(
							'title'			=> esc_html__('Link hover', 'gracioza'),
							'description'	=> esc_html__('Color of the hovered state of links inside this block', 'gracioza')
							),
			'text_link2'=> array(
							'title'			=> esc_html__('Link 2', 'gracioza'),
							'description'	=> esc_html__('Color of the accented texts (areas) inside this block', 'gracioza')
							),
			'text_hover2'=> array(
							'title'			=> esc_html__('Link 2 hover', 'gracioza'),
							'description'	=> esc_html__('Color of the hovered state of accented texts (areas) inside this block', 'gracioza')
							),
			'text_link3'=> array(
							'title'			=> esc_html__('Link 3', 'gracioza'),
							'description'	=> esc_html__('Color of the other accented texts (buttons) inside this block', 'gracioza')
							),
			'text_hover3'=> array(
							'title'			=> esc_html__('Link 3 hover', 'gracioza'),
							'description'	=> esc_html__('Color of the hovered state of other accented texts (buttons) inside this block', 'gracioza')
							)
			)
		);
		gracioza_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'gracioza'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#ffffff',
					'bd_color'			=> '#e5e5e5',
		
					// Text and links colors
					'text'				=> '#898c94',
					'text_light'		=> '#b7b7b7',
					'text_dark'			=> '#213670',
					'text_link'			=> '#f6b5ad',
					'text_hover'		=> '#bedf56',
					'text_link2'		=> '#bedf56',
					'text_hover2'		=> '#f6b5ad',
					'text_link3'		=> '#213670',
					'text_hover3'		=> '#bedf56',
		
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#ffe1dd',
					'alter_bg_hover'	=> '#e6e8eb',
					'alter_bd_color'	=> '#e5e5e5',
					'alter_bd_hover'	=> '#dadada',
					'alter_text'		=> '#333333',
					'alter_light'		=> '#b7b7b7',
					'alter_dark'		=> '#254485',
					'alter_link'		=> '#efb0a9',
					'alter_hover'		=> '#72cfd5',
					'alter_link2'		=> '#3b5791',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',
		
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#61d0e0',
					'extra_bg_hover'	=> '#28272e',
					'extra_bd_color'	=> '#313131',
					'extra_bd_hover'	=> '#3d3d3d',
					'extra_text'		=> '#bfbfbf',
					'extra_light'		=> '#afafaf',
					'extra_dark'		=> '#ffffff',
					'extra_link'		=> '#72cfd5',
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#f7f7f7',
					'input_bg_hover'	=> '#f7f7f7',
					'input_bd_color'	=> '#f7f7f7',
					'input_bd_hover'	=> '#bedf56',
					'input_text'		=> '#898c94',
					'input_light'		=> '#898c94',
					'input_dark'		=> '#898c94',

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#67bcc1',
					'inverse_bd_hover'	=> '#5aa4a9',
					'inverse_text'		=> '#1d1d1d',
					'inverse_light'		=> '#333333',
					'inverse_dark'		=> '#000000',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#ffffff'
				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'gracioza'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#213670',
					'bd_color'			=> '#2e2c33',
		
					// Text and links colors
					'text'				=> '#7a8bb1',
					'text_light'		=> '#254485',
					'text_dark'			=> '#ffffff',
                    'text_link'			=> '#f6b5ad',
                    'text_hover'		=> '#bedf56',
                    'text_link2'		=> '#bedf56',
                    'text_hover2'		=> '#f6b5ad',
                    'text_link3'		=> '#213670',
                    'text_hover3'		=> '#bedf56',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#1e1d22',
					'alter_bg_hover'	=> '#333333',
					'alter_bd_color'	=> '#464646',
					'alter_bd_hover'	=> '#4a4a4a',
					'alter_text'		=> '#a6a6a6',
					'alter_light'		=> '#5f5f5f',
					'alter_dark'		=> '#ffffff',
					'alter_link'		=> '#3b5791',
					'alter_hover'		=> '#fe7259',
					'alter_link2'		=> '#8be77c',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#61d0e0',
					'extra_bg_hover'	=> '#28272e',
					'extra_bd_color'	=> '#464646',
					'extra_bd_hover'	=> '#4a4a4a',
					'extra_text'		=> '#a6a6a6',
					'extra_light'		=> '#5f5f5f',
					'extra_dark'		=> '#ffffff',
					'extra_link'		=> '#ffaa5f',
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',

					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#2e2d32',
					'input_bg_hover'	=> '#2e2d32',
					'input_bd_color'	=> '#2e2d32',
					'input_bd_hover'	=> '#353535',
					'input_text'		=> '#b7b7b7',
					'input_light'		=> '#5f5f5f',
					'input_dark'		=> '#ffffff',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#e36650',
					'inverse_bd_hover'	=> '#cb5b47',
					'inverse_text'		=> '#1d1d1d',
					'inverse_light'		=> '#5f5f5f',
					'inverse_dark'		=> '#000000',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#ffffff'
				)
			)
		
		));
		
		// Simple schemes substitution
		gracioza_storage_set('schemes_simple', array(
			// Main color	// Slave elements and it's darkness koef.
			'text_link'		=> array('alter_hover' => 1,	'extra_link' => 1, 'inverse_bd_color' => 0.85, 'inverse_bd_hover' => 0.7),
			'text_hover'	=> array('alter_link' => 1,		'extra_hover' => 1),
			'text_link2'	=> array('alter_hover2' => 1,	'extra_link2' => 1),
			'text_hover2'	=> array('alter_link2' => 1,	'extra_hover2' => 1),
			'text_link3'	=> array('alter_hover3' => 1,	'extra_link3' => 1),
			'text_hover3'	=> array('alter_link3' => 1,	'extra_hover3' => 1)
		));

		// Additional colors for each scheme
		// Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
		//				'alpha' - to make color transparent (0.0 - 1.0)
		//				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
		gracioza_storage_set('scheme_colors_add', array(
			'bg_color_0'		=> array('color' => 'bg_color',			'alpha' => 0),
			'bg_color_02'		=> array('color' => 'bg_color',			'alpha' => 0.2),
			'bg_color_025'		=> array('color' => 'bg_color',			'alpha' => 0.25),
			'bg_color_07'		=> array('color' => 'bg_color',			'alpha' => 0.7),
			'bg_color_08'		=> array('color' => 'bg_color',			'alpha' => 0.8),
			'bg_color_09'		=> array('color' => 'bg_color',			'alpha' =>  0.9),
			'alter_bg_color_07'	=> array('color' => 'alter_bg_color',	'alpha' => 0.7),
			'alter_bg_color_04'	=> array('color' => 'alter_bg_color',	'alpha' => 0.4),
			'alter_bg_color_02'	=> array('color' => 'alter_bg_color',	'alpha' => 0.2),
			'alter_bd_color_02'	=> array('color' => 'alter_bd_color',	'alpha' => 0.2),
			'extra_bg_color_07'	=> array('color' => 'extra_bg_color',	'alpha' => 0.7),
			'text_dark_01'		=> array('color' => 'text_dark',		'alpha' => 0.1),
			'text_dark_02'		=> array('color' => 'text_dark',		'alpha' => 0.2),
			'text_dark_07'		=> array('color' => 'text_dark',		'alpha' => 0.7),
			'text_link_02'		=> array('color' => 'text_link',		'alpha' => 0.2),
			'text_link_07'		=> array('color' => 'text_link',		'alpha' => 0.7),
			'text_link_blend'	=> array('color' => 'text_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5),
			'alter_link_blend'	=> array('color' => 'alter_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme specific thumb sizes
		// -----------------------------------------------------------------
		gracioza_storage_set('theme_thumbs', apply_filters('gracioza_filter_add_thumb_sizes', array(
			'gracioza-thumb-huge'		=> array(
												'size'	=> array(1170, 658, true),
												'title' => esc_html__( 'Huge image', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-huge'
												),
			'gracioza-thumb-big' 		=> array(
												'size'	=> array( 816, 331, true),
												'title' => esc_html__( 'Large image', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-big'
												),

			'gracioza-thumb-med' 		=> array(
												'size'	=> array( 370, 208, true),
												'title' => esc_html__( 'Medium image', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-medium'
												),
            'gracioza-thumb-related' 		=> array(
												'size'	=> array( 248, 210, true),
												'title' => esc_html__( 'Image for related post', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-related'
												),

            'gracioza-thumb-sticky' 		=> array(
												'size'	=> array( 816, 304, true),
												'title' => esc_html__( 'Image for sticky post', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-sticky'
												),

            'gracioza-thumb-services' 		=> array(
												'size'	=> array( 411, 589, true),
												'title' => esc_html__( 'Image for services', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-services'
												),

            'gracioza-thumb-information' 		=> array(
												'size'	=> array( 410, 218, true),
												'title' => esc_html__( 'Image for services', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-information'
												),


            'gracioza-thumb-team' 		=> array(
												'size'	=> array( 300, 300, true),
												'title' => esc_html__( 'Image for team', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-team'
												),

			'gracioza-thumb-tiny' 		=> array(
												'size'	=> array( 130, 130, true),
												'title' => esc_html__( 'Small square avatar', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-tiny'
												),

            'gracioza-thumb-magazine' 		=> array(
                                                'size'	=> array( 300, 248, true),
                                                'title' => esc_html__( 'Image for magazine', 'gracioza' ),
                                                'subst'	=> 'trx_addons-thumb-magazine'
                                                ),

			'gracioza-thumb-blogger' 		=> array(
				'size'	=> array( 900, 900, true),
				'title' => esc_html__( 'Image for blogger', 'gracioza' ),
				'subst'	=> 'trx_addons-thumb-blogger'
			),


			'gracioza-thumb-masonry-big' => array(
												'size'	=> array( 760,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry Large (scaled)', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-masonry-big'
												),

			'gracioza-thumb-masonry'		=> array(
												'size'	=> array( 370,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry (scaled)', 'gracioza' ),
												'subst'	=> 'trx_addons-thumb-masonry'
												)
			))
		);
	}
}




//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'gracioza_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'gracioza_importer_set_options', 9 );
	function gracioza_importer_set_options($options=array()) {
		if (is_array($options)) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			$options['demo_url'] = esc_url(gracioza_get_protocol() . '://demofiles.ancorathemes.com/gracioza/');
			// Required plugins
			$options['required_plugins'] = array_keys(gracioza_storage_get('required_plugins'));
			// Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
			// Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
			$options['regenerate_thumbnails'] = 0;
			// Default demo
			$options['files']['default']['title'] = esc_html__('Gracioza Demo', 'gracioza');
			$options['files']['default']['domain_dev'] = esc_url('http://thehealthybodyguide.dv.ancorathemes.com');		// Developers domain
			$options['files']['default']['domain_demo']= esc_url('http://gracioza.ancorathemes.com');		// Demo-site domain
			// Banners
			$options['banners'] = array(
				array(
					'image' => gracioza_get_file_url('theme-specific/theme.about/images/frontpage.png'),
					'title' => esc_html__('Front page Builder', 'gracioza'),
					'content' => wp_kses(__('Create your Frontpage right in WordPress Customizer! To do this, you will not need neither the WPBakery Page Builder nor any other Builder. Just turn on/off sections, and fill them with content and decorate to your liking', 'gracioza'), 'gracioza_kses_content'),
					'link_url' => esc_url('//www.youtube.com/watch?v=VT0AUbMl_KA'),
					'link_caption' => esc_html__('More about Frontpage Builder', 'gracioza'),
					'duration' => 20
					),
				array(
					'image' => gracioza_get_file_url('theme-specific/theme.about/images/layouts.png'),
					'title' => esc_html__('Custom layouts', 'gracioza'),
					'content' => wp_kses(__('Forget about problems with customization of header or footer! You can edit any of layout without any changes in CSS or HTML directly in Visual Builder. Moreover - you can easily create your own headers and footers and use them along with built-in', 'gracioza'), 'gracioza_kses_content'),
					'link_url' => esc_url('//www.youtube.com/watch?v=pYhdFVLd7y4'),
					'link_caption' => esc_html__('More about Custom Layouts', 'gracioza'),
					'duration' => 20
					),
				array(
					'image' => gracioza_get_file_url('theme-specific/theme.about/images/documentation.png'),
					'title' => esc_html__('Read full documentation', 'gracioza'),
					'content' => wp_kses(__('Need more details? Please check our full online documentation for detailed information on how to use Gracioza', 'gracioza'), 'gracioza_kses_content'),
					'link_url' => esc_url(gracioza_storage_get('theme_doc_url')),
					'link_caption' => esc_html__('Online documentation', 'gracioza'),
					'duration' => 15
					),
				array(
					'image' => gracioza_get_file_url('theme-specific/theme.about/images/video-tutorials.png'),
					'title' => esc_html__('Video tutorials', 'gracioza'),
					'content' => wp_kses(__('No time for reading documentation? Check out our video tutorials and learn how to customize Gracioza in detail.', 'gracioza'), 'gracioza_kses_content'),
					'link_url' => esc_url(gracioza_storage_get('theme_video_url')),
					'link_caption' => esc_html__('Video tutorials', 'gracioza'),
					'duration' => 15
					),
				array(
					'image' => gracioza_get_file_url('theme-specific/theme.about/images/studio.png'),
					'title' => esc_html__('Website Customization studio', 'gracioza'),
					'content' => wp_kses(__('We can make a website based on this theme for a very fair price.
We can implement any extra functional: translate your website, WPML implementation and many other customization according to your request.', 'gracioza'), 'gracioza_kses_content'),
					'link_url' => esc_url('//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall'),
					'link_caption' => esc_html__('Contact us', 'gracioza'),
					'duration' => 25
					)
				);
		}
		return $options;
	}
}




// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('gracioza_create_theme_options')) {

	function gracioza_create_theme_options() {

		// Message about options override. 
		// Attention! Not need esc_html() here, because this message put in wp_kses_data() below
		$msg_override = __('<b>Attention!</b> Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages', 'gracioza');

		gracioza_storage_set('options', array(
		
			// 'Logo & Site Identity'
			'title_tagline' => array(
				"title" => esc_html__('Logo & Site Identity', 'gracioza'),
				"desc" => '',
				"priority" => 10,
				"type" => "section"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo in the header', 'gracioza'),
				"desc" => '',
				"priority" => 20,
				"type" => "info",
				),
			'logo_text' => array(
				"title" => esc_html__('Use Site Name as Logo', 'gracioza'),
				"desc" => wp_kses_data( __('Use the site title and tagline as a text logo if no image is selected', 'gracioza') ),
				"class" => "gracioza_column-1_2 gracioza_new_row",
				"priority" => 30,
				"std" => 1,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_retina_enabled' => array(
				"title" => esc_html__('Allow retina display logo', 'gracioza'),
				"desc" => wp_kses_data( __('Show fields to select logo images for Retina display', 'gracioza') ),
				"class" => "gracioza_column-1_2",
				"priority" => 40,
				"refresh" => false,
				"std" => 0,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_max_height' => array(
				"title" => esc_html__('Logo max. height', 'gracioza'),
				"desc" => wp_kses_data( __("Max. height of the logo image (in pixels). Maximum size of logo depends on the actual size of the picture", 'gracioza') ),
				"std" => 80,
				"min" => 20,
				"max" => 160,
				"step" => 1,
				"refresh" => false,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "slider"
				),
			// Parameter 'logo' was replaced with standard WordPress 'custom_logo'
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'gracioza') ),
				"class" => "gracioza_column-1_2",
				"priority" => 70,
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile_header' => array(
				"title" => esc_html__('Logo for the mobile header', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile header (if enabled in the section "Header - Header mobile"', 'gracioza') ),
				"class" => "gracioza_column-1_2 gracioza_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_header_retina' => array(
				"title" => esc_html__('Logo for the mobile header for Retina', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'gracioza') ),
				"class" => "gracioza_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile' => array(
				"title" => esc_html__('Logo mobile', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile menu', 'gracioza') ),
				"class" => "gracioza_column-1_2 gracioza_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_retina' => array(
				"title" => esc_html__('Logo mobile for Retina', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'gracioza') ),
				"class" => "gracioza_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "image"
				),
			'logo_side' => array(
				"title" => esc_html__('Logo side', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu', 'gracioza') ),
				"class" => "gracioza_column-1_2 gracioza_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_side_retina' => array(
				"title" => esc_html__('Logo side for Retina', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'gracioza') ),
				"class" => "gracioza_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "image"
				),
			
		
		
			// 'General settings'
			'general' => array(
				"title" => esc_html__('General Settings', 'gracioza'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 20,
				"type" => "section",
				),

			'general_layout_info' => array(
				"title" => esc_html__('Layout', 'gracioza'),
				"desc" => '',
				"type" => "info",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'gracioza'),
				"desc" => wp_kses_data( __('Select width of the body content', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'gracioza')
				),
				"refresh" => false,
				"std" => 'wide',
				"options" => gracioza_get_list_body_styles(),
				"type" => "select"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'gracioza') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'gracioza')
				),
				"std" => '',
				"hidden" => true,
				"type" => "image"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'gracioza'),
				"desc" => wp_kses_data( __('Remove margins above and below the content area', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'gracioza')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),

			'general_sidebar_info' => array(
				"title" => esc_html__('Sidebar', 'gracioza'),
				"desc" => '',
				"type" => "info",
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'gracioza'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gracioza')
				),
				"std" => 'right',
				"options" => array(),
				"type" => "switch"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'gracioza'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gracioza')
				),
				"dependency" => array(
					'sidebar_position' => array('left', 'right')
				),
				"std" => 'sidebar_widgets',
				"options" => array(),
				"type" => "select"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'gracioza'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'gracioza') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),


			'general_widgets_info' => array(
				"title" => esc_html__('Additional widgets', 'gracioza'),
				"desc" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "info",
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets at the top of the page', 'gracioza'),
				"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gracioza')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'gracioza'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gracioza')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'gracioza'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gracioza')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets at the bottom of the page', 'gracioza'),
				"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gracioza')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
				),

			'general_effects_info' => array(
				"title" => esc_html__('Design & Effects', 'gracioza'),
				"desc" => '',
				"type" => "info",
				),
			'border_radius' => array(
				"title" => esc_html__('Border radius', 'gracioza'),
				"desc" => wp_kses_data( __('Specify the border radius of the form fields and buttons in pixels or other valid CSS units', 'gracioza') ),
				"std" => 0,
				"type" => "text"
				),

			'general_misc_info' => array(
				"title" => esc_html__('Miscellaneous', 'gracioza'),
				"desc" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "info",
				),
			'seo_snippets' => array(
				"title" => esc_html__('SEO snippets', 'gracioza'),
				"desc" => wp_kses_data( __('Add structured data markup to the single posts and pages', 'gracioza') ),
				"std" => 0,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),

            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'gracioza'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'gracioza') ),
                "std"   => wp_kses( __( 'I agree that my submitted data is being collected and stored.', 'gracioza'), 'gracioza_kses_content' ),
                "type"  => "text"
            ),
		
		
			// 'Header'
			'header' => array(
				"title" => esc_html__('Header', 'gracioza'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 30,
				"type" => "section"
				),

			'header_style_info' => array(
				"title" => esc_html__('Header style', 'gracioza'),
				"desc" => '',
				"type" => "info"
				),
			'header_type' => array(
				"title" => esc_html__('Header style', 'gracioza'),
				"desc" => wp_kses_data( __('Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"std" => 'default',
				"options" => gracioza_get_list_header_footer_types(),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
				),
			'header_style' => array(
				"title" => esc_html__('Select custom layout', 'gracioza'),
				"desc" => wp_kses( __("Select custom header from Layouts Builder", 'gracioza'), 'gracioza_kses_content' ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"dependency" => array(
					'header_type' => array('custom')
				),
				"std" => GRACIOZA_THEME_FREE ? 'header-custom-sow-header-default' : 'header-custom-header-default',
				"options" => array(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'gracioza'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"std" => 'default',
				"options" => array(),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'gracioza'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"std" => 0,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_zoom' => array(
				"title" => esc_html__('Header zoom', 'gracioza'),
				"desc" => wp_kses_data( __("Zoom the header title. 1 - original size", 'gracioza') ),
				"std" => 1,
				"min" => 0.3,
				"max" => 2,
				"step" => 0.1,
				"refresh" => false,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "slider"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwide', 'gracioza'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"dependency" => array(
					'header_type' => array('default')
				),
				"std" => 1,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_widgets_info' => array(
				"title" => esc_html__('Header widgets', 'gracioza'),
				"desc" => wp_kses_data( __('Here you can place a widget slider, advertising banners, etc.', 'gracioza') ),
				"type" => "info"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'gracioza'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'gracioza') ),
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'gracioza'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"dependency" => array(
					'header_type' => array('default'),
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => gracioza_get_list_range(0,6),
				"type" => "select"
				),

			'menu_info' => array(
				"title" => esc_html__('Main menu', 'gracioza'),
				"desc" => wp_kses_data( __('Select main menu style, position, color scheme and other parameters', 'gracioza') ),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'gracioza'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'gracioza'),
					'left'	=> esc_html__('Left',	'gracioza'),
					'right'	=> esc_html__('Right',	'gracioza')
				),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
				),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'gracioza'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 0,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'gracioza'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'gracioza')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'gracioza'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'gracioza') ),
				"std" => 1,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_image_info' => array(
				"title" => esc_html__('Header image', 'gracioza'),
				"desc" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "info"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'gracioza'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'gracioza') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gracioza')
				),
				"std" => 0,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_mobile_info' => array(
				"title" => esc_html__('Mobile header', 'gracioza'),
				"desc" => wp_kses_data( __("Configure the mobile version of the header", 'gracioza') ),
				"priority" => 500,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "info"
				),
			'header_mobile_enabled' => array(
				"title" => esc_html__('Enable the mobile header', 'gracioza'),
				"desc" => wp_kses_data( __("Use the mobile version of the header (if checked) or relayout the current header on mobile devices", 'gracioza') ),
				"std" => 0,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_additional_info' => array(
				"title" => esc_html__('Additional info', 'gracioza'),
				"desc" => wp_kses_data( __('Additional info to show at the top of the mobile header', 'gracioza') ),
				"std" => '',
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"refresh" => false,
				"teeny" => false,
				"rows" => 20,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "text_editor"
				),
			'header_mobile_hide_info' => array(
				"title" => esc_html__('Hide additional info', 'gracioza'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_logo' => array(
				"title" => esc_html__('Hide logo', 'gracioza'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_login' => array(
				"title" => esc_html__('Hide login/logout', 'gracioza'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_search' => array(
				"title" => esc_html__('Hide search', 'gracioza'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_cart' => array(
				"title" => esc_html__('Hide cart', 'gracioza'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
				),


		
			// 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'gracioza'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 50,
				"type" => "section"
				),
			'footer_type' => array(
				"title" => esc_html__('Footer style', 'gracioza'),
				"desc" => wp_kses_data( __('Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gracioza')
				),
				"std" => 'default',
				"options" => gracioza_get_list_header_footer_types(),
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
				),
			'footer_style' => array(
				"title" => esc_html__('Select custom layout', 'gracioza'),
				"desc" => wp_kses( __("Select custom footer from Layouts Builder", 'gracioza'), 'gracioza_kses_content' ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gracioza')
				),
				"dependency" => array(
					'footer_type' => array('custom')
				),
				"std" => GRACIOZA_THEME_FREE ? 'footer-custom-sow-footer-default' : 'footer-custom-footer-default',
				"options" => array(),
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'gracioza'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gracioza')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 'footer_widgets',
				"options" => array(),
				"type" => "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'gracioza'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gracioza')
				),
				"dependency" => array(
					'footer_type' => array('default'),
					'footer_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => gracioza_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwide', 'gracioza'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'gracioza') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gracioza')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'gracioza'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'gracioza') ),
				'refresh' => false,
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'gracioza') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1)
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'gracioza') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1),
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "image"
				),
			'socials_in_footer' => array(
				"title" => esc_html__('Show social icons', 'gracioza'),
				"desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'gracioza') ),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'gracioza'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'gracioza') ),
				"std" => esc_html__('Copyright &copy; {Y} by AncoraThemes. All rights reserved.', 'gracioza'),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"refresh" => false,
				"type" => "textarea"
				),
			
		
		
			// 'Blog'
			'blog' => array(
				"title" => esc_html__('Blog', 'gracioza'),
				"desc" => wp_kses_data( __('Options of the the blog archive', 'gracioza') ),
				"priority" => 70,
				"type" => "panel",
				),
		
				// Blog - Posts page
				'blog_general' => array(
					"title" => esc_html__('Posts page', 'gracioza'),
					"desc" => wp_kses_data( __('Style and components of the blog archive', 'gracioza') ),
					"type" => "section",
					),
				'blog_general_info' => array(
					"title" => esc_html__('General settings', 'gracioza'),
					"desc" => '',
					"type" => "info",
					),
				'blog_style' => array(
					"title" => esc_html__('Blog style', 'gracioza'),
					"desc" => '',
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => 'excerpt',
					"options" => array(),
					"type" => "select"
					),
				'first_post_large' => array(
					"title" => esc_html__('First post large', 'gracioza'),
					"desc" => wp_kses_data( __('Make your first post stand out by making it bigger', 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('classic', 'masonry', 'modern')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				"blog_content" => array( 
					"title" => esc_html__('Posts content', 'gracioza'),
					"desc" => wp_kses_data( __("Display either post excerpts or the full post content", 'gracioza') ),
					"std" => "excerpt",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"options" => array(
						'excerpt'	=> esc_html__('Excerpt',	'gracioza'),
						'fullpost'	=> esc_html__('Full post',	'gracioza')
					),
					"type" => "switch"
					),
				'excerpt_length' => array(
					"title" => esc_html__('Excerpt length', 'gracioza'),
					"desc" => wp_kses_data( __("Length (in words) to generate excerpt from the post content. Attention! If the post excerpt is explicitly specified - it appears unchanged", 'gracioza') ),
					"dependency" => array(
						'blog_style' => array('excerpt'),
						'blog_content' => array('excerpt')
					),
					"std" => 30,
					"type" => "text"
					),
				'blog_columns' => array(
					"title" => esc_html__('Blog columns', 'gracioza'),
					"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'gracioza') ),
					"std" => 2,
					"options" => gracioza_get_list_range(2,4),
					"type" => "hidden"
					),
				'post_type' => array(
					"title" => esc_html__('Post type', 'gracioza'),
					"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"linked" => 'parent_cat',
					"refresh" => false,
					"hidden" => true,
					"std" => 'post',
					"options" => array(),
					"type" => "select"
					),
				'parent_cat' => array(
					"title" => esc_html__('Category to show', 'gracioza'),
					"desc" => wp_kses_data( __('Select category to show in the blog archive', 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"refresh" => false,
					"hidden" => true,
					"std" => '0',
					"options" => array(),
					"type" => "select"
					),
				'posts_per_page' => array(
					"title" => esc_html__('Posts per page', 'gracioza'),
					"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"hidden" => true,
					"std" => '',
					"type" => "text"
					),
				"blog_pagination" => array( 
					"title" => esc_html__('Pagination style', 'gracioza'),
					"desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"std" => "pages",
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"options" => array(
						'pages'	=> esc_html__("Page numbers", 'gracioza'),
						'links'	=> esc_html__("Older/Newest", 'gracioza'),
						'more'	=> esc_html__("Load more", 'gracioza'),
						'infinite' => esc_html__("Infinite scroll", 'gracioza')
					),
					"type" => "select"
					),
				'show_filters' => array(
					"title" => esc_html__('Show filters', 'gracioza'),
					"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('portfolio', 'gallery')
					),
					"hidden" => true,
					"std" => 0,
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
					),
                    'show_one_sticky_blog' => array(
                        "title" => esc_html__('Show one sticky', 'gracioza'),
                        "desc" => wp_kses_data( __('Show one sticky post in blog', 'gracioza') ),
                        "refresh" => false,
                        "std" => 0,
                        "type" => "checkbox"
                    ),
	
				'blog_sidebar_info' => array(
					"title" => esc_html__('Sidebar', 'gracioza'),
					"desc" => '',
					"type" => "info",
					),
				'sidebar_position_blog' => array(
					"title" => esc_html__('Sidebar position', 'gracioza'),
					"desc" => wp_kses_data( __('Select position to show sidebar', 'gracioza') ),
					"std" => 'right',
					"options" => array(),
					"type" => "switch"
					),
				'sidebar_widgets_blog' => array(
					"title" => esc_html__('Sidebar widgets', 'gracioza'),
					"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'gracioza') ),
					"dependency" => array(
						'sidebar_position_blog' => array('left', 'right')
					),
					"std" => 'sidebar_widgets',
					"options" => array(),
					"type" => "select"
					),
				'expand_content_blog' => array(
					"title" => esc_html__('Expand content', 'gracioza'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'gracioza') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
	
	
				'blog_widgets_info' => array(
					"title" => esc_html__('Additional widgets', 'gracioza'),
					"desc" => '',
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "info",
					),
				'widgets_above_page_blog' => array(
					"title" => esc_html__('Widgets at the top of the page', 'gracioza'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'gracioza') ),
					"std" => 'hide',
					"options" => array(),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
					),
				'widgets_above_content_blog' => array(
					"title" => esc_html__('Widgets above the content', 'gracioza'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'gracioza') ),
					"std" => 'hide',
					"options" => array(),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_content_blog' => array(
					"title" => esc_html__('Widgets below the content', 'gracioza'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'gracioza') ),
					"std" => 'hide',
					"options" => array(),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_page_blog' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'gracioza'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'gracioza') ),
					"std" => 'hide',
					"options" => array(),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
					),

				'blog_advanced_info' => array(
					"title" => esc_html__('Advanced settings', 'gracioza'),
					"desc" => '',
					"type" => "info",
					),
				'no_image' => array(
					"title" => esc_html__('Image placeholder', 'gracioza'),
					"desc" => wp_kses_data( __('Select or upload an image used as placeholder for posts without a featured image', 'gracioza') ),
					"std" => '',
					"type" => "image"
					),
				'time_diff_before' => array(
					"title" => esc_html__('Easy Readable Date Format', 'gracioza'),
					"desc" => wp_kses_data( __("For how many days to show the easy-readable date format (e.g. '3 days ago') instead of the standard publication date", 'gracioza') ),
					"std" => 5,
					"type" => "text"
					),
				'sticky_style' => array(
					"title" => esc_html__('Sticky posts style', 'gracioza'),
					"desc" => wp_kses_data( __('Select style of the sticky posts output', 'gracioza') ),
					"std" => 'inherit',
					"options" => array(
						'inherit' => esc_html__('Decorated posts', 'gracioza'),
						'columns' => esc_html__('Mini-cards',	'gracioza')
					),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
					),
				"blog_animation" => array( 
					"title" => esc_html__('Animation for the posts', 'gracioza'),
					"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => "none",
					"options" => array(),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
					),
				'meta_parts' => array(
					"title" => esc_html__('Post meta', 'gracioza'),
					"desc" => wp_kses_data( __("If your blog page is created using the 'Blog archive' page template, set up the 'Post Meta' settings in the 'Theme Options' section of that page.", 'gracioza') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=0|date=1|author=1|counters=1|share=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'gracioza'),
						'date'		 => esc_html__('Post date', 'gracioza'),
						'author'	 => esc_html__('Post author', 'gracioza'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'gracioza'),
						'share'		 => esc_html__('Share links', 'gracioza'),
						'edit'		 => esc_html__('Edit link', 'gracioza')
					),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "checklist"
				),
				'counters' => array(
					"title" => esc_html__('Views, Likes and Comments', 'gracioza'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'gracioza') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'gracioza')
					),
					"dependency" => array(
						'#page_template' => array('blog.php'),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=0|likes=0|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'gracioza'),
						'likes' => esc_html__('Likes', 'gracioza'),
						'comments' => esc_html__('Comments', 'gracioza')
					),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "checklist"
				),

				
				// Blog - Single posts
				'blog_single' => array(
					"title" => esc_html__('Single posts', 'gracioza'),
					"desc" => wp_kses_data( __('Settings of the single post', 'gracioza') ),
					"type" => "section",
					),
				'hide_featured_on_single' => array(
					"title" => esc_html__('Hide featured image on the single post', 'gracioza'),
					"desc" => wp_kses_data( __("Hide featured image on the single post's pages", 'gracioza') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'gracioza')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				'hide_sidebar_on_single' => array(
					"title" => esc_html__('Hide sidebar on the single post', 'gracioza'),
					"desc" => wp_kses_data( __("Hide sidebar on the single post's pages", 'gracioza') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'show_post_meta' => array(
					"title" => esc_html__('Show post meta', 'gracioza'),
					"desc" => wp_kses_data( __("Display block with post's meta: date, categories, counters, etc.", 'gracioza') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'meta_parts_post' => array(
					"title" => esc_html__('Post meta', 'gracioza'),
					"desc" => wp_kses_data( __("Meta parts for single posts.", 'gracioza') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=0|date=1|author=1|counters=1|share=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'gracioza'),
						'date'		 => esc_html__('Post date', 'gracioza'),
						'author'	 => esc_html__('Post author', 'gracioza'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'gracioza'),
						'share'		 => esc_html__('Share links', 'gracioza'),
						'edit'		 => esc_html__('Edit link', 'gracioza')
					),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "checklist"
				),
				'counters_post' => array(
					"title" => esc_html__('Views, Likes and Comments', 'gracioza'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'gracioza') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=0|likes=0|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'gracioza'),
						'likes' => esc_html__('Likes', 'gracioza'),
						'comments' => esc_html__('Comments', 'gracioza')
					),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "checklist"
				),
				'show_share_links' => array(
					"title" => esc_html__('Show share links', 'gracioza'),
					"desc" => wp_kses_data( __("Display share links on the single post", 'gracioza') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'show_author_info' => array(
					"title" => esc_html__('Show author info', 'gracioza'),
					"desc" => wp_kses_data( __("Display block with information about post's author", 'gracioza') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'blog_single_related_info' => array(
					"title" => esc_html__('Related posts', 'gracioza'),
					"desc" => '',
					"type" => "info",
					),
				'show_related_posts' => array(
					"title" => esc_html__('Show related posts', 'gracioza'),
					"desc" => wp_kses_data( __("Show section 'Related posts' on the single post's pages", 'gracioza') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'gracioza')
					),
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts' => array(
					"title" => esc_html__('Related posts', 'gracioza'),
					"desc" => wp_kses_data( __('How many related posts should be displayed in the single post? If 0 - no related posts showed.', 'gracioza') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => gracioza_get_list_range(1,9),
					"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
					),
				'related_columns' => array(
					"title" => esc_html__('Related columns', 'gracioza'),
					"desc" => wp_kses_data( __('How many columns should be used to output related posts in the single page (from 2 to 4)?', 'gracioza') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 1,
					"options" => gracioza_get_list_range(1,4),
					"type" => "hidden"
					),
				'related_style' => array(
					"title" => esc_html__('Related posts style', 'gracioza'),
					"desc" => wp_kses_data( __('Select style of the related posts output', 'gracioza') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => gracioza_get_list_styles(1,2),
                    "type" => "hidden"
					),
			'blog_end' => array(
				"type" => "panel_end",
				),
			
		
		
			// 'Colors'
			'panel_colors' => array(
				"title" => esc_html__('Colors', 'gracioza'),
				"desc" => '',
				"priority" => 300,
				"type" => "section"
				),

			'color_schemes_info' => array(
				"title" => esc_html__('Color schemes', 'gracioza'),
				"desc" => wp_kses_data( __('Color schemes for various parts of the site. "Inherit" means that this block is used the Site color scheme (the first parameter)', 'gracioza') ),
				"type" => "info",
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'gracioza'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'gracioza')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'gracioza'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'gracioza')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Sidemenu Color Scheme', 'gracioza'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'gracioza')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'gracioza'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'gracioza')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'gracioza'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'gracioza')
				),
				"std" => 'dark',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),

			'color_scheme_editor_info' => array(
				"title" => esc_html__('Color scheme editor', 'gracioza'),
				"desc" => wp_kses_data(__('Select color scheme to modify. Attention! Only those sections in the site will be changed which this scheme was assigned to', 'gracioza') ),
				"type" => "info",
				),
			'scheme_storage' => array(
				"title" => esc_html__('Color scheme editor', 'gracioza'),
				"desc" => '',
				"std" => '$gracioza_get_scheme_storage',
				"refresh" => false,
				"colorpicker" => "tiny",
				"type" => "scheme_editor"
				),


			// 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'gracioza'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'gracioza') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'gracioza')
				),
				"hidden" => true,
				"std" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'gracioza'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'gracioza') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'gracioza')
				),
				"hidden" => true,
				"std" => '',
				"type" => GRACIOZA_THEME_FREE ? "hidden" : "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			// Use huge priority to call render this elements after all options!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"priority" => 10000,
				"type" => "hidden",
				),

			'last_option' => array(		// Need to manually call action to include Tiny MCE scripts
				"title" => '',
				"desc" => '',
				"std" => 1,
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		$fonts = array(
		
			// 'Fonts'
			'fonts' => array(
				"title" => esc_html__('Typography', 'gracioza'),
				"desc" => '',
				"priority" => 200,
				"type" => "panel"
				),

			// Fonts - Load_fonts
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'gracioza'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'gracioza') )
						. '<br>'
						. wp_kses_data( __('<b>Attention!</b> Press "Refresh" button to reload preview area after the all fonts are changed', 'gracioza') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'gracioza'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'gracioza') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'gracioza') ),
				"class" => "gracioza_column-1_3 gracioza_new_row",
				"refresh" => false,
				"std" => '$gracioza_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=gracioza_get_theme_setting('max_load_fonts'); $i++) {
			if (gracioza_get_value_gp('page') != 'theme_options') {
				$fonts["load_fonts-{$i}-info"] = array(
					// Translators: Add font's number - 'Font 1', 'Font 2', etc
					"title" => esc_html(sprintf(__('Font %s', 'gracioza'), $i)),
					"desc" => '',
					"type" => "info",
					);
			}
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'gracioza'),
				"desc" => '',
				"class" => "gracioza_column-1_3 gracioza_new_row",
				"refresh" => false,
				"std" => '$gracioza_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'gracioza'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'gracioza') )
							: '',
				"class" => "gracioza_column-1_3",
				"refresh" => false,
				"std" => '$gracioza_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'gracioza'),
					'serif' => esc_html__('serif', 'gracioza'),
					'sans-serif' => esc_html__('sans-serif', 'gracioza'),
					'monospace' => esc_html__('monospace', 'gracioza'),
					'cursive' => esc_html__('cursive', 'gracioza'),
					'fantasy' => esc_html__('fantasy', 'gracioza')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'gracioza'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'gracioza') )
								. '<br>'
								. wp_kses_data( __('<b>Attention!</b> Each weight and style increase download size! Specify only used weights and styles.', 'gracioza') )
							: '',
				"class" => "gracioza_column-1_3",
				"refresh" => false,
				"std" => '$gracioza_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Fonts - H1..6, P, Info, Menu, etc.
		$theme_fonts = gracioza_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								// Translators: Add tag's name to make title 'H1 settings', 'P settings', etc.
								: esc_html(sprintf(__('%s settings', 'gracioza'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								// Translators: Add tag's name to make description
								: wp_kses_data( sprintf(__('Font settings of the "%s" tag.', 'gracioza'), $tag) ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = array();
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gracioza'),
						'100' => esc_html__('100 (Light)', 'gracioza'), 
						'200' => esc_html__('200 (Light)', 'gracioza'), 
						'300' => esc_html__('300 (Thin)',  'gracioza'),
						'400' => esc_html__('400 (Normal)', 'gracioza'),
						'500' => esc_html__('500 (Semibold)', 'gracioza'),
						'600' => esc_html__('600 (Semibold)', 'gracioza'),
						'700' => esc_html__('700 (Bold)', 'gracioza'),
						'800' => esc_html__('800 (Black)', 'gracioza'),
						'900' => esc_html__('900 (Black)', 'gracioza')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gracioza'),
						'normal' => esc_html__('Normal', 'gracioza'), 
						'italic' => esc_html__('Italic', 'gracioza')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gracioza'),
						'none' => esc_html__('None', 'gracioza'), 
						'underline' => esc_html__('Underline', 'gracioza'),
						'overline' => esc_html__('Overline', 'gracioza'),
						'line-through' => esc_html__('Line-through', 'gracioza')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gracioza'),
						'none' => esc_html__('None', 'gracioza'), 
						'uppercase' => esc_html__('Uppercase', 'gracioza'),
						'lowercase' => esc_html__('Lowercase', 'gracioza'),
						'capitalize' => esc_html__('Capitalize', 'gracioza')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"class" => "gracioza_column-1_5",
					"refresh" => false,
					"std" => '$gracioza_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters to Theme Options
		gracioza_storage_set_array_before('options', 'panel_colors', $fonts);

		// Add Header Video if WP version < 4.7
		if (!function_exists('get_header_video_url')) {
			gracioza_storage_set_array_after('options', 'header_image_override', 'header_video', array(
				"title" => esc_html__('Header video', 'gracioza'),
				"desc" => wp_kses_data( __("Select video to use it as background for the header", 'gracioza') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gracioza')
				),
				"std" => '',
				"type" => "video"
				)
			);
		}

		// Add option 'logo' if WP version < 4.5
		// or 'custom_logo' if current page is 'Theme Options'
		if (!function_exists('the_custom_logo') || (isset($_REQUEST['page']) && $_REQUEST['page']=='theme_options')) {
			gracioza_storage_set_array_before('options', 'logo_retina', function_exists('the_custom_logo') ? 'custom_logo' : 'logo', array(
				"title" => esc_html__('Logo', 'gracioza'),
				"desc" => wp_kses_data( __('Select or upload the site logo', 'gracioza') ),
				"class" => "gracioza_column-1_2 gracioza_new_row",
				"priority" => 60,
				"std" => '',
				"type" => "image"
				)
			);
		}
	}
}


// Returns a list of options that can be overridden for CPT
if (!function_exists('gracioza_options_get_list_cpt_options')) {
	function gracioza_options_get_list_cpt_options($cpt, $title='') {
		if (empty($title)) $title = ucfirst($cpt);
		return array(
					"header_info_{$cpt}" => array(
						"title" => esc_html__('Header', 'gracioza'),
						"desc" => '',
						"type" => "info",
						),
					"header_type_{$cpt}" => array(
						"title" => esc_html__('Header style', 'gracioza'),
						"desc" => wp_kses_data( __('Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'gracioza') ),
						"std" => 'inherit',
						"options" => gracioza_get_list_header_footer_types(true),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
						),
					"header_style_{$cpt}" => array(
						"title" => esc_html__('Select custom layout', 'gracioza'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select custom layout to display the site header on the %s pages', 'gracioza'), $title) ),
						"dependency" => array(
							"header_type_{$cpt}" => array('custom')
						),
						"std" => 'inherit',
						"options" => array(),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
						),
					"header_position_{$cpt}" => array(
						"title" => esc_html__('Header position', 'gracioza'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select position to display the site header on the %s pages', 'gracioza'), $title) ),
						"std" => 'inherit',
						"options" => array(),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
						),
					"header_image_override_{$cpt}" => array(
						"title" => esc_html__('Header image override', 'gracioza'),
						"desc" => wp_kses_data( __("Allow override the header image with the post's featured image", 'gracioza') ),
						"std" => 0,
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "checkbox"
						),
					"header_widgets_{$cpt}" => array(
						"title" => esc_html__('Header widgets', 'gracioza'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select set of widgets to show in the header on the %s pages', 'gracioza'), $title) ),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
						
					"sidebar_info_{$cpt}" => array(
						"title" => esc_html__('Sidebar', 'gracioza'),
						"desc" => '',
						"type" => "info",
						),
					"sidebar_position_{$cpt}" => array(
						"title" => esc_html__('Sidebar position', 'gracioza'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select position to show sidebar on the %s pages', 'gracioza'), $title) ),
						"refresh" => false,
						"std" => 'left',
						"options" => array(),
						"type" => "switch"
						),
					"sidebar_widgets_{$cpt}" => array(
						"title" => esc_html__('Sidebar widgets', 'gracioza'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select sidebar to show on the %s pages', 'gracioza'), $title) ),
						"dependency" => array(
							"sidebar_position_{$cpt}" => array('left', 'right')
						),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
					"hide_sidebar_on_single_{$cpt}" => array(
						"title" => esc_html__('Hide sidebar on the single pages', 'gracioza'),
						"desc" => wp_kses_data( __("Hide sidebar on the single page", 'gracioza') ),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"footer_info_{$cpt}" => array(
						"title" => esc_html__('Footer', 'gracioza'),
						"desc" => '',
						"type" => "info",
						),
					"footer_type_{$cpt}" => array(
						"title" => esc_html__('Footer style', 'gracioza'),
						"desc" => wp_kses_data( __('Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'gracioza') ),
						"std" => 'inherit',
						"options" => gracioza_get_list_header_footer_types(true),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "switch"
						),
					"footer_style_{$cpt}" => array(
						"title" => esc_html__('Select custom layout', 'gracioza'),
						"desc" => wp_kses_data( __('Select custom layout to display the site footer', 'gracioza') ),
						"std" => 'inherit',
						"dependency" => array(
							"footer_type_{$cpt}" => array('custom')
						),
						"options" => array(),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
						),
					"footer_widgets_{$cpt}" => array(
						"title" => esc_html__('Footer widgets', 'gracioza'),
						"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'gracioza') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default')
						),
						"std" => 'footer_widgets',
						"options" => array(),
						"type" => "select"
						),
					"footer_columns_{$cpt}" => array(
						"title" => esc_html__('Footer columns', 'gracioza'),
						"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'gracioza') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default'),
							"footer_widgets_{$cpt}" => array('^hide')
						),
						"std" => 0,
						"options" => gracioza_get_list_range(0,6),
						"type" => "select"
						),
					"footer_wide_{$cpt}" => array(
						"title" => esc_html__('Footer fullwide', 'gracioza'),
						"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'gracioza') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default')
						),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"widgets_info_{$cpt}" => array(
						"title" => esc_html__('Additional panels', 'gracioza'),
						"desc" => '',
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "info",
						),
					"widgets_above_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the top of the page', 'gracioza'),
						"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'gracioza') ),
						"std" => 'hide',
						"options" => array(),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
						),
					"widgets_above_content_{$cpt}" => array(
						"title" => esc_html__('Widgets above the content', 'gracioza'),
						"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'gracioza') ),
						"std" => 'hide',
						"options" => array(),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_content_{$cpt}" => array(
						"title" => esc_html__('Widgets below the content', 'gracioza'),
						"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'gracioza') ),
						"std" => 'hide',
						"options" => array(),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the bottom of the page', 'gracioza'),
						"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'gracioza') ),
						"std" => 'hide',
						"options" => array(),
						"type" => GRACIOZA_THEME_FREE ? "hidden" : "select"
						)
					);
	}
}


// Return lists with choises when its need in the admin mode
if (!function_exists('gracioza_options_get_list_choises')) {
	add_filter('gracioza_filter_options_get_list_choises', 'gracioza_options_get_list_choises', 10, 2);
	function gracioza_options_get_list_choises($list, $id) {
		if (is_array($list) && count($list)==0) {
			if (strpos($id, 'header_style')===0)
				$list = gracioza_get_list_header_styles(strpos($id, 'header_style_')===0);
			else if (strpos($id, 'header_position')===0)
				$list = gracioza_get_list_header_positions(strpos($id, 'header_position_')===0);
			else if (strpos($id, 'header_widgets')===0)
				$list = gracioza_get_list_sidebars(strpos($id, 'header_widgets_')===0, true);
			else if (substr($id, -7) == '_scheme')
				$list = gracioza_get_list_schemes($id!='color_scheme');
			else if (strpos($id, 'sidebar_widgets')===0)
				$list = gracioza_get_list_sidebars(strpos($id, 'sidebar_widgets_')===0, true);
			else if (strpos($id, 'sidebar_position')===0)
				$list = gracioza_get_list_sidebars_positions(strpos($id, 'sidebar_position_')===0);
			else if (strpos($id, 'widgets_above_page')===0)
				$list = gracioza_get_list_sidebars(strpos($id, 'widgets_above_page_')===0, true);
			else if (strpos($id, 'widgets_above_content')===0)
				$list = gracioza_get_list_sidebars(strpos($id, 'widgets_above_content_')===0, true);
			else if (strpos($id, 'widgets_below_page')===0)
				$list = gracioza_get_list_sidebars(strpos($id, 'widgets_below_page_')===0, true);
			else if (strpos($id, 'widgets_below_content')===0)
				$list = gracioza_get_list_sidebars(strpos($id, 'widgets_below_content_')===0, true);
			else if (strpos($id, 'footer_style')===0)
				$list = gracioza_get_list_footer_styles(strpos($id, 'footer_style_')===0);
			else if (strpos($id, 'footer_widgets')===0)
				$list = gracioza_get_list_sidebars(strpos($id, 'footer_widgets_')===0, true);
			else if (strpos($id, 'blog_style')===0)
				$list = gracioza_get_list_blog_styles(strpos($id, 'blog_style_')===0);
			else if (strpos($id, 'post_type')===0)
				$list = gracioza_get_list_posts_types();
			else if (strpos($id, 'parent_cat')===0)
				$list = gracioza_array_merge(array(0 => esc_html__('- Select category -', 'gracioza')), gracioza_get_list_categories());
			else if (strpos($id, 'blog_animation')===0)
				$list = gracioza_get_list_animations_in();
			else if ($id == 'color_scheme_editor')
				$list = gracioza_get_list_schemes();
			else if (strpos($id, '_font-family') > 0)
				$list = gracioza_get_list_load_fonts(true);
		}
		return $list;
	}
}
?>