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
		/* translators: %1$s: server path */
			__( 'Unable to create or edit .htaccess file (function is_readable() or is_writable() returns false). Change directory permissions. The current using path of file is: %1$s. Please contact your server administrator.', 'webp-converter-for-media' ),
			'<strong>' . apply_filters( 'webpc_dir_path', '', 'uploads' ) . '/.htaccess</strong>'
		)
	);
	?>
</p>
