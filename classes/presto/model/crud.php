<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Provides basic CRUD functionality along with data validation.
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
	 * @var Validation	The validation object
	 */
	public $validation = null;

	/**
	 * @var Boolean	Has the validation been passed yet?
	 */
	protected $validation_passed = false;

	/**
	 * @var	array	A list of fields that can be inserted into the database.
	 */
	protected $fields = array();

	/**
	 * Inserts a new row.
	 * Data is automatically filtered and checked for validation.
	 *
	 * @param	array	The data to insert
	 * @return	array	Insert ID and Affected Rows
	 * @return	boolean	FALSE if the data didn't validate
	 */
	public function create(array $data)
	{
		if ( ! $this->validation_passed && ! $this->validate($data))
		{
			return false;
		}

		$data = $this->filter($data);

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
	 * Updates the record with the given primary key.
	 *
	 * The data must be validated before it is updated.
	 * This function will also automatically filter the data.
	 *
	 * @param	mixed	Primary key
	 * @param	array	Data to update
	 * @param	boolean	Should the data be validated?
	 * @return	int		Affected rows
	 * @return	boolean	FALSE if the data didn't validate
	 */
	public function update($key, array $data, $validate = TRUE)
	{
		if ( $validate && ! $this->validation_passed && ! $this->validate($data))
		{
			return false;
		}

		$data = $this->filter($data);

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

	/**
	 * Fetches rows.
	 *
	 * Param  | Description                  | Default
	 * -------|------------------------------|-------
	 * dir    | The direction to sort on     | DESC
	 * limit  | The number of rows to return | null (all rows)
	 * offset | The db offset                | 0
	 * where  | Additional where params      | null
	 *
	 * @param	array	Additional parameters to inject into the query.
	 * @return	Database_Result
	 */
	public function fetch(array $params)
	{
		$query = DB::select()->from($this->table)->order_by($this->primary, Arr::get($params, 'dir', 'DESC'));

		// Check to see if only a number of posts are requested, or all...
		$limit = Arr::get($params, 'limit', null);
		if ($num !== null)
		{
			$query = $query->limit($limit)->offset(Arr::get($params, 'offset', 0));
		}

		$where = Arr::get($params, 'where', null);
		if (is_array($where))
		{
			// If not an array of arrays, then it needs to be normalized
			if ( ! is_array($where[0]))
			{
				$where = array($where[0], $where[1], $where[2]);
			}

			foreach ($where as $w)
			{
				$query = $query->where($w[0], $w[1], $w[2]);
			}
		}

		return $query->as_object()->execute();
	}

	/**
	 * Checks to see if the passed in data
	 *
	 * @param	array	The data to validate against.
	 * @return	boolean	Validation success
	 */
	public function validate(array $data)
	{
		// Setup a new validation instance if this one doesn't exist.
		if ($this->validation === null)
		{
			$this->validation = new Validation($data);
			$this->validation = $this->validation_rules($this->validation);
		}

		$this->validation_passed = $this->validation->check();
		return $this->validation_passed;
	}

	/**
	 * Runs through the data and makes sure there isn't any data that shouldn't be there.
	 *
	 * @param	array	The data to filter
	 * @return	array	The filtered array
	 */
	public function filter(array $data)
	{
		$filtered = array();

		foreach ($data as $key => $value)
		{
			if (in_array($key, $this->fields))
			{
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}

	/**
	 * Takes a validation object and adds in the appropriate rules for the model.
	 *
	 * @param	Validation	A validation object
	 * @return	Validation	The validtion object with the rules added
	 */
	protected function validation_rules(Validation $valid)
	{
		return $valid; // In extended classes add more rules...
	}

}
