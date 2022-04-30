<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * ======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
 echo json_encode(
	array(
		'modules_manager' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 30,
				'title' =>esc_html__('Modules Manager', 'smartSEO')
				,'icon' => '<i class="' . ( $psp->alias ) . '-icon-modules_manager"></i>'
			),
			
			'description' =>esc_html__('Using this module you can activate / deactivate plugin modules.', 'smartSEO'),
			'help' => array(
				'type' => 'remote',
				'url' => 'http://docs.aa-team.com/premium-seo-pack/documentation/modules-manager-2/'
			),
			'load_in' => array(
				'backend' => array(
					'admin-ajax.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy'
			),
			'css' => array(
				'admin'
			)
		)
	)
 );
