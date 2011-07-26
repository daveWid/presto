<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Error controller.
 *
 * @package    Presto
 * @author     Dave Widmer
 * @copyright  2011 © Dave Widmer
 */
class Kohana_Controller_Error extends Controller_Site
{
	/** The error handler controller. */
	public function action_index()
	{
		$r = Request::$current;
		$type = $r->param('type');

		$this->title = "Error » ".$type;
		$this->content = View::factory('presto/error');
	}

}