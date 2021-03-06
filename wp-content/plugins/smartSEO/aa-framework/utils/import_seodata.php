<?php
/*
* Define class psp_ImportSeoData
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
if (class_exists('psp_ImportSeoData') != true) {
	class psp_ImportSeoData {
	
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
			add_action('wp_ajax_pspimportSEOData', array( $this, 'import_seo_data' ));
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
		
		
		public function import_seo_data() {
			global $wpdb;

			$__importSEOFields = array(
				'WooThemes SEO Framework' => array(
					'meta title'            => 'seo_title',
					'meta description'      => 'seo_description',
					'meta keywords'         => 'seo_keywords'
				),
				'All-in-One SEO Pack - old version' => array(
					'meta title'            => 'title',
					'meta description'      => 'description',
					'meta keywords'         => 'keywords'
				),
				'All-in-One SEO Pack' => array(
					'meta title'            => '_aioseop_title',
					'meta description'      => '_aioseop_description',
					'meta keywords'         => '_aioseop_keywords'
				),
				'SEO Ultimate' => array(
					'meta title'            => '_su_title',
					'meta description'      => '_su_description',
					'meta keywords'         => '_su_keywords',
					'noindex'               => '_su_meta_robots_noindex',
					'nofollow'              => '_su_meta_robots_nofollow'
				),
				'Yoast WordPress SEO' => array(
					'meta title'            => '_yoast_wpseo_title',
					'meta description'      => '_yoast_wpseo_metadesc',
					'meta keywords'         => '_yoast_wpseo_metakeywords',
					'noindex'               => '_yoast_wpseo_meta-robots-noindex',
					'nofollow'              => '_yoast_wpseo_meta-robots-nofollow',
					'canonical url'         => '_yoast_wpseo_canonical',
					'focus keyword'         => '_yoast_wpseo_focuskw',
					'multi focus keyword'   => '_yoast_wpseo_focuskeywords',
					'sitemap include'       => '_yoast_wpseo_sitemap-include',
					'sitemap priority'      => '_yoast_wpseo_sitemap-prio',
					'facebook description'  => '_yoast_wpseo_opengraph-description'
				)
			);
			
			$__pspSEOFields = array(
				'meta title'            => array( array( 'title', 'psp_meta' ) ),
				'meta description'      => array( array( 'description', 'psp_meta' ) ),
				'meta keywords'         => array( array( 'keywords', 'psp_meta' ) ),
				'noindex'               => array( array( 'robots_index', 'psp_meta' ) ),
				'nofollow'              => array( array( 'robots_follow', 'psp_meta' ) ),
				'canonical url'         => array( array( 'canonical', 'psp_meta' ) ),
				'focus keyword'         => array( array( 'focus_keyword', 'psp_meta' ), array( 'psp_kw' ) ),
				'multi focus keyword'   => array( array( 'mfocus_keyword', 'psp_meta' ) ),
				'sitemap include'       => array( array( 'psp_sitemap_isincluded' ) ),
				'sitemap priority'      => array( array( 'priority', 'psp_meta' ) ),
				'facebook description'  => array( array( 'facebook_desc', 'psp_meta' ) )
			);
			
			$__convertValues = array(
				'noindex' => array(
					0       => 'default',
					1       => 'noindex',
					2       => 'index'
				),
				'nofollow' => array(
					0       => 'follow',
					1       => 'nofollow'
				),
				'sitemap include' => array(
					'-'         => 'default',
					'always'    => 'always_include',
					'never'     => 'never_include'
				)
			);

			$ret = array(
				'status' 		=> 'invalid',
				'html' 			=> 'unknown error occured.',
				'dbg' 			=> '',
				'nbposts' 		=> 0,
				'last_id' 		=> -1,
				'todo' 			=> '0',
			);

			// import meta data!
			$pluginFrom = isset($_REQUEST['from']) ? str_replace('+', ' ', trim(sanitize_text_field($_REQUEST['from']))) : '';
			$subaction = isset($_REQUEST['subaction']) ? sanitize_text_field($_REQUEST['subaction']) : '';
			//$rowsperstep = isset($_REQUEST['rowsperstep']) ? sanitize_text_field($_REQUEST['rowsperstep']) : 10;
			//$step = isset($_REQUEST['step']) ? sanitize_text_field($_REQUEST['step']) : 0;
			$last_id = isset($_REQUEST['last_id']) ? sanitize_text_field($_REQUEST['last_id']) : -1;
			$perstep = isset($_REQUEST['perstep']) ? sanitize_text_field($_REQUEST['perstep']) : 5;
			
			if ( empty($pluginFrom) ) { // validate selection!
				$ret = array_merge($ret, array(
					'html'		=> 'Invalid plugin from selection!',
				));
				die(json_encode($ret));
			}

			// execute import!
			$pluginFrom = $__importSEOFields[ "$pluginFrom" ];
			$fromMetaKeys = array_values($pluginFrom);

			$sql_postid = "select a.post_id from $wpdb->postmeta as a where 1=1 and a.meta_key regexp '^(" . implode('|', $fromMetaKeys) . ")' and a.post_id > $last_id group by a.post_id order by a.post_id asc limit $perstep;";
			if ( !empty($subaction) && $subaction == 'nbres' ) {
				$sql_postid = str_replace("limit $perstep", '', $sql_postid);
				$perstep = 0;
			}

			$res_postid = $wpdb->get_col( $sql_postid );
			$res_final = array( 'nbposts' => 0, 'last_id' => -1, 'todo' => '0' );
			if ( ! empty($res_postid) && is_array($res_postid) ) {
				$res_final['nbposts'] = count($res_postid);

				$__todo = $perstep ? array_slice($res_postid, 0, $perstep) : $res_postid;
				$res_final['todo'] = implode(',', $__todo); //$__todo;

				$first = reset($__todo);
				$last = end($__todo);

				$res_final['last_id'] = $last;
			}
			$todo = $res_final['todo'];

			if ( !empty($subaction) && $subaction == 'nbres' ) {
				// number of rows: get all post Ids which have metas from old plugin!
				//$sql_nb = "select count(a.post_id) as nb from $wpdb->postmeta as a where 1=1 and a.meta_key regexp '^(" . implode('|', $fromMetaKeys) . ")';";
				//$res_nb = $wpdb->get_var( $sql_nb );

				$res_final['todo'] = array();
				$ret = array_merge($ret, array(
					'status'        => 'valid',
					//'nbrows'        => $res_nb,
					//'html'          => sprintf(esc_html__('Total rows: %s.', 'smartSEO'), $res_nb )
					'html'          => sprintf(esc_html__('Total posts todo: %s.', 'smartSEO'), $res_final['nbposts'] )
				), $res_final);
				die(json_encode($ret));
			}

			// get all post Ids which have metas from old plugin!
			//$sql = "select a.post_id, a.meta_key, a.meta_value from $wpdb->postmeta as a where 1=1 and a.meta_key regexp '^(" . implode('|', $fromMetaKeys) . ")' order by a.post_id asc, a.meta_key asc limit $step, $rowsperstep;";
			//$res = $wpdb->get_results( $sql );

			$sql = "select a.post_id, a.meta_key, a.meta_value from $wpdb->postmeta as a where 1=1 and a.meta_key regexp '^(" . implode('|', $fromMetaKeys) . ")' and a.post_id in ($todo) order by a.post_id asc, a.meta_key asc;";
			$res = $wpdb->get_results( $sql );

			$ret['dbg'] = $res;
			if ( is_null($res) || empty($res) ) {
				if ( $res !== false ) {
					$ret = array_merge($ret, array(
						'status'        => 'valid',
						'html'			=> 'No updates made.',
					), $res_final);
				}
				die(json_encode($ret));
			}

			// statistics array!
			$nbPostsUpdated = 0;
			$nbPostsOptimized = 0;

			$current_post_id = reset( $res );
			$current_post_id = $current_post_id->post_id;

			$pspMetaValues = array();
			$multikw = array();

			array_push( $res, end($res) ); // duplicate last array element, so it's pspMetaValues value is executed too
			$i = 0;
			$resFound = count($res);
			foreach ( $res as $meta ) { // main foreach
				$i++;

				//var_dump('<pre>', $i, $meta->post_id, $meta->meta_key ,'</pre>');
				if ( ( $current_post_id != $meta->post_id ) || ( $i == $resFound ) ) { // next post Id meta rows

					if ( !empty($pspMetaValues) && is_array($pspMetaValues) ) {

						$pspUpd = 0;
						foreach ( $pspMetaValues as $psp_mk => $psp_mv) { // update metas for current post Id

							if ( is_array($psp_mv) && isset($psp_mv['mfocus_keyword']) ) {
								$multikw = array_map('trim', $multikw);
								$multikw = array_filter($multikw);
								$multikw = array_unique($multikw);

								$psp_mv['mfocus_keyword'] = implode("\n", $multikw);
							}

							if ( 'psp_meta' == $psp_mk ) {
								$psp_current = $this->the_plugin->get_psp_meta( $current_post_id );
							} else {
								$psp_current = get_post_meta( $current_post_id, $psp_mk, true);
							}

							if ( empty($psp_current) ) { // update empty meta values!
								//if ( $current_post_id == 22 /*|| $meta->post_id == 22*/ ) {
								//    var_dump('<pre>empty: ',$psp_mk,$psp_mv,'</pre>');
								//}
								$updStat = update_post_meta( $current_post_id, $psp_mk, $psp_mv );

								//if ( $updStat === true || (int) $updStat > 0 ) $pspUpd++;
								$pspUpd++;
							} else {
								//if ( $current_post_id == 14236 /*|| $meta->post_id == 22*/ ) {
								//	var_dump('<pre>NOT empty: ',$psp_mk,$psp_mv,$psp_current,'</pre>');
								//}
								if ( is_array($psp_current) ) { // update only array serialized meta values!
	
									//$psp_mv = array_merge( (array) $psp_mv, (array) $psp_current);
									// Update 2015-07-10
									$__ = (array) $psp_current;
									foreach ($psp_mv as $__k => $__v) {
										$__["$__k"] = isset($psp_current["$__k"]) ? $psp_current["$__k"] : '';
										if ( !empty($__v) ) {
											$__["$__k"] = $__v;
										}
									}
									$psp_mv = $__;
									$updStat = update_post_meta( $current_post_id, $psp_mk, $psp_mv );
									$pspMetaValues[ "$psp_mk" ] = $psp_mv;

									//if ( $updStat === true || (int) $updStat > 0 ) $pspUpd++;
									$pspUpd++;
								} else {
									$updStat = update_post_meta( $current_post_id, $psp_mk, $psp_mv );

									//if ( $updStat === true || (int) $updStat > 0 ) $pspUpd++;
									$pspUpd++;
								}
							}
						} // end foreach

						//var_dump('<pre>', $i, $current_post_id, $pspMetaValues ,'</pre>');
						if ( $pspUpd ) {
							$nbPostsUpdated++;
						}
						
						// psp specific meta!
						if ( $this->import_seo_data_pspExtra( $current_post_id, $pspMetaValues ) ) {
							$nbPostsOptimized++;
						}

					}

					$current_post_id = $meta->post_id;
					$pspMetaValues = array();
					$multikw = array(); // reset metas to be used by next post Id

				} // end if next post Id meta rows

				// current post Id meta rows
				$alias = array_search( $meta->meta_key, $pluginFrom );
				$pspMetaKey = false !== $alias && isset($__pspSEOFields[ "$alias" ]) ? $__pspSEOFields[ "$alias" ] : array();
				//var_dump('<pre>', $i, $meta->post_id, $pspMetaKey ,'</pre>');

				if ( is_array($pspMetaKey) && count($pspMetaKey) >= 1 ) {
					foreach ( $pspMetaKey as $psp_kb ) {

						// multi focus keyword
						if ( $alias == 'focus keyword' ) {
							array_unshift($multikw, $meta->meta_value);
						} else if ( $alias == 'multi focus keyword' ) {
							$multikw2 = $meta->meta_value;
							$multikw2 = json_decode( $multikw2 );
							if ( ! empty($multikw2) && is_array($multikw2) ) {
								foreach ($multikw2 as $multiA => $multiB) {
									if ( isset($multiB->keyword) ) {
										$multikw[] = $multiB->keyword;
									}
								}
							}
						}
						// other value conversions
						else if ( isset($__convertValues[ "$alias" ])
							&& isset($__convertValues[ "$alias" ][ "{$meta->meta_value}"])
						) {
							$meta->meta_value = $__convertValues[ "$alias" ][ "{$meta->meta_value}"];
						}

						if ( count($psp_kb) == 2 ) {
							$pspMetaValues[ "{$psp_kb[1]}" ][ "{$psp_kb[0]}" ] = $meta->meta_value;
						} else {
							$pspMetaValues[ "{$psp_kb[0]}" ] = $meta->meta_value;
						}
					}
				}

			} // end main foreach

			$msg = array();
			//$msg[] = sprintf(esc_html__('Rows: <strong>%s - %s</strong>.', 'smartSEO'), $step, ( $step + $rowsperstep - 1) );
			$msg[] = sprintf(esc_html__('Post IDs done at this step: <strong>%s</strong>.', 'smartSEO'), $todo );
			$msg[] = sprintf(esc_html__('Total number of posts updated: <strong>%s</strong>.', 'smartSEO'), $nbPostsUpdated );
			$msg[] = sprintf(esc_html__('Total number of posts optimized: <strong>%s</strong>.', 'smartSEO'), $nbPostsOptimized );

			$ret = array_merge($ret, array(
				'status'		=> 'valid',
				'html'			=> implode('<br />', $msg)
			), $res_final);
			die(json_encode($ret));
		}
		
		private function import_seo_data_pspExtra( $post_id = 0, $meta = array() ) {
			
			if ( $post_id <= 0 ) {
				return false;
			}
			if ( empty($meta) ) {
				return false;
			}
			
			$post_metas = $this->the_plugin->get_psp_meta( $post_id );

			$post_metas = array_merge(array(
				'title'             => '',
				'description'       => '',
				'keywords'          => '',
				'focus_keyword'     => '',
				'mfocus_keyword'    => '',
	
				'facebook_isactive' => '',
				'facebook_titlu'    => '',
				'facebook_desc'     => '',
				'facebook_image'    => '',
				'facebook_opengraph_type'   => '',
				
				'robots_index'      => '',
				'robots_follow'     => '',
	
				'priority'          => '',
				'canonical'         => ''
			), $post_metas);

			// include on page optimization module!
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/on_page_optimization/init.php');
			$psp_OnPageOptimization = new psp_OnPageOptimization();

			$_REQUEST = array(
				'psp-field-title'                   => $post_metas['title'],
				'psp-field-metadesc'                => $post_metas['description'],
				'psp-field-metakewords'             => $post_metas['keywords'],
				'psp-field-focuskw'                 => $post_metas['focus_keyword'],
				'psp-field-multifocuskw'            => $post_metas['mfocus_keyword'],
	
				'psp-field-facebook-isactive'       => $post_metas['facebook_isactive'],
				'psp-field-facebook-titlu'          => $post_metas['facebook_titlu'],
				'psp-field-facebook-desc'           => $post_metas['facebook_desc'],
				'psp-field-facebook-image'          => $post_metas['facebook_image'],
				'psp-field-facebook-opengraph-type' => $post_metas['facebook_opengraph_type'],
	
				'psp-field-meta_robots_index'       => $post_metas['robots_index'],
				'psp-field-meta_robots_follow'      => $post_metas['robots_follow'],
	
				'psp-field-priority-sitemap'        => $post_metas['priority'],
				'psp-field-canonical'               => $post_metas['canonical']
			);
			$psp_OnPageOptimization->optimize_page( $post_id );
			
			return true;
		}
	}
}

// Initialize the psp_ImportSeoData class
//$psp_ImportSeoData = new psp_ImportSeoData();
