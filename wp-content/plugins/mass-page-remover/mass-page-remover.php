<?php
/*
Plugin Name: Mass Page Remover
Plugin URI: http://www.wesg.ca/2008/07/wordpress-plugin-mass-page-remover/
Description: Easily remove multiple pages or posts.
Version: 1.7
Author: Wes Goodhoofd
Author URI: http://www.wesg.ca/

This program is free software; you can redistribute it and/or
modify it under the terms of version 2 of the GNU General Public
License as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details, available at
http://www.gnu.org/copyleft/gpl.html
or by writing to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

//plugin function
global $version;
$version = 1.7;

function remove_mass_page() {
global $version;
add_options_page('Mass Page Remover ' . $version, 'Mass Page Remover', '8', __FILE__, 'mass_page_remove_admin');
}

//function to actually add to the database
function remove_query($first, $last, $all) {
	global $wpdb;

	//check if the form is empty
	if (($first == '') && ($last == '') && ($all == '')) {
		//build fault tolerance if the form is empty
		return __('There was no data submitted.', 'mass_page_remover');
		exit;
	}

	//start timer for time feedback
	$mtime = microtime(); 
   	$mtime = explode(" ",$mtime); 
   	$mtime = $mtime[1] + $mtime[0]; 
   	$starttime = $mtime; 
   	
	// merge the two values
	if ($last == '')
		$last = $first;
		
	//find real pages within the range
	$main_pages = $wpdb->get_col("select ID from " . $wpdb->posts . " where (ID >= " . $first . " AND ID <= " . $last . ") AND post_type != 'revision' order by ID asc", 0);
	$page_revisions = $wpdb->get_col("select ID from " . $wpdb->posts . " where post_parent in (" . implode(",", $main_pages) . ")");
	$ids = array_merge($main_pages, $page_revisions);
	
	//add the individual pages to be removed
	if ($all != '') {
		$separate = explode(',', $all);
		$ids = array_merge($ids, $separate);
	}

// do the actual deleting
$success == 0;

foreach ($ids as $page) {
	if (wp_delete_post($page))
		$success++;
}

	//stop program timer
	$mtime = microtime(); 
   	$mtime = explode(" ",$mtime); 
   	$mtime = $mtime[1] + $mtime[0]; 
   	$endtime = $mtime; 
   	$totaltime = ($endtime - $starttime); 

	//return informative messages
		if ($sucess == count($pages))
		return sprintf(__('Successfully removed %d pages in %.4lf seconds', 'mass_page_remover'), $success, $totaltime);
	else
		return __('Error removing pages.', 'mass_page_remover');
}

//function to display the admin panel
function mass_page_remove_admin() {
	global $wpdb;
	global $version;
	//load translations
load_plugin_textdomain('mass_page_remover', "wp-content/plugins/mass-page-remover/");
	//look for the hidden post data to know that the form was submitted
	if (isset($_POST['checker'])) {
		$result = remove_query($_POST['first_page'], $_POST['last_page'], $_POST['separate_pages']);
		//print the result of the MySQL query in the pretty banner
		echo '<div id="message" class="updated fade"><p>' . $result . '</p></div>';
}
	//the rest is just form data for submission	
?>
<div class="wrap">
<div id="icon-tools" class="icon32"></div>

<h2><?php echo sprintf("%s %.1f", __('Mass Page Remover', 'mass_page_remover'), $version); ?></h2>
<p><?php _e('From this page, you can remove as many posts or pages as you like. Input a start and end post ID value, or write the page IDs directly, by separating them with commas. Use all fields for maximum efficiency.', 'mass_page_remover'); ?></p>

<div class="inside" id="poststuff">
<div id="grabit" class="gbx-group" style="float: right; margin: 0.4em;">
<div class="postbox">
<h3><?php _e('Plugin Information', 'mass_page_maker'); ?></h3>
<div class="inside">
<ul>
<li><a href="http://www.wesg.ca/" target="_blank">Wes G Homepage</a></li>
<li>Want immediate feedback/troubleshooting? <br />Contact me on <a href="http://twitter.com/wesgood">Twitter</a></li>
<li><a href="http://www.wesg.ca/2008/07/wordpress-plugin-mass-page-remover/" target="_blank">Plugin homepage</a></li>
<li><a href="http://www.wesg.ca/2008/07/wordpress-plugin-mass-page-remover/#changelog" target="_blank">Plugin changelog</a></li>
<li><a href="http://wordpress.org/extend/plugins/mass-page-remover/" target="_blank">Plugin in Wordpress directory</a></li>
<li><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4550478">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</li>
</ul>

</div>
</div>
</div>

<table width="50%">
<form name="mass_page" method="post" action="">
	<tr><td><?php _e('First page to remove', 'mass_page_remover'); ?></td><td><input type="text" name="first_page" size="4"></td></tr>
	<tr><td><?php _e('Last page to remove', 'mass_page_remover'); ?></td><td><input type="text" name="last_page" size="4"></td></tr>
	<tr><td><?php _e('Individual pages (separate by commas)', 'mass_page_remover'); ?></td><td><input type="text" name="separate_pages" size="42"></td></tr>
	
<input type="hidden" name="checker" value="OK">
<tr><td colspan="2"><p class="submit">
<input type="submit" name="Submit" value="<?php _e('Remove Pages') ?>" />
</p></td></tr>
</form>
</table>	
	
<?php
//this little doodad makes the lines alternate color
$inc = 0;
?>

<div class="tablenav">
	<div class="tablenav-pages">
	<?php
	// pagination

	$count = $wpdb->get_col('select id from ' . $wpdb->posts . " where post_status != 'inherit'");
	$records = count($count);
	$rows = 20;
	$pages = ceil($records / $rows);
	$page = $_GET['p'];
	
	if (isset($_GET['p'])) {
		$start = $rows * ($_GET['p']-1);
		$current = $_GET['p']; }
	else {
		$start = 0;
		$current = 1; }

	// listing end
	if ($start+$rows < $records)
		$ending = $start + $rows;
	else
		$ending = $records;
?>
<span class="displaying-num">Displaying <?php echo $start+1 . '&#8211;' . $ending . ' of ' . $records;?></span>
<?php
	for ($r = 1; $r <= $pages; $r++) {
		if (($r <= ($page + 2)) && ($r >= ($page - 2)) || ($r == $pages) || ($r == 1)) {
			if ($r == $current)
				echo ' <span class="page-numbers current">' . $r . '</span>';
			else
				echo ' <a href="' . get_mpr_URL('p', $r) . '" class="page-numbers">' . $r . '</a>';
		}
		else if ($r == ($page - 3) || ($r == ($page + 3)))
			echo ' ... '; 
	
	}

?>
</div>
</div>

<table class="widefat">
<thead>
	<th><?php _e('Post ID', 'mass_page_remover'); ?></th>
	<th><?php _e('Post Title', 'mass_page_remover'); ?></th>
	<th><?php _e('Post Date', 'mass_page_remover'); ?></th>	
</thead>
<tbody>
<?php 
//determine how to display the page list
if (isset($_GET['sort']))
	$sort = $_GET['sort'];
else
	$sort = 'ID';

if (isset($_GET['show']))
	$show = $_GET['show'];
else
	$show = 50;

if (($sort == 'post_date') || ($sort == 'ID'))
	$order = 'DESC';
else
	$order = 'ASC';

if (isset($_GET['order']))
	$order = $_GET['order'];

//get the pages from the database
$query = sprintf("SELECT ID, post_title, post_date, post_status FROM %s WHERE post_status != 'inherit' AND post_status != 'trash' ORDER BY %s %s LIMIT %d,%d",$wpdb->posts,$sort,$order,$start,$show);
$page_ID = $wpdb->get_results($query);

foreach ($page_ID as $print) {
if ($inc % 2 != 0)
			$color = '<tr>';
		else
			$color = '<tr bgcolor="#ebebeb">';
	$type = '';
if ($print->post_status == 'draft')
	$type = __(' (Draft)', 'mass_page_remover');
else if ($print->post_status == 'publish')
	$type = __(' (Published)', 'mass_page_remover');
else if ($print->post_status == 'future')
	$type = __(' (Future post)', 'mass_page_remover');


echo $color . "<td>" . $print->ID . "</td><td><a href='" . get_permalink($print->ID) . "'>" . $print->post_title . "</a>" . $type . "</td>";
if ($print->post_date != '0000-00-00 00:00:00')
	echo "<td>" . date("F d, Y g:i A", strtotime($print->post_date)) . "</td>";
else
	echo "<td></td>";
echo "</tr>";

$inc++;
}
?>
</tbody>
</table>

</div>
<?php
}

function remove_variable($URL, $var, $value) {
//this function needs to remove the variable at the end
//it is far from elegant, but it gets the job done
if (substr_count($URL, $var) > 0) {
	$URL .= '#listing';
	$equal = substr_count($URL, '=');
	$amp = substr_count($URL, '&');

	if ($amp == ($equal - 1))
		$result = $var . '=' . $value . '&';
	else
		$result = $var . '=' . $value . '&';

	$URL = preg_replace('/' . $var . '=([^&]+)(&|#)/', $result . '$2', $URL);
	$URL = str_replace('&#', '#', $URL);
	$URL = str_replace('#listing', '', $URL);	}
else
	$URL .= $var . '=' . $value;$URL = substr($URL, strpos('%', $URL));
return $URL;

}

function get_mpr_URL($var, $value) {
	$var = split('&', $var);
	$value = split('&', $value);
	$variables = $_GET;
	$i = 0;
	$url = $_SERVER['PHP_SELF'] . '?';
	foreach ($var as $element) {
		$variables[$element] = current($value);
		next($value);
		}
	foreach ($variables as $key=>$element) {
		if ($element != '') {
			$url .= $key . '=' . $element;
			if ($i < count($variables)-1)
				$url .= '&';
			$i++;
		}
	}

$url = preg_replace('/\&$/', '', $url);
return $url;
}

add_action('admin_menu', 'remove_mass_page');	//add the action so the blog knows it's there
?>