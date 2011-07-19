<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Auth Driver that uses the Query Builder.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Auth extends Kohana_Auth
{
	/**
	 * @var Model_Users	The model used to do the login
	 */
	public $model;

	/**
	 * Sets the model and loads config and session data.
	 *
	 * @return  void
	 */
	public function __construct($config = array())
	{
		$this->model = new Model_Users;
		parent::__construct($config);
	}

	/**
	 * Does the user login.
	 *
	 * @param	string	$username	The username
	 * @param	string	$password	The password (already hashed)
	 * @param	boolean	$remember	Should the login be remembered? (autologin)
	 * @return	boolean
	 */
	protected function _login($username, $password, $remember)
	{
		$user = $this->model->login($username, $password);

		// Check to see if there was a user
		if (count($user) === 1)
		{
			$this->complete_login(new Admin_User((array) $user->current()));
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Hashing on bcrypt.
	 *
	 * @see	http://php.net/manual/en/function.crypt.php
	 * @param	string	The string to hash
	 * @return	string	The hashed password
	 */
	public function hash($str)
	{
		return crypt($str, $this->generate_salt($str));
	}

	/**
	 * Generates a predictable (but different for each user) salt to use
	 * for hashing.
	 *
	 * Bcrypt salt = "$2a$", a two digit cost parameter, "$", and 22 digits from the base64 alphabet "./0-9A-Za-z"
	 *
	 * @see	http://php.net/manual/en/function.crypt.php
	 * @param	string		The string to help with generating a salt
	 * @return	string		The salt 
	 */
	protected function generate_salt($str)
	{
		$begin = "$2a$" . sprintf("%0%d", $this->config['iterations']) . "$";
		$hash = md5($str);
		$salt = "";

		foreach ($this->config['salt'] as $pos)
		{
			$salt .= substr($hash, $pos, 1);
		}

		$len = strlen($string);
		if ($len < 22)
		{
			$num = 22 - $len;
			$salt .= substr($hash, 0, $num);
		}
		else if ($len > 22)
		{
			$salt = substr($salt, 0, 22);
		}

		return $begin.$salt."$";
	}

	/**
	 * Gets the password that is stored for the given username.
	 *
	 * @param	string	$username	The user get the password for.
	 * @throws	Kohana_Exception
	 */
	public function password($username)
	{
		throw new Kohana_Exception(
			"The password method is not available with the presto auth driver."
		);
	}

	/**
	 * Checks to see if the passed in password matches the logged-in users password.
	 *
	 * @param	string	$password	The password to check
	 * @throws	Kohana_Exception
	 */
	public function check_password($password)
	{
		throw new Kohana_Exception(
			"The check_password method is not available	with the presto auth driver."
		);
	}

}
