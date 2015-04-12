<?php //namespace pluginbuddy\frolic;


if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'tweet') {
	pb_frolic::$ui->title( 'Tweet Button Settings' );
	pb_frolic::$ui->start_metabox( 'Tweet Button Settings' );
	$tweet_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'hashtag') {
	pb_frolic::$ui->title( 'Hashtag Button Settings' );
	pb_frolic::$ui->start_metabox( 'Hashtag Button Settings' );
	$hashtag_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'follow') {
	pb_frolic::$ui->title( 'Follow Button Settings' );
	pb_frolic::$ui->start_metabox( 'Follow Button Settings' );
	$follow_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'mention') {
	pb_frolic::$ui->title( 'Mention Button Settings' );
	pb_frolic::$ui->start_metabox( 'Mention Button Settings' );
	$mention_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'feed') {
	pb_frolic::$ui->title( 'Twitter Feed Settings' );
	pb_frolic::$ui->start_metabox( 'Twitter Feed Settings' );
	$twitter_feed_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
	if ( isset( $_GET['edit'] ) && isset( $_GET['editobject'] ) ) {
		$pb_twittergitter_id = pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['pb_twittergitter_id'];
		delete_transient('pb_twittergitter' . $pb_twittergitter_id);  		//uncomment to do a quick refresh for testing.
	}
}

$url = pb_frolic::page_url() . '&edit=' . $_GET['edit'];
echo pb_frolic::$ui->button( $url, 'Back' );


?>
