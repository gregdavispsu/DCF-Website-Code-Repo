<?php


// This file is automatically loaded for the getting started page as a `template` of sorts.
// The individual plugin getting started page is included from this.


// Set up supporting scripts and styles.
pb_frolic::load_script( 'dashboard' );
pb_frolic::load_style( 'dashboard' );
pb_frolic::load_script( 'jquery-ui-tabs' );
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#pluginbuddy-tabs').tabs();
	});
</script>
<?php
echo '<div style="float: right; width: 20%; margin-right: 30px;"><br><br>';
	pb_frolic::$ui->start_metabox( 'Things to do . . .', true, true );
		echo '<ul class="pluginbuddy-nodecor">';
		echo '	<li>- <a href="http://twitter.com/home?status=' . urlencode('Check out this awesome plugin, ' . pb_frolic::settings( 'name' ) . '! http://pluginbuddy.com @pluginbuddy') . '" title="Share on Twitter" onClick="window.open(jQuery(this).attr(\'href\'),\'ithemes_popup\',\'toolbar=0,status=0,width=820,height=500,scrollbars=1\'); return false;">Tweet about this plugin.</a></li>';
		echo '	<li>- <a href="http://pluginbuddy.com/purchase/">Check out PluginBuddy plugins.</a></li>';
		echo '	<li>- <a href="http://pluginbuddy.com/purchase/">Check out iThemes themes.</a></li>';
		echo '</ul>';
	pb_frolic::$ui->end_metabox();
	
	pb_frolic::$ui->start_metabox( 'PluginBuddy News', true, true );
		echo '<p style="font-weight: bold;">PluginBuddy.com</p>';
		echo pb_frolic::$ui->get_feed( 'http://pluginbuddy.com/feed/', 5 );
		echo '<p style="font-weight: bold;">Twitter @pluginbuddy</p>';
		$twit_append = '<li>&nbsp;</li>';
		$twit_append .= '<li><img src="' . pb_frolic::plugin_url() . '/pluginbuddy/images/twitter.png" style="vertical-align: -3px;" /> <a href="http://twitter.com/pluginbuddy/">Follow @pluginbuddy on Twitter.</a></li>';
		$twit_append .= '<li><img src="' . pb_frolic::plugin_url() . '/pluginbuddy/images/feed.png" style="vertical-align: -3px;" /> <a href="http://pluginbuddy.com/feed/">Subscribe to RSS news feed.</a></li>';
		$twit_append .= '<li><img src="' . pb_frolic::plugin_url() . '/pluginbuddy/images/email.png" style="vertical-align: -3px;" /> <a href="http://pluginbuddy.com/subscribe/">Subscribe to Email Newsletter.</a></li>';
		echo pb_frolic::$ui->get_feed( 'http://twitter.com/statuses/user_timeline/108700480.rss', 5, $twit_append, 'pluginbuddy: ' );
	pb_frolic::$ui->end_metabox();
	
	pb_frolic::$ui->start_metabox( 'Help & Support', true, true );
		echo '<p>See our <a href="http://ithemes.com/codex/page/PluginBuddy">Knowledge Base</a>, <a href="http://pluginbuddy.com/tutorials/">tutorials & videos</a> or visit our <a href="http://pluginbuddy.com/support/">support forum</a> for additional information and help.</p>';
	pb_frolic::$ui->end_metabox();
echo '</div>';



if ( pb_frolic::settings( 'series' ) != '' ) { // SERIES
	pb_frolic::$ui->title( 'Getting Started with ' . pb_frolic::settings( 'series' ) );
	?>
	<div id="pluginbuddy-tabs" style="width: 70%;">
		<ul>
			<?php
			global $pluginbuddy_series;
			
			$i = 0;
			foreach( $pluginbuddy_series[ pb_frolic::settings( 'series' ) ] as $slug => $data ) {
				$i++;
				echo '<li><a href="#pluginbuddy-tabs-' . $i . '"><span>' . $data['name'] . '</span></a></li>';
			}
			?>
		</ul>
		<div class="tabs-borderwrap" id="editbox" style="position: relative; height: 100%; -moz-border-radius-topleft: 0px; -webkit-border-top-left-radius: 0px;">
			<?php
			$i = 0;
			foreach( $pluginbuddy_series[ pb_frolic::settings( 'series' ) ] as $slug => $data ) {
				$i++;
				echo '<div id="pluginbuddy-tabs-' . $i . '">';
				
				if ( file_exists( $data['path'] . '/views/getting_started.php' ) ) {
					pb_frolic::load_view( 'getting_started' );
				} else {
					echo '{views/getting_started.php not found.}';
				}
				
				echo '</div>';
				
				plugin_information( $slug, $data );
			}
			?>
		</div>
	</div>
	<?php
} else { // STANDALONE
	pb_frolic::$ui->title( 'Getting Started with ' . pb_frolic::settings( 'name' ) . ' v' . pb_frolic::settings( 'version' ) );
	
	if ( file_exists( pb_frolic::plugin_path() . '/views/getting_started.php' ) ) {
		pb_frolic::load_view( 'getting_started' );
	} else {
		echo '{views/getting_started.php not found.}';
	}
	
	plugin_information( pb_frolic::settings( 'slug' ), array( 'name' => pb_frolic::settings( 'name' ), 'path' => pb_frolic::plugin_path() ) );
}



function plugin_information( $plugin_slug, $data ) {
	$plugin_path = $data['path'];
	?>
	
	<?php pb_frolic::$ui->start_metabox( 'Plugin Information' ); ?>
		<textarea rows="7" cols="65" style="width: 100%;"><?php echo "Version History:\n\n" ; readfile( $plugin_path . '/history.txt' ); ?></textarea>
		<br /><br />
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#pluginbuddy_carousel_debugtoggle").click(function() {
					jQuery("#pluginbuddy_carousel_debugtoggle_div").slideToggle();
				});
			});
		</script>
		<?php
		if ( pb_frolic::_POST( 'reset_defaults' ) == $plugin_slug ) {
			if ( call_user_func(  'pb_' . $data['slug'] . '::reset_options', true ) === true ) {
				pb_frolic::alert( 'Plugin settings have been reset to defaults for plugin `' . $data['name'] . '`.' );
			} else {
				pb_frolic::alert( 'Unable to reset plugin settings. Verify you are running the latest version.' );
			}
		}
		?>
		<form method="post" action="<?php echo pb_frolic::plugin_url(); ?>">
			<input type="hidden" name="reset_defaults" value="<?php echo $plugin_slug; ?>" />
			<input type="submit" name="submit" value="Reset Plugin Settings to Defaults" id="reset_defaults" class="button secondary-button" onclick="if ( !confirm('WARNING: This will reset all settings associated with this plugin to their defaults. Are you sure you want to do this?') ) { return false; }" />
		</form><br>
	<?php pb_frolic::$ui->end_metabox(); ?>
	
	<?php
}