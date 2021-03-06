<?php
/*
* Define class psp_ActionAdminAjax
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
if (class_exists('psp_ActionAdminAjax') != true) {
	class psp_ActionAdminAjax {
	
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';

		/*
		* Store some helpers config
		*/
		public $the_plugin = null;

		protected static $_instance;
		
	
		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct( $parent ) {
			$this->the_plugin = $parent;

			if ( $this->the_plugin->is_admin ) {
				// search engines notify
				add_action('wp_ajax_pspAdminAjax', array( $this, 'admin_ajax' ));

				// minify module
				add_action('wp_ajax_pspMinifyAdminCache', array( $this, 'admin_minify_cache' ));
				add_action('wp_ajax_pspMinifyAdminExcluding', array( $this, 'admin_minify_excluding' ));

				// video sitemap - metas delete
				add_action('wp_ajax_pspVideoMetas', array( $this, 'video_metas' ));

				// cronjobs panel
				add_action('wp_ajax_psp_cronjobs', array( $this, 'cronjobs_actions' ));
			}
			add_action('wp_ajax_pspSocialSharing', array( $this, 'social_sharing' ));
			add_action('wp_ajax_pspTwitterCards', array( $this, 'twitter_cards' ));

			add_action('wp_ajax_pspSocialSharingFrontend', array( $this, 'social_sharing_frontend' ));
			add_action('wp_ajax_nopriv_pspSocialSharingFrontend', array( $this, 'social_sharing_frontend' ));
		}
		
		/**
		* Singleton pattern
		*
		* @return pspFileEdit Singleton instance
		*/
		public static function getInstance() {
			if (!self::$_instance) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
		
		
		/**
		 * Sitemap
		 *
		 */
		public function admin_ajax() {
			$action = isset($_REQUEST['sub_action']) ? sanitize_text_field($_REQUEST['sub_action']) : '';
			$engine = isset($_REQUEST['engine']) ? strtolower(sanitize_text_field($_REQUEST['engine'])) : '';
			$sitemap_type = isset($_REQUEST['sitemap_type']) ? sanitize_text_field($_REQUEST['sitemap_type']) : 'sitemap';

			$sitemapList = array(
				'sitemap' 					=> 'Sitemap.xml',
				'sitemap_images' 		=> 'Sitemap-Images.xml',
				'sitemap_videos' 		=> 'Sitemap-Videos.xml',
				'kml'							=> 'sitemap-locations.kml',
				'xml'							=> 'sitemap-locations.xml',
			);
			$sitemapCurrent = $sitemapList[ "$sitemap_type" ];

			$ret = array(
				'status'			=> 'invalid',
				'start_date'		=> date('Y-m-d H:i:s'),
				'start_time'		=> 0,
				'end_time'			=> 0,
				'duration'			=> 0,
				'msg'				=> '',
				'msg_html'			=> ''
			);

			if ( $action == 'getStatus') {

				$notifyStatus = $this->the_plugin->get_theoption('psp_sitemap_engine_notify');
				if ( $notifyStatus === false || !isset($notifyStatus["$engine"]) || !isset($notifyStatus["$engine"]["$sitemap_type"]) ) {
				} else {
					$ret['status'] = 'valid';
					$ret['msg_html'] = $notifyStatus["$engine"]["$sitemap_type"]['msg_html'];
				}
				
				die(json_encode($ret));
			}
			
			$sitemapUrl =  home_url('/sitemap.xml');
			switch ($sitemap_type) {
				case 'sitemap_images':
					$sitemapUrl = home_url('/sitemap-images.xml');
					break;
				case 'sitemap_videos':
					$sitemapUrl = home_url('/sitemap-videos.xml');
					break;
				default:
					break;
			}
			if ( $action == 'localseo_notify' ) {

				if ( $sitemap_type == 'kml' ) {
					$sitemapUrl =  home_url('/sitemap-locations.kml');
				} else {
					$sitemapUrl =  home_url('/sitemap-locations.xml');
				}
			}

			if ( in_array($action, array('notify', 'localseo_notify')) && $engine == 'google' ) {
				$engineTitle =esc_html__('Google', 'smartSEO');
				$pingUrl = 'http://www.google.com/webmasters/sitemaps/ping?sitemap=';
				$pingUrl .= urlencode( $sitemapUrl );
			} else if ( in_array($action, array('notify', 'localseo_notify')) && $engine == 'bing' ) {
				$engineTitle =esc_html__('Bing', 'smartSEO');
				$pingUrl = 'http://www.bing.com/webmaster/ping.aspx?siteMap=';
				$pingUrl .= urlencode( $sitemapUrl );
			}

			if ( in_array($action, array('notify', 'localseo_notify')) && in_array($engine, array('google', 'bing')) ) {
			} else {
				$ret['msg_html'] = 'unknown request';
				die(json_encode($ret));
			}

			if ( $action == 'localseo_notify' ) {
				$notifyStatus = $this->the_plugin->get_theoption('psp_localseo_engine_notify');
			} else {
				$notifyStatus = $this->the_plugin->get_theoption('psp_sitemap_engine_notify');
			}

			$ret['start_time'] = $this->the_plugin->microtime_float();

			$response = wp_remote_get( $pingUrl, array('timeout' => 10) );
			if ( is_wp_error( $response ) ) { // If there's error
				$ret = array_merge($ret, array(
					'end_time'		=> $this->the_plugin->microtime_float(),
					'msg'			=> htmlspecialchars( implode(';', $response->get_error_messages()) ),
					'msg_html'		=> '<span class="error">' . ( $engine . ' / ' . $sitemapCurrent ) .esc_html__(' couldn\'t be notified!', 'smartSEO') . '</span>'
				));
				$ret['duration'] = number_format( ( $ret['end_time'] - $ret['start_time'] ), 2 );

				$notifyStatus["$engine"]["$sitemap_type"] = $ret;
				if ( $action == 'localseo_notify' ) {
					$this->the_plugin->save_theoption('psp_localseo_engine_notify', $notifyStatus);
				} else {
					$this->the_plugin->save_theoption('psp_sitemap_engine_notify', $notifyStatus);
				}
				die(json_encode($ret));
			}

			$body = wp_remote_retrieve_body( $response );

			$ret = array_merge($ret, array(
				'end_time'		=> $this->the_plugin->microtime_float(),
				'msg'			=> $body,
				'msg_html'		=> '<span class="error">' . ( $engine . ' / ' . $sitemapCurrent ) .esc_html__(' couldn\'t be notified | invalid response received!', 'smartSEO') . '</span>'
			));
			$ret['duration'] = number_format( ( $ret['end_time'] - $ret['start_time'] ), 2 );

			if ( is_null( $body ) || $body === false ) {
			} else {
				$ret['status'] 		= 'valid';
				$ret['msg_html']	= '<span class="success">' . ( $engine . ' / ' . $sitemapCurrent ) . sprintf(esc_html__(' was notified successfully on %1$s | ping duration: %2$s seconds.', 'smartSEO'), $ret['start_date'], $ret['duration'] ) . '</span>';
			}
			
			$notifyStatus["$engine"]["$sitemap_type"] = $ret;
			if ( $action == 'localseo_notify' ) {
				$this->the_plugin->save_theoption('psp_localseo_engine_notify', $notifyStatus);
			} else {
				$this->the_plugin->save_theoption('psp_sitemap_engine_notify', $notifyStatus);
			}
			die(json_encode($ret));
		}
		
		/**
		 * Twitter Cards
		 *
		 */
		public function twitter_cards() {
			$action = isset($_REQUEST['sub_action']) ? sanitize_text_field($_REQUEST['sub_action']) : '';
			$card_type = isset($_REQUEST['card_type']) ? strtolower(sanitize_text_field($_REQUEST['card_type'])) : '';
			$page = isset($_REQUEST['page']) ? strtolower(sanitize_text_field($_REQUEST['page'])) : '';
			$post_id = isset($_REQUEST['post_id']) ? (int) sanitize_text_field($_REQUEST['post_id']) : 0;
			$box_taxonomy = isset($_REQUEST['box_taxonomy']) ? (string) sanitize_text_field($_REQUEST['box_taxonomy']) : '';
			$box_termid = isset($_REQUEST['box_termid']) ? (int) sanitize_text_field($_REQUEST['box_termid']) : 0;

			$ret = array(
				'status'		=> 'invalid',
				'html'			=> ''
			);
			
			// twitter cards module
			require_once( 'twitter_cards.php' );
			$twc = new psp_TwitterCards( $this->the_plugin );
			
			if ( $action == 'getCardTypeOptions') {

				$ret['status'] = 'valid';
				$ret['html'] = $twc->build_options(array(
					'card_type'	=> $card_type,
					'page' 			=> $page,
					'post_id' 		=> $post_id,
					'box_taxonomy' 	=> $box_taxonomy,
					'box_termid' 			=> $box_termid
				));
			}
			die(json_encode($ret));
		}
		
		/**
		 * Social Sharing
		 */
		public function social_sharing() {
			$action = isset($_REQUEST['sub_action']) ? sanitize_text_field($_REQUEST['sub_action']) : '';
			$toolbar = isset($_REQUEST['toolbar']) ? strtolower(sanitize_text_field($_REQUEST['toolbar'])) : '';

			$ret = array(
				'status'		=> 'invalid',
				'html'			=> ''
			);

			if ( in_array($action, array('getToolbarOptions')) ) {			
				// social sharing module
				require_once( 'social_sharing.php' );
				$ssh = new psp_SocialSharing( $this->the_plugin );
			}
			
			if ( $action == 'getToolbarOptions' ) {

				$ret['status'] = 'valid';
				$ret['html'] = $ssh->build_toolbar_options(array('toolbar' => $toolbar));
			}
			die(json_encode($ret));
		}
		
		public function social_sharing_frontend() {
			$action = isset($_REQUEST['sub_action']) ? sanitize_text_field($_REQUEST['sub_action']) : '';
			$buttons = isset($_REQUEST['buttons']) ? strtolower(sanitize_text_field($_REQUEST['buttons'])) : '';
			$urls = isset($_REQUEST['urls']) ? sanitize_text_field($_REQUEST['urls']) : '';
  
			if ( empty($buttons) || empty($urls) || !is_array($urls) ) {
				$ret = array(
					'status'		=> 'invalid',
					'html'			=> 'is invalid'
				);
			}

			if ( in_array($action, array('getCount')) ) {
				// social sharing module
				require_once( 'social_sharing.php' );
				$ssh = new psp_SocialSharing( $this->the_plugin );
			}

			$results = array();
			if ( $action == 'getCount' ) {
				
				$buttons = explode(',', $buttons);

				$c = 0;
				foreach ($urls as $key => $val) {
					$countStat = $ssh->getSocialsData( $val['url'], $val['id'] );
					foreach ($buttons as $key2 => $network) {
						if ( isset($countStat["$network"]) ) {
							$results[$val['id']][$network] = $ssh->formatCount( $countStat["$network"] );
						}
					}
					$c++;
				}  
  
				$ret['status'] = 'valid';
				$ret['html'] = 'buttons: ' . implode(',', $buttons);
				$ret['results'] = $results;
			}
			die(json_encode($ret));
		}
	
		/**
		 * Minify
		 */
		public function admin_minify_cache() {
			$action = isset($_REQUEST['sub_action']) ? sanitize_text_field($_REQUEST['sub_action']) : '';

			$ret = array(
				'status'            => 'invalid',
				'start_date'        => date('Y-m-d H:i:s'),
				/*'start_time'        => 0,
				'end_time'          => 0,
				'duration'          => 0,*/
				'msg'               => '',
				'msg_html'          => ''
			);

			if ( in_array($action, array('getStatus', 'cache_delete')) ) {
			  
				require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '/modules/Minify/init.php' );
				$pspMinify = pspMinify::getInstance();

			} else {
				$ret['msg_html'] = 'unknown request';
				die(json_encode($ret));
			}

			if ( $action == 'getStatus') {

				//$notifyStatus = $this->the_plugin->get_theoption('psp_Minify');
				//if ( $notifyStatus === false || !isset($notifyStatus["cache"]) ) ;
				//else {
					$ret['status'] = 'valid';
					//$ret['msg_html'] = $notifyStatus["cache"]["msg_html"];
  
					$nb = (int) $pspMinify->get_folder_files_recursive( pspMinify::$paths['cache_path'] );
					$ret['msg_html'] = '<span class="success">' . sprintf(esc_html__('number of files in cache: %d | date: %s.', 'smartSEO'), $nb, $ret['start_date'] ) . '</span>';
				//}
				
				die(json_encode($ret));
			}
			
			if ( $action == 'cache_delete' ) {
				
				$files = glob( pspMinify::$paths['cache_path'] . '*.*' );
				if ( is_array( $files ) ) {
					array_map( 'unlink', $files );
				}
				
				$files2 = glob( pspMinify::$paths['save_remote_path'] . '*.*' );
				if ( is_array( $files2 ) ) {
					array_map( 'unlink', $files2 );
				}
				
				$nb = $pspMinify->get_folder_files_recursive( pspMinify::$paths['cache_path'] );
			}

			$notifyStatus = $this->the_plugin->get_theoption('psp_Minify');

			if ( 1 ) {
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'msg'       => 'success',
					'msg_html'  => '<span class="success">' . sprintf(esc_html__('number of files in cache: %d | date: %s.', 'smartSEO'), $nb, $ret['start_date'] ) . '</span>',
				));
			}
			
			$notifyStatus['cache'] = $ret;
			$this->the_plugin->save_theoption('psp_Minify', $notifyStatus);
			die(json_encode($ret));
		}

		public function admin_minify_excluding() {
			$action = isset($_REQUEST['sub_action']) ? sanitize_text_field($_REQUEST['sub_action']) : '';

			$ret = array(
				'status'            => 'invalid',
				'start_date'        => date('Y-m-d H:i:s'),
				/*'start_time'        => 0,
				'end_time'          => 0,
				'duration'          => 0,*/
				'msg'               => '',
				'msg_html'          => ''
			);
			
			if ( in_array($action, array('getStatus', 'reset', 'refresh')) ) {
			} else {
				$ret['msg_html'] = 'unknown request';
				die(json_encode($ret));
			}

			if ( $action == 'getStatus') {

				$notifyStatus = $this->the_plugin->get_theoption('psp_Minify');
				if ( $notifyStatus === false || !isset($notifyStatus['exclude']) ) {
				} else {
					$ret['status'] = 'valid';
					$ret['msg_html'] = $notifyStatus['exclude']['msg_html'];
				}
				
				die(json_encode($ret));
			}
			
			if ( $action == 'reset' ) {
				delete_option('psp_Minify_assets');
				
			} else if ( $action == 'refresh' ) {
				// nothing to do - just refresh!
			}

			$notifyStatus = $this->the_plugin->get_theoption('psp_Minify');

			/*$ret['start_time'] = $this->the_plugin->microtime_float();

			$pingUrl = 'http://www.google.com';
			$response = wp_remote_get( $pingUrl, array('timeout' => 10) );
			if ( is_wp_error( $response ) ) { // If there's error
				$ret = array_merge($ret, array(
					'end_time'      => $this->the_plugin->microtime_float(),
					'msg'           => htmlspecialchars( implode(';', $response->get_error_messages()) ),
					'msg_html'      => '<span class="error">' .esc_html__('error msg.', 'smartSEO') . '</span>'
				));
				$ret['duration'] = number_format( ($ret['end_time'] - $ret['start_time']), 2 );

				$notifyStatus["exclude"] = $ret;
				$this->the_plugin->save_theoption('psp_Minify', $notifyStatus);
				die(json_encode($ret));
			}

			$body = wp_remote_retrieve_body( $response );

			$ret = array_merge($ret, array(
				'end_time'      => $this->the_plugin->microtime_float(),
				'msg'           => 'error',
				'msg_html'      => '<span class="error">' .esc_html__('error msg.', 'smartSEO') . '</span>'
			));
			$ret['duration'] = number_format( ($ret['end_time'] - $ret['start_time']), 2 );

			if ( is_null( $body ) || $body === false ) ;
			else {
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'msg'       => 'success',
					'msg_html'  => '<span class="success">' . sprintf(esc_html__(' ping date: %s | ping duration: %s seconds.', 'smartSEO'), $ret['start_date'], $ret['duration'] ) . '</span>',
				));
			}*/
			
			if ( 1 ) {
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'msg'       => 'success',
					'msg_html'  => '<span class="success">' . sprintf(
						esc_html__('last operation: %s | execution date: %s.', 'smartSEO'),
						$action,
						$ret['start_date']
					) . '</span>',
				));
			}
			
			$notifyStatus['exclude'] = $ret;
			$this->the_plugin->save_theoption('psp_Minify', $notifyStatus);
			die(json_encode($ret));
		}
	
	
		/**
		 * Video Metas
		 */
		public function video_metas() {
			$action = isset($_REQUEST['sub_action']) ? sanitize_text_field($_REQUEST['sub_action']) : '';

			$ret = array(
				'status'			=> 'invalid',
				'start_date'		=> date('Y-m-d H:i:s'),
				'start_time'		=> 0,
				'end_time'			=> 0,
				'duration'			=> 0,
				'msg'				=> '',
				'msg_html'			=> ''
			);

			if ( $action == 'getStatus') {

				$notifyStatus = $this->the_plugin->get_theoption('psp_video_metas');
				if ( $notifyStatus === false || !isset($notifyStatus['clean']) ) {
				} else {
					$ret['status'] = 'valid';
					$ret['msg_html'] = $notifyStatus['clean']['msg_html'];
				}

				die(json_encode($ret));
			}

			if ( in_array($action, array('clean')) ) {
			} else {
				$ret['msg_html'] = 'unknown request';
				die(json_encode($ret));
			}

			if ( $action == 'clean' ) {
				$notifyStatus = $this->the_plugin->get_theoption('psp_video_metas');
			}

			$ret['start_time'] = $this->the_plugin->microtime_float();

			global $wpdb;
			$sql = '
				{select}
	             FROM ' . $wpdb->prefix . 'postmeta AS a
	             WHERE 1=1
	             {regexp}
	             {orderby}
				;
	        ';
			/*
			$sql = str_replace( array('{select}', '{regexp}', '{orderby}'), array(
				'SELECT count(a.meta_id) as nbfound',
				"AND a.meta_key regexp '^psp_videos' and meta_key not regexp '_stat$'",
				'ORDER BY a.meta_id ASC',
			), $sql );
			//var_dump('<pre>', $sql, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			*/ 
			///*
			$sql = str_replace( array('{select}', '{regexp}', '{orderby}'), array(
				'DELETE a.*',
				"AND a.meta_key regexp '^psp_videos'",
				'',
			), $sql );
			//*/
			$nbfound = $wpdb->query( $sql );

			$ret = array_merge($ret, array(
				'end_time'		=> $this->the_plugin->microtime_float(),
				'msg'			=> '',
			));
			$ret['duration'] = number_format( ( $ret['end_time'] - $ret['start_time'] ), 2 );

			if (1) {
				$ret['status'] 		= 'valid';
				$ret['msg_html']	= '<span class="success">' . (esc_html__('Last time: ', 'smartSEO') ) . sprintf(esc_html__(' %1$s video metas deleted | data: %2$s | duration: %3$s seconds.', 'smartSEO'), $nbfound, $ret['start_date'], $ret['duration'] ) . '</span>';
			}
			
			$notifyStatus['clean'] = $ret;
			if ( $action == 'clean' ) {
				$this->the_plugin->save_theoption('psp_video_metas', $notifyStatus);
			}
			die(json_encode($ret));
		}

		/**
		 * Cronjobs Panel - ajax actions
		 *
		 */
		public function cronjobs_actions( $retType = 'die' ) {    
			// Initialize the cronjobs class
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '/modules/cronjobs/cronjobs.panel.php' );
			$cronObj = new pspCronjobsPanel($this->the_plugin, array());

			$cronObj->ajax_request();
		}
	}
}

// Initialize the psp_ActionAdminAjax class
//$psp_ActionAdminAjax = new psp_ActionAdminAjax();
