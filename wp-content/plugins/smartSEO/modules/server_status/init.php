<?php
/*
* Define class psp_ServerStatus
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;

if (class_exists('psp_ServerStatus') != true) {
	class psp_ServerStatus {
	
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';

		/*
		* Store some helpers config
		*/
		public $the_plugin = null;

		private $module_folder = '';
		private $module = '';

		protected static $_instance;

		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct() {
			global $psp;

			$this->the_plugin = $psp;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/server_status/';
			$this->module = $this->the_plugin->cfg['modules']['server_status'];

			if (is_admin()) {
				add_action('admin_menu', array( &$this, 'adminMenu' ));
			}

			// load the ajax helper
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/server_status/ajax.php' );
			new psp_ServerStatusAjax( $this->the_plugin );
		}

		/**
		* Singleton pattern
		*
		* @return psp_ServerStatus Singleton instance
		*/
		public static function getInstance() {
			if (!self::$_instance) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		* Hooks
		*/
		public static function adminMenu() {
			self::getInstance()
				->_registerAdminPages();
		}

		/**
		* Register plug-in module admin pages and menus
		*/
		protected function _registerAdminPages() {
			add_submenu_page(
				$this->the_plugin->alias,
				$this->the_plugin->alias . ' ' .esc_html__('Check System status', 'smartSEO'),
				esc_html__('System Status', 'smartSEO'),
				'manage_options',
				$this->the_plugin->alias . '_server_status',
				array($this, 'display_index_page')
			);

			return $this;
		}

		public function display_index_page() {
			$this->printBaseInterface();
		}
		
		/*
		* printBaseInterface, method
		* --------------------------
		*
		* this will add the base DOM code for you options interface
		*/
		private function printBaseInterface() {
			global $wpdb;
					

			$plugin_data = get_plugin_data( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'plugin.php' );  
			?>
		<script type="text/javascript" src="<?php echo $this->module_folder; ?>app.class.js" ></script>
		
		<div class="<?php echo $this->the_plugin->alias; ?>">
			
			<div class="<?php echo $this->the_plugin->alias; ?>-content">
			
				<?php
				// show the top menu
				psp_AdminMenu::getInstance()->make_active('general|server_status')->show_menu();
				?>
				
				<!-- Content -->
				<section class="<?php echo $this->the_plugin->alias; ?>-main">
						
					<?php 
					echo psp()->print_section_header(
						$this->module['server_status']['menu']['title'],
						$this->module['server_status']['description'],
						$this->module['server_status']['help']['url']
					);
					?>
					
					<div class="panel panel-default <?php echo $this->the_plugin->alias; ?>-panel">
			
						<!-- Main loading box -->
						<div id="psp-main-loading">
							<div id="psp-loading-overlay"></div>
							<div id="psp-loading-box">
								<div class="psp-loading-text"><?php esc_html_e('Loading', 'smartSEO'); ?></div>
								<div class="psp-meter psp-animate" style="width:86%; margin: 34px 0px 0px 7%;"><span style="width:100%"></span></div>
							</div>
						</div>

						<div class="panel-body <?php echo $this->the_plugin->alias; ?>-panel-body">
							
							<!-- Container -->
							<div class="psp-container clearfix">
			
								<!-- Main Content Wrapper -->
								<div id="psp-content-wrap" class="clearfix">
									
									<div class="psp-panel">
										
										<div class="psp-panel-content psp-server-status">
											
											<table class="psp-table" cellspacing="0">
												
												<thead>
													<tr>
														<th colspan="2"><?php esc_html_e('Modules', 'smartSEO'); ?></th>
													</tr>
												</thead>
										
												<tbody>
													 <tr>
														 <td><?php esc_html_e('Active Modules', 'smartSEO'); ?>:</td>
														 <td><div class="psp-loading-ajax-details" data-action="active_modules"></div></td>
													 </tr>
												</tbody>
												
												
												<?php
													$opStatus_stat = $this->the_plugin->plugin_integrity_get_last_status( 'check_database' );
													
													$check_last_msg = '';
												if ( '' != trim($opStatus_stat['html']) ) {
													$check_last_msg = ( $opStatus_stat['status'] == true ? '<div class="psp-message psp-success">' : '<div class="psp-message psp-error">' ) . $opStatus_stat['html'] . '</div>';
												}
												?>
														
												<thead>
													<tr>
														<th colspan="2"><?php esc_html_e('Environment', 'smartSEO'); ?></th>
													</tr>
												</thead>
										
												<tbody>
													<tr>
														<td width="190"><?php esc_html_e('Home URL', 'smartSEO'); ?>:</td>
														<td><?php echo home_url(); ?></td>
													</tr>
													<tr>
														<td><?php esc_html_e('Plugin Version', 'smartSEO'); ?>:</td>
														<td><?php echo $plugin_data['Version']; ?></td>
													</tr>
													<tr>
														<td><?php esc_html_e('WP Version', 'smartSEO'); ?>:</td>
														<td>
														<?php 
														if ( is_multisite() ) {
															echo 'WPMU';
														} else {
															echo 'WP';
														} 
														?>
														 <?php bloginfo('version'); ?></td>
													</tr>
													<tr>
														<td><?php esc_html_e('Web Server Info', 'smartSEO'); ?>:</td>
														<td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
													</tr>
													<tr>
														<td><?php esc_html_e('PHP Version', 'smartSEO'); ?>:</td>
														<td>
														<?php 
														if ( function_exists( 'phpversion' ) ) {
															echo esc_html( phpversion() );} 
														?>
														</td>
													</tr>
													<tr>
														<td><?php esc_html_e('MySQL Version', 'smartSEO'); ?>:</td>
														<td>
														<?php 
														if ( function_exists( 'mysql_get_server_info' ) ) {
															echo esc_html( ( is_resource($wpdb->dbh) ) ? mysql_get_server_info( $wpdb->dbh ) : $wpdb->db_version() );} 
														?>
														</td>
													</tr>
													<tr>
														<td><?php esc_html_e('WP Memory Limit', 'smartSEO'); ?>:</td>
														<td><div class="psp-loading-ajax-details" data-action="check_memory_limit"></div></td>
													</tr>
													<tr>
														<td><?php esc_html_e('WP Debug Mode', 'smartSEO'); ?>:</td>
														<td>
														<?php 
														if ( defined('WP_DEBUG') && WP_DEBUG ) {
															echo esc_html__('Yes', 'smartSEO');
														} else {
															echo esc_html__('No', 'smartSEO');
														} 
														?>
														</td>
													</tr>
													<tr>
														<td><?php esc_html_e('WP Max Upload Size', 'smartSEO'); ?>:</td>
														<td><?php echo size_format( wp_max_upload_size() ); ?></td>
													</tr>
													<tr>
														<td><?php esc_html_e('PHP Post Max Size', 'smartSEO'); ?>:</td>
														<td>
														<?php 
														if ( function_exists( 'ini_get' ) ) {
															echo size_format( $this->let_to_num( ini_get('post_max_size') ) );} 
														?>
														</td>
													</tr>
													<tr>
														<td><?php esc_html_e('PHP Time Limit', 'smartSEO'); ?>:</td>
														<td>
														<?php 
														if ( function_exists( 'ini_get' ) ) {
															echo ini_get('max_execution_time');} 
														?>
														</td>
													</tr>
											   
												</tbody>
										
												<thead>
													<tr>
														<th colspan="2"><?php esc_html_e('Plugins', 'smartSEO'); ?></th>
													</tr>
												</thead>
										
												<tbody>
													 <tr>
														 <td><?php esc_html_e('Installed Plugins', 'smartSEO'); ?>:</td>
														 <td><div class="psp-loading-ajax-details" data-action="active_plugins"></div></td>
													 </tr>
												</tbody>
										
												<thead>
													<tr>
														<th colspan="2"><?php esc_html_e('Settings', 'smartSEO'); ?></th>
													</tr>
												</thead>
										
												<tbody>
										
													<tr>
														<td><?php esc_html_e('Force SSL', 'smartSEO'); ?>:</td>
														<td><?php echo get_option( 'woocommerce_force_ssl_checkout' ) === 'yes' ? esc_html__('Yes', 'smartSEO') : esc_html__('No', 'smartSEO'); ?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>

			<?php
		}

		/*
		* ajax_request, method
		* --------------------
		*
		* this will create requesto to 404 table
		*/
		public function ajax_request() {
			global $wpdb;
			$request = array(
				'id' 			=> isset($_REQUEST['id']) ? (int) sanitize_text_field($_REQUEST['id']) : 0
			);
			
			$asin = get_post_meta($request['id'], '_amzASIN', true);
			
			$sync = new wwcAmazonSyncronize( $this->the_plugin );
			$sync->updateTheProduct( $asin, $request['id'] );
		}
		
		public function let_to_num( $size) {
			if ( function_exists('wc_let_to_num') ) {
				return wc_let_to_num( $size );
			}

			$l = substr($size, -1);
			$ret = substr($size, 0, -1);
			switch ( strtoupper( $l ) ) {
				case 'P':
					$ret *= 1024;
				case 'T':
					$ret *= 1024;
				case 'G':
					$ret *= 1024;
				case 'M':
					$ret *= 1024;
				case 'K':
					$ret *= 1024;
			}
			return $ret;
		}

		public function fb_auth_url( $pms = array() ) {
			$pms = array_merge(array(
				'facebook'			=> null,
				'fb_details'		=> array(),
				'psp_redirect_url'	=> '',
				'text'				=>esc_html__('Authorize app', 'smartSEO'),
			), $pms);
			extract($pms);

			$ret = array(
				'html'				=> '',
				'url'				=> '',
			);

			if ( 'fbv4' == $this->the_plugin->facebook_sdk_version ) {
				$ret = array_merge( $ret, $this->the_plugin->facebook_get_authorization_url( $pms ) );
			}

			return $ret;
		}
	}
}
// Initialize the psp_ServerStatus class
//$psp_ServerStatus = new psp_ServerStatus();
$psp_ServerStatus = psp_ServerStatus::getInstance();
