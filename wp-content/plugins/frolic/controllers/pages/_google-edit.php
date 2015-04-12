<?php
//namespace pluginbuddy\frolic;

if ( isset( $_GET['edit'] ) && isset( $_GET['editobject'] ) ) { // If editing pass to edit controller.
	self::load_controller( '_google_object-edit' );
} 
else {
	pb_frolic::$ui->title( 'Google Settings' );
	
	/**********  Options Submission Form **********/
	 // Text input:
	$google_account_settings_form = new pb_frolic_settings('google_account_settings', 'accounts#' . $_GET['edit'], 'edit=' . $_GET['edit'] );
	$google_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'title',
		'title'		=>		'Name',
		'tip'		=>		'Internal account name for your reference. This may be whatever you like.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Text input:
	/*$google_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'user_id',
		'title'		=>		'User ID',
		'tip'		=>		'User ID for this account',
		'rules'		=>		'required|string[1-255]',
	) );*/
	// Select input:
	/*$google_account_settings_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'type',
		'title'		=>		'Account Type',
		'options'	=>		array( 'facebook' => 'Facebook', 'googleplus' => 'Google+', 'google' => 'google' ),
		'tip'		=>		'Select Account Type',
		'rules'		=>		'required',
	) );*/
	// Checkbox input:
	/*$google_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'dash_display',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Allow Dashboard Display',
		'tip'		=>		'When enabled, hovering the mouse over the Carousel will cause it to pause and not continue rotating.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );*/
		// Checkbox input:
	/*$google_account_settings_form->add_setting( array(
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
	/*$google_account_settings_form->add_setting( array(
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
	$google_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'enabled',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Enable account',
		'tip'		=>		'Turn this account on and off without deleting it.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['google_account_settings_form'] = $google_account_settings_form;
	
	$google_account_settings_form->process(); // need to finish setting up all these options into the new array for google objects
	
	/********** End Options Submission Form **********/
		
	// ########## BEGIN SUBMISSION PROCESSING ##########
	
	
	// ********** Begin Add Group **********
	$add_google_object_form = new pb_frolic_settings('add_google_object', false, 'edit=' . $_GET['edit'] );
	$add_google_object_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'object_name',
		'title'		=>		'Object name',
		'tip'		=>		'Name of google object.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Select input:
	$add_google_object_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'object_type',
		'title'		=>		'Object type',
		'options'	=>		array( 'plusone_button' => '+1 Button', 'badge' => 'Google+ Badge', 'share_button' => 'Share Button'),
		'tip'		=>		'<ul>
								<li><b>+1 Button</b>: Allows users to give content on your site a +1 on Google Plus.</li>
								<li><b>Badge</b>: Creates a Google Plus badge on your site. This creates a +1 button, a link to your google profile, and also shows other people who have given your site a +1.</li>
								<li><b>Share</b>: Allows users to share content found on your site using Google Plus.</li>
							</ul>',
		'rules'		=>		'required',
	) );
	
	$data['add_google_object_form'] = $add_google_object_form;
	
	$form_result = $add_google_object_form->process(); // need to finish setting up all these options into the new array for google objects
	if ( isset( $form_result['data'] ) && ( count( $form_result['errors'] ) == 0 ) )  {
		$new_object_options = pb_frolic::settings( 'google_defaults' ); // Gets defaults from pb_frolic::$_settings['group_defaults'] which is set up in init.php.
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
		case 'plusone_button':
			return '+1 Button';
			break;
		case 'badge':
			return 'Badge';
			break;

		}
	}
	
	
	// Prepare data for groups listing.
	$objects = array();
	foreach ( (array) pb_frolic::$options['accounts'][$_GET['edit']]['objects'] as $id => $object ) {
		$objects[$id] = array( htmlentities($object['name']), $object['type'], '[pb_frolic account="' . $_GET['edit'] . '" object="' . $id . '"]' );
	}
	$data['objects'] = $objects;
	
	
	
	// Prepare data for plugin settings form.
	
	// Load view.
	pb_frolic::load_view( '_google-edit', $data );
}
?>
