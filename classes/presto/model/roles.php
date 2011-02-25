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
	/**
	 * @var	String	The Database table name
	 */
	public $table = "Roles";

	/**
	 * @var String	The primary key.
	 */
	public $primary = "role_id";

}
