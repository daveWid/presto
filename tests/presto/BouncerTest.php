<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Presto's Bouncer Class.
 * Since we can't use Reqest::$instance->action() as you normally would in
 * production r testing we inject actions in.
 *
 * @group presto
 * @group presto.bouncer
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_BouncerTest extends Kohana_Unittest_Database_TestCase
{
	/** The auth instance used in the tests. */
	public $auth;

	/** The acl for testing. */
	public $acl;

	/**
	 * Creates an Auth instance for use in the tests.
	 */
	public function setUp()
	{
		$this->auth = Auth::instance();

		$this->acl = array(
			'edit' => 'login',
			'delete' => 'admin',
			'review' => array('login', 'reviewer'),
		);

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
	 * Tests to make sure that if the action doesn't have a rule it defaults to true
	 */
	public function test_acl_no_access_rule_is_true()
	{
		$this->assertTrue(Bouncer::acl($this->acl, 'index'));
	}

	/**
	 * Makes sure that if a user isn't logged in and there is an access role
	 * listed that it is false
	 */
	public function test_acl_not_logged_in_with_access_rule_is_false()
	{
		$this->assertFalse(Bouncer::acl($this->acl, 'edit'));
	}

	/**
	 * Runs a login on the test data
	 */
	public function test_acl_if_admin_always_true()
	{
		$this->login(); // User is login and admin.
		$this->assertTrue(Bouncer::acl($this->acl, 'edit'));
	}

	/**
	 * Double check the user has the properties is should have
	 */
	public function test_acl_login_role()
	{
		$this->remove_admin();
		$user = $this->auth->get_user(); // Now user is only login...

		$this->assertTrue(Bouncer::acl($this->acl, 'edit'));
		$this->assertFalse(Bouncer::acl($this->acl, 'delete'));
		$this->assertFalse(Bouncer::acl($this->acl, 'review')); // has login, but not reviewer
	}

	/**
	 * Tests that a role that isn't a string or array throws an exception
	 * @expectedException Kohana_Exception
	 */
	public function test_acl_incorrect_role_throws_exception()
	{
		$this->remove_admin();
		Bouncer::acl(array('fail' => null), 'fail');
	}

	/**
	 * Tests the role function when the user isn't logged in.
	 */
	public function test_role_not_logged_in()
	{
		$this->assertFalse(Bouncer::role('login'));
	}

	/**
	 * Tests a role to see if the user has it
	 */
	public function test_role_function()
	{
		$this->login();
		$this->assertTrue(Bouncer::role('admin'));
		$this->assertTrue(Bouncer::role(array('admin','login')));
		$this->assertFalse(Bouncer::role('fake'));
		$this->assertFalse(Bouncer::role(array('admin','fake')));
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

	/**
	 * Removes the admin role.
	 */
	private function remove_admin()
	{
		$this->login();
		$user = $this->auth->get_user();
		Model::factory('user_roles')->remove($user->user_id, 3); // 3 is admin in testing...
		$this->auth->logout();
		$this->login();
	}

}
