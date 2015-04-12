<?php
//namespace pluginbuddy\frolic;

class pb_frolic_shortcodes extends pb_frolic_shortcodescore {
	
	public static function pb_frolic( $instance, $content = '' ) {
		
		if ( ( $instance['account'] != '' ) && ( $instance['object'] != '' ) && ( isset( pb_frolic::$options['accounts'][$instance['account']] ) ) && ( pb_frolic::$options['accounts'][$instance['account']]['enabled'] == 'true' ) ) {
			
			//DO NOT FORGET TO ADD A BREAK AFTER EVERY NEW CASE!!!!!!!!
			switch ( pb_frolic::$options['accounts'][$instance['account']]['type'] ) {
				case 'facebook':
					require_once(pb_frolic::plugin_path() . '/js/facebook.javascript.php');
					pb_frolic::load_controller( '_run_facebook' );
					return run_facebook( $instance['account'], $instance['object'] );
					break;
				case 'twitter':
					require_once(pb_frolic::plugin_path() . '/js/twitter.javascript.php');
					pb_frolic::load_controller( '_run_twitter' );
					return run_twitter( $instance['account'], $instance['object'] );
					break;
				case 'google':
					require_once(pb_frolic::plugin_path() . '/js/gplus.javascript.php');
					pb_frolic::load_controller( '_run_googleplus' );
					return run_googleplus( $instance['account'], $instance['object'] );
					break;
				case 'linkedin':
					require_once(pb_frolic::plugin_path() . '/js/linkedin.javascript.php');
					pb_frolic::load_controller( '_run_linkedin' );
					return run_linkedin( $instance['account'], $instance['object'] );
					break;
			}
		}
	}
}

?>