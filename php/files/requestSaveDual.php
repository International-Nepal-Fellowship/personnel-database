<?php 
include_once (dirname(__FILE__).'/connect.php');

//set_error_handler("myErrorHandler");	

    $_POST=$dbi->prepareUserData($_POST); 
    
	$tableName	=	$_POST['tableName'];
	$action		=	$_POST['action'];
    $userID     =   $_POST['userID'];
    $retVal     =   "";
     
    switch($tableName){

	case 'organisation_rep':
    
        $name		    =	$_POST['name'];	
        $organisationID	=	$_POST['organisationID'];
        $email         	=	$_POST['email'];
        $knownas		=	$_POST['knownas'];
        break;
        
	case 'visa_history':

        $visaID=$_POST['visaID'];
		$type=$_POST['type'];
		$subtype=$_POST['subtype'];
		$countryID=$_POST['countryID'];
		$number=$_POST['visaNumber'];		
		$status=$_POST['status'];
		$city=$_POST['city'];
		$entryDate=$_POST['entryDate'];
		$issueDate=$_POST['issueDate'];
		$expiryDate=$_POST['expiryDate'];
        break;      
    }
/*
$fh = fopen('savedual.txt', "a");
fwrite($fh, $action."\n");
fclose($fh);
*/
	$dbi->query("Start transaction");

	if ($dbi->isAdmin() === false) {
	    $action = "None";
        $ok = false;
        $query = "Update disallowed for ".$tableName;
    }
	
    if($dbi->maintenance()) { //knobble any further update
        $action = "None";
        $ok = false;
        $query = "Maintenance mode - try again later";
    }
	
	if($action	==	"Add New") {	

		$updateType = 'new';
		
        switch($tableName){

        case 'organisation_rep':
    
            $query	=	sprintf("INSERT INTO `organisation_rep` (id,name,organisation_id,email,known_as) VALUES ('','$name','$organisationID','$email','$knownas')");	
            break;

        case 'visa_history':
            $query =    sprintf("INSERT INTO `visa_history` (id,visa_id,number,subtype,issue_country_id,entry_date,issue_date,expiry_date,`type`,`status`,issue_city) VALUES ('','$visaID','$number','$subtype','$countryID','$entryDate','$issueDate','$expiryDate','$type','$status','$city')");	
            break;
        }
        
    	$retVal.=$query."\n";
    	
    	//Artur Neumann INF/N 8.8.2012
    	//we used $ID here before but $id in the update, so the createChangeLog call $ID was used again
    	//changed to $id everywhere
		$ok=($dbi->isInteger($id=$dbi->insertQuery($query)));
	}
	else if($action	==	"Edit") {
    
		//Artur Neumann INF/N 8.8.2012
		//quick sanitation, make sure $id is integer
    	$id=(int)$_POST['dualID'];
		$updateType = 'update';
/*        
$fh = fopen('savedual.txt', "a");
fwrite($fh, $_POST['dualID']."\n");
fclose($fh);
*/
		$timestamp	=	$_POST['timestamp'];
		// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
		$dbi->checkTimestamp($tableName,$tableName.'_timestamp',$timestamp,'id',$id);
		
		switch($tableName){

        case 'organisation_rep':
 		
            $query	=	sprintf("UPDATE `organisation_rep` SET name='".$name."',email='".$email."',organisation_id='".$organisationID."',known_as='".$knownas."' where id=".$id." and organisation_rep_timestamp='$timestamp'");
            break;
		
		case 'visa_history':
			$query=sprintf("UPDATE `visa_history` SET visa_id='".$visaID."',subtype='".$subtype."', number='".$number."',issue_country_id='".$countryID."',entry_date='".$entryDate."',issue_date='".$issueDate."',expiry_date='".$expiryDate."',`type`='".$type."',`status`='".$status."',`issue_city`='".$city."' where id=".$id." and visa_history_timestamp='$timestamp'");	
			break;
        }	
        
		$retVal.=$query."\n";		
		$ok=($dbi->isInteger($dbi->query($query)));
	}	
	
	if($ok){//insert changes information to change log table   
        $query		=" Cannot create change log. Please retry ";//message to display when createChangeLog() fails

        //Artur Neumann INF/N 8.8.2012
        //changed from $ID to $id, see comment above
		$ok=$dbi->createChangeLog($userID,$tableName,$id,$updateType,$tableName);	
	}
			
	if($ok)
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
			
	$dbi->disconnect();
    
/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('savedual.txt', "a");
fwrite($fh, $retVal."\n"); 
fclose($fh); 
*/
?>	
