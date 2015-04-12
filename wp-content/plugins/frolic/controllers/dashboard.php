<?php
//namespace pluginbuddy\frolic;

class pb_frolic_dashboard extends pb_frolic_dashboardcore {



	public function pb_twitter() {
		$each = pb_frolic::$options['accounts'];
		if( pb_frolic::$options['enable_twitter_dash'] == 'true' ){
			foreach ( $each as $id => $account ) {
				
				if( ($account['type'] == 'twitter') && ($account['dash_display'] == 'true') && ( $account['enabled'] == 'true' ) ) {
					foreach( $account['objects'] as $obj_id => $object ) {
						if( $object['type'] == 'feed' ) {
							echo '<hr/>';
							require_once(pb_frolic::plugin_path() . '/js/twitter.javascript.php');
							pb_frolic::load_controller( '_run_twitter' );
							echo run_twitter( $id, $obj_id );
						}
						if( $object['type'] == 'mention' ) {
							require_once(pb_frolic::plugin_path() . '/js/twitter.javascript.php');
							pb_frolic::load_controller( '_run_twitter' );
							echo run_twitter( $id, $obj_id );
							echo '<br/>';
						}
						
						
					}
				}	
			}
		}
	}
	public function pb_facebook() {
		$each_fb = pb_frolic::$options['accounts'];
		if( pb_frolic::$options['enable_facebook_dash'] == 'true' ){
			foreach ( $each_fb as $fb_id => $account ) {
				
				if( ( $account['type'] == 'facebook' ) && ( $account['dash_display'] == 'true' ) && ( $account['enabled'] == 'true' ) ) {
					foreach( $account['objects'] as $fb_obj_id => $object ){
						if($object['type'] == 'activity_feed' ){
							require_once(pb_frolic::plugin_path() . '/js/facebook.javascript.php');
							pb_frolic::load_controller( '_run_facebook' );
							echo run_facebook( $fb_id, $fb_obj_id );
							
						}
						if($object['type'] == 'comments' ){
							require_once(pb_frolic::plugin_path() . '/js/facebook.javascript.php');
							pb_frolic::load_controller( '_run_facebook' );
							echo run_facebook( $fb_id, $fb_obj_id );
							
						}
					}
				}
			}
		}
	}
	
}
?>
