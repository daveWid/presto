<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for the User Roles table
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Model_User_Roles extends Model_Crud
{
	/**
	 * @var	String	The Database table name
	 */
	public $table = "UserRoles";

	/**
	 * @var String	The primary key.
	 */
	public $primary = "user_id";

	/**
	 * Removes a role from the given user.
	 *
	 * @param	int	$user	User id
	 * @param	int	$role	Role id
	 * @return	int		Affected rows
	 */
	public function remove($user, $role)
	{
		return (int) DB::delete($this->table)
			->where('user_id', '=', $user)
			->where('role_id', '=', $role)
			->execute();
	}
}
