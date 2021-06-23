<?php
/**
 * Notice displayed in admin panel.
 *
 * @var string $ajax_url URL of admin-ajax.
 * @package WebP Converter for Media
 */

?>
<div class="notice notice-success is-dismissible"
	data-notice="webp-converter-for-media"
	data-url="<?php echo esc_url( $ajax_url ); ?>"
>
	<div class="webpContent webpContent--notice">
		<h4>
			<?php echo esc_html( __( 'Thank you for using our plugin WebP Converter for Media!', 'webp-converter-for-media' ) ); ?>
		</h4>
		<p>
			<?php
			echo wp_kses_post(
				sprintf(
				/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
					__( 'Please let us know what you think about our plugin. It is important that we can develop this tool. Thank you for all the ratings, reviews and donates. If you have a technical problem, please before you add a review %1$scheck our FAQ%2$s or contact us if you did not find help there. We will try to help you!', 'webp-converter-for-media' ),
					'<a href="https://wordpress.org/plugins/webp-converter-for-media/#faq" target="_blank">',
					'</a>'
				)
			);
			?>
		</p>
		<div class="webpContent__buttons">
			<a href="https://wordpress.org/support/plugin/webp-converter-for-media/#new-post" target="_blank"
				class="webpContent__button webpButton webpButton--green"
			>
				<?php echo esc_html( __( 'Get help', 'webp-converter-for-media' ) ); ?>
			</a>
			<a href="https://wordpress.org/support/plugin/webp-converter-for-media/reviews/?rate=5#new-post"
				target="_blank"
				class="webpContent__button webpButton webpButton--green"
			>
				<?php echo esc_html( __( 'Add review', 'webp-converter-for-media' ) ); ?>
			</a>
			<a href="https://ko-fi.com/gbiorczyk/?utm_source=webp-converter-for-media&utm_medium=notice-thanks"
				target="_blank"
				class="webpContent__button webpButton webpButton--green dashicons-heart"
			>
				<?php echo esc_html( __( 'Provide us a coffee', 'webp-converter-for-media' ) ); ?>
			</a>
			<button type="button" data-permanently
				class="webpContent__button webpButton webpButton--blue"
			>
				<?php echo esc_html( __( 'I added review, do not show again', 'webp-converter-for-media' ) ); ?>
			</button>
		</div>
	</div>
</div>
