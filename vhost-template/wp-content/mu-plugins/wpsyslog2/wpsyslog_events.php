<?php
/*
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
	The list of hooks is derived from http://codex.wordpress.org/Plugin_API/Action_Reference
*/

global $wpsyslogDoTrigger;
/* $wpsyslogDoTrigger = get_option('wpsyslog_dotrigger'); */

$wpsyslogHaveTriggered = array(
	'add_attachment' => false,
	'delete_attachment' => false,
	'edit_attachment' => false,
	'create_category' => false,
	'delete_category' => false,
	'edit_category' => false,
	'delete_post' => false,
	'save_post' => false,
	'private_to_published' => false,
	'publish_page' => false,
	'publish_phone' => false,
	'publish_post' => false,
	'xmlrpc_publish_post' => false,
	'comment_id_not_found' => false,
	'comment_flood_trigger' => false,
	'comment_post' => false,
	'edit_comment' => false,
	'delete_comment' => false,
	'pingback_post' => false,
	'trackback_post' => false,
	'wp_set_comment_status' => false,
	'add_link' => false,
	'delete_link' => false,
	'edit_link' => false,
	'do_robots' => false,
	'switch_theme' => false,
	'delete_user' => false,
	'retrieve_password' => false,
	'register_post' => false,
	'user_register' => false,
	'personal_options_update' => false,
	'profile_update' => false,
	'wp_login' => false,
	'wp_login_failed' => false,
	'wp_logout' => false,
	'login_form_resetpass' => false,
	'generate_rewrite_rules' => false,
	'plugins' => false
);

add_action('add_attachment', 'wpsyslog_add_attachment', 50); // Runs when an attached file is first added to the database. Action function arguments: attachment ID.
add_action('delete_attachment', 'wpsyslog_delete_attachment', 50); // Runs just after an attached file is deleted from the database. Action function arguments: attachment ID.
add_action('edit_attachment', 'wpsyslog_edit_attachment', 50); // Runs when an attached file is edited/updated to the database. Action function arguments: attachment ID.
add_action('create_category', 'wpsyslog_create_category', 50); // Runs when a new category is created. Action function arguments: category ID.
add_action('delete_category', 'wpsyslog_delete_category', 50); // Runs just after a category is deleted from the database and its corresponding links/posts are updated to remove the category. Action function arguments: category ID.
add_action('edit_category', 'wpsyslog_edit_category', 50); // Runs when a category is updated/edited, including when a post or blogroll link is added/deleted or its categories are updated (which causes the count for the category to update). Action function arguments: category ID.
add_action('delete_post', 'wpsyslog_delete_post', 50); // Runs when a post or page is about to be deleted. Action function arguments: post or page ID.
add_action('save_post', 'wpsyslog_save_post', 50); // Runs when a post or page is updated in the database for any reason (initial save, add comments, change categories, edit, etc.). Action function arguments: post or page ID.
add_action('private_to_published', 'wpsyslog_private_to_published', 50); // Runs when a post is changed from private to published status. Action function arguments: post ID.
add_action('publish_page', 'wpsyslog_publish_page', 50); // Runs when a page is published, or if it is edited and its status is 'published'. Action function arguments: page ID.
add_action('publish_phone', 'wpsyslog_publish_phone', 50); // Runs just after a post is added via email. Action function argument: post ID.
add_action('publish_post', 'wpsyslog_publish_post', 50); // Runs when a post is published, or if it is edited and its status is 'published'. Action function arguments: post ID.
add_action('xmlrpc_publish_post', 'wpsyslog_xmlrpc_publish_post', 50); // Runs when a post is published via XMLRPC request, or if it is edited via XMLRPC and its status is 'published'. Action function arguments: post ID.
add_action('comment_id_not_found', 'wpsyslog_comment_id_not_found', 50); // Runs when the post ID is not found while trying to display comments or comment entry form. Action function argument: post ID.
add_action('comment_flood_trigger', 'wpsyslog_comment_flood_trigger', 50); // Runs when a comment flood is detected, just before wp_die is called to stop the comment from being accepted. Action function arguments: time of previous comment, time of current comment.
add_action('comment_post', 'wpsyslog_comment_post', 50); // Runs just after a comment is saved in the database. Action function arguments: comment ID, approval status ('spam', or 0/1 for disapproved/approved).
add_action('edit_comment', 'wpsyslog_edit_comment', 50); // Runs after a comment is updated/edited in the database. Action function arguments: comment ID.
add_action('delete_comment', 'wpsyslog_delete_comment', 50); // Runs just before a comment is deleted. Action function arguments: comment ID.
add_action('pingback_post', 'wpsyslog_pingback_post', 50); // Runs when a ping is added to a post. Action function argument: comment ID.
add_action('trackback_post', 'wpsyslog_trackback_post', 50); // Runs when a trackback is added to a post. Action function argument: comment ID.
add_action('wp_set_comment_status', 'wpsyslog_wp_set_comment_status', 50); // Runs when the status of a comment changes. Action function arguments: comment ID, status string indicating the new status ('delete', 'approve', 'spam', 'hold').
add_action('add_link', 'wpsyslog_add_link', 50); // Runs when a new blogroll link is first added to the database. Action function arguments: link ID.
add_action('delete_link', 'wpsyslog_delete_link', 50); // Runs when a blogroll link is deleted. Action function arguments: link ID.
add_action('edit_link', 'wpsyslog_edit_link', 50); // Runs when a blogroll link is edited. Action function arguments: link ID.
add_action('do_robots', 'wpsyslog_do_robots', 50); // Runs when the template file chooser determines that it is a robots.txt request.
add_action('switch_theme', 'wpsyslog_switch_theme', 50); // Runs when the blog's theme is changed. Action function argument: name of the new theme.
add_action('delete_user', 'wpsyslog_delete_user', 50); // Runs when a user is deleted. Action function arguments: user ID.
add_action('retrieve_password', 'wpsyslog_retrieve_password', 50); // Runs when a user's password is retrieved, to send them a reminder email. Action function argument: login name.
add_action('register_post', 'wpsyslog_register_post', 50); // Runs before a new user registration request is processed.
add_action('user_register', 'wpsyslog_user_register', 50); // Runs when a user's profile is first created. Action function argument: user ID.
add_action('personal_options_update', 'wpsyslog_personal_options_update', 50); // Runs when a user updates personal options from the admin screen.
add_action('profile_update', 'wpsyslog_profile_update', 50); // Runs when a user's profile is updated. Action function argument: user ID.
add_action('wp_login', 'wpsyslog_wp_login', 50); // Runs when a user logs in.
add_action('wp_login_failed', 'wpsyslog_wp_login_failed', 50); // Runs when a user fails to logs in.
add_action('wp_logout', 'wpsyslog_wp_logout', 50); // Runs when a user logs out.
add_action('login_form_resetpass', 'wpsyslog_reset_password', 50); // Runs when a user logs out.
add_action('generate_rewrite_rules', 'wpsyslog_generate_rewrite_rules', 50); // Runs after the rewrite rules are generated. Action function arguments: the WP_Rewrite class variables as a list.
/*
add_action('deactivate_wpsyslog2/wpsyslog.php', 'wpsyslog_self_deactivated', 50); // If WPsyslog is deactivated
add_action('activate_wpsyslog2/wpsyslog.php', 'wpsyslog_self_activated', 50); // If WPsyslog is activated
*/
function wpsyslog_add_attachment($arg='null', $arg2='null') { // Runs when an attached file is first added to the database. Action function arguments: attachment ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['add_attachment']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Attachment added. Id: #%1$s, name: %2$.', 'wpsyslog'), $arg, $name ), 2);
		$wpsyslogHaveTriggered['add_attachment'] = true;
	}
}

function wpsyslog_delete_attachment($arg='null', $arg2='null') { // Runs just after an attached file is deleted from the database. Action function arguments: attachment ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['delete_attachment']) {
		wpsyslog('core', sprintf( __('Attachment deleted. Id: #%s.', 'wpsyslog'), $arg ), 2);
		$wpsyslogHaveTriggered['delete_attachment'] = true;
	}
}

function wpsyslog_edit_attachment($arg='null', $arg2='null') { // Runs when an attached file is edited/updated to the database. Action function arguments: attachment ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['edit_attachment']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Attachment updated. Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 2);
		$wpsyslogHaveTriggered['edit_attachment'] = true;
	}
}

function wpsyslog_create_category($arg='null', $arg2='null') { // Runs when a new category is created. Action function arguments: category ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['create_category']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$name = get_cat_name($arg);
		}
		wpsyslog('core', sprintf( __('Category created. Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 2);
		$wpsyslogHaveTriggered['create_category'] = true;
	}
}

function wpsyslog_delete_category($arg='null', $arg2='null') { // Runs just after a category is deleted from the database and its corresponding links/posts are updated to remove the category. Action function arguments: category ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['delete_category']) {
		wpsyslog('core', sprintf( __('Category deleted. Id: #%s.', 'wpsyslog'), $arg ), 2);
		$wpsyslogHaveTriggered['delete_category'] = true;
	}
}

function wpsyslog_edit_category($arg='null', $arg2='null') { // Runs when a category is updated/edited, including when a post or blogroll link is added/deleted or its categories are updated (which causes the count for the category to update). Action function arguments: category ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['edit_category']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$name = get_cat_name($arg);
		}
		wpsyslog('core', sprintf( __('Category updated. Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 1);
		$wpsyslogHaveTriggered['edit_category'] = true;
	}
}

function wpsyslog_delete_post($arg='null', $arg2='null') { // Runs when a post or page is about to be deleted. Action function arguments: post or page ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['delete_post']) {
		wpsyslog('core', sprintf( __('Post deleted. Id: #%s.', 'wpsyslog'), $arg ), 2);
		$wpsyslogHaveTriggered['delete_post'] = true;
	}
}

function wpsyslog_save_post($arg='null', $arg2='null') { // Runs when a post or page is updated in the database for any reason (initial save, add comments, change categories, edit, etc.). Action function arguments: post or page ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['save_post']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Post saved. Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 1);
		$wpsyslogHaveTriggered['save_post'] = true;
	}
}

function wpsyslog_private_to_published($arg='null', $arg2='null') { // Runs when a post is changed from private to published status. Action function arguments: post ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['private_to_published']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Post state changed from private to published. Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 1);
		$wpsyslogHaveTriggered['private_to_published'] = true;
	}
}

function wpsyslog_publish_post($arg='null', $arg2='null') { // Runs when a post is published, or if it is edited and its status is 'published'. Action function arguments: post ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['publish_post']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Post published (or edited). Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 2);
		$wpsyslogHaveTriggered['publish_post'] = true;
	}
}

function wpsyslog_publish_page($arg='null', $arg2='null') { // Runs when a page is published, or if it is edited and its status is 'published'. Action function arguments: page ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['publish_page']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Page published (or edited). Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 2);
		$wpsyslogHaveTriggered['publish_page'] = true;
	}
}

function wpsyslog_publish_phone($arg='null', $arg2='null') { // Runs just after a post is added via email. Action function argument: post ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['publish_phone']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Page added remotely (via email). Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 2);
		$wpsyslogHaveTriggered['publish_phone'] = true;
	}
}

function wpsyslog_xmlrpc_publish_post($arg='null', $arg2='null') { // Runs when a post is published via XMLRPC request, or if it is edited via XMLRPC and its status is 'published'. Action function arguments: post ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['xmlrpc_publish_post']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$postdata = get_post($arg);
			$name = $postdata->post_title;
		}
		wpsyslog('core', sprintf( __('Page published via XMLRPC. Id: #%1$s, name: %2$s.', 'wpsyslog'), $arg, $name ), 2);
		$wpsyslogHaveTriggered['xmlrpc_publish_post'] = true;
	}
}

function wpsyslog_comment_id_not_found($arg='null', $arg2='null') { // Runs when the post ID is not found while trying to display comments or comment entry form. Action function argument: post ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['comment_id_not_found']) {
		wpsyslog('core', __('Trying to display the comment form of a non-existent post.', 'wpsyslog'), 3);
		$wpsyslogHaveTriggered['comment_id_not_found'] = true;
	}
}

function wpsyslog_comment_flood_trigger($arg='null', $arg2='null') { // Runs when a comment flood is detected, just before wp_die is called to stop the comment from being accepted. Action function arguments: time of previous comment, time of current comment.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['comment_flood_trigger']) {
		wpsyslog('core', __('Comment flood attempt.', 'wpsyslog'), 3);
		$wpsyslogHaveTriggered['comment_flood_trigger'] = true;
	}
}

function wpsyslog_comment_post($arg='null', $arg2='null') { // Runs just after a comment is saved in the database. Action function arguments: comment ID, approval status ('spam', or 0/1 for disapproved/approved).
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['comment_post']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$commentdata = get_comment($arg);
			$name = $commentdata->comment_author;
			$postdata = get_post($commentdata->comment_post_ID);
			$posttitle = $postdata->post_title;

			if (1 == $commentdata->comment_approved)
				$commentstatus = __('approved', 'wpsyslog');
			elseif (0 == $commentdata->comment_approved)
				$commentstatus = __('not approved', 'wpsyslog');
			elseif ('spam' == $commentdata->comment_approved)
				$commentstatus = __('spam', 'wpsyslog');
			else
				$commentstatus = 'undefined';
		}
		wpsyslog('core', sprintf( __('Comment posted. Comment Id: #%1$s, name: %2$s. Post Id: #%3$s, name: %4$s. Comment status: %5$s.', 'wpsyslog'), $arg, $name, $commentdata->comment_post_ID, $posttitle, $commentstatus ), 1);
		$wpsyslogHaveTriggered['comment_post'] = true;
	}
}

function wpsyslog_edit_comment($arg='null', $arg2='null') { // Runs after a comment is updated/edited in the database. Action function arguments: comment ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['edit_comment']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$commentdata = get_comment($arg);
			$name = $commentdata->comment_author;
			$postdata = get_post($commentdata->comment_post_ID);
			$posttitle = $postdata->post_title;

			if (1 == $commentdata->comment_approved)
				$commentstatus = __('approved', 'wpsyslog');
			elseif (0 == $commentdata->comment_approved)
				$commentstatus = __('not approved', 'wpsyslog');
			elseif ('spam' == $commentdata->comment_approved)
				$commentstatus = __('spam', 'wpsyslog');
			else
				$commentstatus = 'undefined';
		}
		wpsyslog('core', sprintf( __('Comment updated. Comment Id: #%1$s, name: %2$s. Post Id: #%3$s, name: %4$s. Comment status is %5$s.', 'wpsyslog'), $arg, $name, $commentdata->comment_post_ID, $posttitle, $commentstatus ), 1);
		$wpsyslogHaveTriggered['edit_comment'] = true;
	}
}

function wpsyslog_delete_comment($arg='null', $arg2='null') { // Runs just before a comment is deleted. Action function arguments: comment ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['delete_comment']) {
		wpsyslog('core', sprintf( __('Comment #%s has been deleted.', 'wpsyslog'), $arg ), 1);
		$wpsyslogHaveTriggered['delete_comment'] = true;
	}
}

function wpsyslog_pingback_post($arg='null', $arg2='null') { // Runs when a ping is added to a post. Action function argument: comment ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['pingback_post']) {
		$url = $name = $arg;
		if ( is_numeric($arg) ) {
			$commentdata = get_comment($arg);
			$url = $commentdata->comment_author_url;
			$postdata = get_post($commentdata->comment_post_ID);
			$posttitle = $postdata->post_title;

			if (1 == $commentdata->comment_approved)
				$commentstatus = __('approved', 'wpsyslog');
			elseif (0 == $commentdata->comment_approved)
				$commentstatus = __('not approved', 'wpsyslog');
			elseif ('spam' == $commentdata->comment_approved)
				$commentstatus = __('spam', 'wpsyslog');
			else
				$commentstatus = 'undefined';
		}
		wpsyslog('core', sprintf( __('Comment via pingback posted. From <a href="%1$s">%1$s</a> to post Id: #%2$s, name: %3$s. Saved as comment #%4$s. Comment status is %5$s.', 'wpsyslog'), $url, $commentdata->comment_post_ID, $posttitle, $arg, $commentstatus ), 1);
		$wpsyslogHaveTriggered['pingback_post'] = true;
	}
}

function wpsyslog_trackback_post($arg='null', $arg2='null') { // Runs when a trackback is added to a post. Action function argument: comment ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['trackback_post']) {
		$url = $name = $arg;
		if ( is_numeric($arg) ) {
			$commentdata = get_comment($arg);
			$url = $commentdata->comment_author_url;
			$postdata = get_post($commentdata->comment_post_ID);
			$posttitle = $postdata->post_title;

			if (1 == $commentdata->comment_approved)
				$commentstatus = __('approved', 'wpsyslog');
			elseif (0 == $commentdata->comment_approved)
				$commentstatus = __('not approved', 'wpsyslog');
			elseif ('spam' == $commentdata->comment_approved)
				$commentstatus = __('spam', 'wpsyslog');
			else
				$commentstatus = 'undefined';
		}
		wpsyslog('core', sprintf( __('Comment via trackback posted. From <a href="%1$s">%1$s</a> to post Id: #%2$s, name: %3$s. Saved as comment #%4$s. Comment status is %5$s.', 'wpsyslog'), $url, $commentdata->comment_post_ID, $posttitle, $arg, $commentstatus ), 1);
		$wpsyslogHaveTriggered['trackback_post'] = true;
	}
}

function wpsyslog_wp_set_comment_status($arg='null', $arg2='null') { // Runs when the status of a comment changes. Action function arguments: comment ID, status string indicating the new status ("delete", "approve", "spam', 'hold').
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['wp_set_comment_status']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$commentdata = get_comment($arg);
			$name = $commentdata->comment_author;
			$postdata = get_post($commentdata->comment_post_ID);
			$posttitle = $postdata->post_title;

			if (1 == $commentdata->comment_approved)
				$commentstatus = __('approved', 'wpsyslog');
			elseif (0 == $commentdata->comment_approved)
				$commentstatus = __('not approved', 'wpsyslog');
			elseif ('spam' == $commentdata->comment_approved)
				$commentstatus = __('spam', 'wpsyslog');
			else
				$commentstatus = 'undefined';
		}
		wpsyslog('core', sprintf( __('Comment status changed. Comment Id: #%1$s by %2$s on post Id: #%3$s, name: %4$s. New status: %5$s.', 'wpsyslog'), $arg, $name, $commentdata->comment_post_ID, $posttitle, $commentstatus ), 1);
		$wpsyslogHaveTriggered['wp_set_comment_status'] = true;
	}
}

function wpsyslog_add_link($arg='null', $arg2='null') { // Runs when a new blogroll link is first added to the database. Action function arguments: link ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['add_link']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$linkdata = get_bookmark($arg);
			$name = $linkdata->link_name;
			$url = $linkdata->link_url;
		}
		wpsyslog('core', sprintf( __('Blogroll link added. Id: #%1$s, name: <a href="%2$s">%3$s</a>.', 'wpsyslog'), $arg, $url, $name ), 1);
		$wpsyslogHaveTriggered['add_link'] = true;
	}
}

function wpsyslog_delete_link($arg='null', $arg2='null') { // Runs when a blogroll link is deleted. Action function arguments: link ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['delete_link']) {
		wpsyslog('core', sprintf( __('Blogroll link deleted. Id: #%s.', 'wpsyslog'), $arg ), 1);
		$wpsyslogHaveTriggered['delete_link'] = true;
	}
}

function wpsyslog_edit_link($arg='null', $arg2='null') { // Runs when a blogroll link is edited. Action function arguments: link ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['edit_link']) {
		$name = $arg;
		if ( is_numeric($arg) ) {
			$linkdata = get_bookmark($arg);
			$name = $linkdata->link_name;
			$url = $linkdata->link_url;
		}
		wpsyslog('core', sprintf( __('Blogroll link updated. Id: #%1$s, name: <a href="%2$s">%3$s</a>.', 'wpsyslog'), $arg, $url, $name ), 1);
		$wpsyslogHaveTriggered['edit_link'] = true;
	}
}

function wpsyslog_do_robots($arg='null', $arg2='null') { // Runs when the template file chooser determines that it is a robots.txt request.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger, $wpdb;
	if ('true' === $wpsyslogDoTrigger['do_robots']) {
		$useragent = strip_tags($_SERVER['HTTP_USER_AGENT']);
		$useragent = $wpdb->escape($_SERVER['HTTP_USER_AGENT']);
		wpsyslog('core', sprintf( __('File robots.txt retrieved. UserAgent: %s.', 'wpsyslog'), $useragent ), 1);
		$wpsyslogHaveTriggered['do_robots'] = true;
	}
}

function wpsyslog_switch_theme($arg='null', $arg2='null') { // Runs when the blog's theme is changed. Action function argument: name of the new theme.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['switch_theme']) {
		wpsyslog('core', sprintf( __('Theme switched to %s.', 'wpsyslog'), $arg ), 3);
		$wpsyslogHaveTriggered['switch_theme'] = true;
	}
}

function wpsyslog_delete_user($arg='null', $arg2='null') { // Runs when a user is deleted. Action function arguments: user ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['delete_user']) {
		wpsyslog('core', sprintf( __('User deleted. Id: #%s.', 'wpsyslog'), $arg ), 2);
		$wpsyslogHaveTriggered['delete_user'] = true;
	}
}

function wpsyslog_retrieve_password($arg='null', $arg2='null') { // Runs when a user's password is retrieved, to send them a reminder email. Action function argument: login name.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['retrieve_password']) {
		$user = get_profile('display_name', $arg);
		wpsyslog('core', sprintf( __('Password created and sent to user %1$s (%2$s).', 'wpsyslog'), $user, $arg ), 2);
		$wpsyslogHaveTriggered['retrieve_password'] = true;
	}
}

function wpsyslog_register_post($arg='null', $arg2='null') { // Runs before a new user registration request is processed.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['register_post']) {
		wpsyslog('core', __('New user attempt to register.', 'wpsyslog'), 1);
		$wpsyslogHaveTriggered['register_post'] = true;
	}
}

function wpsyslog_user_register($arg='null', $arg2='null') { // Runs when a user's profile is first created. Action function argument: user ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['user_register']) {
		$user = get_userdata($arg);
		wpsyslog('core', sprintf( __('New user successfully registered. Name: %1$s (%2$s).', 'wpsyslog'), $user->display_name, $user->user_login ), 3);
		$wpsyslogHaveTriggered['user_register'] = true;
	}
}

function wpsyslog_personal_options_update($arg='null', $arg2='null') { // Runs when a user updates personal options from the admin screen.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['personal_options_update']) {
		wpsyslog('core', sprintf( __('User personal options changed. User name: %s.', 'wpsyslog'), $arg ), 1);
		$wpsyslogHaveTriggered['personal_options_update'] = true;
	}
}

function wpsyslog_profile_update($arg='null', $arg2='null') { // Runs when a user's profile is updated. Action function argument: user ID.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['profile_update']) {
		$user = get_userdata($arg);
		wpsyslog('core', sprintf( __('User profile changed. User name: %s.', 'wpsyslog'), $user->display_name ), 1);
		$wpsyslogHaveTriggered['profile_update'] = true;
	}
}

function wpsyslog_wp_login($arg='null', $arg2='null') { // Runs when a user logs in.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['wp_login']) {
		$user = get_profile('display_name', $arg);
		$userid = get_profile('id', $arg);

		global $wpsyslog_options;
		if ( 1 != $userid || 'true' != $wpsyslog_options['adminstealth'] )
			wpsyslog('core', sprintf( __('User logged in. User name: %1$s (%2$s).', 'wpsyslog'), $user, $arg ), 2, 500, $userid);
		$wpsyslogHaveTriggered['wp_login'] = true;
	}
}

function wpsyslog_wp_login_failed($arg='null', $arg2='null') { // Runs when a user fails to log in.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['wp_login_failed']) {
		wpsyslog('core', sprintf( __('User authentication failed. User name: %s.', 'wpsyslog'), $arg ), 2);
		$wpsyslogHaveTriggered['wp_login_failed'] = true;
	}
}

function wpsyslog_wp_logout($arg='null', $arg2='null') { // Runs when a user logs out.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['wp_logout']) {
		$user = wp_get_current_user();
		wpsyslog('core', sprintf( __('User logged out. User name: %1$s (%2$s).', 'wpsyslog'), $user->display_name, $user->user_login ), 2);
		$wpsyslogHaveTriggered['wp_logout'] = true;
	}
}

function wpsyslog_reset_password($arg='null', $arg2='null') { // Runs when a user logs out.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;

    if ( isset($_GET['key']) )
    {
        /* Detecting wordpress 2.8.3 vulnerability - $key is array */
        if(is_array($_GET['key']))
        {
            wpsyslog('core', sprintf( __('IDS: Attempt to reset password by attacking wp2.8.3 bug.', 'wpsyslog')  ), 3);
        }
    }
	$wpsyslogHaveTriggered['wp_reset_password'] = true;
}

function wpsyslog_generate_rewrite_rules($arg='null', $arg2='null') { // Runs after the rewrite rules are generated. Action function arguments: the WP_Rewrite class variables as a list.
	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	if ('true' === $wpsyslogDoTrigger['generate_rewrite_rules'] ) {
		if ( empty($_POST) )
			wpsyslog('core', __('The rewrite rules have been newly calculated and saved.', 'wpsyslog'), 3); // Permalink page was opened, hence rewrite rules by plugins were inserted
		else
			wpsyslog('core', __('The permalink options and rewrite rules have been modified and saved.', 'wpsyslog'), 3); // Permalink options are saved
		$wpsyslogHaveTriggered['generate_rewrite_rules'] = true;
	}
}

function wpsyslog_events_without_actions() {

/**
	At the moment, this function suveils plugin activation/deactivation, but anything else could be put here to.
	Events can e.g. determined by $_GET and $_POST parameters, but other are possible, too.
*/

	global $wpsyslogHaveTriggered, $wpsyslogDoTrigger;
	
	/* Start Plugins */
	if ('true' === $wpsyslogDoTrigger['plugins']) {
		if ( // activation request
			'activate' == $_GET['action'] &&
			!empty($_GET['plugin']) &&
			strpos($_SERVER['REQUEST_URI'], 'plugins.php') !== false &&
			current_user_can('activate_plugins')
		) {
			$plugin = $_GET['plugin'];
			$plugin = strip_tags($plugin);
			$plugin = mysql_real_escape_string($plugin);
			wpsyslog('core', sprintf( __('Plugin activated. Plugin name: %s.', 'wpsyslog'), $plugin ), 3);
		}

		if ( // error during activation
			'true' == $_GET['error'] &&
			strpos($_SERVER['REQUEST_URI'], 'plugins.php') !== false &&
			current_user_can('activate_plugins')
		) {
			wpsyslog('core', __('Plugin has triggered an error during activation and has been automatically deactivated.', 'wpsyslog'), 4);
		}

		if ( // activation request
			'deactivate' == $_GET['action'] &&
			!empty($_GET['plugin']) &&
			strpos($_SERVER['REQUEST_URI'], 'plugins.php') !== false &&
			current_user_can('activate_plugins')
		) {
			$plugin = $_GET['plugin'];
			$plugin = strip_tags($plugin);
			$plugin = mysql_real_escape_string($plugin);
			wpsyslog('core', sprintf( __('Plugin deactivated. Plugin name: %s', 'wpsyslog'), $plugin ), 3);
		}
		$wpsyslogHaveTriggered['plugins'] = true;
	}
	/* End Plugins */
}

/*function wpsyslog_self_activated($arg='null', $arg2='null') {
	if ( 'true' == get_option('wpsyslog_init_success') )
		wpsyslog('wpsyslog', __('WPsyslog plugin has been activated.'), 3);
} */

/*function wpsyslog_self_deactivated($arg='null', $arg2='null') {
	wpsyslog('wpsyslog', __('WPsyslog plugin has been deactivated.'), 3);
}*/
