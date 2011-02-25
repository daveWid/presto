<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for the users table
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Model_Users extends Model_Crud
{
	/**
	 * @var	String	The Database table name
	 */
	public $table = "Users";

	/**
	 * @var String	The primary key.
	 */
	public $primary = "user_id";

	/**
	 * Gets the record given the primary key.
	 *
	 * @param	mixed	The primary key (int or string anymore)
	 * @return			User object or false
	 */
	public function read($key)
	{
		$user = DB::select('*',DB::expr('GROUP_CONCAT(`Roles`.`Name`) AS `roles`'))
				->from($this->table)
				->join('UserRoles')->using('user_id')
				->join('Roles')->using('role_id')
				->group_by('user_id')
				->where($this->primary, '=', $key)
				->as_object()
				->execute();

		return $this->get_current($user);
	}

	/**
	 * Attempts to login a user.
	 *
	 * @param	string	$email	Email address
	 * @param	string	$pass	Password
	 * @return	The user object or false
	 */
	public function login($email, $pass)
	{
		$user = DB::select('user_id','email',DB::expr('GROUP_CONCAT(`Roles`.`Name`) AS `roles`'))
			->from($this->table)
			->where('email', '=', $email)
			->where('password', '=', $pass)
			->join('UserRoles')->using('user_id')
			->join('Roles')->using('role_id')
			->group_by('user_id')
			->as_object()
			->execute();

		return $this->get_current($user);
	}

	/**
	 * Gets the current user or false if no user was found.
	 *
	 * @param	Database_Result	The user from the database
	 * @return	User object or false
	 */
	protected function get_current($user)
	{
		if (count($user) == 1)
		{
			$current = $user->current();
			$current->roles = explode(",", $current->roles);
			$current->user_id = (int) $current->user_id;
			return $current;
		}
		else
		{
			return false;
		}
	}

}
