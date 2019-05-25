<?php

#	Name: Uri
#	Description: 
#	Author: Gabriel Henrique
#	Username: @ghrlbr
#	Date: October/2018
#	License: BSD


final class Uri
{
	const REQUEST_URI_NOT_SET = 10;
	const REQUEST_URI_WITHOUT_VALUE = 20;
	const REQUEST_URI_WRONG_FORMAT = 30;
	
	
	private $parameters = null;
	private $resourceType = null;
	
	
	public function GetParameters()
	{
		return $this -> parameters;
	}
	public function GetParameterByIndex(int $index)
	{
		if(isset($this -> parameters[$index]))
		{
			return $this -> parameters[$index];
		}
		else
		{
			return null;
		}
	}
	public function GetResourceType()
	{
		return $this -> resourceType;
	}
	
	
	public function Parse(string $uri)
	{
		if(isset($uri))
		{
			if(!empty($uri))
			{
				if(filter_var('http://localhost/' . $uri, FILTER_VALIDATE_URL))
				{
					$requestUri = $uri;
					$explodedRequestUri = explode('/', $requestUri);
					$filteredRequestUri = array_filter($explodedRequestUri);
					$reindexedRequesturi = array_values($filteredRequestUri);
					
					for($i = 0; $i < count($reindexedRequesturi); $i++)
					{
						$reindexedRequesturi[$i] = utf8_encode($reindexedRequesturi[$i]);
					}
					
					if(count($reindexedRequesturi) > 0)
					{
						$this -> parameters = $reindexedRequesturi;
						$this -> resourceType = $reindexedRequesturi[0];
					}
					
					return true;
				}
				else
				{
					throw new Exception('The requested URI format is not correct.', self::REQUEST_URI_WRONG_FORMAT);
				}
			}
			else
			{
				throw new Exception('The variable $_SERVER[\'REQUEST_URI\'] was set, but it has not value.', self::REQUEST_URI_WITHOUT_VALUE);
			}
		}
		else
		{
			throw new Exception('The variable $_SERVER[\'REQUEST_URI\'] was not set.', self::REQUEST_URI_NOT_SET);
		}
	}
}

?>