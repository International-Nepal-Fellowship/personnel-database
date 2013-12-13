<?php  
    include_once (dirname(__FILE__).'/connect.php'); 
	//include_once (dirname(__FILE__).'/session.php');
	close_session();
	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);

/*	no longer needed
	$userID=$_POST['userID']; 
    $dir = "../main/fileUploads/$userID";  	
    $dbi->removeUserTempDir($dir);
	$dir = "../main/backups/$userID";  	
    $dbi->removeUserTempDir($dir);
    */
	//$dbi->test_file('logout',"\t session name: ".$_SESSION['name']);
?>
