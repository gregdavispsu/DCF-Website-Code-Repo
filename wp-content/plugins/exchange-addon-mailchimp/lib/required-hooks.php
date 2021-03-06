<?php
/**
 * iThemes Exchange MailChimp Add-on
 * @package IT_Exchange_Addon_MailChimp
 * @since 1.0.0
*/

/**
 * Adds actions to the plugins page for the iThemes Exchange MailChimp plugin
 *
 * @since 1.0.0
 *
 * @param array $meta Existing meta
 * @param string $plugin_file the wp plugin slug (path)
 * @param array $plugin_data the data WP harvested from the plugin header
 * @param string $context 
 * @return array
*/
function it_exchange_mailchimp_plugin_row_actions( $actions, $plugin_file, $plugin_data, $context ) {
	
	$actions['setup_addon'] = '<a href="' . get_admin_url( NULL, 'admin.php?page=it-exchange-addons&add-on-settings=mailchimp' ) . '">' . __( 'Setup Add-on', 'it-l10n-exchange-addon-mailchimp' ) . '</a>';
	
	return $actions;
	
}
add_filter( 'plugin_action_links_exchange-addon-mailchimp/exchange-addon-mailchimp.php', 'it_exchange_mailchimp_plugin_row_actions', 10, 4 );

/**
 * Enqueues any scripts we need on the backend during a mail chimp setup
 *
 * @since 0.1.0
 *
 * @return void
*/
function it_exchange_stripe_addon_admin_enqueue_scripts() {
	wp_enqueue_script( 'mailchimp-addon-js', ITUtility::get_url_from_file( dirname( __FILE__ ) ) . '/admin/js/mailchimp-addon.js', array( 'jquery' ) );
}
add_action( 'admin_enqueue_scripts', 'it_exchange_stripe_addon_admin_enqueue_scripts' );


// adds an email to the mailchimp subscription list
function it_exchange_sign_up_email_to_mailchimp_list() {
	
	$settings = it_exchange_get_option( 'addon_mailchimp' );
	
	if( ! empty( $settings['mailchimp-api-key'] ) ) {
		
		if ( ! empty( $_POST['it-exchange-mailchimp-signup'] ) || empty( $settings['mailchimp-optin'] ) ) {
							
			if ( ! empty( $_POST['email'] ) )
				$email = trim( $_POST['email'] );
			else
				$email = false;
				
			if ( ! empty( $_POST['first_name'] ) )
				$fname = trim( $_POST['first_name'] );
			else
				$fname = false;
				
			if ( ! empty( $_POST['last_name'] ) )
				$lname = trim( $_POST['last_name'] );
			else
				$lname = false;
									
			if ( is_email( $email ) ) {
				
				try {			
					$mc = new Mailchimp( trim( $settings['mailchimp-api-key'] ) );
					$double_optin = empty( $settings['mailchimp-double-optin'] ) ? false : true;
					
					$args = array();
					if ( ! empty( $fname ) )
						$args['FNAME'] = $fname;
					if ( ! empty( $lname ) )
						$args['LNAME'] = $lname;
				
					$return = $mc->lists->subscribe( $settings['mailchimp-list'], array( 'email' => $email ), $args, 'html', $double_optin );
				}
				catch ( Exception $e ) {
					$return = false;
				}
			
				return $return;
			}
	
		}
			
	}

	return false;
}
add_action( 'it_exchange_register_user', 'it_exchange_sign_up_email_to_mailchimp_list' );

/**
 * This function subscribes a guest checkout customer to a MailChimp list
 *
 * @since 1.0.0
 *
 * @param string $email Email address of guest checkout user
 * @return void
*/
function it_exchange_addon_mailchimp_init_guest_checkout( $email ) {
	
	$settings = it_exchange_get_option( 'addon_mailchimp' );

	if( ! empty( $settings['mailchimp-api-key'] ) ) {
		
		if ( ! empty( $_POST['it-exchange-mailchimp-signup'] ) || empty( $settings['mailchimp-optin'] ) ) {
							
			if ( ! empty( $email ) )
				$email = trim( $email );
			else
				$email = false;
													
			if ( is_email( $email ) ) {
				
				try {		
					$mc = new Mailchimp( trim( $settings['mailchimp-api-key'] ) );
					$double_optin = empty( $settings['mailchimp-double-optin'] ) ? false : true;
					$return = $mc->lists->subscribe( $settings['mailchimp-list'], array( 'email' => $email ), array(), 'html', $double_optin );
				}
				catch ( Exception $e ) {
					$return = false;
				}
			
				return $return;
			
			}
	
		}
				
	}
}
add_action( 'it_exchange_init_guest_checkout', 'it_exchange_addon_mailchimp_init_guest_checkout' );

/**
 * This function adds our registration field to the list of fields included in the content-registration template part
 *
 * @since 1.0.0
 *
 * @param array $fields existing fields
 * @return array
*/
function it_exchange_mailchimp_sign_up_add_field_to_registration_template_parts( $fields ) { 

    /** 
     * We want to add our field right before the save button
     * 1) Find the save button
     * 2) Spice our value in right before the save button
     * 3) In the event that the save button wasn't found, just tack onto the end
    */

    $key = array_search( 'password2', $fields );
    if ( false === $key  )
        $fields[] = 'mailchimp-signup';
    else
        array_splice( $fields, ++$key, 0, array( 'mailchimp-signup' ) );

    return $fields;
}
add_filter( 'it_exchange_get_content_registration_fields_elements', 'it_exchange_mailchimp_sign_up_add_field_to_registration_template_parts' );
add_filter( 'it_exchange_get_super_widget_registration_fields_elements', 'it_exchange_mailchimp_sign_up_add_field_to_registration_template_parts' );


/**
 * This function adds our guest checkout field to the list of fields included in the content-guest-checkout template part
 *
 * @since 1.0.0
 *
 * @param array $fields existing fields
 * @return array
*/
function it_exchange_mailchimp_get_super_widget_guest_checkout_fields_elements( $fields ) { 

    /** 
     * We want to add our field right before the save button
     * 1) Find the save button
     * 2) Spice our value in right before the save button
     * 3) In the event that the save button wasn't found, just tack onto the end
    */

    $key = array_search( 'email', $fields );
    if ( false === $key  )
        $fields[] = 'mailchimp-signup';
    else
        array_splice( $fields, ++$key, 0, array( 'mailchimp-signup' ) );

    return $fields;
}
add_filter( 'it_exchange_get_super_widget_guest_checkout_fields_elements', 'it_exchange_mailchimp_get_super_widget_guest_checkout_fields_elements' );


/**
 * This function adds our guest checkout field to the list of fields included in the content-guest-checkout template part
 *
 * @since 1.0.0
 *
 * @param array $fields existing fields
 * @return array
*/
function it_exchange_mailchimp_get_super_widget_guest_checkout_actions_elements( $fields ) { 

    /** 
     * We want to add our field right before the save button
     * 1) Find the save button
     * 2) Spice our value in right before the save button
     * 3) In the event that the save button wasn't found, just tack onto the end
    */

    $key = array_search( 'continue', $fields );
    if ( false === $key  )
        $fields[] = 'mailchimp-signup';
    else
        array_splice( $fields, ++$key, 0, array( 'mailchimp-signup' ) );

    return $fields;
}
add_filter( 'it_exchange_get_content-checkout-guest-checkout-purchase-requirement_actions_elements', 'it_exchange_mailchimp_get_super_widget_guest_checkout_actions_elements' );

/**
 * This function tells Exchange to look in a directory in the MailChimp add-on for template parts
 *
 * @since 1.0.0
 *
 * @param array $template_paths existing template paths. Exchange core paths will be added after this filter.
 * @param array $template_names the template part names we're looking for right now.
 * @return array
*/
function it_exchange_mailchimp_add_template_directory( $template_paths, $template_names ) { 

    /** 
     * Use the template_names array to target a specific template part you want to add
     * In this example, we're adding the following template part: content-registration/fields/details/mailchimp-signup.php and super-widget-registration/fields/details/mailchimp-signup.php
     * So we're going to only add our templates directory if Exchange is looking for that part.
    */
    if ( ! in_array( 'content-registration/elements/mailchimp-signup.php', $template_names )
		&& ! in_array( 'content-checkout/elements/purchase-requirements/guest-checkout/elements/mailchimp-signup.php', $template_names )
		&& ! in_array( 'super-widget-registration/elements/mailchimp-signup.php', $template_names )
		&& ! in_array( 'super-widget-guest-checkout/elements/mailchimp-signup.php', $template_names ) ) 
        return $template_paths;

    /** 
     * If we are looking for the mailchimp-signup template part, go ahead and add our add_ons directory to the list
     * No trailing slash
    */
    $template_paths[] = dirname( __FILE__ ) . '/templates';

    return $template_paths;
}
add_filter( 'it_exchange_possible_template_paths', 'it_exchange_mailchimp_add_template_directory', 10, 2 );

function it_exchange_mailchimp_subscribe_product_lists_on_successful_transactions( $transaction_id ) {
	if ( !empty( $transaction_id ) ) {
		$settings = it_exchange_get_option( 'addon_mailchimp' );
		if ( !empty( $settings['mailchimp-api-key'] ) ) {
			try {
				$mc = new Mailchimp( trim( $settings['mailchimp-api-key'] ) );
			    $cart_object = get_post_meta( $transaction_id, '_it_exchange_cart_object', true );
			    $customer = it_exchange_get_transaction_customer( $transaction_id );
				if ( !empty( $cart_object->products ) ) {
					foreach ( $cart_object->products as $product ) {
						if ( it_exchange_product_supports_feature( $product['product_id'], 'mailchimp', array( 'setting' => 'list-id' ) ) 
							&& it_exchange_product_has_feature( $product['product_id'], 'mailchimp', array( 'setting' => 'list-id' ) ) ) {
							$list_id = it_exchange_get_product_feature( $product['product_id'], 'mailchimp', array( 'setting' => 'list-id' ) );
							$double_optin = it_exchange_get_product_feature( $product['product_id'], 'mailchimp', array( 'setting' => 'double-optin' ) );
							$double_optin = empty( $double_optin ) ? false : true; //want to make sure it's boolean at this point
							$args = array();
							if ( ! empty( $customer->data->first_name ) )
								$args['FNAME'] = $customer->data->first_name;
							if ( ! empty( $customer->data->last_name ) )
								$args['LNAME'] = $customer->data->last_name;
							$mc->lists->subscribe( $list_id, array( 'email' => $customer->data->user_email ), $args, 'html', $double_optin );
						}
					}
				}
			}
			catch ( Exception $e ) {
				//nothing to do here.
			}
		}
	}
	return $transaction_id;
}
add_filter( 'it_exchange_add_transaction_success', 'it_exchange_mailchimp_subscribe_product_lists_on_successful_transactions', 20 );