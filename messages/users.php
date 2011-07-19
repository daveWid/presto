<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'first_name' => array(
		'not_empty' => "Please enter the users first name.",
	),

	'last_name' => array(
		'not_empty' => "Please enter the users last name."
	),

	'email' => array(
		'not_empty' => "Please enter your email address.",
		'email' => "Please enter a valid email address.",
	),

	'password' => array(
		'not_empty' => "Please enter a password.",
	),

	'password2' => array(
		'matches' => "Your passwords do not match."
	),

	'current_password' => array(
		'not_empty' => "Please enter your current password",
		'incorrect' => "Your current password is incorrect. Please try again.",
	),

	'add' => array(
		'success' => "Your user has been created.",
		'error' => "There was an error creating the user. (Do they already exist?)",
	),

	'edit' => array(
		'success' => "The data has been saved.",
		'error' => "There was an error. (Did you change anything?)",
	),

	'change' => array(
		'password' => array(
			'success' => "The password has been changed.",
			'error' => "There was an error changing your password. (Did you change it?)",
		)
	),

	// Login form
	'login' => array(
		'email' => array(
			'not_empty' => "Please enter your email address.",
			'email' => "Please enter a valid email address.",
		),

		'password' => array(
			'not_empty' => "Please enter your password.",
		),

		'error' => array(
			'incorrect' => "Your email/password is not correct. Please check and try again",
			'login_first' => "You must login before viewing these resources.",
			'no_access' => "You don't have the proper privileges to access that resource.",
		),

		'success' => array(
			'logged_out' => "You have been logged out successfully!",
		),
	),

);