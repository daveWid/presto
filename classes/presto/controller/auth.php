<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The Presto auth controller.
 * 
 * This controller will keep out the riff-raff from protected resources.
 *
 * @package    Presto
 * @author     Dave Widmer
 * @copyright  2011 © Dave Widmer
 */
class Presto_Controller_Auth extends Controller_Site
{
	/**
	 * @var Presto_User   The logged in user.
	 */
	protected $user;

	/**
	 * @var string  The redirect path for unauthorized users
	 */
	public $redirect = 'login';

	/**
	 * Make sure there is a logged in user
	 */
	public function before()
	{
		$this->user = Auth::instance()->get_user();

		if ( ! $this->is_allowed())
		{
			$this->request->redirect($this->redirect);
		}

		return parent::before();
	}

	/**
	 * Checks to see if a user is allowed to be here
	 *
	 * @return boolean  Is the user allowed?
	 */
	protected function is_allowed()
	{
		return ($this->user === null) ? false : true;
	}

}
