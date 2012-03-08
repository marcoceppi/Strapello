<?php

if( !defined('IN_APP') ) { die('DANGE WILL ROBINSON'); }

class user extends App
{
	public static function init($user)
	{
		$userdata = Strapello::member($user);
		$cards = Strapello::cards('members', $userdata['id']);
		
		$boards = array();
		$tasks = array();

		$done = array();
		$todo = array();
		$next = array();
		$inprogress = array();
		$postponed = array();

		foreach($cards as $card)
		{
			if( !array_key_exists($card['idBoard'], $boards) )
			{
				$boards[$card['idBoard']] = array('data' => Strapello::board($card['idBoard']), 'stats' => array('inprogress' => 0, 'todo' => 0, 'done' => 0, 'total' => 0));
				// Pre-seed cache with lists for the board
				Strapello::lists('boards', $card['idBoard']);
			}
			
			$list = Strapello::lists($card['idList']);
			
			if( !$status = Strapello::status($list['name']) )
			{
				continue;
			}
			
			$task = array();
			$task['id'] = $card['id'];
			$task['name'] = $card['name'];
			$task['shortname'] = truncate($card['name'], 35);
			$task['description'] = $card['desc'];
			$task['status'] = $status;
			$task['list'] = $list;
			$task['url'] = $card['url'];
			$task['members'] = array();
			
			foreach( $card['idMembers'] as $member )
			{
				if( $member != $user['id'] )
				{
					$task['members'][] = Strapello::member($member);
				}
			}
			
			$tasks[$card['id']] = $task;
			
			// Hi everyone looking at me prototype with bad code!
			${$status}[] = $task;

			$boards[$card['idBoard']]['stats'][$status]++;
			$boards[$card['idBoard']]['stats']['total']++;
		}
		
		$total = array();
		$total['tasks'] = count($tasks);
		$total['done'] = count($done);
		$total['doing'] = count($inprogress);
		$total['todo'] = count($todo);
		
		static::$View->assign('total', $total);

		static::$View->assign('percent', array('done' => round(($total['done'] / $total['tasks']) * 100, 2), 'doing' => round(($total['doing'] / $total['tasks']) * 100, 2)));
		
		static::$View->assign('user', $userdata);
		static::$View->assign('boards', $boards);
		
		static::$View->assign('done', $done);
		static::$View->assign('next', $next);
		static::$View->assign('inprogress', $inprogress);
		static::$View->assign('todo', $todo);
		static::$View->assign('postponed', $postponed);
		
		static::$View->assign('API_COUNT', Trello::calls());
		
		static::$View->display('user_main.tpl');
	}
}
