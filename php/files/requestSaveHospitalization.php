<?php
	include_once (dirname(__FILE__).'/connect.php');
	
	set_error_handler("myErrorHandler");
	
		$nameID=$_POST['nameid'];
		$action=$_POST['action'];
		$hospitalisationID=$_POST['hospitalizationid'];
		$startDate=$_POST['startDate'];	
		$endDate =$_POST['endDate'];
		$hospitalID =$_POST['hospital'];
		$relativeID =$_POST['relative'];
		$births =$_POST['births'];
		$illnessID=$_POST['illness'];
		$cost=$_POST['cost'];

	if($action ==	"Add New"){
	
			$query	=	sprintf("INSERT INTO hospitalisation (`id` ,`name_id` ,`relative_id` ,
			`date_from` ,`date_until`, 			`births` ,	`illness_id` ,`hospital_id` ,`cost`)
			values('',$nameID,'$relativeID','$startDate','$endDate','$births','$illnessID','$hospitalID','$cost')
			");
	
		
			$dbi->printTransactionInfo($dbi->query($query));
		
				
	}
	else if ($action == "Edit"){
				
			
			
			$query=sprintf("UPDATE hospitalisation set `name_id`='".$nameID."',
			`relative_id`='".$relativeID."',`date_from`='".$startDate."',`date_until`='".$endDate."',
			`births`='".$births."',`illness_id`='".$illnessID."',`hospital_id`='".$hospitalID."',
			`cost`='".$cost."' where id=".$hospitalisationID);
				
			$dbi->printTransactionInfo($dbi->query($query));

		
	}
	
	$dbi->disconnect();	
	
	
?>