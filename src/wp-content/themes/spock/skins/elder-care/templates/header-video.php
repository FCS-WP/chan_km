<?php
/**
 * The template to display the background video in the header
 *
 * @package SPOCK
 * @since SPOCK 1.0.14
 */
$spock_header_video = spock_get_header_video();
$spock_embed_video  = '';
if ( ! empty( $spock_header_video ) && ! spock_is_from_uploads( $spock_header_video ) ) {
	if ( spock_is_youtube_url( $spock_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $spock_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php spock_show_layout( spock_get_embed_video( $spock_header_video ) ); ?></div>
		<?php
	}
}
