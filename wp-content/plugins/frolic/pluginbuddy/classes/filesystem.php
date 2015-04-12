<?php



/*	class pluginbuddy_filesystem
 *	@author Dustin Bolton
 *	
 *	Handles interfacing with the file system.
 */
class pb_frolic_filesystem {
	
	
	
	// ********** PUBLIC PROPERTIES **********
	
	
	
	// ********** PRIVATE PROPERTIES **********
	
	
	
	// ********** FUNCTIONS **********
	
	
	
	/*	pluginbuddy_filesystem->__construct()
	 *	
	 *	Default constructor.
	 *	
	 *	@return		null
	 */
	function __construct() {
		
	} // End __construct().
	
	
	
	/*	pb_frolic::$filesystem->mkdir()
	 *	
	 *	mkdir that defaults to recursive behaviour. 99% of the time this is what we want.
	 *	
	 *	@param		$pathname		string		Path to create.
	 *	@param		$mode			int			Default: 0777. See PHP's mkdir() function for details.
	 *	@param		$recursive		boolean		Default: true. See PHP's mkdir() function for details.
	 *	@return						boolean		Returns TRUE on success or FALSE on failure.
	 */
	public static function mkdir( $pathname, $mode = 0777, $recursive = true) {
		return mkdir( $pathname, $mode, $recursive );
	} // End mkdir().
	
	
	
	/*	pluginbuddy_filesystem->unlink_recursive()
	 *	
	 *	Unlink a directory recursively. Files all files and directories within. USE WITH CAUTION.
	 *	
	 *	@param		string		$dir		Directory to delete -- all contents within, subdirectories, files, etc will be permanently deleted.
	 *	@return		boolean					True on success; else false.
	 */
	function unlink_recursive( $dir ) {
		if ( defined( 'PB_DEMO_MODE' ) ) {
			return false;
		}
		
		if ( !file_exists( $dir ) ) {
			return true;
		}
		if ( !is_dir( $dir ) || is_link( $dir ) ) {
			return unlink($dir);
		}
		foreach ( scandir( $dir ) as $item ) {
			if ( $item == '.' || $item == '..' ) {
				continue;
			}
			if ( !$this->unlink_recursive( $dir . "/" . $item ) ) {
				chmod( $dir . "/" . $item, 0777 );
				if ( !$this->unlink_recursive( $dir . "/" . $item ) ) {
					return false;
				}
			}
		}
		return rmdir($dir);
	} // End unlink_recursive().
	
	
	
	/**
	 *	pluginbuddy_filesystem->deepglob()
	 *
	 *	Like the glob() function except walks down into paths to create a full listing of all results in the directory and all subdirectories.
	 *	This is essentially a recursive glob() although it does not use recursion to perform this.
	 *
	 *	@param		string		$dir		Path to pass to glob and walk through.
	 *	@return		array					Returns array of all matches found.
	 */
	function deepglob( $dir ) {
		$items = glob( $dir . '/*' );
		
		for ( $i = 0; $i < count( $items ); $i++ ) {
			if ( is_dir( $items[$i] ) ) {
				$add = glob( $items[$i] . '/*' );
				$items = array_merge( $items, $add );
			}
		}
		
		return $items;
	} // End deepglob().
	
	
	// todo: document
	// $exclusions is never modified so just use PHP's copy on modify default behaviour for memory management.
	/*	function_name()
	 *	
	 *	function description
	 *	@param		array/boolean		Array of directory paths to exclude.  If true then this directory is excluded so no need to check with exclusion directory.
	 *	@return		array		array( TOTAL_DIRECTORY_SIZE, TOTAL_SIZE_WITH_EXCLUSIONS_TAKEN_INTO_ACCOUNT )
	 */
	function dir_size_map( $dir, $base, $exclusions, &$dir_array ) {
		if( !is_dir( $dir ) ) {
			return 0;
		}
		
		$ret = 0;
		$ret_with_exclusions = 0;
		$exclusions_result = $exclusions;
		$sub = opendir( $dir );
		while( $file = readdir( $sub ) ) {
			$exclusions_result = $exclusions;
			
			$dir_path = str_replace( $base, '', $dir . '/' . $file );
			
			if ( ( $file == '.' ) || ( $file == '..' ) ) {
				// Do nothing.
			} elseif ( is_dir( $dir . '/' . $file ) ) {
				
				if ( ( $exclusions === true ) || self::in_array_substr( $exclusions, $dir_path ) ) {
					$exclusions_result = true;
				}
				$result = $this->dir_size_map( $dir . '/' . $file, $base, $exclusions_result, $dir_array );
				$this_size = $result[0];
				
				if ( $exclusions_result === true ) { // If excluding then wipe excluded value.
					$this_size_with_exclusions = false;
				} else {
					$this_size_with_exclusions = $result[1]; // / 1048576 );
				}
				
				$dir_array[ $dir_path ] = array( $this_size, $this_size_with_exclusions ); // $dir_array[ DIRECTORY_PATH ] = DIRECTORY_SIZE;
				
				$ret += $this_size;
				$ret_with_exclusions += $this_size_with_exclusions;
				
				unset( $file );
			} else { // FILE.
				$stats = stat( $dir . '/' . $file );
				$ret += $stats['size'];
				if ( ( $exclusions !== true ) && !self::in_array_substr( $exclusions, $dir_path ) ) { // Not excluding.
					$ret_with_exclusions += $stats['size'];
				}
				unset( $file );
			}
		}
		closedir( $sub );
		unset( $sub );
		return array( $ret, $ret_with_exclusions );
	} // End dir_size().
	
	
	public static function in_array_substr( $haystack, $needle ) {
		foreach( $haystack as $hay ) {
			if ( $hay == substr( $needle, 0, strlen( $hay ) ) ) {
				//echo $needle . '~' . $hay . '<br>';
				return true;
			}
		}
		
		return false;
	}
	
	
	public function exit_code_lookup( $code ) {
		switch( (string)$code ) {
			case '0':
				return 'Command completed & returned normally.';
				break;
			case '127':
				return 'Command not found.';
				break;
			default:
				return '-No information available for this exit code-';
				break;
		}
	}
	
	
} // End class pluginbuddy_settings.



?>