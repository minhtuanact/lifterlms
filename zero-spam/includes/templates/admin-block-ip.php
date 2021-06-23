<?php
/**
 * Block IP.
 *
 * @package ZeroSpam
 * @since 5.0.0
 */
?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>"<?php if ( ! empty( $location_form ) ) : ?> class="zerospam-block-location-form"<?php endif; ?>>
<?php wp_nonce_field( 'zerospam', 'zerospam' ); ?>
<input type="hidden" name="action" value="add_blocked_ip" />
<input type="hidden" name="redirect" value="<?php echo esc_url( ZeroSpam\Core\Utilities::current_url() ); ?>" />

<?php if ( empty( $location_form ) ) : ?>
	<label for="blocked-ip">
		<?php _e( 'IP Address', 'zerospam' ); ?>
		<input
			type="text"
			name="blocked_ip"
			value="<?php if( ! empty( $_REQUEST['ip'] ) ) : echo esc_attr( $_REQUEST['ip'] ); endif; ?>"
			placeholder="e.g. xxx.xxx.x.x"
		/>
	</label>
<?php else: ?>
	<label for="location-type">
		<?php _e( 'Location Type', 'zerospam' ); ?>
		<select id="location-type" name="key_type">
			<option value="country_code"><?php _e( 'Country Code', 'zerospam' ); ?></option>
			<option value="region_code"><?php _e( 'Region Code', 'zerospam' ); ?></option>
			<option value="city"><?php _e( 'City Name', 'zerospam' ); ?></option>
			<option value="zip"><?php _e( 'Zip/Postal Code', 'zerospam' ); ?></option>
		</select>
	</label>

	<label for="location-key">
		<?php _e( 'Location Key', 'zerospam' ); ?>
		<input
			id="location-key"
			type="text"
			name="blocked_key"
			value=""
			placeholder="ex. US"
		/>
	</label>
<?php endif; ?>

<label for="blocked-type"><?php _e( 'Type', 'zerospam' ); ?>
	<select id="blocked-type" name="blocked_type">
		<option value="temporary"><?php _e( 'Temporary', 'zerospam' ); ?></option>
		<option value="permanent"><?php _e( 'Permanent', 'zerospam' ); ?></option>
	</select>
</label>

<label for="blocked-reason">
	<?php _e( 'Reason', 'zerospam' ); ?>
	<input type="text" id="blocked-reason" name="blocked_reason" value="" placeholder="<?php _e( 'e.g. Spammed form', 'zerospam' ); ?>" />
</label>

<label for="blocked-start-date">
	<?php esc_html_e( 'Start Date', 'zerospam' ); ?>
	<input
		type="datetime-local"
		id="blocked-start-date"
		name="blocked_start_date"
		value=""
		placeholder="<?php echo esc_attr( __( 'Optional', 'zerospam' ) ); ?>"
	/>
</label>

<label for="blocked-end-date">
	<?php _e( 'End Date', 'zerospam' ); ?>
	<input type="datetime-local" id="blocked-end-date" name="blocked_end_date" value="" placeholder="<?php _e( 'Optional', 'zerospam' ); ?>" />
</label>

<input type="submit" class="button button-primary" value="<?php _e( 'Add/Update Blocked IP', 'zerospam' ); ?>" />

</form>
