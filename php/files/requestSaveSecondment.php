<?php
	include_once (dirname(__FILE__).'/connect.php');
	
		$name_id=$_POST['nameid'];
		$fsaStatus=$_POST['fsaStatus'];
		$secondmentFrom=$_POST['secondmentFrom'];
		$DB=$dbi->getdbname();
		$query=
		"INSERT INTO `".$DB."`.`education` (`id`, `name_id`, `start_date`, `end_date`, `qualification`, `institution`, `speciality`, `division_grade`) VALUES('',$name_id,'$startDate','$endDate','$qualification','$institution','$speciality','$divGrade')";
	//$educationID=$dbi->insertQuery($query);
		
/* FOR TESTING PURPOSE ONLY*/	
	/*
$string="testing: \n $fsaStatus";
$fh = fopen('test.txt', "w"); 
fwrite($fh, $string); 
fclose($fh); 
*/
	
?>