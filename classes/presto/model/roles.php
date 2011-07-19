<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for the roles table
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Model_Roles extends Model_Crud
{
	public $table = "roles";
	public $primary = "role_id";
	protected $fields = array('role_id', 'name', 'description');

	/**
	 * Does the normal row deleting but then also cascades across the user_roles
	 * table a well.
	 *
	 * @param	int	UserID
	 * @return	int	Affected rows
	 */
	public function delete($key)
	{
		$num = parent::delete($key);

		// If a role was deleted, cascade into the user roles
		if ($num > 0)
		{
			Model::factory('user_roles')->delete_role($key);
		}

		return $num;
	}

	/**
	 * Sets the validation rules for the roles table
	 *
	 * @param	Validation	The validation instance
	 * @return	Validation	The validation instance with rules attached.
	 */
	protected function validation_rules(Validation $valid)
	{
		return $valid->rule('name', 'not_empty')
			->rule('description', 'not_empty');
	}

}
