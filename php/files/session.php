<?php
	@session_start();
	function session_check($fileName='')
	{
		//$fh = fopen('ztest_session.txt', "a"); 
		//fwrite($fh, "\n".$fileName." : session name: ". $_SESSION["name"]); 
		//fclose($fh);
        
        if(($_SESSION["name"]!='') || ($fileName == "upload.php"))
		{
			
		}
		else
		{		
            $http = "http://";
            if (($_SERVER['HTTPS'] != '') && ($_SERVER['HTTPS'] != 'off')) $http = "https://";
        
            @header("Location:".$http.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/../main/inf.html');
            //$fh = fopen('ztest_session.txt', "a"); 
            //fwrite($fh, "\nRedirected to login page."); 
            //fclose($fh);
            die("Not logged in\n");
		}			
	}
	
	function close_session()
	{
		$_SESSION["name"]="";
		$_SESSION["role"]="";
		//$_SESSION["check_session"]='';
		$_SESSION["login"]='';
		session_unset();
		session_destroy();
		//logout information
	}
?>
