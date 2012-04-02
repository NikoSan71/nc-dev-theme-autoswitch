<?php
/*
Plugin Name: NC - Dev Theme Autoswitch
Plugin URI: 
Description: Show a specific theme for one or more specified users.
Author: Nicolas Cambas
Author URI: http://blog.nicolascambas.com/
Version: 0.1
*/

/*	Options
================================================== */
$dtas_options = array(
									'user2switch' => array(1),		// ID of users who switch to dev theme
									'devTheme' => 'responsive',		// name of the WIP theme
									'show_message' => true				// show (or not) a little square on top left with 'beta' mention
								);



/*	Switch theme according to the connected user
================================================== */
add_filter('option_template', 'dtas_dev_theme');
add_filter('option_stylesheet', 'dtas_dev_theme');
add_filter('template', 'dtas_dev_theme');

function dtas_dev_theme($template='') {
	global $current_user, $dtas_options;
	get_currentuserinfo();
		
	if ( in_array( $current_user->ID, $dtas_options['user2switch'] ) ) {
		$template = $dtas_options['devTheme'];
		
		// show top message (or not)
		if($dtas_options['show_message']) {
			add_action('wp_footer', 'dtas_showDevMessage');
			add_action('wp_head', 'dtas_addstyle');
		}
		
	}
	
	return $template;
}



/*	Add a message on top of site when
**	WIP theme is active
================================================== */
function dtas_showDevMessage() {
	echo '<div class="dtas-message">BETA!</div>';
}

/*	Add a style css file
================================================== */
function dtas_addstyle() {
	echo '<link rel="stylesheet" type="text/css" href="'.dtas_pluginUrl().'dtas.css" />'; 
}


/*	Return the active plugin URL
================================================== */
function dtas_pluginUrl() {

	//Try to use WP API if possible, introduced in WP 2.6
	if (function_exists('plugins_url')) return trailingslashit(plugins_url(basename(dirname(__FILE__))));
	
	//Try to find manually... can't work if wp-content was renamed or is redirected
	$path = dirname(__FILE__);
	$path = str_replace("\\","/",$path);
	$path = trailingslashit(get_bloginfo('wpurl')) . trailingslashit(substr($path,strpos($path,"wp-content/")));
	return $path;
}

?>