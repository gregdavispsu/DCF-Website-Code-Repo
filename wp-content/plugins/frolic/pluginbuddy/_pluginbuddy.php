<?php



/*	pluginbuddy class
 *	
 *	PluginBuddy.com framework for handling all plugin functioality, architecture, etc.
 *	$settings variable is expected to be in the same scope of this file and previously populated with all plugin settings.
 *	
 *	@author Dustin Bolton
 */
class pb_frolic {
	private static $pbframework_version = '1.0.28';
	
	
	// ********** PUBLIC PROPERTIES **********
	public static $start_time;					// microtime when init() was first run.
	
	
	public static $options;						// Stores all options for plugin that will change such as user defined settings.
	public static $ui;							// User interface class for rapidly constructing WP-styled GUIs. REMOVED. Now added at runtime when init_class_controller() called
	public static $filesystem;					// Class for manipulating & interfacing file system.
	public static $format;						// Class for formatting data or text in human friendly forms.
	public static $classes = array();			// Array holder for user-defined classes needed globally by plugin. Set/get with $class['class_slug'].
	public static $variables = array();			// Array holder for user-defined variables needed globally by plugin. Set/get with $variables['var_name']. Useful for things such as an instance counter that increments.
	
	
	// ********** PRIVATE PROPERTIES **********
	
	
	
	private static $_settings = array (			// Default framework settings for this plugin. NOT the same as options. Access via self::settings().
		'slug'				=>		'',
		'series'			=>		'',
		'default_options'	=>		'',
		'log_serial' 		=>  	'',
	);
	private static $_page_settings;				// Holds admin page settings for adding to the admin menu on a hook later.
	
	// Controller objects. See: /controllers/ directory.
	private static $_actions;					// Controller for WordPress actions.
	private static $_ajax;						// Controller for WordPress AJAX actions.
	private static $_cron;						// Controller for WordPress scheduled crons.
	private static $_dashboard;					// Controller for WordPress admin dashboard items.
	private static $_filters;					// Controller for WordPress filters.
	private static $_shortcodes;				// Controller for WordPress shortcodes.
	//private static $_widgets;					// Controller for WordPress widgets.
	private static $_pages;						// Controller for WordPress pages. See /controllers/pages/ directory.
	
	// Misc variables.
	private static $_plugin_path;				// Local path to plugin. Ex: /users/pb/www/wp-content/plugins/my_plugin (no trailing slash) @see pluginbuddy:plugin_path()
	private static $_plugin_url;				// URL to plugin directory. Ex: http://pluginbuddy.com/wp-content/plugins/my_plugin/ (with trailing slash) @see self::plugin_url()
	private static $_self_link;					// Returns URL to the current admin page if on a plugin page. Ex: http://pluginbuddy.com/wp-admin/index.php?page=pb_myplugin @see self::page_link()
	//private static $_callbacks;				// DISABLED. Using create_function() to bypass need for this. Currently only holding callback for the admin menu . @see pluginbuddy_callbacks class
	public static $_dashboard_widgets;   		// Holds tag and title for unconstructed dashboard widgets temporarily.
	public static $_updater;					// Contains updater object (if enabled) of the most up to date updater found. Populated on init hook.
	
	
	
	// ********** FUNCTIONS **********
	
	
	
	/*	self::init()
	 *	
	 *	Constructor for this static class. Called from the plugin's init.php.
	 *	
	 *	@param		array		$pluginbuddy_settings		Array of plugin settings such as slug, default options,
	 *														plugin-specific options, etc.
	 *	@return		null
	 */
	public static function init( $pluginbuddy_settings ) {
		self::$start_time = microtime( true );
		self::$_settings = array_merge( (array)self::$_settings, (array)$pluginbuddy_settings ); // Merge settings over framework defaults.
		
		// Set up various path variables.
		self::$_plugin_path = dirname( dirname( __FILE__ ) );
		$relative_path = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', self::$_plugin_path ) ), '\\\/' );
		self::$_plugin_url = site_url() . '/' . $relative_path;
		if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) { self::$_plugin_url = str_replace( 'http://', 'https://', self::$_plugin_url ); } // Handle https URLs properly.
		if ( isset( $_GET['page'] ) ) { // If in an admin page then append page querystring.
		$selflinkvar = explode( '?', $_SERVER['REQUEST_URI'] );
			self::$_self_link = array_shift( $selflinkvar ) . '?page=' . htmlentities( $_GET['page'] );
		}
		// Removed. see add_page(). Now using create_function() to bypass need for this. self::$_callbacks = new pluginbuddy_callbacks();
		
		// Load thumbnail live downsizer if needed.
		if ( isset( self::$_settings['modules']['downsizer'] ) && ( self::$_settings['modules']['downsizer'] === true ) ) {
			require_once( self::$_plugin_path . '/pluginbuddy/_image_downsize.php' );
		}
		
		// filesystem class controller.
		if ( isset( self::$_settings['modules']['filesystem'] ) && ( self::$_settings['modules']['filesystem'] === true ) ) {
			self::init_class_controller( 'filesystem' );
		}
		// format class controller.
		if ( isset( self::$_settings['modules']['format'] ) && ( self::$_settings['modules']['format'] === true ) ) {
			self::init_class_controller( 'format' );
		}
		
		if ( is_admin() ) {
			
			// Load UI system.
			self::init_class_controller( 'ui' );
			
			// Load activation hook if in admin and activation file exists.
			if ( file_exists( self::$_plugin_path . '/controllers/activation.php' ) ) {
				register_activation_hook( self::$_plugin_path . '/init.php', create_function( '', "require_once('" . self::$_plugin_path . "/controllers/activation.php');" ) ); // Run some code when plugin is activated in dashboard.
			}
		}
	} // End init().
	
	
	
	/*	self::plugin_path()
	 *	
	 *	Returns local path to plugin. Ex: /users/pb/www/wp-content/plugins/my_plugin (no trailing slash)
	 *	
	 *	@return		string		Plugin path directory (no trailing slash).
	 */
	public static function plugin_path() {
		return self::$_plugin_path;
	} // End plugin_path().
	
	
	
	/*	self::plugin_url()
	 *	
	 *	Returns URL to plugin directory. Ex: http://pluginbuddy.com/wp-content/plugins/my_plugin/ (with trailing slash)
	 *	
	 *	@return		string		Plugin path URL (with trailing slash).
	 */
	public static function plugin_url() {
		return self::$_plugin_url;
	} // End plugin_url().
	
	
	
	/*	self::page_url()
	 *	
	 *	Returns URL to the current admin page if on a plugin page. Ex: http://pluginbuddy.com/wp-admin/index.php?page=pb_myplugin
	 *	
	 *	@return		string		Plugin page URL (with trailing slash).
	 */
	public static function page_url() {
		return self::$_self_link;
	} // End page_url().
	
	
	
	/*	self::ajax_url()
	 *	
	 *	Returns the admin-side AJAX URL. Properly handles prefixing and everything for PB framework.
	 *	Todo: provide non-admin-side functionality?
	 *	
	 *	@param		string		$tag		Tag / slug of AJAX.
	 *	@return		string					URL for AJAX.
	 */
	public static function ajax_url( $tag ) {
		return admin_url('admin-ajax.php') . '?action=pb_' . self::settings( 'slug' ) . '_' . $tag;
	} // End ajax_url().
	
	
	
	/*	self::settings()
	 *	
	 *	Retrieves misc plugin settings both passed from init.php into self::$_settings
	 *	and also plugin settings defined in the init.php header including:
	 *	name, title, description, author, authoruri, version, pluginuri OR url, textdomain, domainpath, network.
	 *	@see self::init()
	 *	
	 *	@param		string		$type		Type of setting to retrieve.
	 *	@return		mixed					Value associated with that settings. Null if not found.
	 */
	public static function settings( $type ) {
		if ( !self::blank( @self::$_settings[$type] ) ) { // Return value if it already exists.
			return self::$_settings[$type];
		}
				
		// The variable does not exist so check to see if it can be extracted from the plugin's header.
		if ( self::blank( @self::$_settings['name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			$info = array_change_key_case( get_plugin_data( self::$_plugin_path . '/init.php', false, false ), CASE_LOWER );
			$info['url'] = $info['pluginuri'];
			unset( $info['pluginuri'] );
			self::$_settings = array_merge( self::$_settings, $info );
		}

		// Try to return setting otherwise throw an error.
		if ( isset( self::$_settings[$type] ) ) {
			return self::$_settings[$type];
		} else {
			return '{Unknown settings() variable `' . $type . '`}';
		}
	} // End settings().
	
	
	
	/*	self::blank()
	 *	
	 *	Returns whether a not a variable is blank (empty string, null, undefined) or not.
	 *	Be sure to suppress errors if using this function where indexes may be non-existant with @ sign.
	 *	
	 *	@param		mixed		Variable to determine if it is blank or not.
	 *	@return		boolean		True if variable is set and is not an empty string.
	 */
	public static function blank( $value ) {
		return empty( $value ) && !is_numeric( $value );
	} // End blank().
	
	
	
	/*	self::_POST()
	 *	
	 *	Returns $_POST value if available, else returns a blank. Prevents having to check isset first. Strips WP's added slashes.
	 *	
	 *	@param		string		Key of POST variable to check.
	 *	@return		mixed		Value of POST variable if set. If not set returns a blank string ''.
	 */
	public static function _POST( $value = null ) {
		if ( isset( $_POST[$value] ) | ( $value === null ) ) {
			if ( $value === null ) { // Requesting $_POST variable.
				return stripslashes_deep( $_POST );
			} else {
				return stripslashes_deep( $_POST[$value] ); // Remove WordPress' magic-quotes style escaping of data. *shakes head*
			}
		} else {
			return '';
		}
	} // End _POST().
	
	
	
	/*	self::_GET()
	 *	
	 *	Returns $_POST value if available, else returns a blank. Prevents having to check isset first.
	 *	TODO: Do we need to stripslashes_deep() on GET vars also like POSTs?
	 *	
	 *	@param		string		Key of POST variable to check.
	 *	@return		mixed		Value of POST variable if set. If not set returns a blank string ''.
	 */
	public static function _GET( $value = '' ) {
		if ( isset( $_GET[$value] ) ) {
			return $_GET[$value];
		} else {
			return '';
		}
	} // End _GET().
	
	
	
	/*	self::get_group()
	 *
	 *	Grabs & returns a reference to a specified point in the options array.
	 *	Ex usage: $group = &self::get_group( 'groups#' . $_GET['edit'] );
	 *	
	 *	@param		string	$savepoint_root		Path in the array to return a reference to.
	 *											Ex: groups#5 will grab self::$options['groups'][5]
	 *	@return		mixed						Value within the array at the specified point.
	 *											Can be used as a reference. See example in description.
	 *											NOTE: Returns false if not found.
	 */
	public static function &get_group( $savepoint_root ) {
		if ( $savepoint_root == '' ) { // Root was requested.
			$return = &self::$options;
			return $return;
		}
		
		$savepoint_subsection = &self::$options;
		$savepoint_levels = explode( '#', $savepoint_root );
		foreach ( $savepoint_levels as $savepoint_level ) {
			if ( isset( $savepoint_subsection{$savepoint_level} ) ) {
				$savepoint_subsection = &$savepoint_subsection{$savepoint_level};
			} else {
				echo '{Error #4489045: Invalid array in path: `' . $savepoint_root . '`}';
				return false;
			}
		}
		
		return $savepoint_subsection;
	} // End get_group().
	
	
	
	/*	self::load()
	 *	
	 *	Loads the plugin options array containing all user-configurable options, etc.
	 *	Access options via self::$options. Bypasses WP options caching for reliability.
	 *	
	 *	@return		null
	 */
	public static function load() {
		self::$options = self::_get_option( 'pb_' . self::settings( 'slug' ) );
		
		// Merge defaults into temporary $options variable and save if it differs with the pre-merge options.
		$options = array_merge( (array)self::settings( 'default_options' ), (array)self::$options );
		if ( self::$options !== $options ) {
			self::$options = $options;
			self::save();
		}
	} // End load().
	
	
	
	/*	self::_get_option()
	 *	
	 *	Bypasses WordPress options cache. Unfortunately there appears to be race condition issues with the built-in WP options system.
	 *	Used by load() function internally. Taken and modified from WordPress core.
	 *	@see load()
	 *	
	 *	@param		string		$option		Option name.
	 *	@param		mixed		$default	default = false; we do not use this.
	 *	@return		mixed					Saved option value.
	 */
	private static function _get_option( $option, $default = false ) {
		global $wpdb;

		$option = trim($option);
		if ( empty($option) )
			return false;

		if ( defined( 'WP_SETUP_CONFIG' ) )
			return false;

		$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );

		// Has to be get_row instead of get_var because of funkiness with 0, false, null values
		if ( is_object( $row ) ) {
			$value = $row->option_value;
		} else {
			$value = $default;
		}

		// If home is not set use siteurl.
		if ( 'home' == $option && '' == $value )
			return get_option( 'siteurl' );

		if ( in_array( $option, array('siteurl', 'home', 'category_base', 'tag_base') ) )
			$value = untrailingslashit( $value );

		$value = maybe_unserialize( $value );

		return $value;
	} // End get_option().
	
	
	
	/*	self::save()
	 *	
	 *	Save plugin options to database.
	 *	
	 *	@return		boolean			True if save succeeded, false otherwise.
	 */
	public static function save() {
		add_site_option( 'pb_' . self::settings( 'slug' ), self::$options, '', 'no'); // 'No' prevents autoload if we wont always need the data loaded.
		return self::_update_option( 'pb_' . self::settings( 'slug' ), self::$options );
	} // End save().
	
	
	
	/*	self::_update_option()
	 *	
	 *	Bypasses WordPress built in update option cache. Taken from WordPress core and modified.
	 *	@see self::_get_option()
	 *	@see self::save()
	 *	
	 *	@param		string	$option			Option name.
	 *	@param		mixed	$newvalue		New value to save into option.
	 *	@return		boolean					True on success; false otherwise.
	 */
	private static function _update_option( $option, $newvalue ) {
		global $wpdb;

		$option = trim($option);
		if ( empty($option) )
			return false;

		$oldvalue = get_option( $option );
		if ( false === $oldvalue ) {
			return add_option( $option, $newvalue );
		} else {
			$newvalue = sanitize_option( $option, $newvalue );
			$newvalue = maybe_serialize( $newvalue );
			$result = $wpdb->update( $wpdb->options, array( 'option_value' => $newvalue ), array( 'option_name' => $option ) );
			
			if ( $result ) return true;
		}

		return false;
	} // End _update_option().
	
	
	
	/*	self::reset_options()
	 *	
	 *	Reset plugin options to defaults passed in $settings in init.php's default_options section ( $settings['default_options'] ). Use with caution.
	 *	
	 *	@param		boolean		$verify		Must pass boolean value of true to be able to reset. Safety mechanism.
	 *	@return		boolean					true on reset, false otherwise.
	 */
	public static function reset_options( $verify = false ) {
		if ( $verify === false ) {
			return false;
		} else {
			self::$options = (array)self::settings( 'default_options' );
			self::save();
			return true;
		}
	} // End reset_options().
	
	
	
	/**
	 *	anti_directory_browsing()
	 *
	 *	Helps security by attempting to block directory browsing by creating
	 *	both index.htm files and .htaccess files turning browsing off.
	 *
	 *	@param		string		$directory		Full absolute pass to insert anti-directory-browsing files into. No trailing slash.
	 *	@return		boolean						True on success securing directory, false otherwise.
	 */
	public static function anti_directory_browsing( $directory = '', $die_on_fail = true ) {
		// Create directory.
		if ( !file_exists( $directory ) ) {
			if ( self::$filesystem->mkdir( $directory ) === false ) {
				self::alert( 'Error #9002: Unable to create directory `' . $error . '`. ' );
				if ( $die_on_fail === true ) {
					die( 'Script halted for security. Please verify permissions and try again.' );
				}
				return false;
			}
		}
		
		$error = '';
		if ( false === file_put_contents( $directory . '/index.php', '<html></html>' ) ) {
			$error .= 'Unable to write index.php file. ';
		}
		if ( false === file_put_contents( $directory . '/index.htm', '<html></html>' ) ) {
			$error .= 'Unable to write index.htm file. ';
		}
		if ( false === file_put_contents( $directory . '/index.html', '<html></html>' ) ) {
			$error .= 'Unable to write index.html file. ';
		}
		if ( false === file_put_contents( $directory . '/.htaccess', '<html></html>' ) ) {
			$error .= 'Unable to write .htaccess file. ';
		}
		
		if ( $error != '' ) { // Failure.
			self::alert( 'Error creating anti directory browsing security files. Errors: `' . $error . '`.' );
			if ( $die_on_fail === true ) {
				die( 'Script halted for security. Please verify permissions and try again.' );
			}
		} else { // Success.
			return true;
		}
	}
	
	
	
	/**
	 *	self::status()
	 *
	 *	Logs data to a CSV file. Optional unique serial identifier.
	 *	If a serial is passed then EVERYTHING will be logged to the specified serial file in addition to whatever (if anything) is logged to main status file.
	 *	Always logs to main status file based on logging settings whether serial is passed or not.
	 *
	 *	@see self::get_status().
	 *
	 *	@param	string	$type		Valid types: error, warning, details, message
	 *	@param	string	$text		Text message to log.
	 *	@param	string	$serial		Optional. Optional unique identifier for this plugin's message. Status messages are unique per plugin so this adds an additional unique layer for retrieval.
	 *	@return	null
	 */
	public static function status( $type, $message, $serial = '' ) {
			$delimiter = '|~|';
			
			// Make sure we have a unique log serial for all logs for security.
			if ( !isset( self::$options['log_serial'] ) || ( self::$options['log_serial'] == '' ) ) {
				self::$options['log_serial'] = self::random_string( 15 );
				self::save();
			}
			
			// Determine whether writing to main file.
			$write_main = false;
			if ( self::$options['log_level'] == 0 ) { // No logging.
					$write_main = false;
			} elseif ( self::$options['log_level'] == 1 ) { // Errors only.
				if ( $type == 'error' ) {
					$write_main = true;
				}
			} else { // Everything else.
				$write_main = true;
			}
			
			 // Determine whether writing to serial file.
			 $write_serial = false;
			if ( $serial != '' ) {
				$write_serial = true;
			} else {
				$write_serial = false;
			}
			
			// Return if not writing to any file.
			if ( ( $write_main !== true )  && ( $write_serial !== true ) ) {
				return;
			}
			
			// Prepare directory for log files. Return if unable to do so.
			if ( true !== self::anti_directory_browsing( WP_CONTENT_DIR . '/uploads/pluginbuddy/' ) ) { // Unable to secure directory. Fail.
				self::alert( 'Unable to create / verify anti directory browsing measures for status file `' . $status_file . '`. Log not written for security.' );
				return;
			}
			
			// Function for writing actual log CSV data. Used later.
			if ( !function_exists( 'write_status_line' ) ) {
				function write_status_line( $file, $content_array, $delimiter ) {
					$delimiter = '|~|';
					if ( false !== ( $file_handle = @fopen( $file, 'a') ) ) { // Append mode.
						//fputcsv ( $file_handle , $content_array );
						fwrite( $file_handle, trim( implode( $delimiter, $content_array ) ) . PHP_EOL );
						fclose( $file_handle );
					} else {
						self::alert( 'Unable to open file handler for status file `' . $file . '`. Unable to write status log.' );
					}
				}
			}
			
			$content_array = array(
								time(),
								round ( microtime( true ) - self::$start_time, 2 ),
								round( memory_get_peak_usage() / 1048576, 2 ),
								$type,
								str_replace( chr(9), '   ', $message ),
							);
			
			/********** MAIN LOG FILE **********/
			if ( $write_main === true ) { // WRITE TO MAIN LOG FILE.
				$main_file = WP_CONTENT_DIR . '/uploads/pluginbuddy/' . self::settings( 'slug' ) . '-' . self::$options['log_serial'] . '.txt';
				write_status_line( $main_file, $content_array, $delimiter );
			}
			
			/********** SERIAL LOG FILE **********/
			if ( $write_serial === true ) {
				$serial_file = WP_CONTENT_DIR . '/uploads/pluginbuddy/' . self::settings( 'slug' ) . '_' . $serial . '-' . self::$options['log_serial'] . '.txt';
				write_status_line( $main_file, $content_array, $delimiter );
			}
	} // End status().
	
	
	
	/*	self::get_status()
	 *	
	 *	Gets all status information logged via status(). Returns an array of arrays with logged data.
	 *	Return format: array(
	 *					array( TIMESTAMP, TYPE, MESSAGE ),
	 *					array( TIMESTAMP, TYPE, MESSAGE ),
	 *				)
	 *
	 *	@see self::status().
	 *	
	 *	@param		string		$serial				Unique identifier. Retrieves a subset of logged information based on this unique ID that was passed to status() when logging.
	 *	@param		boolean		$clear_retrieved	Default: true. On true status information will be purged after retrieval.
	 *	@return		array							Array of arrays.  Each sub-array contains three values: timestamp, type of message, and the message itself. See function description for details.
	 */
	public static function get_status( $serial = '', $clear_retrieved = true ) {
		$delimiter = '|~|';
		$status_file = WP_CONTENT_DIR . '/uploads/pluginbuddy/' . self::settings( 'slug' );
		if ( $serial != '' ) {
			$status_file .= '_' . $serial;
		}
		$status_file .= '-' . self::$options['log_serial'] . '.txt';
		
		if ( !file_exists( $status_file ) ) {
			//self::alert( 'Unable to load status file `' . $status_file . '`. It does not exist.' );
			return array( array( '0', 'warning', 'Log file does not exist; nothing written to it since last clearing?' ) );
			return false;
		}
		
		self::status( 'details', 'Getting status for serial `' . $serial . '`. Clear: `' . ( $clear_retrieved ? 'true' : 'false' ) . '`', $serial );
		
		if ( false !== ( $fh = @fopen( $status_file, 'r') ) ) { // Read write mode.
			$status_lines = array();
			while ( false !== ( $status_line = fgets( $fh ) ) ) {
				$status_lines[] = explode( $delimiter, trim( $status_line ) );
			}
			fclose( $fh );
			
			if ( $clear_retrieved === true ) {
				unlink( $status_file ); // todo: catch errors on this? supress?
			}
			
			return $status_lines;
		} else {
			self::alert( 'Unable to open file handler for status file `' . $status_file . '`. Unable to write status log.' );
		}
	} // End get_status().
	
	
	
	/**
	 *	set_greedy_script_limits()
	 *
	 *	Sets greedy script limits to help prevent timeouts, running out of memory, etc.
	 *
	 *	@return		null
	 *
	 */
	public static function set_greedy_script_limits()  {
		// Don't abort script if the client connection is lost/closed
		@ignore_user_abort( true );
		
		// Set socket timeout to 2 hours.
		@ini_set( 'default_socket_timeout', 60 * 60 * 2 );
		
		// Set maximum runtime to 2 hours.
		$original_maximum_runtime = ini_get( 'max_execution_time' );
		@set_time_limit( 60 * 60 * 2 );
		self::status( 'details', 'Attempted to increase maximum PHP runtime. Original: ' . $original_maximum_runtime . '; New: ' . @ini_get( 'max_execution_time' ) . '.' );
		
		// Increase the memory limit
		$current_memory_limit = trim( @ini_get( 'memory_limit' ) );
		
		// Make sure a minimum memory limit of 256MB is set.
		if ( preg_match( '/(\d+)(\w*)/', $current_memory_limit, $matches ) ) {
			$current_memory_limit = $matches[1];
			$unit = $matches[2];
			// Up memory limit if currently lower than 256M.
			if ( 'g' !== strtolower( $unit ) ) {
				if ( ( $current_memory_limit < 256 ) || ( 'm' !== strtolower( $unit ) ) )
					@ini_set('memory_limit', '256M');
					self::status( 'details', __('Set memory limit to 256M. Previous value < 256M.', 'it-l10n-frolic') );
			}
		} else {
			// Couldn't determine current limit, set to 256M to be safe.
			@ini_set('memory_limit', '256M');
			self::status( 'details', __('Set memory limit to 256M. Previous value unknown.', 'it-l10n-frolic') );
		}
		self::status( 'details', 'Original PHP memory limit: ' . $current_memory_limit . '; New: ' . @ini_get( 'memory_limit' ) . '.' );
	}
	
	
	
	/*	self::debug()
	 *	
	 *	Displays debugging information on screen via a toggleable box. Floats right. Includes all logged information logged in status() and self::$options.
	 *
	 *	@see self::status().
	 *	@see self::get_status().
	 *	
	 *	@return		null
	 */
	public static function debug( $serial = '', $clear_retrieved = true ) {
		echo '<div class="pb_debug"><span class="pb_debug_show">Debug</span><span class="pb_debug_hide">Hide</span>';
		echo '<div class="pb_debug_content">';
		
		
		// Status log:
		if ( $serial == '' ) {
			echo '<b>Debugging Status Information (all; no serial):</b>';
		} else {
			echo '<b>Debugging Status Information (serial: `' . $serial . '`):</b>';
		}
		echo '<textarea wrap="off">';
		$status = self::get_status( $serial , $clear_retrieved );
		foreach( (array)$status as $status_item ) { // $status_item: time, type, message
			//echo '<pre>' . print_r( $status_item, true ) . '</pre>';
			echo $status_item[0] . chr(9) . $status_item[1] . 'sec' . chr(9) . $status_item[2] . 'MB' . chr(9) . $status_item[3] . chr(9) .  chr(9) . $status_item[4] . "\n";
		}
		unset( $status );
		echo '</textarea>';
		
		
		// self::$options
		echo '<b>Debugging Options Array:</b><textarea wrap="off">';
		print_r( self::$options );
		echo '</textarea>';
				
		
		echo '</div>';
		echo '</div>';
	} // End debug().
	
	
	
	/**
	 *	self::log()
	 *
	 *	Logs to a text file depending on settings.
	 *	0 = none, 1 = errors only, 2 = errors + warnings, 3 = debugging (all kinds of actions)
	 *
	 *	@param	string	$text		Text to log.
	 *	@param	string	$log_type	Valid options: error, warning, all (default so may be omitted).
	 *	@return	null
	 */
	public static function log( $text, $log_type = 'all' ) {
		if ( defined( 'PB_DEMO_MODE' ) || !isset( self::$options['log_level'] ) || ( self::$options['log_level'] == 0 ) ) { // No logging in this plugin or disabled.
			return;
		}
		
		$write = false;
		if ( self::$options['log_level'] == 1 ) { // Errors only.
			if ( $log_type == 'error' ) {
				$write = true;
			}
		} else { // All logging (debug mode).
			$write = true;
		}
		
		if ( $write === true ) {
			if ( !isset( self::$options['log_serial'] ) ) {
				self::$options['log_serial'] = self::random_string( 15 );
				self::save();
			}
			$fh = @fopen( WP_CONTENT_DIR . '/uploads/' . self::settings( 'slug' ) . '-' . self::$options['log_serial'] . '.txt', 'a');
			if ( $fh ) {
				fwrite( $fh, '[' . date( 'M j, Y H:i:s ' . get_option( 'gmt_offset' ), time() + (get_option( 'gmt_offset' )*3600) ) . '-' . $log_type . '] ' . $text . "\n" );
				fclose( $fh );
			}
		}
	} // End log().
	
	
	
	/*	self::random_string()
	 *	
	 *	Generate a random string of characters.
	 *	
	 *	@param		
	 *	@return		
	 */
	public static function random_string( $length = 32, $chars = 'abcdefghijkmnopqrstuvwxyz1234567890' ) {
		$chars_length = ( strlen( $chars ) - 1 );
		$string = $chars{rand(0, $chars_length)};
		for ( $i = 1; $i < $length; $i = strlen( $string ) ) {
			$r = $chars{rand(0, $chars_length)};
			if ( $r != $string{$i - 1} ) $string .=  $r;
		}
		return $string;
	} // End random_string().
	
	
	
	/**
	 *	self::video()
	 *
	 *	Displays a message to the user when they hover over the question mark. Gracefully falls back to normal tooltip.
	 *	HTML is supposed within tooltips.
	 *
	 *	@param		string		$video_key		YouTube video key from the URL ?v=VIDEO_KEY_HERE
	 *	@param		string		$title			Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
	 *	@param		boolean		$echo_tip		Whether to echo the tip (default; true), or return the tip (false). (optional)
	 *	@return		string/null					If not echoing tip then the string will be returned. When echoing there is no return.
	 */
	public static function video( $video_key, $title = '', $echo_tip = true ) {
		return self::$ui->video( $video_key, $title, $echo_tip );
	} // End video().
	
	
	
	/**
	 *	self::alert()
	 *
	 *	Displays a message to the user at the top of the page when in the dashboard.
	 *
	 *	@param		string		$message		Message you want to display to the user.
	 *	@param		boolean		$error			OPTIONAL! true indicates this alert is an error and displays as red. Default: false
	 *	@param		int			$error_code		OPTIONAL! Error code number to use in linking in the wiki for easy reference.
	 *	@return		string/null					If not echoing alert then the string will be returned. When echoing there is no return.
	 */
	public static function alert( $message, $error = false, $error_code = '' ) {
		self::$ui->alert( $message, $error, $error_code );
	} // End alert().
	
	
	
	/**
	 *	self::tip()
	 *
	 *	Displays a message to the user when they hover over the question mark. Gracefully falls back to normal tooltip.
	 *	HTML is supposed within tooltips.
	 *
	 *	@param		string		$message		Actual message to show to user.
	 *	@param		string		$title			Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
	 *	@param		boolean		$echo_tip		Whether to echo the tip (default; true), or return the tip (false). (optional)
	 *	@return		string/null					If not echoing tip then the string will be returned. When echoing there is no return.
	 */
	public static function tip( $message, $title = '', $echo_tip = true ) {
		self::init_class_controller( 'ui' ); // $ui class required pages controller and may not be set up if not in our own pages.
		return self::$ui->tip( $message, $title, $echo_tip );
	} // End tip().
	
	
	
	/*	self::add_page()
	 *	
	 *	Adds a page into the admin. Stores menu items to add in self::$_page_settings array. Registers callback to register_admin_menu() with WordPress to actually set up the pages.
	 *	@see self::register_admin_menu()
	 *	
	 *	@param		string		$parent_slug		Slug of the parent menu item to go under. If a series use `SERIES` for the value to automatically handle the series. PB prefix automatically applied unless $slug_prefix overrides.
	 *	@param		string		$page_slug			Slug for this page. PB prefix automatically applied unless $slug_prefix overrides.
	 *	@param		string		$page_title			Title of the page. If this menu item has no parent this can be an array of TWO titles. The root menu and the first submenu item that links to the same place.
 	 *	@param		string		$capability			Capability required to access page. Default: activate_plugins.
 	 *	@param		string		$icon				Menu icon graphic. Automatically prefixes this value with the full URL to plugin's images directory. Default: icon_16x16.png.
 	 *	@param		string		$slug_prefix		Prefix to use with this menu. Override if needing to add menu under another plugin or core menus. Default: DEFAULT.
 	 *	@param		int			$position			Priority on where in the menu to add this. By default it is added to the bottom of the menu. It's possible to overwrite another menu item if this number matches. Use caution. Default: null.
	 *	@return		null
	 */
	public static function add_page( $parent_slug, $page_slug, $page_title, $capability = 'activate_plugins', $icon = 'icon_16x16.png', $slug_prefix = 'DEFAULT', $position = NULL ) {
		if ( $slug_prefix == 'DEFAULT' ) {
			$slug_prefix = 'pb_' . self::settings( 'slug' ) . '_';
		}
		
		if ( !is_object( self::$_pages ) ) {
			self::_init_core_controller( 'pages' );
			
			add_action( 'admin_menu', create_function( '', 'pb_' . self::settings( 'slug' ) . '::register_admin_menu();' ) );
		}
		
		self::$_page_settings[] = array(
			'parent'  =>  $parent_slug,
			'slug'   =>  $page_slug,
			'title'   =>  $page_title,
			'capability' =>  $capability,
			'icon'   =>  $icon,
			'slug_prefix' =>  $slug_prefix,
			'position'  =>  $position,
		);
	} // End add_page().
	
	
	
	/*	register_admin_menu()
	 *	
	 *	Internal callback for actually registering the menu items into WordPress. Registers pages defined by self::add_page().
	 *	@see self::add_page()
	 *	
	 *	@return		null
	 */
	public static function register_admin_menu() {
		if ( !self::blank( self::$_settings['series'] ) ) { // SERIES
			$series_slug = 'pb_' . self::$_settings['series'];
			// We need to see first if this series' root menu has been created by a plugin yet.
			global $menu;
			$found_series = false;
			foreach ( $menu as $menus => $item ) { // Loop through existing menu items looking for our series.
				if ( $item[0] == $series_slug ) {
					$found_series = true;
				}
			}
			if ( $found_series === false ) { // Series root menu does not exist; create it.
				add_menu_page( self::$_settings['series'] . ' Getting Started', self::$_settings['series'], apply_filters( 'frolic_capability', 'switch_themes' ), $series_slug, array( &self::$_pages, 'getting_started' ), self::plugin_url() . '/images/series_icon_16x16.png' ); // , $page['position']
				add_submenu_page( $series_slug, self::$_settings['series'] . ' Getting Started', 'Getting Started', apply_filters( 'frolic_capability', 'switch_themes' ), $series_slug, array( &self::$_pages, 'getting_started' ) );
			}
			
			// Register for getting started page.
			global $pluginbuddy_series;
			if ( !isset( $pluginbuddy_series[ self::$_settings['series'] ] ) ) {
				$pluginbuddy_series[ self::$_settings['series'] ] = array();
			}
			
			// Add this plugin into global series variable.
			$pluginbuddy_series[ self::$_settings['series'] ][ self::$_settings['slug'] ] = array(
				'path'		=>	self::plugin_path(),
				'name'		=>	self::settings( 'name' ),
				'slug'		=>	self::settings( 'slug' ),
			);
		}
		
		// Add all registered pages for this plugin.
		foreach ( self::$_page_settings as $page ) {
			$menu_slug = $page['slug_prefix'] . $page['slug'];
			if ( $page['parent'] == 'SERIES' ) { // Adding page into series.
				$parent_slug = 'pb_' . self::$_settings['series'];
				if ( self::blank( self::$_settings['series'] ) ) { // No series set but menu is registered into a series.
					echo '{WARNING: Menu item registered into a series but no plugin series is defined.}';
				}
			} else { // Non-series page.
				$parent_slug = $page['slug_prefix'] . $page['parent'];
			}

			if ( is_array( $page['title'] ) ) {
				$page_title = $page['title'][0];
				$page_title_alt = $page['title'][1];
			} else { // Not an array so only one page title.
				$page_title = $page['title'];
				$page_title_alt = $page['title'];
			}

			if ( self::blank( $page['parent'] ) ) { // Top-level menu.
				if ( is_multisite() ) { 
					add_menu_page( $page_title, $page_title, apply_filters( 'frolic_capability', 'switch_themes' ), $menu_slug, array( &self::$_pages, $page['slug'] ), self::plugin_url() . '/images/' . $page['icon'], $page['position'] );
					add_submenu_page( $menu_slug, self::settings( 'name' ) . ' ' . $page_title_alt, $page_title_alt, apply_filters( 'frolic_capability', 'switch_themes' ), $menu_slug, array( &self::$_pages, $page['slug'] ) ); // Allows naming of first submenu item differently from the parent. Else its auto created with same name.
				} else {
					add_menu_page( $page_title, $page_title, apply_filters( 'frolic_capability', 'switch_themes' ), $menu_slug, array( &self::$_pages, $page['slug'] ), self::plugin_url() . '/images/' . $page['icon'], $page['position'] );
					add_submenu_page( $menu_slug, self::settings( 'name' ) . ' ' . $page_title_alt, $page_title_alt, apply_filters( 'frolic_capability', 'switch_themes' ), $menu_slug, array( &self::$_pages, $page['slug'] ) ); // Allows naming of first submenu item differently from the parent. Else its auto created with same name.
				}
			} else { // Sub-menu.
				if ( is_multisite() ) {
					add_submenu_page( $parent_slug, self::settings( 'name' ) . ' ' . $page_title, $page_title, apply_filters( 'frolic_capability', 'switch_themes' ), $menu_slug, array( &self::$_pages, $page['slug'] ) );
				} else {
					add_submenu_page( $parent_slug, self::settings( 'name' ) . ' ' . $page_title, $page_title, apply_filters( 'frolic_capability', 'switch_themes' ), $menu_slug, array( &self::$_pages, $page['slug'] ) );
				}
			}
		}
	} // End register_admin_menu().
	
	
	
	/*	self::add_action()
	 *	
	 *	Registers a WordPress action. Action of the name $tag will call the method in /controllers/actions.php with the matching name.
	 *	
	 *	@param		string/array	$tag				Tag / slug for the action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@param		int				$priority			Integer priority number for the action.
	 *	@param		int				$accepted_args		Number of arguments this action may accept in its method.
	 *	@return		null
	 */
	public static function add_action( $tag, $priority = 10, $accepted_args = 1 ) {
		if ( !is_object( self::$_actions ) ) { self::_init_core_controller( 'actions' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_action( $tag, array( &self::$_actions, $callback_method ), $priority, $accepted_args );
	} // End add_action().
	
	
	
	/*	self::add_ajax()
	 *	
	 *	Registers a WordPress ajax action. Ajax action of the name $tag will call the method in /controllers/ajax.php with the matching name.
	 *	
	 *	@param		string/array		$tag				Tag / slug for the action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@return		null
	 */
	public static function add_ajax( $tag ) {
		if ( !is_object( self::$_ajax ) ) { self::_init_core_controller( 'ajax' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_action( 'wp_ajax_pb_' . self::settings( 'slug' ) . '_' . $tag, array( &self::$_ajax, $callback_method ) );
	} // End add_ajax().
	
	
	
	/*	self::add_cron()
	 *	
	 *	Registers a WordPress action. Cron of the name $tag will call the method in /controllers/cron.php with the matching name.
	 *	TODO: make this work.
	 *	
	 *	@param		string/array		$tag				Tag / slug for the action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@return		null
	 */
	public static function add_cron( $tag ) {
		echo 'TODO: Add cron support.';
	} // End add_cron().
	
	
	
	/*	self::add_dashboard_widget()
	 *	
	 *	Registers a WordPress action. Action of the name $tag will call the method in /controllers/dashboard.php with the matching name.
	 *	
	 *	@param		string/array	$tag				Tag / slug for the action.
	 *	@param		string			$title				Dashboard widget title.
	 *	@param		boolean			$accepted_args		Number of arguments this action may accept in its method.
	 *	@return		null
	 */
	public static function add_dashboard_widget( $tag, $title, $force_top = false ) {
		if ( !is_object( self::$_dashboard ) ) {
			self::$_dashboard_widgets = array(); // Init variable.

			self::_init_core_controller( 'dashboard' );
			add_action( 'wp_dashboard_setup', array( &self::$_dashboard, 'register_widgets' ) );
		}
		self::$_dashboard_widgets[] = array( 'tag' => $tag, 'title' => $title, 'force_top' => $force_top ); // Push into array to be later registered via dashboard controller's register_widgets function.
	} // End add_dashboard_widget().
	
	
	
	/*	self::add_filter()
	 *	
	 *	Registers a WordPress filter. Filter of the name $tag will call the method in /controllers/filters.php with the matching name.
	 *	
	 *	@param		string/array		$tag				Tag / slug for the action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@param		int				$priority			Integer priority number for the filter.
	 *	@param		int				$accepted_args		Number of arguments this filter may accept in its method.
	 */
	public static function add_filter( $tag, $priority = 10, $accepted_args = 1 ) {
		if ( !is_object( self::$_filters ) ) { self::_init_core_controller( 'filters' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_filter( $tag, array( &self::$_filters, $callback_method ), $priority, $accepted_args );
	} // End add_filter().
	
	
	
	/*	self::add_shortcode()
	 *	
	 *	Registers a WordPress shortcode. Shortcode of the name $tag will call the method in /controllers/shortcodes.php with the matching name.
	 *	
	 *	@param		string/array		$tag				Tag / slug for the shortcode. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@return		
	 */
	public static function add_shortcode( $tag ) {
		if ( !is_object( self::$_shortcodes ) ) { self::_init_core_controller( 'shortcodes' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_shortcode( $tag, array( &self::$_shortcodes, $callback_method ) );
	} // End add_shortcode().
	
	
	
	/*	self::init_class_controller()
	 *	
	 *	Registers the UI class into the pluginbuddy framework for pages. Registered on demand by pages controller.
	 *	@see pages controller
	 *
	 *	@return		null
	 */
	public static function init_class_controller( $class_slug ) {
		if ( !is_object( self::$$class_slug ) ) {
			$class_file = self::plugin_path() . '/pluginbuddy/classes/' . $class_slug . '.php';
			if ( file_exists( $class_file ) ) {
				require_once( $class_file );
				$class_name = 'pb_' . self::settings( 'slug' ) . '_' . $class_slug;
				self::$$class_slug = new $class_name();
			} else {
				echo '{Error: Missing class controller file `' . $class_file . '`.}';
			}
		}
	}
	
	
	
	/*	self::_init_core_controller()
	 *	
	 *	Initialize a core controller class (ex: pages, ajax, filters, etc) for pluginbuddy framework usage.
	 *	
	 *	@param		string		$name		Name of the controller to register. Valid controllers: actions, ajax, cron, dashboard, filters, shortcodes, pages.
	 *	@return		
	 */
	private static function _init_core_controller( $name ) {
		if ( !is_array( self::$options ) ) { self::load(); } // Assume we need plugin options need loaded if controllers are loaded for this session.
		
		require_once( self::$_plugin_path . '/controllers/' . $name . '.php' );
		$classname = 'pb_frolic_' . $name;
		$internal_classname = '_' . $name;
		self::$$internal_classname = new $classname();
	} // End _init_core_controller().
	
	
	
	/*	self::nonce()
	 *	
	 *	Echos or returns a WordPress nonce for the framework. Handles prefixing. Use with forms for security. Verifies the user came from a WP generated page.
	 *	
	 *	@param		boolean		$echo		True: echos the none; false: returns nonce.
	 *	@return		null/string				Returns null or string based on $echo value.
	 */
	public static function nonce( $echo = true ) {
		return wp_nonce_field( 'pb_' . self::settings( 'name' ) . '-nonce', '_wpnonce', true, $echo );
	} // End nonce().
	
	
	
	/*	self::verify_nonce()
	 *	
	 *	Verifies the nonce submitted in form.
	 *	
	 *	@return		null/true		Script die()'s on failure, returns true on success.
	 */
	public static function verify_nonce() {
		check_admin_referer( 'pb_' . self::settings( 'name' ) . '-nonce' );
	} // End verify_nonce().
	
	
	
	/*	self::load_script()
	 *	
	 *	Load a JavaScript file into the page. Handles prefixed, enqueuing, etc.
	 *	
	 *	@param		string		$script			If a .js file is included then a file in the js directory is loaded; else loads a built-in named library script.
	 *											Ex: load_script( 'sort.js' ) will load /wp-content/plugins/my_plugin/js/sort.js; load_script( 'jquery' ) will load internal jquery library in WordPress if it exists.
	 *	@param		boolean		$core_script	If true scripts are loaded from /pluginbuddy/js/SCRIPT.js. Else scripts loaded from plugin's js directory.
	 *	@return		null
	 */
	public static function load_script( $script, $core_script = false ) {
		if ( strstr( $script, '.js' ) ) { // Loading a file specifically.
			if ( $core_script === true ) {
				$local_path = self::$_plugin_path . '/pluginbuddy/js/';
				$url_path = self::$_plugin_url . '/pluginbuddy/js/';
			} else {
				$local_path = self::$_plugin_path . '/js/';
				$url_path = self::$_plugin_url . '/js/';
			}
			$script_name = 'pb_' . self::settings( 'slug' ) . '_' . $script;
			if ( !wp_script_is( $script_name ) ) { // Only load script once.
				if ( file_exists( $local_path . $script ) ) { // Load our local script if file exists.
					wp_enqueue_script( $script_name, $url_path . $script );
					wp_print_scripts( $script_name );
				} else {
					echo '{Error: Javascript file was set to load that did not exist: `' . $url_path . $script . '`}';
				}
			}
		} else { // Not a specific file.
			if ( !wp_script_is( $script ) ) { // Only load script once.
				wp_enqueue_script( $script );
				wp_print_scripts( $script );
			}
		}
	} // End load_script().
	
	
	
	/*	self::load_style()
	 *	
	 *	Load a CSS file into the page. Handles prefixed, enqueuing, etc.
	 *	
	 *	@param		string		$style			If a .css file is included then a file in the css directory is loaded; else loads a built-in named library style.
	 *											Ex: load_style( 'sort.css' ) will load /wp-content/plugins/my_plugin/css/sort.css; load_style( 'dashboard' ) will load internal dashboard css in WordPress if it exists.
	 *	@param		boolean		$core_style		If true styles are loaded from /pluginbuddy/css/STYLE.css. Else styles loaded from plugin's css directory.
	 *	@return		null
	 */
	public static function load_style( $style, $core_style = false ) {
		if ( strstr( $style, '.css' ) ) { // Loading a file specifically.
			if ( $core_style === true ) {
				$local_path = self::$_plugin_path . '/pluginbuddy/css/';
				$url_path = self::$_plugin_url . '/pluginbuddy/css/';
			} else {
				$local_path = self::$_plugin_path . '/css/';
				$url_path = self::$_plugin_url . '/css/';
			}
			$style_name = 'pb_' . self::settings( 'slug' ) . '_' . $style;
			if ( !wp_style_is( $style_name ) ) { // Only load style once.
				if ( file_exists( $local_path . $style ) ) { // Load our local style if file exists.
					wp_enqueue_style( $style_name, $url_path . $style );
					wp_print_styles( $style_name );
				} else {
					echo '{Error: CSS file was set to load that did not exist: `' . $url_path . $style . '`}';
				}
			}
		} else { // Not a specific file.
			if ( !wp_style_is( $style ) ) { // Only load style once.
				wp_enqueue_style( $style );
				wp_print_styles( $style );
			}
		}
	} // End load_style().
	
	
	
	/*	self::load_view()
	 *	
	 *	Loads a view. Typically called from within a controller. Data passed as second argument will has extract() ran on it within the view for easy variable access.
	 *	
	 *	@param		string		$view_name				Name of view. Corresponds to the view filename: /views/view_name.php
	 *	@param		array		$pluginbuddy_data		Array of variables to be extracted for use by the view.
	 *	@return		null
	 */
	public static function load_view( $view_name, $pluginbuddy_data = array() ) {
		$pluginbuddy_view_file = self::$_plugin_path . '/views/' . $view_name . '.php'; // Variable named this way as the included file inherits this variable and we don't want an accidental collision.
		if ( file_exists( $pluginbuddy_view_file ) ) {
			unset( $view_name );
			if ( is_array( $pluginbuddy_data ) ) {
				extract( $pluginbuddy_data );
			} else {
				echo '{Warning: Data parameter passed to view was not an array.}';
			}
			//global $dionysus_controller; // Gives the view the ability to access functions within the controller if needed.
			require $pluginbuddy_view_file;
		} else {
			echo '{INVALID VIEW: `' . $view_name . '`; file not found.}';
		}
	} // End load_view().
	
	
	
	/*	self::load_controller()
	 *	
	 *	Loads a controller. Controllers may load controllers. Controller uses require_once to avoid problems.
	 *	
	 *	@param		string		$controller				Name of controller. Corresponds to the controller filename: /controllers/controller_name.php
	 *	@return		null
	 */
	public static function load_controller( $controller ) {
		// Using this method so load_controller() may be used anywhere.
		if ( file_exists( self::plugin_path() . '/controllers/' . $controller . '.php' ) ) {
			require_once( self::plugin_path() . '/controllers/' . $controller . '.php' );
		} else {
			echo '{Error: Unable to load page controller `' . $controller . '`; file not found.}';
		}
		/*
		if ( file_exists( self::plugin_path() . '/controllers/' . $controller . '.php' ) ) {
			require_once( self::plugin_path() . '/controllers/' . $controller . '.php' );
		} else {
			echo '{Error: Unable to load controller `' . $controller . '`; file not found.}';
		}
		*/
		
		//self::$_pages->load_controller( $controller );
	} // End load_controller().
	
	
	
	/*	self::register_widget()
	 *	
	 *	Registers a widget. Will register widget class in /controllers/widget/slug.php. Widget class extend WP_Widgets.
	 *	
	 *	@param		string		$slug		Name / slug for widget. Must match filename in controllers\widgets\ directory. Class name in the format: pb_{PLUGINSLUG}_widget_{WIDGETSLUG}
	 *	@return		null
	 */
	public static function register_widget( $slug ) {
		if ( file_exists( self::plugin_path() . '/controllers/widgets/' . $slug . '.php' ) ) {
			require( self::plugin_path() . '/controllers/widgets/' . $slug . '.php' );
			add_action( 'widgets_init', create_function( '', 'register_widget(\'pb_' . self::settings( 'slug' ) . '_widget_' . $slug . '\');' ) );
		} else {
			echo '{Error #3444548922: Unable to load widget file `controllers/widgets/' . $slug . '.php`.}';
		}
	} // End register_widget().
	
	
	
} // End class pluginbuddy.



// ********** Load core classes **********

require_once( dirname( __FILE__ ) . '/classes/core_controllers.php' );
//require_once( dirname( __FILE__ ) . '/classes/ui.php' ); Now handled in self::_construct().
require_once( dirname( __FILE__ ) . '/classes/form.php' );
require_once( dirname( __FILE__ ) . '/classes/settings.php' );



// ********** Initialize PluginBuddy framework **********

pb_frolic::init( $pluginbuddy_settings );
unset( $pluginbuddy_settings );



// ********** Load initialization files **********

require_once( dirname( dirname( __FILE__ ) ) . '/init_global.php' );

if ( is_admin() ) {
	require_once( dirname( dirname( __FILE__ ) ) . '/init_admin.php' );
} else {
	require_once( dirname( dirname( __FILE__ ) ) . '/init_public.php' );
}


?>