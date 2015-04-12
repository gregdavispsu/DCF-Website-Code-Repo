<?php

/**
 * Register our template paths
 *
 * @since 1.0
 *
 * @param array $paths existing template paths
 *
 * @return array
 */
function itegfp_add_template_paths( $paths = array() ) {
	$paths[] = ITEGFP::$dir . 'lib/templates';

	return $paths;
}

add_filter( 'it_exchange_possible_template_paths', 'itegfp_add_template_paths' );

/**
 * Log a user in when they register with GravityForms.
 *
 * @since 1.0
 *
 * @param $user_id  int
 * @param $config   array
 * @param $lead     array
 * @param $password string
 */
function itegfp_log_user_in_on_registration( $user_id, $config, $lead, $password ) {

	$user = get_user_by( 'id', $user_id );

	wp_signon( array(
		'user_login'    => $user->user_login,
		'user_password' => $password
	), is_ssl() );
}

add_action( 'gform_user_registered', 'itegfp_log_user_in_on_registration', 10, 4 );

/**
 * Enqueue Gravity Forms JS on checkout pages
 * with a Gravity Form Checkout Info product.
 *
 * @since 1.3
 */
function itegfp_enqueue_registration_scripts() {

	if ( ! itegfp_override_registration() ) {
		return;
	}

	$form_ids = array( itegfp_override_registration() );

	$req = it_exchange_get_next_purchase_requirement();

	if ( $req && $req['slug'] == 'logged-in' || it_exchange_is_page( 'registration' ) ) {
		require_once( GFCommon::get_base_path() . "/form_display.php" );

		foreach ( $form_ids as $form_id ) {
			$form = GFFormsModel::get_form_meta( $form_id );

			if ( it_exchange_in_superwidget() ) {
				GFFormDisplay::print_form_scripts( $form, true );

				wp_enqueue_script( 'gform_gravityforms' );
				wp_print_scripts( array( 'gform_gravityforms' ) );
			} else {
				GFFormDisplay::enqueue_form_scripts( $form, true );

				wp_enqueue_script( 'gform_gravityforms' );
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'itegfp_enqueue_registration_scripts' );
add_action( 'it_exchange_super_widget_ajax_top', 'itegfp_enqueue_registration_scripts' );

/**
 * Display the add-on's transaction method name.
 *
 * @since 1.0
 *
 * @param $name string
 *
 * @return string
 */
function itegfp_method_name( $name ) {
	$options = it_exchange_get_option( 'addon_gravity_forms' );

	return $options['title'];
}

add_filter( 'it_exchange_get_transaction_method_name_gravity-forms-exchange', 'itegfp_method_name', 9 );

/**
 * Add a select dropdown in Gravity Form's 'product' field settings.
 *
 * @since 1.0
 *
 * @param $position int
 * @param $form_id  int
 */
function itegfp_standard_settings_product( $position, $form_id ) {
	if ( $position === 25 ): ?>
		<li class="it_gravity_forms_exchange_product field_setting">
			<label for="field_exchange_product">
				<?php _e( 'Connect to iThemes Exchange Product', ITEGFP::SLUG ); ?> <?php gform_tooltip( 'it_gravity_forms_product' ); ?>
			</label>
			<select id="field_exchange_product" name="products[0][id]" class="it_gravity_forms_product_select">
				<?php if ( $products = it_exchange_get_products( array( 'posts_per_page' => - 1 ) ) ): ?>
					<option value=""><?php _e( 'Select a product...', ITEGFP::SLUG ); ?></option>
					<?php foreach ( $products as $product ): ?>
						<?php $product_util = new ITEGFP_Utils_Product( $product ); ?>
						<option value="<?php echo $product_util->get_ID(); ?>" data-price="<?php echo $product_util->get_price(); ?>">
							<?php echo $product_util->get_title(); ?>
						</option>
					<?php endforeach; ?>
				<?php else: ?>
					<option value="0">
						<?php _e( 'No products available', ITEGFP::SLUG ); ?>
					</option>
				<?php endif; ?>
			</select>
		</li>
	<?php
	endif;
}

add_action( 'gform_field_standard_settings', 'itegfp_standard_settings_product', 10, 2 );

/**
 * Add a select dropdown in Gravity Form's 'option' field settings.
 *
 * @since 1.0
 *
 * @param $position int
 * @param $form_id  int
 */
function itegfp_standard_settings_options( $position, $form_id ) {
	if ( $position === 25 ): ?>
		<li class="it_gravity_forms_exchange_variant field_setting">
			<label for="field_exchange_variant">
				<?php _e( 'This is an iThemes Exchange Product', ITEGFP::SLUG ); ?> <?php gform_tooltip( 'it_gravity_forms_variant' ); ?>
			</label>
			<button id="field_exchange_variant" class="button button-small button-default it_gravity_forms_variant_load" type="button">
				<?php _e( 'Load options for this product', ITEGFP::SLUG ); ?>
			</button>
		</li>
	<?php
	endif;
}

add_action( 'gform_field_standard_settings', 'itegfp_standard_settings_options', 50, 2 );

/**
 * Inject a JS script for displaying the select dropdown of iThemes Exchange products in the admin page.
 *
 * @since 1.0
 */
function itegfp_editor_script() {
	?>
	<script type="text/javascript">
		(function ($) {
			fieldSettings['product'] += ', .it_gravity_forms_exchange_product';
			fieldSettings['option'] += ', .it_gravity_forms_exchange_variant';

			$(document).bind('gform_load_field_settings', function (event, field, form) {
				switch (field.type) {
					case 'product':
						$('select.it_gravity_forms_product_select').val(field['itExchangeProduct']).trigger('change');
						break;

					case 'option':
						break;
				}
			});
		}(jQuery));
	</script>
<?php
}

add_action( 'gform_editor_js', 'itegfp_editor_script' );

/**
 * Add a tooltip to the iThemes Exchange products.
 *
 * @since 1.0
 *
 * @param $tooltips array
 *
 * @return array
 */
function itegfp_tooltips( $tooltips ) {
	$tooltips['it_gravity_forms_product'] = sprintf( '<h6>%s</h6>%s', __( 'iThemes Exchange Products', ITEGFP::SLUG ), __( 'Connect this form to an iThemes Exchange product.', ITEGFP::SLUG ) );
	$tooltips['it_gravity_forms_variant'] = sprintf( '<h6>%s</h6>%s', __( 'iThemes Exchange Options', ITEGFP::SLUG ), __( 'Load iThemes Exchange product variants.', ITEGFP::SLUG ) );

	return $tooltips;
}

add_filter( 'gform_tooltips', 'itegfp_tooltips' );

/**
 * Make an Exchange purchase when a form with a product field is submitted.
 *
 * @since 1.0
 *
 * @param $entry array
 * @param $form  array
 */
function itegfp_exchange_purchase( $entry, $form ) {

	$is_exchange = false;
	$email       = '';

	foreach ( $form['fields'] as $field ) {

		if ( $field['type'] == 'email' ) {
			$email = $entry[ absint( $field['id'] ) ];
		}

		if ( ! empty( $field['itExchangeProduct'] ) ) {
			$is_exchange = true;
		}
	}

	if ( ! $is_exchange ) {
		return;
	}

	$cart = new ITEGFP_Utils_Cart();

	// Save the current items in the cart.
	$cart->cache();

	// Clear the cart and add new items to it.
	$cart->clear();

	// Add all items in Gravity Form's product field.
	$cart->add_products( $entry, $form );

	// Guest checkout
	if ( $email && ! is_user_logged_in() && function_exists( 'it_exchange_init_guest_checkout_session' ) ) {
		it_exchange_init_guest_checkout_session( $email );

		itegfp_guest_checkout_bump_session();
	}

	$transaction_obj = it_exchange_generate_transaction_object();
	$tid             = false;

	if ( $transaction_obj ) {
		$customer    = it_exchange_get_current_customer();
		$customer_id = isset( $customer->ID ) ? $customer->ID : false;

		$status     = isset( $entry['payment_status'] ) ? $entry['payment_status'] : 'paid';
		$ite_status = itegfp_translate_gravity_forms_payment_status( $status );

		$tid = it_exchange_add_transaction( 'gravity-forms-exchange', $entry['id'], $ite_status, $customer_id, $transaction_obj );
	}

	// Clear the shopping cart session.
	$cart->clear();

	// Restore the old shopping cart session.
	$cart->restore();

	$options = it_exchange_get_option( 'addon_gravity_forms' );

	if ( $options['redirect_to_confirmation'] ) {
		wp_redirect( it_exchange_get_transaction_confirmation_url( $tid ) );

		exit;
	}
}

add_action( 'gform_after_submission', 'itegfp_exchange_purchase', 10, 2 );

/**
 * Display a "View Entry" link on the GF method name on the Payments screen.
 *
 * @since 1.0
 *
 * @param WP_Post $post
 */
function itegfp_display_view_entry_link_on_payment_page( $post ) {

	$transaction = it_exchange_get_transaction( $post );

	if ( $transaction->transaction_method != 'gravity-forms-exchange' ) {
		return;
	}

	$lead_id = $transaction->get_transaction_meta( 'method_id' );
	$lead    = RGFormsModel::get_lead( $lead_id );
	$form_id = $lead['form_id'];

	$url = "admin.php?page=gf_entries&view=entry&id=$form_id&lid=$lead_id";
	echo "<span style='font-size: 12px'>&nbsp;<a href='{$url}'>" . __( 'View Entry', ITEGFP::SLUG ) . "</a></span>";
}

add_action( 'it_exchange_transaction_print_metabox_after_transaction_method_name', 'itegfp_display_view_entry_link_on_payment_page' );

/**
 * Mark this transaction method as okay to manually change transactions
 *
 * @since 1.0
 */
add_filter( 'it_exchange_gravity-forms-exchange_transaction_status_can_be_manually_changed', '__return_true' );

/**
 * Returns status options
 *
 * @since 0.3.6
 *
 * @return array
 */
function itegfp_get_default_status_options() {
	$options = array(
		'pending'  => _x( 'Pending', 'Transaction Status', 'it-l10n-ithemes-exchange' ),
		'paid'     => _x( 'Paid', 'Transaction Status', 'it-l10n-ithemes-exchange' ),
		'refunded' => _x( 'Refunded', 'Transaction Status', 'it-l10n-ithemes-exchange' ),
		'voided'   => _x( 'Voided', 'Transaction Status', 'it-l10n-ithemes-exchange' ),
	);

	return $options;
}

add_filter( 'it_exchange_get_status_options_for_gravity-forms-exchange_transaction', 'itegfp_get_default_status_options' );

/**
 * Gets the interpreted transaction status from valid transaction statuses
 *
 * @since 1.0
 *
 * @param string $status the string of the gravity forms transaction
 *
 * @return string transaction status
 */
function itegfp_transaction_status_label( $status ) {

	$statuses = itegfp_get_default_status_options();

	return isset( $statuses[ $status ] ) ? $statuses[ $status ] : __( "Unknown", ITEGFP::SLUG );
}

add_filter( 'it_exchange_transaction_status_label_gravity-forms-exchange', 'itegfp_transaction_status_label' );


/**
 * Returns a boolean. Is this transaction a status that warrants delivery of any products attached to it?
 *
 * @since 1.0
 *
 * @param boolean $cleared passed in through WP filter. Ignored here.
 * @param mixed   $transaction
 *
 * @return boolean
 */
function itegfp_transaction_is_cleared_for_delivery( $cleared, $transaction ) {
	$valid_stati = array( 'paid' );

	return in_array( it_exchange_get_transaction_status( $transaction ), $valid_stati );
}

add_filter( 'it_exchange_gravity-forms-exchange_transaction_is_cleared_for_delivery', 'itegfp_transaction_is_cleared_for_delivery', 10, 2 );

/**
 * Refund payment when GF refunds.
 *
 * @since 1.0
 *
 * @param array $entry
 * @param array $action
 */
function itegfp_refund_payment( $entry, $action ) {
	$transaction = itegfp_get_transaction_by_method_id( $entry['id'] );

	$transaction->add_refund( $action['amount'] );
}

add_action( 'gform_post_payment_refunded', 'itegfp_refund_payment', 10, 2 );

/**
 * Update a payment's status when the GF transaction changes.
 *
 * @since 1.0
 *
 * @param array $entry
 * @param array $action
 */
function itegfp_update_payment_status( $entry = array(), $action = array() ) {

	$ite_status = itegfp_translate_gravity_forms_payment_status( $action['payment_status'] );

	$transaction = itegfp_get_transaction_by_method_id( $entry['id'] );

	it_exchange_update_transaction_status( $transaction, $ite_status );
}

add_action( 'gform_post_payment_completed', 'itegfp_update_payment_status', 10, 2 );
add_action( 'gform_post_payment_refunded', 'itegfp_update_payment_status', 10, 2 );

/**
 * Back-compat layer for changing payment statuses.
 *
 * @since 1.0
 *
 * @param  array  $feed
 * @param  array  $entry
 * @param  string $status
 */
function itegfp_update_payment_status_back_compat( $feed, $entry, $status ) {
	itegfp_update_payment_status( $entry, array( 'payment_status' => $status ) );
}

add_action( 'gform_post_payment_status', 'itegfp_update_payment_status_back_compat', 10, 3 );