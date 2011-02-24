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
	/**
	 * @var	String	The Database table name
	 */
	public $table = "UserRoles";

	/**
	 * @var String	The primary key.
	 */
	public $primary = "UserID";

}
