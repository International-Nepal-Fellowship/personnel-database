<?php
	include_once (dirname(__FILE__).'/connect.php');
			
//	set_error_handler("myErrorHandler");	
	$_POST=$dbi->prepareUserData($_POST);

	$ID		=	$_POST['id'];
	$table		=	$_POST['tableName'];	
	$userID		=	$_POST['userID'];
	//$nameID		=	$_POST['nameID'];
	//$action		=	$_POST['action'];
	//$familyID	=	$dbi->get_family_id($nameID);			

    $dbi->query("Start transaction");
             
	if ($dbi->isAdmin() === false) {
		$ok = false;
		$query = "Delete disallowed for ".$table;
	}

	if($dbi->maintenance()) { //knobble any further update
        $ok = false;
        $query = "Maintenance mode - try again later";
    }
	
    if ($ok) {
        $keyConstraint='';
        $keyConstraint=$dbi->check_foreign_keys('Delete','users','search_id',$ID);

        if($keyConstraint!=''){
            $query = $dbi->printForeignKeyError('default search ,',true);
            $ok = false;
        }   
        else {
            $query="Cannot create change log. Please retry";//message to display when createChangeLog() fails           
            $ok=$dbi->createChangeLog($userID,'search_history',$ID,'delete','search_history');	
        }
    }
    
    if($ok){
		$query	=	'UPDATE `search_history` SET `search_history`.`deleted`=1 WHERE `search_history`.`id`='.$ID.'';
		$ok = ($dbi->isInteger($dbi->query($query)));
	}
		
	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback		
	
    $retVal=$query."\n";
	$dbi->disconnect();	
    
//$dbi-> test_file('requestDeletetab:'.$query."\n");		

?>