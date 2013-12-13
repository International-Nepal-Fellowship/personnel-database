<?php
	include_once (dirname(__FILE__).'/connect.php');
	
		$name_id=$_POST['nameid'];
		$qualification=$_POST['qualification'];
		$startDate=$_POST['startDate'];
		$endDate=$_POST['endDate'];
		$divGrade=$_POST['eduDivGrade'];
		$institution=$_POST['eduInstitution'];
		$speciality=$_POST['eduSpeciality'];
		$DB=$dbi->getdbname();
		$query=
		"INSERT INTO `".$DB.".`education` (`id`, `name_id`, `start_date`, `end_date`, `qualification`, `institution`, `speciality`, `division_grade`) VALUES('',$name_id,'$startDate','$endDate','$qualification','$institution','$speciality','$divGrade')";
	$educationID=$dbi->insertQuery($query);
		
/* FOR TESTING PURPOSE ONLY*/	
/*		
$string="testing: \n $query";
$fh = fopen('test.txt', "w"); 
fwrite($fh, $string); 
fclose($fh); 
*/
	
?>