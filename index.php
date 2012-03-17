<?php

/**
 * Main router
 *
 * So many people have frameworks out there, including one I built 
 * do all this jazz. But! WE'RE PROTOTYPING ON THE FLY GOGOGO. 
 * Proof of Concept <3. This should all be wrapped in a Router to handle
 * this kind of magic.
 */

define('IN_APP', true);

require_once('lib/common.php');

$raw_route = (!empty($_GET['__r'])) ? $_GET['__r'] : 'main';

$routes = explode('/', $raw_route);
$route = array_shift($routes);

if( file_exists('app/' . $route . '.php') )
{
	require_once('app/' . $route . '.php');
	
	// There might be a better way to do this.
	$route::$View = $View;
	$method = (!empty($routes)) ? array_shift($routes) : 'init';
	
	try
	{
		$route_test = new ReflectionMethod($route, $method);
	}
	catch( Exception $e )
	{
		$route::init($method);
	}
	
	$route::$method($routes);
}
else
{
	die('Lost?');
}
