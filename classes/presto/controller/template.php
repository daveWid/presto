<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Template Controller.
 * Pulled in functionality from Kohana_Controller_Template
 * to cut down on the amount of autoloading.
 *
 * @package	presto
 * @author	Dave Widmer
 * @copyright	2010 © Dave Widmer
 */
class Presto_Controller_Template extends Kohana_Controller
{
	/**
	 * @var  string  page template
	 */
	public $template = 'template';

	/**
	 * @var  boolean  auto render template
	 **/
	public $auto_render = TRUE;

	/**
	 * @var	array	CSS files
	 */
	public $css = array();

	/**
	 * @var	array	Javascript files
	 */
	public $js  = array();

	/**
	 * @var	string	page title
	 */
	public $title;

	/**
	 * @var	View	View for the template
	 */
	public $view;

	/**
	 * Preloads the view based on the action
	 */
	public function before()
	{
		if ($this->auto_render === TRUE)
		{
			// Load the template
			$this->template = View::factory($this->template);

			// Load the view automagically
			$this->view = View::factory( $this->request->action );
		}
	}

	/**
	 * Adds in CSS, JS and title into the view
	 */
	public function after()
	{
		if ($this->auto_render === TRUE)
		{
			// Add in the css, js, title and view
			$this->template->set('css', $this->css)->set('js', $this->js)->set('title', $this->title)->set('view', $this->view);

			// Assign the template as the request response and render it
			$this->request->response = $this->template;
		}
	}

}