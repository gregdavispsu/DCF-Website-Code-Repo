<?php
//namespace pluginbuddy\frolic;

pb_frolic::$ui->title( 'Frolic Settings' );

// Prepare data for plugin settings form.
	$settings_form = new pb_frolic_settings('settings', '' );

		// Checkbox input:
		$settings_form->add_setting( array(
			'type'		=>		'checkbox',
			'name'		=>		'enable_twitter_dash',
			'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
			'title'		=>		'Twitter in Dashboard',
			'tip'		=>		'When enabled, will display feed objects from active Twitter accounts in the dashboard.',
			'css'		=>		'',
			'after'		=>		'',
			'rules'		=>		'required',
		) );
		$settings_form->add_setting( array(
			'type'		=>		'checkbox',
			'name'		=>		'enable_facebook_dash',
			'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
			'title'		=>		'Facebook in Dashboard',
			'tip'		=>		'When enabled, will display Activity Feed, and Comments objects from active Facebook accounts in the dashboard.',
			'css'		=>		'',
			'after_tr'  =>      '<p>this is after the title</p>',
			'before'    =>      '',
			'after_tr_param' => 'true',
			'rules'		=>		'required',
		) );
		
		$settings_form->add_setting( array(
			'type'      =>      'text',
			'name'      =>      'it_twit_consumer_key',
			'title'     =>      'Consumer key',
			'css'       =>       'width: 200px',
			'tip'       =>      'Twitter consumer key for Twitter authorization. You can grab this by using your twitter id here https://dev.twitter.com/apps/new',
			'rules'     =>      '',
		) );

		$settings_form->add_setting( array(
			'type'      =>      'text',
			'name'      =>      'it_twit_consumer_secret',
			'title'     =>      'Consumer secret key',
			'css'       =>       'width: 200px',
			'tip'       =>      'Twitter consumer secret key for Twitter authorization. You can grab this by using your twitter id here https://dev.twitter.com/apps/new',
			'rules'     =>      '',
		) );

		$settings_form->add_setting( array(
			'type'      =>      'text',
			'name'      =>      'it_twit_access_token',
			'title'     =>      'Access token',
			'css'       =>       'width: 200px',
			'tip'       =>      'Twitter access token for Twitter authorization. You can grab this by using your twitter id here https://dev.twitter.com/apps/new',
			'rules'     =>      '',
		) );

		$settings_form->add_setting( array(
			'type'      =>      'text',
			'name'      =>      'it_twit_access_secret',
			'title'     =>      'Access secret token',
			'css'       =>       'width: 200px',
			'tip'       =>      'Twitter access secret token for Twitter authorization. You can grab this by using your twitter id here https://dev.twitter.com/apps/new',
			'rules'     =>      '',
			'after_table'  =>      '<p style="color: #AFAFAF;">To use this plugin with twitter please go <a href="https://dev.twitter.com/apps" >here</a> and register an app with twitter. After you have registered your application, hit the button "create my access token" at the bottom of the page. After that enter in your consumer key, consumer secret key, access token, and access token secret into the settings above. This will allow you to pull tweets from any account.</p>',
			'after_tr_param' => 'false',
		) );
		
		$data['settings_form'] = $settings_form;

		$settings_form->process(); // Handles processing the submitted form (if applicable).
	

// Load view.
pb_frolic::load_view( 'settings', $data );



?>
