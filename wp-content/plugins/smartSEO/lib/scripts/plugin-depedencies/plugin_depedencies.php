<?php 
if (class_exists('psp_aaTeamPluginDepedencies') != true) {
	class psp_aaTeamPluginDepedencies {
	
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';

		/*
		* Store some helpers config
		*/
		public $the_plugin = null;

		protected static $_instance;
		
		private static $debug = false;


		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct( $the_plugin ) {
			$this->the_plugin = $the_plugin;
		}

		/**
		* Singleton pattern
		*
		* @return wwcAmzAffSpinner Singleton instance
		*/
		public static function getInstance() {
			if (!self::$_instance) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
		
		
		public function initDepedenciesPage() {
			$is_admin = is_admin() === true ? true : false;
			
			// If the user can manage options, let the fun begin!
			if ( $is_admin /*&& current_user_can( 'manage_options' )*/ ) {
				if ($is_admin) {
					// Adds actions to hook in the required css and javascript
					add_action( 'admin_print_styles', array( $this->the_plugin, 'admin_load_styles') );
					add_action( 'admin_print_scripts', array( $this->the_plugin, 'admin_load_scripts') );
				}

				// create dashboard page
				add_action( 'admin_menu', array( $this, 'createDepedenciesPage' ) );

				// get fatal errors
				add_action ( 'admin_notices', array( $this->the_plugin, 'fatal_errors'), 10 );

				// get fatal errors
				add_action ( 'admin_notices', array( $this->the_plugin, 'admin_warnings'), 10 );
			}
			
			$this->the_plugin->load_modules( 'depedencies' );
		}
		
		public function createDepedenciesPage() {
			add_menu_page(
				$this->the_plugin->pluginName . esc_html__(' Depedencies', 'smartSEO' ),
				$this->the_plugin->pluginName . esc_html__(' Depedencies', 'smartSEO' ),
				'manage_options',
				$this->the_plugin->alias,
				array( $this, 'depedencies_manage_options_template' ),
				$this->the_plugin->cfg['paths']['plugin_dir_url'] . 'icon_16.png'
			);
		}
		
		public function depedencies_manage_options_template() {
			// Derive the current path and load up psp_aaInterfaceTemplates
			$plugin_path = $this->the_plugin->cfg['paths']['freamwork_dir_path'];
			if (class_exists('psp_aaInterfaceTemplates') != true) {
				require_once($plugin_path . 'settings-template.class.php');

				// Initalize the your psp_aaInterfaceTemplates
				$psp_aaInterfaceTemplates = new psp_aaInterfaceTemplates($this->the_plugin->cfg);

				// try to init the interface
				$psp_aaInterfaceTemplates->printBaseInterface( 'depedencies' );
			}
		}
		
		public function depedencies_plugin_redirect_valid() {
			//delete_option('psp_depedencies_is_valid');
			//$site_url = $this->the_plugin->get_current_page_url(array());
			//header( "Location: $site_url" );
			delete_option('psp_depedencies_is_valid');
			wp_redirect( get_admin_url() . 'admin.php?page=psp' );
		}
		
		public function depedencies_plugin_redirect() {

			delete_option('psp_depedencies_do_activation_redirect');
			wp_redirect( get_admin_url() . 'admin.php?page=psp' );
		}
		
		public function verifyDepedencies() {
			$ret = array('status' => 'valid', 'msg' => '');
			ob_start();
			?>
			<div class="psp-message">
				 All of the bellow libraries must be enabled in order for our plugin to function right!
			</div>
			<input type="button" value="Re-Check Depedencies" class="psp-button blue psp-depedencies-check" style="margin-top: 10px;">
			<?php
			if ( self::$debug ) {
				$ret['status'] = 'invalid';
			}
			
			$output = ob_get_contents();
			ob_end_clean();
			$ret['msg'] = $output;
			return $ret;
		}
	}
}
