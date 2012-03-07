<?php

define('APPLICATION_ROOT', dirname(__FILE__) . '/../');

set_include_path(get_include_path() . PATH_SEPARATOR . APPLICATION_ROOT . 'lib');

require_once('helper/Template.php');
require_once('helper/App.php');

require_once('model/Strapello.php');

$View = new Template();
$View->compile_dir = APPLICATION_ROOT . 'views/_cache';
$View->template_dir = APPLICATION_ROOT . 'views';

function truncate($string, $limit, $pad = '&hellip;')
{
	if(strlen($string) <= $limit) return $string;

	/*
	if(($breakpoint = strpos($string, $break, $limit)) !== false)
	{
		if($breakpoint < strlen($string) - 1)
		{
			$string = substr($string, 0, $breakpoint) . $pad;
		}
    }
    */
    
    $string = substr($string, 0, $limit) . $pad;
	
	return $string;
}
