<?php

	include_once (dirname(__FILE__).'/connect.php');	
		
	set_error_handler("myErrorHandler");	
	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);

		$userID		=	$_POST['userID'];	
		$staffID	=	$_POST['staffid'];
		$countryID			=	$_POST['birthplacecountryID'];
		//$birthPlaceCountry	=	$_POST['birthplacecountry'];
		//$countryID		=	$dbi->get_table_item('country','id','name',$birthPlaceCountry);
		//$nameID	=	$_POST['id'];
		$action	=	$_POST['action'];
		$retVal	=	$action."\n";
		
	//data for name table
		//$birthPlace	=	$_POST['birthplacecounty'].'/'.$_POST['birthplacetown'].'/'.$countryID;			
		$birthPlaceCounty	=	$_POST['birthplacecounty'];
		$birthPlaceTown		=	$_POST['birthplacetown'];
		$countryID			=	$_POST['birthplacecountryID'];
		
		$title		=	$_POST['title'];
		$foreNames	=	$_POST['forename'];
		$surName	=	$_POST['surname'];
		$knownAs	=	$_POST['knownas'];
		$maritalStatus	=	$_POST['maritalstatus'];
		$gender		 =	$_POST['gender'];
		$bloodGroup	 =	$_POST['bloodgroup'];
		$dob		 =	$_POST['dob'];
		$nextToKin	 =	$_POST['nexttokin'];
		$nationality =	$_POST['nationality'];
	//data for relation table
		$relationship	=	$_POST['relation'];
		
		
	$dbi->query("Start transaction");
	$ok = true;
	$retVal = $action."\n";
	
	if($action	==	"Add New") {
		
		//$nextToKinID	=	$dbi->get_default_kin_id();
		$nextToKinID	=	'';
		//$familyID 	=	$dbi->get_family_id($nameID);
		$familyID 	=	$dbi->get_family_id($staffID);		
		//$countryID	=	$dbi->set_value('country','name',$country);	
		//echo "country ID:".$countryID;
			
		//insert into  name table		
		$query	=	sprintf("INSERT INTO		`name`(id,family_id,next_of_kin_id,title,forenames,known_as,marital_status,gender,blood_group,dob,birth_town,birth_district,birth_country_id) 
		VALUES('','$familyID','$nextToKinID','$title','$foreNames','$knownAs','$maritalStatus','$gender','$bloodGroup','$dob','$birthPlaceTown','$birthPlaceCounty','$countryID')");	
		$retVal.=$query."\n";

		$ok = ($dbi->isInteger($relativeID = $dbi->insertQuery($query)));		
		//echo "relative ID".$relativeID;
			
		if ($ok) {
		
			#enter relation of this person
			$query	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES('$staffID','$relativeID','$relationship')");
			$retVal.=$query."\n";
			
			$ok = ($dbi->isInteger($dbi->query($query)));		
		}

		if ($ok) {
		
			#enter relation for other staff members in same family
		
			$query	=	sprintf("SELECT name_id from `inf_staff` where name_id in (select id from name where family_id = '$familyID') and name_id != '$staffID'");
			$retVal.=$query."\n";
			$result = $dbi->query($query);

			while($ok && ($row = mysql_fetch_array($result))){			
				$query	=	sprintf("INSERT INTO `relation`(name_id,relation_id,relationship) VALUES(".$row[0].",$relativeID,'Other')");
				$retVal.=$query."\n";
				$ok = ($dbi->isInteger($dbi->query($query)));					
			}

		}
		
		if ($ok) {
			
			#insert into surname table
			$surName	=	$_POST['surname'];
		//	$query	=	sprintf("INSERT into `surname`(id,name_id,surname) values('','$relativeID','$surName')");
			$query	=	sprintf("INSERT INTO `surname`(`id`,`name_id`,`surname`,`priority`) values('','$relativeID','$surName','1')");
			$retVal.=$query."\n";
			
			$ok = ($dbi->isInteger($dbi->query($query)));		
		}
			
		if ($ok) {
		
			#update next_of_kin_id in name table
		
			if($nextToKin	==	"Yes"){
				$query	=	sprintf("UPDATE `name` set next_of_kin_id=".$relativeID." where id=".$staffID);
				$retVal.=$query."\n";
				
				$ok = ($dbi->isInteger($dbi->query($query)));
			}
			
			//echo "nextToKin".$nextToKinID;
		}
		if ($ok) {
				
			#insert into nationality table			
			$query	=	sprintf("INSERT INTO `nationality`(`id`,`name_id`,`nationality`,`priority`) values('','$relativeID','$nationality','1')");
			$retVal.=$query."\n";
			
			$ok = ($dbi->isInteger( $dbi->query($query)));
		}
		
		if ($ok) {	
			$query="Cannot create change Log. Please Retry";
			$retVal.=$query."\n";	
			
			$ok=$dbi->createChangeLog($userID,'name',$relativeID,'new','tab',$relativeID);
		}


	//$nameID=$relativeID;
	//$updateType	=	'new';
	
		
	}
	else if($action	==	"Edit") {
		//$updateType	=	'update';
		$surname_timestamp	=	$_POST['surname_timestamp'];
		$nationality_timestamp	=	$_POST['nationality_timestamp'];
		$relation_timestamp	=	$_POST['relation_timestamp'];
		$name_timestamp		=	$_POST['name_timestamp'];
		//$address_timestamp	=	$_POST['address_timestamp'];
		
	
		$nameID	=	$_POST['id'];
		$oldSurname	=	$_POST['oldSurname'];
		$oldNationality	=	$_POST['oldNationality'];
		// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
		$dbi->checkTimestamp('name','name_timestamp',$name_timestamp,'id',$nameID);
		$dbi->checkTimestamp('surname','surname_timestamp',$surname_timestamp,'name_id',$nameID);
		$dbi->checkTimestamp('nationality','nationality_timestamp',$nationality_timestamp,'name_id',$nameID);
		$dbi->checkTimestamp('relation','relation_timestamp',$relation_timestamp,'relation_id',$nameID," AND `relation`.`name_id`	= '$staffID'");
		
		
		$dbi->query("Start transaction");
			
		//update name table
		$query	=	sprintf("UPDATE `name` set 		
		title	=	'$title',
		forenames	=	'$foreNames',
		known_as	=	'$knownAs',
		marital_status	=	'$maritalStatus',
		gender	=	'$gender',
		blood_group	=	'$bloodGroup',
		birth_country_id='$countryID',
		dob	=	'$dob',
		birth_district	=	'$birthPlaceCounty',
		birth_town	=	'$birthPlaceTown'
		where id=".$nameID." and name_timestamp='$name_timestamp'");
		$retVal.=$query."\n";
		
		$ok = ($dbi->isInteger($dbi->query($query)));
			
		if ($ok) {
					
			//update relation table
			$query	=	sprintf("UPDATE `relation` set relationship =	'$relationship' where name_id	= '$staffID' and relation_id = '$nameID' and relation_timestamp='$relation_timestamp'");
			$retVal.=$query."\n";

			$ok = ($dbi->isInteger($dbi->query($query)));
		}
		
		if ($ok) {
			//$surName
			//for saving the old surnames and updating the priority field
			if($oldSurname	!=''){
				$query	="UPDATE `surname` SET `priority` =`priority`+1 WHERE `surname`.`name_id` = '$nameID'";
				$retVal.=$query."\n";
				$ok=($dbi->isInteger( $dbi->query($query)));
				//for saving new surname
				if($ok){
						$query	=	sprintf("INSERT INTO `surname`(`id`,`name_id`,`surname`,`priority`) 
						values('','$nameID','$surName','1')");
					$retVal.=$query."\n";	
			
					$ok=($dbi->isInteger( $dbi->query($query)));
				}
			}
			else{		//if no saving of old surname	
				$query=sprintf("UPDATE 	`surname` set
				`surname`.`surname`	=	'$surName' where `surname`.`priority`=1 and `surname`.`name_id`	=".$nameID);
				$retVal.=$query."\n";
			
				$ok=($dbi->isInteger( $dbi->query($query)));
			}
			
		}
			
		if ($ok) {
					
			if($nextToKin	==	"Yes"){
				$query	=	sprintf("UPDATE `name` set next_of_kin_id=".$nameID." where id=".$staffID);
				$retVal.=$query."\n";

				$ok = ($dbi->isInteger($dbi->query($query)));
			}
			//echo "nextToKin".$nextToKinID;
		}
		if	($ok) {			
		
			//for saving the old nationality and updating the priority field
			if($oldNationality	!=''){
				$query	="UPDATE `nationality` SET `priority` =`priority`+1 WHERE `nationality`.`name_id` = '$nameID'";
				$retVal.=$query."\n";
				$ok=($dbi->isInteger( $dbi->query($query)));
				if($ok){
						$query	=	sprintf("INSERT INTO `nationality`(`id`,`name_id`,`nationality`,`priority`) 
						values('','$nameID','$nationality','1')");
					$retVal.=$query."\n";	
			
					$ok=($dbi->isInteger( $dbi->query($query)));
				}
			}
			else{		//if no saving of old nationality	
				$query=sprintf("UPDATE 	`nationality` set
				`nationality`.`nationality`	=	'$nationality' where `nationality`.`priority`=1 and `nationality`.`name_id`	=".$nameID	);
				$retVal.=$query."\n";
			
				$ok=($dbi->isInteger( $dbi->query($query)));
			}
		}

		if ($ok) {
			//update change log	
//$this->test_file("\n change log\n");			
			$query="Cannot create change Log. Please Retry";
			$retVal.=$query."\n";			
			$ok=$dbi->createChangeLog($userID,'name',$nameID,'update','tab',$nameID);
		}	
		
	}
	
	
		
	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
			
	$dbi->disconnect();	

/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('saverelativetest.txt', "a"); 
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/

?>
