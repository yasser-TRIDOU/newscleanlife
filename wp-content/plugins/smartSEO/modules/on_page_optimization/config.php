<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
 echo json_encode(
	array(
		'on_page_optimization' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 93,
				'show_in_menu' => false,
				'title' =>esc_html__('Settings', 'smartSEO'),
				'icon' => '<span class="' . ( $psp->alias ) . '-icon-mass_optimization"><span class="path1"></span><span class="path2"></span></span>',
			),
			'description' =>esc_html__('Settings', 'smartSEO'),
			'module_init' => 'init.php',
			'help' => array(
				'type' => 'remote',
				'url' => ''
			),
			'load_in' => array(
				'backend' => array(
					'admin.php?page=psp_massOptimization',
					'admin-ajax.php',
					'edit.php',
					'post.php',
					'post-new.php',
					'edit-tags.php',
					'term.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'ajaxupload',
				'jquery-ui-core',
				'jquery-ui-autocomplete'
			),
			'css' => array(
				'admin'
			)
		)
	)
 );
