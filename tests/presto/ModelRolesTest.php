<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests the Roles Model
 *
 * @group presto
 * @group presto.model
 * @group presto.model.roles
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_ModelRolesTest extends Kohana_Unittest_Database_TestCase
{
	/** The model to test with. */
	public $model;

	public function setUp()
	{
		$this->model = Model::factory('roles');
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
			'name' => 'testing',
			'description' => 'Here is a role that is for testing'
		));

		$this->assertSame(4, $id);
		$this->assertSame(1, $affected);
	}

	/**
	 * Tests the default read values.
	 */
	public function test_reads()
	{
		$guest = $this->model->read(1);
		$login = $this->model->read(2);
		$admin = $this->model->read(3);

		$this->assertSame('guest', $guest->name);
		$this->assertSame('login', $login->name);
		$this->assertSame('admin', $admin->name);
	}

	/**
	 * Tests to make sure false is returned when the read doesn't find the key.
	 */
	public function test_read_false_when_not_found()
	{
		$this->assertFalse($this->model->read(4));
	}

	/**
	 * Double check the user has the properties is should have
	 */
	public function test_update()
	{
		$affected = $this->model->update(1, array(
			'name' => 'testing'
		));
		$role = $this->model->read(1);

		$this->assertSame(1, $affected);
		$this->assertSame('testing', $role->name);
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
