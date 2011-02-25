<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The bouncer class is used to keep out the riff-raff.
 * This class relies on the Auth class along with a ACL type array.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Bouncer
{
	/** The admin role for full access, or null for no admin role. */
	public static $admin_role = 'admin';

	/**
	 * Checks to see if the user is allowed.
	 *
	 * @param	array	$acl		The access control list
	 * @param	string	$action		The action to test against, if none given
	 *								Request::$current->action() is used
	 * @return	boolean			Does the user have access?
	 * @throws	Kohana_Exception
	 */
	public static function check(array $acl, $action = null)
	{
		$action = ($action !== null) ? $action : Request::$current->action();

		// Check to see if the action should be checked
		if ( ! array_key_exists($action, $acl))
		{
			return true;
		}

		// User not logged in and acl rule exists...
		$user = Auth::instance()->get_user(false);
		if ($user === false)
		{
			return false;
		}

		// Now check for admin user (they are VIP so let 'em in!)
		if (in_array(self::$admin_role, $user->roles))
		{
			return true;
		}

		// User logged in and not an admin
		// So lets check for access...
		$roles = $acl[$action];

		// Make sure the roles are a string or array
		if ( ! is_string($roles) && ! is_array($roles))
		{
			throw new Kohana_Exception("ACL roles must be a string or array of strings");
		}

		// Normalize into array
		if (is_string($roles))
		{
			$roles = array($roles);
		}

		// Iterate through the roles and make sure they are all in there...
		$access = true;
		foreach ($roles as $role)
		{
			if ( ! in_array($role, $user->roles))
			{
				$access = false;
				break;
			}
		}

		// If you made it through you are golden
		return $access;
	}

}
