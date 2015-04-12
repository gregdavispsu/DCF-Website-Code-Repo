<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_delcf' );

/** MySQL database username */
define( 'DB_USER', 'delcf' );

/** MySQL database password */
define( 'DB_PASSWORD', 'V2ykBzHwjcTGtxLh' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XP<y+<mEH62<]iHEI{fnbXMQ<ny$q3<{6$TXM3>}jJUYMvjnyY3>,y$U3vzo0|>4');
define('SECURE_AUTH_KEY',  'j>ygVY0v!|zB04F,cgUQFJ!gCG4dRVhGN|wsgkC!}0|NCGD_adSOCG~dpsh[~_:sK');
define('LOGGED_IN_KEY',    'Rvrc47^}$^Y74C!ZdRNCG@cosg[@!}rJNC[~_Z9KOClZdoN|[~-osK[lpe#+~]pHK');
define('NONCE_KEY',        '#OSeD+~tpdh9~+*u6];A*XaPL9DqeitS];_qb3A*<+*X6ILAmqfbPT{qnrf>$^<$<');
define('AUTH_SALT',        'FcNYcQznr$c37}>$^Y748}VJNY8vzokYc4z,>@F474-RVKG48wVgkZ!w-|kCG4_w-');
define('SECURE_AUTH_SALT', ':WdC-~tpdh9~[:_K8C9~WaPL9DpeitS];_]pHK:<+*X6HLAiXeaPT]q+mpH#jmb.y');
define('LOGGED_IN_SALT',   'nCZo|zorJ>48}UJN[oGK88C1ZKVZC-~o-Z08@-~pH#15[SGKW5twlhVZ1wtxm;_#2');
define('NONCE_SALT',       'Inb7@kU0,rNB3j0osgckY!v@!v8}00,>gFCG5dRVhG~!wsgkC!:0|NCG_h9D1:_#d');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */

define( 'WP_SITEURL', 'http://delcf.wpengine.com' );

define( 'WP_HOME', 'http://delcf.wpengine.com' );

define( 'WPE_APIKEY', 'ccab2c9012d540066c4a847b4e92d8f8a25772f6' );

define( 'WPE_EXTERNAL_URL', false );

define( 'DB_HOST_SLAVE', '127.0.0.1' );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

define( 'PWP_NAME', 'delcf' );

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '1648' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 22 );

define( 'WPE_LBMASTER_IP', '97.107.135.211' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISABLE_WP_CRON', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'delcf.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-1648', );

$wpe_special_ips=array ( 0 => '97.107.135.211', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
