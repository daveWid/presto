<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'driver'       => 'query_builder',
	'hash_method'  => 'sha256',
	'hash_key'     => NULL,
	'lifetime'     => 1209600,
	'session_key'  => 'auth_user'
);
