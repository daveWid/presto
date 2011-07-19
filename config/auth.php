<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'driver'       => 'presto',
	'iterations'  => 15,
	'salt'			=> array(2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31),
	'lifetime'     => 1209600,
	'session_key'  => 'auth_user'
);
