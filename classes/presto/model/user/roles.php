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
	public $table = "user_roles";
	public $primary = "user_id";
	protected $fields = array('user_id', 'role_id');

	/**
	 * Adds the role(s) for the given user.
	 *
	 * @param	int		User id
	 * @param	mixed	A role {string} or list of roles {array}
	 * @return	int		The number of roles added
	 */
	public function add($user, $roles)
	{
		if ( ! is_array($roles))
		{
			$roles = array($roles);
		}

		$num = 0;
		$data = array('user_id' => $user);
		foreach ($roles as $r)
		{
			$data['role_id'] = $r;
			list($id, $affected) = parent::create($data);
			$num += $affected;
		}

		return $num;
	}

	/**
	 * Removes the role(s) for the given user.
	 *
	 * @param	int		User id
	 * @param	mixed	A role {string} or list of roles {array}
	 * @return	int		The number of roles added
	 */
	public function remove($user, $roles)
	{
		if ( ! is_array($roles))
		{
			$roles = array($roles);
		}

		$num = 0;
		$data = array('user_id' => $user);
		foreach ($roles as $r)
		{
			$num += DB::delete($this->table)
						->where('user_id', '=', $user)
						->where('role_id', '=', $r)
						->limit(1)
						->execute();
		}

		return $num;
	}

	/**
	 * Gets all of the roles from the given user.
	 *
	 * @param	int	The users id to pull
	 * @return	array	The list of roles that the user has
	 */
	public function get_user($id)
	{
		$result = DB::select('role_id', 'name')
			->from($this->table)
			->join('roles')->using('role_id')
			->where('user_id', '=', $id)
			->as_object()
			->execute();

		$roles = array();
		foreach ($result as $row)
		{
			$roles[$row->role_id] = $row->name;
		}

		return $roles;
	}

	/**
	 * Deletes a role from the system with the given id.
	 *
	 * @param	int	Role id
	 * @return	int	Affected Rows
	 */
	public function delete_role($id)
	{
		return DB::delete($this->table)
			->where('role_id', '=', $id)
			->execute();
	}

	/**
	 * Sets the validation rules.
	 *
	 * @param	Validation	The validation instance
	 * @return	Validation	The validation instance with rules attached.
	 */
	protected function validation_rules(Validation $valid)
	{
		return $valid->rule('user_id', 'not_empty')
			->rule('role_id', 'not_empty');
	}

}
