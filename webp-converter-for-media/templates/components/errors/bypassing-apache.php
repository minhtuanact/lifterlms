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
		/* translators: %1$s: open anchor tag, %2$s: close anchor tag, %3$s: open anchor tag, %4$s: close anchor tag */
			__( 'Requests to images are processed by your server bypassing Apache. When loading images, rules from the .htaccess file are not executed. Occasionally, this only applies to known file extensions: .jpg, .png, etc. and when e.g. .png2 extension is loaded, then the redirections from the .htaccess file work, because the server does not understand this format and does not treat it as image files. Check the redirects for %1$s.png file%2$s (for which the redirection does not work) and for %3$s.png2 file%4$s (for which the redirection works correctly). Change the server settings to stop ignoring the rules from the .htaccess file.', 'webp-converter-for-media' ),
			'<a href="' . WEBPC_URL . 'assets/img/debug/icon-before.png" target="_blank">',
			'</a>',
			'<a href="' . WEBPC_URL . 'assets/img/debug/icon-before.png2" target="_blank">',
			'</a>'
		)
	);
	?>
	<br><br>
	<?php
	echo esc_html(
		__( 'In this case, please contact your server administrator.', 'webp-converter-for-media' )
	);
	?>
</p>
<?php require __DIR__ . '/passthru-info.php'; ?>
