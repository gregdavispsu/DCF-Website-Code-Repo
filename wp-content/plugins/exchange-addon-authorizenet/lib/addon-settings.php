<?php
/**
 * Exchange will build your add-on's settings page for you and link to it from our add-on
 * screen. You are free to link from it elsewhere as well if you'd like... or to not use our API
 * at all. This file has all the functions related to registering the page, printing the form, and saving
 * the options. This includes the wizard settings. Additionally, we use the Exchange storage API to
 * save / retreive options. Add-ons are not required to do this.
*/

/**
 * This is the function registered in the options array when it_exchange_register_addon was called for Authorize.net
 *
 * It tells Exchange where to find the settings page
 *
 * @return void
*/
function it_exchange_authorizenet_addon_settings_callback() {
	$IT_Exchange_AuthorizeNet_Add_On = new IT_Exchange_AuthorizeNet_Add_On();
	$IT_Exchange_AuthorizeNet_Add_On->print_settings_page();
}

/**
 * Outputs wizard settings for Authorize.Net
 *
 * Exchange allows add-ons to add a small amount of settings to the wizard.
 * You can add these settings to the wizard by hooking into the following action:
 * - it_exchange_print_[addon-slug]_wizard_settings
 * Exchange exspects you to print your fields here.
 *
 * @since 1.0.0
 * @todo make this better, probably
 * @param object $form Current IT Form object
 * @return void
*/
function it_exchange_print_authorizenet_wizard_settings( $form ) {
	$IT_Exchange_AuthorizeNet_Add_On = new IT_Exchange_AuthorizeNet_Add_On();
	$settings    = it_exchange_get_option( 'addon_authorizenet', true );
	$form_values = ITUtility::merge_defaults( ITForm::get_post_data(), $settings );
	$hide_if_js  =  it_exchange_is_addon_enabled( 'authorizenet' ) ? '' : 'hide-if-js';
	?>
	<div class="field authorizenet-wizard <?php echo $hide_if_js; ?>">
	<?php if ( empty( $hide_if_js ) ) { ?>
		<input class="enable-authorizenet" type="hidden" name="it-exchange-transaction-methods[]" value="authorizenet" />
	<?php } ?>
	<?php $IT_Exchange_AuthorizeNet_Add_On->get_authorizenet_payment_form_table( $form, $form_values ); ?>
	</div>
	<?php
}
add_action( 'it_exchange_print_authorizenet_wizard_settings', 'it_exchange_print_authorizenet_wizard_settings' );

/**
 * Saves Authorize.Net settings when the Wizard is saved
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_save_authorizenet_wizard_settings( $errors ) {
	if ( ! empty( $errors ) )
		return $errors;

	$IT_Exchange_AuthorizeNet_Add_On = new IT_Exchange_AuthorizeNet_Add_On();
	return $IT_Exchange_AuthorizeNet_Add_On->authorizenet_save_wizard_settings();
}
add_action( 'it_exchange_save_authorizenet_wizard_settings', 'it_exchange_save_authorizenet_wizard_settings' );

/**
 * Default settings for Authorize.Net
 *
 * @since 1.0.0
 *
 * @param array $values
 * @return array
*/
function it_exchange_authorizenet_addon_default_settings( $values ) {
	$defaults = array(
		'authorizenet-test-mode'             => false,
		'authorizenet-api-login-id'          => '',
		'authorizenet-transaction-key'       => '',
		'authorizenet-purchase-button-label' => __( 'Purchase', 'it-l10n-exchange-addon-authorizenet' ),
	);
	$values = ITUtility::merge_defaults( $values, $defaults );
	return $values;
}
add_filter( 'it_storage_get_defaults_exchange_addon_authorizenet', 'it_exchange_authorizenet_addon_default_settings' );

/**
 * Filters default currencies to only display those supported by Authorize.Net
 *
 * @since 1.0.0
 *
 * @param array $default_currencies Array of default currencies supplied by iThemes Exchange
 * @return array filtered list of currencies only supported by Authorize.Net
 */
function it_exchange_authorizenet_addon_get_currency_options( $default_currencies ) {
	$IT_Exchange_AuthorizeNet_Add_On = new IT_Exchange_AuthorizeNet_Add_On();
	$authnet_currencies = $IT_Exchange_AuthorizeNet_Add_On->get_supported_currency_options();
	return array_intersect_key( $default_currencies, $authnet_currencies );
}
add_filter( 'it_exchange_get_currency_options', 'it_exchange_authorizenet_addon_get_currency_options' );

/**
 * Class for Authorize.Net
 * @since 1.0.0
*/
class IT_Exchange_AuthorizeNet_Add_On {

	/**
	 * @var string $_current_page Current $_GET['page'] value
	 * @since 1.0.0
	*/
	public $_current_page;

	/**
	 * @var string $_current_add_on Current $_GET['add-on-settings'] value
	 * @since 1.0.0
	*/
	public $_current_add_on;

	/**
	 * @var string $status_message will be displayed if not empty
	 * @since 1.0.0
	*/
	public $status_message;

	/**
	 * @var string $error_message will be displayed if not empty
	 * @since 1.0.0
	*/
	public $error_message;

	/**
	 * Class constructor
	 *
	 * Sets up the class.
	 * @since 1.0.0
	 * @return void
	*/
	function __construct() {
		$this->_current_page   = empty( $_GET['page'] ) ? false : $_GET['page'];
		$this->_current_add_on = empty( $_GET['add-on-settings'] ) ? false : $_GET['add-on-settings'];

		if ( ! empty( $_POST ) && is_admin() && 'it-exchange-addons' == $this->_current_page && 'authorizenet' == $this->_current_add_on ) {
			add_action( 'it_exchange_save_add_on_settings_authorizenet', array( $this, 'save_settings' ) );
			do_action( 'it_exchange_save_add_on_settings_authorizenet' );
		}
	}

	/**
	 * Prints settings page
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function print_settings_page() {
		$settings = it_exchange_get_option( 'addon_authorizenet', true );
		$form_values  = empty( $this->error_message ) ? $settings : ITForm::get_post_data();
		$form_options = array(
			'id'      => apply_filters( 'it_exchange_add_on_authorizenet', 'it-exchange-add-on-authorizenet-settings' ),
			'enctype' => apply_filters( 'it_exchange_add_on_authorizenet_settings_form_enctype', false ),
			'action'  => 'admin.php?page=it-exchange-addons&add-on-settings=authorizenet',
		);
		$form         = new ITForm( $form_values, array( 'prefix' => 'it-exchange-add-on-authorizenet' ) );

		if ( ! empty ( $this->status_message ) )
			ITUtility::show_status_message( $this->status_message );
		if ( ! empty( $this->error_message ) )
			ITUtility::show_error_message( $this->error_message );

		?>
		<div class="wrap">
			<?php screen_icon( 'it-exchange' ); ?>
			<h2><?php _e( 'Authorize.Net Settings', 'it-l10n-exchange-addon-authorizenet' ); ?></h2>

			<?php do_action( 'it_exchange_authorizenet_settings_page_top' ); ?>
			<?php do_action( 'it_exchange_addon_settings_page_top' ); ?>
			<?php $form->start_form( $form_options, 'it-exchange-authorizenet-settings' ); ?>
				<?php do_action( 'it_exchange_authorizenet_settings_form_top' ); ?>
				<?php $this->get_authorizenet_payment_form_table( $form, $form_values ); ?>
				<?php do_action( 'it_exchange_authorizenet_settings_form_bottom' ); ?>
				<p class="submit">
					<?php $form->add_submit( 'submit', array( 'value' => __( 'Save Changes', 'it-l10n-exchange-addon-authorizenet' ), 'class' => 'button button-primary button-large' ) ); ?>
				</p>
			<?php $form->end_form(); ?>
			<?php do_action( 'it_exchange_authorizenet_settings_page_bottom' ); ?>
			<?php do_action( 'it_exchange_addon_settings_page_bottom' ); ?>
		</div>
		<?php
	}

	/**
	 * @todo verify video link
	 */
	function get_authorizenet_payment_form_table( $form, $settings = array() ) {

		$general_settings = it_exchange_get_option( 'settings_general' );

		if ( ! empty( $settings ) )
			foreach ( $settings as $key => $var )
				$form->set_option( $key, $var );

		if ( ! empty( $_GET['page'] ) && 'it-exchange-setup' == $_GET['page'] ) : ?>
			<h3><?php _e( 'Authorize.Net', 'it-l10n-exchange-addon-authorizenet' ); ?></h3>
		<?php endif; ?>
		<div class="it-exchange-addon-settings it-exchange-authorizenet-addon-settings">
			<p>
				<?php _e( 'To get Authorize.Net set up for use with Exchange, you\'ll need to add the following information from your Authorize.Net account.', 'it-l10n-exchange-addon-authorizenet' ); ?>
				<br /><br />
				<?php _e( 'Video:', 'it-l10n-exchange-addon-authorizenet' ); ?>&nbsp;<a href="http://ithemes.com/tutorials/setting-up-authorizenet-in-exchange/" target="_blank"><?php _e( 'Setting Up Authorize.Net in Exchange', 'it-l10n-exchange-addon-authorizenet' ); ?></a>
			</p>
			<p>
				<?php _e( 'Don\'t have an Authorize.Net account yet?', 'it-l10n-exchange-addon-authorizenet' ); ?> <a href="http://authorize.net" target="_blank"><?php _e( 'Go set one up here', 'it-l10n-exchange-addon-authorizenet' ); ?></a>.
				<span class="tip" title="<?php _e( 'Enabling Authorize.Net limits your currency options to United States Dollars and Canadian Dollars.', 'it-l10n-exchange-addon-authorizenet' ); ?>">i</span>
			</p>
			<?php
				if ( ! in_array( $general_settings['default-currency'], array_keys( $this->get_supported_currency_options() ) ) )
					echo '<h4>' . sprintf( __( 'You are currently using a currency that is not supported by Authorize.net. <a href="%s">Please update your currency settings</a>.', 'it-l10n-exchange-addon-authorizenet' ), add_query_arg( 'page', 'it-exchange-settings' ) ) . '</h4>';
			?>
			<h4><?php _e( 'Step 1. Fill out your Authorize.Net API Credentials', 'it-l10n-exchange-addon-authorizenet' ); ?></h4>
			<p>
				<label for="authorizenet-api-login-id"><?php _e( 'API Login ID', 'it-l10n-exchange-addon-authorizenet' ); ?> <span class="tip" title="<?php _e( 'Your API Login ID can be found under the Setting Menu on your Merchant Interface (on your Authorize.net account).  Follow the instructions provided by Authorize.net to find your API Login and Transaction Key. Instructions can be found here.

', 'it-l10n-exchange-addon-authorizenet' ); ?>">i</span></label>
				<?php $form->add_text_box( 'authorizenet-api-login-id' ); ?>
			</p>
			<p>
				<label for="authorizenet-live-transaction-key"><?php _e( 'Transaction Key', 'it-l10n-exchange-addon-authorizenet' ); ?> <span class="tip" title="<?php _e( 'Your Transaction Key can be found under the Setting Menu on your Merchant Interface (on your Authorize.net account).  Follow the instructions provided by Authorize.net to find your API Login and Transaction Key. Instructions can be found here.

', 'it-l10n-exchange-addon-authorizenet' ); ?>">i</span></label>
				<?php $form->add_text_box( 'authorizenet-transaction-key' ); ?>
			</p>
			<h4><?php _e( 'Optional: Edit Purchase Button Label', 'it-l10n-exchange-addon-authorizenet' ); ?></h4>
			<p>
				<label for="authorizenet-purchase-button-label"><?php _e( 'Purchase Button Label', 'it-l10n-exchange-addon-authorizenet' ); ?> <span class="tip" title="<?php _e( 'This is the text inside the button your customers will press to purchase with Authorize.net', 'it-l10n-exchange-addon-authorizenet' ); ?>">i</span></label>
				<?php $form->add_text_box( 'authorizenet-purchase-button-label' ); ?>
			</p>

			<h4 class="hide-if-wizard"><?php _e( 'Optional: Enable Test Mode', 'it-l10n-exchange-addon-authorizenet' ); ?></h4>
			<p class="hide-if-wizard">
				<?php $form->add_check_box( 'authorizenet-test-mode', array( 'class' => 'show-test-mode-options' ) ); ?>
				<label for="authorizenet-test-mode"><?php _e( 'Enable Test Mode?', 'it-l10n-exchange-addon-authorizenet' ); ?> <span class="tip" title="<?php _e( 'Use this mode for testing your store. This mode will need to be disabled when the store is ready to process customer payments.', 'it-l10n-exchange-addon-authorizenet' ); ?>">i</span></label>
			</p>
		</div>
		<?php
	}

	/**
	 * Save settings
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function save_settings() {
		$defaults = it_exchange_get_option( 'addon_authorizenet' );
		$new_values = wp_parse_args( ITForm::get_post_data(), $defaults );

		// Check nonce
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'it-exchange-authorizenet-settings' ) ) {
			$this->error_message = __( 'Error. Please try again', 'it-l10n-exchange-addon-authorizenet' );
			return;
		}

		$errors = apply_filters( 'it_exchange_add_on_authorizenet_validate_settings', $this->get_form_errors( $new_values ), $new_values );
		if ( ! $errors && it_exchange_save_option( 'addon_authorizenet', $new_values ) ) {
			ITUtility::show_status_message( __( 'Settings saved.', 'it-l10n-exchange-addon-authorizenet' ) );
		} else if ( $errors ) {
			$errors = implode( '<br />', $errors );
			$this->error_message = $errors;
		} else {
			$this->status_message = __( 'Settings not saved.', 'it-l10n-exchange-addon-authorizenet' );
		}
	}

	function authorizenet_save_wizard_settings() {
		if ( empty( $_REQUEST['it_exchange_settings-wizard-submitted'] ) )
			return;

		$authorizenet_settings = array();

		// Fields to save
		$fields = array(
			'authorizenet-test-mode',
			'authorizenet-api-login-id',
			'authorizenet-transaction-key',
			'authorizenet-purchase-button-label'
		);

		$default_wizard_authorizenet_settings = apply_filters( 'default_wizard_authorizenet_settings', $fields );

		foreach( $default_wizard_authorizenet_settings as $var ) {
			if ( isset( $_REQUEST['it_exchange_settings-' . $var] ) ) {
				$authorizenet_settings[$var] = $_REQUEST[ 'it_exchange_settings-' . $var ];
			}
		}

		$settings = wp_parse_args( $authorizenet_settings, it_exchange_get_option( 'addon_authorizenet' ) );

		if ( $error_msg = $this->get_form_errors( $settings ) ) {

			return $error_msg;

		} else {
			it_exchange_save_option( 'addon_authorizenet', $settings );
			$this->status_message = __( 'Settings Saved.', 'it-l10n-exchange-addon-authorizenet' );
		}

		return;
	}

	/**
	 * Validates for values
	 *
	 * Returns string of errors if anything is invalid
	 *
	 * @since 1.0.0
	 * @return void
	*/
	public function get_form_errors( $values ) {

		$errors = array();

		if ( empty( $values['authorizenet-api-login-id'] ) )
			$errors[] = __( 'Please include your API Login ID', 'it-l10n-exchange-addon-authorizenet' );

		if ( empty( $values['authorizenet-transaction-key'] ) )
			$errors[] = __( 'Please include your Transaction Key', 'it-l10n-exchange-addon-authorizenet' );

		return $errors;
	}

	/**
	 * Prints HTML options for default status
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function get_supported_currency_options() {
		$options = array(
			'USD' => __( 'United States Dollar' ),
			'CAD' => __( 'Canadian Dollar' ),
			'GBP' => __( 'British Pound' ),
			'EUR' => __( 'European Euro' ),
		);
		return $options;
	}

}
