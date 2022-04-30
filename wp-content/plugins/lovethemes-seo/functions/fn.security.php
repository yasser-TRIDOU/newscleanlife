<?php
/**
 * Security enhancements that help overall SEO.
 *
 * Text Domain: lovethemes
 *
 * @package WordPress\LoveThemes Auto SEO
 */

 if ( ! function_exists( 'lovethemes_check_referrer' ) ) :
 	/**
 	 * Reject requests for comment posting coming from
 	 * a browser (or, more commonly, a bot) that has no
 	 * referrer in the request.
 	 */
 	function lovethemes_check_referrer()
 	{
 		if ( ! isset( $_SERVER['HTTP_REFERER'] ) )
 		{
 			wp_die( esc_html__( "Please enable referrers in your browser.", "lovethemes" ) );
 		}
 	}
 	add_action( 'check_comment_flood', 'lovethemes_check_referrer' );
 endif;

 if ( ! function_exists( 'lovethemes_remove_x_pingback' ) ) :
 	/**
 	 * Disable X Pingback Header
 	 */
 	function lovethemes_remove_x_pingback( $headers )
 	{
 	    unset( $headers['X-Pingback'] );
 	    return $headers;
 	}
 	add_filter( 'wp_headers', 'lovethemes_remove_x_pingback' );
 endif;

 if ( ! function_exists( 'lovethemes_remove_header_info' ) ) :
 	/**
 	 * Remove unnecessary header information
 	 */
 	function lovethemes_remove_header_info()
 	{
 		remove_action( 'wp_head', 'feed_links', 2 );
 	    remove_action( 'wp_head', 'feed_links_extra', 3 );
 	    remove_action( 'wp_head', 'rsd_link' );
 	    remove_action( 'wp_head', 'wlwmanifest_link' );
 	    remove_action( 'wp_head', 'wp_generator' );
 	    remove_action( 'wp_head', 'start_post_rel_link' );
 		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
 	    remove_action( 'wp_head', 'index_rel_link' );
 	    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
 	    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
 		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
 		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
 		remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
 	}
 	add_action( 'init', 'lovethemes_remove_header_info' );
 endif;