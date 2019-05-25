<?php


require_once '../libraries/route.php';
require_once '../libraries/response.php';
require_once '../classes/jobs.php';


$route = new Route();
$route -> SetGet('OnGet');
$route -> SetPost('OnPost');
$route -> SetPut('OnPut');
$route -> SetDelete('OnDelete');
$route -> Redirect();


function OnPut()
{	
	global $_PUT;
	
	$response = new Response();
	
	if(isset($_PUT['identifier']))
	{
		if(isset($_PUT['name']))
		{
			if($_PUT['name'] == 'identifier')
			{
				$response -> Mount('ERROR', 'Não é possível alterar o identificador do cargo');
				$response -> WriteInScreen();
			}
			else
			{
				if(isset($_PUT['value']))
				{
					// Edit by identifier
					$jobs = new Jobs();
					
					try
					{
						$jobs -> Put($_PUT['identifier'], $_PUT['name'], $_PUT['value']);
						
						$response -> Mount('SUCCESS', 'Informações alteradas');
						$response -> WriteInScreen();
					}
					catch(Exception $e)
					{
						$response -> Mount('ERROR', $e -> getMessage());
						$response -> WriteInScreen();
					}
				}
				else
				{
					$response -> Mount('ERROR', 'Você precisa definir o novo valor da informação para editar');
					$response -> WriteInScreen();
				}
			}
		}
		else
		{
			$response -> Mount('ERROR', 'Você precisa definir a informação que quer editar');
			$response -> WriteInScreen();
		}
	}
	else
	{
		$response -> Mount('ERROR', 'Você precisa definir um identificador do cargo');
		$response -> WriteInScreen();
	}
}
function OnGet()
{
	if(isset($_GET['identifier']))
	{
		// Get by identifier
		$jobs = new Jobs();
		$response = new Response();
		
		try
		{
			$jobs -> GetByIdentifier($_GET['identifier']);
			
			$data = array(
				'identifier' => $jobs -> GetIdentifier(),
				'title' => $jobs -> GetTitle()
			);
			
			$response -> Mount('SUCCESS', 'Cargo encontrado', $data);
			$response -> WriteInScreen();
		}
		catch(Exception $e)
		{
			$response -> Mount('ERROR', $e -> getMessage());
			$response -> WriteInScreen();
		}
	}
	else
	{
		if(isset($_GET['title']))
		{
			// Get by title
			$jobs = new Jobs();
			$response = new Response();
			
			try
			{
				$jobs -> GetByTitle($_GET['title']);
				
				$data = array(
					'identifier' => $jobs -> GetIdentifier(),
					'title' => $jobs -> GetTitle()
				);
				
				$response -> Mount('SUCCESS', 'Cargo encontrado', $data);
				$response -> WriteInScreen();
			}
			catch(Exception $e)
			{
				$response -> Mount('ERROR', $e -> getMessage());
				$response -> WriteInScreen();
			}
		}
		else
		{
			if(isset($_GET['query']))
			{
				// Get by search
				$jobs = new Jobs();
				$response = new Response();
				
				try
				{
					$allJobs = $jobs -> GetBySearch($_GET['query']);
					$data = array();
					
					for($i = 0; $i < count($allJobs); $i++)
					{
						$job = array(
							'identifier' => $allJobs[$i]['identifier'],
							'title' => $allJobs[$i]['title']
						);
						
						array_push($data, $job);
					}
					
					$response -> Mount('SUCCESS', 'Cargos', $data);
					$response -> WriteInScreen();
				}
				catch(Exception $e)
				{
					$response -> Mount('ERROR', $e -> getMessage());
					$response -> WriteInScreen();
				}
			}
			else
			{
				// Get all
				$jobs = new Jobs();
				$response = new Response();
				
				try
				{
					$allJobs = $jobs -> GetAll();
					$data = array();
					
					for($i = 0; $i < count($allJobs); $i++)
					{
						$job = array(
							'identifier' => $allJobs[$i]['identifier'],
							'title' => $allJobs[$i]['title']
						);
						
						array_push($data, $job);
					}
					
					$response -> Mount('SUCCESS', 'Cargos', $data);
					$response -> WriteInScreen();
				}
				catch(Exception $e)
				{
					$response -> Mount('ERROR', $e -> getMessage());
					$response -> WriteInScreen();
				}
			}
		}
	}
}
function OnDelete()
{
	global $_DELETE;
	
	$response = new Response();
	
	if(isset($_DELETE['identifier']))
	{
		// Delete by identifier
		$jobs = new Jobs();
		
		try
		{
			$jobs -> Delete($_DELETE['identifier']);
			
			$response -> Mount('SUCCESS', 'Cargo deletado');
			$response -> WriteInScreen();
		}
		catch(Exception $e)
		{
			$response -> Mount('ERROR', $e -> getMessage());
			$response -> WriteInScreen();
		}
	}
	else
	{
		$response -> Mount('ERROR', 'Você precisa definir um identificador do cargo');
		$response -> WriteInScreen();
	}
}
function OnPost()
{
	$response = new Response();
	
	
	if(isset($_POST['title']))
	{
		if(empty($_POST['title']))
		{
				$response -> Mount('ERROR', 'O nome do cargo não pode ser vazio');
				$response -> WriteInScreen();
		}
		else
		{
			$title = $_POST['title'];
			
			$jobs = new Jobs();
		
			try
			{
				$jobs -> Add($title);
				
				$response -> Mount('SUCCESS', 'Cargo adicionado');
				$response -> WriteInScreen();
			}
			catch(Exception $e)
			{
				$response -> Mount('ERROR', $e -> getMessage());
				$response -> WriteInScreen();
			}
		}
	}
	else
	{
		$response -> Mount('ERROR', 'O nome do cargo precisa ser definido');
		$response -> WriteInScreen();
	}
}

?>