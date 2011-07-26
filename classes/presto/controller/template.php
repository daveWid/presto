<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The Presto Template controller.
 * This will be used by all view that need to be wrapped into the template.
 *
 * @package    Presto
 * @author     Dave Widmer
 * @copyright  2011 © Dave Widmer
 */
class Presto_Controller_Template extends Kohana_Controller_Template
{
	/**
	 * @var string	Page title
	 */
	public $title = "";

	/**
	 * Add the path to the script you need.
	 *
	 * @var array	Extra script files to be included.
	 */
	public $js = array();

	/**
	 * You will need to add the path => type into the array.
	 * (e.g "reset.css" => "screen"
	 *
	 * @var array	Extra CSS to be included in the template
	 */
	public $css = array();

	/**
	 * You will need to add the property => value into the $meta array.
	 *
	 * @var array
	 */
	public $meta = array();

	/**
	 * @var View	The view for the content
	 */
	public $content;

	/**
	 * Binds some variables to the template.
	 */
	public function before()
	{
		$before = parent::before();

		if ($this->auto_render === TRUE)
		{
			$vars = array("title", "js", "css", "meta", "content");

			foreach ($vars as $name)
			{
				$this->template->bind($name, $this->$name);
			}
		}

		return $before;
	}

	/**
	 * Checks to see if the request was ajax
	 */
	public function after()
	{
		if ($this->request->is_ajax())
		{
			$this->auto_render = false;
			$this->response->headers(array(
				'Content-Type' => 'application/json'
			))->body(json_encode($this->content));
		}

		return parent::after();
	}

}
