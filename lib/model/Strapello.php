<?php
/**
 * Strapello
 * 
 * @package Strapello
 * @subpackge Model
 * @author Marco Ceppi <marco@ceppi.net>
 * 
 * @note Not sure if Caching should be lower in the chain at the Trello
 * 		 level or not. Only time will tell.
 */

require_once('helper/Trello.php');

class Strapello
{
	public static $lists, $boards, $members, $cards;
	public static $statuses = array(
		'done' => 'done', 'todo' => 'todo', 'to do' => 'todo', 
		'doing' => 'inprogress', 'done' => 'done', 'finished' => 'done', 
		'next' => 'next', 'hold' => 'postponed', 'on hold' => 'postponed', 
		'in progress' => 'inprogress');

	private static $cache = array();
	
	public static function lists($id)
	{
		if( !$data = static::cache($id) )
		{
			$data = Trello::get_array("lists/$id");
			static::cache($id, $data);
		}
		
		return $data;
	}
	
	public static function board($id, $fields = array('name', 'url'))
	{
		if( !$data = static::cache($id) )
		{
			$fields = (is_array($fields) && !empty($fields)) ? '?fields=' . implode($fields) : '';
			$data = Trello::get_array("board/$id/$fields");
			static::cache($id, $data);
		}
		
		return $data;
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
		if( !$data = static::cache($id) )
		{
			$data = Trello::get_array("member/$id");
			static::cache($id, $data);
		}
		
		return $data;
	}
	
	/**
	 * Cards
	 * 
	 * Get the cards for a member, organization, board, or list
	 * 
	 * @param string $key either a member, organization, board, list
	 * @param mixed optional $value The search value for key
	 * @param array optional $filter Filtered set of results. Defaults to ALL options
	 * 
	 * @return false|array of $key cards
	 */
	public static function cards($key, $value = null, $filter = array('fields' => 'all', 'checklists' => 'all', 'checkItemStatuses' => 'true'))
	{
		$valid_keys = array('member', 'organization', 'board', 'list');
		
		if( !in_array($key, $valid_keys) )
		{
			throw new Exception($key . ' is not a valid API endpoint.');
		}
		
		if( !$data = static::cache($key . '_' . $value) )
		{
			$data = Trello::get_array("$key/$value/cards");
			
			foreach( $data as $card )
			{
				static::cache($card['id'], $card);
			}
			
			static::cache($key . '_' . $value, $data);
		}
		
		return $data;
	}
	
	// This does not work.
	public static function card($id)
	{
		if( !$data = static::cache($id) )
		{
			$data = Trello::get_array("card/$id");
			static::cache($id, $data);
		}
		
		return $data;
	}
	
	private static function cache($key, $value = null)
	{
		if( !is_null($value) )
		{
			static::$cache[$key] = $value;
			
			return true;
		}
		else
		{
			return ( array_key_exists($key, static::$cache) ) ? static::$cache[$key] : false;
		}
	}
}
