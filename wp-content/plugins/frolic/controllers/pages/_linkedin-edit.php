<?php
//namespace pluginbuddy\frolic;

if ( isset( $_GET['edit'] ) && isset( $_GET['editobject'] ) ) { // If editing pass to edit controller.
	self::load_controller( '_linkedin_object-edit' );
} 
else {
	pb_frolic::$ui->title( 'LinkedIn Settings' );
	
	/**********  Options Submission Form **********/
	 // Text input:
	$linkedin_account_settings_form = new pb_frolic_settings('linkedin_account_settings', 'accounts#' . $_GET['edit'], 'edit=' . $_GET['edit'] );
	$linkedin_account_settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'title',
		'title'		=>		'Name',
		'tip'		=>		'Internal account name for your reference. This may be whatever you like.',
		'rules'		=>		'required|string[1-255]',
	) );

	// Checkbox input:
	$linkedin_account_settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'enabled',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Enable account',
		'tip'		=>		'Turn this account on and off without deleting it.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['linkedin_account_settings_form'] = $linkedin_account_settings_form;
	
	$linkedin_account_settings_form->process(); // need to finish setting up all these options into the new array for facebook objects
	
	/********** End Options Submission Form **********/
		
	// ########## BEGIN SUBMISSION PROCESSING ##########
	
	
	// ********** Begin Add Group **********
	$add_linkedin_object_form = new pb_frolic_settings('add_linkedin_object', false, 'edit=' . $_GET['edit'] );
	$add_linkedin_object_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'object_name',
		'title'		=>		'Object name',
		'tip'		=>		'Name of the Facebook object.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Select input:
	$add_linkedin_object_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'object_type',
		'title'		=>		'Object type',
		'options'	=>		array( 'share' => 'Share', 'profile' => 'Profile', 'insider' => 'Insider', 'c_profile' => 'Company Profile', 'recommend' => 'Recommend', 'jobs' => 'Show Jobs', 'follow' => 'Follow' ),
		'tip'		=>		'<ul>
								<li><b>Share</b>: Creates a LinkedIn share button.</li>
								<li><b>Profile</b>: Displays a user profile.</li>
								<li><b>Insider</b>: Displays a Company Insider window.</li>
								<li><b>Company Profile</b>: Displays the Company Profile as found on LinkedIn.</li>
								<li><b>Recommend</b>: Creates a Recommend button for use with a product ID.</li>
								<li><b>Show Jobs</b>: Shows job matches based on either company or personal profile.</li>
								<li><b>Follow</b>: Creates a LinkedIn Follow button.</li>
							 </ul>',
		'rules'		=>		'required',
	) );
	
	$data['add_linkedin_object_form'] = $add_linkedin_object_form;
	
	$form_result = $add_linkedin_object_form->process(); // need to finish setting up all these options into the new array for facebook objects
	if ( isset( $form_result['data'] ) && ( count( $form_result['errors'] ) == 0 ) )  {
		$new_object_options = pb_frolic::settings( 'linkedin_defaults' ); // Gets defaults from pb_frolic::$_settings['group_defaults'] which is set up in init.php.
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
	//( 'share' => 'Share', 'profile' => 'Profile', 'insider' => 'Insider', 'c_profile' => 'Company Profile', 'recommend' => 'Recommend', 'jobs' => 'Show Jobs', 'follow' => 'Follow' )
	function type($string){
		switch( $string ) {
		case 'share':
			return 'Share';
			break;
		case 'profile':
			return 'Profile';
			break;
		case 'insider':
			return 'Insider';
			break;
		case 'c_profile':
			return 'Company Profile';
			break;
		case 'recommend':
			return 'Recommend';
			break;
		case 'jobs':
			return 'Show Jobs';
			break;
		case 'follow':
			return 'Follow';
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
	pb_frolic::load_view( '_linkedin-edit', $data );
}
?>
