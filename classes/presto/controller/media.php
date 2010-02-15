<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Media Autoloading controller.
 *
 * @package	presto
 * @author	Dave Widmer
 * @copyright	2010 © Dave Widmer
 */
class Presto_Controller_Media extends Kohana_Controller
{
	/**
	 * Loading of media files.
	 */
	public function action_load()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$path = pathinfo($file);

		$file = Kohana::find_file('media', $path['dirname'] . '/' . $path['filename'], $path['extension']);

		if ($file){
			// Send the file content as the response
			$this->request->response = file_get_contents($file);
		} else {
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the content type for this extension
		$this->request->headers['Content-Type'] = File::mime_by_ext($path['extension']);
	}

}