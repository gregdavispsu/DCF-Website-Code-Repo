<?php
//namespace pluginbuddy\frolic;

if ( isset( $_GET['edit'] ) && isset( $_GET['editobject'] ) ) { // If editing pass to edit controller.
	self::load_controller( '_facebook_object-edit' );
} 
else {
	pb_frolic::$ui->title( 'Facebook Settings' );
	
	/**********  Options Submission Form **********/
	 // Text input:
	$facebook_account_settings_form = new pb_frolic_settings('facebook_account_settings', 'accounts#' . $_GET['edit'], 'edit=' . $_GET['edit'] );
	$facebook_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'title',
		'title'		=>		'Name',
		'tip'		=>		'Internal account name for your reference. This may be whatever you like.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Text input:
	/*$facebook_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'user_id',
		'title'		=>		'User ID',
		'tip'		=>		'User ID for this account',
		'rules'		=>		'required|string[1-255]',
	) );*/
	// Select input:
	/*$facebook_account_settings_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'type',
		'title'		=>		'Account Type',
		'options'	=>		array( 'facebook' => 'Facebook', 'googleplus' => 'Google+', 'twitter' => 'Twitter' ),
		'tip'		=>		'Select Account Type',
		'rules'		=>		'required',
	) );*/
	// Checkbox input:
	$facebook_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'dash_display',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Allow dashboard display',
		'tip'		=>		'Would you like to allow the display of any comments or activity feeds in this account?',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
		// Checkbox input:
	/*$facebook_account_settings_form->add_setting( array(
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
	/*$facebook_account_settings_form->add_setting( array(
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
	$facebook_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'enabled',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Enable account',
		'tip'		=>		'Turn this account on and off without deleting it.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['facebook_account_settings_form'] = $facebook_account_settings_form;
	
	$facebook_account_settings_form->process(); // need to finish setting up all these options into the new array for facebook objects
	
	/********** End Options Submission Form **********/
		
	// ########## BEGIN SUBMISSION PROCESSING ##########
	
	
	// ********** Begin Add Group **********
	$add_facebook_object_form = new pb_frolic_settings('add_facebook_object', false, 'edit=' . $_GET['edit'] );
	$add_facebook_object_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'object_name',
		'title'		=>		'Object name',
		'tip'		=>		'Name of the Facebook object.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Select input:
	$add_facebook_object_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'object_type',
		'title'		=>		'Object type',
		'options'	=>		array( 'like' => 'Like Button', 'comments' => 'Comments Block', 'activity_feed' => 'Activity Feed', 'like_box' => 'Like Box', 'send' => 'Send Button', 'recommendations' => 'Recommendations', 'subscribe' => 'Subscribe Button' ),
		'tip'		=>		'<ul>
								<li><b>Like</b>: This button will allow users to like your site or post through facebook.</li>
								<li><b>Comments</b>: This will allow users to post a comment about your site through facebook.</li>
								<li><b>Activity Feed</b>: This shows a feed of recent activity that facebook has reported from your site.</li>
								<li><b>Like Box</b>: This box allows users to not only like the site, but also view an activity feed. </li>
								<li><b>Send</b>: This button allows a user to share a link from your site through facebook.</li>
								<li><b>Recommendations</b>: Shows personalized recommendations for each user, hosted from facebook.</li>
							 </ul>
		',
		'rules'		=>		'required',
	) );
	
	$data['add_facebook_object_form'] = $add_facebook_object_form;
	
	$form_result = $add_facebook_object_form->process(); // need to finish setting up all these options into the new array for facebook objects
	if ( isset( $form_result['data'] ) && ( count( $form_result['errors'] ) == 0 ) )  {
		$new_object_options = pb_frolic::settings( 'facebook_defaults' ); // Gets defaults from pb_frolic::$_settings['group_defaults'] which is set up in init.php.
		$new_object_options['name'] = htmlentities( $form_result['data']['object_name'] );
		$new_object_options['type'] = htmlentities( $form_result['data']['object_type'] ); // Set new title.
		array_push( pb_frolic::$options['accounts'][$_GET['edit']]['objects'], $new_object_options ); // Push into  existing groups array.
		pb_frolic::save(); // Save.
		
		pb_frolic::alert( 'Object "' . htmlentities( $form_result['data']['object_name'] ) . '" has been added. Hover over the object in the listing below and click `Edit Object Settings` to configure.' );
	}
	// ********** End Add Group **********
	
	
	// Delete group(s).
	
	if ( pb_frolic::_POST( 'do_bulk_action' ) != '' ) {
		$deleted_groups = '';
		foreach ( (array) $_POST['items'] as $id ) {
			if ( isset( pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$id] ) ) { // Does this exist?
				$deleted_groups .= ' "' . pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$id]['name'] . '",'; // Note it.
				unset( pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$id] ); // Delete it!
			}
		}
		
		pb_frolic::save();
		pb_frolic::alert( 'Deleted Object(s) ' . trim( htmlentities($deleted_groups), ',' ) . '.' );
	}
	
	
	// ########## END SUBMISSION PROCESSING ##########
	function type($string){
		switch( $string ) {
		case 'like':
			return 'Like';
			break;
		case 'comments':
			return 'Comments';
			break;
		case 'activity_feed':
			return 'Activitiy Feed';
			break;
		case 'like_box':
			return 'Like Box';
			break;
		case 'send':
			return 'Send';
			break;
		case 'recommendations':
			return 'Recommendations';
			break;
		case 'login_button':
			return 'Login Button';
			break;
		case 'subscribe':
			return 'Subscribe Button';
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
	pb_frolic::load_view( '_facebook-edit', $data );
}
?>
