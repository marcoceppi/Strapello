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
	
	// We *really* need to be caching data more aggressively
	private $cache = array();
	
	public static function fetch($url)
	{
		return @file_get_contents($url);
	}
	
	public static function get($path)
	{
		
	}
}

