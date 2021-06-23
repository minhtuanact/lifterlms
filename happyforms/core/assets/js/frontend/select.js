( function( $ ) {

	HappyForms.parts = HappyForms.parts || {};

	HappyForms.parts.select = {
		init: function( options ) {
			this.type = this.$el.data( 'happyforms-type' );

			this.$input = $( '[data-serialize]', this.$el );
			var $visualInput = $( 'input[type="text"]', this.$el );
			var $select = $( '.happyforms-custom-select-dropdown', this.$el );

			$visualInput.happyFormsSelect( {
				$input: this.$input,
				$select: $select,
				searchable: $visualInput.attr( 'data-searchable' ),
				required: 'undefined' === typeof this.$el.data( 'happyforms-required' ) ? false : true,
			});

			this.$input.on( 'blur', this.onBlur.bind(this) );
			this.initTooltip();
		},
	};

} )( jQuery );