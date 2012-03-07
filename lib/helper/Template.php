<?php
/**
 * Template parsing
 * 
 * @package Framework
 * @subpackge View
 * @author Marco Ceppi <marco@ceppi.net>
 */

require_once('vendor/Smarty/Smarty.class.php');

class Template extends Smarty
{
	public function append($key, $value)
	{
		$existing_value = $this->getTemplateVars($key);
		
		if( is_array($existing_value) )
		{
			array_push($existing_value, $value);
			
			$this->assign($key, $existing_value);
		}
	}
}

// I've been doing this wrong for a long time. Thanks Stefano!

define('TEMPLATE_JSON', 0);
define('TEMPLATE_XML', 1);
define('TEMPLATE_PHP', 2); // Please don't ever use this.

function display( $out, $type = TEMPLATE_JSON )
{
	switch( $type )
	{
		case TEMPLATE_PHP:
			if( is_array($out) )
			{
				die(serialize($out));
			}
			else
			{
				die($out);
			}
		break;
		case TEMPLATE_JSON:
		default:
			if( is_array($out) )
			{
				header('Cache-Control: no-cache, must-revalidate');
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
				header('Content-type: application/json');
				
				die(json_encode($out));
			}
			else
			{
				return false;
			}
		break;
	}
}

