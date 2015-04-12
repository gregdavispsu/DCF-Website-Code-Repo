<?php

//namespace pluginbuddy\frolic;

$link = pb_frolic::plugin_path() . '/classes/twitter.class.php';
include pb_frolic::plugin_path() . '/classes/twitter.class.php'; //used for twitter buttons

$link = pb_frolic::plugin_path() . '/classes/twittergitter.class.php';
include pb_frolic::plugin_path() . '/classes/twittergitter.class.php'; //used for twitter feed


function run_twitter( $account_id, $object_id ) {
	
	$twit = new pb_frolic_twitterbuttons();
	$tg = new twittergitter();
	$item = pb_frolic::$options['accounts'][$account_id]['objects'][$object_id];
	$instance = $account_id . $object_id;
	//$twit_return = $twit->twitter_js();
	$twit_return = '';
	
	switch( $item['type'] ) {
		case 'feed':
			return $tg->pb_twittergitter( $item['pb_twittergitter_id'], $item['num_posts'], $item['title'], $item['enable_name'], $item['enable_title'], true );
			break;
		case 'tweet':
			$twit_return .= $twit->flavored_button( $item['size'], $item['related'] . ':' . $item['related_desc'], $instance );
			return $twit_return;
			break;
		case 'follow':
			$twit_return .= $twit->follow_button( $item['user'], $instance );
			return $twit_return;
			break;
		case 'hashtag':
			$twit_return .= $twit->hashtag_button( $item['hashtag'], $item['default_text'], $item['related'] . ':' . $item['related_desc'], $instance );
			return $twit_return;
			break;
		case 'mention':
			$twit_return .= $twit->to_button( $item['user'], $instance );
			return $twit_return;
			break;

	}
	

	
}

?>
