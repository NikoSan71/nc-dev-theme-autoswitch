<?php
/*
Plugin Name: NC - Dev Theme Autoswitch
Plugin URI: 
Description: Show a specific theme for one or more specified users.
Author: Nicolas Cambas
Author URI: http://blog.nicolascambas.com/
Version: 0.1
*/

add_filter('option_template', 'dev_theme');
add_filter('option_stylesheet', 'dev_theme');
add_filter('template', 'dev_theme');

function dev_theme($template='') {
	global $current_user;
	get_currentuserinfo();
	
	// users ID
	$user2switch = array(1,2);
	
	// names of current theme and developpement theme
	$devTheme = 'devTheme';
	$actualTheme = 'actualTheme';
	
	if ( in_array( $current_user->ID, $user2switch ) ) {
		$template = $devTheme;
	} else {
		$template = $actualTheme;
	}
	
	return $template;
}

?>