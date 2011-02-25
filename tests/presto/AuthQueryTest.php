<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Presto's Auth Query Driver
 *
 * @group presto
 * @group presto.auth
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_AuthQueryTest extends Kohana_Unittest_Database_TestCase
{
	/** The auth instance used in the tests. */
	public $auth;

	/**
	 * Creates an Auth instance for use in the tests.
	 */
	public function setUp()
	{
		$this->auth = Auth::instance();
		parent::setUp();
	}

	/**
	 * Logs the "user" out when testing.
	 */
	public function tearDown()
	{
		$this->auth->logout();
		parent::tearDown();
	}

	/**
	 * Gets the dataset to run the tests on.
	 *
	 * @return	The data set to use for testing.
	 */
	public function getDataSet()
	{
		return new PHPUnit_Extensions_Database_DataSet_XmlDataSet(dirname(__FILE__) . '/../data/Users.xml');
	}

	/**
	 * Making sure auth is empty on initialization.
	 */
	public function test_empty_on_init()
	{
		$this->assertFalse($this->auth->logged_in());
		$this->assertNull($this->auth->get_user());
	}

	/**
	 * Runs a login on the test data
	 */
	public function test_login()
	{
		$this->assertFalse($this->auth->login('dave@davewidmer.net','wrongpassword'));
		$this->assertTrue($this->login());
		$this->assertTrue($this->auth->logged_in());
	}

	/**
	 * Double check the user has the properties is should have
	 */
	public function test_user_properties()
	{
		$this->login();
		$user = $this->auth->get_user();

		$this->assertType('stdClass', $user);
		$this->assertObjectHasAttribute('user_id', $user);
		$this->assertObjectHasAttribute('email', $user);
		$this->assertObjectHasAttribute('roles', $user);
	}

	/**
	 * Tests the user for roles.
	 */
	public function test_user_roles()
	{
		$this->login();

		$this->assertTrue($this->auth->logged_in());
		$this->assertFalse($this->auth->logged_in('guest'));
		$this->assertTrue($this->auth->logged_in('login'));
		$this->assertTrue($this->auth->logged_in(array('admin','login')));
		$this->assertFalse($this->auth->logged_in(array('guest','madeuprole')));
	}

	/**
	 * Tests to make sure the password() function throws an exception
	 * @expectedException Kohana_Exception
	 */
	public function test_password_throws_exception()
	{
		$this->auth->password('dave@davewidmer.net');
	}

	/**
	 * Tests to make sure the check_password() function throws an exception
	 * @expectedException Kohana_Exception
	 */
	public function test_check_password_throws_exception()
	{
		$this->login();
		$this->auth->check_password('dave');
	}
	
	/**
	 * Check that the logout works correctly.
	 */
	public function test_logout()
	{
		$this->login();

		$this->assertTrue($this->auth->logged_in());
		$this->assertTrue($this->auth->logout());
		$this->assertFalse($this->auth->logged_in());
	}

	/**
	 * Logs a user in with correct credentials.
	 *
	 * @return	boolean	Login success
	 */
	private function login()
	{
		return $this->auth->login('dave@davewidmer.net', 'dave');
	}

}
