<?php
//namespace pluginbuddy\frolic;
/*************************************************************************************************************************************************************************
																				twittergitter.class.php
																				http://dev.twitter.com
																	
																		container class for twitter functionality

Functions:
	twittergitter()															--class constructor
	linkify_tweet(tweet)													--adds links to tweets using regex for twitter users & hashtags
	httpify_tweet(tweet)													--adds links
****************************************************************************************************************************************************************************/
 
class twittergitter {

/***********************Properties********************************/
	public $var = '';
	public $var1 = '';
/***********************End Properties*****************************/




/***********************Functions**********************************/

	public function twittergitter() {
		$var = 'hello'; 
	}

	public function linkify_tweet( $tweet ) {  //http://granades.com/2009/04/06/using-regular-expressions-to-match-twitter-users-and-hashtags/  --Thank you!
		$tweet = preg_replace('/(^|\s)@(\w+)/', '\1@<a href="http://www.twitter.com/\2">\2</a>', $tweet);
		return preg_replace('/(^|\s)#(\w+)/', '\1#<a href="http://search.twitter.com/search?q=%23\2">\2</a>', $tweet);
	}
		
	public function httpify_tweet( $tweet ) { //http://bavotasan.com/2009/turn-plain-text-urls-into-active-links-using-php/  --Thank you!
		return preg_replace('/https?:\/\/[\w\-\.!~?&+\*\'"(),\/]+/','<a href="$0">$0</a>',$tweet);
	}
	public function span_title( $user_id, $tweet, $enable_name ) {
		if($enable_name == true){
			return str_ireplace( $user_id . ':',    '<a class="frolic-' . $user_id . '-username" href="http://www.twitter.com/'. $user_id .'"> '. $user_id . ': </a>'      ,$tweet );
		}else{
			return str_ireplace( $user_id . ':', '', $tweet );
		}
	}
	
	public function pb_twittergitter( $twitter_handle, $tweet_count, $title, $enable_name, $enable_title, $return_wp_error = false ) { //take group id, and return data
		$transient_name = "pb_twittergitter-{$twitter_handle}-{$tweet_count}";
		$transient_timeout = 300;
		
		// Uncomment to do a quick refresh for testing.
		//delete_transient( $transient_name );
		
		
		if ( empty( $twitter_handle ) ) {
			$message = __( 'An empty Twitter Handle was passed to the pb_twittergitter function.', 'it-l10n-frolic' );
			if ( $return_wp_error )
				return new WP_Error( 'pb_frolic_empty_twitter_handle', $message );
			
			return $message;
		}
		
		
		$tweets = get_transient( $transient_name );
		
		if ( false !== $tweets )
			return $tweets;
		
		
		$auth_options = array(
			'it_twit_consumer_key'    => 'consumer_key',
			'it_twit_consumer_secret' => 'consumer_secret',
			'it_twit_access_token'    => 'user_token',
			'it_twit_access_secret'   => 'user_secret',
		);
		
		$credentials = array();
		$is_missing_credentials = false;
		
		foreach ( $auth_options as $index => $credential ) {
			if ( empty( pb_frolic::$options[$index] ) ) {
				$is_missing_credentials = true;
				break;
			}
			
			$credentials[$credential] = pb_frolic::$options[$index];
		}
		
		
		if ( true === $is_missing_credentials ) {
			$message = __( 'The Twitter API configuration is incomplete. Please use the Frolic > Settings page to configure your Twitter API credentials.', 'it-l10n-frolic' );
			
			if ( $return_wp_error )
				return new WP_Error( 'pb_frolic_missing_twitter_credentials', $message );
			
			return $message;
		}
		
		
		require_once( dirname( dirname( __FILE__ ) ) . '/lib/tmh-oauth/tmh-oauth.php' );
		
		$auth = new IT_THM_OAuth( $credentials );
		
		$url = $auth->url( '1.1/statuses/user_timeline', 'json' );
		
		$request_args = array(
//			'include_rts' => '1',
			'count'       => $tweet_count,
			'screen_name' => $twitter_handle,
		);
		
		$response_code = $auth->request( 'GET', $url, $request_args );
		
		
		if ( 200 != $response_code ) {
			$message = __( 'The request to load tweets from Twitter failed. Please verify your Twitter API credentials in Frolic > Settings.', 'it-l10n-frolic' );
			
			if ( $return_wp_error )
				return new WP_Error( 'pb_frolic_failed_twitter_request', $message );
			
			return $message;
		}
		
		
		$items = $auth->response['response'];
		
		if ( empty( $items ) ) {
			$message = __( 'The request to load tweets from Twitter failed. The returned data was empty.', 'it-l10n-frolic' );
			
			if ( $return_wp_error )
				return new WP_Error( 'pb_frolic_failed_twitter_request', $message );
			
			return $message;
		}
		
		
		$items = json_decode( $items, true );
		
		if ( empty( $items ) ) {
			$message = __( 'The request to load tweets from Twitter failed. The returned data was not in JSON format.', 'it-l10n-frolic' );
			
			if ( $return_wp_error )
				return new WP_Error( 'pb_frolic_failed_twitter_request', $message );
			
			return $message;
		}
		
		if ( ! is_array( $items ) ) {
			$message = __( 'The request to load tweets from Twitter failed. The returned data is not an array.', 'it-l10n-frolic' );
			
			if ( $return_wp_error )
				return new WP_Error( 'pb_frolic_failed_twitter_request', $message );
			
			return $message;
		}
		
		
		$tweets .= "<div class='pb-frolic'>\n";
		
		
		if ( ! empty( $title ) && ( true == $enable_title ) )
			$tweets .= "<a class='frolic-twitter-feed-title' href='http://www.twitter.com/{$twitter_handle}'>{$title}</a> "; //TODO move this to widget menu
		
		
		$tweets .= "<ul class='frolic-twitteruser-{$twitter_handle}'>\n";
		
		$number = 1;
		
		foreach( $items as $item ) {
			$tweets .= "<li class='frolic-li-item-{$number}'>\n";
			$tweets .= $this->span_title( $twitter_handle, $this->linkify_tweet( $this->httpify_tweet( $item['text'] ) ), $enable_name ) . "\n";
			$tweets .= "</li>\n";
			
			$number++;
		}
		
		$tweets .= "</ul>\n";
		$tweets .= "</div>\n";
		
		
		set_transient( $transient_name, $tweets, $transient_timeout );
		
		
		return $tweets;
	}







/*************************End Functions*******************************/



/*************************Aux****************************************/
/***********************End Aux**************************************/

}
?>
