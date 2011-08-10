<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The Presto administration controller.
 * 
 * This controller makes sure that the user is an administrator before letting them in
 *
 * @package    Presto
 * @author     Dave Widmer
 * @copyright  2011 © Dave Widmer
 */
class Presto_Controller_Crud extends Controller_Auth
{
	/**
	 * @var Model_Crud  The model to poll for the data.
	 */
	protected $model;

	/**
	 * Read
	 */
	public function action_index()
	{
		$this->title = "View";
		$this->content = View::factory("{$this->request->controller()}/view")->set(array(
			'data' => $this->model->fetch(),
		));
	}

	/**
	 * Create
	 */
	public function action_add()
	{
		if ($this->request->method() === "POST")
		{
			$result = $this->model->create($_POST);
			$this->process_result($result);
		}

		if ( ! $this->content)
		{
			$this->title = "Add";
			$this->content = View::factory("{$this->request->controller()}/form");
		}
	}

	/**
	 * Update
	 */
	public function action_edit()
	{
		$id = $this->request->param("id");

		if ($this->request->method() === "POST")
		{
			$result = $this->model->update($id, $_POST);
			$this->process_result($result);
		}
		else
		{
			$_POST = (array) $this->model->read($id);
		}

		if ( ! $this->content)
		{
			$this->title = "Edit";
			$this->content = View::factory("{$this->request->controller()}/form");
		}
	}

	/**
	 * Delete
	 */
	public function action_delete()
	{
		$id = $this->request->param("id");
		$result = $this->model->delete($id);

		$this->process_result($result);

		if ( ! $this->content)
		{
			$this->request->redirect($this->request->url(array(
				'action' => false
			)));
		}
	}

	/**
	 * Processes the result from the database
	 *
	 * @param mixed   array on a successfull create, int of affected rows for
	 *                 edit/delete or false on error
	 */
	protected function process_result($result)
	{
		if (is_array($result))
		{
			list($id, $num) = $result;
			$this->success($id, $num);
		}
		else if ($result === false)
		{
			$this->failed_validation();
		}
		else
		{
			($result === 0) ?
				$this->no_change() :
				$this->success($this->request->param('id'), $result);
		}
	}

	/**
	 * The last database action was a success
	 *
	 * @param int  The insert id
	 * @param int  The number of affected rows
	 */
	protected function success($id, $num)
	{
		$msg = Kohana::message($this->request->controller(), "{$this->request->action()}.success");
		$this->prep_response(true, Message::SUCCESS, $msg);
	}

	/**
	 * The last database result had no change
	 */
	protected function no_change()
	{
		$msg = Kohana::message($this->request->controller(), "{$this->request->action()}.no_change");
		$this->prep_response(false, Message::WARN, $msg);
	}

	/**
	 * The validation failed before submitting
	 */
	protected function failed_validation()
	{
		$msg = $this->model->validation->errors($this->request->controller());
		$this->prep_response(false, Message::ERROR, $msg);
	}

	/**
	 * Preps the response to the user.
	 *
	 * @param boolean  Was this a 
	 * @param string   The type of response
	 * @param string   The message to give to the user
	 */
	protected function prep_response($success, $type, $msg)
	{
		if ($this->request->is_ajax())
		{
			$this->content = array(
				'success' => $success,
				'type' => $type,
				'body' => $msg,
			);
		}
		else
		{
			Message::set($type, $msg);
		}
	}
	
}
