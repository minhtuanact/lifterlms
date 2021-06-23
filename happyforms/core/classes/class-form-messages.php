<?php

class HappyForms_Form_Messages {

	private static $instance;

	private $form = null;

	private $default_validation_messages = array();

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();
		self::$instance->define_validation_defaults();

		return self::$instance;
	}

	public function hook() {
		add_filter( 'happyforms_meta_fields', array( $this, 'meta_fields' ) );
		add_action( 'happyforms_do_messages_control', array( happyforms_get_setup(), 'do_control' ), 10, 3 );
		add_filter( 'happyforms_validate_meta_data', array( $this, 'validate_validation_messages' ), 10 );
	}

	public function get_fields() {

		$fields = array(
			'words_label_min' => array(
				'default' => __( 'Min words', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'words_label_max' => array(
				'default' => __( 'Max words', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'characters_label_min' => array(
				'default' => __( 'Min characters', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'characters_label_max' => array(
				'default' => __( 'Max characters', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'no_results_label' => array(
				'default' => __( 'Nothing found', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'number_min_invalid' => array(
				'default' => __( "Oops. This number isn't big enough.", 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'number_max_invalid' => array(
				'default' => __( 'Oops. This number is too big.', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'optional_part_label' => array(
				'default' => __( '(optional)', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'required_field_label' => array(
				'default' => __( '', 'happyforms' ),
				'sanitize' => 'sanitize_text_field'
			),
		);

		$fields = apply_filters( 'happyforms_messages_fields', $fields );

		return $fields;
	}

	public function get_controls() {
		$controls = array(
			// control groupings
			1 => array (
				'type' => 'group_start',
				'group_type' => 'group',
				'group_id' => 'messages-view-alerts',
				'group_title' => __( 'Alerts', 'happyforms' ),
				'group_description' => __( 'These messages are shown to respondents at the very top of the form to communicate the form’s status.', 'happyforms' ),
			),
			2000 => array (
				'type' => 'group_end',
			),

			2001 => array (
				'type' => 'group_start',
				'group_type' => 'group',
				'group_id' => 'messages-view-buttons',
				'group_title' => __( 'Buttons', 'happyforms' ),
				'group_description' => __( 'The messages are shown to respondents as they fill out the form to help them trigger an action.', 'happyforms' ),
			),
			4000 => array (
				'type' => 'group_end',
			),
			4001 => array (
				'type' => 'group_start',
				'group_type' => 'group',
				'group_id' => 'messages-view-errors',
				'group_title' => __( 'Errors', 'happyforms' ),
				'group_description' => __( 'These messages are shown to respondents when they try to submit their reply but one or more fields has a mistake.', 'happyforms' ),
			),
			6000 => array (
				'type' => 'group_end',
			),
			6001 => array (
				'type' => 'group_start',
				'group_type' => 'group',
				'group_id' => 'messages-view-hints',
				'group_title' => __( 'Hints', 'happyforms' ),
				'group_description' => __( 'These messages are shown to respondents as they fill out the form to help them avoid mistakes.', 'happyforms' ),
			),
			8000 => array (
				'type' => 'group_end',
			),

			// individual controls
			4300 => array(
				'type' => 'text',
				'label' => __( 'Number too small', 'happyforms' ),
				'field' => 'number_min_invalid',
			),
			4320 => array(
				'type' => 'text',
				'label' => __( 'Number too big', 'happyforms' ),
				'field' => 'number_max_invalid',
			),
			6020 => array(
				'type' => 'text',
				'label' => __( "Search couldn't find anything", 'happyforms' ),
				'field' => 'no_results_label',
			),
			6040 => array(
				'type' => 'text',
				'label' => __( 'Minimum characters', 'happyforms' ),
				'field' => 'characters_label_min',
			),
			6060 => array(
				'type' => 'text',
				'label' => __( 'Maximum characters', 'happyforms' ),
				'field' => 'characters_label_max',
			),
			6080 => array(
				'type' => 'text',
				'label' => __( 'Minimum words', 'happyforms' ),
				'field' => 'words_label_min',
			),
			6100 => array(
				'type' => 'text',
				'label' => __( 'Maximum words', 'happyforms' ),
				'field' => 'words_label_max',
			),
			6010 => array(
				'type' => 'text',
				'label' => __( 'Question is optional', 'happyforms' ),
				'field' => 'optional_part_label',
			),
			6011 => array(
				'type' => 'text',
				'label' => __( 'Question is required', 'happyforms' ),
				'field' => 'required_field_label',
			),
		);

		$controls = happyforms_safe_array_merge( array(), $controls );
		$controls = apply_filters( 'happyforms_messages_controls', $controls );
		ksort( $controls, SORT_NUMERIC );

		return $controls;
	}

	/**
	 * Filter: add fields to form meta.
	 *
	 * @hooked filter happyforms_meta_fields
	 *
	 * @param array $fields Current form meta fields.
	 *
	 * @return array
	 */
	public function meta_fields( $fields ) {
		$fields = array_merge( $fields, $this->get_fields() );

		return $fields;
	}

	public function validate_validation_messages( $meta_data ) {
		$meta_fields = wp_list_pluck( $this->get_fields(), 'default' );

		foreach( $meta_fields as $key => $value ) {
			if ( empty( $meta_data[ $key ] ) ) {
				if ( 'optional_part_label' == $key ) {
					continue;
				}
				$meta_data[ $key ] = $meta_fields[ $key ];
			}
		}

		return $meta_data;
	}

	public function define_validation_defaults(){
		$this->default_validation_messages = happyforms_validation_messages()->get_default_messages();
	}

	public function get_default_validation_message( $key ) {
		if ( empty( $this->default_validation_messages ) ) {
			$this->define_validation_defaults();
		}

		return $this->default_validation_messages[ $key ];
	}

	public function get_validation_message(  $message, $message_key ) {
		if ( empty( $this->form[ $message_key ] ) ) {
			return $message;
		}

		return $this->form[ $message_key ];
	}

}

if ( ! function_exists( 'happyforms_get_messages' ) ):

function happyforms_get_messages() {
	return HappyForms_Form_Messages::instance();
}

endif;

happyforms_get_messages();
