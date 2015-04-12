<?php
//namespace pluginbuddy\frolic;

if ( false === ( $account = &pb_frolic::get_group( 'accounts#' . $_GET['edit'] ) ) ) {
	pb_frolic::alert( 'Error: Invalid group ID `' . htmlentities( $_GET['edit'] ) . '`', true );
	return;
}

pb_frolic::$ui->title( 'Group Settings for "' . stripslashes( $account['title'] ) . '" (<a href="' . pb_frolic::page_url() . '">group list</a>)</h2>' );



$this_array = array('dan' => 'name', 'hi' => 'hello');
// ########## BEGIN SUBMISSION PROCESSING ##########
$data = $this_array;
// Image has been updated in media library.
// Load view.
pb_frolic::load_view( '_settings-edit', $data );
?>
