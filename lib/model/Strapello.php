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
			
			if( !$data = static::cache($id . '_' . $value . '_lists') )
			{
				$data = Trello::get_array("$id/$value/lists");
				
				foreach($data as $list)
				{
					static::cache($list['id'], $list);
				}
				
				static::cache($id . '_' . $value . '_lists', $data);
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
		
		$filter = (!empty($filter)) ? 'filter=' . ((is_array($filter)) ? implode(',', $filter) : $filter) : '';
		
		if( !empty($params) )
		{
			$p = '';
			foreach($params as $k => $v)
			{
				$p .= ((!empty($p)) ? '&' : '') . "$k=" . ((is_array($v)) ? implode(',', $v) : $v);
			}
			
			$params = $p;
		}
		
		if( !$data = static::cache($key . '_' . $value . '_actions') )
		{
			$data = Trello::get_array("$key/$value/actions?$filter&$params");
			
			foreach( $data as $action )
			{
				static::cache($action['id'], $action);
			}
			
			static::cache($key . '_' . $value . '_actions', $data);
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
		
		if( !$data = static::cache($key . '_' . $value . '_cards') )
		{
			$data = Trello::get_array("$key/$value/cards");
			
			foreach( $data as $card )
			{
				static::cache($card['id'], $card);
			}
			
			static::cache($key . '_' . $value . '_cards', $data);
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
	
	public static function changes($key, $value, $seeds = array())
	{
		$raw_actions = static::actions($key, $value, 'updateCard:idList');
		//echo "<pre>";var_dump($raw_actions);die();
		if( empty($seeds) )
		{
			throw new Exception('Not supported yet');
		}

		$now = date('Y-m-d');
		$changes = array($now => array());

		foreach($seeds as $seed)
		{
			$changes[$now][$seed['id']] = $seed['status'];
		}

		/**
		 * This is a weird way to do this, so I'm going to close my eyes and do it - then see what happens
		 *
		 * So we go through a 24 hour (day) and track each card that changes, a card may change multiple
		 * times a day, so we only care about
		 *
		 * That makes sense, it only tracks changes that happen, so we can't get long running statuses. Instead lets
		 * feed in the current card statuses for a user then step backwards! One day, these API results will be
		 * cached. Until then, I'm so sorry Trello <3
		 *
		 * Store everything as the Unix EPOC timestamp, because we're cool like that.
		 * Naw, just kidding. Who cares about EPOCH, we'll just convert it in the JS
		 */
		$actions = array();
		$last_change_date = $now;
		foreach( $raw_actions as $change )
		{
			$change_time = date('Y-m-d', strtotime($change['date']));
			
			// Lets see if we've done this day yet, if not we'll need to first process the previous day's 
			// data, then start a new bean counter
			if( !array_key_exists($change_time, $changes) )
			{
				$next_day = date('Y-m-d', strtotime('-1 day', strtotime($last_change_date)));
				for( $i = strtotime($change_time); $i <= strtotime($next_day); $next_day = date('Y-m-d', strtotime('-1 day', strtotime($next_day))) )
				{
					$actions[$last_change_date] = array('todo' => 0, 'done' => 0, 'inprogress' => 0, 'next' => 0, 'js_time' => strtotime($last_change_date) * 1000);
					// Look at how lazy I am!
					$next_day = date('Y-m-d', strtotime('-1 day', strtotime($last_change_date)));
					// Process other day's stats
					foreach( $changes[$last_change_date] as $card_id => $status )
					{
						$actions[$last_change_date][$status]++;
						//$actions[$last_change_date]['total']++;
					}
					
					$changes[$next_day] = $changes[$last_change_date];
					$last_change_date = $next_day;
				}
			}
			
			if( !$status = static::status($change['data']['listAfter']['name']) )
			{
				continue;
			}
			
			$changes[$change_time][$change['data']['card']['id']] = $status;
			$last_change_date = $change_time;
		}
		
		return $actions;
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
