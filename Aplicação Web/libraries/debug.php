<?php

#	Name: Debug
#	Description: It allow the programmer debug your application while develop
#	Author: Gabriel Henrique
#	Username: @ghrlbr
#	Date: October/2018
#	License: BSD


final class Debug
{
	const DEVELOPMENT_MODE = 10;
	const PRODUCTION_MODE = 20;
	
	
	private $report = null;
	private $mode = self::PRODUCTION_MODE;
	
	
	public function SetMode(int $mode)
	{
		switch($mode)
		{
			case self::PRODUCTION_MODE:
			
				$this -> mode = self::PRODUCTION_MODE;
			
				break;
				
			case self::DEVELOPMENT_MODE:
			
				$this -> mode = self::DEVELOPMENT_MODE;
			
				break;
				
			default:
			
				$this -> mode = self::PRODUCTION_MODE;
			
				break;
		}
	}
	
	
	public function GetReport()
	{
		return $this -> report;
	}
	
	
	public function Debug($status = null, $code = null, $description = null, $data = null)
	{		
		if(!empty($status) && !empty($code) && !empty($description))
		{
			$this -> WriteInScreen($status, $code, $description, $data);
			
			return true;
		}
		
		return true;
	}
	public function MountReport($status, $code, $description, $data = null)
	{
		$header = array('status' => strtoupper($status),
						'code' => strtoupper($code),
						'description' => $description,
						'warnings' => $this -> warnings);
		$body = $data;
		
		$reportAsArray = array('header' => $header, 'body' => $body);
		$reportEncodedAsJson = json_encode($reportAsArray);
		
		$this -> report = $responseEncodedAsJson;
		
		return true;
	}
	public function WriteInScreen($status, $code, $description, $data = null)
	{
		$this -> MountReport($status, $code, $description, $data);
		
		echo $this -> report;
		
		return true;
	}
	public function WriteInConsole(string $status, string $message)
	{		
		switch($this -> mode)
		{
			case self::PRODUCTION_MODE:
			
				return false;
			
				break;
				
			case self::DEVELOPMENT_MODE:
			
				switch(strtoupper($status))
				{
					case 'ERROR':
					
						echo '<script type="text/javascript">';
						echo 'console.error("' . $message . '");';
						echo '</script>';
					
						break;
						
					case 'WARN':
					
						echo '<script type="text/javascript">';
						echo 'console.warn("' . $message . '");';
						echo '</script>';
						
						break;
						
					case 'LOG':
						
						echo '<script type="text/javascript">';
						echo 'console.log("' . $message . '");';
						echo '</script>';
					
						break;
						
					default:
					
						echo '<script type="text/javascript">';
						echo 'console.log("' . $message . '");';
						echo '</script>';
					
						break;
				}
			
				break;
				
			default:
			
				return false;
			
				break;
		}
		
		return true;
	}
}

?>