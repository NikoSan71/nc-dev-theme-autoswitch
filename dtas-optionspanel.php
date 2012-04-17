<?php

/*	Include JS and CSS
================================================== */
	function dtas_addjscss() {
		echo '<link rel="stylesheet" type="text/css" href="'.dtas_pluginUrl().'style/dtas.css" />'; 
	}
	add_action('admin_head', 'dtas_addjscss');

	wp_deregister_script(array('jquery'));
	wp_enqueue_script('jquery', dtas_pluginUrl().'js/jquery-1.7.2.min.js');
	wp_enqueue_script('jquery.multi-select', dtas_pluginUrl().'js/jquery.multi-select.js');
	wp_enqueue_script('jquery.quicksearch', dtas_pluginUrl().'js/jquery.quicksearch.js');
	wp_enqueue_script('dtas-scripts', dtas_pluginUrl().'js/dtas-scripts.js', array('jquery', "jquery.quicksearch", "jquery.multi-select"), '1.0.0', true);


/* Add option menu (under settings)
================================================== */
	function dtas_theme_autoswitch_menu() {
		add_submenu_page('options-general.php', 'theme_autoswitch', 'Theme Autoswitch', 'manage_options', 'dtas_theme_autoswitch', 'dtas_theme_autoswitch' );
	}
	add_action('admin_menu', 'dtas_theme_autoswitch_menu');
	

/*	register settings
================================================== */
	function dtas_register_settings() {
		register_setting( 'dtas-settings-group', 'dtas_active' );
		register_setting( 'dtas-settings-group', 'dtas_show_msg' );
		register_setting( 'dtas-settings-group', 'dtas_msg_text' );
		register_setting( 'dtas-settings-group', 'dtas_themes' );
		
		register_setting( 'dtas-settings-group', 'dtas_users_id' );
	}
	add_action( 'admin_init', 'dtas_register_settings' );

/* Options panel content
================================================== */
	function dtas_theme_autoswitch() {
		global $wpdb;
	
		//must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.', 'dtas') );
    }
				
	?>
	
		<div class="wrap dtas">
		
			<div id="icon-options-general" class="icon32"><br /></div>
			<h2>Dev. Theme Autoswitch</h2>
			
			<form id="dtas-form" method="post" action="options.php">
			
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				
    		<?php settings_fields('dtas-settings-group'); ?>
    		
				<?php
					$checkedYes = "";
					$checkedNo = "checked";
					if (get_option('dtas_active') == "yes") { $checkedNo = ""; $checkedYes="checked";  }
				?>
				<div class="dtas-activate">
					<p><?php _e('Activate the plugin?', 'dtas'); ?></p>
					<label for="dtas_activeY"><input id="dtas_activeY" type="radio" name="dtas_active" value="yes" <?php echo $checkedYes; ?> /> <?php _e('Yes', 'dtas'); ?></label>
					<label for="dtas_activeN"><input id="dtas_activeN" type="radio" name="dtas_active" value="no" <?php echo $checkedNo; ?> /> <?php _e('No', 'dtas'); ?></label> 
				</div> <!-- .dtas-activate -->
				
				<div class="dtas-theme">
					<p><?php _e('Development Theme', 'dtas'); ?></p>
					<?php dtas_themes_list( get_option('dtas_themes') ); ?>
					<p class="options-help">
						<?php _e('That theme will be used when connected with the user account selected below.', 'dtas'); ?>
					</p>
				</div> <!-- .dtas-theme -->
				
				
				<div class="dtas-users">
					<p><?php _e('Choose users', 'dtas'); ?></p>
					<?php dtas_users_list(); ?>
					<p class="options-help">
						<?php _e('Clic the left column to choose one or more users who will see the development theme.', 'dtas'); ?>
					</p>
				</div> <!-- .dtas-users -->
				
				<?php
					$checkedYes = "";
					$checkedNo = "checked";
					if (get_option('dtas_show_msg') == "yes") { $checkedNo = ""; $checkedYes="checked";  }
				?>
				<div class="dtas-admin-message">
					<p><?php _e('Show message on front?', 'dtas'); ?></p>
					<label for="dtas_show_msgY"><input id="dtas_show_msgY" type="radio" name="dtas_show_msg" value="yes" <?php echo $checkedYes; ?> /> <?php _e('Yes', 'dtas'); ?></label>
					<label for="dtas_show_msgN"><input id="dtas_show_msgN" type="radio" name="dtas_show_msg" value="no" <?php echo $checkedNo; ?> /> <?php _e('No', 'dtas'); ?></label> 
					<br /><br />
					<p><?php _e('Message text', 'dtas'); ?></p>
					<input type="text" name="dtas_msg_text" value="<?php echo get_option('dtas_msg_text'); ?>" />
					
					<p class="options-help">
						<?php _e('Show a little message (or not) on the top left of the front site.', 'dtas'); ?>
					</p>
				</div> <!-- .dtas-admin-message -->
				
    	</form>
    
		</div> <!-- .wrap -->
	
	<?php
	
	}	// dtas_theme_autoswitch
	

/* Echo the theme list (without actual theme)
================================================== */
	function dtas_themes_list($actualTheme){
		global $post;
	?>
		<select name="dtas_themes" id="dtas_themes">
		<?php
			
			$dtas_themes = get_themes();
			$dtas_current_themes = get_current_theme();
			
			foreach ($dtas_themes as $key => $value) {
				if($dtas_current_themes != $value['Name']) {
  				$selected = '';
  				if( $actualTheme == $value['Template'] ) {
  					$selected = 'selected=selected';
  				}
  				
					echo '<option value="'. $value['Template'] .'"'. $selected .'>'. $value['Name'] .'</option>';
	    	}
	    }
	    
		?>
		</select>		

	<?php
	}	// dtas_themes_list


/* Echo users list
================================================== */
	function dtas_users_list(){
		global $post;
	?>
		<select name="dtas_users_id[]" multiple="multiple" id="dtas_users_id">
		<?php
		
				$dtas_users_selected = get_option('dtas_users_id');
		
				// prepare arguments
				$dtas_users_args  = array(
				'role' => '',
				'orderby' => 'display_name'
				);
				
				$dtas_users_query = new WP_User_Query($dtas_users_args);

				$dtas_users_results = $dtas_users_query->get_results();
				// Check for results
				if (!empty($dtas_users_results)) 
				{				
			    foreach ($dtas_users_results as $dtas_user)
			    {
			        $author_info = get_userdata($dtas_user->ID);
      				$selected = '';
      				if( in_array($dtas_user->ID, $dtas_users_selected) ) {
      					$selected = 'selected=selected';
      				}
      				
							echo '<option value="'. $dtas_user->ID .'"'. $selected .'>'.$author_info->display_name.'</option>';
			    }
			    
				} else {
				    echo 'No authors found';
				}
				
		?>
		</select>		

	<?php
	}	// dtas_users_list


?>