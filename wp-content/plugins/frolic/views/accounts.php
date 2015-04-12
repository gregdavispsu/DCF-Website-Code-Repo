<?php
 //namespace pluginbuddy\frolic; 
if ( isset($_POST['pb_frolic_account_type'] ) ) {
 if ( 'twitter' == $_POST['pb_frolic_account_type'] && empty(pb_frolic::$options['it_twit_consumer_key'] )) { 
 pb_frolic::alert( __( 'Please use the Frolic > <a href="' . admin_url( 'admin.php?page=pb_frolic_settings' ) . '" >Settings</a> page to configure your Twitter API credentials.', 'it-l10n-frolic' ));
	}
 }
?>
<?php

echo '<h3>Add New Frolic Account</h3>';
$add_account_form->display_settings( '+ Add Account' );
echo '&nbsp;&nbsp;';
echo '<br></br>';
echo '<br><br>';



// Group listing.

$account_list_settings = array(
					'action'		=>		pb_frolic::page_url(),
					'columns'		=>		array( 'Account Name', 'Type', 'Dashboard Display', 'Enabled' ),
					'hover_actions'	=>		array( 'edit' => 'Edit Account Settings' ),
					'bulk_actions'	=>		array( 'delete_account' => 'Delete' ),
				);
				
if( !empty($accounts) ){
pb_frolic::$ui->list_table( $accounts, $account_list_settings );
echo '<br>';
}




// Settings listing.
//pb_frolic::$ui->start_metabox( 'Plugin Settings' );
//$settings_form->display_settings( 'Save Settings' );
//pb_frolic::$ui->end_metabox();
?>
