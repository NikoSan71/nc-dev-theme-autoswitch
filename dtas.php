<?php
/*
Plugin Name: NC - Dev Theme Autoswitch
Plugin URI: https://github.com/NikoSan71/nc-dev-theme-autoswitch
Description: Show a specific theme for one or more specified users.
Author: Nicolas Cambas
Author URI: http://blog.nicolascambas.com/
Version: 0.5
*/

/*	Options
================================================== */
	$dtas_options = array(
										'active' => get_option('dtas_active'),					// plugin active or not	
										'user2switch' => get_option('dtas_users_id'),		// ID of users who switch to dev theme
										'devTheme' => get_option('dtas_themes'),				// name of the development theme
										'show_message' => get_option('dtas_show_msg'),	// show (or not) a little square on top left with 'beta' mention
										'msg_text' => get_option('dtas_msg_text')				// show (or not) a little square on top left with 'beta' mention
									);
	
	// internationalization
	function dtas_text_domain() {
		load_plugin_textdomain( 'dtas', false, basename( dirname( __FILE__ ) ) . '/lang' );
	}
	add_action( 'init', 'dtas_text_domain' );

	// admin panel
	require_once (WP_PLUGIN_DIR . '/nc-dev-theme-autoswitch/dtas-optionspanel.php');



/*	Switch theme according to the connected user
================================================== */
add_filter('option_template', 'dtas_dev_theme');
add_filter('option_stylesheet', 'dtas_dev_theme');
add_filter('template', 'dtas_dev_theme');

function dtas_dev_theme($template='') {
	global $current_user, $dtas_options;
	get_currentuserinfo();
		
	if ( ($dtas_options['user2switch']) && (in_array( $current_user->ID, $dtas_options['user2switch'] )) && ($dtas_options['active'] == 'yes') ) {
		$template = $dtas_options['devTheme'];
		
		// show top message (or not)
		if($dtas_options['show_message'] == 'yes') {
			add_action('wp_footer', 'dtas_showDevMessage');
			add_action('wp_head', 'dtas_addstyle');
		}
		
	}
	
	return $template;
}


/*	Add a message on top of site when
**	development theme is active
================================================== */
function dtas_showDevMessage() {
	global $dtas_options;
	echo '<div class="dtas-message">'.$dtas_options['msg_text'].'</div>';
}

/*	Add a style css file
================================================== */
function dtas_addstyle() {
	echo '<link rel="stylesheet" type="text/css" href="'.dtas_pluginUrl().'style/dtas.css" />'; 
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