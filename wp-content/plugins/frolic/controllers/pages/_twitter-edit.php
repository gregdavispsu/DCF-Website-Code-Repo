<?php
//namespace pluginbuddy\frolic;
?>
<?php
if ( isset( $_GET['edit'] ) && isset( $_GET['editobject'] ) ) { // If editing pass to edit controller.
	self::load_controller( '_twitter_object-edit' );
} 
else {
	pb_frolic::$ui->title( 'Twitter Settings' );
	
	/**********  Options Submission Form **********/
	 // Text input:
	$twitter_account_settings_form = new pb_frolic_settings('twitter_account_settings', 'accounts#' . $_GET['edit'], 'edit=' . $_GET['edit'] );
	$twitter_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'title',
		'title'		=>		'Name',
		'tip'		=>		'Internal account name for your reference. This may be whatever you like.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Text input:
	/*$twitter_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'user_id',
		'title'		=>		'User ID',
		'tip'		=>		'User ID for this account',
		'rules'		=>		'required|string[1-255]',
	) );*/
	/*$twitter_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'num_of_tweets',
		'title'		=>		'Tweets to display',
		'tip'		=>		'How many tweets do you want to display',
		'rules'		=>		'required|string[1-255]',
		'css'		=>		'text-align: right; width: 35px;',
		'after'  	=>  	' tweets.',
	) );*/
	//checkbox input:
	$twitter_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'dash_display',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Display in Dashboard',
		'tip'		=>		'When enabled, will allow feed items to be displayed on dashboard.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
		// Checkbox input:
	/*$twitter_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'dash_posting',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Allow Dashboard Posting',
		'tip'		=>		'When enabled, hovering the mouse over the Carousel will cause it to pause and not continue rotating.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );*/
	// Checkbox input:
	/*$twitter_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'allow_broadcast',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Allow Broadcasting',
		'tip'		=>		'When enabled, hovering the mouse over the Carousel will cause it to pause and not continue rotating.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );*/
	// Checkbox input:
	$twitter_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'enabled',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Enable account',
		'tip'		=>		'Turn this account on and off without deleting it.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['twitter_account_settings_form'] = $twitter_account_settings_form;
	
	$twitter_account_settings_form->process(); // need to finish setting up all these options into the new array for twitter objects
	
	/********** End Options Submission Form **********/
		
	// ########## BEGIN SUBMISSION PROCESSING ##########
	
	
	// ********** Begin Add Group **********
	$add_twitter_object_form = new pb_frolic_settings('add_twitter_object', false, 'edit=' . $_GET['edit'] );
	$add_twitter_object_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'object_name',
		'title'		=>		'Object name',
		'tip'		=>		'Internal object name for your reference. This may be whatever you like.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Select input:
	/*$add_twitter_object_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'object_type',
		'title'		=>		'Object type',
		'options'	=>		array( 'feed' => 'Feed', 'tweet' => 'Tweet', 'follow' => 'Follow', 'hashtag' => 'Hashtag', 'mention' => 'Mention'),
		'tip'		=>		'<ul><li><b>Feed</b>: Pulls recent tweets and displays them with a shortcode.</li>
								 <li><b>Tweet</b>: Allows user to tweet from current page.</li>
								 <li><b>Follow</b>: Adds follow button, target may be specified.</li>
								 <li><b>Hashtag</b>: Allows user to post to a specified Twitter topic.</li>
								 <li><b>Mention</b>: Tweet to a specific @user.</li>
							</ul>',
		'rules'		=>		'required',
	) );*/
	// Radio input:
	$add_twitter_object_form->add_setting( array(
		'type'		=>		'radio',
		'name'		=>		'object_type',
		'title'		=>		'Object type',
		'options'	=>		array( 	'feed' 		=> 	'Feed',
									'tweet' 	=> 	'Tweet  <img src="' . pb_frolic::plugin_url() . '/images/tweet_button.png" style="vertical-align: -7px;">',
									'follow'	=> 	'Follow  <img src="' . pb_frolic::plugin_url() . '/images/follow_button.png" style="vertical-align: -7px;">',
									'hashtag'	=>	'Hashtag  <img src="' . pb_frolic::plugin_url() . '/images/hashtag_button.png" style="vertical-align: -7px;">',
									'mention'	=>	'Mention  <img src="' . pb_frolic::plugin_url() . '/images/mention_button.png" style="vertical-align: -7px;">',
									),
		'tip'		=>		'<ul><li><b>Feed</b>: Pulls recent tweets and displays them with a shortcode.</li>
								 <li><b>Tweet</b>: Allows user to tweet from current page.</li>
								 <li><b>Follow</b>: Adds follow button, target may be specified.</li>
								 <li><b>Hashtag</b>: Allows user to post to a specified Twitter topic.</li>
								 <li><b>Mention</b>: Tweet to a specific @user.</li>
							</ul>',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
		'orientation'	=>		'vertical', // also try vertical.
	) );
	
	$data['add_twitter_object_form'] = $add_twitter_object_form;
	
	$form_result = $add_twitter_object_form->process(); // need to finish setting up all these options into the new array for twitter objects
	if ( isset( $form_result['data'] ) && ( count( $form_result['errors'] ) == 0 ) )  {
		$new_object_options = pb_frolic::settings( 'twitter_defaults' ); // Gets defaults from pb_frolic::$_settings['object_defaults'] which is set up in init.php.
		$new_object_options['name'] = htmlentities( $form_result['data']['object_name'] );
		$new_object_options['type'] = htmlentities( $form_result['data']['object_type'] ); // Set new title.
		array_push( pb_frolic::$options['accounts'][$_GET['edit']]['objects'], $new_object_options ); // Push into  existing groups array.
		
		pb_frolic::save(); // Save.
		
		pb_frolic::alert( 'Object "' . htmlentities( $form_result['data']['object_name'] ) . '" has been added. Hover over the object in the listing below and click `Edit Object Settings` to configure.' );
	}
	/********** End Add Group **********/
	
	
	// Delete group(s).
	
	if ( pb_frolic::_POST( 'do_bulk_action' ) != '' ) {
		$deleted_objects = '';
		foreach ( (array) $_POST['items'] as $id ) {
			if ( isset( pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$id] ) ) { // Does this exist?
				$deleted_objects .= ' "' . pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$id]['name'] . '",'; // Note it.
				unset( pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$id] ); // Delete it!
			}
		}
		
		pb_frolic::save();
		pb_frolic::alert( 'Deleted Object(s) ' . trim( htmlentities($deleted_objects), ',' ) . '.' );
	}
	
	
	// ########## END SUBMISSION PROCESSING ##########
	
	function type($string){
		switch( $string ) {
		case 'feed':
			return 'Feed';
			break;
		case 'tweet':
			return 'Tweet';
			break;
		case 'follow':
			return 'Follow';
			break;
		case 'hashtag':
			return 'Hashtag';
			break;
		case 'mention':
			return 'Mention';
			break;
		}
	}
	
	// Prepare data for groups listing.
	$objects = array();
	foreach ( (array) pb_frolic::$options['accounts'][$_GET['edit']]['objects'] as $id => $object ) {
		$objects[$id] = array( htmlentities($object['name']), type($object['type']), '[pb_frolic account="' . $_GET['edit'] . '" object="' . $id . '"]' );
	}
	$data['objects'] = $objects;
	
	
	
	// Prepare data for plugin settings form.
	
	// Load view.
	pb_frolic::load_view( '_twitter-edit', $data );
}
?>
