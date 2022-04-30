<?php
/**
 * Class for adding customizer settings.
 *
 * @package FlorianBrinkmann\LazyLoadResponsiveImages
 */

namespace FlorianBrinkmann\LazyLoadResponsiveImages;

/**
 * Class Settings
 *
 * Adds options to the customizer.
 *
 * @package FlorianBrinkmann\LazyLoadResponsiveImages
 */
class Settings {

	/**
	 * Helpers object.
	 *
	 * @var \FlorianBrinkmann\LazyLoadResponsiveImages\Helpers
	 */
	private $helpers;

	/**
	 * Array of options data.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Classes which should not be lazy loaded.
	 *
	 * @var array
	 */
	private $disabled_classes;

	/**
	 * Value of settings for enabling lazy loading for iFrames.
	 *
	 * @var string
	 */
	private $enable_for_iframes;

	/**
	 * Value of setting for loading the unveilhooks plugin.
	 *
	 * @var string
	 */
	private $load_native_loading_plugin;

	/**
	 * Value of setting for loading the unveilhooks plugin.
	 *
	 * @var string
	 */
	private $load_unveilhooks_plugin;

	/**
	 * Value of settings for enabling lazy loading for background images.
	 *
	 * @var string
	 */
	private $enable_for_background_images;

	/**
	 * Value of settings for enabling lazy loading for videos.
	 *
	 * @var string
	 */
	private $enable_for_videos;

	/**
	 * Value of settings for enabling lazy loading for audios.
	 *
	 * @var string
	 */
	private $enable_for_audios;

	/**
	 * Value of setting for displaying a loading spinner.
	 *
	 * @var string
	 */
	private $loading_spinner;

	/**
	 * Default loading spinner color.
	 *
	 * @var string
	 */
	private $loading_spinner_color_default = '#333333';

	/**
	 * Value of setting for loading spinner color.
	 *
	 * @var string
	 */
	private $loading_spinner_color;

	/**
	 * Value of setting for displaying the option to disable the plugin per page/post.
	 *
	 * @var string
	 */
	private $granular_disable_option;

	/**
	 * Array of object types that should show the checkbox to disable lazy loading.
	 *
	 * @var array
	 */
	private $disable_option_object_types = array();

	/**
	 * String to modify lazysizes config.
	 *
	 * @var string
	 */
	private $lazysizes_config = '';

	/**
	 * Value of setting for processing the complete website markup.
	 *
	 * @var string
	 */
	private $process_complete_markup;

	/**
	 * Value of setting for additional filters to process.
	 *
	 * @var array
	 */
	private $additional_filters;

	/**
	 * Allowed HTML tags in descriptions.
	 *
	 * @var array
	 */
	private $allowed_description_html = array(
		'a' => array( 'href' => array() ),
		'br' => array(),
		'code' => array(),
		'strong' => array(),
	);

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		// Set helpers.
		$this->helpers = new Helpers();

		$this->options = array(
			'lazy_load_responsive_images_disabled_classes'      => array(
				'value'             => get_option( 'lazy_load_responsive_images_disabled_classes', '' ),
				'label'             => __( 'CSS classes to exclude', 'lazy-loading-responsive-images' ),
				'description'       => __( 'Enter one or more CSS classes to exclude them from lazy loading (separated by comma). This works only if the element that would get lazy loaded has the class, not on wrapper elements. To exclude an element and its children, use the <code>skip-lazy</code> class or the <code>data-skip-lazy</code> attribute.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'text_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_class_name_list',
				),
			),
			'lazy_load_responsive_images_additional_filters'      => array(
				'value'             => get_option( 'lazy_load_responsive_images_additional_filters', '' ),
				'label'             => __( 'Additional filters', 'lazy-loading-responsive-images' ),
				'description'       => __( 'Enter one or more additional WordPress filters that should be processed (one per line), for example, <code>wp_get_attachment_image</code>. Anything that does not match the regular expression for PHP function names will be removed.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'textarea_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_filter_name_list',
				),
			),
			'lazy_load_responsive_images_enable_for_iframes'    => array(
				'value'             => get_option( 'lazy_load_responsive_images_enable_for_iframes', '0' ),
				'label'             => __( 'Enable lazy loading for iframes', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_native_loading_plugin'    => array(
				'value'             => get_option( 'lazy_load_responsive_images_native_loading_plugin', '0' ),
				'label'             => __( 'Include lazysizes native loading plugin' ),
				'description'       => __( 'The plugin transforms images and iframes to use native lazyloading in browsers that support it. <strong>Important:</strong> Supporting browsers will use their threshold to decide if media needs to be loaded. That might lead to media being loaded even if it is far away from the visible area.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_unveilhooks_plugin'    => array(
				'value'             => get_option( 'lazy_load_responsive_images_unveilhooks_plugin', '0' ),
				'label'             => __( 'Include lazysizes unveilhooks plugin' ),
				'description'       => __( 'The plugin adds support for lazy loading of background images, scripts, styles, and videos. To use it with background images, scripts and styles, you will need to <a href="https://github.com/aFarkas/lazysizes/tree/gh-pages/plugins/unveilhooks">manually modify the markup</a>.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_enable_for_background_images'     => array(
				'value'             => get_option( 'lazy_load_responsive_images_enable_for_background_images', '0' ),
				'label'             => __( 'Enable lazy loading for inline background images', 'lazy-loading-responsive-images' ),
				'description'       => __( 'This feature needs the unveilhooks plugin and will automatically load it, regardless of the option to load the unveilhooks plugin is enabled or not. 
				<strong>It is possible that this setting causes issues, because:</strong> To also support multiple background images and to provide a 
				fallback for disabled JavaScript, the plugin removes the background rules from the element and adds a style element instead.
				The CSS selector is <code>.unique-class.lazyloaded</code> for the JS case, respective <code>.unique-class.lazyload</code> for the case that JS is disabled.
				If you have CSS background rules with a higher specifity that match the element, they will overwrite the rules
				that were extracted by Lazy Loader.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_enable_for_videos'     => array(
				'value'             => get_option( 'lazy_load_responsive_images_enable_for_videos', '0' ),
				'label'             => __( 'Enable lazy loading for videos', 'lazy-loading-responsive-images' ),
				'description'       => __( 'This feature needs the unveilhooks plugin and will automatically load it, regardless of the option to load the unveilhooks plugin is enabled or not.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_enable_for_audios'     => array(
				'value'             => get_option( 'lazy_load_responsive_images_enable_for_audios', '0' ),
				'label'             => __( 'Enable lazy loading for audios', 'lazy-loading-responsive-images' ),
				'description'       => __( 'This feature needs the unveilhooks plugin and will automatically load it, regardless of the option to load the unveilhooks plugin is enabled or not.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_loading_spinner'       => array(
				'value'             => get_option( 'lazy_load_responsive_images_loading_spinner', '0' ),
				'label'             => __( 'Display a loading spinner', 'lazy-loading-responsive-images' ),
				'description'       => __( 'To give the users a hint that there is something loading where they just see empty space. Works best with the aspectratio option. <a href="https://caniuse.com/#feat=svg-smil">Limited browser support.</a>', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_loading_spinner_color' => array(
				'value'             => get_option( 'lazy_load_responsive_images_loading_spinner_color', $this->loading_spinner_color_default ),
				'label'             => __( 'Color of the spinner', 'lazy-loading-responsive-images' ),
				'description'       => __( 'Spinner color in hex format. Default: #333333', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'color_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_hex_color',
				),
			),
			'lazy_load_responsive_images_granular_disable_option' => array(
				'value'             => get_option( 'lazy_load_responsive_images_granular_disable_option', '0' ),
				'label'             => __( 'Enable option to disable plugin per page/post', 'lazy-loading-responsive-images' ),
				'description'       => __( 'Displays a checkbox in the publish area of all post types (pages/posts/CPTs) that lets you disable the plugin on that specific post. To make it work for CPTs, they must support <code>custom-fields</code>.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_process_complete_markup' => array(
				'value'             => get_option( 'lazy_load_responsive_images_process_complete_markup', '0' ),
				'label'             => __( 'Process the complete markup', 'lazy-loading-responsive-images' ),
				'description'       => __( 'Instead of just modifying specific parts of the page (for example, the post content, post thumbnail), the complete generated markup is processed. With that, all images (and other media, if you enabled it) will be lazy loaded. Because the plugin needs to process more markup with that option enabled, it might slow down the page generation time a bit. If your page contains HTML errors, like unclosed tags, this might lead to unwanted behavior, because the DOM parser used by Lazy Loader tries to correct that.', 'lazy-loading-responsive-images' ),
				'field_callback'    => array( $this, 'checkbox_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_checkbox',
				),
			),
			'lazy_load_responsive_images_lazysizes_config' => array(
				'value'             => get_option( 'lazy_load_responsive_images_lazysizes_config', '' ),
				'label'             => __( 'Modify the default config', 'lazy-loading-responsive-images' ),
				'description'       => sprintf( /* translators: s=code example. */
					__( 'Here you can add custom values for the config settings of the <a href="https://github.com/aFarkas/lazysizes/#js-api---options">lazysizes script</a>. An example could look like this, modifying the value for the expand option:%s', 'lazy-loading-responsive-images' ),
					'<br><br><code>window.lazySizesConfig = window.lazySizesConfig || {};</code><br><code>lazySizesConfig.expand = 300;</code>'
				),
				'field_callback'    => array( $this, 'textarea_field_cb' ),
				'sanitize_callback' => array(
					$this->helpers,
					'sanitize_textarea',
				),
			),
		);

		// Fill properties with setting values.
		$this->disabled_classes        = explode( ',', $this->options['lazy_load_responsive_images_disabled_classes']['value'] );
		$this->enable_for_iframes      = $this->options['lazy_load_responsive_images_enable_for_iframes']['value'];
		$this->load_native_loading_plugin = $this->options['lazy_load_responsive_images_native_loading_plugin']['value'];
		$this->load_unveilhooks_plugin = $this->options['lazy_load_responsive_images_unveilhooks_plugin']['value'];
		$this->enable_for_videos       = $this->options['lazy_load_responsive_images_enable_for_videos']['value'];
		$this->enable_for_audios       = $this->options['lazy_load_responsive_images_enable_for_audios']['value'];
		$this->loading_spinner         = $this->options['lazy_load_responsive_images_loading_spinner']['value'];
		$this->loading_spinner_color   = $this->options['lazy_load_responsive_images_loading_spinner_color']['value'];
		$this->granular_disable_option = $this->options['lazy_load_responsive_images_granular_disable_option']['value'];
		$this->process_complete_markup = $this->options['lazy_load_responsive_images_process_complete_markup']['value'];
		$this->additional_filters = explode( "\n", $this->options['lazy_load_responsive_images_additional_filters']['value'] );
		$this->lazysizes_config = $this->options['lazy_load_responsive_images_lazysizes_config']['value'];
		$this->enable_for_background_images = $this->options['lazy_load_responsive_images_enable_for_background_images']['value'];

		// Register settings on media options page.
		add_action( 'admin_init', array( $this, 'settings_init' ), 12 );

		// Include color picker JS.
		add_action( 'admin_enqueue_scripts', array(
			$this,
			'add_color_picker',
		) );

		if ( '1' === $this->granular_disable_option ) {
			add_action( 'init', array( $this, 'disable_option_object_types_filter' ), 11 );

			// Register meta for disabling per page.
			add_action( 'init', array( $this, 'register_post_meta' ), 11 );

			// Publish post actions.
			add_action( 'post_submitbox_misc_actions', array( $this, 'add_checkbox' ), 9 );
			add_action( 'save_post', array( $this, 'save_checkbox' ) );
		}
	}

	/**
	 * Init settings on media options page.
	 */
	public function settings_init() {
		// Add section.
		add_settings_section(
			"lazy-load-responsive-images-section",
			sprintf(
				'<span id="lazy-loader-options">%s</span>',
				__( 'Lazy Loader options', 'lazy-loading-responsive-images' )
			),
			array( $this, 'section_cb' ),
			'media'
		);

		// Loop the options.
		foreach ( $this->options as $option_id => $option ) {
			// Register setting.
			register_setting( 'media', $option_id, array(
				'sanitize_callback' => $option['sanitize_callback'],
			) );

			// Create field.
			add_settings_field(
				$option_id,
				$option['label'],
				$option['field_callback'],
				'media',
				'lazy-load-responsive-images-section',
				array(
					'label_for'   => $option_id,
					'value'       => $option['value'],
					'description' => ( isset( $option['description'] ) ? $option['description'] : '' ),
				)
			);
		} // End foreach().
	}

	/**
	 * Section callback.
	 *
	 * @param array $args
	 */
	public function section_cb( $args ) {
	}

	/**
	 * Checkbox callback.
	 *
	 * @param array $args               {
	 *                                  Argument array.
	 *
	 * @type string $label_for          (Required) The label for the checkbox.
	 * @type string $value              (Required) The value.
	 * @type string $description        (Required) Description.
	 * }
	 */
	public function checkbox_field_cb( $args ) {
		// Get option value.
		$option_value = $args['value'];

		// Get label for.
		?>
		<input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>"
			   type="checkbox" <?php echo ( $option_value == '1' || $option_value == 'on' ) ? 'checked="checked"' : ''; ?>>
		<?php
		// Check for description.
		if ( '' !== $args['description'] ) { ?>
			<p class="description">
				<?php echo wp_kses( $args['description'], $this->allowed_description_html  ); ?>
			</p>
			<?php
		}
	}

	/**
	 * Text field callback.
	 *
	 * @param array $args               {
	 *                                  Argument array.
	 *
	 * @type string $label_for          (Required) The label for the text field.
	 * @type string $value              (Required) The value.
	 * @type string $description        (Required) Description.
	 * }
	 */
	public function text_field_cb( $args ) {
		// Get option value.
		$option_value = $args['value'];

		// Get label for.
		?>
		<input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>"
			   type="text" value="<?php echo esc_attr( $option_value ); ?>">
		<?php
		// Check for description.
		if ( '' !== $args['description'] ) { ?>
			<p class="description">
				<?php echo wp_kses( $args['description'], $this->allowed_description_html  ); ?>
			</p>
			<?php
		}
	}

	/**
	 * Textarea field callback.
	 *
	 * @param array $args               {
	 *                                  Argument array.
	 *
	 * @type string $label_for          (Required) The label for the textarea.
	 * @type string $value              (Required) The value.
	 * @type string $description        (Required) Description.
	 * }
	 */
	public function textarea_field_cb( $args ) {
		// Get option value.
		$option_value = $args['value'];

		?>
		<textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" style="width: 100%;"><?php echo esc_textarea( $option_value ); ?></textarea>
		<?php
		// Check for description.
		if ( '' !== $args['description'] ) { ?>
			<p class="description">
				<?php echo wp_kses( $args['description'], $this->allowed_description_html  ); ?>
			</p>
			<?php
		}
	}

	/**
	 * Color field callback.
	 *
	 * @param array $args               {
	 *                                  Argument array.
	 *
	 * @type string $label_for          (Required) The label for the color
	 *                                  field.
	 * @type string $value              (Required) The value.
	 * @type string $description        (Required) Description.
	 * }
	 */
	public function color_field_cb( $args ) {
		// Get option value.
		$option_value = $args['value'];

		// Get label for.
		?>
		<input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>"
			   type="text" value="<?php echo esc_attr( $option_value ); ?>"
			   data-default-color="<?php echo esc_attr( $this->loading_spinner_color_default ); ?>"
			   class="lazy-load-responsive-images-color-field">
		<?php
		// Check for description.
		if ( '' !== $args['description'] ) { ?>
			<p class="description">
				<?php echo wp_kses( $args['description'], array( 'a', 'strong', 'code', 'br' ) ); ?>
			</p>
			<?php
		}
	}

	/**
	 * Add color picker to media settings page and init it.
	 *
	 * @param string $hook_suffix PHP file of the admin screen.
	 */
	public function add_color_picker( $hook_suffix ) {
		// Check if we are not on the media backend screen.
		if ( 'options-media.php' !== $hook_suffix ) {
			return;
		} // End if().

		// Add color picker script and style and init it.
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_add_inline_script( 'wp-color-picker', "jQuery(document).ready(function($){
    $('.lazy-load-responsive-images-color-field').wpColorPicker();
});" );
	}

	/**
	 * Set array of post types that support granular disabling of Lazy Loader features.
	 */
	public function disable_option_object_types_filter() {
		$public_post_types = get_post_types( array(
			'public' => true,
		), 'names' );

		// Remove attachment post type.
		if ( is_array( $public_post_types ) && isset( $public_post_types['attachment'] ) ) {
			unset( $public_post_types['attachment'] );
		}

		/**
		 * Filter for the object types that should show the checkbox
		 * for disabling the lazy loading functionality. By default, all
		 * public post types (except attachment) are included.
		 *
		 * @param array $public_post_types An array of post types that should have the option
		 *                                 for disabling.
		 */
		$this->disable_option_object_types = apply_filters( 'lazy_loader_disable_option_object_types', $public_post_types );
	}

	/**
	 * Register post meta for disabling plugin per
	 */
	public function register_post_meta() {
		if ( ! is_array( $this->disable_option_object_types ) ) {
			return;
		}

		foreach ( $this->disable_option_object_types as $object_type ) {
			\register_post_meta(
				$object_type,
				'lazy_load_responsive_images_disabled',
				array(
					'type' => 'boolean',
					'description' => __( 'If the Lazy Loader plugin should be disabled for this page/post/CPT entry', 'lazy-loading-responsive-images' ),
					'single' => true,
					'show_in_rest' => true,
				)
			);
		}
	}

	/**
	 * Add checkbox to Publish Post meta box.
	 *
	 * @link https://github.com/deworg/dewp-planet-feed/
	 */
	public function add_checkbox() {
		global $post;

		if ( ! in_array( $post->post_type, $this->disable_option_object_types ) ) {
			return;
		}

		// Check user capability. Not bailing, though, on purpose.
		$maybe_enabled = current_user_can( 'publish_posts' );
		// This actually defines whether post will be listed in our feed.
		$value = absint( get_post_meta( $post->ID, 'lazy_load_responsive_images_disabled', true ) );
		printf(
			'<div class="misc-pub-section dewp-planet">
				<label for="disable-lazy-loader">
					<input type="checkbox" id="disable-lazy-loader" name="disable-lazy-loader" class="disable-lazy-loader" %s %s />
					<span class="dewp-planet__label-text">%s</span>
				</label>
			</div>',
			$maybe_enabled ? '' : 'disabled',
			$value === 1 ? 'checked' : '',
			esc_html__( 'Disable Lazy Loader', 'lazy-loading-responsive-images' )
		);
	}

	/**
	 * Save option value to post meta.
	 *
	 * @link https://github.com/deworg/dewp-planet-feed/
	 *
	 * @param  int $post_id ID of current post.
	 *
	 * @return int          ID of current post.
	 */
	public function save_checkbox( $post_id ) {
		if ( empty( $post_id ) || empty( $_POST['post_ID'] ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( absint( $_POST['post_ID'] ) !== $post_id ) {
			return $post_id;
		}
		if ( ! in_array( $_POST['post_type'], $this->disable_option_object_types ) ) {
			return $post_id;
		}
		if ( ! current_user_can( 'publish_posts' ) ) {
			return $post_id;
		}
		if ( empty( $_POST['disable-lazy-loader'] ) ) {
			\delete_post_meta( $post_id, 'lazy_load_responsive_images_disabled' );
		} else {
			\add_post_meta( $post_id, 'lazy_load_responsive_images_disabled', true, true );
		}
		return $post_id;
	}

	/**
	 * Return disabled classes setting value.
	 * 
	 * @return array
	 */
	public function get_disabled_classes() {
		return $this->disabled_classes;
	}

	/**
	 * Return load_unveilhooks_plugin value.
	 * 
	 * @return string
	 */
	public function get_load_unveilhooks_plugin() {
		return $this->load_unveilhooks_plugin;
	}

	/**
	 * Return enable_for_audios value.
	 * 
	 * @return string
	 */
	public function get_enable_for_audios() {
		return $this->enable_for_audios;
	}

	/**
	 * Return enable_for_videos value.
	 * 
	 * @return string
	 */
	public function get_enable_for_videos() {
		return $this->enable_for_videos;
	}

	/**
	 * Return enable_for_iframes value.
	 * 
	 * @return string
	 */
	public function get_enable_for_iframes() {
		return $this->enable_for_iframes;
	}

	/**
	 * Return enable_for_background_images value.
	 * 
	 * @return string
	 */
	public function get_enable_for_background_images() {
		return $this->enable_for_background_images;
	}

	/**
	 * Return load_native_loading_plugin value.
	 * 
	 * @return string
	 */
	public function get_load_native_loading_plugin() {
		return $this->load_native_loading_plugin;
	}

	/**
	 * Return lazysizes_config value.
	 * 
	 * @return string
	 */
	public function get_lazysizes_config() {
		return $this->lazysizes_config;
	}

	/**
	 * Return loading_spinner_color value.
	 * 
	 * @return string
	 */
	public function get_loading_spinner_color() {
		return $this->loading_spinner_color;
	}

	/**
	 * Return loading_spinner_color_default value.
	 * 
	 * @return string
	 */
	public function get_loading_spinner_color_default() {
		return $this->loading_spinner_color_default;
	}

	/**
	 * Return loading_spinner value.
	 * 
	 * @return string
	 */
	public function get_loading_spinner() {
		return $this->loading_spinner;
	}

	/**
	 * Return disable_option_object_types value.
	 * 
	 * @return array
	 */
	public function get_disable_option_object_types() {
		return $this->disable_option_object_types;
	}

	/**
	 * Return process_complete_markup value.
	 * 
	 * @return string
	 */
	public function get_process_complete_markup() {
		return $this->process_complete_markup;
	}

	/**
	 * Return additional_filters value.
	 * 
	 * @return array
	 */
	public function get_additional_filters() {
		return $this->additional_filters;
	}
}
