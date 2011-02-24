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
	public $primary = "UserID";

	/**
	 * Attempts to login a user.
	 *
	 * @param	string	$email	Email address
	 * @param	string	$pass	Password
	 * @return	mixed			The user object or false
	 */
	public function login($email, $pass)
	{
		$user = DB::select('UserID','Email',DB::expr('GROUP_CONCAT(`Roles`.`Name`) AS `Roles`'))
			->from($this->table)
			->where('Email', '=', $email)
			->where('Password', '=', $pass)
			->join('UserRoles')->using('UserID')
			->join('Roles')->using('RoleID')
			->group_by('UserID')
			->as_object()
			->execute();

		if (count($user) == 1)
		{
			$current = $user->current();
			$current->Roles = explode(",", $current->Roles);
			return $current;
		}
		else
		{
			return false;
		}
	}

}
