<?php
/*
 * Plugin Name: iThemes Exchange - Abandoned Carts
 * Version: 1.0.3
 * Description: Tracks abandoned carts and automatically emails customers
 * Plugin URI: http://ithemes.com/exchange/abandoned-carts
 * Author: iThemes
 * Author URI: http://ithemes.com
 * iThemes Package: exchange-addon-abandoned-carts
 
 * Installation:
 * 1. Download and unzip the latest release zip file.
 * 2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 * 3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 * 4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 *
*/

/**
 * This registers our plugin as an exchange addon
 *
 * To learn how to create your own-addon, visit http://ithemes.com/codex/page/Exchange_Custom_Add-ons:_Overview
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_register_abandoned_carts_addon() {
	$options = array(
		'name'              => __( 'Abandoned Carts', 'it-l10n-exchange-addon-abandoned-carts' ),
		'description'       => __( 'Tracks abandoned carts and automatically emails customers.', 'it-l10n-exchange-addon-abandoned-carts' ),
		'author'            => 'iThemes',
		'author_url'        => 'http://ithemes.com/exchange/abandoned-carts/',
		'icon'              => ITUtility::get_url_from_file( dirname( __FILE__ ) . '/lib/abandoned-carts50px.png' ),
		'file'              => dirname( __FILE__ ) . '/init.php',
		'category'          => 'admin',
	);
	it_exchange_register_addon( 'abandoned-carts', $options );
}
add_action( 'it_exchange_register_addons', 'it_exchange_register_abandoned_carts_addon' );

/**
 * Loads the translation data for WordPress
 *
 * @uses load_plugin_textdomain()
 * @since 1.0.0
 * @return void
*/
function it_exchange_abandoned_carts_set_textdomain() {
	load_plugin_textdomain( 'it-l10n-exchange-addon-abandoned-carts', false, dirname( plugin_basename( __FILE__  ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'it_exchange_abandoned_carts_set_textdomain' );

/**
 * Registers Plugin with iThemes updater class
 *
 * @since 1.0.0
 *
 * @param object $updater ithemes updater object
 * @return void
*/
function ithemes_exchange_addon_abandoned_carts_updater_register( $updater ) { 
	    $updater->register( 'exchange-addon-abandoned-carts', __FILE__ );
}
add_action( 'ithemes_updater_register', 'ithemes_exchange_addon_abandoned_carts_updater_register' );
require( dirname( __FILE__ ) . '/lib/updater/load.php' );

/**
 * Activation hook. Runs on activation
 *
 * @since 1.0.0
*/
function it_exchange_abandoned_carts_activation_hook() {
	// Setup cron on activation
	wp_schedule_event( time(), 'hourly', 'it_exchange_abandoned_carts_hourly_event_hook' );
	update_option( 'it-exchange-create-abandoned-cart-demo-email', true );
}
register_activation_hook( __FILE__, 'it_exchange_abandoned_carts_activation_hook' );

/**
 * Deactivation hook. Runs on deactivation
 *
 * @since 1.0.0
*/
function it_exchange_abandoned_carts_deactivation_hook() {
	// clear cron on deactivation
	wp_clear_scheduled_hook( 'it_exchange_abandoned_carts_hourly_event_hook' );
}
register_deactivation_hook( __FILE__, 'it_exchange_abandoned_carts_deactivation_hook' );
