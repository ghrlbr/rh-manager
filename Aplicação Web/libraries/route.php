<?php 

$_DELETE;
$_PUT;


final class Route 
{
	// Variables
	private $onGet;
	private $onPost;
	private $onPut;
	private $onDelete;
	private $onOther;


	// Settlers 
	public function SetGet(string $function)
	{
		$this -> onGet = $function;
	}
	public function SetPost(string $function)
	{
		$this -> onPost = $function;
	}
	public function SetPut(string $function)
	{
		$this -> onPut = $function;
	}
	public function SetDelete(string $function)
	{
		$this -> onDelete = $function;
	}
	public function SetOther(string $function)
	{
		$this -> onOther = $function;
	}
	
	
	// Actions
	public function Redirect()
	{
		global $_DELETE;
		global $_PUT;
		
		$requestedMethod = $_SERVER['REQUEST_METHOD'];
		$requestedMethod = strtoupper($requestedMethod);
		
		switch($requestedMethod)
		{
			case 'GET':
			
				if(empty($this -> onGet))
				{
					return false;
				}
				else
				{
					if(function_exists($this -> onGet))
					{
						call_user_func($this -> onGet);
					}
					else
					{
						return false;
					}
				}
			
				break;
				
			case 'POST':
			
				if(empty($this -> onPost))
				{
					return false;
				}
				else
				{
					if(function_exists($this -> onPost))
					{
						call_user_func($this -> onPost);
					}
					else
					{
						return false;
					}
				}
			
				break;
				
			case 'PUT':
			
				if(empty($this -> onPut))
				{
					return false;
				}
				else
				{
					if(function_exists($this -> onPut))
					{
						parse_str(file_get_contents("php://input"), $_PUT);
						call_user_func($this -> onPut);
					}
					else
					{
						return false;
					}
				}
			
				break;
				
			case 'DELETE':
			
				if(empty($this -> onDelete))
				{
					return false;
				}
				else
				{
					if(function_exists($this -> onDelete))
					{
						parse_str(file_get_contents("php://input"), $_DELETE);
						call_user_func($this -> onDelete);
					}
					else
					{
						return false;
					}
				}
			
				break;
				
			default:
			
				if(empty($this -> onOther))
				{
					return false;
				}
				else
				{
					if(function_exists($this -> onOther))
					{
						call_user_func($this -> onOther);
					}
					else
					{
						return false;
					}
				}
			
				break;
		}
	}
}

?>