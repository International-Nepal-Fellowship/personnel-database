<?php

	include_once (dirname(__FILE__).'/connect.php');
		
//	set_error_handler("myErrorHandler");	

	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);
	
	$action 	=	$_POST['action'];
	$ID			=	$_POST['id'];
	$table		=	$_POST['tableName'];
	$extra		=	false;
	$requestor	=	"none";
	$extraTable	=	"";
	$extraField = 	$table."_id";
	$extraValue	=	$ID;
	$extraID	=	$ID;
	$query		=	"";

	$ok = true;
	$retVal = $action."\n";
	$retVal2 = "";
	
	switch($table){

	case 'secondment_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"secondment";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];
		$table 		=	"document_notes";

		break;
	
	case 'document_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"documentation";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];
		//$current	= 	$_POST['current'];	
		//$requestor	=	$_POST['requestor'];		
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];
		break;
		
	case 'orientation_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"orientation";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];
		break;

	case 'orientation_arrangement_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"orientation_arrangement";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];
		break;
	
	case 'home_assignment_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"home_assignment";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];
		break;
		
	case 'course_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"course";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];		
	//	$dbi->test_file("requestSaveDocs.php extraField: $extraField\n\n");
		break;
		
	case 'review_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"review";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];		
		break;
		
	case 'training_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"training";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];			
		break;
		
	case 'insurance_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"insurance";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];			
		break;
		
	case 'registration_notes':
	
		if($action == "Add New") $extra = true;
		$extraTable	=	"registrations";
		$table 		=	"document_notes";
		$extraField =	$_POST['docField'];
		$extraID	=	$_POST['docID'];	
		$notes		=	$_POST['notes'];
		$link		=	$_POST['link'];			
		break;
	}
	
	if ($extra) {
		$dbi->query("Start transaction");
	}

	if($action	==	"Add New") { //verify add permissions
		if ($dbi->isNotAllowed($extraTable,'add')) {
			$action = "None";
			$extra = false;
			$ok = false;
			$query = "Add disallowed for ".$extraTable;
		}
	}
	if($action	==	"Edit") { //verify edit permissions
		if ($dbi->isNotAllowed($extraTable,'edit')) {
			$action = "None";
			$extra = false;
			$ok = false;
			$query = "Edit disallowed for ".$extraTable;
		}
	}
	
    if($dbi->maintenance()) { //knobble any further update
        $action = "None";
		$extra = false;
        $ok = false;
        $query = "Maintenance mode - try again later";
    }
	
	if($action	==	"Add New") {

		switch($table){

		case 'document_notes':
        
			$query=sprintf("INSERT INTO `document_notes`(id,notes,link) VALUES('','$notes','$link')");	
			//$dbi->test_file('requestsavedoc.php',$query);
			break;
		}	

		if ($query != "") {
			$retVal.=$query."\n";

			if ($extra) {		
				$ok = ($dbi->isInteger($ID=$dbi->insertQuery($query)));//if database returns integer (last inserted row ID)then NOT an error
				$extraValue = $ID;
			}
			else {
				//$dbi->printTransactionInfo($dbi->insertQuery($query));
				$ok = ($dbi->isInteger($ID=$dbi->insertQuery($query)));
			}
		}
	}
	else if($action	==	"Edit") {

		switch($table){

		case 'document_notes':
        
			$query	=	sprintf("UPDATE `document_notes` SET notes='".$notes."',link='".$link."' WHERE id=".$ID);	
			break;
		}
	
		if ($query != "") {
			$retVal.=$query."\n";
		
			if ($extra) {
				$ok = ($dbi->isInteger( $dbi->query($query)));
			}
			else {
				//$dbi->printTransactionInfo($dbi->query($query));
				$ok = ($dbi->isInteger( $dbi->query($query)));
			}
		}
	}

	if ($extra) {
	
		if (($ok) && ($extraTable != "")) { 
        
            if ($extraTable == 'course') //ensure timestamp not changed, otherwise messes up record in $extraTable when saving
                $extraTimestamp = 'timestamp';
            else
                $extraTimestamp = $extraTable.'_timestamp';
			
			switch ($table){

			case 'document_notes':

				$query =	sprintf("UPDATE `".$extraTable."` set ".$extraField."=".$extraValue.",`".$extraTimestamp."`= `".$extraTimestamp."` where id=".$extraID);
		
				$retVal.=$query."\n";
				$ok = ($dbi->isInteger($dbi->query($query)));
				break;
			}
		}
	}
	
	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
	
	$dbi->disconnect();	
/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('savedoc.txt', "a");
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fwrite($fh, $retVal2); 
fwrite($fh,"\n");
fclose($fh); 
*/

?>