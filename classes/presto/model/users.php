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
	 * Attempts to login a user.
	 *
	 * @param	string	$email	Email address
	 * @param	string	$pass	Password
	 * @return	mixed			The user object or false
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

		if (count($user) == 1)
		{
			$current = $user->current();
			$current->roles = explode(",", $current->roles);
			return $current;
		}
		else
		{
			return false;
		}
	}

}
