<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The Presto administration controller.
 * 
 * This controller makes sure that the user is an administrator before letting them in
 *
 * @package    Presto
 * @author     Dave Widmer
 * @copyright  2011 © Dave Widmer
 */
class Presto_Controller_Admin extends Controller_Auth
{
	public $redirect = 'dashboard';

	/**
	 * Makes sure that the user is allowed to access the page
	 *
	 * @return boolean	Is the user allowed?
	 */
	protected function is_allowed()
	{
		return $this->user->is_admin();
	}

}
