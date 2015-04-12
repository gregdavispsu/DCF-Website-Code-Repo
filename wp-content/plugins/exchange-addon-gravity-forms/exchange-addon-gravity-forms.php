<?php
/*
Plugin Name: iThemes Exchange - Gravity Forms Add-on
Plugin URI: http://ironbounddesigns.com
Description: Sell Exchange products through Gravity Forms
Version: 1.0
Author: Iron Bound Designs
Author URI: http://ironbounddesigns.com
License: GPL2
Text Domain: ibd-exchange-addon-itegfp
Domain Path: /lang
*/

/**
 * Show deps nag.
 *
 * @since 1.0
 */
function itegfp_show_deps_nag() {
	if ( ! itegfp_deps_met() ) {
		?>
		<div id="it-exchange-add-on-deps-nag" class="it-exchange-nag">
			<?php _e( 'You must have Gravity Forms active to use the Gravity Forms Exchange Add-on.', ITEGFP::SLUG ); ?>
		</div>
	<?php
	}
}

add_action( 'admin_notices', 'itegfp_show_deps_nag' );

/**
 * Determine if all of our deps are met
 *
 * @since 1.0
 *
 * @return bool
 */
function itegfp_deps_met() {
	return class_exists( 'GFForms' );
}

/**
 * Class ITEGFP
 */
class ITEGFP {

	/**
	 * Plugin Version
	 */
	const VERSION = 1.0;

	/**
	 * Translation SLUG
	 */
	const SLUG = 'ibd-exchange-addon-itegfp';

	/**
	 * Name of this plugin as it appears on our store.
	 */
	const NAME = 'ithemes-exchange-gravity-forms-addon';

	/**
	 * Product ID.
	 */
	const ID = 825;

	/**
	 * @var string
	 */
	static $dir;

	/**
	 * @var string
	 */
	static $url;

	/**
	 * Constructor.
	 */
	public function __construct() {
		self::$dir = plugin_dir_path( __FILE__ );
		self::$url = plugin_dir_url( __FILE__ );
		spl_autoload_register( array( "ITEGFP", "autoload" ) );

		add_action( 'admin_init', array( $this, 'setup_licensing' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_and_styles' ), 5 );
	}

	/**
	 * Setup the licensing for EDD.
	 *
	 * @since 1.0
	 */
	public function setup_licensing() {
		$options = it_exchange_get_option( 'addon_gravity_forms' );
		// retrieve our license key from the DB
		$license_key = trim( $options['license'] );
		// setup the updater
		new EDD_SL_Plugin_Updater( 'http://ironbounddesigns.com', __FILE__, array(
				'version' => self::VERSION,
				'license' => $license_key,
				'item_id' => self::ID,
				'author'  => 'Iron Bound Designs',
				'url'     => home_url()
			)
		);
	}

	/**
	 * Register admin scripts.
	 *
	 * @since 1.0
	 */
	public function scripts_and_styles() {
		wp_register_script( 'itegfp-admin', ITEGFP::$url . 'assets/js/admin.js', array( 'jquery' ), self::VERSION, true );
	}

	/**
	 * Autoloader.
	 *
	 * If the class begins with ITEGFP, then look for it by breaking the class into pieces by '_'.
	 * Then look in the corresponding directory structure by concatenating the class parts. The filename is then
	 * prefaced with either class, abstract, or interface.
	 *
	 * If the class doesn't begin with ITEGFP, then look in the lib/classes, with a filename by replacing
	 * underscores with dashes. Follows the same conventions for filename prefixes.
	 *
	 * @since 1.0
	 *
	 * @param $class_name string
	 */
	public static function autoload( $class_name ) {
		if ( substr( $class_name, 0, 6 ) != "ITEGFP" ) {

			if ( substr( $class_name, 0, 12 ) == "IT_Theme_API" ) {


				$path  = self::$dir . "api/theme";
				$class = strtolower( substr( $class_name, 13 ) );

				$name = str_replace( "_", "-", $class );
			} else {
				$path  = self::$dir . "lib/classes";
				$class = strtolower( $class_name );

				$name = str_replace( "_", "-", $class );
			}
		} else {
			$path = self::$dir . "lib";

			$class = substr( $class_name, 6 );
			$class = strtolower( $class );

			$parts = explode( "_", $class );
			$name  = array_pop( $parts );

			$path .= implode( "/", $parts );
		}

		$path .= "/class.$name.php";

		if ( file_exists( $path ) ) {
			require( $path );

			return;
		}

		if ( file_exists( str_replace( "class.", "abstract.", $path ) ) ) {
			require( str_replace( "class.", "abstract.", $path ) );

			return;
		}

		if ( file_exists( str_replace( "class.", "interface.", $path ) ) ) {
			require( str_replace( "class.", "interface.", $path ) );

			return;
		}
	}

}

new ITEGFP();

/**
 * This registers our custom iThemes Exchange add-on.
 *
 * @since 1.0
 */
function itegfp_register_addon() {

	$options = array(
		'name'              => __( 'Gravity Forms', ITEGFP::SLUG),
		'description'       => __( 'Sell Exchange products through Gravity Forms.', ITEGFP::SLUG ),
		'author'            => 'Iron Bound Designs',
		'author_url'        => 'http://www.ironbounddesigns.com',
		'icon'              => ITEGFP::$url . 'assets/img/icon-50x50.png',
		'file'              => ITEGFP::$dir . 'init.php',
		'settings-callback' => 'itegfp_addon_settings_callback',
	);

	if ( itegfp_deps_met() ) {
		it_exchange_register_addon( 'gravity-forms', $options );
	}
}

add_action( 'it_exchange_register_addons', 'itegfp_register_addon' );


/**
 * Loads the translation data for WordPress
 *
 * @since 1.0
 */
function itegfp_textdomain() {
	load_plugin_textdomain( 'it-l10n-exchange-addon-gravity-forms', false, ITEGFP::$dir . '/lang/' );
}

add_action( 'plugins_loaded', 'itegfp_textdomain' );