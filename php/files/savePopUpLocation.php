<?php
//NOT USED SINCE savePopUpTypes.php DOES THE TASK IT IS SUPPOSED TO DO
	include_once (dirname(__FILE__).'/connect.php');
			
		$DB=$dbi->getdbname();	
		$locationName	=	$_POST['name'];
		$department=	$_POST['department'];
		
		$query=sprintf("INSERT INTO `".$DB."`.`location` (`id`, `name`, `dept`) 
		VALUES ('','$locationName', '$department')");				
			
		$dbi->printTransactionInfo($locID=$dbi->insertQuery($query));
		
		//$dbi-> test_file('loc',$query);	
						
	//reload the dataprovider with the updated data
		
/*---for location and id*/
	$result =$dbi-> get_type("location","id","name");
	
	$retVal.="<servicelocations>";	
	
	if(!$result)
		$retVal.="<servicelocation></servicelocation>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<servicelocation  data="'.$result.'">'.$value.'</servicelocation>';		
	
		}	
	$retVal.="</servicelocations>";
	
	print $retVal;
	
	
	
	$dbi->disconnect();	
	

	
?>
