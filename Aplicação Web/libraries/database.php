<?php

final class Database
{
	private $username;
	private $password;
	private $host;
	private $name;
	
	private $connection;
	private $query;
	
	public function SetUsername($value)
	{
		$this -> username = $value;
	}
	public function SetPassword($value)
	{
		$this -> password = $value;
	}
	public function SetHost($value)
	{
		$this -> host = $value;
	}
	public function SetName($value)
	{
		$this -> name = $value;
	}
	
	public function GetQuery($fetchBefore = true, $type = 'ASSOC')
	{
		if($fetchBefore == true)
		{
			switch(strtoupper($type))
			{
				case 'ASSOC':
				
					return $this -> query -> fetchAll(PDO::FETCH_ASSOC);
				
					break;
					
				case 'OBJ':
				
					return $this -> query -> fetchAll(PDO::FETCH_OBJ);
				
					break;
					
				default:
				
					return $this -> query -> fetchAll(PDO::FETCH_OBJ);
				
					break;
			}
		}
		else
		{
			return $this -> query;
		}
	}
	
	public function Connect()
	{
		try
		{
			$this -> connection = new PDO("mysql:host={$this -> host};dbname={$this -> name}", $this -> username, $this -> password, array(PDO::ATTR_PERSISTENT => true));
		}
		catch(Exception $exception)
		{
			throw new Exception('Internal error when try to connect to database', 0, $exception);
		}
	}
	public function Query($sql)
	{
		try
		{
			$this -> query = $this -> connection -> query($sql);
		}
		catch(Exception $exception)
		{
			throw new Exception('Internal error when try to query database', 0, $exception);
		}
	}
}

?>