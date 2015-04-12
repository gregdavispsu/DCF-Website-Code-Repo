<?php
//namespace pluginbuddy\frolic;


/**************************************************************************************************************************************************************************************/	
	 
	$plusone_button_form = new pb_frolic_settings('plusone_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Select input:
	$plusone_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'size',
		'title'		=>		'Button size',
		'options'	=>		array( 'small' => 'Small', 'medium' => 'Medium', 'tall' => 'Tall'),
		'tip'		=>		'Controls the size of the Google +1 button.',
		'rules'		=>		'required',
	) );
	$plusone_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'annotation',
		'title'		=>		'Style',
		'options'	=>		array( '' => 'Bubble', 'inline' => 'Inline', 'none' => 'None' ),
		'tip'		=>		'Controls where extra information appears for the button, such as the number of pluses.',
	) );

	$data['plusone_button_form'] = $plusone_button_form;
	$plusone_button_form->process();
	
/**************************************************************************************************************************************************************************************/	
	 
	$badge_form = new pb_frolic_settings('badge', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	// Select input:
	// Text input:
	$badge_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'g_url',
		'title'		=>		'Google+ page',
		'tip'		=>		'Page that the badge should reference. This should be your plus.google.com ID, example: http://plus.google.com/<b>this_huge_number</b>, This is not your personal profile, this is for a "Google+ Page"',
		'rules'		=>		'required|string[1-255]',
		'css'		=>		'width: 300px;',
	) );
	// Select input:
	$badge_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'theme',
		'title'		=>		'Theme',
		'options'	=>		array( 'light' => 'Light', 'dark' => 'Dark' ),
		'tip'		=>		'Controls the color scheme of the badge.',
		'rules'		=>		'required',
	) );
	$badge_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'height',
		'title'		=>		'Height',
		'options'	=>		array( '69' => 'Short', '131' => 'Tall' ),
		'tip'		=>		'Controls the height of the badge.',
		'rules'		=>		'required',
	) );
	$badge_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'width',
		'title'		=>		'Width',
		'tip'		=>		'As you shrink the Badge width, it will automatically remove things that can no longer fit. Will accept any number between 100-400',
		'rules'		=>		'required|int[100-400]',
		'css'		=>		'width: 50px; text-align: right;',
		'after'		=>		' px',
	) );

	$data['badge_form'] = $badge_form;
	$badge_form->process();

/**************************************************************************************************************************************************************************************/
	$share_button_form = new pb_frolic_settings('share_button', 'accounts#' . $_GET['edit'] . '#objects#' . $_GET['editobject'], 'edit=' . $_GET['edit'] . '&editobject=' . $_GET['editobject'] );
	$share_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'height',
		'title'		=>		'Button size',
		'options'	=>		array( '15' => 'Small', 'medium' => 'Medium', '24' => 'Large'),
		'tip'		=>		'Controls the size of the Google +1 button.',
		'rules'		=>		'',
	) );
	$share_button_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'annotation',
		'title'		=>		'Style',
		'options'	=>		array( 'bubble' => 'Bubble', 'vertical-bubble' => 'Vertical Bubble', 'inline' => 'Inline', 'none' => 'None' ),
		'tip'		=>		'Controls where extra information appears for the button, such as the number of pluses.',
	) );
	$data['share_button_form'] = $share_button_form;
	$share_button_form->process();
pb_frolic::load_view( '_google_object-edit', $data );
?>
