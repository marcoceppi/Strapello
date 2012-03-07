<?php

/**
 * Main router
 *
 * So many people have frameworks out there, including one I built to
 * do all this jazz. But! WE'RE PROTOTYPING ON THE FLY GOGOGO. Proof of Concept <3
 */
 
$raw_route = (!empty($_GET['__r'])) ? $_GET['__r'] : 'main';

$routes = explode('/', $raw_route);
$route = array_shift($routes);

if( file_exists('app/' . $route . '.php') )
{
	require_once('app/' . $route . '.php');
	
	$method = (!empty($routes)) ? array_shift($routes) : 'init';
	$route::$method($routes);
}
else
{
	die('Lost?');
}
