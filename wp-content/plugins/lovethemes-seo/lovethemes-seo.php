<?php
/**
 * Plugin Name: Magic SEO
 * Plugin URI: https://demo.lovethemes.co/
 *
 * Description: Magic SEO is a beautifully simple automated SEO solution for WordPress. Start optimising your website without the need to hire an SEO or understand code. Simply write good, relevant content and Magic SEO will do the rest! Magic SEO enables site wide automatic Search Engine Optimisation (SEO) for your website. It will automatically generate titles, descriptions and meta tags directly from your websites' content and will also add Twitter Card and Open Graph  data for social media. In addition, it will generate a search engine friendly XML sitemap that automatically updates with your content and provides further options for enhanced performance & security which can directly affect Search Engine Optimisation (SEO).
 *
 * Author: LoveThemes
 * Author URI: https://lovethemes.co/
 *
 * Version: 20210912
 *
 * Text Domain: lovethemes
 *
 * @package WordPress\LoveThemes Auto SEO
 */
if ( ! defined( 'ABSPATH' ) )
{
    exit; // Exit if accessed directly.
}

class LoveThemes_Auto_SEO {
	function __construct()
	{
		/**
		 * Show PHP errors or warnings
		 */
		error_reporting( E_ERROR | E_WARNING );

		if ( ! function_exists( 'is_plugin_active' ) ) :
			/**
			 * Check if plugin function exists
			 */
     		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		endif;

        if ( ! function_exists( 'lovethemes_plugin_load_textdomain' ) ) :
            /**
    		 * Make plugin available for translation.
    		 * Translations can be dropped in the /language/ directory.
    		 */
            function lovethemes_plugin_load_textdomain()
            {
                load_plugin_textdomain( 'lovethemes', FALSE, basename( dirname( __FILE__ ) ) . '/language/' );
            }
            add_action( 'plugins_loaded', 'lovethemes_plugin_load_textdomain' );
        endif;
        /**
         * Prevent conflicts with other SEO plugins.
         */
        if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) :
			/**
			 * Yoast plugin check for conflicts
			 */
			echo '
			<div class="notice notice-error">
				<p>'.esc_html__( "You appear to be using Yoast SEO Plugin. Please deactivate as this may cause conflicts with Magic SEO.","lovethemes" ).'
			</div>
			';
		endif;

		if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) ) :
			/**
			 * AIO SEO plugin check for conflicts
			 */
			echo '
			<div class="notice notice-error">
				<p>'.esc_html__( "You appear to be using All In One SEO Plugin. Please deactivate as this may cause conflicts with Magic SEO.", "lovethemes" ).'
			</div>
			';
		endif;
        /**
         * Backwards compat with our themes.
         */
        if ( ! function_exists( 'lovethemes_seo' ) ) :
            /**
             * Load the plugin files
             */
            function lovethemes_seo()
            {
                /**
                 * Define paths
                 */
                define( "MAGIC_SEO_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );
                define( "MAGIC_SEO_PLUGIN_URL", plugins_url( '', __FILE__ ) );
                /**
                 * Load plugin options
                 */
                require_once MAGIC_SEO_PLUGIN_PATH . '/classes/seo-options.php';
                /**
                 * Get all of the available plugin options.
                 * Options sanitized on input.
                 */
				$options              = get_option( 'option_name' );
                $ga_id                = $options['ga_id'];
                $enhanced_security    = $options['enhanced_security'];
                $enhanced_performance = $options['enhanced_performance'];
				$minify_source        = $options['minify_source'];

                // analytics
                if ( $ga_id != '' )
        		{
        			require_once( 'functions/fn.analytics.php' );
        		}

                // performance
                if ( $enhanced_performance != false )
        		{
        			require_once( 'functions/fn.performance.php' );
        		}

                // security
                if ( $enhanced_security != false )
        		{
        			require_once( 'functions/fn.security.php' );
        		}

                // minification
                if ( $minify_source != false )
        		{
        			require_once( 'functions/fn.minify.php' );
        		}

                // load main seo functions
                require_once( 'functions/fn.seo.php' );

                // add noindex metabox
                function lovethemes_add_noindex_checkbox_function()
                {
                    $screens = array( 'post', 'page' );

                    foreach( $screens as $screen )
                    {
                        add_meta_box( 'noindex_checkbox_id','Block Search Engines', 'lovethemes_noindex_checkbox_callback_function', $screen, 'side', 'high');
                    }
                }
                add_action( 'add_meta_boxes', 'lovethemes_add_noindex_checkbox_function' );

                function lovethemes_noindex_checkbox_callback_function( $post )
                {
                    global $post;

                    $isNoIndex = get_post_meta( $post->ID, 'is_noindex', true );
                    ?>
                        <input type="checkbox" name="is_noindex" value="yes" <?php echo (( $isNoIndex == 'yes') ? 'checked="checked"': ''); ?>/>
                        <label><?php esc_html_e( "Prevent Search Engines indexing this content?", "lovethemes" ); ?></label>
                    <?php
                }

                function lovethemes_save_noindex_post( $post_id )
                {
                    update_post_meta( $post_id, 'is_noindex', $_POST['is_noindex'] );
                }
                add_action( 'save_post', 'lovethemes_save_noindex_post' );
            }
            add_action( 'plugins_loaded', 'lovethemes_seo' );
        endif;
    }
}
new LoveThemes_Auto_SEO();