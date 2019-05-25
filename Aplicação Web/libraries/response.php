<?php

final class Response
{
	private $status;
	private $message;
	private $data;
	
	
	private $contentToWrite;
	
	
	public function WriteInScreen()
	{
		header('content-type: application/json');
		
		echo $this -> contentToWrite;
		
		exit;
	}
	public function Mount(string $status, $message, $data = null)
	{
		$header = array(
			'status' => $status,
			'message' => $message
		);
		$body = $data;
		
		$content = $arrayedContent = array('header' => $header, 'body' => $body);
		$content = json_encode($arrayedContent);
		
		$this -> contentToWrite = $content;
	}
}

?>