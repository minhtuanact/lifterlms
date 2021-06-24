/**
 * Internal dependencies
 */
import { domReady, apiRequest } from 'utils';

// Wait for DOM.
domReady( () => {
	const containerEl = document.getElementById( 'edds-stripe-connect-account' );
	const actionsEl = document.getElementById( 'edds-stripe-disconnect-reconnect' );

	if ( ! containerEl ) {
		return;
	}

	return apiRequest( 'edds_stripe_connect_account_info', {
		...containerEl.dataset,
	} )
		.done( ( response ) => {
			containerEl.innerHTML = response.message;
			containerEl.classList.add( `notice-${ response.status }` );
			if ( response.actions ) {
				actionsEl.innerHTML = response.actions;
			}
		} )
		.fail( ( error ) => {
			containerEl.innerHTML = error.message;
			containerEl.classList.add( 'notice-error' );
		} );
} );
