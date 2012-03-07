<?php

define('IN_APP', true);

require_once('inc/config.inc.php');


$statuses = array('done' => 'done', 'todo' => 'todo', 'to do' => 'todo', 'doing' => 'inprogress', 'done' => 'done', 'finished' => 'done', 'next' => 'next', 'hold' => 'postponed', 'on hold' => 'postponed', 'in progress' => 'inprogress');

function truncate($string, $limit, $break = ' ', $pad = '...')
{
	if(strlen($string) <= $limit) return $string;

	if(($breakpoint = strpos($string, $break, $limit)) !== false)
	{
		if($breakpoint < strlen($string) - 1)
		{
			$string = substr($string, 0, $breakpoint) . $pad;
		}
    }
	
	return $string;
}

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

function getStatus($name)
{
	global $statuses;
	$name = preg_replace('/[^a-zA-Z0-9 ]/', '', strtolower($name));
	return (array_key_exists($name, $statuses)) ? $statuses[$name] : DEFAULT_STATUS;
}

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
	
	$task = array();
	$task['name'] = $card['name'];
	$task['description'] = $card['desc'];
	$task['status'] = getStatus($lists[$card['idList']]);
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
	
	$status = $task['status'];
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

require_once('tpl/user_breakdown.tpl');
//echo "<pre>";
//var_dump($api_count);
//var_dump($inprogress);
//var_dump($tasks);
//var_dump($lists);
//var_dump($boards);
