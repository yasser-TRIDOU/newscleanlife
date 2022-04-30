<?php
/**
 * Dummy module return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
global $psp;
echo json_encode(
	array(
		$tryed_module['db_alias'] => array(
			 'import_seo_other_plugins' => array(
				'title' 	=>esc_html__('Import settings from other SEO plugins for posts, pages, custom post types', 'smartSEO'),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> false, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> array(
					'install_btn' => array(
						'type' => 'submit',
						'value' =>esc_html__('Import SEO', 'smartSEO'),
						'color' => 'success',
						'action' => 'psp-ImportSEO',
					)
				), // true|false|array
				'style' 	=> 'panel', // panel|panel-widget
				
				// create the box elements array
				'elements'	=> array(
					'from' => array(
						'type' 		=> 'select',
						'std' 		=> 'yoast',
						'size' 		=> 'normal',
						'force_width' => '190',
						'title' 	=>esc_html__('Import from:', 'smartSEO'),
						'desc' 		=>esc_html__('Select the plugin from which you want to import SEO settings for posts, pages, custom post types.', 'smartSEO'),
						'options'	=> array(
							'Yoast WordPress SEO' 				=> 'Yoast WordPress SEO',
							'SEO Ultimate' 						=> 'SEO Ultimate',
							'All-in-One SEO Pack - old version' => 'All-in-One SEO Pack - old version',
							'All-in-One SEO Pack' 				=> 'All-in-One SEO Pack',
							'WooThemes SEO Framework' 			=> 'WooThemes SEO Framework'
						)
					)
				)
			)

		 
	 

		)
	)
);   
