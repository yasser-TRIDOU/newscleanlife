<?php
/*
* Define class psp_PluginUtils
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
if (class_exists('psp_PluginUtils') != true) {
	class psp_PluginUtils {
	
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';

		/*
		* Store some helpers config
		*/
		public $the_plugin = null;
		//public $amzHelper = null;

		protected static $_instance;
		
	
		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct( $parent ) {
			$this->the_plugin = $parent;
			//$this->amzHelper = $this->the_plugin->amzHelper;
		}
		
		/**
		* Singleton pattern
		*
		* @return Singleton instance
		*/
		public static function getInstance( $parent ) {
			if (!self::$_instance) {
				self::$_instance = new self($parent);
			}
			
			return self::$_instance;
		}
	
	
		/**
		 * Plugin Data & Status & Version Updates
		 */
		public function get_plugin_data() {
			$source = file_get_contents( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '/plugin.php' );
			$tokens = token_get_all( $source );
			$data = array();
			if ( trim($tokens[1][1]) != '' ) {
				$__ = explode("\n", $tokens[1][1]);
				foreach ($__ as $key => $value) {
					$___ = explode(': ', $value);
					if ( count($___) == 2 ) {
						$data[trim(strtolower(str_replace(' ', '_', $___[0])))] = trim($___[1]);
					}
				}               
			}
			
			// For another way to implement it:
			//		see wp-admin/includes/update.php function get_plugin_data
			//		see wp-includes/functions.php function get_file_data
			return $data;  
		}

		public function plugin_row_meta_filter( $links, $file ) {
			$plugin_folder = $this->the_plugin->plugin_details['folder_index'];
   
			if ( $file == $plugin_folder ) {
				$row_meta = array(
					'docs'    => '<a href="' . esc_url( apply_filters( 'psp_docs_url', $this->the_plugin->plugin_row_meta('docs_url') ) ) . '" title="' . esc_attr( esc_html__('View Documentation', 'smartSEO' ) ) . '">' . esc_html__('Docs', 'smartSEO' ) . '</a>',
					'support' => '<a href="' . esc_url( apply_filters( 'psp_support_url', $this->the_plugin->plugin_row_meta('support_url') ) ) . '" title="' . esc_attr( esc_html__('Visit Customer Support Forum', 'smartSEO' ) ) . '">' . esc_html__('Support Forum', 'smartSEO' ) . '</a>',
				);
	
				return array_merge( $links, $row_meta );
			}
	
			return (array) $links;
		}
	}
}
