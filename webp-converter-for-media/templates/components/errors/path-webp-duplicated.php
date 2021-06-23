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
		/* translators: %1$s: filter name, %2$s: server path */
			__( 'The paths for /uploads files and for saving converted WebP files are the same. Change them using filter %1$s. The current path for them is: %2$s.', 'webp-converter-for-media' ),
			'<strong>webpc_dir_path</strong>',
			'<strong>' . apply_filters( 'webpc_dir_path', '', 'uploads' ) . '</strong>'
		)
	);
	?>
</p>
