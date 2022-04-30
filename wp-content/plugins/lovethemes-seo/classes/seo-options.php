<?php
/**
 * This creates the plugins options.
 *
 * Text Domain: lovethemes
 *
 * @package WordPress\LoveThemes Auto SEO
 */
class LoveThemes_SEO_Options {
    /**
     * Instantiate options
     */
	private $options;

	public function __construct()
    {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}
    /**
     * Add plugin page menu
     */
	public function add_plugin_page()
    {
		add_menu_page( 'Magic SEO', 'Magic SEO', 'manage_options', 'lovethemes-seo', array( $this, 'create_magic_settings_page' ), MAGIC_SEO_PLUGIN_URL . '/assets/img/icon.png', 99 );
	}
    /**
     * Create options page
     */
	public function create_magic_settings_page()
    {
		$this->options = get_option( 'magic_option_name' );
    ?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Magic SEO', 'lovethemes' ); ?></h2>
            <p><?php esc_html_e( 'This plugin enables sitewide automatic Search Engine Optimisation (SEO) for your website. It automatically generates titles, descriptions and meta tags directly from your websites content and also adds Open Graph data for social media. Additionally, it will generate a search engine friendly XML sitemap that automatically updates with your content and provides further options for enhanced performance & security which can directly affect Search Engine Optimisation (SEO).', 'lovethemes' ); ?></p>

			<hr>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'magic_option_group' );
					do_settings_sections( 'magic_settings' );
					submit_button();
				?>
			</form>
		</div>
	<?php
    }
    /**
     * Register page settings
     */
	public function page_init()
    {
		/**
		 * Register Settings
		 */
		register_setting( 'magic_option_group', 'magic_option_name', array( $this, 'sanitize' ) );
		/**
		 * Add Settings Section
		 */
		add_settings_section( 'magic_setting_section', 'Settings', array( $this, 'section_info' ), 'magic_settings' );
		/**
		 * GA ID Setting
		 */
		add_settings_field( 'ga_id', 'Google Analytics UA-ID', array( $this, 'ga_id_callback' ), 'magic_settings', 'magic_setting_section' );
		/**
		 * Performance Setting
		 */
		add_settings_field( 'enhanced_performance', 'Enable Performance Enhancements', array( $this, 'enhanced_performance_callback' ), 'magic_settings', 'magic_setting_section' );
		/**
		 * Security Setting
		 */
		add_settings_field( 'enhanced_security', 'Enable Security Enhancements', array( $this, 'enhanced_security_callback' ), 'magic_settings', 'magic_setting_section' );
		/**
		 * Minify HTML Source Setting
		 */
		add_settings_field( 'minify_source', 'Enable HTML Source Minification', array( $this, 'minify_source_callback' ), 'magic_settings', 'magic_setting_section' );
	}
    /**
     * Sanitize Options
     */
	public function sanitize($input)
    {
		$sanitized = array();

		$allowed_html = array(
		    'a' => array(
		        'href' => array(),
		        'title' => array()
		    ),
		    'br' => array(),
		    'em' => array(),
		    'strong' => array(),
			'b' => array(),
		);

		//$sanitized['magic_option_name'] = wp_kses( $input['magic_option_name'], $allowed_html );

		if ( isset( $input['ga_id'] ) )
        {
			$sanitized['ga_id'] = sanitize_text_field( $input['ga_id']  );
		}

		if ( isset( $input['enhanced_performance'] ) )
        {
			$sanitized['enhanced_performance'] = sanitize_text_field( $input['enhanced_performance'] );
		}

		if ( isset( $input['enhanced_security'] ) )
        {
			$sanitized['enhanced_security'] = sanitize_text_field( $input['enhanced_security'] );
		}

		if ( isset( $input['minify_source'] ) )
        {
			$sanitized['minify_source'] = sanitize_text_field( $input['minify_source'] );
		}

		return $sanitized;
	}
    /**
     * Options page info section
     */
	public function section_info()
    {
		$anchor_text = esc_html__( 'click here.', 'lovethemes' );
		$anchor      = esc_url( 'https://support.google.com/analytics/answer/1008080#zippy=%2Cin-this-article' );
		$link        = sprintf( '<a href="%s">%s</a>', $anchor, $anchor_text );

 		/* translators: 1 is a link with text "click here." and URL https://support.google.com/analytics/answer/1008080#zippy=%2Cin-this-article */
		echo sprintf( esc_html__( 'The settings below are in addition to the automatic SEO features provided by the plugin and are optional. But, we highly recommended using them as they can have a direct positive impact on your SEO. If you would like to use your Google Analytics, simply add your tracking code below. If you need help fimding your Google Analytics UA-ID %1$s', 'lovethemes' ), $link );
	}
    /**
     * Options callbacks
     */
	public function ga_id_callback()
    {
		printf(
			'<input class="regular-text" type="text" name="magic_option_name[ga_id]" id="ga_id" value="%s" placeholder="'.esc_attr__('UA-XXXXXX-X','lovethemes').'">
			<br>
			<label style="font-size:12px!important;">'.esc_html__('If the theme you are using has a tracking code option, only use one, not both.','lovethemes').'</label>
			',
			isset( $this->options['ga_id'] ) ? esc_attr( $this->options['ga_id']) : ''
		);
	}

	public function enhanced_performance_callback()
    {
		printf(
			'<input type="checkbox" id="enhanced_performance" name="magic_option_name[enhanced_performance]" value="1"' . checked( 1, $this->options['enhanced_performance'], false ) . '/>
			<label>'.esc_html__('Remove emoji junk, enable webp mime type upload, and remove query strings to increase overall website performance.','lovethemes').'</label>
			'
		);
	}

	public function enhanced_security_callback()
    {
		printf(
			'<input type="checkbox" id="enhanced_security" name="magic_option_name[enhanced_security]" value="1"' . checked( 1, $this->options['enhanced_security'], false ) . '/>
			<label>'.esc_html__('Reject requests for comment posting coming from a browser (or, more commonly, a bot) that has no referrer in the request and will remove unneeded header information and x-pingback that can be used for version hacking.','lovethemes').'</label>
			'
		);
	}

	public function minify_source_callback()
    {
		printf(
			'<input type="checkbox" id="minify_source" name="magic_option_name[minify_source]" value="1"' . checked( 1, $this->options['minify_source'], false ) . '/>
			<label>'.esc_html__('Minification reduces load time and bandwidth usage on your website. It dramatically improves website speed and accessibility, directly translating into a better user experience.','lovethemes').'</label>
			'
		);
	}
}
if ( is_admin() )
{
	new LoveThemes_SEO_Options();
}