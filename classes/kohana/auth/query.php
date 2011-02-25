<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Auth Driver that uses the Query Builder.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Kohana_Auth_Query extends Kohana_Auth
{
	/**
	 * Does the user login.
	 *
	 * @param	string	$username	The username
	 * @param	string	$password	The password (already hashed)
	 * @param	boolean	$remember	Should the login be remembered? (autologin)
	 * @return	boolean			Successful?
	 */
	protected function _login($username, $password, $remember)
	{
		$user = Model::factory('users')->login($username, $password);

		return ($user !== false) ?
			$this->complete_login($user) :
			false ;
	}

	/**
	 * Gets the password that is stored for the given username.
	 *
	 * @param	string	$username	The user get the password for.
	 * @throws	Kohana_Exception
	 */
	public function password($username)
	{
		throw new Kohana_Exception(
			"The password method is not available with the query builder driver."
		);
	}

	/**
	 * Checks to see if the passed in password matches the logged-in users password.
	 *
	 * @param	string	$password	The password to check
	 * @throws	Kohana_Exception
	 */
	public function check_password($password)
	{
		throw new Kohana_Exception(
			"The check_password method is not available	with the query builder auth driver."
		);
	}

	/**
	 * Check if there is an active session. Optionally allows checking for a
	 * specific role.
	 *
	 * @param	mixed	$role	The role to check for (or array of roles...)
	 * @return	boolean		If the user is logged in and has the specified role (if given)
	 */
	public function logged_in($role = NULL)
	{
		$user = $this->get_user();

		if ($user === null)
		{
			return false;
		}

		if ($role === null)
		{
			return true;
		}
		else
		{
			if(is_string($role))
			{
				return in_array($role, $user->roles); // just one role
			}
			else
			{
				$good = true;
				foreach($role as $r) // Loop through a list of roles
				{
					if ( ! in_array($r, $user->roles))
					{
						$good = false;
						break;
					}
				}

				return $good;
			}
		}
	}

}
