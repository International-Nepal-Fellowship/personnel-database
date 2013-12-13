<?php

    header('Cache-Control: no-cache, must-revalidate');
	include_once (dirname(__FILE__).'/session.php');
	//session_check();
	include_once (dirname(__FILE__).'/AuthLibrary.php');    

	$dbiAuth=new AuthLibrary();
	//set_error_handler("myErrorHandler");			
	
		
	function myErrorHandler($errno, $errstr, $errfile, $errline){
		switch ($errno) {
		case	E_USER_ERROR:
			$errorVal	.= "<errors><error>My ERROR[$errno] $errstr";
			$errorVal	.= "  Fatal error on line $errline in file $errfile";
			$errorVal	.= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")";
			$errorVal	.= "Aborting...</error></errors>";
			
		case E_USER_WARNING:
			$errorVal	.= "<errors><error>My WARNING[$errno] $errstr</error></errors>";

		case E_USER_NOTICE:
			$errorVal	.= "<errors><error>My NOTICE [$errno] $errstr</error></errors>";

        case E_STRICT:
            return; //ignore

		default:
			$errorVal	.= "<errors><error>Unknown error type: [$errno] $errstr on line $errline in file $errfile</error></errors>";
      
		}	
		print $errorVal;
		exit;
		
	}	
	
	if(!$dbiAuth->isConnected())
		$dbiAuth->connect_default();	

		

		
?>