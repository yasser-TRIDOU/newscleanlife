<?php
/**
 * This is the function for the analytics tracking code
 *
 * @package WordPress\LoveThemes Auto SEO
 */
if ( ! function_exists( 'lovethemes_analytics' ) ) :
	function lovethemes_analytics()
	{
		/**
         * Get analytics option
         */
		$options  = get_option( 'option_name' );
		$tracking = $options['ga_id'];

        if ( $tracking != '' ) :
    ?>
    	<script async="async" src="//www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $tracking ); ?>"></script>
    	<script async="async">window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config', '<?php echo esc_attr( $tracking ); ?>');</script>
	<?php
		endif;
	}
	// use high priority to load last just before </head> tag
	add_action ( 'wp_head', 'lovethemes_analytics', 999999 );
endif;
