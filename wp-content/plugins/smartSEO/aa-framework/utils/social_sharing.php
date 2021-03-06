<?php
/*
* Define class psp_SocialSharing
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
if (class_exists('psp_SocialSharing') != true) {
	class psp_SocialSharing {
	
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';

		/*
		* Store some helpers config
		*/
		public $the_plugin = null;
		private $plugin_settings = array();
		
		protected $module_folder = '';
		protected $module_folder_path = '';

		protected static $_instance;

		private $socialNetworks = array();
		private $toolbarTypes = array();
		private $pageTypes = array();
		private $shareInfo;
		
		private static $isTest = false;
		
		public static $utils = array();


		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct( $parent ) {
			$this->the_plugin = $parent;
			$this->plugin_settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_socialsharing' );
			
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/Social_Stats/';
			$this->module_folder_path = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/Social_Stats/';

			// get utils
			self::$utils = $this->the_plugin->get_client_utils();
				
			$this->socialNetworks();
			
			$this->init();
		}
		
		/**
		 * Frontend load
		 *
		 */
		public function init() {
			$isEnabled = $this->is_toolbar_enabled();

			// at least 1 toolbar is enabled!
			if ( !is_admin() && $isEnabled['isEnabled'] ) {
				
				add_action( 'wp_enqueue_scripts', array($this, 'the_styles') );
				add_action( 'wp_enqueue_scripts', array($this, 'the_scripts') );
				
				add_action( 'wp_head', array($this, 'the_header') );
				add_action( 'wp_footer', array($this, 'the_footer') );
			}
		}
		
		public function the_styles() {
			if ( !wp_style_is('psp_socialshare_css') ) {
				wp_enqueue_style( 'psp_socialshare_css' , $this->module_folder . 'social_sharing.css' );
			}
		}
		public function the_scripts() {
			if ( !wp_script_is('jquery') ) { // first, check to see if it is already loaded
				//wp_enqueue_script( 'jquery' , 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' );
				wp_enqueue_script( 'jquery' , $this->cfg['paths']['freamwork_dir_url'] . 'js/jquery/jquery-3.6.0.min.js' );
			}
			if ( !wp_script_is('psp_socialshare_js') ) {
				wp_enqueue_script( 'psp_socialshare_js' , $this->module_folder . 'social_sharing.js', array(
					'jquery'
				) );
				wp_localize_script( 'psp_socialshare_js', 'pspSocialSharing_ajaxurl', admin_url('admin-ajax.php') );
			}
		}
		public function the_header() {
			$isEnabled = $this->is_toolbar_enabled();

			// the content toolbars inserted in the post content
			if ( $isEnabled['isContent'] ) {
				add_filter( 'the_content', array($this, 'update_the_content'), 15 );
			}
			return ;
		}
		public function the_footer() {
			$isEnabled = $this->is_toolbar_enabled();

			// the floating toolbar inserted in wp footer
			if ( $isEnabled['isFloating'] && $this->is_page_allowed('floating') ) {
				$theToolbar = $this->getToolbar('floating');
				if ( !empty($theToolbar) ) {
					echo $theToolbar;
				}
			}
			
			// build html with 3 toolbars options which will be read and executed in javascript file!
			echo $this->setToolbarsOptions();
			
			echo $this->setToolbarsBackground();
			return ;
		}
		public function update_the_content( $content) {
			$isEnabled = $this->is_toolbar_enabled();

			// horizontal content toolbar
			if ( $isEnabled['content_horizontal'] && $this->is_page_allowed('content_horizontal') ) {
				$content = $this->getToolbar('content_horizontal', $content);
			}
			
			// vertical content toolbar - ( after horizontal toolbar - so the top markes is set right!)
			if ( $isEnabled['content_vertical'] && $this->is_page_allowed('content_vertical') ) {
				$content = $this->getToolbar('content_vertical', $content);
			}
			
			return $content;
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
		* Social Sharing
		*/
		public function socialNetworks() {
			
			$this->toolbarTypes = array(
				'none'				=>esc_html__('None', 'smartSEO'),
				'floating'			=>esc_html__('Floating Toolbar', 'smartSEO'),
				'content_horizontal'		=>esc_html__('Content Top / Bottom Toolbar', 'smartSEO'),
				'content_vertical'		=>esc_html__('Content Left / Right Toolbar', 'smartSEO')
			);
			
			$this->pageTypes = array(
				'home' 			=>esc_html__('Homepage', 'smartSEO'),
				'front_page' 	=>esc_html__('Posts Front Page', 'smartSEO'),
				'single' 		=>esc_html__('Posts', 'smartSEO'),
				'page' 			=>esc_html__('Pages', 'smartSEO'),
				'category' 		=>esc_html__('Category Pages', 'smartSEO'),
				'tag' 			=>esc_html__('Tag Pages', 'smartSEO'),
				'archive' 		=>esc_html__('Archive Pages', 'smartSEO')
			);

			$this->socialNetworks = array(
				//'more' 		=> array('title' =>esc_html__('More', 'smartSEO')),
				'print' 		=> array('title' =>esc_html__('Print', 'smartSEO')),
				'email' 		=> array('title' =>esc_html__('Email', 'smartSEO')),
				'facebook' 		=> array('title' =>esc_html__('Facebook', 'smartSEO')),
				'plusone' 		=> array('title' =>esc_html__('Plusone', 'smartSEO')),
				'linkedin' 		=> array('title' =>esc_html__('Linkedin', 'smartSEO')),
				'stumbleupon' 	=> array('title' =>esc_html__('Stumble Upon', 'smartSEO')),
				'pinterest' 	=> array('title' =>esc_html__('Pinterest', 'smartSEO')),
				'buffer' 		=> array('title' =>esc_html__('Buffer', 'smartSEO')), // @js errors
				'twitter' 	=> array('title' =>esc_html__('Twitter', 'smartSEO')),
				'flattr' 		=> array('title' =>esc_html__('Flattr', 'smartSEO')),
				'reddit' 		=> array('title' =>esc_html__('Reddit', 'smartSEO')),
				'digg' 		=> array('title' =>esc_html__('Digg', 'smartSEO')),
				'xing' 		=> array('title' =>esc_html__('Xing', 'smartSEO')),
				'tumblr' 		=> array('title' =>esc_html__('Tumblr', 'smartSEO')),

				//2017-june not working anymore
				//'delicious' 	=> array('title' =>esc_html__('Delicious', 'smartSEO')),
			);
		}
		
		
		/**
		 * Admin
		 *
		 */

		public function set_toolbar_options( $defaults = array(), $pms = array() ) {
			if ( !is_array($defaults) ) {
				$defaults = array();
			}

			extract($pms);

			$toolbarTitle = $this->toolbarTypes["$toolbar"];

			$options = array(
				array(
					/* define the form_sizes  box */
					'socialsharing' => array(
						'title' 		=> $toolbarTitle,
						'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
						'header' 	=> false, // true|false
						'toggler' 	=> false, // true|false
						'buttons' 	=> false, // true|false
						'style' 		=> 'panel', // panel|panel-widget
						
						// create the box elements array
						'elements'	=> array(
							$toolbar . '-enabled' => array(
								'type' 		=> 'select',
								'std' 		=> 'no',
								'size' 		=> 'large',
								'force_width'  => '120',
								'title' 		=>esc_html__('Enabled:', 'smartSEO'),
								'desc' 		=> 'choose yes if you want to enable this toolbar type',
								'options'	=> array(
									'no'			=>esc_html__('No', 'smartSEO'),
									'yes'			=>esc_html__('Yes', 'smartSEO')
								)
							)

							/*,$toolbar.'-contact' => array(
								'type' 		=> 'html',
								'html' 		=> $this->set_toolbar_contact( $toolbar, $defaults )
							)*/

							,$toolbar . '-design' => array(
								'type' 		=> 'html',
								'html' 		=> $this->set_toolbar_optdesign( $toolbar, $defaults )
							)

							,$toolbar . '-position' => array(
								'type' 		=> 'html',
								'html' 		=> $this->set_toolbar_position( $toolbar . '-position', isset($defaults[$toolbar . '-position']) ? $defaults[$toolbar . '-position'] : array() )
							)

							,$toolbar . '-margin' => array(
								'type' 		=> 'html',
								'html' 		=> $this->set_toolbar_margin( $toolbar . '-margin', isset($defaults[$toolbar . '-margin']) ? $defaults[$toolbar . '-margin'] : array() )
							)

							,$toolbar . '-pages' 	=> array(
								'type' 		=> 'multiselect',
								'std' 		=> array('homepage', 'post'),
								'size' 		=> 'small',
								'force_width'  => '250',
								'title' 		=>esc_html__('Toolbar showing areas:', 'smartSEO'),
								'desc' 		=>esc_html__('areas where you want the social share toolbar to appear', 'smartSEO'),
								'options' 	=> $this->pageTypes
							)
							
							,$toolbar . '-exclude-categ' 	=> array(
								'type' 		=> 'multiselect',
								'std' 		=> array(),
								'size' 		=> 'small',
								'force_width'  => '250',
								'title' 		=>esc_html__('Exclude toolbar on categories:', 'smartSEO'),
								'desc' 		=>esc_html__('categories where you don\'t want the social share toolbar to appear (also all posts belonging to these categories will not have the toolbar)', 'smartSEO'),
								'options' 	=> $this->tbExcludeCategs_opt()
							)
							
							,$toolbar . '-exclude-post' => array(
								'type' 		=> 'html',
								'html' 		=> $this->set_toolbar_exclude( $toolbar . '-exclude-post', isset($defaults[$toolbar . '-exclude-post']) ? $defaults[$toolbar . '-exclude-post'] : array() )
							)

							,$toolbar . '-opt' => array(
								'type' 		=> 'html',
								'html' 		=> $this->set_toolbar_opt( $toolbar, $defaults )
							)
							
							,$toolbar . '-buttons' => array(
								'type' 		=> 'html',
								'html' 		=> $this->set_toolbar_buttons( $toolbar, $defaults )
							)

						)
					)
				)
			);

			// setup the default value base on array with defaults
			if (count($defaults) > 0) {
				foreach ($options as $option) {
					foreach ($option as $box_id => $box) {
						//if(in_array($box_id, array_keys($defaults))){
						foreach ($box['elements'] as $elm_id => $element) {
							if (isset($defaults[$elm_id])) {
								$option[$box_id]['elements'][$elm_id]['std'] = $defaults[$elm_id];
							}
						}
						//}
					}
				}

				// than update the options for returning
				$options = array( $option );
			}

			return $options;
		}
		
		public function build_toolbar_options( $pms = array()) {
			
			extract($pms);
			
			// load the settings template class
			require_once( $this->the_plugin->cfg['paths']['freamwork_dir_path'] . 'settings-template.class.php' );
			
			// Initalize the your psp_aaInterfaceTemplates
			$psp_aaInterfaceTemplates = new psp_aaInterfaceTemplates($this->the_plugin->cfg);
			
			$options = array();
			$options = $this->plugin_settings;
			
			// then build the html, and return it as string
			$html_options = $psp_aaInterfaceTemplates->bildThePage( $this->set_toolbar_options( $options, $pms ) , $this->the_plugin->alias, array(), false);
			return $html_options;
		}
		
		/**
		 * Custom methods
		 */
		private function set_toolbar_position( $field_name, $db_meta_name ) {
			ob_start();
			
			require($this->module_folder_path . 'lists.inc.php');
			?>
<div class="psp-panel-body panel-body psp-form-row">
	<label><?php esc_html_e('Position: ', 'smartSEO'); ?></label>
	<div class="psp-form-item large">
	<span class="formNote">&nbsp;</span>
			<?php
			foreach ($psp_socialsharing_position as $key => $value) {

				$__toolbar = str_replace('-position', '', $field_name);
				//if ( $__toolbar == 'content_horizontal' && $key == 'vertical' ) continue 1;

				$val = '0';
				if ( isset($db_meta_name[$key]) && isset($db_meta_name[$key]) ) {
					$val =$db_meta_name[$key];
				}
				?>
		<label for="<?php echo $field_name . '[' . $key . ']'; ?>" style="display:inline;float:none;"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</label>
		&nbsp;
		<select id="<?php echo $field_name . '[' . $key . ']'; ?>" name="<?php echo $field_name . '[' . $key . ']'; ?>" style="width:120px;">
				<?php
				foreach ($value as $kk => $vv) {

					if ( $__toolbar == 'content_horizontal' && $key == 'vertical' && $kk == 'center' ) {
						continue 1;
					}
					if ( $__toolbar == 'content_vertical' && $key == 'horizontal' && $kk == 'center' ) {
						continue 1;
					}
					echo '<option value="' . ( $kk ) . '" ' . ( $val == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
				} 
				?>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
			} 
			?>
	</div>
</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
		
		private function set_toolbar_margin( $field_name, $db_meta_name ) {
			ob_start();
			
			require($this->module_folder_path . 'lists.inc.php');
			?>
<div class="psp-panel-body panel-body psp-form-row">
	<label><?php esc_html_e('Margin: ', 'smartSEO'); ?></label>
	<div class="psp-form-item large">
	<span class="formNote">&nbsp;</span>
			<?php
			foreach ($psp_socialsharing_margin as $key => $value) {
		
				$__toolbar = str_replace('-margin', '', $field_name);
				//if ( $__toolbar == 'content_horizontal' && $key == 'vertical' ) continue 1;

				$val = '';
				if ( isset($db_meta_name[$key]) && isset($db_meta_name[$key]) ) {
					$val =$db_meta_name[$key];
				}
				?>
		<label for="<?php echo $field_name . '[' . $key . ']'; ?>" style="display:inline;float:none;"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</label>
		&nbsp;
		<input type='text' class='' id='<?php echo $field_name . '[' . $key . ']'; ?>' name='<?php echo $field_name . '[' . $key . ']'; ?>' value='<?php echo $val; ?>' style="width:100px;">&nbsp;<?php esc_html_e('px', 'smartSEO'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
			} 
			?>
	</div>
</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
		
		private function set_toolbar_opt( $field_name, $db_meta_name ) {
			ob_start();
			
			require($this->module_folder_path . 'lists.inc.php');
			
			$__optArr = array( 
				'btnsize' 		=> $psp_socialsharing_opt['btnsize'],
				'viewcount' 	=> $psp_socialsharing_opt['viewcount'],
				'withmore'		=> $psp_socialsharing_opt['withmore']
			);
			$__optArrDetails = array(
				'btnsize' 	=> array('title' =>esc_html__('Buttons size', 'smartSEO')),
				'viewcount' 	=> array('title' =>esc_html__('View count', 'smartSEO')),
				'withmore'		=> array('title' =>esc_html__('With More button', 'smartSEO')),
			);
			?>
<div class="psp-panel-body panel-body psp-form-row">
	<label><?php esc_html_e('Buttons options: ', 'smartSEO'); ?></label>
	<div class="psp-form-item large">
	<span class="formNote">&nbsp;</span>
			<?php
			foreach ($__optArr as $key => $value) {
		
				$__theKey = $field_name . '-' . $key;

				$val = '';
				if ( isset($db_meta_name[$__theKey]) && isset($db_meta_name[$__theKey]) ) {
					$val =$db_meta_name[$__theKey];
				}
				?>
		<label for="<?php echo $__theKey; ?>" style="display:inline;float:none;"><?php echo ucfirst(str_replace('_', ' ', $__optArrDetails[$key]['title'])); ?>:</label>
		&nbsp;
		<select id="<?php echo $__theKey; ?>" name="<?php echo $__theKey; ?>" style="width:120px;">
				<?php
				foreach ($value as $kk => $vv) {
					echo '<option value="' . ( $kk ) . '" ' . ( $val == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
				} 
				?>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
			} 
			?>
	</div>
</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		private function set_toolbar_exclude( $field_name, $db_meta_name ) {
			ob_start();
			
			require($this->module_folder_path . 'lists.inc.php');
			?>
<div class="psp-panel-body panel-body psp-form-row">
	<label><?php esc_html_e('Include/Exclude toolbar on Post, Pages: ', 'smartSEO'); ?></label>
	<div class="psp-form-item large">
	<span class="formNote">&nbsp;</span>
			<?php
			foreach ($psp_socialsharing_exclude as $key => $value) {
		
				$__toolbar = str_replace('-exclude-post', '', $field_name);

				$val = '';
				if ( isset($db_meta_name[$key]) && isset($db_meta_name[$key]) ) {
					$val =$db_meta_name[$key];
				}
				?>
				<?php /*<label for="<?php echo $field_name.'['.$key.']'; ?>" style="display:inline-block;"><?php echo $value['title'];?>:</label>*/ ?>
		<div class="psp-form-item large" style="display:inline-block; width:49%; margin-left:0;">
			<span class="formNote" style="width: 100%;"><?php echo $value['desc']; ?></span>
			<textarea class='' id='<?php echo $field_name . '[' . $key . ']'; ?>' name='<?php echo $field_name . '[' . $key . ']'; ?>' style=""><?php echo $val; ?></textarea>
		</div>
				<?php
			} 
			?>
	</div>
</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		private function set_toolbar_optdesign( $field_name, $db_meta_name ) {
			ob_start();
			
			require($this->module_folder_path . 'lists.inc.php');
			
			$__optArr = $psp_socialsharing_design;
			
			$__optArrDetails = array(
				'make_floating' 	=> array('title' =>esc_html__('Make it floating', 'smartSEO')),
				'background_color'	=> array('title' =>esc_html__('Background color', 'smartSEO')),
				'floating_beyond_content'	=> array('title' =>esc_html__('Floating beyond the end of the post content', 'smartSEO'))
			);
			?>
<div class="psp-panel-body panel-body psp-form-row">
	<label><?php esc_html_e('Design: ', 'smartSEO'); ?></label>
	<div class="psp-form-item large">
	<span class="formNote">&nbsp;</span>
			<?php
			foreach ($__optArr as $key => $value) {
		
				$__theKey = $field_name . '-' . $key;
		
				if ($field_name != 'content_vertical' && in_array($key, array('make_floating', 'floating_beyond_content')) ) {
					continue 1;
				}

				$val = '';
				if ( isset($db_meta_name[$__theKey]) && isset($db_meta_name[$__theKey]) ) {
					$val =$db_meta_name[$__theKey];
				}
		
				if ( $key == 'background_color' ) {
					?>
		<label for="<?php echo $__theKey; ?>" style="display:inline;float:none;"><?php echo $__optArrDetails[$key]['title']; ?>:</label>
		&nbsp;
		<input type='text' class='socialshare-color-picker' id='<?php echo $__theKey; ?>' name='<?php echo $__theKey; ?>' value='<?php echo $val; ?>' data-background_color="<?php echo $val; ?>" style="width:100px;">&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
				} else {
					?>
		<label for="<?php echo $__theKey; ?>" style="display:inline;float:none;"><?php echo $__optArrDetails[$key]['title']; ?>:</label>
		&nbsp;
		<select id="<?php echo $__theKey; ?>" name="<?php echo $__theKey; ?>" style="width:120px;">
					<?php
					foreach ($value as $kk => $vv) {
						echo '<option value="' . ( $kk ) . '" ' . ( $val == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
					} 
					?>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
				}
			} 
			?>
	</div>
</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
		
		private function set_toolbar_buttons( $field_name, $db_meta_name ) {
			ob_start();
			
			$__theKey = $field_name . '-' . 'buttons';
			$selectedBtn = array();
			if ( isset($db_meta_name[$__theKey]) && !empty($db_meta_name[$__theKey]) ) {
				$selectedBtn = explode(',', $db_meta_name[$__theKey]);
			}
			$selectedBtn = (array) $selectedBtn;

			$availableBtn = array_keys( $this->socialNetworks );
			$selectedBtn = array_intersect($availableBtn, $selectedBtn);
			$availableBtn = array_diff( $availableBtn, $selectedBtn );
			?>
<div class="psp-panel-body panel-body psp-form-row">
	<label><?php esc_html_e('Toolbar buttons: ', 'smartSEO'); ?></label>
	<div class="psp-form-item large">
	<span class="formNote">&nbsp;</span>

	<input type="hidden" id="btn-selected-list" name="<?php echo $__theKey; ?>" value="" />
	
	<div class="btn-wrapper">
		<span class="title"><?php esc_html_e('Available buttons', 'smartSEO'); ?></span>
		<ul class="btn-available btn-sortable">
			<?php
			if ( !empty($availableBtn) ) {
				foreach ( $availableBtn as $k => $v ) {
					?>
				<li class="block social-btn <?php echo $v; ?>" data-btn="<?php echo $v; ?>"><a class="icon"><span class="title"><?php echo $this->socialNetworks["$v"]['title']; ?></span></a><span class="delete"><?php esc_html_e('x', 'smartSEO'); ?></span></li>
					<?php	
				}
			}
			?>
		</ul>
	</div>
	
	<div class="btn-wrapper">
		<span class="title"><?php esc_html_e('Selected buttons', 'smartSEO'); ?></span>
		<ul class="btn-selected btn-sortable">
			<?php
			if ( !empty($selectedBtn) ) {
				foreach ( $selectedBtn as $k => $v ) {
					?>
				<li class="block social-btn <?php echo $v; ?>" data-btn="<?php echo $v; ?>"><a class="icon"><span class="title"><?php echo $this->socialNetworks["$v"]['title']; ?></span></a><span class="delete"><?php esc_html_e('x', 'smartSEO'); ?></span></li>
					<?php	
				}
			}
			?>
		</ul>
	</div>

	</div>
</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		private function set_toolbar_contact( $field_name, $db_meta_name ) {
			ob_start();

			require($this->module_folder_path . 'lists.inc.php');

			$__optArr = $psp_socialsharing_opt['contact'];
			?>
<div class="psp-panel-body panel-body psp-form-row">
	<label><?php esc_html_e('Info details: ', 'smartSEO'); ?></label>
	<div class="psp-form-item large">
	<span class="formNote">&nbsp;</span>
			<?php
			foreach ($__optArr as $key => $value) {
		
				$__theKey = $field_name . '-' . $key;
		
				$val = '';
				if ( isset($value['std']) && !empty($value['std']) ) {
					$val = $value['std'];
				}
				if ( isset($db_meta_name[$__theKey]) && isset($db_meta_name[$__theKey]) ) {
					$val =$db_meta_name[$__theKey];
				}
				?>
		<label for="<?php echo $__theKey; ?>" style="display:inline;float:none;"><?php echo $value['title']; ?>:</label>
		&nbsp;
		<input type='text' class='' id='<?php echo $__theKey; ?>' name='<?php echo $__theKey; ?>' value='<?php echo $val; ?>' style="width:100px;">&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
			}
			?>
	</div>
</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
		
		
		/**
		 * Frontend methods
		 */
		// toolbar is enabled
		private function is_toolbar_enabled() {
			$opt = $this->plugin_settings;
			
			$ret = array(
				'floating'				=> false,
				'content_horizontal'	=> false,
				'content_vertical'		=> false,
				
				'isFloating'			=> false,
				'isContent'				=> false,
				'isEnabled'				=> false
			);

			extract(self::$utils);

			// per Mobile
			if ( isset($this->plugin_settings['users_devices'])
				&& $this->plugin_settings['users_devices'] != 'both' ) {

				if ( $this->plugin_settings['users_devices'] == 'mobile' ) {
					if ( !$isMobile ) {
						return $ret;
					}

				} else if ( $this->plugin_settings['users_devices'] == 'desktop' ) {
					if ( $isMobile ) {
						return $ret;
					}
				}
			}
			
			$isEnabled = false;
			$isFloating = false;
			$isContent = false;
			foreach ($this->toolbarTypes as $k=>$v) {
				$status = $this->get_property( $k . '-enabled', 'string' );
				if ( $status=='yes' ) {

					$ret["$k"] = true;
					if ( $k == 'floating' ) {
						$isFloating = true;
					}
					if ( in_array($k, array('content_horizontal', 'content_vertical')) ) {
						$isContent = true;
					}
					$isEnabled = true;
				}
			}
			
			return array_merge($ret, array(
				'isEnabled'			=> $isEnabled,
				'isFloating'		=> $isFloating,
				'isContent'			=> $isContent
			));
		}

		// toolbar is allowed on page type
		private function is_page_allowed( $toolbarType = 'floating' ) {
			if ( is_admin() || is_feed() ) {
				return false;
			}

			$allowedPages = $this->get_property( $toolbarType . '-pages', 'array' );
			
			// loop through all page types!
			if ( is_home() ) {
				return ( in_array('home', $allowedPages) ? true : false );
			} else if ( is_front_page() ) {
				return ( in_array('front_page', $allowedPages) ? true : false );
			} else if ( is_single() ) {
				return ( in_array('single', $allowedPages) && !$this->is_exclude_item($toolbarType) ? true : false );
			} else if ( is_page() ) {
				return ( in_array('page', $allowedPages) && !$this->is_exclude_item($toolbarType) ? true : false );
			} else if ( is_attachment() ) {
				return ( in_array('attachment', $allowedPages) ? true : false );
			} else if ( is_category() ) {
				return ( in_array('category', $allowedPages) && !$this->is_exclude_item($toolbarType) ? true : false );
			} else if ( is_tag() ) {
				return ( in_array('tag', $allowedPages) ? true : false );
			} else if ( is_tax() ) {
				return ( in_array('tax', $allowedPages) ? true : false );
			} else if ( is_archive() ) {
				return ( in_array('archive', $allowedPages) ? true : false );
			} else if ( is_author() ) {
				return ( in_array('author', $allowedPages) ? true : false );
			} else if ( is_search() ) {
				return ( in_array('search', $allowedPages) ? true : false );
			} else if ( is_404() ) {
				return ( in_array('404', $allowedPages) ? true : false );
			}
			return false;
		}
		
		// get toolbar
		private function getToolbar( $toolbarType, $content = false) {

			global $post;

			$toolbar = $this->buildToolbar($toolbarType, $post);
			if ( empty($toolbar) ) {
				return ( $content!==false ? $content : '' );
			}

			$ret = $toolbar;
			if ( $toolbarType == 'floating' ) {
				return $ret;
			}
			if ( $content!==false ) {
				// horizontal toolbar - chose position (top - above content or bottom - bellow content)
				if ( $toolbarType == 'content_horizontal' ) {
					$position = $this->get_property( $toolbarType . '-position', 'array' );
					$vertical = isset($position['vertical']) && in_array($position['vertical'], array('top', 'bottom')) ? $position['vertical'] : 'top';

					if ( $vertical == 'top' ) {
						$ret = $toolbar . $content;
					}
					if ( $vertical == 'bottom' ) {
						$ret = $content . $toolbar;
					}
				}
				// vertical toolbar - always bellow content - moved by js
				else if ( $toolbarType == 'content_vertical' ) {
					$__mark_top = '<span class="psp-social-content-mark-top"></span>';
					$__mark_bottom = '<span class="psp-social-content-mark-bottom"></span>';
					$ret = ( $__mark_top . $content . $__mark_bottom ) . $toolbar;
				}
			}
			return $ret;
		}
		
		// build toolbar!
		private function buildToolbar( $toolbarType, $post = null) {
			$__btnUrl = $this->module_folder;
			
			$post_id = 0;
			if ( !is_null($post) && is_object($post) && isset($post->ID) ) {
				$post_id = $post->ID;
			}
	
			if ( $toolbarType == 'floating' ) {
				global $wp_query;
				$post = $wp_query->get_queried_object();
			}
			
			$this->shareInfo = $this->getPostInfo($post, $toolbarType);

			$cssExtra = array();
			$__params = ' data-itemid="' . $post_id . '" data-url="' . $this->shareInfo->url . '" ';
			switch ($toolbarType) {
				case 'floating':
					$__tbType = 'box-floating';
					break;
					
				case 'content_horizontal':
					$__tbType = 'box-panel';
					break;
					
				case 'content_vertical':
					$__tbType = 'box-panel-vertical';
					break;
			}
			
			if ( $this->get_property( $toolbarType . '-viewcount', 'string', 'no') == 'yes' ) {
				$cssExtra[] = 'viewcount';
			}
			if ( $this->get_property( $toolbarType . '-btnsize', 'string', 'normal') == 'large' ) {
				$cssExtra[] = 'large';
			}
			
			if ( self::$isTest ) {
				$buttons = '
					<div class="social-btn"><img src="' . $__btnUrl . 'buttons-test/btn_1.png" width="65" height="23" /></div>
					<div class="social-btn"><img src="' . $__btnUrl . 'buttons-test/btn_2.png" width="59" height="23" /></div>
					<div class="social-btn"><img src="' . $__btnUrl . 'buttons-test/btn_3.png" width="59" height="22" /></div>
					<div class="social-btn"><img src="' . $__btnUrl . 'buttons-test/btn_4.png" width="59" height="22" /></div>
					<div class="social-btn"><img src="' . $__btnUrl . 'buttons-test/btn_5.png" width="59" height="19" /></div>
				';
			} else {
				$buttons = $this->getButtons($toolbarType, $post);
				if ( empty($buttons) ) {
					return '';
				}
				$buttons_list = implode('', $buttons);
			}

			/*$buttonsList = $this->get_property( $toolbarType . '-buttons', 'string' );
			$toolbarPms = array(
				'type'		=> $toolbarType,
				'itemid'	=> $post_id,
				'position' 	=> $this->get_property( $toolbarType . '-position', 'array', array(
					'horizontal' 	=> 'left',
						'vertical' 	=> 'top'
					) ),
				'margin' 	=> $this->get_property( $toolbarType . '-margin', 'array', array(
					'horizontal' 	=> 0,
						'vertical' 	=> 0
					) ),
					'viewcount'	=> $this->get_property( $toolbarType . '-viewcount', 'string', 'no'),
					'btnsize'	=> $this->get_property( $toolbarType . '-btnsize', 'string', 'normal'),
					'buttons'	=> $buttonsList
			);
			$toolbarPmsJson = json_encode($toolbarPms);*/

			$ret = '
					<!-- start/ Premium SEO pack - Wordpress Plugin / Social Sharing Toolbar -->
					<div class="psp-sshare-wrapper ' . $__tbType . ( !empty($cssExtra) ? ' ' . implode(' ', $cssExtra) : '' ) . '" ' . $__params . '>
						<div class="psp-socialbox-content">'
						. $buttons_list
						. '</div>
					</div>'
					/*. '<script type="text/javascript">
					jQuery(document).ready(function() {
					//<![CDATA[
						// pspSocialSharing.setAjaxUrl( "' . admin_url('admin-ajax.php') . '" );
						var pspSocialSharing_pms = ' . $toolbarPmsJson . ';
						pspSocialSharing.build_toolbar( pspSocialSharing_pms );
					//]]>
					});
					</script>'*/
					. '<!-- end/ Premium SEO pack - Wordpress Plugin / Social Sharing Toolbar -->
			';
			return $ret;
		}
		
		private function getButtons( $toolbarType, $post = null) {
			$ret = array();
			$buttonsList = $this->get_property( $toolbarType . '-buttons', 'string' );
			$buttonsList = (array) explode(',', $buttonsList);
			if ( empty($buttonsList) ) {
				return $ret;
			}
			
			// social sharing module
			$pms = array(
				'toolbarType'		=> $toolbarType,
				'post'				=> $post
			);
			require_once( 'social_sharing_btn.php' );
			$sharingButtons = new psp_SocialSharingButtons( $this->the_plugin, $pms );
			
			$shareInfo = $this->shareInfo;
			
			if ( $toolbarType=='floating' ) {
				$sharingButtons->setPostInfo( null, $shareInfo );
			} else {
				$sharingButtons->setPostInfo( $post, $shareInfo );
			}
			
			// more buttons list
			$buttons_more = array_keys( $this->socialNetworks );
			$buttons_more = array_diff( $buttons_more, $buttonsList );
			// has more button
			$withmore = $this->get_property( $toolbarType . '-withmore', 'string' );
			$withmore = ( $withmore == 'yes' && count($buttons_more) > 0 ) ? true : false;
			
			// built more buttons list
			$btnMore = array();
			if ( $withmore ) {
				foreach ($buttons_more as $k=>$v) {
					$__func = $v . '_btn';
					if ( is_callable(array($sharingButtons, $__func), true) && method_exists($sharingButtons, $__func) ) {
						$btnMore[] = $sharingButtons->$__func();
					}
				} }
  
			$btn = array();
			if ( $withmore && !empty($btnMore) ) {
				$buttonsList[] = 'more';
			}
			foreach ($buttonsList as $k=>$v) {
				$__func = $v . '_btn';
				if ( is_callable(array($sharingButtons, $__func), true) && method_exists($sharingButtons, $__func) ) {
					if ( $v == 'more' ) {
						$btn[] = $sharingButtons->$__func( $btnMore );
					} else {
						$btn[] = $sharingButtons->$__func();
					}
				}
			}
			return $btn;
		}
		
		private function setToolbarsOptions() {
			$isEnabled = $this->is_toolbar_enabled();
			
			$tblList = array();
			if ( $isEnabled['floating'] ) {
				$tblList['floating'] = array();
			}
			if ( $isEnabled['content_horizontal'] ) {
				$tblList['content_horizontal'] = array();
			}
			if ( $isEnabled['content_vertical'] ) {
				$tblList['content_vertical'] = array();
			}
			
			if ( !empty($tblList) ) {
				foreach ($tblList as $k=>$v) {

					$toolbarType = $k;
					$buttonsList = $this->get_property( $toolbarType . '-buttons', 'string' );
					$toolbarPms = array(
						'currentToolbar'	=> '',
						'type'			=> $toolbarType,
						'itemid'		=> 0,
						'position' 		=> $this->get_property( $toolbarType . '-position', 'array', array(
							'horizontal' 		=> 'left',
							'vertical' 			=> 'top'
							) 
						),
						'margin' 		=> $this->get_property( $toolbarType . '-margin', 'array', array(
							'horizontal' 		=> 0,
							'vertical' 		=> 0
							)
						),
						'viewcount'		=> $this->get_property( $toolbarType . '-viewcount', 'string', 'no'),
						'btnsize'		=> $this->get_property( $toolbarType . '-btnsize', 'string', 'normal'),
						'buttons'		=> $buttonsList
					);
					if ( $toolbarType == 'content_vertical' ) {
						$toolbarPms['make_floating'] = $this->get_property( $toolbarType . '-make_floating', 'string', 'no');
						$toolbarPms['floating_beyond_content'] = $this->get_property( $toolbarType . '-floating_beyond_content', 'string', 'no');
					}
					$toolbarPms['is_admin_bar_showing'] = is_admin_bar_showing() ? 'yes' : 'no';
					$tblList["$toolbarType"] = $toolbarPms;
				}
			}
			$tblList = json_encode($tblList);
			$tblList = htmlentities($tblList);
			
			return '<div id="psp-sshare-toolbars-options" style="display: none;" data-options="' . $tblList . '"></div>';
		}
		
		public function getPostInfo( $post, $toolbarType = '') {

			$isPremium = false;
			if ( $this->the_plugin->is_plugin_active( 'psp' ) ) {
				$__moduleIsActive = get_option('psp_module_title_meta_format');
				if ( isset($__moduleIsActive) && $__moduleIsActive=='true' ) {
					$isPremium = true;
				}
			}
		
			if ( !$isPremium ) {
				$urlroot = home_url(); // get_bloginfo('url');

				if ( is_singular() || $toolbarType!='floating' ) {
					$post_id = $post->ID;
					$url = get_permalink($post->ID);
					$title = get_the_title($post->ID);
				} else if ( is_category() || is_tag() || is_tax() ) {
					$post_id = $post->term_id;
				}
				if ( is_home() || is_front_page() ) {
					$url = home_url( '/' );
				}
				
				if ( !isset($url) || empty($url) ) {
					$url = $this->the_plugin->get_current_page_url(array());
				}
				if ( !isset($title) || empty($title) ) {
					$title = wp_title('', false);
					$title = trim($title);
				}
			
				$shareInfo = (object) array(
					'urlroot'		=> $urlroot,
					'url'			=> $url,
					'title'			=> $title
				);
				return $shareInfo;
			}
 
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/title_meta_format/init.php');
			$info = new psp_TitleMetaFormat();

			$info->setPostInfo( $post );
			// $infoFb = new pspSocialTags(); // facebook
			// $infoTw = new pspSocialTwitterCards(); // twitter cards
			
			$shareInfo = (object) array(
				'info'			=> $info,
				'infoFb'		=> isset($infoFb) ? $infoFb : array(),
				'infoTw'		=> isset($infoTw) ? $infoTw : array()
			);

			$urlroot = home_url(); //get_bloginfo('url');
			$url = $shareInfo->info->get_the_url();
			$title = $shareInfo->info->get_the_title();

			if ( $toolbarType=='floating' ) {
				$url = $shareInfo->info->the_url();
				$title = $shareInfo->info->the_title('');
			}

			// $info = $shareInfo->infoFb->opengraph_tags(true);
			// $title = isset($info['og:title']) && !empty($info['og:title']) ? $info['og:title'] : $title;
			// $info = $shareInfo->infoTw->twitter_cards_tags(true);
			// $title = isset($info['twitter:title']) && !empty($info['twitter:title']) ? $info['twitter:title'] : $title;
			
			$shareInfo = (object) array(
				'urlroot'		=> $urlroot,
				'url'			=> $url,
				'title'			=> $title
			);
			return $shareInfo;
		}

		public function setToolbarsBackground() {
			$isEnabled = $this->is_toolbar_enabled();
			
			$tblList = array();
			$toolbars = array(
				'floating' 					=> 'box-floating',
				'content_horizontal'		=> 'box-panel',
				'content_vertical'			=> 'box-panel-vertical'
			);
			foreach ( $toolbars as $toolbarType => $cssValue ) {
				if ( $isEnabled["$toolbarType"] ) {
					$bkcolor = $this->get_property( $toolbarType . '-background_color', 'string' );
					if ( !empty($bkcolor) ) {
						$bkcolor = str_replace('#', '', $bkcolor);
						$tblList["$toolbarType"] = '.psp-sshare-wrapper.' . $cssValue . ' { background-color: #' . $bkcolor . '; }';
					}
				}
			}
			
			if ( !empty($tblList) ) {
				$tblList = implode(PHP_EOL, $tblList);
			
				$tblList = PHP_EOL . '<!-- start/ ' . ( $this->the_plugin->details['plugin_name'] ) . '/ Social Sharing -->' . PHP_EOL
				. '<style type="text/css">' . PHP_EOL
				. $tblList
				. PHP_EOL . '</style>'
				. PHP_EOL . '<!-- end/ ' . ( $this->the_plugin->details['plugin_name'] ) . '/ Social Sharing -->' . PHP_EOL;
				return $tblList;
			}
			return ''; 
		}

		/**
		 * get COUNT
		 */
		public function getSocialsData( $website_url = '', $itemid = 0 ) {
			$socialData = $this->the_plugin->social_get_stats(array(
				//'providers'				=> array(),
				'from'					=> 'toolbar',
				'cache_life_time'		=> 600, // in seconds
				'website_url'			=> $website_url,
				'postid'				=> $itemid,
			));
			return $socialData;
		}


		/**
		 * Toolbar exclude
		 */
		public function tbExcludeCategs_opt() {
			$args = array(
				'orderby' => 'name',
				'parent' => 0
			);
			$categories = get_categories( $args );
			if ( empty($categories) || !is_array($categories)) {
				return array();
			}
			
			$ret = array();
			foreach ( $categories as $category ) {
				$key = $category->term_id;
				$value = $category->name;
				$ret["$key"] = $value;
			}
			return $ret;
		}
		
		public function is_exclude_item( $toolbarType ) {
			
			$__excludePost = $this->get_property( $toolbarType . '-exclude-post', 'array' );
			$exclude = array(
				'categ'			=> $this->get_property( $toolbarType . '-exclude-categ', 'array' ),
				'post_include'	=> isset($__excludePost['include']) && !empty($__excludePost['include']) ? array_map('trim', explode(',', $__excludePost['include'])) : array(),
				'post_exclude'	=> isset($__excludePost['exclude']) && !empty($__excludePost['exclude']) ? array_map('trim', explode(',', $__excludePost['exclude'])) : array()
			);
	
			if ( is_category() ) {

				$categ = get_category(get_query_var('cat'), false);
				$categ_id = $categ->term_id;
				if ( in_array($categ_id, $exclude['categ']) ) {
					return true;
				}

			} else if ( is_single() || is_page() ) {

				global $post;
				$post_id = $post->ID;
  
				// verify post in posts IDs list
				if ( !empty($exclude['post_include']) ) {
					if ( !in_array($post_id, $exclude['post_include']) ) {
						return true;
					}
					return false;
				}
				if ( !empty($exclude['post_exclude']) ) {
					if ( in_array($post_id, $exclude['post_exclude']) ) {
						return true;
					}
				}

				// verify post in category
				$categories = get_the_category($post_id);
				if ( $categories ) {
					foreach ($categories as $category) {
						// if ( $category->name == 'uncategorized' || $category->slug == 'uncategorized' ) continue 1;
						if ( in_array($category->term_id, $exclude['categ']) ) {
							return true;
						}
					}
				}
			}
			return false;
		}


		/**
		 * UTILS
		 */
		private function get_property( $key, $type = 'string', $default = '' ) {
			$opt = $this->plugin_settings;
			switch ($type) {
				case 'string':
					$prop = isset($opt["$key"]) ? $opt["$key"] : ( !empty($default) ? $default : '' );
					break;
					
				case 'array':
					$prop = isset($opt["$key"]) && is_array($opt["$key"]) ? $opt["$key"] : ( !empty($default) ? $default : array() );
					break;
			}
			return $prop;
		}
		
		public function formatCount( $value ) {
			if ( is_string($value) ) {
				return $value;
			}

			$ret = (int) $value;
			$len = strlen( (string) $value);
			if ( $len >= 5 && $len <= 6 ) {
				$ret = '+' . floor( $value / 1000 ) . 'k';
			} else if ( $len >=7 && $len <= 9 ) {
				$ret = '+' . floor( $value / 1000000 ) . 'm';
			}
			return $ret;
		}
	}
}

// Initialize the psp_SocialSharing class
//$psp_SocialSharing = new psp_SocialSharing();
