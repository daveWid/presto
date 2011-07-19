<?php defined('SYSPATH') or die('No direct script access.');

/** Error route */
Route::set('error', "error(/<type>)")
	->defaults(array(
		'controller' => "error",
		'action' => "index",
		'type' => "404"
	 ));
