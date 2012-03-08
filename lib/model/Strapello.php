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
	
	// Options needs better defaults
	public static function lists($id, $value = null, $options = array('cards' => 'none'))
	{
		if( is_null($value) )
		{
			if( !$data = static::cache($id) )
			{
				$data = Trello::get_array("lists/$id");
				static::cache($id, $data);
			}
		}
		else
		{
			$valid_keys = array('boards');
			
			if( !in_array($id, $valid_keys) )
			{
				throw new Exception($id . ' is not a valid API endpoint.');
			}
			
			if( !$data = static::cache($id . '_' . $value) )
			{
				$data = Trello::get_array("$id/$value/lists");
				
				foreach($data as $list)
				{
					static::cache($list['id'], $list);
				}
				
				static::cache($id . '_' . $value);
			}
		}
		
		return $data;
	}
	
	public static function board($id, $fields = array('name', 'url'))
	{
		if( !$data = static::cache($id) )
		{
			$fields = (is_array($fields) && !empty($fields)) ? '?fields=' . implode(',', $fields) : '';
			$data = Trello::get_array("boards/$id/$fields");
			static::cache($id, $data);
		}
		
		return $data;
	}
	
	public static function actions($key, $value = null, $filter = 'all', $params = array('limit' => 1000, 'fields' => 'all'))
	{
		$valid_keys = array('members', 'organizations', 'boards', 'lists');
		
		if( !in_array($key, $valid_keys) )
		{
			throw new Exception($key . ' is not a valid API endpoint.');
		}
		
		if( is_null($value) )
		{
			throw new Exception($key . ' must have a value.');
		}
		
		$filter = (!empty($filter)) ? 'filter=' . ((is_array($filter)) ? implode($filter) : $filter) : '';
		
		if( !empty($params) )
		{
			$p = '';
			foreach($params as $k => $v)
			{
				$p .= ((!empty($p)) ? '&' : '') . "$k=" . ((is_array($v)) ? implode($v) : $v);
			}
			
			$params = $p;
		}
		
		if( !$data = static::cache($key . '_' . $value) )
		{
			$data = Trello::get_array("$key/$value/actions?$filter&$params");
			
			foreach( $data as $action )
			{
				static::cache($action['id'], $action);
			}
			
			static::cache($key . '_' . $value, $data);
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
			$data = Trello::get_array("members/$id");
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
		$valid_keys = array('members', 'organizations', 'boards', 'lists');
		
		if( !in_array($key, $valid_keys) )
		{
			throw new Exception($key . ' is not a valid API endpoint.');
		}
		
		if( is_null($value) )
		{
			throw new Exception($key . ' must have a value.');
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
	
	public static function status($name)
	{
		$name = preg_replace('/[^a-zA-Z0-9 ]/', '', strtolower($name));
		return (array_key_exists($name, static::$statuses)) ? static::$statuses[$name] : DEFAULT_STATUS;
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
