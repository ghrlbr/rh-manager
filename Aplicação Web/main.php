<?php

require_once 'libraries/uri.php';
require_once 'libraries/debug.php';


$debug = new Debug();
$uri = new Uri();


$debug -> SetMode(DEBUG::PRODUCTION_MODE);


try
{
	$uri -> Parse($_SERVER['REQUEST_URI']);
}
catch(Exception $exception)
{
	switch($exception -> getCode())
	{
		case Uri::REQUEST_URI_WRONG_FORMAT:
		
			$debug -> WriteInConsole('ERROR', 'The client\'s request URI was set, but it has a wrong format.');
		
			break;
			
		case Uri::REQUEST_URI_WITHOUT_VALUE:
		
			$debug -> WriteInConsole('ERROR', 'The client\'s request URI was set, but it has no value.');
		
			break;
			
		case Uri::REQUEST_URI_NOT_SET:
		
			$debug -> WriteInConsole('ERROR', 'The client\'s request URI was not set.');
		
			break;
			
		default:
		
			$debug -> WriteInConsole('ERROR', 'It occurred an undefined error when try to parse the request URL.');
		
			break;
	}
}


if(empty($uri -> GetParameterByIndex(0)) == false)
{
	if(file_exists('views/structures/' . $uri -> GetParameterByIndex(0) . '.html'))
	{
		require_once 'views/structures/' . $uri -> GetParameterByIndex(0) . '.html';
	}
	else
	{
		echo 'Show 404.html';
	}
}
else
{
	require_once 'views/structures/index.html';
}

?>