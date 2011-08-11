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
	public $table = "users";
	public $primary = "user_id";
	protected $fields = array('user_id', 'email', 'password', 'first_name', 'last_name');

	/**
	 * Takes a validation object and adds in the appropriate rules for the model.
	 *
	 * @param	Validation	A validation object
	 * @return	Validation	The validtion object with the rules added
	 */
	protected function validation_rules(Validation $valid)
	{
		return $valid->rule('first_name', 'not_empty')
			->rule('last_name', 'not_empty')
			->rule('email', 'not_empty')
			->rule('email', 'email')
			->rule('password', 'not_empty')
			->rule('password2', 'matches', array(':validation', 'password', 'password2'));
	}

	/**
	 * Validates the user when editing the information.
	 *
	 * @param	array	The data to validate against.
	 * @return	boolean	Validation success
	 */
	public function validate_edit(array $data)
	{
		// Setup a new validation instance if this one doesn't exist.
		if ($this->validation === null)
		{
			$this->validation = new Validation($data);
			$this->validation->rule('first_name', 'not_empty')
				->rule('last_name', 'not_empty')
				->rule('email', 'not_empty')
				->rule('email', 'email');
		}

		$this->validation_passed = $this->validation->check();
		return $this->validation_passed;
	}

	/**
	 * Validates the login form.
	 *
	 * @param	array	The data to validate against.
	 * @return	boolean	Validation success
	 */
	public function validate_login(array $data)
	{
		// Setup a new validation instance if this one doesn't exist.
		if ($this->validation === null)
		{
			$this->validation = new Validation($data);
			$this->validation->rule('email', 'not_empty')
				->rule('email', 'email')
				->rule('password', 'not_empty');
		}

		$this->validation_passed = $this->validation->check();
		return $this->validation_passed;
	}

	/**
	 * Validates the change password form.
	 *
	 * @param	array	The data to validate against.
	 * @param	boolean	Do we need to check the current password?
	 * @return	boolean	Validation success
	 */
	public function validate_passwords(array $data, $current = null)
	{
		// Setup a new validation instance if this one doesn't exist.
		if ($this->validation === null)
		{
			$this->validation = new Validation($data);

			if ($current)
			{
				$this->validation->rule('current_password', 'not_empty');
			}

			$this->validation->rule('password', 'not_empty')
				->rule('password2', 'matches', array(':validation', 'password', 'password2'));
		}

		$this->validation_passed = $this->validation->check();
		return $this->validation_passed;
	}

	/**
	 * The specialized select statement because of all of the joins needed
	 * with the users.
	 *
	 * @return	Database_Select
	 */
	private function get_select()
	{
		return DB::select('user_id','email','first_name','last_name', DB::expr("GROUP_CONCAT(`roles`.`name`) AS `roles`"))
			->from($this->table)
			->join('user_roles', 'left')->using('user_id')
			->join('roles', 'left')->using('role_id')
			->group_by('user_id')
			->as_object();
	}

	/**
	 * Inserts a new row.
	 * Data is automatically filtered and checked for validation.
	 *
	 * Additionally, the password is automatically hashed.
	 *
	 * @param	array	The data to insert
	 * @return	array	Insert ID and Affected Rows
	 * @return	boolean	FALSE if the data didn't validate
	 */
	public function create(array $data)
	{
		$data['password'] = Auth::instance()->hash($data['password']);
		return parent::create($data);
	}

	/**
	 * Make sure we leave out the password when reading the data.
	 *
	 * @param	int	User id
	 * @return	object	The database row
	 */
	public function read($key)
	{
		$user = parent::read($key);

		if ($user !== false)
		{
			unset($user->password);
		}

		return $user;
	}

	/**
	 * Deletes a user from the database
	 *
	 * @param	int	User id
	 * @return	int	Affected rows
	 */
	public function delete($key)
	{
		DB::delete('user_roles')->where('user_id', '=', $key)->execute();
		return parent::delete($key);
	}

	/**
	 * Attempts to login the user.
	 *
	 * @param	string	Username (email)
	 * @param	string	Password
	 * @return	Database_Result	The user result
	 */
	public function login($user, $pass)
	{
		return $this->get_select()
			->where('email', '=', $user)
			->where('password', '=', $pass)
			->limit(1)
			->execute();
	}

	/**
	 * Changes the password for the given user.
	 *
	 * @param	int		The user id of the password to change
	 * @param	array	Password data
	 * @return	int		Affected rows
	 * @return	boolean	False if the current password is incorrect
	 */
	public function change_password($id, $data)
	{
		$user = $this->read($id);

		// Check to make sure the current password is right...
		$current = Arr::get($data, 'current_password', null);
		if ($current !== null)
		{
			if ( ! $this->check_password($id, $current))
			{
				return false; // error...
			}
		}

		$data['password'] = Auth::instance()->hash($data['password']);
		return $this->update($id, $data, false);
	}

	/**
	 * Checks to see if the password is right.
	 *
	 * @param	int	User ID
	 * @param	string	The current password
	 * @return	boolean	The result of the check
	 */
	public function check_password($id, $pass)
	{
		$result = DB::select()
				->from($this->table)
				->where('user_id', '=', $id)
				->where('password', '=', Auth::instance()->hash($pass))
				->limit(1)
				->execute();

		return count($result) === 1;
	}

}
