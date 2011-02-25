<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests the User Roles Model
 *
 * @group presto
 * @group presto.model
 * @group presto.model.userroles
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_ModelUserRolesTest extends Kohana_Unittest_Database_TestCase
{
	/** The model to test with. */
	public $model;

	public function setUp()
	{
		$this->model = Model::factory('user_roles');
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
			'user_id' => '1',
			'role_id' => '1'
		));

		$this->assertSame(0, $id); // No primary key...
		$this->assertSame(1, $affected);
	}

}
