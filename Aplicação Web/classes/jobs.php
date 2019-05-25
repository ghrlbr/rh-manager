<?php

require_once '../libraries/database.php';
require_once '../includes/database.php';

final class Jobs
{
	// Variables
	private $identifier;
	private $title;
	
	private $database;
	
	
	// Getters
	public function GetIdentifier()
	{
		return $this -> identifier;
	}
	public function GetTitle()
	{
		return $this -> title;
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
		$this -> database -> Query("SELECT * FROM `jobs` WHERE `identifier` = '$identifier'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> database -> Query("UPDATE `jobs` SET `$name`='$value' WHERE `identifier` = '$identifier'");
			
			return true;
		}
		else
		{
			throw new Exception('Este cargo não existe');
		}
	}
	public function Delete($identifier)
	{
		$this -> database -> Query("SELECT * FROM `jobs` WHERE `identifier` = '$identifier'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> database -> Query("DELETE FROM `jobs` WHERE `identifier` = '$identifier'");
			
			return true;
		}
		else
		{
			throw new Exception('Este cargo não existe');
		}
	}
	
	public function Add(string $title)
	{
		$this -> database -> Query("SELECT * FROM `jobs` WHERE `title` = '$title'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			throw new Exception('Este cargo já existe');
		}
		else
		{
			$this -> database -> Query("INSERT INTO `jobs`(`title`) VALUES ('$title')");
		}
	}
	
	public function GetAll()
	{
		$this -> database -> Query("SELECT `identifier`,`title` FROM `jobs`");
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
		$this -> database -> Query("SELECT `identifier`,`title` FROM `jobs` WHERE `identifier` LIKE '$query%' OR `title` LIKE '$query%'");
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
		$this -> database -> Query("SELECT * FROM `jobs` WHERE `identifier` = '$identifier'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> identifier = $response[0]['identifier'];
			$this -> title = $response[0]['title'];
			
			return true;
		}
		else
		{
			throw new Exception('Cargo não encontrado');
		}
	}
	public function GetByTitle($title)
	{
		$this -> database -> Query("SELECT * FROM `jobs` WHERE `title` = '$title'");
		$response = $this -> database -> GetQuery();
		
		if(count($response) >= 1)
		{
			$this -> identifier = $response[0]['identifier'];
			$this -> title = $response[0]['title'];
			
			return true;
		}
		else
		{
			throw new Exception('Cargo não encontrado');
		}
	}
}

?>