<?php
//namespace pluginbuddy\frolic;


	 // Text input:
	$linkedin_share_button_form = new pb_frolic_settings('linkedin_share_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$linkedin_share_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'Override URL',
		'tip'		=>		'The URL that is linked to this button. If left blank, will default to page the button is placed on.',
		'rules'		=>		'string[0-255]',
		'css'		=>		'width: 300px;',
	) );
	// Select input:
	$linkedin_share_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'layout',
		'title'		=>		'Count Layout',
		'options'	=>		array( 'top' => 'Top', 'right' => 'Right', '' => 'None' ),
		'tip'		=>		'Where do you want to show the number of shares.',
		'rules'		=>		'required',
	) );
	// Select input:
	$data['linkedin_share_button_form'] = $linkedin_share_button_form;
	$linkedin_share_button_form->process();
	/****************************************************************/
	$linkedin_profile_form = new pb_frolic_settings('linkedin_profile', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$linkedin_profile_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'url',
		'title'		=>		'Public Profile URL',
		'tip'		=>		'This must be a public profile.',
		'rules'		=>		'string[0-255]',
		'css'		=>		'width: 300px;',
	) );
	// Select input:
	$linkedin_profile_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'layout',
		'title'		=>		'Profile Layout',
		'options'	=>		array( 'inline' => 'Inline', 'hover' => 'Hover', 'click' => 'Click'  ),
		'tip'		=>		'Controls where the profile information is displayed.',
		'rules'		=>		'required',
	) );
	// Text input:
	$linkedin_profile_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'data_text',
		'title'		=>		'Text to display', // tip: try leaving this blank!
		'tip'		=>		'For use with hover and click layouts.',
		'rules'		=>		'required|string[1-255]',
	) );
	$linkedin_profile_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'related',
		'title'		=>		'Show Connections',
		'options'	=>		array( 'true' => 'Yes', 'false' => 'No' ),
		'tip'		=>		'Show connections?',
		'rules'		=>		'required',
	) );
	$data['linkedin_profile_form'] = $linkedin_profile_form;
	$linkedin_profile_form->process();
	/*******************************************************************/
	$linkedin_insider_form = new pb_frolic_settings('linkedin_insider', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$linkedin_insider_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'c_name',
		'title'		=>		'Company ID',
		'tip'		=>		'The ID of your company.',
		'rules'		=>		'string[0-255]',
		'css'		=>		'width: 300px;',
	) );
	// Checkbox input:
	$linkedin_insider_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'innetwork',
		'options'	=>		array( 'unchecked' => 'hide', 'checked' => 'show' ),
		'title'		=>		'In Network',
		'tip'		=>		'Show or hide people in your network. If all disabled, or all enabled, will show default.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Checkbox input:
	$linkedin_insider_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'newhires',
		'options'	=>		array( 'unchecked' => 'hide', 'checked' => 'show' ),
		'title'		=>		'New Hires',
		'tip'		=>		'Show or hide new hires. If all disabled, or all enabled, will show default.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	// Checkbox input:
	$linkedin_insider_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'jobchanges',
		'options'	=>		array( 'unchecked' => 'hide', 'checked' => 'show' ),
		'title'		=>		'Changes and Promotions',
		'tip'		=>		'Show or hide changes and promotions. If all disabled, or all enabled, will show default.',
		'css'		=>		'',
		'after'		=>		'',
		'rules'		=>		'required',
	) );
	$data['linkedin_insider_form'] = $linkedin_insider_form;
	$linkedin_insider_form->process();
	/*******************************************************************/
	$linkedin_company_profile_form = new pb_frolic_settings('linkedin_company_profile', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$linkedin_company_profile_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'c_name',
		'title'		=>		'Company ID',
		'tip'		=>		'The ID of your company found on LinkedIn.',
		'rules'		=>		'string[0-255]',
		'css'		=>		'width: 300px;',
	) );
	// Select input:
	$linkedin_company_profile_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'layout',
		'title'		=>		'Profile Layout',
		'options'	=>		array( 'inline' => 'Inline', 'hover' => 'Hover', 'click' => 'Click'  ),
		'tip'		=>		'Controls where the profile information is displayed.',
		'rules'		=>		'required',
	) );
	// Text input:
	$linkedin_company_profile_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'data_text',
		'title'		=>		'Text to display', // tip: try leaving this blank!
		'tip'		=>		'For use with hover and click layouts.',
		'rules'		=>		'required|string[1-255]',
	) );
	$linkedin_company_profile_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'related',
		'title'		=>		'Show Connections',
		'options'	=>		array( '' => 'Yes', 'false' => 'No' ),
		'tip'		=>		'Show connections?',
		'rules'		=>		'required',
	) );
	$data['linkedin_company_profile_form'] = $linkedin_company_profile_form;
	$linkedin_company_profile_form->process();
	/*******************************************************************/
	$linkedin_recommend_button_form = new pb_frolic_settings('linkedin_recommend_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Text input:
	$linkedin_recommend_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'data_company',
		'title'		=>		'Company ID', // tip: try leaving this blank!
		'tip'		=>		'ID of company.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Text input:
	$linkedin_recommend_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'data_product',
		'title'		=>		'Product ID', // tip: try leaving this blank!
		'tip'		=>		'This can be found as the numeric part of the URL for the product page on LinkedIn.',
		'rules'		=>		'required|string[1-255]',
	) );
	// Select input:
	$linkedin_recommend_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'format',
		'title'		=>		'Count Layout',
		'options'	=>		array( 'top' => 'Vertical', 'right' => 'Horizontal', '' => 'None' ),
		'tip'		=>		'Changes where the count will display.',
		'rules'		=>		'required',
	) );
	$data['linkedin_recommend_button_form'] = $linkedin_recommend_button_form;
	$linkedin_recommend_button_form->process();
	/*******************************************************************/
	$linkedin_show_jobs_form = new pb_frolic_settings('linkedin_show_jobs', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$linkedin_show_jobs_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'layout',
		'title'		=>		'Show which jobs?',
		'options'	=>		array( 'yours' => 'Your Jobs', 'all' => 'All Jobs' ),
		'tip'		=>		'Changes where the count will display.',
		'rules'		=>		'required',
	) );
	$linkedin_show_jobs_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'data_company',
		'title'		=>		'Company ID', 
		'tip'		=>		'ID of company as listed in LinkedIn. Necessary if displaying your jobs only.',
		'rules'		=>		'required|string[1-255]',
	) );
	$data['linkedin_show_jobs_form'] = $linkedin_show_jobs_form;
	$linkedin_show_jobs_form->process();
	/*******************************************************************/
	$linkedin_follow_button_form = new pb_frolic_settings('linkedin_follow_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$linkedin_follow_button_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'data_company',
		'title'		=>		'Company ID', 
		'tip'		=>		'ID of company as listed in LinkedIn.',
		'rules'		=>		'required|string[1-255]',
	) );
	$linkedin_follow_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'layout',
		'title'		=>		'Count Layout',
		'options'	=>		array( 'top' => 'Vertical', 'right' => 'Horizontal', 'none' => 'None' ),
		'tip'		=>		'Changes where the count will display.',
		'rules'		=>		'',
	) );
	$data['linkedin_follow_button_form'] = $linkedin_follow_button_form;
	$linkedin_follow_button_form->process();
	/*******************************************************************/
	 
pb_frolic::load_view( '_linkedin_object-edit', $data );
?>