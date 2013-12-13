<?php

	include_once (dirname(__FILE__).'/connect.php');
	
//	set_error_handler("myErrorHandler");	

function get_existing_relationship($rel_id,$name_id,$db) {

    $query1 = sprintf("SELECT relationship FROM `relation` WHERE relation_id = $rel_id AND name_id = $name_id");
    
    $queryResult = $db->query($query1);
	if($queryResult){
        if (mysql_num_rows($queryResult) > 0) {
            $row = mysql_fetch_assoc($queryResult);
            return $row['relationship'];
        }
    }
    return "";
}

function get_gender($name_id,$db) {

    $query1 = sprintf("SELECT gender FROM `name` WHERE id = $name_id");
    
    $queryResult = $db->query($query1);
	if($queryResult){
        if (mysql_num_rows($queryResult) > 0) {
            $row = mysql_fetch_assoc($queryResult);
            return $row['gender'];
        }
    }
    return "";
}
                
function get_gender_relationship($relationship_type,$gender)
{
    $relationship = $relationship_type;
    
    switch($gender){
	
	case 'Male':
        
        switch($relationship_type) {
            case 'Child':
                $relationship = 'Son';
                break;
            case 'Parent':
                $relationship = 'Father';
                break;
            case 'Spouse':
                $relationship = 'Husband';
                break;
            case 'Parent-in-law':
                $relationship = 'Father-in-law';
                break;
            case 'Child-in-law':
                $relationship = 'Son-in-law';
                break;
            case 'Grandchild':
                $relationship = 'Grandson';
                break;
            case 'Grandparent':
                $relationship = 'Grandfather';
                break;
            case 'Sibling':
                $relationship = 'Brother';
                break;
            case 'Sibling-in-law':
                $relationship = 'Brother-in-law';
                break;
            case 'Sibling-child':
                $relationship = 'Nephew';
                break;
            case 'Parent-sibling':
                $relationship = 'Uncle';
                break;
            }
        break;
        
    	case 'Female':
        
        switch($relationship_type) {
            case 'Child':
                $relationship = 'Daughter';
                break;
            case 'Parent':
                $relationship = 'Mother';
                break;
            case 'Spouse':
                $relationship = 'Wife';
                break;
            case 'Parent-in-law':
                $relationship = 'Mother-in-law';
                break;
            case 'Child-in-law':
                $relationship = 'Daughter-in-law';
                break;
            case 'Grandchild':
                $relationship = 'Granddaughter';
                break;
            case 'Grandparent':
                $relationship = 'Grandmother';
                break;
            case 'Sibling':
                $relationship = 'Sister';
                break;
            case 'Sibling-in-law':
                $relationship = 'Sister-in-law';
                break;
            case 'Sibling-child':
                $relationship = 'Niece';
                break;
            case 'Parent-sibling':
                $relationship = 'Aunt';
                break;
            }
        break;
       
    }

/*
$fh = fopen('savegeneraltest.txt', "a"); 
fwrite($fh, "returning gender relationship ".$relationship." from ".$relationship_type." and ".$gender); 
fwrite($fh,"\n");
fclose($fh);
*/     
    return $relationship;
}

function get_relationship_type($relationship) {

    switch($relationship) {
        case 'Son':
        case 'Daughter':
            $relationship_type = 'Child';
            break;
        case 'Father':
        case 'Mother':
            $relationship_type = 'Parent';
            break;
        case 'Husband':
        case 'Wife':
            $relationship_type = 'Spouse';
            break;
        case 'Son-in-law':
        case 'Daughter-in-law':
            $relationship_type = 'Child-in-law';
            break;
        case 'Father-in-law':
        case 'Mother-in-law':
            $relationship_type = 'Parent-in-law';
            break;
        case 'Grandson':
        case 'Granddaughter':
            $relationship_type = 'Grandchild';
            break;
        case 'Grandfather':
        case 'Grandmother':
            $relationship_type = 'Grandparent';
            break;
        case 'Brother':
        case 'Sister':
            $relationship_type = 'Sibling';
            break;
        case 'Brother-in-law':
        case 'Sister-in-law':
            $relationship_type = 'Sibling-in-law';
            break;
        case 'Nephew':
        case 'Niece':
            $relationship_type = 'Sibling-child';
            break;
        case 'Uncle':
        case 'Aunt':
            $relationship_type = 'Parent-sibling';
            break;    
        default:
            $relationship_type = $relationship;
            break;
        }
        
    return $relationship_type;
}

function get_reverse_relationship_type($reverse_relationship_type)
{
    switch($reverse_relationship_type) {
        case 'Parent':
            $relationship_type = 'Child';
            break;
        case 'Child':
            $relationship_type = 'Parent';
            break;
        case 'Parent-in-law':
            $relationship_type = 'Child-in-law';
            break;
        case 'Child-in-law':
            $relationship_type = 'Parent-in-law';
            break;
        case 'Grandparent':
            $relationship_type = 'Grandchild';
            break;
        case 'Grandchild':
            $relationship_type = 'Grandparent';
            break;
        case 'Parent-sibling':
            $relationship_type = 'Sibling-child';
            break;
        case 'Sibling-child':
            $relationship_type = 'Parent-sibling';
            break;    
        default:
            $relationship_type = $reverse_relationship_type;
            break;
        }

    return $relationship_type;    
}

function reverse_relationship_type($relationship_type,$reverse) 
{
    if ($reverse) return get_reverse_relationship_type($relationship_type);
    return $relationship_type;
}

function get_other_relationship_type($a_b,$a_c)
{
    $a_b_type = get_reverse_relationship_type(get_relationship_type($a_b)); //relationship between person A and B
    $a_c_type = get_reverse_relationship_type(get_relationship_type($a_c)); //relationship between person A and C
    
    $b_c_type = 'None'; //relationship between person B and C
    $reverse = false;
    
    if($a_b_type < $a_c_type) { //transform is reversible so swap order and use reverse flag
        $temp = $a_c_type;
        $a_c_type = $a_b_type; 
        $a_b_type = $temp;
        $reverse = true;
    }
    
    if (($a_b_type == 'Parent') && ($a_c_type == 'Parent')) $b_c_type = 'Sibling';
    if (($a_b_type == 'Child') && ($a_c_type == 'Child')) $b_c_type = 'Spouse';
    if (($a_b_type == 'Sibling') && ($a_c_type == 'Sibling')) $b_c_type = 'Sibling';
    if (($a_b_type == 'Sibling-child') && ($a_c_type == 'Sibling-child')) $b_c_type = 'Sibling';
    
    if (($a_b_type == 'Child-sibling') && ($a_c_type == 'Child')) $b_c_type = reverse_relationship_type('Sibling',$reverse);
    if (($a_b_type == 'Parent') && ($a_c_type == 'Child')) $b_c_type = reverse_relationship_type('Grandchild',$reverse);
    if (($a_b_type == 'Parent-sibling') && ($a_c_type == 'Child')) $b_c_type = reverse_relationship_type('Grandchild',$reverse);
    if (($a_b_type == 'Parent-sibling') && ($a_c_type == 'Parent')) $b_c_type = reverse_relationship_type('Cousin',$reverse);
    if (($a_b_type == 'Sibling') && ($a_c_type == 'Parent')) $b_c_type = reverse_relationship_type('Parent-sibling',$reverse);
    if (($a_b_type == 'Sibling') && ($a_c_type == 'Child')) $b_c_type = reverse_relationship_type('Child',$reverse);
    if (($a_b_type == 'Sibling') && ($a_c_type == 'Child-sibling')) $b_c_type = reverse_relationship_type('Child-sibling',$reverse);
    if (($a_b_type == 'Sibling') && ($a_c_type == 'Cousin')) $b_c_type = reverse_relationship_type('Cousin',$reverse);
    if (($a_b_type == 'Sibling') && ($a_c_type == 'Grandchild')) $b_c_type = reverse_relationship_type('Grandchild',$reverse);
    if (($a_b_type == 'Sibling-in-law') && ($a_c_type == 'Parent')) $b_c_type = reverse_relationship_type('Parent-sibling',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Parent')) $b_c_type = reverse_relationship_type('Parent',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Parent-in-law')) $b_c_type = reverse_relationship_type('Parent-in-law',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Parent-sibling')) $b_c_type = reverse_relationship_type('Parent-sibling',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Child')) $b_c_type = reverse_relationship_type('Child-in-law',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Child-in-law')) $b_c_type = reverse_relationship_type('Child',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Sibling')) $b_c_type = reverse_relationship_type('Sibling-in-law',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Sibling-in-law')) $b_c_type = reverse_relationship_type('Sibling',$reverse);
    if (($a_b_type == 'Spouse') && ($a_c_type == 'Grandparent')) $b_c_type = reverse_relationship_type('Grandparent',$reverse);
    
    if (($a_b_type == 'Parent') && ($a_c_type == 'Grandparent')) $b_c_type = 'Other'; //Child or Child-sibling    
    if (($a_b_type == 'Grandchild') && ($a_c_type == 'Child')) $b_c_type = 'Other'; //Child or Child-sibling
    if (($a_b_type == 'Sibling') && ($a_c_type == 'Parent-sibling')) $b_c_type = 'Other'; //Child or Child-sibling
    if (($a_b_type == 'Grandparent') && ($a_c_type == 'Grandparent')) $b_c_type = 'Other'; //Cousin or sibling
    if (($a_b_type == 'Parent-sibling') && ($a_c_type == 'Parent-sibling')) $b_c_type = 'Other'; //Cousin or sibling
    if (($a_b_type == 'Grandchild') && ($a_c_type == 'Child-sibling')) $b_c_type = 'Other'; //Child or Child-in-law
    if (($a_b_type == 'Cousin') && ($a_c_type == 'Child')) $b_c_type = 'Other'; //Parent-sibling or Parent-in-law
    if (($a_b_type == 'Cousin') && ($a_c_type == 'Child-sibling')) $b_c_type = 'Other'; //Parent-sibling or Parent    

/*
$fh = fopen('savegeneraltest.txt', "a"); 
fwrite($fh, "returning other relationship ".$b_c_type." from ".$a_b_type." and ".$a_c_type); 
fwrite($fh,"\n");
fclose($fh);
*/   
    return $b_c_type;
}

function get_reverse_relationship($relationship,$gender)
{
    return get_gender_relationship(get_reverse_relationship_type(get_relationship_type($relationship)),$gender);
}

	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);	
    
	//Artur Neumann ict.projects@nepal.inf.org
	//This array is used to make more changelog than the default one.
	$additional_change_logs = Array();
	
    $mode = $_POST['mode'];
		
	$userID		=	$_POST['userID'];	
	$birthPlaceCounty	=	$_POST['birthplacecounty'];
	$birthPlaceTown		=	$_POST['birthplacetown'];
	$countryID			=	$_POST['birthplacecountryID'];
	//$birthPlaceCountry	=	$_POST['birthplacecountry'];
	//$countryID		=	$dbi->get_table_item('country','id','name',$birthPlaceCountry);
	$id					=	$_POST['id'];
	$action				= 	$_POST['action'];	
		
	//data for name table
	$title	=	$_POST['title'];
	$foreNames	=	$_POST['forename'];
	$knownAs	=	$_POST['knownas'];
	$maritalStatus	=	$_POST['maritalstatus'];
	$gender	=	$_POST['gender'];
	$bloodGroup	=	$_POST['bloodgroup'];
	$dob	=	$_POST['dob'];	
    $site	=	$dbi->getSiteName();
	if($site!='Patient') $embassy_reg	=	$_POST['embassy_reg'];	 
	if($site!='Patient') $embassy_id	=	$_POST['embassy_id'];	 
	
	//$birthPlace	=	$birthPlaceCounty.'/'.$birthPlaceTown.'/'.$countryID;

    if ($mode == 'relative') {
        $staffID = $_POST['staffid'];
        $nextToKin	=	$_POST['nexttokin'];
        $relationship	=	$_POST['relation'];
    } 
    else {
	
		//data for inf_staff table For Inf Personnel System	
        $staffTypeID=0;
        $leavingReasonID=0;
        $startDate='0000-00-00';
        $endDate='0000-00-00';
        $reviewDate='0000-00-00';
        $retirementDate='0000-00-00';
        $leavingDate='0000-00-00';
		
        //data for patient_inf table For Patient System
        $pad='';//for patient system
        $pwd='';//for patient system
        $cac='';//for patient system
        $footwear_needed='';//for patient system
        $religionID	=	$dbi->get_table_item('religion','id','name',$_POST['religion']);							
        $casteID	=	$dbi->get_table_item('caste','id','name',$_POST['caste']);	
        if($site!='Patient') $citizenShip	=	$_POST['citizenship'];
        //$medicalInsurance	=	$_POST['medicalinsurance'];	
	}
    
	#data for surname table
	$surName=$_POST['surname'];
	
	#data for nationality table
	$nationality=$_POST['nationality'];
	
	$dbi->query("Start transaction");
	$ok = true;
	$retVal = $action."\n";   

	if($action	==	"Add New") { //verify add permissions
		if ($dbi->isNotAllowed('personal','add')) {
			$action = "None";
			$ok = false;
			$query = "Add disallowed for personal";
		}
	}
	if(($action	==	"Edit") || ($action	==	"Add Existing")) { //verify edit permissions
		if ($dbi->isNotAllowed('personal','edit')) {
			$action = "None";
			$ok = false;
			$query = "Edit disallowed for personal";
		}
	}

    if($dbi->maintenance()) { //knobble any further update
        $action = "None";
        $ok = false;
        $query = "Maintenance mode - try again later";
    }
	
	if($action	==	"Add New") {		
		
        $logType = "new";        
		//$dbi-> test_file('general',$familyID);	
		
        if ($mode == "relative") {
            $familyID 	=	$dbi->get_family_id($staffID);
        }        
        else
        {
            //insert into family table
            $query = sprintf("INSERT INTO `family`(id) VALUES ('')");	
            $retVal.=$query."\n";           
            $ok = ($dbi->isInteger($familyID=$dbi->insertQuery($query)));
        }
		
		if ($ok) {
		
			//$nextToKinID	=$dbi->get_default_kin_id();	
            $nextToKinID = '';
						
			//insert into  name table		
			$query	=	sprintf("INSERT INTO `name` (id,family_id,next_of_kin_id,title,forenames,known_as,marital_status,gender,blood_group,dob,birth_town,birth_district,birth_country_id) VALUES('','$familyID','$nextToKinID','$title','$foreNames','$knownAs','$maritalStatus','$gender','$bloodGroup','$dob','$birthPlaceTown','$birthPlaceCounty','$countryID')");			
			$retVal.=$query."\n";	
			$ok = ($dbi->isInteger($nameID = $dbi->insertQuery($query)));
		}

		if ($ok && ($mode == 'staff')&&($site!='Patient')) {
									
			$query	=	sprintf("INSERT INTO `inf_staff` (id,name_id,religion_id,caste_id,citizenship,embassy_reg,embassy_id) VALUES
			('','$nameID','$religionID','$casteID','$citizenShip','$embassy_reg','$embassy_id')");
					
			$retVal.=$query."\n";

			//Artur Neumann ict.projects@nepal.inf.org
			//4.07.2013
			//change to insertQuery and save the result
			$insert_result = $dbi->insertQuery($query);
			$ok = ($dbi->isInteger($insert_result));
			
			//Artur Neumann ict.projects@nepal.inf.org
			//4.07.2013
			//get data for additional change log
			if ($ok) {
			
				
				$additional_change_log['table'] ='inf_staff';
				$additional_change_log['recordID'] = $insert_result;
				$additional_change_log['updateType'] = 'new';
				
				
			
				array_push ($additional_change_logs, $additional_change_log);
			
			}			
			
		
		/*	if($ok){
				
				$query	=	sprintf("INSERT INTO `staff` (id,staff_type_id,name_id,leaving_reason_id,start_date,probation_end_date,retirement_date,leaving_date,next_review_due) VALUES('','$staffTypeID','$nameID','$leavingReasonID','$startDate','$endDate','$retirementDate','$leavingDate','$reviewDate')");
				
				$retVal.=$query."\n";
			
				$ok = ($dbi->isInteger( $dbi->query($query)));
			}
			*/
		}	
		
		if ($ok && ($mode == 'staff')&&($site=='Patient')) {
			//insert into patient_inf table	for patient system	 ,
			
				$query	=	sprintf("INSERT INTO `patient_inf` (id,patient_id,PAL,PWD,care_after_cure,footwear_needed,religion_id,caste_id) VALUES	('','$nameID','$pal','$pwd','$cac','$footwear_needed','$religionID','$casteID')");
					
			$retVal.=$query."\n";
			
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}				

		//Artur Neumann
		//4.7.2013
		//no need to do this part if the surname didn't change. e.g when a new relative was added
		if	($ok && $oldSurname != $surName) {
				
			#insert into surname table			
			$query	=	sprintf("INSERT INTO `surname`(`id`,`name_id`,`surname`,`priority`) VALUES('','$nameID','$surName','1')");
			$retVal.=$query."\n";			
		
			//Artur Neumann ict.projects@nepal.inf.org
			//4.07.2013
			//change to insertQuery and save the result
			$insert_result = $dbi->insertQuery($query);
			$ok = ($dbi->isInteger($insert_result));
				
			//Artur Neumann ict.projects@nepal.inf.org
			//4.07.2013
			//get data for additional change log
			if ($ok) {
					
				
				$additional_change_log['table'] ='surname';
				$additional_change_log['recordID'] = $insert_result;
				$additional_change_log['updateType'] = 'new';
				
				
					
				array_push ($additional_change_logs, $additional_change_log);
					
			}
							
			
			
		}

        if ($ok && ($mode == 'relative') && ($staffID != $nameID)) {
		
			#enter relation of this person
			$query	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES('$staffID','$nameID','$relationship')");
			$retVal.=$query."\n";			
			
			//Artur Neumann ict.projects@nepal.inf.org
			//4.07.2013
			//change to insertQuery and save the result
			$insert_result = $dbi->insertQuery($query);
			$ok = ($dbi->isInteger($insert_result));
			
			//Artur Neumann ict.projects@nepal.inf.org
			//4.07.2013
			//get data for additional change log
			if ($ok) {
					
				
				$additional_change_log['table'] ='relation';
				$additional_change_log['recordID'] = $insert_result;
				$additional_change_log['updateType'] = 'new';
				
				
					
				array_push ($additional_change_logs, $additional_change_log);
					
			}			
			

            if ($ok) { #reverse relationship
                $query	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES('$nameID','$staffID','".get_reverse_relationship($relationship,get_gender($staffID,$dbi))."')");
                $retVal.=$query."\n";			
                
                //Artur Neumann ict.projects@nepal.inf.org
                //4.07.2013
                //change to insertQuery and save the result
                $insert_result = $dbi->insertQuery($query);
                $ok = ($dbi->isInteger($insert_result));
                	
                //Artur Neumann ict.projects@nepal.inf.org
                //4.07.2013
                //get data for additional change log
                if ($ok) {
                		
                	
                	$additional_change_log['table'] ='relation';
                	$additional_change_log['recordID'] = $insert_result;
                	$additional_change_log['updateType'] = 'new';
                	
                	
                		
                	array_push ($additional_change_logs, $additional_change_log);
                		
                }
                
            }
            $retVal.=$relationship." (".$gender.")\n";
		}
		
		if ($ok) {
			
            if ($nationality != "") {
                #insert into nationality table			
                $query	=	sprintf("INSERT INTO `nationality`(`id`,`name_id`,`nationality`,`priority`) VALUES('','$nameID','$nationality','1')");
                $retVal.=$query."\n";			

                //Artur Neumann ict.projects@nepal.inf.org
                //11.07.2013
                //change to insertQuery and save the result
                $insert_result = $dbi->insertQuery($query);
                $ok = ($dbi->isInteger($insert_result));
                 
                //Artur Neumann ict.projects@nepal.inf.org
                //11.07.2013
                //get data for additional change log
                if ($ok) {
                
                	
                	$additional_change_log['table'] ='nationality';
                	$additional_change_log['recordID'] = $insert_result;
                	$additional_change_log['updateType'] = 'new';
                	
                	
                
                	array_push ($additional_change_logs, $additional_change_log);                
                }
                
            }
		}
	}
	else if(($action	==	"Edit") || ($action	==	"Add Existing")) {
		
        $nameID = $id;
        $logType = "update";
		$oldSurname			=	$_POST['oldSurname'];
		$oldNationality		=	$_POST['oldNationality'];
		$surname_timestamp	=	$_POST['surname_timestamp'];
		$nationality_timestamp	=	$_POST['nationality_timestamp'];
		$name_timestamp		=	$_POST['name_timestamp'];
		//$address_timestamp	=	$_POST['address_timestamp'];
			
		//$timestamp_error= checkTimestamp($table,$timestamp_field,$timestamp,$check_field,$check_value)
		// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
		$dbi->checkTimestamp('name','name_timestamp',$name_timestamp,'id',$id);
		$dbi->checkTimestamp('surname','surname_timestamp',$surname_timestamp,'name_id',$id);
		$dbi->checkTimestamp('nationality','nationality_timestamp',$nationality_timestamp,'name_id',$id);

        if ($mode == 'relative') {
            $familyID 	=	$dbi->get_family_id($staffID);
            $relation_timestamp	=	$_POST['relation_timestamp'];
            $dbi->checkTimestamp('relation','relation_timestamp',$relation_timestamp,'relation_id',$id," AND `relation`.`name_id`	= '$staffID'");
        }
        else {
			if($site=='Patient'){
				$patient_inf_timestamp	=	$_POST['patient_inf_timestamp'];
				$dbi->checkTimestamp('patient_inf','patient_inf_timestamp',$patient_inf_timestamp,'patient_id',$id);
			}
			else{
				$inf_staff_timestamp	=	$_POST['inf_staff_timestamp'];
				$dbi->checkTimestamp('inf_staff','inf_staff_timestamp',$inf_staff_timestamp,'name_id',$id);
			}
        }		
		$query=sprintf("UPDATE `name` set title	=	'$title',forenames	='$foreNames',known_as	='$knownAs',marital_status	='$maritalStatus',gender	='$gender',blood_group='$bloodGroup',birth_country_id='$countryID',dob	=	'$dob',	birth_district	=	'$birthPlaceCounty',	birth_town	=	'$birthPlaceTown' where id=".$id." and name_timestamp='$name_timestamp'");			
		 
		$retVal.=$query."\n";
		$ok=($dbi->isInteger( $dbi->query($query)));

		if ($ok && ($mode == 'staff')) {
			
			if($site=='Patient')
				$query=sprintf("UPDATE `patient_inf` set religion_id	=	'$religionID',caste_id	=	'$casteID' where patient_id=".$id." and patient_inf_timestamp='$patient_inf_timestamp'");//--
			else
				$query=sprintf("UPDATE `inf_staff` set embassy_reg = '$embassy_reg',embassy_id = '$embassy_id', religion_id	=	'$religionID',caste_id	=	'$casteID',citizenship	=	'$citizenShip' where name_id=".$id." and inf_staff_timestamp='$inf_staff_timestamp'");//--
			
			//TODO try SQL injection on $id	
			

			$retVal.=$query."\n";	
			$ok=($dbi->isInteger( $dbi->query($query)));
			
			//Artur Neumann ict.projects@nepal.inf.org
			//19.07.2013
			//get data for additional change log
			if ($ok) {
			
				$result = $dbi->query("SELECT id FROM `inf_staff` WHERE  embassy_reg = '$embassy_reg' AND embassy_id = '$embassy_id' AND  religion_id	=	'$religionID' AND caste_id	=	'$casteID' AND citizenship	=	'$citizenShip'  AND  name_id=".(int)$id);
					
				$row = mysql_fetch_array($result);
			
					
				$additional_change_log['table'] ='inf_staff';
				$additional_change_log['recordID'] = $row['id'];
				$additional_change_log['updateType'] = 'update';
					
					
			
				array_push ($additional_change_logs, $additional_change_log);
			
			}			
			
			
		}
        
		//Artur Neumann
		//4.7.2013
		//no need to do this part if the surname didn't change. e.g when a new relative was added
		if	($ok && $oldSurname != $surName) {
			
			//$surName
			//for saving the old surnames and updating the priority field
			//$surname_timestamp=$_POST['surname_timestamp'];
			if($oldSurname	!=''){
				$query	="UPDATE `surname` SET `priority` =`priority`+1 WHERE `surname`.`name_id` = '$id'";
				$retVal.=$query."\n";
				$ok=($dbi->isInteger( $dbi->query($query)));
				//for saving new surname
	
				if($ok){
					
					
					$query	=	sprintf("INSERT INTO `surname`(`id`,`name_id`,`surname`,`priority`) values('','$id','$surName','1')");
					$retVal.=$query."\n";		
					//Artur Neumann ict.projects@nepal.inf.org
					//4.07.2013 
					//changed from $dbi->query to $dbi->insertQuery
					//and saves the result	
					$insert_result=$dbi->insertQuery($query);
					$ok=($dbi->isInteger( $insert_result));
					
					//Artur Neumann ict.projects@nepal.inf.org
					//4.07.2013
					//get data for additional change log					
					if ($ok) {
						
						$additional_change_log['table'] ='surname';
						$additional_change_log['recordID'] = $insert_result;
						$additional_change_log['updateType'] = 'new';
						
						
						
						array_push ($additional_change_logs, $additional_change_log);


					}

					
				}
				

			}
			else{		//if no saving of old surname	
				$query=sprintf("UPDATE 	`surname` set `surname`.`surname`	=	'$surName' where `surname`.`priority`=1 and `surname`.`name_id`	=".(int)$id);
				$retVal.=$query."\n";			
				$ok=($dbi->isInteger( $dbi->query($query)));
				
				//Artur Neumann ict.projects@nepal.inf.org
				//4.07.2013
				//get data for additional change log
				if ($ok) {

					$result = $dbi->query("SELECT id FROM `surname` WHERE `surname`.`priority`=1 and `surname`.`name_id`	=".(int)$id);
					
					$row = mysql_fetch_array($result);

					
					$additional_change_log['table'] ='surname';
					$additional_change_log['recordID'] = $row['id'];
					$additional_change_log['updateType'] = 'update';
					
					
				
					array_push ($additional_change_logs, $additional_change_log);
				
				}
				
			}
		} 
        

		
		
        if ($ok && ($mode == 'relative') && ($staffID != $id)) {
					
			//update relation table
            $existingrelationship = get_existing_relationship($id,$staffID,$dbi);
            if ($existingrelationship != "") {
                $query	=	sprintf("UPDATE `relation` set relationship =	'$relationship' where name_id	= '".(int)$staffID."' and relation_id = '".(int)$id."' and relation_timestamp='$relation_timestamp'");
                
                $ok = ($dbi->isInteger($dbi->query($query)));
                
                //Artur Neumann ict.projects@nepal.inf.org
                //4.07.2013
                //get data for additional change log
                if ($ok) {
                
                	$result = $dbi->query("SELECT id FROM `relation` WHERE name_id	= '".(int)$staffID."' and relation_id = '".(int)$id."' AND deleted=0 ORDER BY relation_timestamp  DESC  LIMIT 1");
                		
                	$row = mysql_fetch_array($result);
                
                	
                	$additional_change_log['table'] = 'relation';
                	$additional_change_log['recordID'] = $row['id'];
                	$additional_change_log['updateType'] = 'update';
                	
                	
                
                	array_push ($additional_change_logs, $additional_change_log);
                
                }
                
                
            }
            else {
                $query	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES('$staffID','$id','$relationship')");
                
                //Artur Neumann ict.projects@nepal.inf.org
                //4.07.2013
                //change to insertQuery and save the result
                $insert_result = $dbi->insertQuery($query);
                $ok = ($dbi->isInteger($insert_result));
                	
                //Artur Neumann ict.projects@nepal.inf.org
                //4.07.2013
                //get data for additional change log
                if ($ok) {
                		
                	
                	$additional_change_log['table'] ='relation';
                	$additional_change_log['recordID'] = $insert_result;
                	$additional_change_log['updateType'] = 'new';
                	
                	
                		
                	array_push ($additional_change_logs, $additional_change_log);
                		
                }
                
            }
			$retVal.=$query."\n";
			
            
            if ($ok) { #reverse relationship
                $existingrelationship = get_existing_relationship($staffID,$id,$dbi);
                $reverserelationship = get_reverse_relationship($relationship,get_gender($staffID,$dbi));
                $retVal .= "change reverse ".$existingrelationship." to ".$reverserelationship."\n";
                if ($existingrelationship != "") {
                    $query	=	sprintf("UPDATE `relation` set relationship =	'$reverserelationship' where name_id	= '$id' and relation_id = '$staffID'");

                    if ($existingrelationship != $reverserelationship) {
                   	
                    	$ok = ($dbi->isInteger($dbi->query($query)));
                    	
                    	//Artur Neumann ict.projects@nepal.inf.org
                    	//4.07.2013
                    	//get data for additional change log
                    	if ($ok) {
                    	
                    		$result = $dbi->query("SELECT id FROM `relation` WHERE relationship =	'$reverserelationship' AND name_id	= '".(int)$id."' and relation_id = '".(int)$staffID."'");
                    	
                    		$row = mysql_fetch_array($result);
                    	
                    		
                    		$additional_change_log['table'] = 'relation';
                    		$additional_change_log['recordID'] = $row['id'];
                    		$additional_change_log['updateType'] = 'update';
                    		
                    		
                    	
                    		array_push ($additional_change_logs, $additional_change_log);
                    	
                    	}
                    	
                    	
                    }
                
                }
                else {
                    $query	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES('$id','$staffID','$reverserelationship')");
                    if ($existingrelationship != $reverserelationship) {                    	
                    	//Artur Neumann ict.projects@nepal.inf.org
                    	//11.07.2013
                    	//change to insertQuery and save the result
                    	$insert_result = $dbi->insertQuery($query);
                    	$ok = ($dbi->isInteger($insert_result));
                    	 
                    	//Artur Neumann ict.projects@nepal.inf.org
                    	//11.07.2013
                    	//get data for additional change log
                    	if ($ok) {
                    	
                    		
                    		$additional_change_log['table'] ='relation';
                    		$additional_change_log['recordID'] = $insert_result;
                    		$additional_change_log['updateType'] = 'new';
                    		
                    		
                    	
                    		array_push ($additional_change_logs, $additional_change_log);
                    	
                    	}
                    	
                    }
                
                }
                if ($existingrelationship != $reverserelationship) {
                    $retVal.=$query."\n";			
                }                        
            } 
            $retVal.=$relationship." (".$gender.")\n";
        }

		if	($ok) {			
		
          if ($nationality != "") {
			//for saving the old nationality and updating the priority field
			if($oldNationality	!=''){
				$query	="UPDATE `nationality` SET `priority` =`priority`+1 WHERE `nationality`.`name_id` = '$id'";
				$retVal.=$query."\n";
				$ok=($dbi->isInteger( $dbi->query($query)));
				if($ok){
					$query	=	sprintf("INSERT INTO `nationality`(`id`,`name_id`,`nationality`,`priority`) values('','$id','$nationality','1')");
					$retVal.=$query."\n";
					
					//Artur Neumann ict.projects@nepal.inf.org
					//11.07.2013
					//change to insertQuery and save the result
					$insert_result = $dbi->insertQuery($query);
					$ok = ($dbi->isInteger($insert_result));
					 
					//Artur Neumann ict.projects@nepal.inf.org
					//11.07.2013
					//get data for additional change log
					if ($ok) {

						
						$additional_change_log['table'] ='nationality';
						$additional_change_log['recordID'] = $insert_result;
						$additional_change_log['updateType'] = 'new';
						
						

						array_push ($additional_change_logs, $additional_change_log);
					}
				}
			}
			else{		//if no saving of old nationality
                if ($nationality_timestamp == 'null') {//no existing nationality record
                	$query	=	sprintf("INSERT INTO `nationality`(`id`,`name_id`,`nationality`,`priority`) values('','$id','$nationality','1')");
					$retVal.=$query."\n";	                   
					
					//Artur Neumann ict.projects@nepal.inf.org
					//11.07.2013
					//change to insertQuery and save the result
					$insert_result = $dbi->insertQuery($query);
					$ok = ($dbi->isInteger($insert_result));
					 
					//Artur Neumann ict.projects@nepal.inf.org
					//11.07.2013
					//get data for additional change log
					if ($ok) {
					
						
						$additional_change_log['table'] ='nationality';
						$additional_change_log['recordID'] = $insert_result;
						$additional_change_log['updateType'] = 'new';
						
						
					
						array_push ($additional_change_logs, $additional_change_log);
					}
					
                }
                else {
                    $query=sprintf("UPDATE 	`nationality` set `nationality`.`nationality`	=	'$nationality' where `nationality`.`priority`=1 and `nationality`.`name_id`	=".$id	);
                    $retVal.=$query."\n";	                  
                   
                    
                    $ok = ($dbi->isInteger($dbi->query($query)));
                     
                    //Artur Neumann ict.projects@nepal.inf.org
                    //11.07.2013
                    //get data for additional change log
                    if ($ok) {
                    	 
                    	$result = $dbi->query("SELECT id FROM `nationality` WHERE `nationality`.`nationality` =	'$nationality' AND `nationality`.`priority`=1 and `nationality`.`name_id`	=".$id);
                    	 
                    	$row = mysql_fetch_array($result);
                    	 
                    	
                    	$additional_change_log['table'] = 'nationality';
                    	$additional_change_log['recordID'] = $row['id'];
                    	$additional_change_log['updateType'] = 'update';
                    	
                    	$additional_change_log['nameID'] = $id;
                    	 
                    	array_push ($additional_change_logs, $additional_change_log);
                    	 
                    }
                    
                    
                }
			}
          }
		} 		
	}

	if ($ok && ($mode == 'relative') && ($staffID != $nameID)) {
		#enter relationships for other members in same family

        //$query	=	sprintf("SELECT name.id, relation.relationship, name.gender, name.forenames FROM `name` LEFT JOIN relation ON relation.relation_id = name.id WHERE id in (select id from name where family_id = '$familyID' and id != '$staffID' and id != '$nameID') and relation.name_id = $staffID and relation.deleted = 0");            
		//Artur Neumann INF/N ict.projects@nepal.inf.org 
		//21.09.2012
		//because the relation table now has an id, we need to make the statement clearer
		$query	=	sprintf("SELECT name.id, relation.relationship, name.gender, name.forenames FROM `name` LEFT JOIN relation ON relation.relation_id = name.id WHERE name.id in (select name.id from name where name.id != '$staffID' and name.id != '$nameID') and relation.name_id = $staffID and relation.deleted = 0");   
        
		$retVal.=$query."\n";
		$result = $dbi->query($query);

		while($ok && ($row = mysql_fetch_array($result))){
        
            $othertype  =   get_other_relationship_type($row[1],$relationship);
            $other  =   get_gender_relationship($othertype,$row[2]);
            $reverseothertype = get_reverse_relationship_type($othertype);
            $reverseother = get_gender_relationship($reverseothertype,$gender);
            if ($other != "None") {
            
                $existingrelationship = get_existing_relationship($row[0],$nameID,$dbi);
                $retVal .= "change ".$existingrelationship." to ".$other."\n";
                if ($existingrelationship != "") {
                    if ($other == "Other") $other = $existingrelationship; //don't change an existing relationship to other
                    $query1	=	sprintf("UPDATE `relation` set relationship =	'$other' where name_id	= '$nameID' and relation_id = '$row[0]'");
                    
                    //Artur Neumann ict.projects@nepal.inf.org
                    //4.07.2013
                    //moved
                    if ($existingrelationship != $other) {
                    	$retVal .= $row[3].": ".$query1."\n";
                    	$ok = ($dbi->isInteger($dbi->query($query1)));
                    	
                    	//Artur Neumann ict.projects@nepal.inf.org
                    	//4.07.2013
                    	//get data for additional change log
                    	if ($ok) {
                    	
                    		$result = $dbi->query("SELECT id FROM `relation` WHERE name_id	= '".(int)$nameID."' and relation_id = '$row[0]'");
                    	
                    		$row_last_update = mysql_fetch_array($result);
                    	
                    		
                    		$additional_change_log['table'] ='relation';
                    		$additional_change_log['recordID'] = $row_last_update['id'];
                    		$additional_change_log['updateType'] = 'update';
                    		
                    		
                    	
                    		array_push ($additional_change_logs, $additional_change_log);
                    	
                    	}
                    	
                    }
                }
                else {
                    $query1	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES($nameID,".$row[0].",'".$other."')");
                    
                    //Artur Neumann ict.projects@nepal.inf.org
                    //4.07.2013
                    //moved
                    if ($existingrelationship != $other) {
                    	$retVal .= $row[3].": ".$query1."\n";                    

                    	//Artur Neumann ict.projects@nepal.inf.org
                    	//4.07.2013
                    	//change to insertQuery and save the result
                    	$insert_result = $dbi->insertQuery($query1);
                    	$ok = ($dbi->isInteger($insert_result));
                    	 
                    	//Artur Neumann ict.projects@nepal.inf.org
                    	//4.07.2013
                    	//get data for additional change log
                    	if ($ok) {

                    		
                    		$additional_change_log['table'] ='relation';
                    		$additional_change_log['recordID'] = $insert_result;
                    		$additional_change_log['updateType'] = 'new';
                    		
                    		

                    		array_push ($additional_change_logs, $additional_change_log);

                    	}
                    }
                    
                    
                }
                  

                if ($ok) { // now the reverse relation
                
                    $existingrelationship = get_existing_relationship($nameID,$row[0],$dbi);
                    $retVal .= "change reverse ".$existingrelationship." to ".$reverseother."\n";
                    if ($existingrelationship != "") {
                        if ($reverseother == "Other") $reverseother = $existingrelationship; //don't change an existing relationship to other
                        $query1	=	sprintf("UPDATE `relation` set relationship =	'$reverseother' where name_id	= '$row[0]' and relation_id = '$nameID'");

                        //Artur Neumann ict.projects@nepal.inf.org
                        //11.07.2013
                        //moved
                        $ok = ($dbi->isInteger($dbi->query($query1)));
                        
                        
                        //Artur Neumann ict.projects@nepal.inf.org
                        //11.07.2013
                        //get data for additional change log
                        if ($ok) {
                        		
                        	$result = $dbi->query("SELECT id FROM `relation` WHERE name_id	= '".$row[0]."' and relation_id = '".(int)$nameID."'");
                        		
                        	$row_last_update = mysql_fetch_array($result);
                        		
                        	
                        	$additional_change_log['table'] ='relation';
                        	$additional_change_log['recordID'] = $row_last_update['id'];
                        	$additional_change_log['updateType'] = 'update';
                        	
                        	
                        		
                        	array_push ($additional_change_logs, $additional_change_log);
                        		
                        }
                    }
                    else {
                    	
                        $query1	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES(".$row[0].",$nameID,'".$reverseother."')");
                        
                        
                         
                        //Artur Neumann ict.projects@nepal.inf.org
                        //4.07.2013
                        //change to insertQuery and save the result
                        $insert_result = $dbi->insertQuery($query1);
                        $ok = ($dbi->isInteger($insert_result));

                        //Artur Neumann ict.projects@nepal.inf.org
                        //4.07.2013
                        //get data for additional change log
                        if ($ok) {
                        
                        	
                        	$additional_change_log['table'] ='relation';
                        	$additional_change_log['recordID'] = $insert_result;
                        	$additional_change_log['updateType'] = 'new';
                        	
                        	
                        
                        	array_push ($additional_change_logs, $additional_change_log);
                        
                        }
                        
                        
                    }
                    if ($existingrelationship != $reverseother) {
                        $retVal .= $query1."\n";
                        }
                }                
			}
        }
	}  

	if ($ok && ($mode == 'relative')) {
					
		if($nextToKin	==	"Yes"){
			$query	=	sprintf("UPDATE `name` set next_of_kin_id=".$nameID." where id=".$staffID);
			$retVal.=$query."\n";
			$ok = ($dbi->isInteger($dbi->query($query)));
			
		}
		//echo "nextToKin".$nextToKinID;
	}
    
    if ($ok) {
		//update change log			
		$query="Cannot create change log. Please retry";			
		$ok=$dbi->createChangeLog($userID,'name',$nameID,$logType,'tab',$nameID);
		
		if ($ok) {
			foreach ($additional_change_logs as $additional_change_log) {
				$ok=$dbi->createChangeLog(
						$userID,
						$additional_change_log['table'],
						$additional_change_log['recordID'],
						$additional_change_log['updateType'],
						'tab',
						$nameID);
				if (!$ok) {
					break;
				}
			}
		}
	}
        
	if ($ok) {		
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
        print ("<lastID>$nameID</lastID>");
    }
	else {
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
        print ("<lastID>-1</lastID>");
	}
	
	$dbi->disconnect();
//$dbi->test_file('requestsave general '."\n\n",$retVal);	
/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('savegeneraltest.txt', "a"); 
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/
	
?>