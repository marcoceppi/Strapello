<?php
/**
 * Strapello
 * 
 * @package Strapello
 * @subpackge Model
 * @author Marco Ceppi <marco@ceppi.net>
 */

require_once('helper/Trello.php');

class Strapello
{
	public static $lists, $boards, $members, $cards;
	protected static $trello;
	private static $cache;
	
	public static function lists($id)
	{
		
	}
	
	public static function board($id)
	{
		
	}
	
	/**
	 * Member
	 * 
	 * Get member information
	 * 
	 * @param string $key either a member, organization, board, list
	 * @param mixed optional $value The search value for key
	 * 
	 * @return false|array of $key cards
	 */
	public static function member($id)
	{
		
	}
	
	/**
	 * Cards
	 * 
	 * Get the cards for a member, organization, board, or list
	 * 
	 * @param string $key either a member, organization, board, list
	 * @param mixed optional $value The search value for key
	 * 
	 * @return false|array of $key cards
	 */
	public static function cards($key, $value = null)
	{
		
	}
	
	public static function card()
	{
		
	}
}
