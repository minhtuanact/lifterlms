<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<div class="happyforms-part-wrap">
		<?php happyforms_the_part_label( $part, $form ); ?>

		<?php if ( 'tooltip' !== $part['description_mode'] || 'hidden' === $part['label_placement'] ) : ?>
			<?php happyforms_print_part_description( $part ); ?>
		<?php endif; ?>

		<?php
		$options = happyforms_get_part_options( $part['options'], $part, $form );
		$is_searchable = count( $options ) > 5;
		$is_searchable = apply_filters( 'happyforms_is_dropdown_searchable', $is_searchable, $part, $form );
		$value = happyforms_get_part_value( $part, $form );
		$default_label = ( '' !== $value ) ? $options[$value]['label'] : '';
		$placeholder_text = $part['placeholder'];
		?>

		<div class="happyforms-part__el">
			<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>
			<div class="happyforms-custom-select">
				<div class="happyforms-part__select-wrap">
					<input type="hidden" name="<?php happyforms_the_part_name( $part, $form ); ?>" value="<?php echo $value; ?>" data-serialize />

					<input id="<?php happyforms_the_part_id( $part, $form ); ?>" type="text" value="<?php echo $default_label; ?>" placeholder="<?php echo $placeholder_text; ?>" data-searchable="<?php echo $is_searchable ? 'true' : 'false'; ?>" autocomplete="off" <?php happyforms_the_part_attributes( $part, $form ); ?> />

					<?php happyforms_select( $options, $part, $form ); ?>
				</div>
			</div>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>
		</div>
	</div>
</div>
