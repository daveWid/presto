<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Provides basic CRUD functionality.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Model_Crud extends Model
{
	/**
	 * @var	String	The Database table name
	 */
	public $table;

	/**
	 * @var String	The primary key.
	 */
	public $primary;

	/**
	 * Inserts a new row.
	 *
	 * @param	array	The data to insert
	 * @return	array	Insert ID and Affected Rows
	 */
	public function create(array $data)
	{
		return DB::insert($this->table)
				->columns(array_keys($data))
				->values(array_values($data))
				->execute();
	}

	/**
	 * Gets the record given the primary key.
	 *
	 * @param	mixed	The primary key
	 * @return	Object	The database result as an stdClass or false
	 */
	public function read($key)
	{
		$result = DB::select()
			->from($this->table)
			->where($this->primary, '=', $key)
			->as_object()
			->execute();

		return (count($result) == 1) ? $result->current() : false;
	}

	/**
	 * Updates the record with the given key.
	 *
	 * @param	mixed	Primary key
	 * @param	array	Data to update
	 * @return	int		Affected rows
	 */
	public function update($key, array $data)
	{
		return DB::update($this->table)
				->where($this->primary, '=', $key)
				->set($data)
				->execute();
	}

	/**
	 * Deletes a record from the database.
	 *
	 * @param	mixed	Primary key value
	 * @return	int		Affected rows
	 */
	public function delete($key)
	{
		return DB::delete($this->table)
				->where($this->primary, '=', $key)
				->execute();
	}

}
