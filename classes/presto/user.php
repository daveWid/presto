<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The user who is currently logged into the site.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_User
{
	/** User parameters. */
	protected $params = array();

	/**
	 * Creates a new Presto_User instance.
	 *
	 *		$user = new Presto_User($params);
	 *
	 * @param	array	Params
	 */
	public function __construct(array $params)
	{
		$this->params = $params;

		$roles = $this->get('roles');
        if ( ! is_array($roles))
        {
            $this->set('roles', explode(",", $roles));
        }
	}

	/**
	 * Checks to see if the user is an admin.
	 *
	 *		if ($user->is_admin())
	 *		{
	 *			// Do admin stuff here...
	 *		}
	 * 
	 * @return	boolean
	 */
	public function is_admin()
	{
		return $this->has_role('admin');
	}

	/**
	 * Checks to see if the user has the given role(s).
	 *
	 *		// Single role
	 *		$allowed = $user->has_role('test');
	 *
	 *		// A list of roles
	 *		$has_all = $user->has_role(array('test', 'manager'));
	 *
	 * @param	mixed	A role or array of roles
	 * @return	boolean
	 */
	public function has_role($roles)
	{
		if ( ! is_array($roles))
		{
			$roles = array((string) $roles);
		}

		$good = true;
		$user_roles = $this->get('roles', array());

		foreach ($roles as $name)
		{
			if ( ! in_array($name, $user_roles))
			{
				$good = false;
				break;
			}
		}

		return $good;
	}

	/**
	 * A getter method.
	 *
	 *		$id = $user->get('user_id');
	 *
	 * @param	string	The property to get
	 * @param	mixed	The value if the property cant be found
	 * @return	mixed	The value or the default value
	 */
	public function get($name, $default = null)
	{
		return Arr::get($this->params, $name, $default);
	}

	/**
	 * The magic "getter".
	 *
	 * @param	string	The name of the property to fetch
	 * @return	mixed	The value
	 */
	public function __get($name)
	{
		return $this->get($name, null);
	}

	/**
	 * A setter method.
	 *
	 *		$user->set('roles', array('admin','guest'));
	 *
	 * @param	string	The property to set
	 * @param	mixed	The value to set
	 */
	public function set($name, $value)
	{
		$this->params[$name] = $value;
	}

	/**
	 * The magic "setter".
	 *
	 * @param	string	The name of the property
	 * @param	mixed	The value to set
	 */
	public function __set($name, $value)
	{
		return $this->set($name, $value);
	}

} // End Presto_User
