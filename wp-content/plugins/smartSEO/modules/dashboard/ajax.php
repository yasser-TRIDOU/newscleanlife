<?php
/*
* Define class psp_DashboardAjax
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
if (class_exists('psp_DashboardAjax') != true) {
	class psp_DashboardAjax extends psp_Dashboard {
	
		public $the_plugin = null;
		private $module_folder = null;
		
		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct( $the_plugin = array() ) {
			$this->the_plugin = $the_plugin;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/dashboard/';
  
			// ajax  helper
			add_action('wp_ajax_pspDashboardRequest', array( &$this, 'ajax_request' ));
		}
		
		/*
		* ajax_request, method
		* --------------------
		*
		* this will create requests to 404 table
		*/
		public function ajax_request() {
			$return = array();
			
			$actions = isset($_REQUEST['sub_actions']) ? explode(',', sanitize_text_field($_REQUEST['sub_actions'])) : '';
  
			$website_url = home_url();
			
			if ( in_array( 'social_impact', $actions) ) {

				$socialServices = $this->the_plugin->social_get_allowed_providers();
				$socialData = $this->the_plugin->social_get_stats(array(
					'from'					=> 'dashboard',
					'cache_life_time'		=> 600, // in seconds
					'website_url'			=> $website_url,
				));
				  
				$html = array();
				$html[] = '<ul class="psp-lists-status">';

				// alexa
				$html[] = 	'<li style="color: #00102c">';
				$html[] = 		'<img src="' . ( $this->module_folder ) . 'assets/stats/alexa-icon.png" class="psp-lists-icon">';
				$html[] = 		'<label>' . (esc_html__('in the World', 'smartSEO') ) . '</label>';
				$html[] = 		'<span>' . ( isset($socialData['alexa']) ?  $socialData['alexa'] . '<sup>th</sup>' : '&ndash;' ) . '</span>';
				$html[] = 	'</li>';

				// social providers
				if ( ! empty($socialServices) ) {
					foreach ($socialServices as $ssKey => $ssVal) {

						$socialHtmlBox = $this->the_plugin->social_get_htmlbox(array(
							'from'			=> 'dashboard',
							'img_src'		=> $this->module_folder . 'assets/stats/',
							'ssKey'			=> $ssKey,
							'ssVal'			=> $ssVal,
							'socialData'	=> $socialData,
						));
						$html[] = $socialHtmlBox;
					} // end foreach
				}

				$html[] = '</ul>';
				
				$html[] = '<span class="psp-cache-info">Generated on <strong>' . ( date('F j, Y, g:i a', $socialData['_cache_date']) ) . '</strong></span>';
				 
				$return['social_impact'] = array(
					'status' => 'valid',
					'html' => implode("\n", $html)
				);
			}
			
			if ( in_array( 'charset', $actions) ) {
				$html = array();
				$html[] = '<ul class="psp-lists-status">';
				
				$charset = get_bloginfo('charset');
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Charset', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($charset) ? $charset : '&ndash;' ) . '</span>';
				$html[] = 	'</li>'; 
				
				$html[] = '</ul>'; 
				 
				$return['charset'] = array(
					'status' => 'valid',
					'html' => implode("\n", $html)
				);
			}
			
			if ( in_array( 'technologies', $actions) ) {
				
				$html = array();
				$html[] = '<ul class="psp-lists-status">';
				
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Server Software', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '&ndash;' ) . '</span>';
				$html[] = 	'</li>'; 
				
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Server Admin', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($_SERVER['SERVER_ADMIN']) ? $_SERVER['SERVER_ADMIN'] : '&ndash;' ) . '</span>';
				$html[] = 	'</li>'; 
				
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Server Signature', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : '&ndash;' ) . '</span>';
				$html[] = 	'</li>';
				
				$html[] = '</ul>'; 
				 
				$return['technologies'] = array(
					'status' => 'valid',
					'html' => implode("\n", $html)
				);
			}
			
			if ( in_array( 'server_ip', $actions) ) {
				$server_ip_info = $this->the_plugin->social_get_remote(
					'http://api.hostip.info/get_json.php?ip=' . $_SERVER['SERVER_ADDR']
				);
				
				$html = array();
				$html[] = '<ul class="psp-lists-status">';
				
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Server IP', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($server_ip_info['ip']) ? $server_ip_info['ip'] : '&ndash;' ) . '</span>';
				$html[] = 	'</li>';
				
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Country Name', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($server_ip_info['country_name']) ? $server_ip_info['country_name'] : '&ndash;' ) . '</span>';
				$html[] = 	'</li>';
				
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Country Code', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($server_ip_info['country_code']) ? $server_ip_info['country_code'] : '&ndash;' ) . '</span>';
				$html[] = 	'</li>';
				
				$html[] = 	'<li>';
				$html[] = 		'<label>' . (esc_html__('Country City', 'smartSEO') ) . ':</label>';
				$html[] = 		'<span>' . ( isset($server_ip_info['city']) ? $server_ip_info['city'] : '&ndash;' ) . '</span>';
				$html[] = 	'</li>';
				
				$html[] = '</ul>'; 
				 
				$return['server_ip'] = array(
					'status' => 'valid',
					'html' => implode("\n", $html)
				);
			}

			if ( in_array( 'aateam_products', $actions) ) {
				
				$sites = array('codecanyon', 'themeforest', 'graphicriver');
				$html = array();
				foreach ( $sites as $site ) {
					$api_url = 'http://marketplace.envato.com/api/edge/new-files-from-user:AA-Team,%s.json';
					
					$response_data = $this->the_plugin->social_get_remote( sprintf( $api_url, $site) );
					
					// reorder the array
					if ( isset($response_data['new-files-from-user']) && count($response_data['new-files-from-user']) > 0 ) {
						$data = array();
						$__arr = $response_data['new-files-from-user'];
						$__newarr = array();
						$__newarrSales = array();
						foreach ($__arr as $k => $v) {
							$key = $v['id'];
							$__newarr["$key"] = $v;
							$__newarrSales["$key"] = $v['sales'];
						}
						asort($__newarrSales, SORT_NUMERIC);
						foreach ($__newarrSales as $k => $v) {
							$__newarrSales["$k"] = $__newarr["$k"];
						}
						$reversed_data = array_reverse($__newarrSales, true);
						
						if ( count($reversed_data) > 0 ) {
							$html[] = '<div class="psp-aa-products-container" id="aa-prod-' . ( $site ) . '">';
							$html[] = 	'<ul style="width: ' . ( count($reversed_data) * 135 ) . 'px">';
							foreach ( $reversed_data as $item ) {
								$html[] = 	'<li>';
								$html[] = 		'<a target="_blank" href="' . ( $item['url'] ) . '?rel=AA-Team" data-preview="' . ( $item['live_preview_url'] ) . '">';
								$html[] = 			'<img src="' . ( $item['thumbnail'] ) . '" width="80" alt="' . ( $item['item'] ) . '">';
								$html[] = 			'<span class="the-rate-' . ( ceil( $item['rating'] ) ) . '"></span>';
								$html[] = 			'<strong>$' . ( $item['cost'] ) . '</strong>';
								$html[] = 		'</a>';
								$html[] = 	'</li>';
							}
							$html[] = 	'</ul>';			
							$html[] = '</div>';	
						}
						
					}
				}

				$return['aateam_products'] = array(
					'status' => 'valid',
					'html' => implode("\n", $html)
				);
			}
  
			die(json_encode($return));
		}
	}
}
