<?php

/**
 * Load the plugin functions.
 */
require_once( ITEGFP::$dir . 'lib/functions.php' );

/**
 * Load the required hooks.
 */
require_once( ITEGFP::$dir . 'lib/hooks.php' );

/**
 * Load the add-on settings.
 */
require_once( ITEGFP::$dir . 'lib/settings.php' );

/**
 * Load the admin area.
 */
new ITEGFP_Admin();