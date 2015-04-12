<?php
//namespace pluginbuddy\frolic;


	 // Text input:
	$facebook_like_button_form = new pb_frolic_settings('facebook_like_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$facebook_like_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'Override URL',
		'tip'		=>		'The URL that is linked to this button. If left blank, will default to page the button is placed on.',
		'rules'		=>		'string[0-255]',
		'css'		=>		'width: 300px;',
	) );
	// Select input:
	$facebook_like_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'data_layout',
		'title'		=>		'Layout',
		'options'	=>		array( 'standard' => 'Standard', 'button_count' => 'Button Count', 'box_count' => 'Box Count' ),
		'tip'		=>		'Controls the layout for the information to be displayed next to the like button.',
		'rules'		=>		'required',
	) );
	// Text input:
	$facebook_like_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'Width of area the button will occupy.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
		// Checkbox input:
	$facebook_like_button_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'send_button',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Send button',
		'tip'		=>		'Add a Send button beside the Like button.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Checkbox input:
	$facebook_like_button_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'show_faces',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Show faces',
		'tip'		=>		'Show the faces of those who have liked your page or link.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );

	$data['facebook_like_button_form'] = $facebook_like_button_form;
	
	$facebook_like_button_form->process();
	/**********  Like Button Settings Form END **********/
	 
/**************************************************************************************************************************************************************************************/	
	
	/**********  Comments Settings Form **********/
	//pb_frolic::$ui->title( 'Comments Settings' );
	$facebook_comments_setting_form = new pb_frolic_settings('facebook_comments_setting', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$facebook_comments_setting_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'URL',
		'tip'		=>		'URL linked to the comments box.',
		'rules'		=>		'required|string[1-255]',
		'css'		=>		'width: 300px;',
	) );
	// Text input:
	$facebook_comments_setting_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'num_posts',
		'title'		=>		'Posts to display',
		'tip'		=>		'The number of posts to display.',
		'rules'		=>		'required|int[1-255]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' posts',
	) );
	// Text input:
	$facebook_comments_setting_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'Width of the comments box.',
		'rules'		=>		'',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	$data['facebook_comments_setting_form'] = $facebook_comments_setting_form;
	$facebook_comments_setting_form->process();
	/**********  Comments Settings Form END **********/
	
	/**************************************************************************************************************************************************************************************/	
	
	/**********  Activity Feed Settings Form **********/
	//pb_frolic::$ui->title( 'Activity Feed Settings' );
	$facebook_activity_feed_form = new pb_frolic_settings('facebook_activity_feed', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$facebook_activity_feed_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'Domain',
		'tip'		=>		'Domain for the activity feed to reflect, such as www.example.com, if left blank, will default to the domain that the activity feed is on.',
		'rules'		=>		'string[0-255]',
		'css'		=>		'width: 300px;',
	) );
	// Text input:
	$facebook_activity_feed_form->add_setting( array(
		'type'		=>		'color',
		'name'		=>		'color',
		'title'		=>		'Border color',
		'tip'		=>		'Color for the border.',
		'rules'		=>		'required|string[1-800]',
		'css'		=>		'width: 80px;',
	) );
	// Text input:
	$facebook_activity_feed_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'Width of the Activity Feed box.',
		'rules'		=>		'required|string[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Text input:
	$facebook_activity_feed_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'height',
		'title'		=>		'Height',
		'tip'		=>		'Height of the Activity Feed box.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Checkbox input:
	$facebook_activity_feed_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'rec',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Display recommendations',
		'tip'		=>		'Along with activity, this will show user recommendations made through facebook.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Checkbox input:
	$facebook_activity_feed_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'header',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Header',
		'tip'		=>		'This adds a header bar with a title to the activity feed box.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['facebook_activity_feed_form'] = $facebook_activity_feed_form;
	$facebook_activity_feed_form->process();
	/**********  Activity Feed Settings Form  END **********/
	 
	/**************************************************************************************************************************************************************************************/	
	
	/**********  Like Box Settings Form **********/
	//pb_frolic::$ui->title( 'Like Box Settings' );
	$facebook_like_box_form = new pb_frolic_settings('facebook_like_box', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$facebook_like_box_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'Page URL',
		'tip'		=>		'This is the URL to the facebook page which the likebox will reference. Example: http://www.facebook.com/pluginbuddy. Note: This is not for personal pages.',
		'rules'		=>		'required|string[1-255]',
		'css'		=>		'width: 300px;',
	) );
	// Text input:
	$facebook_like_box_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Box width',
		'tip'		=>		'Width of Like Box.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Checkbox input:
	$facebook_like_box_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'show_faces',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Show faces',
		'tip'		=>		'Would you like to show a facepile in the like box?',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Checkbox input:
	$facebook_like_box_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'stream',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Data Stream',
		'tip'		=>		'Do you want to show a data stream in the like box?',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Checkbox input:
	$facebook_like_box_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'header',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Box header',
		'tip'		=>		'Display a Box Header with a title.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['facebook_like_box_form'] = $facebook_like_box_form;
	$facebook_like_box_form->process();
	/**********  Like Box Settings Form END **********/
	  
	 /**************************************************************************************************************************************************************************************/
	
	/**********  Send Button Settings Form **********/
	//pb_frolic::$ui->title( 'Send Button Settings' );
	$facebook_send_button_form = new pb_frolic_settings('facebook_send_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$facebook_send_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'URL',
		'title'		=>		'URL',
		'tip'		=>		'Link for the send button.',
		'rules'		=>		'required|string[1-255]',
		'css'		=>		'width: 300px;',
	) );
	// Text input:
	// Select input:
	$facebook_send_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'font',
		'title'		=>		'Send button font',
		'options'	=>		array( 'arial' => 'Arial', 'lucida grande' => 'Lucida Grande', 'segoe ui' => 'Segoe UI', 'tahoma' => 'Tahoma', 'trebuchet ms' => 'Trebuchet MS', 'verdana' => 'Verdana' ),
		'tip'		=>		'Select a font for your send button.',
		'rules'		=>		'required',
	) );
	$data['facebook_send_button_form'] = $facebook_send_button_form;
	$facebook_send_button_form->process();
	/**********  Send Button Settings Form END **********/
	  
	 /**************************************************************************************************************************************************************************************/
	
	/**********  Recommendations Settings Form **********/
	//pb_frolic::$ui->title( 'Recommendation box Settings' );
	$facebook_recommendations_form = new pb_frolic_settings('facebook_recommendations', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'Reference URL',
		'tip'		=>		'The URL that your recommendation will reference.',
		'rules'		=>		'string[0-255]',
		'css'		=>		'width: 300px;',
	) );
	// Text input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'Width of the recommendations box.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Text input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'height',
		'title'		=>		'Height',
		'tip'		=>		'Height of the recommendations box.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Text input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'color',
		'title'		=>		'Border color',
		'tip'		=>		'Border color of the recommendations box.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Select input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'font',
		'title'		=>		'Font',
		'options'	=>		array( 'arial' => 'Arial', 'lucida grande' => 'Lucida Grande', 'segoe ui' => 'Segoe UI', 'tahoma' => 'Tahoma', 'trebuchet ms' => 'Trebuchet MS', 'verdana' => 'Verdana' ),
		'tip'		=>		'Controls the font of the recommendations box.',
		'rules'		=>		'required',
	) );
	// Select input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'link_target',
		'title'		=>		'Link target',
		'options'	=>		array( '_blank' => 'Blank', '_top' => 'Top', '_parent' => 'Parent' ),
		'tip'		=>		'The context in which content links are opened.',
		'rules'		=>		'required',
	) );
	// Radio input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'radio',
		'name'		=>		'color_scheme',
		'title'		=>		'Theme',
		'options'	=>		array( 'dark' => 'Dark', 'light' => 'Light' ),  //'options'	=>		array( 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' ),
		'tip'		=>		'Color Scheme for the recommendations box.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Checkbox input:
	$facebook_recommendations_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'header',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Header',
		'tip'		=>		'Enables a header with a title on the recommendations box.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['facebook_recommendations_form'] = $facebook_recommendations_form;
	$facebook_recommendations_form->process();
	/**********  Recommendations Settings Form END **********/
	 
	 /**************************************************************************************************************************************************************************************/
	
	/**********  Login Button Settings Form **********/
	//pb_frolic::$ui->title( 'Login Button Settings' );
	$facebook_login_button_form = new pb_frolic_settings('facebook_login_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Checkbox input:
	$facebook_login_button_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'show_faces',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Show faces',
		'tip'		=>		'Show faces on login.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Text input:
	$facebook_login_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'Width of available space for login button and faces.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Text input:
	$facebook_login_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'max_rows',
		'title'		=>		'Max rows',
		'tip'		=>		'The max rows available for the "show faces" feature.',
		'rules'		=>		'required|string[1-255]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' rows',
	) );
	$data['facebook_login_button_form'] = $facebook_login_button_form;
	$facebook_login_button_form->process();
	/**********  Login Button Settings Form END **********/
	  
	 /**************************************************************************************************************************************************************************************/
	
	/**********  Live Stream Settings Form **********/
	//pb_frolic::$ui->title( 'Live Stream Settings' ); // TODO add this to frolic
	$facebook_live_stream_form = new pb_frolic_settings('facebook_live_stream', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$facebook_live_stream_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'app_id',
		'title'		=>		'App ID',
		'tip'		=>		'API key, or application ID, you can sign up for one on ',
		'rules'		=>		'required|string[1-255]',
	) );
	// Text input:
	$facebook_live_stream_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'Width of the stream feed box.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Text input:
	$facebook_live_stream_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'height',
		'title'		=>		'Height',
		'tip'		=>		'Height of the stream feed box.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	// Text input:
	$facebook_live_stream_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'xid',
		'title'		=>		'XID',
		'tip'		=>		'If you have more than one feed on a page, each needs to have a unique ID.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Text input:
	$facebook_live_stream_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'via_attribution_url',
		'title'		=>		'Attribution URL',
		'tip'		=>		'If left blank, will use Connect URL.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Checkbox input:
	$facebook_live_stream_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'post_to_friends',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Always post to friends.',
		'tip'		=>		'Posts will also go to the personal profile of the user.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['facebook_live_stream_form'] = $facebook_live_stream_form;
	$facebook_live_stream_form->process();
	/**********  Live Stream Settings Form END **********/
	/**************************************************************************************************************************************************************************************/
	$facebook_subscribe_button_form = new pb_frolic_settings('facebook_subscribe_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$facebook_subscribe_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'Profile URL',
		'tip'		=>		'Facebook profile URL for the user to subscribe to. This must be a valid profile URL such as http://www.facebook.com/profilename. In order to enable subscribing for your facebook profile you must first go into Account Settings -> Subscribers and click the check box.',
		'rules'		=>		'string[0-255]',
	) );
	$facebook_subscribe_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'data_layout',
		'title'		=>		'Layout',
		'options'	=>		array( 'standard' => 'Standard', 'button_count' => 'Button Count', 'box_count' => 'Box Count' ),
		'tip'		=>		'Controls the layout for the information to be displayed next to the subscribe button.',
		'rules'		=>		'required',
	) );
	$facebook_subscribe_button_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'show_faces',
		'options'	=>		array( 'unchecked' => 'false', 'checked' => 'true' ),
		'title'		=>		'Show faces',
		'tip'		=>		'Show the faces of those who have subscribed to you.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$facebook_subscribe_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'color_scheme',
		'title'		=>		'Theme',
		'options'	=>		array( 'light' => 'Light', 'dark' => 'Dark' ),
		'tip'		=>		'Controls the color scheme of the subscribe button.',
		'rules'		=>		'required',
	) );
	$facebook_subscribe_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'font',
		'title'		=>		'Font',
		'options'	=>		array( 'arial' => 'Arial', 'lucida grande' => 'Lucida Grande', 'segoe ui' => 'Segoe UI', 'tahoma' => 'Tahoma', 'trebuchet ms' => 'Trebuchet MS', 'verdana' => 'Verdana' ),
		'tip'		=>		'Controls the font of the subscribe button.',
		'rules'		=>		'required',
	) );
	$facebook_subscribe_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'Width of the subscribe button area.',
		'rules'		=>		'required|int[1-800]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );
	$data['facebook_subscribe_button_form'] = $facebook_subscribe_button_form;
	$facebook_subscribe_button_form->process();
	
	

pb_frolic::load_view( '_facebook_object-edit', $data );
?>
