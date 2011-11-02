<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Provides basic CRUD functionality along with data validation.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
abstract class Presto_Model_Crud extends Model
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
	 * @var boolean	Has the validation been passed yet?
	 */
	protected $validation_passed = false;

	/**
	 * @var	array	A list of fields that can be inserted into the database.
	 */
	protected $fields = array();

	/**
	 * Saves the data to the database. If the primary key is present, then 
	 * the data will be updated, otherwise it will be added.
	 *
	 * @param   array   The data to save
	 * @return  array   The response array which is 'success' => boolean,
	 *                  'body' => primary_key, 'action' => '(add|edit)'
	 */
	public function save(array $data)
	{
		$response = array();

		$id = Arr::get($data, $this->primary, FALSE);
		if ($id)
		{
			$response['action'] = 'edit';

			unset($data[$this->primary]);
			$num = $this->update($id, $data);

			$response['body'] = $id;
			$response['success'] = ($num !== false);
		}
		else
		{
			$response['action'] = 'add';
			list($id, $num) = $this->create($data);

			$response['success'] = ($num > 0);
			$response['body'] = $id;
		}

		return $response;
	}

	/**
	 * Inserts a new row.
	 * Data is automatically filtered and checked for validation.
	 *
	 *		$model = new Model_Users;
	 *		list($id, $num) = $model->create($data);
	 *
	 *		// Or
	 *		list($id, $num) = Model::factory('users')->create($data);
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
	 *		$row = $model->read($key);
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
	 * By default all updated data must be validated, but this can be changed
	 * with the third parameter.
	 *
	 * This function will also automatically filter the data.
	 *
	 *		$num = $model->update($key, $data);
	 *
	 *		// Remove Validation
	 *		$num = $model->update($key, $data, false);
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
	 *		$num = $model->delete($key);
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
	 * Below are the additional parameters that can be set. The where
	 * param should follow the where clause in the Datbase class as either
	 * a single array, or an array of arrays.
	 *
	 * Param  | Description                  | Default
	 * -------|------------------------------|-------
	 * order  | The column to order on       | primary key
	 * dir    | The direction to sort on     | DESC
	 * limit  | The number of rows to return | null (all rows)
	 * offset | The db offset                | 0
	 * where  | Additional where params      | null
	 *
	 *		// Grabs all the rows, latest first
	 *		$rows = $model->fetch();
	 *
	 *		// Grabs 20 rows, starting at the 20th row, earliest first where status is 1
	 *		$rows = $model->fetch(array(
	 *			'dir' => 'ASC',
	 *			'num' => 20,
	 *			'offset' => 20,
	 *			'where' => array('status', '=', 1),
	 *		));
	 *
	 * @param	array	Additional parameters to inject into the query.
	 * @param	DB_Query	A Database query that has already been started
	 * @return	Database_Result
	 */
	public function fetch(array $params = array(), $query = null)
	{
		if ($query === null)
		{
			$query = DB::select()->from($this->table);
		}

		$query = $query->order_by(Arr::get($params, "order", $this->primary), Arr::get($params, 'dir', 'DESC'));

		// Check to see if only a number of posts are requested, or all...
		$limit = Arr::get($params, 'limit', null);
		if ($limit !== null)
		{
			$query = $query->limit($limit)->offset(Arr::get($params, 'offset', 0));
		}

		$where = Arr::get($params, 'where', null);
		if (is_array($where))
		{
			// If not an array of arrays, then it needs to be normalized
			if ( ! is_array($where[0]))
			{
				$where = array(array($where[0], $where[1], $where[2]));
			}

			foreach ($where as $w)
			{
				$query = $query->where($w[0], $w[1], $w[2]);
			}
		}

		return $query->as_object()->execute();
	}

	/**
	 * Checks to see if the passed in data. This will be run automatically in
	 * all create statement and updates by default. You can remove the update 
	 * validation checking by passing in false as the 3rd param.
	 *
	 *		if ($model->validate($_POST))
	 *		{
	 *			// Save data
	 *			// $model->create($_POST);
	 *		}
	 *		else
	 *		{
	 *			// Show errors
	 *			// $model->validation->errrors('message_file')
	 *		}
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
	 * Runs through the data and makes sure there isn't any data that
	 * shouldn't be there.
	 *
	 * This function is run automatically before any data is added/updated.
	 *
	 * If you are getting unexpected results, make sure to look at your $fields
	 * variable.
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
	 * Add all specific func
	 *
	 * @param	Validation	A validation object
	 * @return	Validation	The validtion object with the rules added
	 */
	abstract protected function validation_rules(Validation $valid);

}
