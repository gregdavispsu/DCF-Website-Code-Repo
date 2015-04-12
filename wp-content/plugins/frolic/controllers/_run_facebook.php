<?php
//namespace pluginbuddy\frolic;

$link = pb_frolic::plugin_path() . '/classes/facebook.class.php';
include pb_frolic::plugin_path() . '/classes/facebook.class.php';

function run_facebook( $account_id, $object_id ) {
	$fb = new pb_frolic_facebook();
	$item = pb_frolic::$options['accounts'][$account_id]['objects'][$object_id];
	$instance = $account_id . $object_id;
	$return = '';
	//$return = $fb->javascript_sdk();
	
	switch( $item['type'] ) {
		case 'like':
			$return .= $fb->like_button( $item['url'], $item['send_button'], $item['width'], $item['show_faces'], $instance, $item['data_layout'] );
			return $return;
			break;
		case 'comments':
			$return .= $fb->comments( $item['url'], $item['num_posts'], $item['width'], $instance );
			return $return;
			break;
		case 'activity_feed':
			$return .= $fb->activity_feed( $item['url'], $item['rec'], $item['width'], $item['height'], $item['header'], $item['color'], $instance );
			return $return;
			break;
		case 'like_box':
			$return .= $fb->like_box( $item['url'], $item['width'], $item['show_faces'], $item['stream'], $item['header'], $instance );
			return $return;
			break;
		case 'send':
			$return .= $fb->send( $item['url'], $item['font'], $instance );
			return $return;
			break;
		case 'recommendations':
			$return .= $fb->recommendations( $item['url'], $item['width'], $item['height'], $item['header'], $item['color_scheme'], $item['link_target'], $item['color'], $item['font'], $instance );
			return $return;
			break;
		case 'login_button':
			$return .= $fb->login_button( $item['show_faces'], $item['width'], $item['max_rows'], $instance );
			return $return;
			break;
		case 'subscribe':
			$return .= $fb->subscribe( $item['url'], $item['data_layout'], $item['show_faces'], $item['color_scheme'], $item['font'], $item['width'], $instance );
			return $return;
			break;

	}
}

?>