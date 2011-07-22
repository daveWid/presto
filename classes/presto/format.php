<?php defined('SYSPATH') or die('No direct script access.');

/**
 * A simple text formatting library.
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_Format
{
	/**
	 * Formats a phone number.
	 *
	 * @param	string	Phone number
	 * @param	string	sprintf pattern
	 * @return	string	Formatted phone number
	 */
	public static function phone($number, $pattern = '(%d) %d-%d')
	{
		$number = preg_replace('/[^\d]/', '', $number);

		switch (strlen($number))
		{
			case 11:
				$pattern = '1 ' . $pattern;
				$number = substr($number, 1);
			case 10:
				return sprintf($pattern,
						substr($number, 0, 3),
						substr($number, 3, 3),
						substr($number, 6)
				);
				break;
			default:
				return $number;
		}

	}

	/**
	 * Formats a zip code.
	 *
	 * @param	string	Zip code
	 * @return	string	Formatted zip code
	 */
	public static function zip($zip)
	{
		$zip = preg_replace('/[^\d]/', '', $zip);

		switch (strlen($zip))
		{
			case 5:
				return $zip;
				break;
			case 9:
				return substr($zip, 0, 5).'-'.substr($zip, 5);
				break;
		}
	}

}
