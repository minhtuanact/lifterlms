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
		__( 'The selected option of "Conversion method" does not support AVIF format. This is a new format, so you will probably have to wait for its full support via PHP on your server. This issue is plugin-independent.', 'webp-converter-for-media' )
	);
	?>
	<br><br>
	<?php
	echo wp_kses_post(
		__( 'Select a different method in the "Conversion method" option, if available. Or in the "List of supported output formats" option, select only the WebP format, because the selected conversion method does not yet allow converting to AVIF format.', 'webp-converter-for-media' )
	);
	?>
</p>
