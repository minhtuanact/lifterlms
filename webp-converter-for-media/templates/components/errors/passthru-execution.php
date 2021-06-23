<?php
/**
 * Server configuration error displayed in errors section.
 *
 * @var string $passthru_url URL of Pass Thru image loader.
 * @package WebP Converter for Media
 */

?>
<p>
	<?php
	echo wp_kses_post(
		sprintf(
		/* translators: %s: anchor tag */
			__( 'Execution of the PHP file from path "%s" is blocked on your server, or access to this file is blocked. Add an exception and enable this file to be executed via HTTP request. To do this, check the security plugin settings (if you are using) or the security settings of your server.', 'webp-converter-for-media' ),
			'<a href="' . $passthru_url . '" target="_blank">' . $passthru_url . '</a>',
			'<br><br>'
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
