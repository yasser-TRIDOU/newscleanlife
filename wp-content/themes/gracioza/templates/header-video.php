<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0.14
 */
$gracioza_header_video = gracioza_get_header_video();
$gracioza_embed_video = '';
if (!empty($gracioza_header_video) && !gracioza_is_from_uploads($gracioza_header_video)) {
	if (gracioza_is_youtube_url($gracioza_header_video) && preg_match('/[=\/]([^=\/]*)$/', $gracioza_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$gracioza_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($gracioza_header_video) . '[/embed]' ));
			$gracioza_embed_video = gracioza_make_video_autoplay($gracioza_embed_video);
		} else {
			$gracioza_header_video = str_replace('/watch?v=', '/embed/', $gracioza_header_video);
			$gracioza_header_video = gracioza_add_to_url($gracioza_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$gracioza_embed_video = '<iframe src="' . esc_url($gracioza_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php gracioza_show_layout($gracioza_embed_video); ?></div><?php
	}
}
?>