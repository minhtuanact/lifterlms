<?php
/**
 * Server configuration error displayed in errors section.
 *
 * @package WebP Converter for Media
 */

?>
<p>
	<?php
	echo wp_kses_post(
		sprintf(
		/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
			__( 'On your server is not installed GD or Imagick library. Please read %1$sthe plugin FAQ%2$s, specifically question about requirements of plugin. This issue is plugin-independent. Please contact your server administrator in this case.', 'webp-converter-for-media' ),
			'<a href="https://wordpress.org/plugins/webp-converter-for-media/#faq" target="_blank">',
			'</a>'
		)
	);
	?>
</p>
