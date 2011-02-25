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
	 * Checks to see if a user is allowed access to a resource with an
	 * access control list as an array with the array keys being the name
	 * of the action to control. A missing key will result in no check and
	 * with an array of roles, a user must have all roles to be granted access.
	 *
	 * @param	array	$acl		The access control list
	 * @param	string	$action		The action to test against, if none given
	 *								Request::$current->action() is used
	 * @return	boolean			Does the user have access?
	 */
	public static function acl(array $acl, $action = null)
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
		$roles = self::normalize_roles($acl[$action]);
		return self::check_roles($roles, $user->roles);
	}

	/**
	 * Checks to make sure the user has a given role.
	 * This is useful when you don't want to create a full acl and just want
	 * a specific role to pass along (e.g. admin panel)
	 *
	 * @param	mixed	$role	The role (or array of roles) to check for
	 */
	public static function role($role)
	{
		// User not logged in
		$user = Auth::instance()->get_user(false);
		if ($user === false)
		{
			return false;
		}

		// Logged in, check for the role
		$roles = self::normalize_roles($role);
		return self::check_roles($roles, $user->roles);
	}

	/**
	 * Normalize the roles into an array
	 *
	 * @param	mixed	$roles	The roles to check.
	 * @return	array		The roles in array format
	 * @throws	Kohana_Exception
	 */
	protected static function normalize_roles($roles)
	{
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

		return $roles;
	}

	/**
	 * Checks the roles to check against the users roles.
	 *
	 * @param	array	$check	The roles to check for
	 * @param	array	$user	The roles the user has
	 * @return	boolean		Passed the check?
	 */
	protected static function check_roles($check, $user)
	{
		$access = true;
		foreach ($check as $role)
		{
			if ( ! in_array($role, $user))
			{
				$access = false;
				break;
			}
		}

		return $access;
	}

}
