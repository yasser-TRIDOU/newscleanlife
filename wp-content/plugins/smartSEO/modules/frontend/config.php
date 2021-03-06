<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
echo json_encode(array(
		'frontend' => array(
			'version' => '1.0',
			'menu' => array(
				'show_in_menu' => false,
				'order' => 1,
				'title' =>esc_html__('Frontend', 'smartSEO'),
				'icon' => '<span class="' . ( $psp->alias ) . '-icon-frontend"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></span>'
			),
			'description' =>esc_html__('Using this module you can display meta tags in frontend!', 'smartSEO'),
			'module_init' => 'init.php',
			'help' => array(
				'type' => 'remote',
				'url' => 'http://docs.aa-team.com/products/premium-seo-pack/'
			),
			'load_in' => array(
				'backend'	=> false,
				'frontend' 	=> true
			),
		)
));
