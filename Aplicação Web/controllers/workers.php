<?php


require_once '../libraries/route.php';
require_once '../libraries/response.php';
require_once '../classes/workers.php';
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
	
	$workers = new Workers();
	$jobs = new Jobs();
	$response = new Response();
	
	if(isset($_PUT['identifier']))
	{
		if(isset($_PUT['name']))
		{
			if($_PUT['name'] == 'identifier')
			{
				$response -> Mount('ERROR', 'Não é possível alterar o identificador do funcionário');
				$response -> WriteInScreen();
			}
			else
			{
				if(isset($_PUT['value']))
				{
					// Edit by identifier
					try
					{
						$workers -> Put($_PUT['identifier'], $_PUT['name'], $_PUT['value']);
						
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
	$workers = new Workers();
	$jobs = new Jobs();
	$response = new Response();
		
	if(isset($_GET['identifier']))
	{
		// Get by identifier
		try
		{
			$workers -> GetByIdentifier($_GET['identifier']);
			$jobs -> GetByIdentifier($workers -> GetJob());
			
			$worker = array(
				'identifier' => $workers -> GetIdentifier(),
				'name' => $workers -> GetName(),
				'job' => array(
					'identifier' => $jobs -> GetIdentifier(),
					'title' => $jobs -> GetTitle()
				)
			);
			
			$response -> Mount('SUCCESS', 'Funcionário encontrado', $worker);
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
		if(isset($_GET['name']))
		{
			// Get by name			
			try
			{
				$allWorkers = $workers -> GetByName($_GET['name']);
				$jobs -> GetByIdentifier($workers -> GetJob());
					
				$worker = array(
					'identifier' => $workers -> GetIdentifier(),
					'name' => $workers -> GetName(),
					'job' => array(
						'identifier' => $jobs -> GetIdentifier(),
						'title' => $jobs -> GetTitle()
					)
				);
					
				$response -> Mount('SUCCESS', 'Funcionário adicionado com successo', $worker);
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
				try
				{
					$allWorkers = $workers -> GetBySearch($_GET['query']);
					$data = array();
					
					for($i = 0; $i < count($allWorkers); $i++)
					{
						$jobs -> GetByIdentifier($allWorkers[$i]['job']);
						
						$worker = array(
							'identifier' => $allWorkers[$i]['identifier'],
							'name' => $allWorkers[$i]['name'],
							'job' => array(
								'identifier' => $jobs -> GetIdentifier(),
								'title' => $jobs -> GetTitle()
							)
						);
						
						array_push($data, $worker);
					}
					
					$response -> Mount('SUCCESS', 'Funcionários', $data);
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
				try
				{
					$allWorkers = $workers -> GetAll();
					$data = array();
					
					for($i = 0; $i < count($allWorkers); $i++)
					{
						$jobs -> GetByIdentifier($allWorkers[$i]['job']);
						
						$worker = array(
							'identifier' => $allWorkers[$i]['identifier'],
							'name' => $allWorkers[$i]['name'],
							'job' => array(
								'identifier' => $jobs -> GetIdentifier(),
								'title' => $jobs -> GetTitle()
							)
						);
						
						array_push($data, $worker);
					}
					
					$response -> Mount('SUCCESS', 'Funcionários', $data);
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
	
	$workers = new Workers();
	$response = new Response();
	
	if(isset($_DELETE['identifier']))
	{
		// Delete by identifier
		try
		{
			$workers -> Delete($_DELETE['identifier']);
			
			$response -> Mount('SUCCESS', 'Funcionário deletado');
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
		$response -> Mount('ERROR', 'Você precisa definir um identificador do funcionário');
		$response -> WriteInScreen();
	}
}
function OnPost()
{
	$workers = new Workers();
	$response = new Response();
	
	
	if(isset($_POST['name']))
	{
		if(empty($_POST['name']))
		{
				$response -> Mount('ERROR', 'O nome do funcionário não pode ser vazio');
				$response -> WriteInScreen();
		}
		else
		{
			if(isset($_POST['job']))
			{
				if(empty($_POST['job']))
				{
					$response -> Mount('ERROR', 'O cargo do funcionário não pode ser vazio');
					$response -> WriteInScreen();
				}
				else
				{
					try
					{
						$workers -> Add($_POST['name'], $_POST['job']);
						
						$response -> Mount('SUCCESS', 'Funcionário adicionado');
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
				$response -> Mount('ERROR', 'O cargo do funcionário precisa ser definido');
				$response -> WriteInScreen();
			}
		}
	}
	else
	{
		$response -> Mount('ERROR', 'O nome do funcionário precisa ser definido');
		$response -> WriteInScreen();
	}
}

?>