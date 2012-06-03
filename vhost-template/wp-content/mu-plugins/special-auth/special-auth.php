<?php 


remove_filter('authenticate', 'wp_authenticate_username_password');
add_filter('authenticate', 'mojo_authenticate_username_password', 20, 3);

function mojo_authenticate_username_password($user, $username, $password) {
	if ( is_a($user, 'WP_User') ) { return $user; }

	if ( empty($username) || empty($password) ) {
		$error = new WP_Error();

		if ( empty($username) )
			$error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));

		if ( empty($password) )
			$error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

		return $error;
	}

	$userdata_2 = mojo_get_user_by('login', $username);
	$userdata = get_user_by('login', $username);

	if ( !$userdata )
		return new WP_Error('invalid_username', sprintf(__('<strong>ERROR</strong>: Invalid username. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), wp_lostpassword_url()));

	if ( !$userdata_2 )
		return new WP_Error('invalid_username', sprintf(__('<strong>ERROR</strong>: Invalid username. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), wp_lostpassword_url()));

	if ( is_multisite() ) {
		// Is user marked as spam?
		if ( 1 == $userdata->spam)
			return new WP_Error('invalid_username', __('<strong>ERROR</strong>: Your account has been marked as a spammer.'));

		// Is a user's blog marked as spam?
		if ( !is_super_admin( $userdata->ID ) && isset($userdata->primary_blog) ) {
			$details = get_blog_details( $userdata->primary_blog );
			if ( is_object( $details ) && $details->spam == 1 )
				return new WP_Error('blog_suspended', __('Site Suspended.'));
		}
	}

	$userdata = apply_filters('wp_authenticate_user', $userdata, $password);
	if ( is_wp_error($userdata) )
		return $userdata;

	if ( !wp_check_password($password, $userdata_2->user_pass, $userdata->ID) )
		return new WP_Error( 'incorrect_password', sprintf( __( '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s" title="Password Lost and Found">Lost your password</a>?' ),
		$username, wp_lostpassword_url() ) );

	$user =  new WP_User($userdata->ID);
	return $user;
}

function mojo_get_user_by($field, $value)
{
	global $wpdb;

	$value = trim( $value );

	if ( !$value )
		return false;

	switch ($field) {
		case 'login':
			$value = sanitize_user( $value );
			$db_field = 'user_login';
			break;
		default:
			return false;
	}

	if ( !$userdata = $wpdb->get_row( $wpdb->prepare(
		"SELECT * FROM $wpdb->users WHERE 'mojo_authenticate'='mojo_authenticate' and $db_field = %s", $value
	) ) )
		return false;

	$user = new WP_User;
	$user->init( $userdata );

	return $user;
}

