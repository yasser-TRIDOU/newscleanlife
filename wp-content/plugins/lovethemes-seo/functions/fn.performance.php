<?php
/**
 * Performance enhancements that help overall SEO.
 *
 * Text Domain: lovethemes
 *
 * @package WordPress\LoveThemes Auto SEO
 */
/**
 * Remove Emoji Junk
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

if ( ! function_exists( 'lovethemes_disable_wp_responsive_images' ) ) :
	/**
     * Disable WP srcset responsive images
     */
	function lovethemes_disable_wp_responsive_images()
	{
		return 1;
	}
	add_filter('max_srcset_image_width', 'lovethemes_disable_wp_responsive_images');
endif;

if ( ! function_exists( 'lovethemes_webp_upload_mimes' ) ) :
	/**
     * Enable webp for enhanced performance
     */
	function lovethemes_webp_upload_mimes( $existing_mimes )
	{
		$existing_mimes['webp'] = 'image/webp';

		return $existing_mimes;
	}
	add_filter( 'mime_types', 'lovethemes_webp_upload_mimes' );
endif;

if ( ! function_exists( 'lovethemes_remove_query_strings' ) ) :
    /**
     * Remove query strings from
     * assets for caching
     */
    function lovethemes_remove_query_strings( $src )
    {
        return remove_query_arg( 'ver', $src );
    }
    add_filter( 'script_loader_src', 'lovethemes_remove_query_strings' );
    add_filter( 'style_loader_src', 'lovethemes_remove_query_strings' );
endif;

if ( ! function_exists( 'lovethemes_remove_type_attr' ) ) :
    /**
     * Remove type attributes on scripts and css for W3C validation
     */
    function lovethemes_remove_type_attr( $tag, $handle )
    {
        return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
    }
    add_filter( 'script_loader_tag', 'lovethemes_remove_type_attr', 10, 2 );
    add_filter( 'style_loader_tag', 'lovethemes_remove_type_attr', 10, 2 );
endif;

if ( ! function_exists( 'lovethemes_async' ) ) :
    /**
     * Asynchronously load CSS to improve performance
     */
    function lovethemes_async( $url )
    {
        if ( strpos( $url, '#asyncload') === false )
        {
            return $url;
        }
        else if ( is_admin() )
        {
            return str_replace( '?#asyncload', '', $url );
        }
        else
        {
    	    return str_replace( '?#asyncload', '', $url ) . "' rel='preload' as='style";
        }
    }
    add_filter( 'clean_url', 'lovethemes_async', 11, 1 );
endif;

if ( ! function_exists( 'lovethemes_defer' ) ) :
    /**
     * Defer scripts to improve performance
     */
    function lovethemes_defer( $url )
    {
        if ( strpos( $url, '#deferload') === false )
        {
            return $url;
        }
        else if ( is_admin() )
        {
            return str_replace( '?#deferload', '', $url );
        }
        else
        {
    	    return str_replace( '?#deferload', '', $url ) . "' defer='defer";
        }
    }
    add_filter( 'clean_url', 'lovethemes_defer', 11, 1 );
endif;