<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The message class is a simple flash messaging system. This is needed because
 * the web is stateless and you need to let your users what is going on.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Message
{
	/** Constants to use for the types of messages that can be set. */
	const ERROR = "error";
	const NOTICE = "notice";
	const SUCCESS = "success";
	const WARN = "warn";

	/** The name of the session variable. */
	public static $name = "flash_message";

	/** The path to the view class. */
	public static $path = "presto/message";

	/**
	 * @var	array	The message to display in an array format
	 */
	public $message;

	/**
	 * @var	string	The type of message. It can be anything, but the constants are preferred.
	 */
	public $type;

	/**
	 * Creates a new Presto_Message instance.
	 *
	 * @param	string	Type of message
	 * @param	mixed	Message to display, either string or array
	 */
	public function __construct($type, $message)
	{
		$this->type = $type;

		if ($message == "")
		{
			$message = null;
		}

		$this->message = (is_array($message) || $message === null) ?
			$message :
			array($message);
	}

	/**
	 * Clears the message from the session
	 */
	public static function clear()
	{
		Session::instance()->delete(self::$name);
	}

	/**
	 * Displays the message
	 *
	 * @return	string	The message to display or an empty string ""
	 */
	public static function display()
	{
		$msg = self::get();

		if ($msg)
		{
			self::clear();
			return View::factory(self::$path)->set("msg", $msg)->render();
		}
		else
		{
			return "";
		}
	}

	/**
	 * The same as display - used to mold to Kohana standards
	 *
	 * @return	string	HTML for message
	 */
	public static function render()
	{
		return self::display();
	}

	/**
	 * Gets the current message.
	 *
	 * @return	mixed	The message or null
	 */
	public static function get()
	{
		return Session::instance()->get(self::$name, null);
	}

	/**
	 * Sets a message.
	 *
	 * @param	string	Type of message
	 * @param	mixed	Array/String for the message
	 */
	public static function set($type, $message)
	{
		Session::instance()->set(self::$name, new Message($type, $message));
	}

	/**
	 * Sets an error message.
	 *
	 * @param	mixed	String/Array for the message(s)
	 * @return	void
	 */
	public static function error($message)
	{
		self::set(Message::ERROR, $message);
	}

	/**
	 * Sets a notice message.
	 *
	 * @param	mixed	String/Array for the message(s)
	 * @return	void
	 */
	public static function notice($message)
	{
		self::set(Message::NOTICE, $message);
	}

	/**
	 * Sets a success message.
	 *
	 * @param	mixed	String/Array for the message(s)
	 * @return	void
	 */
	public static function success($message)
	{
		self::set(Message::SUCCESS, $message);
	}

	/**
	 * Sets a warning message.
	 *
	 * @param	mixed	String/Array for the message(s)
	 * @return	void
	 */
	public static function warn($message)
	{
		self::set(Message::WARN, $message);
	}

}
