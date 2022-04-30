<?php
/**
 * module return as json_encode
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
			/* define the form_messages box */
			'on_page_optimization' => array(
				'title' 	=>esc_html__('Mass Optimization', 'smartSEO'),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					'parse_shortcodes' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=>esc_html__('Parse content shortcodes:', 'smartSEO'),
						'desc' 		=>esc_html__('If you choose "yes", the shortcodes in the page/post content are also parsed by the optimization algorithm, but the process will be more time consuming.', 'smartSEO'),
						'options'	=> array(
							'yes' => 'YES',
							'no' => 'NO'
						)
					),
					
					'charset' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=>esc_html__('Server Charset:', 'smartSEO'),
						'desc' 		=>esc_html__('Server Charset (used internal by the php-query class)', 'smartSEO')
					),
					
					'meta_title_sufix' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=>esc_html__('Meta title - text append to:', 'smartSEO'),
						'desc' 		=>esc_html__('Append this text to the end of the meta title value from the database', 'smartSEO')
					),
					
					'meta_keywords_stop_words' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> 'a, you, if',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=>esc_html__('Stop Words List:', 'smartSEO'),
						'desc' 		=>esc_html__('Used default at optimize to auto generate <span style="font-style: bold; color: red;">Meta Keywords</span>
							<br/>The list of stop words (comma separated) which are not taken into consideration when analyzing the content. Default list: <strong>a, you, if</strong>', 'smartSEO'),
						'height'	=> '200px'
					),
					'meta_keywords_stop_words_content' => array(
						'type' 		=> 'select',
						'std' 		=> 'yes',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=>esc_html__('Stop Words List - Content:', 'smartSEO'),
						'desc' 		=>esc_html__('Choose "yes" if you want to use the "Stop Words List" for <span style="font-style: bold; color: red;">SEO Content Analysis rules</span> too (to determine keyword density and if the page content or meta seo title has enough words).', 'smartSEO'),
						'options'	=> array(
							'yes' => 'YES',
							'no' => 'NO'
						)
					),

					'word_min_chars' 	=> array(
						'type' 		=> 'select',
						'std' 		=> '4',
						'size' 		=> 'large',
						'title' 	=>esc_html__('Word Min Chars:', 'smartSEO'),
						'force_width'=> '100',
						'desc' 		=>esc_html__('Used default at optimize to auto generate <span style="font-style: bold; color: red;">Meta Keywords</span>
							<br/>The minimum number of characters for a word to be considered valid.', 'smartSEO'),
						'options'	=> $psp->doRange( range(0, 10, 1) )
					),
					'word_min_chars_content' => array(
						'type' 		=> 'select',
						'std' 		=> 'yes',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=>esc_html__('Word Min Chars - Content:', 'smartSEO'),
						'desc' 		=>esc_html__('Choose "yes" if you want to use the "Word Min Chars" for <span style="font-style: bold; color: red;">SEO Content Analysis rules</span> too (to determine keyword density and if the page content or meta seo title has enough words).', 'smartSEO'),
						'options'	=> array(
							'yes' => 'YES',
							'no' => 'NO'
						)
					),

					'post_allowed_rules' 	=> array(
						'type' 		=> 'multiselect_left2right',
						'std' 		=> array_keys( $psp->get_content_analyzing_rules() ), //array(),
						'size' 		=> 'large',
						'rows_visible'	=> 10,
						'title' 	=>esc_html__('Post: Allowed Rules', 'smartSEO'),
						'desc' 		=>esc_html__('here you can choose which rules you want to use when analyzing content for <span style="font-style: bold; color: red;">posts, pages, custom post types</span>.<br/>to view a rule\'s full text, hover over it.', 'smartSEO'),
						'info'		=> array(
							'left' =>esc_html__('All Rules list', 'smartSEO'),
							'right' =>esc_html__('Your chosen rules from list', 'smartSEO'),
						),
						'options' 	=> $psp->get_content_analyzing_rules(),
					),

					'category_allowed_rules' 	=> array(
						'type' 		=> 'multiselect_left2right',
						'std' 		=> array_keys( $psp->get_content_analyzing_rules() ), //array(),
						'size' 		=> 'large',
						'rows_visible'	=> 10,
						'title' 	=>esc_html__('Category: Allowed Rules', 'smartSEO'),
						'desc' 		=>esc_html__('here you can choose which rules you want to use when analyzing content for <span style="font-style: bold; color: red;">categories, tags, custom taxonomies</span>.<br/>to view a rule\'s full text, hover over it.', 'smartSEO'),
						'info'		=> array(
							'left' =>esc_html__('All Rules list', 'smartSEO'),
							'right' =>esc_html__('Your chosen rules from list', 'smartSEO'),
						),
						'options' 	=> $psp->get_content_analyzing_rules(),
					)
				)
			)
		)
	)
);
