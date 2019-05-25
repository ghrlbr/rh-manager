<?php

require_once '../libraries/database.php';
require_once '../includes/database.php';

final class Workers
{
	// Variables
	private $identifier;
	private $name;
	private $job;
	
	private $database;
	
	
	// Getters
	public function GetIdentifier()
	{
		return $this -> identifier;
	}
	public function GetName()
	{
		return $this -> name;
	}
	public function GetJob()
	{
		return $this -> job;
	}
	
	
	// Actions
	public function __construct()
	{
		$this -> database = new Database();
		$this -> database -> SetHost(DATABASE_HOST);
		$this -> database -> SetUsername(DATABASE_USERNAME);
		$this -> database -> SetPassword(DATABASE_PASSWORD);
		$this -> database -> SetName(DATABASE_NAME);
		
		try
		{
			$this -> database -> Connect();
		}
		catch(Exception $e)
		{
			throw new Exception('Ocorreu um erro ao se conectar ao servidor');
		}
	}
	
	public function Put($identifier, $name, $value)
	{
		$this -> database -> Query("SELECT * FROM `workers` WHERE `identifier` = '$identifier'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> database -> Query("UPDATE `workers` SET `$name`='$value' WHERE `identifier` = '$identifier'");
			
			return true;
		}
		else
		{
			throw new Exception('Este funcionário não existe');
		}
	}
	public function Delete($identifier)
	{
		$this -> database -> Query("SELECT * FROM `workers` WHERE `identifier` = '$identifier'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> database -> Query("DELETE FROM `workers` WHERE `identifier` = '$identifier'");
			
			return true;
		}
		else
		{
			throw new Exception('Este funcionário não existe');
		}
	}
	
	public function Add($name, $job)
	{
		$this -> database -> Query("SELECT * FROM `workers` WHERE `name` = '$name'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			throw new Exception('Funcionário já existente');
		}
		else
		{
			$this -> database -> Query("INSERT INTO `workers`(`name`, `job`) VALUES ('$name', '$job')");
		}
	}
	
	public function GetAll()
	{
		$this -> database -> Query("SELECT * FROM `workers`");
		$response = $this -> database -> GetQuery();
			
		if(count($response) >= 1)
		{
			return $response;
		}
		else
		{
			return array();
		}
	}
	public function GetBySearch($query)
	{
		$this -> database -> Query("SELECT * FROM `workers` WHERE `job` LIKE '$query%' OR `name` LIKE '$query%'");
		$response = $this -> database -> GetQuery();
			
		if(count($response) >= 1)
		{
			return $response;
		}
		else
		{
			return array();
		}
	}
	public function GetByIdentifier($identifier)
	{
		$this -> database -> Query("SELECT * FROM `workers` WHERE `identifier` = '$identifier'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> identifier = $response[0]['identifier'];
			$this -> name = $response[0]['name'];
			$this -> job = $response[0]['job'];
			
			return true;
		}
		else
		{
			throw new Exception('Funcionário não encontrado');
		}
	}
	public function GetByName($name)
	{
		$this -> database -> Query("SELECT * FROM `workers` WHERE `name` = '$name'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> identifier = $response[0]['identifier'];
			$this -> name = $response[0]['name'];
			$this -> job = $response[0]['job'];
			
			return true;
		}
		else
		{
			throw new Exception('Funcionário não encontrado');
		}
	}
}

?>