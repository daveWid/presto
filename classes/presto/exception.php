<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Exception Handler...
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Exception
{
	/**
	 * Handles a caught exception.
	 *
	 * @param	Exception	The Exception that was caught.
	 * @return	boolean
	 */
	public static function handler(Exception $e)
	{
		if (Kohana::$environment === Kohana::PRODUCTION)
		{
			try
			{
				$r = new Request('error/404');

				// Create a text version of the exception
				if (is_object(Kohana::$log))
				{
					// Add this exception to the log
					Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e));

					// Make sure the logs are written
					Kohana::$log->write();
				}

				echo $r->execute()->send_headers()->body();
				return TRUE;
			}
			catch (Exception $e)
			{
				// Clean the output buffer if one exists
				ob_get_level() and ob_clean();

				echo $e->getMessage();
				exit(1);
			}
		}
		else
		{
			return Kohana_Exception::handler($e);
		}
	}

}
