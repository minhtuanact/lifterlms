<?php
/**
 * This file is used to add metabox in case of Gutenberg Editor.
 *
 * @package contact-bank/lib
 */

	$get_contact_bank = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT *  FROM ' . $wpdb->prefix . 'contact_bank_meta
			INNER JOIN ' . $wpdb->prefix . 'contact_bank ON ' . $wpdb->prefix . 'contact_bank_meta.meta_id = ' . $wpdb->prefix . 'contact_bank.id WHERE ' . $wpdb->prefix . 'contact_bank.type = %s and ' . $wpdb->prefix . 'contact_bank_meta.meta_key = %s ORDER BY meta_id DESC',
			'form',
			'form_data'
		)
	);// WPCS: db call ok, cache ok.

	$unserialized_forms_data_array = array();
	foreach ( $get_contact_bank as $key ) {
		$unserialized_data                = array();
		$unserialized_data                = maybe_unserialize( $key->meta_value );
		$unserialized_data['old_form_id'] = $key->old_form_id;
		$unserialized_data['id']          = $key->id;
		$unserialized_data['meta_key']    = $key->meta_key;// WPCS: slow query ok.
		$unserialized_data['meta_id']     = $key->meta_id;
		array_push( $unserialized_forms_data_array, $unserialized_data );
	}
	?>
<style>
.col-md-6 {
	width: 46% !important;
	}
</style>
<div class="form-body">
	<div class="form-group">
		<label class="control-label">
			<?php echo esc_attr( $cb_select_form ); ?> :
		<span class="required" aria-required="true">*</span>
	</label>
	<select id="add_contact_form_id" name="add_contact_form_id" class="form-control">
		<option value=""><?php echo esc_attr( $cb_shortcode_button_select_form ); ?>  </option>
		<?php
		foreach ( $unserialized_forms_data_array as $data ) {
			?>
			<option value="<?php echo intval( $data['old_form_id'] ); ?>"><?php echo '' !== $data['form_title'] ? esc_attr( $data['form_title'] ) : 'Untitled Form'; ?></option>
			<?php
		}
		?>
	</select>
	<i class="controls-description"><?php echo esc_attr( __( 'Choose Source Type to generate shortcode', 'contact-bank' ) ); ?></i>
</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">
					<?php echo esc_attr( $cb_form_title ); ?> :
				<span class="required" aria-required="true">*</span>
			</label>
			<select id="add_contact_form_id_title" name="add_contact_form_id_title" class="form-control">
				<option value="show"><?php echo esc_attr( $cb_shortcode_button_show ); ?></option>
				<option value="hide"><?php echo esc_attr( $cb_shortcode_button_hide ); ?></option>
			</select>
			<i class="controls-description"><?php echo esc_attr( __( 'Choose Form Title to generate shortcode', 'contact-bank' ) ); ?></i>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">
				<?php echo esc_attr( $cb_form_description ); ?> :
				<span class="required" aria-required="true">*</span>
			</label>
			<select id="add_contact_form_description" name="add_contact_form_description" class="form-control">
				<option value="show"><?php echo esc_attr( $cb_shortcode_button_show ); ?></option>
				<option value="hide"><?php echo esc_attr( $cb_shortcode_button_hide ); ?></option>
			</select>
			<i class="controls-description"><?php echo esc_attr( __( 'Choose Form description to generate shortcode', 'contact-bank' ) ); ?></i>
		</div>
	</div>
</div>
<div class="line-separator"></div>
<input type="button" class="btn vivid-green" name="ux_btn_generate_shortcode" id="ux_btn_generate_shortcode" value="Add Shortcode" onclick="add_shortcode_block_contact_bank()">
</div>
<script>
function add_shortcode_block_contact_bank(){
	var form_id = jQuery("#add_contact_form_id").val();
	var form_title = jQuery("#add_contact_form_id_title").val();
	var form_description = jQuery("#add_contact_form_description").val();
	var shortcode_content = "";
	if (form_id == "")
	{
		alert("<?php echo esc_attr( $cb_shotcode_button_choose_form ); ?>");
	} else{
		shortcode_content = "[contact_bank form_id=\"" + form_id + "\" form_title=\"" + form_title + "\" form_description=\"" + form_description + "\"][/contact_bank]";
		let block = wp.blocks.createBlock( 'core/paragraph', { content: shortcode_content } );
		wp.data.dispatch( 'core/editor' ).insertBlocks( block );
	}
}
</script>
