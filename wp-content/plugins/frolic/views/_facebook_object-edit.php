<?php //namespace pluginbuddy\frolic;

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'like') {
	pb_frolic::$ui->title( 'Like Button Settings' );
	pb_frolic::$ui->start_metabox( 'Like Button Settings' );
	$facebook_like_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'comments') {
	pb_frolic::$ui->title( 'Comments Settings' );
	pb_frolic::$ui->start_metabox( 'Comments Box Settings' );
	$facebook_comments_setting_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'activity_feed') {
	pb_frolic::$ui->title( 'Activity Feed Settings' );
	pb_frolic::$ui->start_metabox( 'Activity Feed Settings' );
	$facebook_activity_feed_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'send') {
	pb_frolic::$ui->title( 'Send Button Settings' );
	pb_frolic::$ui->start_metabox( 'Send Button Settings' );
	$facebook_send_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'like_box') {
	pb_frolic::$ui->title( 'Like Box Settings' );
	pb_frolic::$ui->start_metabox( 'Like Box Settings' );
	$facebook_like_box_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'recommendations') {
	pb_frolic::$ui->title( 'Recommendation box Settings' );
	pb_frolic::$ui->start_metabox( 'Recommendations Settings' );
	$facebook_recommendations_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'login_button') {
	pb_frolic::$ui->title( 'Login Button Settings' );
	pb_frolic::$ui->start_metabox( 'Login Button Settings' );
	$facebook_login_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'live_stream') {
	pb_frolic::$ui->title( 'Live Stream Settings' );
	pb_frolic::$ui->start_metabox( 'Live Stream Settings' );
	$facebook_live_stream_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'subscribe') {
	pb_frolic::$ui->title( 'Subscribe Button Settings' );
	pb_frolic::$ui->start_metabox( 'Subscribe Button Settings' );
	$facebook_subscribe_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
$url = pb_frolic::page_url() . '&edit=' . $_GET['edit'];
echo pb_frolic::$ui->button( $url, 'Back' );

?>
