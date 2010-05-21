<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The C(reate) R(ead) U(pdate) D(elete) Model.
 *
 * @package	preston
 * @author	Dave Widmer
 */
class Model_Crud extends Kohana_Model
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
	 * @return	Database_Result
	 */
	public function read($key)
	{
		return DB::select()
				->from($this->table)
				->where($this->primary, '=', $key)
				->as_object()
				->execute();
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