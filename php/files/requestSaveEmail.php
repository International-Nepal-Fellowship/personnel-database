<?php
	include_once (dirname(__FILE__).'/connect.php');
	
	set_error_handler("myErrorHandler");
	
		$nameID		=	$_POST['nameid'];
		$familyID	=	$dbi->get_family_id($nameID);
		$email		=	$_POST['email'];
		$type		=	$_POST['type'];
		$action		=	$_POST['action'];
		$emailID	=	$_POST['emailid'];
		$current	=	$_POST['current'];	
		$requestor	=	$_POST['requestor'];
	$dbi->query("Start transaction");
	$ok = true;
	$retVal = $action."\n";		
		
	if($action ==	"Add New") {

		$query=sprintf("INSERT INTO email(id,family_id,email,type) values('',$familyID,'$email','$type')");
		$retVal.=$query."\n";

		$ok = ($dbi->isInteger($emailID=$dbi->insertQuery($query)));//if database returns integer (last inserted row ID)then NOT an error
		if ($ok) {		
					
			$query=sprintf("INSERT INTO name_email(name_id,email_id) values($nameID,$emailID)");
			$retVal.=$query."\n";
			
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}					 
	}
	else if ($action == "Edit") {
		
		$query=sprintf("UPDATE email set email='".$email."',type='".$type."' where id=".$emailID);
		$retVal.=$query."\n";
			
		$ok = ($dbi->isInteger( $dbi->query($query)));
	}
	
	if (($ok) && ($current == "true")) { // current email
	
		$query =	sprintf("SELECT name_id FROM name_email WHERE (name_id=".$nameID." AND email_id=".$emailID.")");
		$retVal.=$query."\n";
			
		$result =	$dbi->query($query);
			
		if(mysql_num_rows($result)	==	0) {// doesn't already exist in name_email table
			
			$query=sprintf("INSERT INTO name_email(name_id,email_id) values($nameID,$emailID)");	// same as for add new
			$retVal.=$query."\n";	
				
			$ok = ($dbi->isInteger($dbi->query($query)));
		}
			
		if($ok) {
			
			$query =	sprintf("UPDATE name set email_id=".$emailID." where id=".$nameID);
			$retVal.=$query."\n";
				
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}	
	}

	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
	

		
/* 
IF REQUEST FROM popUpEmail.mxml DO EXTRA WORK
*/	
		
	if($requestor	==	'popUpEmail.mxml'){	
		
	$result =$dbi->get_values_2('email','id','email','family_id',$familyID,'type','family');	
	$retVal="<emails>";
	
	if(!$result)
		$retVal.="<email></email>";	
	else{
		foreach($result as $result=>$value){		
			$retVal.='<email data="'.$result.'">'.$value.'</email>';		
		}
	}		
	$retVal.="</emails>";	
	print $retVal;
	
	
	}	
		
		
	$dbi->disconnect();	

?>
