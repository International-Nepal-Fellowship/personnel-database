<?php include_once (dirname(__FILE__).'/connect.php');
	
	set_error_handler("myErrorHandler");	
		
	
	
	$casteName=$_POST['name'];
	$tableName=$_POST['tablename'];
	
	switch($tableName){
	case 'caste':
		//insert into caste table
		$query = sprintf("INSERT INTO $tableName"."(id,name) VALUES ('','$casteName')");	
		break;	
	}
		$dbi->insertQuery($query);
		$dbi->disconnect();		

/* FOR TESTING PURPOSE ONLY*/			
/*
$fh = fopen('test.txt', "w"); 
fwrite($fh, $query); 
fclose($fh); 
*/
		
?>	