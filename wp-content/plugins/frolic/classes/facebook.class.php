<?php
//namespace pluginbuddy\frolic;
/*************************************************************************************************************************************************************************
																				facebook.class.php
																	http://developers.facebook.com/docs/plugins/
																	
																Class allows easy use of facebook API

Functions:
	facebook()																										--default constructor
	like_button($url)																								--spits out iframe code for a like button to specified url
	comments()																										--uses javascript_sdk() to create a comments area
	subscribe( $url, $layout_style, $show_faces, $color_scheme, $font, $width ) 									--outputs subscribe button
	javascript_sdk()																								--outputs the javascript sdk code necessary for other functions to work, needs to be loaded before other things will work.
	activity_feed($rec)																								--outputs activity feed, $rec determines if we want recommendations shown.
	like_box($data_href, $data_width, $data_show_faces, $data_stream, $data_header)									--takes (url, int, bool, bool, bool) and outputs a box with options included
	send($link, $font)																								--takes a link and font, and outputs a send box
	recommendations($domain, $width, $height, $header, $color_scheme, $link_target, $border_color, $font)			--takes (url, int, int, bool, string, string, string, string) returns recommendations box
	login_button($show_faces, $width, $max_rows)																	--takes (boolean, int, int) returns a login button, that shows faces after login
	registration($client_id, $redirect_uri, $fields, $fb_only, $fb_register, $width, $border_color, $target)		--takes (string, string, string, boolean, boolean, int, string, string) returns registration form
	face_pile($url, $size, $width, $rows, $color_scheme)															--takes (string, int, int, int, string) returns a pile of faces from Facebook.
	live_stream($app_id, $width, $height, $post_friends)															--takes (string, int, int, boolean) inserts a facebook live stream to page
	
****************************************************************************************************************************************************************************/
if ( ! class_exists( 'pb_frolic_facebook' ) ) 
{
	class pb_frolic_facebook
	{

	//change over all functions to use JS / HTML5 implementation





	/***********************Properties********************************/
		public $var = '';
		public $var1 = '';
	/***********************End Properties*****************************/







	/***********************Functions**********************************/
		
		/* facebook()
		 * 
		 * constructor for our new facebook object
		 * 
		 * @return null
		 */
		public function pb_frolic_facebook()
		{
			$var = 'object created';
		}
		
		
		/* like_button()
		 * 
		 * adds a like button to a given page
		 * 
		 * $url			string		url that the like button is to be linked to.
		 * $send		boolean		do you want to have a send button?
		 * $width		int			how wide do you want the object?
		 * $show_faces	boolean		do you want to show a row of faces with the object?
		 * 
		 * @return null
		 */
		public function like_button($url, $send, $width, $show_faces, $instance, $data_layout ) //g2g
		{
			return '<div class="pb-frolic pb-frolic-facebook pb-frolic-like-button pb-frolic-object-' . $instance . '"><div class="fb-like" data-layout="' . $data_layout . '" data-href="'. $url . '" data-send="' . $send . '" data-width="' . $width . '" data-show-faces="' . $show_faces . '"></div></div>';
		}
		
		/* comments()
		 * 
		 * sets up a comments panel in a given space
		 * 
		 * $url			string		url that the comments link to.
		 * $num_posts	int			number of posts.
		 * $width		int			width of object.  	 
		 * 
		 * @return null
		 */
		public function comments($url, $num_posts, $width, $instance )
		{
			return '<div class="pb-frolic pb-frolic-facebook pb-frolic-comments pb-frolic-object-' . $instance . '"><div class="fb-comments" data-href="' . $url . '" data-num-posts="' . $num_posts . '" data-width="' . $width . '"></div></div>';
		}

		/* javascript_sdk()
		 * 
		 * sets up the javascript sdk for use with non-iframe plugins
		 * 
		 * @return null
		 */
		public function javascript_sdk()
		{
			return '<div id="fb-root"></div>
				<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=213571795384800";
				fjs.parentNode.insertBefore(js, fjs);
				}(document, "script", "facebook-jssdk"));</script>';
		}
		/* activity_feed()
		 * 
		 * adds a box with an activity feed, and optional recommendations
		 * 
		 * $url				string		link
		 * $rec				boolean		sets recommendations on or off
		 * $width			int			sets the object width
		 * $height			int			sets the object height
		 * $header			boolean		set data header
		 * $color			string		pick a border color
		 * 
		 * @return null
		 */
		public function activity_feed($url, $rec, $width, $height, $header, $color, $instance )
		{	
				return '<div class="pb-frolic pb-frolic-facebook pb-frolic-activity-feed pb-frolic-object-' . $instance . '"><div class="fb-activity" data-site="' . $url . '" data-width="' . $width . '" data-height="' . $height . '" data-header="' . $header . '" data-border-color="' . $color . '" data-recommendations="' . $rec . '"></div></div>';	
		}

		/* like_box()
		 * 
		 * adds a like box to the page
		 * 
		 * $data_href		string		url for the like box to link to
		 * $data_width		int			width for the like box
		 * $data_showfaces	bool		Do you want to show faces in the like box?
		 * $data_stream		bool		Do you want to show a data stream?
		 * $data_header		bool		Display header for the like box?
		 * @return null
		 */
		public function like_box($data_href, $data_width, $data_showfaces, $data_stream, $data_header, $instance )
		{
			return '<div class="pb-frolic pb-frolic-facebook pb-frolic-like-box pb-frolic-object-' . $instance . '"><div class="fb-like-box" data-href="'. $data_href . '" data-width="' . $data_width . '" data-show-faces="' . $data_showfaces . '" data-stream="' . $data_stream . '" data-header="' . $data_header . '"></div></div>';
		}
		
		/* send()
		 * 
		 * adds a send button to page
		 * 
		 * $link			string		the url to send
		 * $font			string		font for 'send' button
		 * @return null
		 */
		public function send($link, $font, $instance )
		{
			return '<div class="pb-frolic pb-frolic-facebook pb-frolic-send-button pb-frolic-object-' . $instance . '"><div class="fb-send" data-href="' . $link . '" data-font="' . $font . '"></div></div>';
		}
		
		/* recommendations()
		 * 
		 * displays a recommendations iframe
		 * 
		 * $domain 			string 		in the form of a URL, the target for recommendations
		 * $width 			int 		container width
		 * $height 			int 		container height
		 * $header 			boolean 	turn header on or off
		 * $color_scheme 	string 		accepts 'light' or 'dark'
		 * $link_target 	string 		accepts '_blank', '_top', or '_parent', tells plugin where to open link
		 * $color		 	string 		use 'red', 'blue', 'green', etc.
		 * $font 			string 		accepts 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
		 * @return null
		 */
		public function recommendations($domain, $width, $height, $header, $color_scheme, $link_target, $border_color, $font, $instance )
		{
			return '<div class="pb-frolic pb-frolic-facebook pb-frolic-recommendations pb-frolic-object-' . $instance . '"><div class="fb-recommendations" data-site="' . $domain . '" data-width="' . $width . '" data-height="' . $height . '" data-header="' . $header . '" data-colorscheme="' . $color_scheme . '" data-linktarget="' . $link_target . '" data-border-color="' . $border_color . '" data-font="' . $font . '"></div></div>';
		}
		
		/* login_button()
		 * 
		 * creates a button to allow visitors to log into facebook from the site
		 * 
		 * $show_faces		boolean		do you want to show your friends' faces?
		 * $width			int			object width
		 * $max_rows		int			how many rows of faces do you want to display?
		 * @return null
		 */
		public function login_button($show_faces, $width, $max_rows, $instance )
		{
			return '<div class="pb-frolic pb-frolic-facebook pb-frolic-login-button pb-frolic-object-' . $instance . '"><div class="fb-login-button" data-show-faces="' . $show_faces . '" data-width="' . $width . '" data-max-rows="' . $max_rows . '"></div></div>';
		}
		/* subscribe()
		 * 
		 * creates a button that allows users to subscribe to another specific user
		 * 
		 * $url				string		profile url of the user to subscribe to
		 * $layout_style	string		determines size and layout of the button -- standard, button_count, box_count
		 * $show_faces		boolean		show faces of subscribers?
		 * $color_scheme	string		light or dark
		 * $font			string		font type to use?
		 * $width			int			width of the plugin
		 * 
		 * $return null
		 * */
		public function subscribe( $url, $layout_style, $show_faces, $color_scheme, $font, $width, $instance ) 
		{
			return '<div class="pb-frolic pb-frolic-facebook pb-frolic-subscribe pb-frolic-object-' . $instance . '"><div class="fb-subscribe" data-href="' . $url . '" data-layout="' . $layout_style . '" data-show-faces="' . $show_faces . '" data-colorscheme="' . $color_scheme . '" data-font="' . $font . '" data-width="' . $width . '"></div></div>';
			//return '<div class="fb-subscribe" data-href="" data-layout="button_count" data-show-faces="true" data-font="tahoma" data-width="450"></div>';
		}

		/* registration()
		 * 
		 * creates a registration panel for those without facebook
		 * 
		 * $client_id		string		your App ID
		 * $redirect_uri	string		the URI that will process the signed_request. It must be prefixed by your site URL
		 * $fields			string		comma separated list of Named Fields or JSON of custom fields
		 * $fb_only			boolean		optional, Only allow users to register by linking their facebook profiles, default false
		 * $fb_register		boolean		optional, Allow users to register for facebook during the registration process
		 * $width			int			optional, int, the width of the iframe in pixels
		 * $border_color	string		optional, the border color of the plugin
		 * $target			string		optional, the target of the form submission: _top(default), _parent, or _self
		 * @return null
		 */
		 // http://developers.facebook.com/docs/plugins/registration/   --for a list of named fields usable by the $fields var
		 // I'm not really sure if this is something we want, I put the basics here, and they work, so, we can built upon it in the future just in case. --Dan
		public function registration($client_id, $redirect_uri, $fields, $fb_only, $fb_register, $width, $border_color, $target )
		{
			return '<iframe src="https://www.facebook.com/plugins/registration.php?
						client_id=' . $client_id . '
						redirect_uri=' . $redirect_uri . '
						fields=' . $fields . '"
					scrolling="auto"
					frameborder="no"
					style="border:none"
					allowTransparency="true"
					width="100%"
					height="330">
			</iframe>';
		}
		
		//Not rendering for some reason, not even on the developers.facebook.com test site. Gonna leave it here, but comment it out for now.
		/* face_pile()
		 * 
		 * creates a... pile of face?
		 * 
		 * $url				string		which page do you want linked to the facepile
		 * $app_id			string		not currently in use :: your api key for the site. 				
		 * $size			string		size of iframe container, small or large.
		 * $width			int			width of the iframe container
		 * $rows			int			number of rows for the facepile
		 * $color_scheme	string		light or dark
		 * @return null
		 */
		 //should work just fine, wasn't able to test it, since I don't have a website with a ton of 'likes' on it etc etc. 
		/*public function face_pile($url, $size, $width, $rows, $color_scheme)
		{
			echo '<iframe src="//www.facebook.com/plugins/facepile.php?href=http%3A%2F%2F' . $url . '&amp;size=' . $size . '&amp;width=' . $width . '&amp;max_rows=' . $rows . '&amp;colorscheme='.$color_scheme.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px;" allowTransparency="true"></iframe>';
		}*/
		
		/* live_stream()
		 * 
		 * The Live Stream plugin lets users visiting your site or application share activity and comments in real time. 
		 * Live Stream works best when you are running a real-time event, like live streaming video for concerts, speeches, 
		 * or webcasts, live Web chats, webinars, massively multiplayer games.
		 * 
		 * $api_id			string		api key required
		 * $width			int			width of iframe container, minimum width is 400px
		 * $height			int			height of iframe container
		 * $xid				int			required if you're going to have more than one live stream on a page, each needs a unique xid
		 * $via_url			string		Via attribution url, Url users are directed to when they click the app name
		 * $always_friends	string		Always post to friends, if set all user posts will always go to their profile.
		 * @return null
		 */
		 //another one I'm not sure we want to get involved with, but here it is in case we want it. 
		public function live_stream($app_id, $width, $height, $xid)
		{
			return '<div class="fb-live-stream" data-event-app-id="' . $app_id . '" data-width="' . $width . '" data-height="' . $height . '" data-xid="' . $xid . '" data-always-post-to-friends="false"></div>';
		}
		
	/*************************End Functions*******************************/

	/*************************Aux****************************************/
	/***********************End Aux**************************************/

	}
}
?>
