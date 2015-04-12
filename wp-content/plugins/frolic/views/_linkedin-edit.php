<?php //namespace pluginbuddy\frolic; ?>

<?php
echo '<h3>LinkedIn Account Settings</h3>';
$linkedin_account_settings_form->display_settings( 'Update Account Settings' );
echo '&nbsp;&nbsp;';
echo '<br></br>';
echo '<br><br>';



// Group listing.

$object_list_settings = array(
					'action'		=>		pb_frolic::page_url() . '&edit=' . pb_frolic::_GET('edit'),
					'columns'		=>		array( 'Object Name', 'Type', 'Shortcode' ),
					'hover_actions'	=>		array( 'edit=' . $_GET['edit'] . '&editobject' => 'Edit Object Settings' ),
					'bulk_actions'	=>		array( 'delete_object' => 'Delete' ),
				);

if( !empty($objects) ){
pb_frolic::$ui->list_table( $objects, $object_list_settings );
echo '<br>';
}
pb_frolic::$ui->start_metabox( 'Add a LinkedIn Object' );
$add_linkedin_object_form->display_settings( 'Add LinkedIn Object' );
pb_frolic::$ui->end_metabox();

$url = pb_frolic::page_url();
echo pb_frolic::$ui->button( $url, 'Back' );




?>
