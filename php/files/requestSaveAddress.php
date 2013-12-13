<?php

	include_once (dirname(__FILE__).'/connect.php');
	
	set_error_handler("myErrorHandler");
	
		$country		=	$_POST['country'];
		$nameID			=	$_POST['nameid'];
		$address		=	$_POST['address'];
		$cityTown		=	$_POST['citytown'];
		$postCodeZip	=	$_POST['postcode'];
		$stateProvince	=	$_POST['stateprovince'];
		$longitude		=	$_POST['longitude'];
		$latitude		=	$_POST['latitude'];
		$type			=	$_POST['type'];
		$familyID		=	$dbi->get_family_id($nameID);
		$action			 = 	$_POST['action'];
		$addressID		=	$_POST['addressid'];
		$countryID		=	$dbi->get_table_item('country','id','name',$country);		
		$current		=	$_POST['current'];
		$requestor		=	$_POST['requestor'];
		
	
	$dbi->query("Start transaction");
	$ok = true;
	$retVal = $action."\n";	
		
	if($action	==	"Add New") {
			
		$query	=	sprintf("INSERT INTO
		address(id,family_id,country_id,address,type,city_town,postcode_zip,state_province,longitude,latitude)
		values('',$familyID,$countryID,'$address','$type','$cityTown','$postCodeZip','$stateProvince',
		'$longitude','$latitude')" 	);	

		$retVal.=$query."\n";
		$ok = ($dbi->isInteger($addressID=$dbi->insertQuery($query)));//if database returns integer (last inserted row ID)then NOT an error
		if ($ok) {		
		
			$query	=	sprintf("INSERT INTO name_address(name_id,address_id) values($nameID,$addressID)");	
			$retVal.=$query."\n";
			
			$ok = ($dbi->isInteger( $dbi->query($query)));	

		
		}				
	}
	else if($action	==	"Edit") {
			
		$query	=	sprintf("UPDATE address set country_id=".$countryID.",address='".$address."',
		type='".$type."',city_town='".$cityTown."',postcode_zip='".$postCodeZip."', 
		state_province='".$stateProvince."',longitude='".$longitude."', latitude='".$latitude."' 
		where id=".$addressID);	
		$retVal.=$query."\n";
			
		$ok = ($dbi->isInteger( $dbi->query($query)));			
	}
	
	if (($ok) && ($current == "true")) { // current address

		$query =	sprintf("SELECT name_id FROM name_address WHERE (name_id=".$nameID." AND address_id=".$addressID.")");
		$retVal.=$query."\n";
			
		$result =	$dbi->query($query);
		
		if(mysql_num_rows($result)	==	0) {// doesn't already exist in name_address table
			
			$query	=	sprintf("INSERT INTO name_address(name_id,address_id) values($nameID,$addressID)");	// same as for add new
			$retVal.=$query."\n";
				
			$ok = ($dbi->isInteger($dbi->query($query)));
		}
			
		if($ok) {
		
			$query =	sprintf("UPDATE name set address_id=".$addressID." where id=".$nameID);
			$retVal.=$query."\n";
				
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}	
	}

	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
		
/* 
IF REQUEST FROM popUpAddress.mxml DO EXTRA WORK
*/	
	if($requestor	==	'popUpAddress.mxml'){
		$result =$dbi->get_values_2('address','id','address','family_id',$familyID,'type','family');	
		$retVal="<addresses>";
		
		if(!$result)
		$retVal.="<address>None</address>";	
		else{
	
			foreach($result as $result=>$value){		
				$retVal.='<address data="'.$result.'">'.$value.'</address>';		
			}	
		}
		$retVal.="</addresses>";
		print $retVal;
		
			//$dbi->test_file("requestSaveAddress",$retVal);
	}
	
		
		
		
		
	$dbi->disconnect();	



?>
