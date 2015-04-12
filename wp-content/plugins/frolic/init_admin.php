<?php
//namespace pluginbuddy\frolic;
pb_frolic::load();


// ********** ACTIONS (admin) **********



// ********** AJAX (admin) **********



// ********** DASHBOARD (admin) **********
if( pb_frolic::$options['enable_twitter_dash'] == 'true' ){
	pb_frolic::add_dashboard_widget('pb_twitter', 'Twitter', true);
}
if( pb_frolic::$options['enable_facebook_dash'] == 'true' ){
	pb_frolic::add_dashboard_widget('pb_facebook', 'Facebook', true);
}

// ********** FILTERS (admin) **********


// ********** PAGES (admin) **********
pb_frolic::add_page( '', 'getting_started', array( pb_frolic::settings( 'name' ), 'Getting Started' ) ); // parent_slug (use SERIES for series), page_slug, title
pb_frolic::add_page( 'getting_started', 'accounts', 'Accounts' );
pb_frolic::add_page( 'getting_started', 'settings', 'Settings' );




// ********** LIBRARIES & CLASSES (admin) **********



// ********** OTHER (admin) **********



?>
