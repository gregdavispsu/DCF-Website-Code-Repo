<?php //namespace pluginbuddy\frolic;

if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'share') {
	pb_frolic::$ui->title( 'Share Button Settings' );
	pb_frolic::$ui->start_metabox( 'Share Button Settings' );
	$linkedin_share_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'profile') {
	pb_frolic::$ui->title( 'LinkedIn Profile Settings' );
	pb_frolic::$ui->start_metabox( 'LinkedIn Profile Settings' );
	$linkedin_profile_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'insider') {
	pb_frolic::$ui->title( 'LinkedIn Insider Settings' );
	pb_frolic::$ui->start_metabox( 'LinkedIn Insider Settings' );
	$linkedin_insider_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'c_profile') {
	pb_frolic::$ui->title( 'LinkedIn Company Profile Settings' );
	pb_frolic::$ui->start_metabox( 'LinkedIn Company Profile Settings' );
	$linkedin_company_profile_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'recommend') {
	pb_frolic::$ui->title( 'Recommend Button Settings' );
	pb_frolic::$ui->start_metabox( 'Recommend Button Settings' );
	$linkedin_recommend_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'jobs') {
	pb_frolic::$ui->title( 'LinkedIn Jobs Settings' );
	pb_frolic::$ui->start_metabox( 'LinkedIn Jobs Settings' );
	$linkedin_show_jobs_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}
if(pb_frolic::$options['accounts'][$_GET['edit']]['objects'][$_GET['editobject']]['type'] == 'follow') {
	pb_frolic::$ui->title( 'Follow Button Settings' );
	pb_frolic::$ui->start_metabox( 'Follow Button Settings' );
	$linkedin_follow_button_form->display_settings( 'Save Settings' );
	pb_frolic::$ui->end_metabox();
}


$url = pb_frolic::page_url() . '&edit=' . $_GET['edit'];
echo pb_frolic::$ui->button( $url, 'Back' );

?>
