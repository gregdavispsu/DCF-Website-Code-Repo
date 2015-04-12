<?php
/**
 *
 *	Plugin Name: Frolic
 *	Plugin URI: http://ithemes.com/purchase/frolic
 *	Description: Integrate social media buttons, feeds, and more into your site and access social media tools from the dashboard.
 *	Version: 1.3.16
 *	Author: iThemes
 *	Author URI: http://ithemes.com
 *  iThemes Package: frolic
 *
 *	Installation:
 * 
 *	1. Download and unzip the latest release zip file.
 *	2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 *	3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 *	4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 * 
 *	Usage:
 * 
 *	1. Navigate to the new plugin menu in the Wordpress Administration Panel.
 *
 *	NOTE: DO NOT EDIT THIS OR ANY OTHER PLUGIN FILES. NO USER-CONFIGURABLE OPTIONS WITHIN.
 */


//namespace pluginbuddy\frolic;

$pluginbuddy_settings = array(
	'slug'				=>		'frolic',
	'series'			=>		'',
	'default_options'	=>		array(
		'accounts'				=>		array(),
		'log_level'				=>		'0',
		'access'				=>		'activate_plugins',
		'enable_facebook_dash'	=>		'false',
		'enable_twitter_dash'	=>		'false',
		'it_twit_consumer_key'    =>      '',
		'it_twit_consumer_secret' =>      '',
		'it_twit_access_token'    =>      '',
		'it_twit_access_secret'   =>      '',
	),
	'group_defaults'	=>		array(
		'title'					=>		'title',
		'name'					=>		'name',
		'num_of_tweets'			=>		'3',
		'user_id'				=>		'user id',
		'type'					=>		'type',
		'dash_display'			=>		'false',
		'dash_posting'			=>		'true',
		'allow_broadcast'		=>		'true',
		'default_broadcast'		=>		'true',
		'enabled'				=>		'true',
		'objects'				=>		array(
		),
	),
	'facebook_defaults'	=>		array(
		'app_id'				=>		'',								//facebook
		'xid'					=>		'',								//facebook
		'via_attribution_url'	=>		'',								//facebook
		'url'					=>		'',								//any
		'width'					=>		400,							//any
		'height'				=>		400,							//any
		'data_layout'			=>      'standard',
		'color'					=>		'#000000',						//any
		'font'					=>		'arial',						//any
		'data_layout'			=>      'standard',
		'link_target'			=>		'_parent',						//facebook
		'color_scheme'			=>		'light',						//facebook
		'size'					=>		'medium',
		'max_rows'				=>		'2',							//facebook
		'post_to_friends'		=>		'false',						//facebook
		'stream'				=>		'false',						//facebook
		'send_button'			=>		'false',						//facebook
		'show_faces'			=>		'false',						//facebook
		'header'				=>		'true',							//facebook
		'num_posts'				=>		'4',
		'rec'					=>		'false',						//facebook
	),
	'twitter_defaults'	=>		array(
		'default_text'			=>		'',								//twitter
		'title'					=>		'iThemes',
		'pb_twittergitter_id'	=>		'ithemes',					    //twitter
		'user'					=>		'ithemes',					    //twitter
		'url'					=>		'',	                            //any
		'width'					=>		'400',							//any
		'height'				=>		'800',							//any
		'hashtag'				=>		'hashtag',						//twitter
		'color'					=>		'black',						//any
		'enable_name'			=>      true,
		'enable_title'			=>      true,
		'font'					=>		'arial',						//any
		'related'				=>		'',								//twitter
		'related_desc'			=>		'',								//twitter
		'size'					=>		'medium',
		'rec'					=>		false,							//twitter
		'num_posts'				=>		'4',
	),
	'linkedin_defaults'	=>		array(
		'url'					=>		'',				//any
		'c_name'				=>		'',
		'layout'				=>		'',
		'data_id'				=>		'',
		'format'				=>		'inline',
		'related'				=>		'false',
		'data_text'				=>		'',
		'innetwork'				=>		'',
		'newhires'				=>		'',
		'jobchanges'			=>		'',
		'data_counter'			=>		'',
		'data_company'			=>		'',
		'data_product'			=>		'',
		'company_id'			=>		'',
		'id'					=>		'',
	),
	'google_defaults'	=>		array(
		'g_url'					=>		'117407736693149783588', 		//gplus
		'url'					=>		'',								//any
		'width'					=>		'300',							//any
		'height'				=>		'131',							//any
		'annotation'			=>      '',
		'color'					=>		'black',						//any
		'font'					=>		'arial',						//any
		'size'					=>		'medium',
		'num_posts'				=>		'4',
		'theme'					=>      'light',
	),
	'widget_defaults'	=>     array(
		'group'     =>     array(),
	),
	'modules'   =>  array(
		'downsizer'   =>  false,   // Load thumbnail image downsizer.
		'filesystem'  =>  false,   // File system helper methods.
		'format'   =>  false,   // Text / data formatting helper methods.
		'updater'   =>  true,   // Load PluginBuddy automatic upgrades.
	)
);


// $settings is expected to be populated prior to including PluginBuddy framework. Do not edit below.
require( dirname( __FILE__ ) . '/pluginbuddy/_pluginbuddy.php' );

function it_frolic_updater_register( $updater ) {
$updater->register( 'frolic', __FILE__ );
}
add_action( 'ithemes_updater_register', 'it_frolic_updater_register' );


require( dirname( __FILE__ ) . '/lib/updater/load.php' );

?>
