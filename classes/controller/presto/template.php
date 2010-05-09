<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The main template controller.
 *
 * @package	presto
 * @author	Dave Widmer
 * @copyright	2010 © Dave Widmer
 */
class Presto_Controller_Template extends Kohana_Controller
{
	/** @var  string/View	Page template */
	public $template = 'template';

	/** @var	boolean	Rend the template automatically? */
	public $auto_render = TRUE;

	/** @var	array	List of CSS files */
	public $css = array();

	/** @var	array	List of Javascript files */
	public $js  = array();

	/** @var	string	Page Title */
	public $title;

	/** @var	View	View used as the main content of the page. */
	public $content;

	/**
	 * Preloads the view based on the action
	 */
	public function before()
	{
		// If the auto render flag is set, load the template.
		if ($this->auto_render === TRUE)
		{
			$this->template = View::factory($this->template);
		}
	}

	/**
	 * Completes the request
	 */
	public function after()
	{
		// If auto_render then load up the content and display the template.
		if ($this->auto_render === TRUE)
		{
			// Add in the css, js, title and view
			$this->template->set('css', $this->css)
				->set('js', $this->js)
				->set('title', $this->title)
				->set('content', $this->content);

			// Render the response
			$this->request->response = $this->template;
		}
	}

}