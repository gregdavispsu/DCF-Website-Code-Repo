<?php
/**
 * Plugin Name: Disable New User Welcome Email Message
 * Plugin URI: http://38solutions.com
 * Description: Eliminates the plain text emails sent to users after they register to your site. This is helpful if you don't like how WordPress sends out the username and password in regular plain text.
 *              Special thanks to Stu Miller for original idea: http://www.stumiller.me/disable-wordpress-notifications-email-for-users-and-admins/
 * Version: 1.0.0
 * Author: Greg Davis - 38solutions
 * Author URI: http://38solutions.com
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: 
 * Network: false
 * License: GPL2
 */
 
 /*  Copyright 2015 Greg Davis - 38solutions  (email : greg@38solutions.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !function_exists('wp_new_user_notification') ) {
	
	function wp_new_user_notification( ) {}
	
}
?>