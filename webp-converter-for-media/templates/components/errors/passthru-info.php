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
		/* translators: %1$s: open strong tag, %2$s: close strong tag, %3$s: loader name */
			__( '%1$sAlso try changing option "Image loading mode" to a different one.%2$s Issues about rewrites can often be resolved by setting this option to "%3$s". You can do this in plugin settings below. After changing settings, remember to flush cache if you use caching plugin or caching via hosting.', 'webp-converter-for-media' ),
			'<strong>',
			'</strong>',
			'Pass Thru'
		)
	);
	?>
</p>
