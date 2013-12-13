<?php
	include_once (dirname(__FILE__).'/connect.php');

//	set_error_handler("myErrorHandler");	

	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);	
	$DB=$dbi->getdbname();
	
//$dbi->test_file('deleteTab ');
	
	//$ID			=	81;//$_POST['id'];
	//$table		=	'name';//$_POST['tableName'];	
	//$nameID		=	81;//$_POST['nameID'];	
	/*
	$userID		=	2;
	$ID			=	1;
	$table		=	'visa_history';
	$nameID		=	3459;
	$action		=	'delete';
	*/
	
	$_POST=$dbi->prepareUserData($_POST);	
	$DB=$dbi->getdbname();
	
	$userID		=	$_POST['userID'];
	$ID			=	$_POST['id'];
	$table		=	$_POST['tableName'];	
	$nameID		=	$_POST['nameID'];
	$action		=	$_POST['action'];
	
	$familyID	=	$dbi->get_family_id($nameID);
	$updateType	='';
	if($action=='Add new')			
		$updateType='new';
	else
		$updateType=strtolower($action);	

	$keyConstraint='';
	$query		=	"";
	$retVal = $action."\n";
    $retVal.= $table."\n";
//$dbi->test_file('deleteTab  $action,$ID,$table,$nameID',"$action,$ID,$table,$nameID");
	$ok = true;
    
	$dbi->query("Start transaction");
	
	if ($dbi->isNotAllowed($table,'delete')) {
		$action = "None";
		$ok = false;
		$query = "Delete disallowed for ".$table;
	}
	
    if($dbi->maintenance()) { //knobble any further update
        $action = "None";
        $ok = false;
        $query = "Maintenance mode - try again later";
    }
	if ($ok) {
		$query="Cannot create change log. Please retry";//message to display when createChangeLog() fails
		$ok=$dbi->createChangeLog($userID,$table,$ID,$updateType,'tab',$nameID);
	}
	if ($ok) {
	switch($table){
	
		case 'name':
        case 'relation':
		
        $relation_only = (($table == 'relation') && ($action == 'Delete'));
        $retVal.= $relation_only."\n";
        
		if($action=='Force Delete') {
			//$keyConstraint.=forceDeleteRelatives($dbi,$userID,$nameID,$action,$table);
            $keyConstraint.=$dbi->check_foreign_keys($action,'relation','name_id',$ID,'relation_id');
            $keyConstraint.=$dbi->check_foreign_keys($action,'relation','relation_id',$ID,'name_id');
        }
		else {
			//$keyConstraint.=$dbi->check_foreign_keys($action,'relation','name_id',$ID,'relation_id',"AND `relation`.`relation_id` = $nameID");
            //$keyConstraint.=$dbi->check_foreign_keys($action,'relation','name_id',$nameID,'relation_id',"AND `relation`.`relation_id` = $ID");         
        }
//$dbi->test_file("deleteTab name  $action");	

        if (!relation_only) {
            $keyConstraint.=$dbi->check_foreign_keys($action,'documentation','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'home_assignment','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'hospitalisation','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'leave','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'movement','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'orientation','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'orientation_arrangement','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'review','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'secondment','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'training','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'training_needs','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'name_post','name_id',$ID,'post_id');
	
            $keyConstraint.=$dbi->clear_foreign_key('post','manager_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'service','name_id',$ID);		
            $keyConstraint.=$dbi->check_foreign_keys($action,'staff','name_id',$ID);
            //$keyConstraint.=$dbi->check_foreign_keys($action,'health_staff','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'name_address','name_id',$ID,'address_id');
            $keyConstraint.=$dbi->check_foreign_keys($action,'name_phone','name_id',$ID,'phone_id');
            $keyConstraint.=$dbi->check_foreign_keys($action,'name_email','name_id',$ID,'email_id');
		
            $keyConstraint.=$dbi->check_foreign_keys($action,'education','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'photo','name_id',$ID);
            $keyConstraint.=$dbi->check_foreign_keys($action,'passport','name_id',$ID);
		
            $keyConstraintPatient=$dbi->check_foreign_keys($action,'patient_visit','patient_id',$ID);
            $keyConstraintPatient.=$dbi->check_foreign_keys($action,'treatment','patient_id',$ID);
            $keyConstraintPatient.=$dbi->check_foreign_keys($action,'patient_service','patient_id',$ID);
            $keyConstraintPatient.=$dbi->check_foreign_keys($action,'patient_surgery','patient_id',$ID);
            $keyConstraintPatient.=$dbi->check_foreign_keys($action,'patient_appliance','patient_id',$ID);
            $keyConstraintPatient.=$dbi->check_foreign_keys($action,'patient_bill','patient_id',$ID);
            $keyConstraintPatient.=$dbi->check_foreign_keys($action,'health_staff','name_id',$ID);
            $keyConstraintPatient.=$dbi->check_foreign_keys($action,'training_needs','name_id',$ID);
            //If patient_visit (and other patient System related tables like patient_bill, patient_service, treatment, etc)table do not have any entry for this $ID 
            //then we can delete the entry from patient_inf while deleteing from name table
            //OR if this is force delete case then we can UPDATE patient_inf 
            if(($keyConstraintPatient=='')||($action=='Force Delete')) {		
                $keyConstraintPatient.=$dbi->check_foreign_keys('Force Delete','patient_inf','patient_id',$ID);
            }
            $keyConstraint.=$keyConstraintPatient;	
        }            			
		if($keyConstraint!=''){
			$query = $dbi->printForeignKeyError($keyConstraint,true);
			$ok = false;
		}
		if ($ok) {
			$query='UPDATE  `name` SET  `next_of_kin_id` =0 WHERE  `name`.`next_of_kin_id`='.$ID;
            if ($relation_only) { //restrict to current name if relation, unless forced
                $query.=' AND `name`.`id`='.$nameID;
            }
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger($dbi->query($query)));
		}
		if ($ok) {					
			$query	='UPDATE `relation` SET `relation`.`deleted` = 1 WHERE `relation`.`name_id`='.$nameID.' and `relation`.`relation_id`='.$ID;
			$retVal.=$query."\n";			
			$ok = ($dbi->isInteger( $dbi->query($query)));
//if (!$ok)echo"<br> DeleteRelative: $query ";
		}
		if ($ok) {					
			$query	='UPDATE `relation` SET `relation`.`deleted` = 1 WHERE `relation`.`relation_id`='.$nameID.' and `relation`.`name_id`='.$ID;
			$retVal.=$query."\n";			
			$ok = ($dbi->isInteger( $dbi->query($query)));
//if (!$ok)echo"<br> DeleteRelative: $query ";
		}
//if(!$ok)echo"$query<br>";
        if (!$relation_only) { //don't delete anything else for relation unless forced
            if ($ok) {
                $query='UPDATE `surname` SET `deleted`=1 WHERE `surname`.`name_id`='.$ID;
                $retVal.=$query."\n";
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `nationality` SET `deleted`=1 WHERE `nationality`.`name_id`='.$ID;
                $retVal.=$query."\n";
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `health_staff` SET `deleted`=1 WHERE `health_staff`.`name_id`='.$ID;
                $retVal.=$query."\n";					
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `inf_staff` SET `deleted`=1 WHERE `inf_staff`.`name_id`='.$ID;
                $retVal.=$query."\n";					
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `name` SET `deleted`=1 WHERE `name`.`id`='.$ID;
                $retVal.=$query."\n";
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `name_email` SET `deleted`=1 WHERE `name_email`.`name_id`='.$ID;
                $retVal.=$query."\n";
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `name_phone` SET `deleted`=1 WHERE `name_phone`.`name_id`='.$ID;
                $retVal.=$query."\n";
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `name_address` SET `deleted`=1 WHERE `name_address`.`name_id`='.$ID;
                $retVal.=$query."\n";
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
            if ($ok) {
                $query='UPDATE `name_post` SET `deleted`=1 WHERE `name_post`.`name_id`='.$ID;
                $retVal.=$query."\n";
                $ok = ($dbi->isInteger( $dbi->query($query)));
//if(!$ok)echo"$query<br>";
            }
        }	
		if ($relation_only) {
			$query='SELECT `relation_id` FROM `relation` WHERE `deleted`=0 AND `name_id`='.$ID.' UNION SELECT `name_id` FROM `relation` WHERE `deleted`=0 AND `relation_id`='.$ID;
			$retVal.=$query."\n";
			$queryResult= $dbi->query($query);
			if($queryResult){
				if (mysql_num_rows($queryResult) == 0){ //no longer related to anyone
					$query='UPDATE `name` SET `deleted`=1 WHERE `name`.`id`='.$ID;
					$retVal.=$query."\n";
					$ok = ($dbi->isInteger( $dbi->query($query)));
				}
            }
        }
	break;
	
	case 'address':
    case 'phone':
    case 'email':

		if($action== 'Force Delete') $action	=	'Delete';//dont allow force delete
		//if this email not used in movement then keyConstraint='' else keyConstraint= foreign tablename(i.e. movement for now)
		$keyConstraint.=$dbi->check_foreign_keys($action,'movement',$table.'_id',$ID);
		
		$keyConstraint.=$dbi->check_foreign_keys($action,'location',$table.'_id',$ID);
		$keyConstraint.=$dbi->check_foreign_keys($action,'organisation',$table.'_id',$ID);
		if($keyConstraint=='')
		{
			$keyConstraint.=$dbi->check_foreign_keys($action,'name',$table.'_id',$ID);
			if($keyConstraint!='')
				$keyConstraint = 'Current '.$table.',';
		}
		if($keyConstraint!=''){			
			$query=$dbi->printForeignKeyError($keyConstraint,true);
			$ok = false;
		}	
		if ($ok) {
			$query	=	'UPDATE `name_'.$table.'` SET `deleted`=1 WHERE `name_'.$table.'`.`name_id`='.$nameID.' and `name_'.$table.'`.`'.$table.'_id`='.$ID;
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger($dbi->query($query)));
		}
		break;
		
	case 'service':
			
		if ($ok) {
			$query='UPDATE `name_post` SET `deleted`=1 WHERE `name_post`.`name_id`='.$nameID;
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}
		if ($ok) {
			$query='UPDATE `service` SET `deleted`=1 WHERE `service`.`id`='.$ID;
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}
				
		break;
	
	case 'training':
        	
		if ($ok) {
			$query='UPDATE `training_needs` SET `deleted`=1 WHERE `training_needs`.`name_id`='.$nameID;
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}
		if ($ok) {
			$query='UPDATE `training` SET `deleted`=1 WHERE `training`.`id`='.$ID;
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}
				
		break;
        
	case 'visa_history':
		//$dbi->test_file('visa_history','$query');
		//break;
	case 'photo':
	case 'passport':
	case 'education':
	case 'documentation':
	case 'secondment':
	case 'orientation':
    case 'orientation_arrangement':
    case 'movement':
	case 'leave':	
	case 'staff':
	case 'hospitalisation':
	case 'review':
	case 'home_assignment':
	case 'patient_bill':
	case 'patient_appliance':		
	case 'patient_surgery':	
	case 'patient_service':	
	case 'treatment':
	case 'health_staff':
		
		//Artur Neumann INF/N 27.07.2012
		//It was not possible to delete items in the work_experience tab
	case 'work_experience':
		
		//Artur Neumann INF/N 29.09.2012
		//It was not possible to delete items in the insurance tab
	case 'insurance':		
		
		$query = 'UPDATE `'.$table.'` SET `deleted`=1 WHERE `'.$table.'`.`id`='.$ID;
		$retVal.=$query."\n";			
		$ok=($dbi->isInteger($dbi->query($query)));
		break;

/*	case 'inf_staff':
		$query='UPDATE `inf_staff` SET `deleted`=1 WHERE `inf_staff`.`id`='.$ID;	
		$dbi->printTransactionInfo($dbi->query($query));
		break;
	case 'service':
	if ($ok) {
					$query='UPDATE `name_post` SET `deleted`=1 WHERE `name_post`.`name_id`='.$ID;
					$retVal.=$query."\n";
					$ok = ($dbi->isInteger( $dbi->query($query)));
				}
		$query='UPDATE `service` SET `deleted`=1 WHERE `service`.`id`='.$ID;
		$dbi->printTransactionInfo($dbi->query($query));		
		break;
	*/
    
	case 'patient_visit':
		//check if the patinet_visit table have more than one row. if only single row then delete info from patient_inf table also
		$deletePatientInf=false;
		$query='SELECT  `id` FROM `'.$DB.'`.`patient_visit` WHERE `'.$DB.'`.`patient_visit`.`patient_id`='.$nameID;
		$result=$dbi->query($query);
		if (mysql_num_rows($result) == 1)
			$deletePatientInf=true;
			
		if ($ok) {
			$query='UPDATE `patient_visit` SET `deleted`=1 WHERE `patient_visit`.`id`='.$ID;
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}
		
		if($deletePatientInf){
			if ($ok) {				
				$query=sprintf("UPDATE `patient_inf` set PAL = '',PWD = '', care_after_cure = '',footwear_needed='' where patient_id=".$nameID." and patient_inf_timestamp='".$_POST['patient_inf_timestamp']."'");
				$retVal.=$query."\n";
				$ok = ($dbi->isInteger( $dbi->query($query)));
			}
		}
		
		break;		
	}
	}
	/*
	if ($ok) {
		$query="Cannot create change log. Please retry";//message to display when createChangeLog() fails
		$ok=$dbi->createChangeLog($userID,$table,$ID,$updateType,'tab',$nameID);
	}
	*/
	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
	
	$dbi->disconnect();		
	
//$dbi-> test_file('requestDeletetab:'.$query."\n");	

/*
$fh = fopen('deletetest.txt', "a"); 
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/	

?>