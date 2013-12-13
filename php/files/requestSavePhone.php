<?php

	include_once (dirname(__FILE__).'/connect.php');
			
	set_error_handler("myErrorHandler");		
		
		$nameID		=	$_POST['nameid'];
		$phone		=	$_POST['phone'];
		$type		=	$_POST['type'];
		$shared		=	$_POST['shared'];
		$extn		=	$_POST['extension'];
		$familyID	=	$dbi->get_family_id($nameID);
		$phoneID	=	$_POST['phoneid'];
		$action 	=	$_POST['action'];
		$countryID	=	$_POST['countryID'];		
		$current	= 	$_POST['current'];	
		$requestor	=	$_POST['requestor'];
		//$countryID	=	$dbi->get_table_item('country','id','name',$country);

	$dbi->query("Start transaction");
	$ok = true;
	$retVal = $action."\n";
	
	if($action	==	"Add New") {
		
		$query=sprintf("INSERT INTO phone(id,family_id,country_id,phone,type,extn,shared)
		values('',$familyID,$countryID,'$phone','$type','$extn','$shared')");
		$retVal.=$query."\n";
	
		
		$ok = ($dbi->isInteger($phoneID=$dbi->insertQuery($query)));//if database returns integer (last inserted row ID)then NOT an error
		if ($ok) {

			$query=sprintf("INSERT INTO name_phone(name_id,phone_id) values($nameID,$phoneID)");
			$retVal.=$query."\n";

			$ok = ($dbi->isInteger( $dbi->query($query)));
		}		
	}
	else if($action	==	"Edit") {
	
		$query=sprintf("UPDATE phone set country_id=".$countryID.",phone='".$phone."',type='".$type."',
		shared='".$shared."',extn = '".$extn."' where id=".$phoneID);
		$retVal.=$query."\n";
			
		$ok = ($dbi->isInteger( $dbi->query($query)));
	}
	
	if (($ok) && ($current == "true")) { // current phone
	
		$query =	sprintf("SELECT name_id FROM name_phone WHERE (name_id=".$nameID." AND phone_id=".$phoneID.")");
		$retVal.=$query."\n";
			
		$result =	$dbi->query($query);
			
		if(mysql_num_rows($result)	==	0) {// doesn't already exist in name_phone table
			
			$query=sprintf("INSERT INTO name_phone(name_id,phone_id) values($nameID,$phoneID)");	// same as for add new	
			$retVal.=$query."\n";
				
			$ok = ($dbi->isInteger($dbi->query($query)));
		}
			
		if($ok) {
			
			$query =	sprintf("UPDATE name set phone_id=".$phoneID." where id=".$nameID);
			$retVal.=$query."\n";
				
			$ok = ($dbi->isInteger($dbi->query($query)));
		}	
	}
	
	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback


			
/* 
IF REQUEST FROM popUpPhone.mxml DO EXTRA WORK
*/	
	if($requestor	==	'popUpPhone.mxml'){
	
	$result =$dbi->get_values_2('phone','id','phone','family_id',$familyID,'type','family');	
	$retVal="<phones>";
	
	if(!$result)
		$retVal.="<phone></phone>";	
	else{
			foreach($result as $result=>$value){		
				$retVal.='<phone data="'.$result.'">'.$value.'</phone>';		
			}	
	}
	$retVal.="</phones>";	
	print $retVal;
	
	}
	
	$dbi->disconnect();	

?>	
