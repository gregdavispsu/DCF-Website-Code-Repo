<?php

/**
 * Display the settings form of the add-on.
 *
 * @since 1.0
 */
function itegfp_addon_settings_callback() {
	$it_exchange_gravity_forms_addon = new ITEGFP_Settings();
	$it_exchange_gravity_forms_addon->print_settings_page();
}

/**
 * List default values for the settings options.
 *
 * @since 1.0
 *
 * @param array $defaults
 *
 * @return array
 */
function itegfp_addon_settings_defaults( $defaults ) {
	$defaults['title']                    = __( "Gravity Forms", ITEGFP::SLUG );
	$defaults['license']                  = '';
	$defaults['registration_form']        = 0;
	$defaults['redirect_to_confirmation'] = true;

	return $defaults;
}

add_filter( 'it_storage_get_defaults_exchange_addon_gravity_forms', 'itegfp_addon_settings_defaults' );

/**
 * Class ITEGFP_Settings
 */
class ITEGFP_Settings {

	/**
	 * @var $page string
	 */
	private $page;

	/**
	 * @var $addon string
	 */
	private $addon;

	/**
	 * @var $message string
	 */
	private $message;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		if ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) {
			$this->page = $_GET['page'];
		} else {
			$this->page = false;
		}

		if ( isset( $_GET['add-on-settings'] ) && ! empty( $_GET['add-on-settings'] ) ) {
			$this->addon = $_GET['add-on-settings'];
		} else {
			$this->addon = false;
		}

		if ( is_admin() && $this->page === 'it-exchange-addons' && $this->addon === 'gravity-forms' && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			add_action( 'it_exchange_save_add_on_settings_gravity-forms', array( $this, 'save_settings' ) );
			do_action( 'it_exchange_save_add_on_settings_gravity-forms' );
		}
	}

	/**
	 * Print the settings page.
	 *
	 * @since 1.0
	 */
	public function print_settings_page() {
		$form_values = ITUtility::merge_defaults( ITForm::get_post_data(), it_exchange_get_option( 'addon_gravity_forms', true ) );

		$form_options = array(
			'id'     => 'it-exchange-addon-gravity-forms-settings',
			'action' => admin_url( 'admin.php?page=it-exchange-addons&add-on-settings=gravity-forms' ),
		);

		$form = new ITForm( $form_values, array( 'prefix' => 'it-exchange-addon-gravity-forms' ) );

		if ( ! empty( $this->message ) ) {
			ITUtility::show_status_message( $this->message );
		}
		?>
		<div class="wrap">
			<h2><?php _e( 'Gravity Forms Settings', ITEGFP::SLUG ); ?></h2>

			<?php do_action( 'it_exchange_addon_settings_page_top' ); ?>
			<?php $form->start_form( $form_options, 'it-exchange-gravity-forms-settings' ); ?>
			<?php do_action( 'it_exchange_gravity_forms_settings_form_top' ); ?>
			<?php $this->get_gravity_forms_table( $form, $form_values ); ?>
			<?php do_action( 'it_exchange_gravity_forms_settings_form_bottom' ); ?>
			<p class="submit">
				<?php $form->add_submit( 'submit', array(
					'value' => __( 'Save Changes', ITEGFP::SLUG ),
					'class' => 'button button-primary button-large'
				) ); ?>
			</p>
			<?php $form->end_form(); ?>
			<?php do_action( 'it_exchange_addon_settings_page_bottom' ); ?>
		</div>
	<?php
	}

	/**
	 * Get the forms table.
	 *
	 * @since 1.0
	 *
	 * @param ITForm $form
	 * @param array  $settings
	 */
	public function get_gravity_forms_table( $form, $settings = array() ) {

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $key => $var ) {
				$form->set_option( $key, $var );
			}
		}

		?>
		<div class="it-exchange-addon-settings it-exchange-itecls-addon-settings">

			<label for="license"><?php _e( "License Key", ITEGFP::SLUG ); ?></label>
			<?php $form->add_text_box( 'license' ); ?>

			<label for="title"><?php _e( "Title", ITEGFP::SLUG ); ?></label>
			<?php $form->add_text_box( 'title' ); ?>

			<p class="description"><?php _e( "Override the label for this quasi payment gateway.", ITEGFP::SLUG ); ?></p>

			<label for="redirect_to_confirmation"><?php _e( "Redirect to Confirmation", ITEGFP::SLUG ); ?></label>
			<?php $form->add_check_box( 'redirect_to_confirmation' ); ?>

			<p class="description">
				<?php _e( "When a user purchases a product through Gravity Forms, check this to redirect them to the Exchange confirmation page.", ITEGFP::SLUG ); ?>
				<br>
				<?php _e( "Otherwise, the default Gravity Forms post-submission action will occur.", ITEGFP::SLUG ); ?>
			</p>

			<label for="registration_form"><?php _e( "Override Registration Form", ITEGFP::SLUG ); ?></label>
			<?php $form->add_drop_down( 'registration_form', $this->get_gravity_forms_select_data() ); ?>

			<p class="description"><?php _e( "Override the Super Widget and Exchange registration form, if desired.", ITEGFP::SLUG ); ?></p>
		</div>
	<?php
	}

	/**
	 * Save the form settings.
	 *
	 * @since 1.0
	 */
	public function save_settings() {
		$defaults = it_exchange_get_option( 'addon_gravity_forms' );
		$values   = wp_parse_args( ITForm::get_post_data(), $defaults );

		if ( wp_verify_nonce( $_POST['_wpnonce'], 'it-exchange-gravity-forms-settings' ) ) {
			$errors = apply_filters( 'it_exchange_add_on_gravity_forms_validate_settings', $this->get_form_errors( $values, $defaults ), $values );

			if ( ! $errors && it_exchange_save_option( 'addon_gravity_forms', $values ) ) {
				ITUtility::show_status_message( __( 'Settings saved.', ITEGFP::SLUG ) );
			} else {
				$this->message = __( 'Settings not saved.', ITEGFP::SLUG );
			}
		} else {
			$this->message = __( 'Invalid request. Please try again.', ITEGFP::SLUG );
		}
	}

	/**
	 * Validates for values.
	 *
	 * @since 1.0
	 *
	 * @param array $values
	 * @param array $old_values
	 *
	 * @return array
	 */
	public function get_form_errors( $values, $old_values ) {
		$errors = array();

		if ( $values['license'] != $old_values['license'] && ! empty( $values['license'] ) ) {
			$result = $this->activate( $values['license'] );
			if ( $result == 'error' ) {
				$errors[] = __( "There was a problem connecting to the license server API. Please try again later.", ITEGFP::SLUG );
			} elseif ( $result == 'inactive' ) {
				$errors[] = __( "This license is inactive.", ITEGFP::SLUG );
			}
		}

		return $errors;
	}

	/**
	 * Activate the license.
	 *
	 * @since 1.0
	 *
	 * @param $license string
	 *
	 * @return string ( active|inactive|error )
	 */
	protected function activate( $license ) {
		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => ITEGFP::ID, // the name of our product in EDD,
			'url'        => home_url()
		);
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, 'http://ironbounddesigns.com' ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return 'error';
		}
		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "active" or "inactive"
		return $license_data->license;
	}

	/**
	 * Get the select data for gravity forms.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	protected function get_gravity_forms_select_data() {
		$select_data    = array();
		$select_data[0] = "-- " . __( "Exchange Default", ITEGFP::SLUG ) . " --";

		foreach ( GFFormsModel::get_forms( 1 ) as $form ) {
			$select_data[ $form->id ] = $form->title;
		}

		return $select_data;
	}
}