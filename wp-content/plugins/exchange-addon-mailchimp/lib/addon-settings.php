<?php
/**
 * iThemes Exchange MailChimp Add-on
 * @package IT_Exchange_Addon_MailChimp
 * @since 1.0.0
*/

/**
 * Call back for settings page
 *
 * This is set in options array when registering the add-on and called from it_exchange_enable_addon()
 *
 * @since 0.3.6
 * @return void
*/
function it_exchange_mailchimp_settings_callback() {
	$IT_Exchange_MailChimp_Add_On = new IT_Exchange_MailChimp_Add_On();
	$IT_Exchange_MailChimp_Add_On->print_settings_page();
}

class IT_Exchange_MailChimp_Add_On {

	/**
	 * @var boolean $_is_admin true or false
	 * @since 0.3.6
	*/
	var $_is_admin;

	/**
	 * @var string $_current_page Current $_GET['page'] value
	 * @since 0.3.6
	*/
	var $_current_page;

	/**
	 * @var string $_current_add_on Current $_GET['add-on-settings'] value
	 * @since 0.3.6
	*/
	var $_current_add_on;

	/**
	 * @var string $status_message will be displayed if not empty
	 * @since 0.3.6
	*/
	var $status_message;

	/**
	 * @var string $error_message will be displayed if not empty
	 * @since 0.3.6
	*/
	var $error_message;

	/**
 	 * Class constructor
	 *
	 * Sets up the class.
	 * @since 0.3.6
	 * @return void
	*/
	function IT_Exchange_MailChimp_Add_On() {
		$this->_is_admin       = is_admin();
		$this->_current_page   = empty( $_GET['page'] ) ? false : $_GET['page'];
		$this->_current_add_on = empty( $_GET['add-on-settings'] ) ? false : $_GET['add-on-settings'];

		if ( ! empty( $_POST ) && $this->_is_admin && 'it-exchange-addons' == $this->_current_page && 'mailchimp' == $this->_current_add_on ) {
			add_action( 'it_exchange_save_add_on_settings_mailchimp', array( $this, 'save_settings' ) );
			do_action( 'it_exchange_save_add_on_settings_mailchimp' );
		}

		add_filter( 'it_storage_get_defaults_exchange_addon_mailchimp', array( $this, 'set_default_settings' ) );
	}

	function print_settings_page() {
		$settings = it_exchange_get_option( 'addon_mailchimp', true );
	
		$form_values  = empty( $this->error_message ) ? $settings : ITForm::get_post_data();
		$form_options = array(
			'id'      => apply_filters( 'it_exchange_add_on_mailchimp', 'it-exchange-add-on-mailchimp-settings' ),
			'enctype' => apply_filters( 'it_exchange_add_on_mailchimp_settings_form_enctype', false ),
			'action'  => 'admin.php?page=it-exchange-addons&add-on-settings=mailchimp',
		);
		$form         = new ITForm( $form_values, array( 'prefix' => 'it-exchange-add-on-mailchimp' ) );

		if ( ! empty ( $this->status_message ) )
			ITUtility::show_status_message( $this->status_message );
		if ( ! empty( $this->error_message ) )
			ITUtility::show_error_message( $this->error_message );

		?>
		<div class="wrap">
			<?php screen_icon( 'it-exchange' ); ?>
			<h2><?php _e( 'MailChimp Settings', 'it-l10n-exchange-addon-mailchimp' ); ?></h2>

			<?php do_action( 'it_exchange_mailchimp_settings_page_top' ); ?>
			<?php do_action( 'it_exchange_addon_settings_page_top' ); ?>

			<?php $form->start_form( $form_options, 'it-exchange-mailchimp-settings' ); ?>
				<?php do_action( 'it_exchange_mailchimp_settings_form_top' ); ?>
				<?php $this->get_mailchimp_payment_form_table( $form, $form_values ); ?>
				<?php do_action( 'it_exchange_mailchimp_settings_form_bottom' ); ?>
				<p class="submit">
					<?php $form->add_submit( 'submit', array( 'value' => __( 'Save Changes', 'it-l10n-exchange-addon-mailchimp' ), 'class' => 'button button-primary button-large' ) ); ?>
				</p>
			<?php $form->end_form(); ?>
			<?php do_action( 'it_exchange_mailchimp_settings_page_bottom' ); ?>
			<?php do_action( 'it_exchange_addon_settings_page_bottom' ); ?>
		</div>
		<?php
	}

	function get_mailchimp_payment_form_table( $form, $settings = array() ) {
		//$default_status_options = it_exchange_mailchimp_get_default_status_options();
		$mailchimp_lists = it_exchange_get_mailchimp_lists( $settings['mailchimp-api-key'] );

		if ( !empty( $settings ) )
			foreach ( $settings as $key => $var )
				$form->set_option( $key, $var );
		?>
        
        <div class="it-exchange-addon-settings it-exchange-mailchimp-addon-settings">
            <p><?php _e( 'MailChimp allows store owners to manage and email lists of their currently subscribed customers.', 'it-l10n-exchange-addon-mailchimp' ); ?></p>
            <p><?php _e( 'Video:', 'it-l10n-exchange-addon-mailchimp' ); ?>&nbsp;<a href="http://ithemes.com/tutorials/exchange-add-ons-mailchimp/" target="_blank"><?php _e( 'Setting Up MailChimp in Exchange', 'it-l10n-exchange-addon-mailchimp' ); ?></a></p>
            <p><?php _e( 'To setup MailChimp in Exchange, complete the settings below.', 'it-l10n-exchange-addon-mailchimp' ); ?></p>
			<h4><label for="mailchimp-api-key"><?php _e( 'MailChimp API Key', 'it-l10n-exchange-addon-mailchimp' ) ?> <span class="tip" title="<?php _e( 'Enter your MailChimp API Key from your MailChimp dashboard, under Account Settings -> Extras -> API Keys', 'it-l10n-exchange-addon-mailchimp' ); ?>">i</span></label></h4>
			<p> <?php $form->add_text_box( 'mailchimp-api-key' ); ?> </p>
			<h4><label for="mailchimp-list"><?php _e( 'MailChimp List', 'it-l10n-exchange-addon-mailchimp' ) ?> <span class="tip" title="<?php _e( 'This is the list you want to use from MailChimp (only appears after saving your MailChimp API key).', 'it-l10n-exchange-addon-mailchimp' ); ?>">i</span></label></h4>
			<p> <?php $form->add_drop_down( 'mailchimp-list', $mailchimp_lists ); ?> </p>
			<h4><label for="mailchimp-label"><?php _e( 'Sign-up Label', 'it-l10n-exchange-addon-mailchimp' ) ?> <span class="tip" title="<?php _e( 'This will be the label displayed next to the sign-up option on the registration page.', 'it-l10n-exchange-addon-mailchimp' ); ?>">i</span></label></h4>
            <p> <?php $form->add_text_box( 'mailchimp-label' ); ?> </p>
			<h4><label for="mailchimp-optin"><?php _e( 'Enable Opt-in.', 'it-l10n-exchange-addon-mailchimp' ) ?> <span class="tip" title="<?php _e( 'Enabling opt-in is a good way to prevent your list from being black listed as SPAM. Users will only be added to this list if they select the opt-in checkmark when registering for an account.', 'it-l10n-exchange-addon-mailchimp' ); ?>">i</span></label></h4>
			<p> <?php $form->add_check_box( 'mailchimp-optin' ); ?> </p>
			<h4><label for="mailchimp-double-optin"><?php _e( 'Enable Double Opt-in.', 'it-l10n-exchange-addon-mailchimp' ) ?> <span class="tip" title="<?php _e( 'Enabling double opt-in is a good way to prevent your list from being black listed as SPAM. Users will be sent a confirmation email from MailChimp after signing up, and will only be added to your list after they have confirmed their subscription.', 'it-l10n-exchange-addon-mailchimp' ); ?>">i</span></label></h4>
			<p> <?php $form->add_check_box( 'mailchimp-double-optin' ); ?> </p>
		</div>
		<?php
	}

	/**
	 * Save settings
	 *
	 * @since 0.3.6
	 * @return void
	*/
	function save_settings() {
		$defaults = it_exchange_get_option( 'addon_mailchimp' );
		$new_values = wp_parse_args( ITForm::get_post_data(), $defaults );

		// Check nonce
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'it-exchange-mailchimp-settings' ) ) {
			$this->error_message = __( 'Error. Please try again', 'it-l10n-exchange-addon-mailchimp' );
			return;
		}

		$errors = apply_filters( 'it_exchange_add_on_manual_transaction_validate_settings', $this->get_form_errors( $new_values ), $new_values );
		if ( ! $errors && it_exchange_save_option( 'addon_mailchimp', $new_values ) ) {
			ITUtility::show_status_message( __( 'Settings saved.', 'it-l10n-exchange-addon-mailchimp' ) );
		} else if ( $errors ) {
			$errors = implode( '<br />', $errors );
			$this->error_message = $errors;
		} else {
			$this->status_message = __( 'Settings not saved.', 'it-l10n-exchange-addon-mailchimp' );
		}
	}

	/**
	 * Validates for values
	 *
	 * Returns string of errors if anything is invalid
	 *
	 * @since 0.3.6
	 * @return void
	*/
	function get_form_errors( $values ) {
		
		$default_wizard_mailchimp_settings = apply_filters( 'default_wizard_mailchimp_settings', array( 'mailchimp-api-key', 'mailchimp-list', 'mailchimp-label', 'mailchimp-double-optin' ) );
		$errors = array();
		if ( empty( $values['mailchimp-api-key'] ) ) {
			$errors[] = __( 'The MailChimp API Key field cannot be left blank.', 'it-l10n-exchange-addon-mailchimp' );
		} else {
			try {
				$mc = new Mailchimp( trim( $values['mailchimp-api-key'] ) );
				$mc->helper->ping();
			}
			catch ( Exception $e ) {
				$errors[] = $e->getMessage();
			}
		}
		if ( empty( $values['mailchimp-label'] ) )
			$errors[] = __( 'The MailChimp sign-up label cannot be left blank.', 'it-l10n-exchange-addon-mailchimp' );
			

		return $errors;
	}

	/**
	 * Sets the default options for manual payment settings
	 *
	 * @since 0.3.6
	 * @return array settings
	*/
	function set_default_settings( $defaults ) {
		$defaults['mailchimp-api-key'] = '';
		$defaults['mailchimp-label']   = __( 'Sign up to receive updates via email!', 'it-l10n-exchange-addon-mailchimp' );
		$defaults['mailchimp-optin'] = true;
		$defaults['mailchimp-double-optin'] = true;
		return $defaults;
	}
}
