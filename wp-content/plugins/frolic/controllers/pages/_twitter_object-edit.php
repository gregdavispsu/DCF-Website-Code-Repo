<?php
//namespace pluginbuddy\frolic;

	/********** Tweet Button Settings Form **********/

	
	 // Text input:
	$tweet_button_form = new pb_frolic_settings('tweet_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	 // Select input:
	$tweet_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'size',
		'title'		=>		'Size',
		'options'	=>		array( 'medium' => 'Medium', 'large' => 'Large' ),
		'tip'		=>		'What size would you like the button to be?',
		'rules'		=>		'required',
	) );
	// Text input:
	$tweet_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'related',
		'title'		=>		'Related accounts',
		'tip'		=>		'After the user has tweeted, this account will be suggested to the user as a related account.',
		'rules'		=>		'required|string[1-255]',
	) );
	//Text input:
	$tweet_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'related_desc',
		'title'		=>		'Related accounts description',
		'tip'		=>		'Description for the related account.',
		'rules'		=>		'required|string[1-255]',
	) );

	$data['tweet_button_form'] = $tweet_button_form;
	$tweet_button_form->process();
	/**********  Tweet Button Settings Form END **********/
	 
/**************************************************************************************************************************************************************************************/	
	$hashtag_button_form = new pb_frolic_settings('hashtag_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$hashtag_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'hashtag',
		'title'		=>		'Hashtag',
		'tip'		=>		'Enter the hashtag excluding the # symbol here.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Text input:
	$hashtag_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'default_text',
		'title'		=>		'Default text',
		'tip'		=>		'Default text you want in the tweet field.',
		'rules'		=>		'string[0-255]',
	) );
	// Text input:
	$hashtag_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'related',
		'title'		=>		'Related accounts',
		'tip'		=>		'After the user has tweeted, this account will be suggested to the user as a related account.',
		'rules'		=>		'string[0-255]',
	) );
	$hashtag_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'related_desc',
		'title'		=>		'Related account description',
		'tip'		=>		'Enter a description of your recommendation here.',
		'rules'		=>		'string[0-255]',
	) );
	$data['hashtag_button_form'] = $hashtag_button_form;
	$hashtag_button_form->process();
	
/**************************************************************************************************************************************************************************************/	
 
	$follow_button_form = new pb_frolic_settings('follow_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	//Text input:
	$follow_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'user',
		'title'		=>		'User',
		'tip'		=>		'Who would you like this follow button to refer to?',
		'rules'		=>		'required|string[1-255]',
	) );
	$data['follow_button_form'] = $follow_button_form;
	$follow_button_form->process();
	
/**************************************************************************************************************************************************************************************/	
	 
	$mention_button_form = new pb_frolic_settings('mention_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$mention_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'user',
		'title'		=>		'User',
		'tip'		=>		'Tweet to this @user.',
		'rules'		=>		'required|string[1-255]',
	) );

	$data['mention_button_form'] = $mention_button_form;
	$mention_button_form->process();
	
/*****************************************************************************************************************************************************************************************/
	$twitter_feed_form = new pb_frolic_settings('twitter_feed', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
		$twitter_feed_form->add_setting( array(
			'type'		=>		'text',
			'name'		=>		'title',
			'title'		=>		'Title',
			'tip'		=>		'What is the title of the feed?',
			'rules'		=>		'required|string[1-255]',
		) );
		$twitter_feed_form->add_setting( array(
			'type'		=>		'text',
			'name'		=>		'pb_twittergitter_id',
			'title'		=>		'User',
			'tip'		=>		'Which feed would you like to pull?',
			'rules'		=>		'required|string[1-255]',
		) );
		$twitter_feed_form->add_setting( array(
			'type'		=>		'text',
			'name'		=>		'num_posts',
			'title'		=>		'Posts to display',
			'tip'		=>		'How many post would you like to display?',
			'rules'		=>		'required|int[1-255]',
		) );
		$twitter_feed_form->add_setting( array(
			'type'		=>		'checkbox',
			'name'		=>		'enable_name',  //just added these, make sure they work
			'options'	=>		array( 'unchecked' => false, 'checked' => true ),
			'title'		=>		'Enable feed names',
			'tip'		=>		'When enabled will display name of user before each feed object.',
			'rules'		=>		'',
		) );
		$twitter_feed_form->add_setting( array(
			'type'		=>		'checkbox',
			'name'		=>		'enable_title', //just added these, make sure they work
			'options'	=>		array( 'unchecked' => false, 'checked' => true ),
			'title'		=>		'Enable feed title',
			'tip'		=>		'When enabled will display title at top of feed.',
			'rules'		=>		'',
		) );
	$data['twitter_feed_form'] = $twitter_feed_form;
	$twitter_feed_form->process();

pb_frolic::load_view( '_twitter_object-edit', $data );
?>
