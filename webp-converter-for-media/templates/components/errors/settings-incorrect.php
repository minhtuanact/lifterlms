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
		__( 'The plugin settings are incorrect! Check them out and save them again. Please remember that you must have at least one option selected for each field.', 'webp-converter-for-media' )
	);
	?>
</p>
