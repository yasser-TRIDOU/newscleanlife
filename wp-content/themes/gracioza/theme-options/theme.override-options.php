<?php
/**
 * Theme Options and override-optionses support
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.29
 */


// -----------------------------------------------------------------
// -- Override options
// -----------------------------------------------------------------

if ( !function_exists('gracioza_init_override_options') ) {
	add_action( 'after_setup_theme', 'gracioza_init_override_options' );
	function gracioza_init_override_options() {
		if ( is_admin() ) {
			add_action('admin_enqueue_scripts',	'gracioza_add_override_options_scripts');
			add_action('save_post',				'gracioza_save_override_options');
		}
	}
}
	
// Load required styles and scripts for admin mode
if ( !function_exists( 'gracioza_add_override_options_scripts' ) ) {
	
	function gracioza_add_override_options_scripts() {
		// If current screen is 'Edit Page' - load font icons
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && gracioza_allow_override_options(!empty($screen->post_type) ? $screen->post_type : $screen->id)) {
			wp_enqueue_style( 'fontello-style',  gracioza_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'jquery-ui-accordion', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'gracioza-options', gracioza_get_file_url('theme-options/theme.options.js'), array('jquery'), null, true );
			wp_localize_script( 'gracioza-options', 'gracioza_dependencies', gracioza_get_theme_dependencies() );
		}
	}
}


// Check if override options is allow
if (!function_exists('gracioza_allow_override_options')) {
	function gracioza_allow_override_options($post_type) {
		return apply_filters('gracioza_filter_allow_override_options', in_array($post_type, array('page', 'post')), $post_type);
	}
}

// Add override options
if (!function_exists('gracioza_add_override_options')) {
    add_filter('gracioza_filter_override_options', 'gracioza_add_override_options');
	function gracioza_add_override_options($list) {
        global $post_type;
        if (gracioza_allow_override_options($post_type)) {
            $list[] = array(sprintf('gracioza_override_options_%s', $post_type),
                esc_html__('Theme Options', 'gracioza'),
                'gracioza_show_override_options',
                $post_type,
                $post_type=='post' ? 'side' : 'advanced',
                'default'
            );
        }
        return $list;
	}
}

// Callback function to show fields in override options
if (!function_exists('gracioza_show_override_options')) {
	function gracioza_show_override_options() {
		global $post, $post_type;
		if (gracioza_allow_override_options($post_type)) {
			// Load saved options 
			$meta = get_post_meta($post->ID, 'gracioza_options', true);
			$tabs_titles = $tabs_content = array();
			global $GRACIOZA_STORAGE;
			// Refresh linked data if this field is controller for the another (linked) field
			// Do this before show fields to refresh data in the $GRACIOZA_STORAGE
			foreach ($GRACIOZA_STORAGE['options'] as $k=>$v) {
				if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
				if (!empty($v['linked'])) {
					$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
					if (!empty($v['val']) && !gracioza_is_inherit($v['val']))
						gracioza_refresh_linked_data($v['val'], $v['linked']);
				}
			}
			// Show fields
			foreach ($GRACIOZA_STORAGE['options'] as $k=>$v) {
				if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
				if (empty($v['override']['section']))
					$v['override']['section'] = esc_html__('General', 'gracioza');
				if (!isset($tabs_titles[$v['override']['section']])) {
					$tabs_titles[$v['override']['section']] = $v['override']['section'];
					$tabs_content[$v['override']['section']] = '';
				}
				$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
				$tabs_content[$v['override']['section']] .= gracioza_options_show_field($k, $v, $post_type);
			}
			if (count($tabs_titles) > 0) {
				?>
				<div class="gracioza_options gracioza_override_options">
					<input type="hidden" name="override_options_post_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
					<input type="hidden" name="override_options_post_type" value="<?php echo esc_attr($post_type); ?>" />
					<div id="gracioza_options_tabs" class="gracioza_tabs">
						<ul><?php
							$cnt = 0;
							foreach ($tabs_titles as $k=>$v) {
								$cnt++;
								?><li><a href="#gracioza_options_<?php echo esc_attr($cnt); ?>"><?php echo esc_html($v); ?></a></li><?php
							}
						?></ul>
						<?php
							$cnt = 0;
							foreach ($tabs_content as $k=>$v) {
								$cnt++;
								?>
								<div id="gracioza_options_<?php echo esc_attr($cnt); ?>" class="gracioza_tabs_section gracioza_options_section">
									<?php gracioza_show_layout($v); ?>
								</div>
								<?php
							}
						?>
					</div>
				</div>
				<?php		
			}
		}
	}
}


// Save data from override options
if (!function_exists('gracioza_save_override_options')) {
	
	function gracioza_save_override_options($post_id) {

		// verify nonce
		if ( !wp_verify_nonce( gracioza_get_value_gp('override_options_post_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$post_type = wp_kses_data(wp_unslash(isset($_POST['override_options_post_type']) ? $_POST['override_options_post_type'] : $_POST['post_type']));

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		// Save meta
		$meta = array();
		$options = gracioza_storage_get('options');
		foreach ($options as $k=>$v) {
			// Skip not overriden options
			if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
			// Skip inherited options
			if (!empty($_POST['gracioza_options_inherit_' . $k])) continue;
			// Get option value from POST
			$meta[$k] = isset($_POST['gracioza_options_field_' . $k])
							? gracioza_get_value_gp('gracioza_options_field_' . $k)
							: ($v['type']=='checkbox' ? 0 : '');
		}
		update_post_meta($post_id, 'gracioza_options', $meta);
		
		// Save separate meta options to search template pages
		if ($post_type=='page' && !empty($_POST['page_template']) && $_POST['page_template']=='blog.php') {
			update_post_meta($post_id, 'gracioza_options_post_type', isset($meta['post_type']) ? $meta['post_type'] : 'post');
			update_post_meta($post_id, 'gracioza_options_parent_cat', isset($meta['parent_cat']) ? $meta['parent_cat'] : 0);
		}
	}
}
?>