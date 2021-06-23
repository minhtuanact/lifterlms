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
			__( 'The selected option of "Conversion method" does not support WebP format. Please read %1$sthe plugin FAQ%2$s, specifically question about requirements of plugin. GD or Imagick library is installed on your server, but it does not support the WebP format. This issue is plugin-independent.', 'webp-converter-for-media' ),
			'<a href="https://wordpress.org/plugins/webp-converter-for-media/#faq" target="_blank">',
			'</a>'
		)
	);
	?>
	<br><br>
	<?php
	echo wp_kses_post(
		__( 'Select a different method in the "Conversion method" option if available, or reconfigure the server so that the selected conversion method supports the WebP format. Please contact your server administrator in this case.', 'webp-converter-for-media' )
	);
	?>
</p>
