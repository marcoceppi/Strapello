<?php

/**
 * Main router
 *
 * So many people have frameworks out there, including one I built to
 * do all this jazz. But! WE'RE PROTOTYPING ON THE FLY GOGOGO. Proof of Concept <3
 */

require_once('lib/common.php');

$raw_route = (!empty($_GET['__r'])) ? $_GET['__r'] : 'main';

$routes = explode('/', $raw_route);
$route = array_shift($routes);

if( file_exists('app/' . $route . '.php') )
{
	define('IN_APP', true);
	
	require_once('app/' . $route . '.php');
	
	// There might be a better way to do this.
	$route::$View = $View;
	$method = (!empty($routes)) ? array_shift($routes) : 'init';
	$route::$method($routes);
}
else
{
	die('Lost?');
}
