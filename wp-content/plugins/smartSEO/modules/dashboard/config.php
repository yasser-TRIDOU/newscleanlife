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
	'dashboard' => array(
		'version' => '1.0',
		'menu' => array(
			'order' => 1,
			'title' =>esc_html__('Dashboard', 'smartSEO'),
			'icon' => '<i class="' . ( $psp->alias ) . '-icon-dashboard"></i>'
		),
		'description' => 'Dashboard Area - Here you will find usefull shortcuts to different modules inside the plugin.',
		'module_init' => 'init.php',
		'help' => array(
			'type' => 'remote',
			'url' => 'http://docs.aa-team.com/smartseo'
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
			'tipsy',
			//'percentageloader-0.1',
			'flot-2.0',
			'flot-tooltip',
			'flot-stack',
			'flot-pie',
			'flot-time',
			'flot-resize'
		),
		'css' => array(
			'admin'
		)
	)
));
