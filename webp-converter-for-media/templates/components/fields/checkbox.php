<?php
/**
 * Checkbox field displayed in plugin settings form.
 *
 * @var mixed[] $option Data of field.
 * @var string  $index  Index of field.
 * @package WebP Converter for Media
 */

?>
<?php if ( $option['info'] ) : ?>
	<p><?php echo wp_kses_post( $option['info'] ); ?></p>
<?php endif; ?>
<table class="webpTable">
	<?php foreach ( $option['values'] as $value => $label ) : ?>
		<tr>
			<td>
				<input type="checkbox"
					name="<?php echo esc_attr( $option['name'] ); ?>[]"
					value="<?php echo esc_attr( $value ); ?>"
					id="webpc-<?php echo esc_attr( $index ); ?>-<?php echo esc_attr( $value ); ?>"
					class="webpCheckbox__input"
					<?php echo ( in_array( $value, $option['value'] ) ) ? 'checked' : ''; ?>
					<?php echo ( in_array( $value, $option['disabled'] ) ) ? 'disabled' : ''; ?>>
				<label for="webpc-<?php echo esc_attr( $index ); ?>-<?php echo esc_attr( $value ); ?>"></label>
			</td>
			<td>
				<label for="webpc-<?php echo esc_attr( $index ); ?>-<?php echo esc_attr( $value ); ?>"
					class="webpCheckbox__label"
				>
					<?php echo wp_kses_post( $label ); ?>
				</label>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
