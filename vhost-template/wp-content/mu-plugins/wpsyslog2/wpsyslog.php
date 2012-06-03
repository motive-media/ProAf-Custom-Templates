<?php
/*
Plugin Name: WPsyslog2
Plugin URI: http://www.ossec.net/wpsyslog2/
Description: WPsyslog2 is a global log plugin for Wordpress. It keeps track of all system events and log them to syslog. It tracks events such as new posts, new profiles, new users, failed logins, logins, logouts, etc. It also tracks the latest vulnerabilities and alerts if any of them are triggered, becoming very useful when integrated with a log analysis tool, like OSSEC HIDS. 
Author: Alex Guensche - Daniel B. Cid
Version: 0.2
Author URI: http://www.ossec.net/wpsyslog2/
*/


/* 
    

*/

/*
    Integrated from WPsyslog.

	WPsyslog -- Global logging facility for WordPress
	Copyright (C) 2007 Alex Guensche <http://www.zirona.com>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation in the Version 2.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/


/*
	This plugin was developed during a project with the German "Menschen für
	Tierrechte" (Humans for Animal Rights). This is an organisation of
	activists who advocate animal rights and fight against abuse of animals.

	They generously decided to donate this work to the general public by letting
	Zirona put it under the GNU GPL and allowing us to promote and distribute it.
	(In turn, we have extended it with a couple of additional features.)

	Please support their work, e.g. by placing a link to www.tierrechte.de and
	spreading the word. Not because of this plugin, but generally for a better
	treatment of the beings on this planet.
*/


// no support for multi-language plugins, log messages must be in one language
load_plugin_textdomain('wpsyslog', 'wp-content/plugins/wpsyslog2/lang');

function wpsyslogInit() {
/*
all the initialisation stuff
	- load languages
	- create user roles on first run (todo)
	- create db table on first run
	- define core events to trigger on first run
	- other functions
*/
/*	$wpsyslogStatus = get_option('wpsyslog_init_success');
	if ( empty($wpsyslogStatus) ) { */
		global $wpsyslog_options;

		$wpsyslog_options = array(
			'activate' => 'true',
			'coreevents' => 'true',
			'adminstealth' => 'false',
			'onsubpages' => 'false',
			'timeformat' => 'Y-m-d H:i:s',
			'tableheight' => '400px'
		);
/*		update_option('wpsyslog_options', $wpsyslog_options); */
		global $wpsyslogDoTrigger;

		$wpsyslogDoTrigger = array(
			'add_attachment' => 'false',
			'delete_attachment' => 'false',
			'edit_attachment' => 'false',
			'create_category' => 'true',
			'delete_category' => 'true',
			'edit_category' => 'true',
			'delete_post' => 'true',
			'save_post' => 'true',
			'private_to_published' => 'false',
			'publish_page' => 'true',
			'publish_phone' => 'true',
			'publish_post' => 'true',
			'xmlrpc_publish_post' => 'true',
			'comment_id_not_found' => 'true',
			'comment_flood_trigger' => 'true',
			'comment_post' => 'true',
			'edit_comment' => 'false',
			'delete_comment' => 'false',
			'pingback_post' => 'false',
			'trackback_post' => 'false',
			'wp_set_comment_status' => 'false',
			'add_link' => 'true',
			'delete_link' => 'true',
			'edit_link' => 'true',
			'do_robots' => 'false',
			'switch_theme' => 'true',
			'delete_user' => 'true',
			'retrieve_password' => 'true',
			'register_post' => 'false',
			'user_register' => 'true',
			'personal_options_update' => 'false',
			'profile_update' => 'false',
			'wp_login' => 'true',
			'wp_login_failed' => 'true',
			'wp_logout' => 'true',
			'generate_rewrite_rules' => 'false',
			'plugins' => 'false'
		);
/*		update_option('wpsyslog_dotrigger', $wpsyslogDoTrigger); */

/*		global $wpdb;
		$query = "DROP TABLE IF EXISTS `{$wpdb->prefix}wpsyslog`";
		$wpdb->query($query);

		$query =
		"CREATE TABLE `{$wpdb->prefix}wpsyslog` (
			`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`severity` ENUM( '0', '1', '2', '3', '4', '5' ) NOT NULL DEFAULT '1',
			`user` VARCHAR(15) CHARACTER SET ASCII COLLATE ascii_general_ci NOT NULL,
			`module` VARCHAR(30) CHARACTER SET ASCII COLLATE ascii_general_ci NOT NULL,
			`message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
		
		$createtable = $wpdb->query($query);
		if ($createtable === false) {
			update_option('wpsyslog_init_success', 'false');
			echo '<pre>'.mysql_error().'</pre><br />Error creating tables! Aborting.';
			return false;
		} else {
			update_option('wpsyslog_init_success', 'true');
			wpsyslog('wpsyslog', __('WPsyslog was successfully initialised: Database table created, default options added, user roles added.', 'wpsyslog'), 2);
		} */
	/* } */
	if ( function_exists('wpsyslog_events_without_actions') )
		wpsyslog_events_without_actions();
}

/* function wpsyslogAdminMenu() {
	global $wp_version;
	$wpsyslog_options = get_option('wpsyslog_options');
	if ( ( !empty($wp_version) && version_compare($wp_version, '2.2', '<') ) || 'true' != $wpsyslog_options['onsubpages'] ) {
			if ( current_user_can('level_10') || current_user_can('read_wpsyslog') )
			add_menu_page('WPsyslog2', 'WPsyslog2', 0, 'wpsyslog2/wpsyslog_admin_data.php');

		if ( current_user_can('level_10') || ( current_user_can('read_wpsyslog') && current_user_can('manage_wpsyslog') ) )
			add_submenu_page('wpsyslog2/wpsyslog_admin_data.php', __('WPsyslog2', 'wpsyslog2'), __('Config', 'wpsyslog2'), 0, 'wpsyslog2/wpsyslog_admin_config.php');
	} else {
		if ( current_user_can('level_10') || current_user_can('read_wpsyslog') )
			add_submenu_page('edit.php', __('WPsyslog2', 'wpsyslog2'), __('WPsyslog2', 'wpsyslog2'), 0, 'wpsyslog2/wpsyslog_admin_data.php');

		if ( current_user_can('level_10') || ( current_user_can('read_wpsyslog') && current_user_can('manage_wpsyslog') ) )
			add_submenu_page('options-general.php', __('Config', 'wpsyslog2'), __('WPsyslog2', 'wpsyslog2'), 0, 'wpsyslog2/wpsyslog_admin_config.php');
	}
} */

function wpsyslog($module, $message, $severity=1, $cut=500, $userid=0, $time=0 ) {

	global $wpsyslog_options;
	/* $wpsyslog_options = get_option('wpsyslog_options'); */
	global $wpdb;

	$module = substr($module, 0, 30);
	$module = $wpdb->escape($module);
	
	$message = ( is_numeric($cut) && 0 < $cut )
		 ? substr($message, 0, $cut)
		 : substr($message, 0, 500);
	$message = $wpdb->escape($message);
    $message = htmlspecialchars($message);
	
    $user_name = NULL;
	if ( !$userid || !is_integer($userid) ) {
		$user = wp_get_current_user();
		if ( !empty($user->ID) ) {
			$userid = $user->ID;
            $user_name = $user->user_login;
		} else {
			$userid = preg_replace( '|[^0-9\.]|', '', preg_quote($_SERVER['REMOTE_ADDR'], '|') );
		}
	}
    else
    {
        $user_info = get_userdata($userid);
        $user_name = $user_info->user_login;
    }

	$time = (int)$time;
	if (1181677869 > $time || 2147483647 < $time)
		$time = date('U');

	$time_mysql = date('Y-m-d H:i:s', $time);
	
	$severity = (int)$severity;
	if (0 > $severity || 5 < $severity)
		$severity = 1;


    /* Setting remote ip. */
    $remote_ip = NULL;
    if(isset($_SERVER['REMOTE_ADDR']) && strlen($_SERVER['REMOTE_ADDR']) > 4)
    {
        $remote_ip = $_SERVER['REMOTE_ADDR'];
    }
    else
    {
        $remote_ip = "Local";
    }


    /* Setting header block */
    $block_header = NULL;
    if($user_name !== NULL)
    {
        $block_header = '['.$remote_ip.' '.$user_name.']';
    }
    else
    {
        $block_header = '['.$remote_ip.' na]';
    }

    /* Making sure we escape everything. */
    $block_header = htmlspecialchars($block_header);


    /* Getting severity. */
    $severityname = "Info";
    if($severity == 0)
    {
        $severityname = "Debug";
    }
    else if($severity == 1)
    {
        $severityname = "Notice";
    }
    else if($severity == 2)
    {
        $severityname = "Info";
    }
    else if($severity == 3)
    {
        $severityname = "Warning";
    }
    else if($severity == 4)
    {
        $severityname = "Error";
    }
    else if($severity == 5)
    {
        $severityname = "Critical";
    }


    /* First log via syslog. */
    openlog("WPsyslog", LOG_PID, LOG_DAEMON);
    syslog(LOG_WARNING, "$block_header $severityname: $message");
    closelog();

/*
	$query = "
	INSERT INTO `{$wpdb->prefix}wpsyslog` (
		`time`,
		`severity`,
		`user`,
		`module`,
		`message`
	)
	VALUES (
		'$time_mysql',
		'$severity',
		'$userid',
		'$module',
		'$message'
	)";


	$result = $wpdb->query($query);

	if ( false === $result) {
		echo mysql_error();
		return false;
	} */
	return true;
}

/* function wpsyslogAdminHeader() {
	$wpsyslog_options = get_option('wpsyslog_options');
	?>
	<link rel="stylesheet" href="<?php bloginfo('siteurl') ?>/wp-content/plugins/wpsyslog2/wpsyslog.css" type="text/css" media="screen" />
	<style type="text/css">#wpsyslog_data { height: <?php echo $wpsyslog_options['tableheight'] ?>; }</style>
	<?php
} */

/* function wpsyslogCaps($caps) {
	$caps[] = 'manage_wpsyslog';
	$caps[] = 'read_wpsyslog';
	return $caps;
} */

/* add_filter('capabilities_list', 'wpsyslogCaps'); */
/* add_action('admin_head', 'wpsyslogAdminHeader');
add_action('admin_menu', 'wpsyslogAdminMenu'); */
add_action('init', 'wpsyslogInit');

/* $wpsyslog_options = get_option('wpsyslog_options'); */
/* if ( 'true' == $wpsyslog_options['coreevents']) */
	require_once(ABSPATH.'/wp-content/mu-plugins/wpsyslog2/wpsyslog_events.php');
?>
