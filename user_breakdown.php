<?php

require_once('inc/config.inc.php');


/**
 * These should be made into a methods of a Strapello class
 */
function getList($list_id)
{
	return json_decode(callAPI('https://api.trello.com/1/lists/' . $list_id . '?key=' . PUBLIC_KEY), true);
}

function getBoard($board_id)
{
	return json_decode(callAPI('https://api.trello.com/1/boards/' . $board_id . '?fields=name,url&key=' . PUBLIC_KEY), true);
}

function getOrg($org_id)
{
	
}

function getUser($user)
{
	return json_decode(callAPI('https://api.trello.com/1/members/' . $user . '?key=' . PUBLIC_KEY), true);
}

function getCards($user)
{
	return json_decode(callAPI('https://api.trello.com/1/members/' . $user . '/cards?key=' . PUBLIC_KEY), true);
}

function getChanges($user, $cards = null)
{
	$raw_changes = json_decode(callAPI('https://api.trello.com/1/members/' . $user . '/actions?limit=1000&filter=updateCard:idList&key=' . PUBLIC_KEY), true);
	
	if( is_null($cards) )
	{
		die('nope.');
	}

	$now = date('Y-m-d');
	$changes = array($now => array());

	foreach($cards as $card)
	{
		$changes[$now][$card['id']] = $card['status'];
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
	foreach( $raw_changes as $change )
	{
		$change_time = date('Y-m-d', strtotime($change['date']));
		
		// Lets see if we've done this day yet, if not we'll need to first process the previous day's 
		// data, then start a new bean counter
		if( !array_key_exists($change_time, $changes) )
		{
			$next_day = date('Y-m-d', strtotime('-1 day', strtotime($last_change_date)));
			for( $i = strtotime($change_time); $i <= strtotime($next_day); $next_day = date('Y-m-d', strtotime('-1 day', strtotime($next_day))) )
			{
				$actions[$last_change_date] = array();
				// Look at how lazy I am!
				$next_day = date('Y-m-d', strtotime('-1 day', strtotime($last_change_date)));
				// Process other day's stats
				foreach( $changes[$last_change_date] as $card_id => $status )
				{
					$actions[$last_change_date][$status]++;
					$actions[$last_change_date]['total']++;
				}
				
				$changes[$next_day] = $changes[$last_change_date];
				$last_change_date = $next_day;
			}
		}
		
		if( !$status = getStatus($change['data']['listAfter']['name']) )
		{
			continue;
		}
		
		$changes[$change_time][$change['data']['card']['id']] = $status;
		$last_change_date = $change_time;
	}
	
	return $actions;
}

function getStatus($name)
{
	global $statuses;
	$name = preg_replace('/[^a-zA-Z0-9 ]/', '', strtolower($name));
	return (array_key_exists($name, $statuses)) ? $statuses[$name] : DEFAULT_STATUS;
}

// This should be a Trello class method
function callAPI($url)
{
	global $api_count;
	
	$api_count++;
	return file_get_contents($url);
}

$api_count = 0;
$orgs = array();
$boards = array();
$lists = array();
$tasks = array();
$members = array();
$actions = array();

$done = array();
$todo = array();
$next = array();
$inprogress = array();
$postponed = array();

$user = $_GET['user'];
$user = getUser($user);

$cards = getCards($user['id']);

foreach($cards as $card)
{
	if( !array_key_exists($card['idBoard'], $boards) )
	{
		$boards[$card['idBoard']] = array('data' => getBoard($card['idBoard']), 'stats' => array('inprogress' => 0, 'todo' => 0, 'done' => 0, 'total' => 0));
	}
	
	if( !array_key_exists($card['idList'], $lists) )
	{
		$list = getList($card['idList']);
		$lists[$card['idList']] = $list['name'];
	}
	
	if( !$status = getStatus($lists[$card['idList']]) )
	{
		continue;
	}
	
	$task = array();
	$task['id'] = $card['id'];
	$task['name'] = $card['name'];
	$task['description'] = $card['desc'];
	$task['status'] = $status;
	$task['board'] = $lists[$card['idList']];
	$task['url'] = $card['url'];
	$task['members'] = array();
	
	foreach( $card['idMembers'] as $member )
	{
		if( $member != $user['id'] )
		{
			if( !array_key_exists($member, $members) )
			{
				$members[$member] = getUser($member);
			}
			
			$task['members'][] = $members[$member];
		}
	}
	
	$tasks[$card['id']] = $task;
	
	// Hi everyone looking at me prototype with bad code!
	${$status}[] = $task;

	$boards[$card['idBoard']]['stats'][$status]++;
	$boards[$card['idBoard']]['stats']['total']++;
}

$total = count($tasks);
$total_done = count($done);
$doing = count($inprogress);

$percent_done = round(($total_done / $total) * 100, 2);
$percent_doing = round(($doing / $total) * 100, 2);


$changes = getChanges($user['id'], $tasks);

require_once('tpl/user_breakdown.tpl');
//echo "<pre>";
//var_dump($api_count);
//var_dump($inprogress);
//var_dump($tasks);
//var_dump($lists);
//var_dump($boards);
//var_dump($changes);
