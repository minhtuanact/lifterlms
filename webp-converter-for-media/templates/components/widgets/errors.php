<?php
/**
 * Widget displayed errors on plugin settings page.
 *
 * @var string[] $errors List of errors detected by plugin.
 * @package WebP Converter for Media
 */

?>
<?php if ( $errors ) : ?>
	<div class="webpPage__widget">
		<h3 class="webpPage__widgetTitle webpPage__widgetTitle--error">
			<?php echo esc_html( __( 'Server configuration error', 'webp-converter-for-media' ) ); ?>
		</h3>
		<div class="webpContent webpContent--wide">
			<?php echo wp_kses_post( implode( '<p>---</p>', $errors ) ); ?>
			<p>---</p>
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
					/* translators: %1$s: open strong tag, %2$s: close strong tag, %3$s: errors codes */
						__( '%1$sError codes:%2$s %3$s', 'webp-converter-for-media' ),
						'<strong>',
						'</strong>',
						implode( ', ', array_keys( $errors ) )
					)
				);
				?>
			</p>
		</div>
	</div>
<?php endif; ?>
