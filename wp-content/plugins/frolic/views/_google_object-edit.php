<?php //namespace pluginbuddy\frolic;
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'plusone_button') {
	pb_frolic::$ui->title( '+1 Button Settings' );
	pb_frolic::$ui->start_metabox( '+1 Button Settings' );
	$plusone_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'badge') {
	pb_frolic::$ui->title( 'Badge Settings' );
	pb_frolic::$ui->start_metabox( 'Badge Settings' );
	$badge_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'share_button') {
	pb_frolic::$ui->title( 'Share Button Settings' );
	pb_frolic::$ui->start_metabox( 'Share Button Settings' );
	$share_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
$url = pb_frolic::page_url() . '&edit=' . $_GET['edit'];
echo pb_frolic::$ui->button( $url, 'Back' );


?>
