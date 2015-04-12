<?php
//namespace pluginbuddy\frolic;

if ( isset( $_GET['edit'] ) && pb_frolic::$options['accounts'][$_GET['edit']]['type'] ) { // If editing pass to edit controller.
	self::load_controller( '_' . pb_frolic::$options['accounts'][$_GET['edit']]['type'] . '-edit' );
} 
else {
	pb_frolic::$ui->title( pb_frolic::settings( 'name' ) . ' Accounts' );
	
	
	// ########## BEGIN SUBMISSION PROCESSING ##########
	
	
	// ********** Begin Add Group **********
	$add_account_form = new pb_frolic_settings( 'add_account', false );
	$add_account_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'account_name',
		'title'		=>		'Account name',
		'tip'		=>		'Internal account name for your reference. This may be whatever you like.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Select input:
	$add_account_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'account_type',
		'title'		=>		'Account type',
		'options'	=>		array( 'facebook' => 'Facebook', 'google' => 'Google+', 'linkedin' => 'LinkedIn', 'twitter' => 'Twitter' ),
		'tip'		=>		'Select what type of account to add.',
		'rules'		=>		'required',
	) );
	
	$data['add_account_form'] = $add_account_form;
	
	$form_result = $add_account_form->process();
	if ( isset( $form_result['data'] ) && ( count( $form_result['errors'] ) == 0 ) )  {
		$new_account_options = pb_frolic::settings( 'group_defaults' ); // Gets defaults from pb_frolic::$_settings['group_defaults'] which is set up in init.php.
		$new_account_options['title'] = htmlentities( $form_result['data']['account_name'] ); // Set new title.
		$new_account_options['type'] = htmlentities( $form_result['data']['account_type'] ); // set new type.
		if($new_account_options['type'] == 'twitter'){
			$object_def = pb_frolic::settings('twitter_defaults');
			$new_account_options['objects'][0] = $object_def;
			$new_account_options['objects'][0]['type'] = 'feed';
			$new_account_options['objects'][0]['name'] = 'Feed';
		}
		array_push( pb_frolic::$options['accounts'], $new_account_options ); // Push into  existing groups array.
		
		pb_frolic::save(); // Save.
		
		pb_frolic::alert( 'Account "' . htmlentities( $form_result['data']['account_name'] ) . '" has been added.' );
	}
	// ********** End Add Group **********
	
	
	// Delete group(s).
	if ( pb_frolic::_POST( 'do_bulk_action' ) != '' ) {
		$deleted_groups = '';
		
		foreach ( (array) $_POST['items'] as $id ) {
			if ( isset( pb_frolic::$options['accounts'][$id] ) ) { // Does this exist?
				$deleted_groups .= ' "' . pb_frolic::$options['accounts'][$id]['title'] . '",'; // Note it.
				unset( pb_frolic::$options['accounts'][$id] ); // Delete it!
			}
		}
		
		pb_frolic::save();
		pb_frolic::alert( 'Deleted account(s) ' . trim( htmlentities($deleted_groups), ',' ) . '.' ); //TODO htmlentities?
	}
	
	
	// ########## END SUBMISSION PROCESSING ##########
	
	
	
	// Prepare data for groups listing.
	$accounts = array();
	foreach ( (array) pb_frolic::$options['accounts'] as $id => $account ) { //http://davidwalsh.name/javascript-shorthand-if-else-examples   -- in case you don't understand shorthand if statements
		$accounts[$id] = 	array( htmlentities(ucfirst($account['title'])), ucfirst($account['type']), ($account['dash_display'] == 'true' ? 'Yes':'No'), ($account['enabled'] == 'true' ? 'Yes':'No') );
	}
	$data['accounts'] = $accounts;
	

	
	
	
	// Load view.
	pb_frolic::load_view( 'accounts', $data );
}
?>
