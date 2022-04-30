<?php
if ( ! function_exists( 'lovethemes_auto_seo' ) ) :
	/**
	 * The main auto SEO function
	 *
	 * Text Domain: lovethemes
	 *
	 * @package WordPress\LoveThemes Auto SEO
	 */
	function lovethemes_auto_seo()
	{
		global $post;
		setup_postdata( $post );

		// check if we're on a single post or page, if not use bloginfo settings
		if ( is_single() || is_page() ) :
			// get the content
			$content = get_the_content( $post );

			// strip shortcodes
			$content = preg_replace( "~(?:\[/?)[^/\]]+/?\]~s", "", $content );

			// strip styles
			$content = preg_replace( "/<style\\b[^>]*>(.*?)<\\/style>/s", "", $content );

			// strip images
			$content = preg_replace("/<img[^>]+\>/i", "", $content);

			// strip returns and newlines
			$content = str_replace("\n",  ' ', $content );
			$content = str_replace("\r",  ' ', $content );
			$content = str_replace("&nbsp;",  ' ', $content );

			// strip html
			$content = strip_tags( $content );

			// convert html back
			$content = htmlspecialchars_decode( $content );
		else :
			$content = wp_strip_all_tags( get_bloginfo( 'description' ) );
		endif;

		$lovethemes_featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );

		// get custom meta box data for noindexing
		$all_meta_fields = get_post_custom( $post->ID );
 		$isNoIndex = $all_meta_fields["is_noindex"][0];

		// if meta is true, block search engines
		if ( ! empty( $isNoIndex ) ) :
	?>
		<meta name="robots" content="noindex, nofollow" />
	<?php
		else :
	?>

		<link rel="canonical" href="<?php echo esc_url( get_the_permalink() ); ?>" />
		<meta name="description" content="<?php echo wp_strip_all_tags( substr( $content, 0, 320 )."..." ); ?>" />

		<meta property="og:locale" content="<?php echo wp_strip_all_tags( get_locale() ); ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo esc_url( get_the_permalink() ); ?>" />
		<meta property="og:site_name" content="<?php echo wp_strip_all_tags( bloginfo( 'name' ) ); ?>" />
		<meta property="og:title" content="<?php echo wp_strip_all_tags( get_the_title() ); ?>" />
		<meta property="og:description" content="<?php echo wp_strip_all_tags( substr( $content, 0, 320 )."..." ); ?>" />

		<?php if ( $lovethemes_featured_image[0] ) : ?>
			<meta property="og:image" content="<?php echo esc_url( $lovethemes_featured_image[0] ); ?>" />
		<?php endif; ?>

		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:title" content="<?php echo wp_strip_all_tags( get_the_title() ); ?>" />
		<meta name="twitter:description" content="<?php echo wp_strip_all_tags( substr( $content, 0, 320 )."..." ); ?>" />

		<?php if ( $lovethemes_featured_image[0] ) : ?>
			<meta name="twitter:image" content="<?php echo esc_url( $lovethemes_featured_image[0] ); ?>" />
		<?php endif; ?>
	<?php
		// end indexing meta check
		endif;
	}
	// use low priority to load first just after <head> tag
	add_action ( 'wp_head', 'lovethemes_auto_seo', -1 );
endif;

if ( ! function_exists( 'lovethemes_sitemap' ) ) :
	/**
	 * XML Sitemap Generator
	 */
    function lovethemes_sitemap()
    {
        $sitemap_posts = get_posts(
            array(
                'numberposts' => -1,
                'orderby'     => 'modified',
                'order'       => 'DESC',
                'post_type'   => array( 'post', 'page' )
            )
        );

        $sitemap  = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '
<!-- XML Sitemap -->
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        ';

            foreach( $sitemap_posts as $post )
            {
                setup_postdata( $post );

                $postdate = explode( " ", $post->post_modified );

                $sitemap .=
                '<url>'.
                    '<loc>' . get_permalink( $post->ID ) . '</loc>' .
                    '<lastmod>' . $postdate[0] . '</lastmod>' .
                    '<changefreq>weekly</changefreq>' .
                '</url>';
            }

        $sitemap .= '</urlset>';

        $fp = fopen( ABSPATH . 'sitemap.xml', 'w' );

        fwrite( $fp, $sitemap );
        fclose( $fp );
    }
    add_action( 'publish_post', 'lovethemes_sitemap' );
    add_action( 'publish_page', 'lovethemes_sitemap' );
endif;