<?php
/**
 * Trello API
 * 
 * @package Trello
 * @subpackge API
 * @author Marco Ceppi <marco@ceppi.net>
 */

class Trello
{
	// Hello o/
	private static $calls = 0;
	private static $api = 'https://api.trello.com/1/';
	
	public static function fetch($url)
	{
		static::$calls++;
		return @file_get_contents($url);
	}
	
	public static function get_array($path)
	{
		return json_decode(static::fetch(static::build($path)), true);
	}
	
	public static function get_object($path)
	{
		return json_decode(static::fetch(static::build($path)));
	}
	
	public static function calls()
	{
		return static::$calls;
	}
	
	private static function build($path)
	{
		return static::$api . $path . ((strpos($path, '?') !== false) ? '&' : '?') . 'key=' . PUBLIC_KEY;
	}
}
