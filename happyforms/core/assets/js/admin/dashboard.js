( function( $, settings ) {

	var happyForms = window.happyForms || {};
	window.happyForms = happyForms;

	happyForms.dashboard = {
		init: function() {
			$( document ).on( 'click', '.happyforms-editor-button', this.onEditorButton.bind( this ) );
			$( '.happyforms-dialog__button' ).on( 'click', this.onDialogButton.bind( this ) );
			$( '.happyforms-notice:not(.one-time)' ).on( 'click', '.notice-dismiss', this.onNoticeDismiss.bind( this ) );
			$( document ).on( 'click', '.happyforms-modal__frame', this.lockModalEvent );
		},

		onEditorButton: function( e ) {
			var title = $( e.currentTarget ).attr( 'data-title' );

			$('#happyforms-modal').dialog( {
				title: title,
				dialogClass: 'happyforms-dialog wp-dialog',
				draggable: false,
				width: 'auto',
				modal: true,
				resizable: false,
				closeOnEscape: true,
				position: {
					my: 'center',
					at: 'center',
					of: $(window)
				}
			} );
		},

		onDialogButton: function( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();

			var formId = $( '#happyforms-dialog-select' ).val();
			if ( ! formId ) {
				return false;
			}

			var shortcode = settings.shortcode.replace( 'ID', formId );
			window.parent.send_to_editor( shortcode );
			$( '#happyforms-modal' ).dialog( 'close' );
			$( '#happyforms-dialog-select' ).val( '' );

			if ( editor = this.getCurrentEditor() ) {
				editor.focus();
			}
		},

		getCurrentEditor: function() {
			var editor,
				hasTinymce = typeof tinymce !== 'undefined',
				hasQuicktags = typeof QTags !== 'undefined';

			if ( ! wpActiveEditor ) {
				if ( hasTinymce && tinymce.activeEditor ) {
					editor = tinymce.activeEditor;
					wpActiveEditor = editor.id;
				} else if ( ! hasQuicktags ) {
					return false;
				}
			} else if ( hasTinymce ) {
				editor = tinymce.get( wpActiveEditor );
			}

			return editor;
		},

		onNoticeDismiss: function( e ) {
			e.preventDefault();

			var $target = $( e.target );
			var $parent = $target.parents( '.notice' ).first();
			var id = $parent.attr( 'id' ).replace( 'happyforms-notice-', '' );
			var nonce = $parent.data( 'nonce' );

			$.post( ajaxurl, {
					action: 'happyforms_hide_notice',
					nid: id,
					nonce: nonce
				}
			);
		},

		openModal: function( id ) {
			var self = this;

			$.get( ajaxurl, {
				action: settings.actionModalFetch,
				id: id, 
			}, function( html ) {
				$( 'body' ).addClass( 'modal-open' );
				$( 'body' ).append( html );

				$( document ).one( 'click', '.happyforms-modal__overlay', self.closeModal );
				$( document ).one( 'click', '.happyforms-modal__dismiss', self.closeModal );
			} );
		},

		closeModal: function() {
			if ( $( '[data-happyforms-modal-dismissible]' ).length ) {
				var id = $( '[data-happyforms-modal-id]' ).attr( 'data-happyforms-modal-id' );
				
				$.post( ajaxurl, {
					action: settings.actionModalDismiss,
					id: id, 
				} );
			}
			
			$( '.happyforms-modal__overlay' ).remove();
			$( 'body' ).removeClass( 'modal-open' );
		},

		lockModalEvent: function( e ) {
			e.stopPropagation();
		},
	};

	$( function() {
		happyForms.dashboard.init();
	} );

} )( jQuery, _happyFormsAdmin );
