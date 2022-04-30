<?php
/**
 * AA-Team - http://www.aa-team.com
 * ===============================+
 *
 * @package		psp_AdminMenu
 * @author		Andrei Dinca
 * @version		1.0
 */
! defined( 'ABSPATH' ) and exit;

if (class_exists('psp_AdminMenu') != true) {
	class psp_AdminMenu {
		
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';

		/*
		* Store some helpers config
		*/
		public $the_plugin = null;
		private $the_menu = array();
		private $current_menu = '';
		private $ln = '';
		
		private $menu_depedencies = array();

		protected static $_instance;

		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct() {
			global $psp;
			$this->the_plugin = $psp;
			
			// update the menu tree
			$this->the_menu_tree();
			
			return $this;
		}

		/**
		* Singleton pattern
		*
		*/
		public static function getInstance() {
			if (!self::$_instance) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
		
		private function the_menu_tree() {
			if ( isset($this->the_plugin->cfg['modules']['depedencies']['folder_uri'])
				&& !empty($this->the_plugin->cfg['modules']['depedencies']['folder_uri']) ) {
				$this->menu_depedencies['depedencies'] = array( 
					'title' => esc_html__('Plugin depedencies', 'smartSEO' ),
					'url' => admin_url('admin.php?page=psp'),
					'folder_uri' => $this->the_plugin->cfg['paths']['freamwork_dir_url'],
					'menu_icon' => '<i class="' . ( $this->the_plugin->alias ) . '-icon-dashboard"></i>'
				);
				
				$this->clean_menu();			
				$this->capabilities();
				return true;
			}

			$this->the_menu['dashboard'] = array( 
				'title' => esc_html__('Dashboard', 'smartSEO' ),
				'url' => admin_url('admin.php?page=psp#dashboard'),
				'folder_uri' => $this->the_plugin->cfg['paths']['freamwork_dir_url'],
				'menu_icon' => '<i class="' . ( $this->the_plugin->alias ) . '-icon-dashboard"></i>'
			);
			
		 
			
			 
			$this->the_menu['on_page_optimization'] = array( 
				'title' => esc_html__('Settings', 'smartSEO' ),
				'url' => admin_url('admin.php?page=psp#on_page_optimization'),
				'folder_uri' => $this->the_plugin->cfg['paths']['freamwork_dir_url'],
				'menu_icon' => '<i class="psp-icon-miscellaneous"></i>',
				 
			);
			
			$this->the_menu['title_meta_format'] = array( 
				'title' => esc_html__('Title & Meta Format', 'smartSEO' ),
				'url' => admin_url('admin.php?page=psp#title_meta_format'),
				'folder_uri' => $this->the_plugin->cfg['paths']['freamwork_dir_url'],
				'menu_icon' => '<span class="psp-icon-title_meta"><span class="path1"></span><span class="path2"></span></span>',
				 
			);
			
			
			$this->the_menu['general'] = array( 
				'title' => esc_html__('Plugin Settings', 'smartSEO' ),
				'url' => '#',
				'folder_uri' => $this->the_plugin->cfg['paths']['freamwork_dir_url'],
				'menu_icon' => '<i class="psp-icon-plugin_settings"></i>',
				'submenu' => array(	
					'setup_backup' => array(
						'title' => esc_html__('Import', 'smartSEO' ),
						'url' => admin_url('admin.php?page=psp#setup_backup'),
						'folder_uri' => $this->the_plugin->cfg['modules']['setup_backup']['folder_uri'],
						'menu_icon' => '<i class="' . ( $this->the_plugin->alias ) . '-icon-setup_backup"></i>',
					),
					
					'server_status' => array(
						'title' => esc_html__('Server Status', 'smartSEO' ),
						'url' => admin_url('admin.php?page=psp_server_status'),
						'folder_uri' => $this->the_plugin->cfg['modules']['server_status']['folder_uri'],
						'menu_icon' => '<i class="' . ( $this->the_plugin->alias ) . '-icon-server_status"></i>',
					),
				)
			);
			
			$this->the_menu['collapse_menu'] = array( 
				'title' => esc_html__('Collapse menu', 'smartSEO' ),
				'url' => '#',
				'folder_uri' => $this->the_plugin->cfg['paths']['freamwork_dir_url'],
				'menu_icon' => '<i class="' . ( $this->the_plugin->alias ) . '-checks-arrow-left"></i>',
		
			);

  

			$this->clean_menu();			
			$this->capabilities();
		}
		
		public function capabilities() {
			foreach ($this->the_menu as $k=>$v) { // menu
				if ( isset($v['submenu']) && !empty($v['submenu']) ) {
					foreach ($v['submenu'] as $sk=>$sv) { // submenu
						$module = $sk;
						if ( //!in_array($module, $this->the_plugin->cfg['core-modules']) &&
						!$this->the_plugin->capabilities_user_has_module($module) ) {
							unset($this->the_menu["$k"]['submenu']["$sk"]);
						}
					}
				} else {
					$module = $k;
					if ( //!in_array($module, $this->the_plugin->cfg['core-modules']) &&
					!$this->the_plugin->capabilities_user_has_module($module) ) {
						unset($this->the_menu["$k"]);
					}
				}
			}
			
			foreach ($this->the_menu as $k=>$v) { // menu
				if ( isset($v['submenu']) && empty($v['submenu']) ) {
					unset($this->the_menu["$k"]);
				}
			}
		}

		public function clean_menu() {
			foreach ($this->the_menu as $key => $value) {
				if ( isset($value['submenu']) ) {
					foreach ($value['submenu'] as $kk2 => $vv2) {
						$kk2orig = $kk2;
						// fix to support same module multiple times in menu
						$kk2 = substr( $kk2, 0, ( ( $t = strpos($kk2, '--') )!==false ? $t : strlen($kk2) ) );
  
						if ( ( $kk2 != 'synchronization_log' )
							&& !in_array( $kk2, array_keys($this->the_plugin->cfg['activate_modules'])) ) {
							unset($this->the_menu["$key"]['submenu']["$kk2orig"]);
						}
					}
				}
			}

			foreach ($this->the_menu as $k=>$v) { // menu
				if ( isset($v['submenu']) && empty($v['submenu']) ) {
					unset($this->the_menu["$k"]);
				}
			}
		}

		public function show_menu( $pluginPage = '' ) {
			$plugin_data = psp_get_plugin_data(); //$this->the_plugin->details;
 
			$html = array();
			
			$html[] = '<aside class="' . ( $this->the_plugin->alias ) . '-sidebar">';
			$html[] = '<div class="' . ( $this->the_plugin->alias ) . '-title logo">';
			$html[] = '<img src="' . ( $this->the_plugin->cfg['paths']['freamwork_dir_url'] . 'images/logo.png' ) . '" id="' . ( $this->the_plugin->alias ) . '-full-logo" /> <span><i>V</i> ' . ( $plugin_data['version'] ) . '</span>';
			$html[] = '</div>';
			$html[] = '<img src="' . ( $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'logo-small.png' ) . '" style="display:none;" id="' . ( $this->the_plugin->alias ) . '-collapsed-logo" alt="smartSEO"/>';
			$html[] = '<div class="' . ( $this->the_plugin->alias ) . '-responsive-menu hide">Menu <i class="fa fa-bars" aria-hidden="true"></i></div>';	
			$html[] = '<nav class="' . ( $this->the_plugin->alias ) . '-nav">';
								
			if ( $pluginPage == 'depedencies' ) {
				$menu = $this->menu_depedencies;
				$this->current_menu = array(
					0 => 'depedencies',
					1 => 'depedencies'
				);
			} else {
				$menu = $this->the_menu;
			}

			//var_dump('<pre>',$this->current_menu ,'</pre>');
			//:: MENU
			$currentnav = '';
			if ( ! empty($this->current_menu) && is_array($this->current_menu) && count($this->current_menu) >= 2 ) {
				unset($this->current_menu[0]);
				$currentnav = implode('#', $this->current_menu);
			}
			$html[] = '<ul data-currentnav="' . $currentnav . '">';
			foreach ($menu as $key => $value) {

					$_id = 'psp-nav-' . $key;
					$_sect = 'psp-section-' . $key;
					$_is_active = isset($this->current_menu[0]) && ( $key == $this->current_menu[0] ) ? 'active' : '';

					$html[] = '<li id="' . $_id . '" class="' . ( $_sect . ' ' . $_is_active ) . '">';
					
				if ( $value['url'] == '#' ) {
					$value['url'] = 'javascript: void(0)';
				}
					$html[] = '<a href="' . ( $value['url'] ) . '" ' . ( $key != 'collapse_menu' ? 'data-toggle="tipsy"' : '' ) . ' title="' . $value['title'] . '">'; 
				if ( isset($value['menu_icon']) ) {
					$html[] = $value['menu_icon'];
				}
					$html[] = '<span class="psp-sidebar-menu-item-title">' . $value['title'] . '</span>';
					$html[] = '</a>';

					//:: SUB-MENU
				if ( isset($value['submenu']) ) {

					$_sect = 'psp-sub-menu';
					$_is_active = '';

					$html[] = '<ul class="' . ( $_sect . ' ' . $_is_active ) . '">';

					foreach ($value['submenu'] as $kk2 => $vv2) {
	
						$_id = 'psp-sub-nav-' . $kk2;
						$_sect = 'psp-section-' . $kk2;
						$_is_active = isset($this->current_menu[1]) && $kk2 == $this->current_menu[1] ? 'active' : '';

						$html[] = '<li id="' . $_id . '" class="' . ( $_sect . ' ' . $_is_active ) . '">';

						$html[] = '<a href="' . ( $vv2['url'] ) . '">';
						if ( isset($vv2['menu_icon']) ) {
							$html[] = $vv2['menu_icon'];
						}
						$html[] = $vv2['title'];
						$html[] = '</a>'; 

						//:: SUB-SUB-MENU
						if ( isset($vv2['submenu']) ) {
							
							$_sect = 'psp-sub-sub-menu';
							$_is_active = '';

							$html[] = '<ul class="' . ( $_sect . ' ' . $_is_active ) . '">';

							foreach ($vv2['submenu'] as $kk3 => $vv3) {

								$_id = 'psp-sub-sub-nav-' . $kk3;
								$_sect = 'psp-section-' . $kk3;
								$_is_active = isset($this->current_menu[2]) && $kk2 == $this->current_menu[2] ? 'active' : '';

								$html[] = '<li id="' . $_id . '" class="' . ( $_sect . ' ' . $_is_active ) . '">';

								$html[] = '<a href="' . ( $vv3['url'] ) . '">';
								$html[] = '<i class="psp-icon-submenu_icon"></i>';
								$html[] = $vv3['title'];
								$html[] = '</a>';

								$html[] = '</li>';
							} // end foreach

							$html[] = '</ul>';
						}

						$html[] = '</li>';
					} // end foreach

					$html[] = '</ul>';
				}

					$html[] = '</li>';
			} // end foreach
			$html[] = '</ul>';

			$html[] = '</nav>';
			$html[] = '</aside>';
			
			echo implode("\n", $html);
		}

		public function make_active( $section = '' ) {
			$this->current_menu = explode('|', $section);
			return $this;
		}
	}
}
