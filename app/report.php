<?php

if( !defined('IN_APP') ) { die('DANGE WILL ROBINSON'); }

class report extends App
{
	public static function init()
	{
		// Show help screen
		die('Sorry, what?');
		//static::$View->display('user_main.tpl');
	}
	
	public static function board($board_id)
	{
		$board_id = array_shift($board_id);
		$board = Strapello::board($board_id);
		$cards = Strapello::cards('boards', $board['id']);
		
		$members = array();
		$tasks = array();

		$done = array();
		$todo = array();
		$next = array();
		$inprogress = array();
		$postponed = array();

		$lists = Strapello::lists('boards', $board['id']);
		
		foreach($cards as $card)
		{
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
				$task['members'][] = Strapello::member($member);
				
				if( !array_key_exists($member, $members) )
				{
					$members[$member] = array('data' => Strapello::member($member), 'stats' => array('todo' => 0, 'next' => 0, 'inprogress' => 0, 'done' => 0, 'total' => 0));
				}
				
				$members[$member]['stats'][$status]++;
				$members[$member]['stats']['total']++;
			}
			
			$tasks[$card['id']] = $task;
			${$status}[] = $task;
			
			if( empty($task['members']) )
			{
				$unassigned[] = $task;
			}
		}
		
		$total = array();
		$total['tasks'] = count($tasks);
		$total['done'] = count($done);
		$total['doing'] = count($inprogress);
		$total['todo'] = count($todo);
		
		$chart_data = array();
		$changes = Strapello::changes('boards', $board['id'], $tasks);
		
		end($changes);
		$chart_data['first_key'] = key($changes);
		$first_row = current($changes);
		$chart_data['total'] = $first_row['total'];
		reset($changes);
		
		static::$View->assign('chart_data', $chart_data);
		static::$View->assign('changes', $changes);
		$js = static::$View->fetch('card_burndown.js.tpl');
		static::$View->assign('JS', $js);
		
		static::$View->assign('total', $total);
		static::$View->assign('members', $members);
		static::$View->assign('board', $board);

		static::$View->assign('percent', array('done' => round(($total['done'] / $total['tasks']) * 100, 2), 'doing' => round(($total['doing'] / $total['tasks']) * 100, 2)));
		
		static::$View->assign('lists', $lists);
		
		static::$View->assign('done', $done);
		static::$View->assign('next', $next);
		static::$View->assign('inprogress', $inprogress);
		static::$View->assign('todo', $todo);
		static::$View->assign('postponed', $postponed);
		static::$View->assign('unassigned', $unassigned);
		
		static::$View->assign('API_COUNT', Trello::calls());
		
		static::$View->display('report_board.tpl');
	}
}
