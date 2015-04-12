<?php
//namespace pluginbuddy\frolic;
?>
<style type="text/css">
ul.dot {
	list-style-type:disc;
	}
ul.dot li{
	margin-left:20px;
}
</style>
<?php pb_frolic::$ui->start_metabox( 'Setting up Frolic' ); ?>
	<h4>Getting Started</h4>
	<ol>
		<li>Click on the Accounts page in the admin menu.</li>
		<li>Select an account type, and give this new account a memorable name, then click to create a new account.</li>
		<li>Mouse over the new account to reveal the 'edit account settings' link. Click this link.</li>
		<li>Congrats! You have added a Frolic account!</li>
	</ol>
	<h4>Inside of the Account Settings Page</h4>
	<ul class="dot">
		<li>The first section on this page allows you to change the settings for the given account.</li>
		<li>The next area is a form for adding new objects to the account.</li>
		<li>An object may take the form of anything from a button to a comments area.</li>
	</ul>
	<ol>
		<li>Select the type of object you would like to create.</li>
		<li>Give this new object a descriptive name. Click to create a new object.</li>
		<li>Mouse over the item to reveal the 'edit object settings' link and click this link.</li>
		<li>You may now edit the settings of the newly created object and click to save your changes.</li>
	</ol>
	
	<p>Each object you create inside of an account is given a unique shortcode. This shortcode may be copied and pasted anywhere on your site to display that object.</p>
	
	<h4>Shortcodes</h4>
	<ul class="dot">
		<li>Shortcodes are provided for all Account types.</li>
		<li>A unique shortcode is created for each object inside of each account.</li>
		<li>This shortcode may be copied to any place on your site where you want the corresponding object to display.</li>
	</ul>
	<h4>Widget Usage</h4>
	<ol>
		<li>Make sure you have at least one account, with one object already created using the steps listed above.</li>
		<li>Select 'Appearance' from the admin menu and navigate to 'Widgets.'</li>
		<li>Find the Frolic widget in the available widgets and drag it to the desired widget area.</li>
		<li>Find the object you would like to display in the drop down menu, select it, then click save.</li>
		<li>Congrats! You have now added a Frolic object to a widget area.</li>
	</ol>
	<h4>The Dashboard</h4>
	<ul class="dot">
		<li>When a user creates a new Twitter account, there is a default feed item created.</li>
		<li>You may edit this feed item to use whatever Twitter user ID you would like, if you have "Allow Dashboard Display" enabled, this feed will also show up on your wordpress dashboard.</li>
		<li>You may add more feeds, and so long as the account has a public feed, they may all display in your dashboard.</li>
		<li>If you would like to have an account with a feed on your site, but not on your dashboard, simply disable the dashboard setting, and use the shortcode.</li>
		<li>If you add a Facebook account into Frolic and add a comments box or activity feed, you may also display those on the dashboard as well.</li>
	</ul>
	<h4>Important Information</h4>
	<ul class="dot">
		<li>Twitter has a mandatory refresh buffer. Once you set up a feed, that buffer will only update every 6-10 minutes. This is in place in order to keep their servers from being hit too hard by user requests.</li>
		<li>If you make an update, please give it an ample amount of time to reflect the change.</li>
		<li>Facebook's api goes down from time to time. If you see a red box that says invalid action, give them a bit to bring it back online (usually a couple of hours). Otherwise, you can disable the account so you don't have to look at those ugly red boxes.</li>
		<li>The Google Badge requires your Google+ page URL.  If you don't have one you should be able to create one <a href="https://plus.google.com/pages/create">here</a> .</li>
		<li>In order to pull company info you will need to use your company ID from LinkedIn. If you go to your company's page on LinkedIn you'll see http://www.linkedin.com/company/######?some_other_stuff. You'll 
		want to grab the "######" part. This is the company ID.</li>
	</ul>
	
<?php pb_frolic::$ui->end_metabox(); ?>