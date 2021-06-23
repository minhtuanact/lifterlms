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
		__( 'Your server uses the cache for HTTP requests. The rules from .htaccess file or from Nginx configuration are not executed every time when the image is loaded, but the last redirect from cache is performed. With each request to image, your server should execute the rules from .htaccess file or from Nginx configuration. Now it only does this the first time and then uses cache. This means that if your server redirected image to WebP format the first time, it does so on every request. It should check the rules from .htaccess file or from Nginx configuration each time during request to image and redirect only when the conditions are met.', 'webp-converter-for-media' )
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
