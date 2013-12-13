<?php

    header('Cache-Control: no-cache, must-revalidate');
	include_once (dirname(__FILE__).'/session.php');
	//session_check();
	session_check(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1));//parameter gives the current page name
	include_once (dirname(__FILE__).'/SussolLibrary.php');    
	
	$dbi=new SussolLibrary();
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
	
	if(!$dbi->isConnected()) {
		$dbi->connect_default();
		$query1='SET @@auto_increment_offset='.$dbi->getSiteID();
		$result=mysql_query($query1);
	}
		
?>