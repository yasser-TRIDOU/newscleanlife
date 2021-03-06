<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
 global $psp;
echo json_encode(
	array(
		'server_status' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 4,
				'show_in_menu' => false,
				'title' =>esc_html__('Server Status', 'smartSEO'),
				'icon' => '<i class="' . ( $psp->alias ) . '-icon-server_status"></i>'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32_serverstatus.png',
				'url'	=> admin_url('admin.php?page=psp_server_status')
			),
			'description' =>esc_html__('Using the server status module you can check if your install is correct, if you have the right server configuration and you can test the product imports.', 'smartSEO'),
			'module_init' => 'init.php',
			'help' => array(
				'type' => 'remote',
				'url' => 'http://docs.aa-team.com/premium-seo-pack/documentation/server-status-2/'
			),
			'load_in' => array(
				'backend' => array(
					'admin.php?page=psp_server_status',
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
