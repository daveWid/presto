<?php defined('SYSPATH') or die('No direct script access.');

// Media Loader routes
Route::set('presto/media', 'media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'presto_media',
		'action'     => 'index',
		'file'       => NULL,
	));