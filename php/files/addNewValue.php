<?php
include_once (dirname(__FILE__).'/connect.php');
		
		//$value=$_POST['value'] ;
		$table=$_POST['tableName'];
		
			/* FOR TESTING PURPOSE ONLY*/	

$string="testing: \n $table ";
$fh = fopen('test.txt', "w"); 
fwrite($fh, $string); 
fclose($fh); 
	

?>