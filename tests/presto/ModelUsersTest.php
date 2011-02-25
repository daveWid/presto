<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests the Users Model
 *
 * @group presto
 * @group presto.model
 * @group presto.model.users
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_ModelUsersTest extends Kohana_Unittest_Database_TestCase
{
	/** The model to test with. */
	public $model;

	public function setUp()
	{
		$this->model = Model::factory('users');
		parent::setUp();
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
	 * Tests the create function.
	 */
	public function test_create()
	{
		list($id, $affected) = $this->model->create(array(
			'email' => 'dave2@davewidmer.net',
			'password' => 'justabunchofrandomstuff'
		));

		$this->assertSame(2, $id);
		$this->assertSame(1, $affected);
	}

	/**
	 * Tests a read.
	 */
	public function test_read()
	{
		$user = $this->model->read(1);

		$this->assertSame(1, $user->user_id);
		$this->assertSame('dave@davewidmer.net', $user->email);
		$this->assertSame(array('login','admin'), $user->roles);
	}

	/**
	 * Tests to make sure false is returned when the read doesn't find the key.
	 */
	public function test_read_false_when_not_found()
	{
		$this->assertFalse($this->model->read(2));
	}

	/**
	 * Double check the user has the properties is should have
	 */
	public function test_update()
	{
		$affected = $this->model->update(1, array(
			'email' => 'dave2@davewidmer.net'
		));
		$user = $this->model->read(1);

		$this->assertSame(1, $affected);
		$this->assertSame('dave2@davewidmer.net', $user->email);
	}

	/**
	 * Tests the user for roles.
	 */
	public function test_delete()
	{
		$affected = $this->model->delete(1);
		$this->assertSame(1, $affected);
	}

}
