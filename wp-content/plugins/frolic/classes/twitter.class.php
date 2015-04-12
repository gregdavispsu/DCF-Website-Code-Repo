<?php
//namespace pluginbuddy\frolic;
/*************************************************************************************************************************************************************************
																				twitter.class.php
																				http://dev.twitter.com
																	
																		container class for twitter functionality

Functions:
	twitterbuttons()														--class constructor
	plain_button()															--plain tweet button
	flavored_button()														--tweet button with options
	hashtag_button()														--hashtag category button
	to_button()																--@name button
	follow_button()															--standard follow button
****************************************************************************************************************************************************************************/
if ( ! class_exists( 'pb_frolic_twitterbuttons' ) ) 
{
	class pb_frolic_twitterbuttons
	{

	/***********************Properties********************************/
		public $var = '';
		public $var1 = '';
	/***********************End Properties*****************************/




	/***********************Functions**********************************/

	/* twitterbuttons()
	 *
	 * class constructor
	 *
	 * @return null
	 */
	public function pb_frolic_twitterbuttons(){
		$var = 'hello';
	}

	public function twitter_js(){
		return '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
			  if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";
			  fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
	}
	/* plain_button()
	 *
	 * creates just a plain tweet button with no options, bells, or whistles.
	 *
	 * @return null
	 */
	public function plain_button(){
		return '<div class="frolic-twitter-plain-button"><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a></div>';
	}

	/* flavored_button()
	 *
	 * creates a button with extra options
	 *
	 * $size	int		--decides the size for the button, small, medium, or large
	 * $related	string	--decide who you want put on the related page after a tweet
	 *
	 * @return null
	 */
	public function flavored_button($size, $related, $instance){
	   return '<div class="pb-frolic pb-frolic-twitter pb-frolic-tweet-button pb-frolic-object-' . $instance . '"><a href="https://twitter.com/share" class="twitter-share-button" data-related="' . $related  . '" data-lang="en" data-size="' . $size . '" data-count="none">Tweet</a></div>';
	}

	/* hashtag_button()
	 *
	 * creates a hashtag button
	 *
	 * $hashtag		string		--inserts the category for the button to link to
	 * $text		string		--default text for tweet
	 * $related		string		--related persons you want displayed
	 *
	 * @return null
	 */
	public function hashtag_button($hashtag, $text, $related, $instance){
		return '<div class="pb-frolic pb-frolic-twitter pb-frolic-hashtag pb-frolic-object-' . $instance . '"><a href="https://twitter.com/intent/tweet?button_hashtag=#' . $hashtag . '&text=' . $text . '" class="twitter-hashtag-button" data-lang="en" data-related="'. $related . '">Tweet #' . $hashtag . '</a></div>';
	}

	/* to_button()
	 *
	 * creates an @screen_name button
	 *
	 * $screen_name		string		--screen name you want to tweet at
	 *
	 * @return null
	 */
	public function to_button($screen_name, $instance){
		return '<div class="pb-frolic pb-frolic-twitter pb-frolic-mention pb-frolic-object-' . $instance . '"><a href="https://twitter.com/intent/tweet?screen_name=@' . $screen_name . '" class="twitter-mention-button" data-lang="en">Tweet to @' . $screen_name . '</a></div>';
	}

	/* follow_button
	 *
	 * creates a follow button
	 *
	 * $screen_name		string		--screen name to follow
	 *
	 * @return null
	 */
	public function follow_button($screen_name, $instance){
		return '<div class="pb-frolic pb-frolic-twitter pb-frolic-follow pb-frolic-object-' . $instance . '"><a href="https://twitter.com/' . $screen_name . '" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @' . $screen_name . '</a></div>';
	}


	/*************************End Functions*******************************/



	/*************************Aux****************************************/
	/***********************End Aux**************************************/

	}
}
?>
