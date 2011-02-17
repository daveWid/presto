<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The Presto Template controller.
 * This will be used by all view that need to be wrapped into the template.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
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
	public $meta = array("charset" => "utf-8");

	/**
	 * @var View	The view for the content
	 */
	public $content;

	/**
	 * Adss variables to the template before it renders
	 */
	public function after()
	{
		if ($this->auto_render === TRUE)
		{
			$vars = array("title", "js", "css", "meta", "content");

			foreach ($vars as $key)
			{
				$this->template->set($key, $this->{$key});
			}
		}

		return parent::after();
	}

}
