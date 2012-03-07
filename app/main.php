<?php

if( !defined('IN_APP') ) { die('DANGE WILL ROBINSON'); }

class main extends App
{
	public static function init()
	{
		//echo "Hello world!";
		static::$View->assign('TEST', 'Welcome to the Jungle');
		static::$View->display('test.tpl');
	}
}
