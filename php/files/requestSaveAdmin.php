<?php 
include_once (dirname(__FILE__).'/connect.php');

//	set_error_handler("myErrorHandler");	
	
	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);

	//$name='Ram';
	//$tableName='organisation_rep';
	//$action='Add New';
	//$organisationID	=	1;
	//$email         	=	"e@mail";
	//$knownas		=	"knownas";
	
/*	
	$strPermission='staff_add=n,review_view=y,service_add=n,movement_add=n,leave_add=n,training_add=n,hospitalisation_add=n,review_add=n,staff_delete=n,service_delete=n,movement_delete=n,leave_delete=n,training_delete=n,hospitalisation_delete=n,review_delete=n,staff_edit=n,service_edit=n,movement_edit=n,leave_edit=n,training_edit=n,hospitalisation_edit=n,review_edit=n,staff_view=y,service_view=y,movement_view=y,leave_view=y,training_view=y,hospitalisation_view=n';
*/
	$DB=$dbi->getdbname();
	$host='localhost';
	$name=$_POST['name'];
	$tableName=$_POST['tableName'];
	$action=$_POST['action'];
    $retVal="";

//$dbi->test_file("requestSaveAdmin","$name $tableName");
	
    if ($tableName != 'users') {
        $userID=$_POST['userID'];
    }
	else if ($action == "Edit") {
		$userID=$_POST['id'];
	}
	
	$disallowed = ($dbi->isAdmin() === false);
	
	switch($tableName){
	/*case 'organisation_rep':
		$organisationID	=	$_POST['organisationID'];
		$email         	=	$_POST['email'];
		$knownas		=	$_POST['knownas'];
		break;
	*/
	case 'site':
		//$siteSpecificID=987;
		$siteSpecificID=$_POST['siteSpecificID'];
		//$smtpServer=$_POST['smtpServer'];
      	//$smtpUserID=$_POST['smtpUserID'];
      	//$smtpPassword=$_POST['smtpPassword'];  
		$emailDomain=$_POST['emailDomain'];  	
		$fiscalStart=$_POST['fiscalStart'];
        $timeout=$_POST['timeout'];
		$hospital_limit=$_POST['hospital_limit'];
        $maintenance=$_POST['maintenance'];
		$location_id=$_POST['location_id'];
		$programme_id=$_POST['programme_id'];
		$disallowed = ($dbi->isSuperAdmin() === false);
		break;
		
	case 'search_history':
		$search_query=$_POST['query'];
        $search_4_all=$_POST['search_4_all'];
		//if any updating query is made then make it blank
		if((stripos($search_query, ' update '))
		||(stripos($search_query, ' replace '))
		||(stripos($search_query, ' create '))
		||(stripos($search_query, ' delete '))
		||(stripos($search_query, ' insert ')) )
			$search_query='';
		
        $search_query=str_replace('"',"'",$search_query);  
        $search_query=str_replace("\\\'","'",$search_query);  
		$disallowed = ($dbi->isSuperAdmin() === false);		
		break;
		
	case 'users':
		$userSurname=$_POST['userSurname'];
		$userEmail=$_POST['userEmail'];
		$changePassword=$_POST['changePassword'];
		$userIncompleteWarning=$_POST['userIncompleteWarning'];		
		$changedPasswordSettings=$_POST['changedPasswordSettings'];
        $defaultSearchID=$_POST['defaultSearchID'];
		$disallowed = ($dbi->isLoggedIn($userID) === false);
		break;
	
	case 'security_user_permission':
		$addStatus	=	$_POST['addStatus'];
		$deleteStatus=	$_POST['deleteStatus'];
		$editStatus	=	$_POST['editStatus'];
		$viewStatus	=	$_POST['viewStatus'];	
		$userEmail 	=	$_POST['userEmail'];
		$userType	=	$_POST['userType'];
		$userNameID=$_POST['userNameID'];
		$password =		$_POST['password'];
		$strAllTabNames=$_POST['allTabNames'];		
		$arrAllTabNames=explode(",",$strAllTabNames);
		$assignGroup= 	$_POST['groupID'];//if value is 0 then no group change in EDIT mode
		$strPermission=$addStatus.''.$deleteStatus.''.$editStatus.''.$viewStatus;
		$strPermission=substr_replace($strPermission ,"",-1);//remove the last character which is  "," 
		$arrPermission   = explode(",",$strPermission);
		$disallowed = ($dbi->isSuperAdmin() === false);
		break;
	
	case 'security_role_permission':
		$addStatus	=	$_POST['addStatus'];
		$deleteStatus=	$_POST['deleteStatus'];
		$editStatus	=	$_POST['editStatus'];
		$viewStatus	=	$_POST['viewStatus'];				
		$strPermission=$addStatus.''.$deleteStatus.''.$editStatus.''.$viewStatus;
		$strPermission=substr_replace($strPermission ,"",-1);//remove the last character which is  "," 
		$arrPermission   = explode(",",$strPermission);	
		$disallowed = ($dbi->isSuperAdmin() === false);
		break;
		
	case 'leave_type':
		$entitlement		=	$_POST['entitlement'];
		$carry_forward		=	$_POST['carry_forward'];
		break;

	case 'location':
		$dept		=	$_POST['dept'];	
		$phoneID = $_POST['phoneID'];
		$addressID = $_POST['addressID'];
		$emailID =$_POST['emailID'];		
		break;
			
	case 'hospital':
		$countryID		=	$_POST['country_id'];
		$type			=	$_POST['type'];	
		break;
		
	case 'programme':
		$code			=	$_POST['code'];	
		break;

	case 'agreement':
		$description	=	$_POST['description'];
		$date_from		=	$_POST['date_from'];
		$date_until		=	$_POST['date_until'];
		break;
		
	case 'course':
		$typeID			=	$_POST['course_type_id'];
		$subjectID		=	$_POST['course_subject_id'];
		$location		=	$_POST['location'];
		$provider		=	$_POST['provider'];
		$date_from		=	$_POST['date_from'];
		$date_until		=	$_POST['date_until'];
		$dates_fixed	=	$_POST['dates_fixed'];
		break;
			
	case 'country':
		$istd_code	=	$_POST['istd_code'];
		$cur	=	$_POST['cur'];			
		break;
		
	case 'post':
	case 'visapost':
		$tableName='post';
		$type=$_POST['type'];
		$sectionID=$_POST['section_id'];
		$unitID=$_POST['unit_id'];
		$programmeID=$_POST['programme_id'];
		$description=$_POST['description'];		
		$status=$_POST['status'];
		$hours=$_POST['hours'];
		$email=$_POST['email'];
		$visaID=$_POST['visa_id'];
		$managerID = $_POST['managerID'];
		$jobReviewer = $_POST['jobReviewer'];
		$medicalReviewer = $_POST['medicalReviewer'];
		$personnelReviewer = $_POST['personnelReviewer'];
		$provisional = $_POST['provisional'];
		$active = $_POST['active'];
		$agreementID = $_POST['agreementID'];
		break;
		
	case 'organisation':
		//$secondment_from = $_POST['secondment_from'];
		//$secondment_to = $_POST['secondment_to'];
		//$local_support_provider = $_POST['local_support_provider'];
		//$church = $_POST['church'];		
		//$embassy = $_POST['embassy'];			
		$phoneID = $_POST['phoneID'];
		$addressID = $_POST['addressID'];
		$emailID = $_POST['emailID'];
        $detailIDList = $_POST['organisationTypes'];
        $extraTable = 'organisation_link';
        $extraID = 'organisation_id';
        $extraField = 'organisation_type_id';
		break;
		
	case 'patient_appliance_type':
		$type=$_POST['type'];
		break;
	
	case 'treatment_regimen':
		$treatmentCategoryID=$_POST['treatmentCategoryID'];
		break;
		
	case 'treatment_result':
		$treatmentCategoryID=$_POST['treatmentCategoryID'];
		$success=$_POST['success'];
		break;	
	
	case 'treatment_case':
		$treatmentCategoryID=$_POST['treatmentCategoryID'];
		$positive=$_POST['positive'];
		break;	
		
	case 'treatment_reason':
		$treatmentCategoryID=$_POST['treatmentCategoryID'];
		$main=$_POST['main'];
        if ($main=='Yes') $name=str_replace(' ','-',$name); //can't have spaces in main reason - messes up XML structure in detail reasons
		$type=$_POST['type'];
		break;	
	}

    $dbi->query("Start transaction");
     
	if ($disallowed) {
		$action = "None";
        $ok = false;
        $query = "Update disallowed for ".$tableName;
	}
	
    if($dbi->maintenance() && ($tableName != 'site') && ($tableName != 'search_history') && ($tableName!='security_user_permission') && ($tableName!='security_role_permission')) { //knobble any further update except for site, queries and user/group permissions
        $action = "None";
        $ok = false;
        $query = "Maintenance mode - try again later";
    }
	
	if($action	==	"Add New") {
	
		switch($tableName){
		/*
		case 'organisation_rep':
			$query	=	sprintf("INSERT INTO `organisation_rep` "."(id,name,organisation_id,email,known_as) 
			VALUES ('','$name','$organisationID','$email','$knownas')");	
			break;
		*/
		case 'treatment_reason':			
			$query	=	sprintf("INSERT INTO `treatment_reason` "."(id,name,treatment_category_id,`type`,main) VALUES ('','$name','$treatmentCategoryID','$type','$main')");	
//$dbi->test_file("requestSaveAdmin","$query");	
		break;
		
		case 'treatment_case':			
			$query	=	sprintf("INSERT INTO `treatment_case` "."(id,name,treatment_category_id,positive) VALUES ('','$name','$treatmentCategoryID','$positive')");	
//$dbi->test_file("requestSaveAdmin","$query");	
		break;
		
		case 'treatment_result':			
			$query	=	sprintf("INSERT INTO `treatment_result` "."(id,name,treatment_category_id,success) VALUES ('','$name','$treatmentCategoryID','$success')");	
//$dbi->test_file("requestSaveAdmin","$query");	
		break;
	
		case 'treatment_regimen':			
			$query	=	sprintf("INSERT INTO `treatment_regimen` "."(id,name,treatment_category_id) VALUES ('','$name','$treatmentCategoryID')");	
			break;
		
		case 'patient_appliance_type':			
			$query	=	sprintf("INSERT INTO `patient_appliance_type` "."(id,name,`type`) VALUES ('','$name','$type')");	
			break;
		
		case 'site':
			$query	=	sprintf("INSERT INTO `site` "."(id,fiscal_year_start,name,site_specific_id,email_domain,timeout,hospitalisation_limit,maintenance,location_id,programme_id) VALUES ('','$fiscalStart','$name','$siteSpecificID','$emailDomain',$timeout,$hospital_limit,$maintenance,$location_id,$programme_id)");	
			break;
			
		case 'search_history':			
			$query	=	sprintf("INSERT INTO `search_history` "."(id,name,search_4_all,query) VALUES ('','$name','$search_4_all',\"$search_query\")");	
			break;
			
		case 'leave_type':
			$query	=	sprintf("INSERT INTO `leave_type` "."(id,name,entitlement,carry_forward) VALUES ('','$name','$entitlement','$carry_forward')");	
			break;

		case 'location':
			break;
			
		case 'hospital':
			$query	=	sprintf("INSERT INTO `hospital` "."(id,name,`type`,country_id) VALUES ('','$name','$type','$countryID')");	
			break;
			
		case 'programme':
			$query	=	sprintf("INSERT INTO `programme` "."(id,name,code) VALUES ('','$name','$code')");	
			break;

		case 'agreement':
			$query	=	sprintf("INSERT INTO `agreement` "."(id,name,description,date_from,date_until) VALUES ('','$name','$description','$date_from','$date_until')");			
			break;
			
		case 'course':
			$query	=	sprintf("INSERT INTO `course` "."(id,name,course_type_id,course_subject_id,location,provider,date_from,date_until,dates_fixed) VALUES ('','$name','$typeID','$subjectID','$location','$provider','$date_from','$date_until','$dates_fixed')");			
			break;
			
		case 'country':	
			$query	=	sprintf("INSERT INTO `country` "."(id,name,istd_code,currency) VALUES ('','$name','$istd_code','$cur')");	
			break;
		
		case 'post':
            $query=sprintf("INSERT INTO `post` "."(id,email,visa_id,name,unit_id,section_id,programme_id,`type`,`status`,hours,description,manager_id,job_reviewer,personnel_reviewer,medical_reviewer,provisional,active,agreement_id) VALUES ('','$email','$visaID','$name','$unitID','$sectionID','$programmeID','$type','$status','$hours','$description','$managerID','$jobReviewer','$personnelReviewer','$medicalReviewer','$provisional','$active','$agreementID')");
			break;
			
		case 'security_role_permission':
       
            $ok = true;
            
			//$groupName=$_POST['groupName'];
			$strAllTabNames=$_POST['allTabNames'];
	//$dbi->test_file("strAllTabNames\n $strAllTabNames");		
			$arrAllTabNames=explode(",",$strAllTabNames);
			
            //Insert the new group into the security_role table
			$query="INSERT INTO `security_role` (`name`) VALUES ('$name')";
            
            $retVal.=$query."\n";
			$ok = ($dbi->isInteger($groupID=$dbi->insertQuery($query)));
            
            if ($ok) {
                //Insert all fields with all default placeholders for tab names and permission modes 	
                $ok = $dbi->insertDefaultGroupPermission($groupID,$arrAllTabNames);		
			}
            if ($ok) {
                //Update the permissions For the given tabs whose information is given by the client 	
                $ok = $dbi->updateGroupPermission($arrPermission,$groupID,$userID,'new');
            }
            
			break;
            
			case 'security_user_permission':	
			//$userType=$_POST['userType'];
            $ok = true;
           
			//$password=$dbi->getDefaultPassword($name);
			/*
			$dbi->query("use mysql");
			//create mysql.user table user
            $query="CREATE USER '$name'@'$host'";	
		
            $retVal.=$query."\n";
			$ok = ($dbi->isInteger($newUserID=$dbi->insertQuery($query)));  
			//create mysql.user table password
			if ($ok) {

				$query="update mysql.user set password=MD5('$password') where host='$host' and user='$name'";
				$ok = ($dbi->isInteger($newUserID=$dbi->insertQuery($query))); 

			}	//Now if admin allow SELECT for all tables , for non-admin user allow SELECT for all tables except some admin tables		
			if ($ok) {

				$query="initialise grants";
				$ok=$dbi->initaliseDbGrants($userType,$name,$host,$action);

			}
			$dbi->query("use $DB");
			*/
			//create inf_personnel application user in inf.users
			if ($ok) {
			
			    $query2 =	sprintf("SELECT id FROM `users` WHERE user_name ='".$name."' AND deleted = 1");
                $retVal.=$query2."\n";
                $result =	$dbi->query($query2);
			
                if(mysql_num_rows($result)	==	0) {// doesn't already exist
                    $query="INSERT INTO `users` (`user_name`,`role_id`,`password`,`email`, `password_changed`, `name_id`) VALUES ('$name','$assignGroup','". MD5($password)."','$userEmail',1,'$userNameID')";	
					$retVal.=$query."\n";
					$ok = ($dbi->isInteger($newUserID=$dbi->insertQuery($query)));
					if ($ok) {
						$query="Failed to insert default user permissions";
						//Insert all fields with all default placeholders for tab names and permission modes 	
						$ok = $dbi->insertDefaultUserPermission($newUserID,$arrAllTabNames);
					}					
                }
                else { //undelete if it has been deleted
					$row=mysql_fetch_assoc($result);
					$newUserID=$row['id'];
					$query="UPDATE `users` SET deleted=0, password_changed=1, password='". MD5($password)."', name_id='".$userNameID."', role_id='".$assignGroup."', name='".$name."',email='".$userEmail."',search_id=0 WHERE id =".$newUserID." AND deleted=1";
                    $retVal.=$query."\n";
                    $ok = ($dbi->isInteger($dbi->query($query)));
                }
			}

            if ($ok) {
                $query="Failed to assign user group permissions";
                //Assign the groupPermissions (for user of both application user table and mysql user table)
                $ok = $dbi->changeUserGroup($newUserID,$assignGroup,$name,$host,$action);      
		    }			
		/*	if ($ok) {
                //insert permissions in mysql privileges 	
                $ok = $dbi->updateMysqlUserPermission($action,$arrAllTabNames,$arrPermission,$name,$host);
  
			}
			*/
            if ($ok) { 
                $query="Failed to update custom user permissions";
                //Update the permissions For the given tabs whose information is given by the client 	
                $ok = $dbi->updateUserPermission($arrAllTabNames,$arrPermission,$newUserID,$userID,'new'); 
				//here userID is application user id not inf.users.id BUT  $newUserID is inf.users.id 
            }
           if ($ok) {
            	//send Email to the user
                $query = "Failed to send email to user"; //Message if sending email fails
				$ok = $dbi->sendMail($userEmail,$password,$userID,$name);
            }
			break;			
			
		case 'organisation':
//$dbi->test_file("requestSaveAdmin.php Add New \n\n",$query);	
			break;

		default:
			$query = sprintf("INSERT INTO `$tableName` "."(id,name) VALUES ('','$name')");	
			break;
		}
		
		if(($tableName!='security_user_permission' ) && ($tableName!='security_role_permission' )){
			//$dbi->printTransactionInfo($ID=$dbi->insertQuery($query));
            
            if(($tableName=='organisation') || ($tableName=='location')) { //need to create a family record with the same ID

                $query1 = "INSERT INTO family (id) VALUES ('')";
                $retVal.=$query1."\n";
                $ok=($dbi->isInteger($familyID=$dbi->insertQuery($query1)));
                
                if($ok) {
                
                    switch ($tableName) {
                    case 'organisation':
                        $query=sprintf("INSERT INTO `organisation`(id,name,address_id,email_id,phone_id) VALUES('$familyID','$name','$addressID','$emailID','$phoneID')");
                        break;
                    
                    case 'location':
                        $query	=	sprintf("INSERT INTO `location` "."(id,name,dept,phone_id,address_id,email_id) VALUES ('$familyID','$name','$dept','$phoneID','$addressID','$emailID')");	
                        break;
                    }
                    
                    $ok=($dbi->isInteger($id=$dbi->insertQuery($query)));
                }
            }
            else {			
                $ok=($dbi->isInteger($id=$dbi->insertQuery($query)));
            }
            
            $retVal.=$query."\n";
            
			if($ok){//insert changes information to change log table
                $query		=" Cannot create change log. Please retry ";//message to display when createChangeLog() fails
				$ok=$dbi->createChangeLog($userID,$tableName,$id,'new','admin');	
			}
		}	
	
	}//end of if 'Add New'

	else if(($action	==	"Edit") &&($tableName!='security_user_permission' ) &&($tableName!='security_role_permission' )){

		$id=$_POST['id'];
		$timestamp	=	$_POST['timestamp'];
		// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
		$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
		
		switch($tableName){
		/*
		case 'organisation_rep':
			$query	=	sprintf("UPDATE `organisation_rep` SET name='".$name."',email='".$email."',organisation_id='".$organisationID."',known_as='".$known_as."' where id=".$id);			
			break;
		*/
		
		case 'treatment_reason':			
			$query	=	sprintf("UPDATE `treatment_reason` SET name='".$name."',treatment_category_id='".$treatmentCategoryID."',main='".$main."',`type`='".$type."' where id=".$id." and timestamp='$timestamp'");	
			break;
		
		case 'treatment_case':			
			$query	=	sprintf("UPDATE `treatment_case` SET name='".$name."',treatment_category_id='".$treatmentCategoryID."',positive='".$positive."' where id=".$id." and timestamp='$timestamp'");	
			break;
		
		case 'treatment_result':			
			$query	=	sprintf("UPDATE `treatment_result` SET name='".$name."',treatment_category_id='".$treatmentCategoryID."',success='".$success."' where id=".$id." and timestamp='$timestamp'");	
			break;
		
		case 'treatment_regimen':			
			$query	=	sprintf("UPDATE `treatment_regimen` SET name='".$name."',treatment_category_id='".$treatmentCategoryID."' where id=".$id." and timestamp='$timestamp'");	
			break;
		
		case 'patient_appliance_type':			
			$query	=	sprintf("UPDATE `patient_appliance_type` SET name='".$name."',`type`='".$type."' where id=".$id." and timestamp='$timestamp'");	
			break;
		
		case 'site':
			$query	=	sprintf("UPDATE `site` SET email_domain='".$emailDomain."' , maintenance='".$maintenance."' ,fiscal_year_start='".$fiscalStart."' ,timeout='".$timeout."' ,hospitalisation_limit='".$hospital_limit."' ,location_id='".$location_id."' ,programme_id='".$programme_id."' where id=".$id." and timestamp='$timestamp'");	
			break;
			
		case 'search_history':			
			$query	=	sprintf("UPDATE `search_history` SET name='".$name."',search_4_all='".$search_4_all."',query='".$search_query."' where id=".$id." and timestamp='$timestamp'");	
			break;
			
		case 'users':
			if($changePassword=='no'){
				$query=sprintf("UPDATE `users` SET name='".$name."',email='".$userEmail."',search_id='".$defaultSearchID."',lastname='".$userSurname."',incomplete_warning='".$userIncompleteWarning."'where id=".$id);
			}
			else{
				$newPassword=$_POST['newPassword'];
			
				$query="UPDATE `users` SET password='".$newPassword."', name='".$name."',email='".$userEmail."',search_id='".$defaultSearchID."',lastname='".$userSurname."',incomplete_warning='".$userIncompleteWarning."' ";
				if($changedPasswordSettings!='')
					$query.=" ,password_changed=0 ";
				$query.=" where id=".$id;
				$query=sprintf($query);							
			}
			break;
            
		case 'leave_type':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			$query	=	sprintf("UPDATE `leave_type` SET name='".$name."',entitlement='".$entitlement."',carry_forward='".$carry_forward."' where id=".$id." and timestamp='$timestamp'");	
			break;

		case 'location':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			$query	=	sprintf("UPDATE `location` SET name='".$name."',dept='".$dept."',address_id='".$addressID."',email_id='".$emailID."',phone_id='".$phoneID."' where id=".$id." and timestamp='$timestamp'");	
			break;
			
		case 'hospital':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			$query	=	sprintf("UPDATE `hospital` SET name='".$name."',`type`='".$type."',country_id='".$countryID."' where id=".$id." and timestamp='$timestamp'");	
			break;
			
		case 'programme':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			$query	=	sprintf("UPDATE `programme` SET name='".$name."',`code`='".$code."' where id=".$id." and timestamp='$timestamp'");	
			break;
			
		case 'agreement':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			$query	=	sprintf("UPDATE `agreement` SET name='".$name."',description='".$description."',date_from='".$date_from."',date_until='".$date_until."' where id=".$id." and timestamp='$timestamp'");		
			break;
		
		case 'course':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			$query	=	sprintf("UPDATE `course` SET name='".$name."',course_type_id='".$typeID."',course_subject_id='".$subjectID."',location='".$location."',provider='".$provider."',date_from='".$date_from."',date_until='".$date_until."',dates_fixed='".$dates_fixed."' where id=".$id." and timestamp='$timestamp'");		
			break;	
			
		case 'country':	
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			$query	=	sprintf("UPDATE `country` SET name='".$name."',istd_code='".$istd_code."',currency='".$cur."' where id=".$id." and timestamp='$timestamp'");	
			break;
		
		case 'post':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);
			//Artur Neumann INFN 14.12.2012 
			//the sprintf statement before did not use the sprintf formating abilities and it 
			//rejected of-course the % character to be used in the strings because % is a formating character for 
			//sprintf
			//$query=sprintf("UPDATE `post` SET name='".$name."',email='".$email."',visa_id='".$visaID."',section_id='".$sectionID."',unit_id='".$unitID."',programme_id='".$programmeID."',`type`='".$type."',`status`='".$status."',hours='".$hours."',description='".$description."',manager_id='".$managerID."',personnel_reviewer='".$personnelReviewer."',job_reviewer='".$jobReviewer."',medical_reviewer='".$medicalReviewer."',provisional='".$provisional."',active='".$active."',agreement_id='".$agreementID."' where id=".$id." and timestamp='$timestamp'");
			
	        $query=sprintf("UPDATE `post` SET name='%s',email='%s',visa_id='%d',section_id='%d',unit_id='%d',programme_id='%s',".
	        			   "`type`='%s',`status`='%s',hours='%s',description='%s',manager_id='%d',".
	        			   "personnel_reviewer='%s',job_reviewer='%s',medical_reviewer='%s',provisional='%s',".
	        			   "active='%s',agreement_id='%d' where id='%d' and timestamp='%s'",
	        				$name,$email,$visaID,$sectionID,$unitID,$programmeID,$type,$status,$hours,$description,
	        				$managerID,$personnelReviewer,$jobReviewer,$medicalReviewer,$provisional,$active,$agreementID,
	        				$id,$timestamp);
			break;			
			
		case 'organisation':
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);		
			$query=sprintf("UPDATE `organisation` SET name='".$name."',address_id='".$addressID."',email_id='".$emailID."',phone_id='".$phoneID."' WHERE id=".$id." and timestamp='$timestamp'");			
//$dbi->test_file("requestSaveAdmin.php Edit \n\n",$query);	
			break;			
			
		default:
			// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
			//$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'id',$id);			
			$query	=	sprintf("UPDATE `$tableName` SET name='".$name."' where id=".$id." and timestamp='$timestamp'");	
			break;
		}
		
		//$dbi->printTransactionInfo($dbi->query($query));

        $retVal.=$query."\n";		
		$ok=($dbi->isInteger($dbi->query($query)));
		//$ok=false;	
		if($ok){//insert changes information to change log table				
			$query		=" Cannot create change log. Please retry ";//message to display when createChangeLog() fails
			$ok=$dbi->createChangeLog($userID,$tableName,$id,'update','admin');	
		}		
	}			
	else if(($action ==	"Edit")&&($tableName=='security_user_permission' )){
    
		$editedUserID		=	$_POST['editedUserID'];
		$timestamp	=	$_POST['timestamp'];	
		$queryMysql="";
		$changePsw=false;
	/*	if($password!=''){//password changed 
			$changePsw=true;
			$psw=MD5($password);
			$queryMysql.="update mysql.user set password= '$psw' where user='$oldName' and host='$host'";			
		}	
		*/
		// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
		$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'user_id',$editedUserID);
		        
        $query	=	"UPDATE `users` SET user_name='".$name."',name_id='".$userNameID."',email='".$userEmail."' ";
        
        if ($assignGroup != 0) {
            $query  .=	", role_id='".$assignGroup."' ";
        }
            
		if($password!=''){
			$query	.=", `password_changed` =  '1', `password`='".MD5($password)."' ";			
		}
		$query.=" where id=".$editedUserID;	
			
		$query	=	sprintf($query);

        $retVal.=$query."\n";
		$ok=($dbi->isInteger( $dbi->query($query)));
/*
		$dbi->query('use mysql');
       	if ($ok && $changePsw) {
			$query=$queryMysql;
            $retVal.=$query."\n";
            $ok=($dbi->isInteger( $dbi->query($queryMysql)));
		}	
		
		if ($ok) {
                //insert permissions in mysql privileges 
				$query="update mysql user permission";
				$retVal.=$query."\n";
                $ok = $dbi->updateMysqlUserPermission($action,$arrAllTabNames,$arrPermission,$name,$host);
            }
		$dbi->query("use $DB");
		*/
        if ($ok && ($assignGroup != 0)) {
			//initialise admin level table permission in for mysql user	
			//$query="initialise database grants";
			//$retVal.=$query."\n";
			//$ok=$dbi->initaliseDbGrants($userType,$name,$host,$action);	
           
			if($ok){//Change the groupPermissions for application User and mysql user both
				$retVal.='change user group'."\n";
				$ok = $dbi->changeUserGroup($editedUserID,$assignGroup,$name,$host,$action);
				}        }
		if ($ok) {
            //Update the permissions For the given tabs whose information is given by the client 	
			$retVal.='update user permission'."\n";			
            $ok = $dbi->updateUserPermission($arrAllTabNames,$arrPermission,$editedUserID,$userID,'update');
		//here userID is application user id not inf.users.id BUT  $editedUserID is inf.users.id 
        }
       if ($ok) {
            if($password!=''){
            	//send Email to the user
                $query = "Failed to send email to user";
				$ok = $dbi->sendMail($userEmail,$password,$userID,$name);
            }
        }
	}
	
    else if(($action	==	"Edit")&&($tableName=='security_role_permission' )){
	
		$groupID= 	$_POST['groupID'];	
		$timestamp	=	$_POST['timestamp'];
		//CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
		$dbi->checkTimestamp($tableName,'timestamp',$timestamp,'role_id',$groupID);
        
		$query	=	sprintf("UPDATE `security_role` SET name='".$name."' where id=".$groupID);

        $retVal.=$query."\n";        
		$ok=($dbi->isInteger( $dbi->query($query)));
        
		if ($ok) {
            //Update the permissions For the given tabs whose information is given by the client 	
            $ok = $dbi->updateGroupPermission($arrPermission,$groupID,$userID,'update');
		}	
    }

//$fh = fopen('saveadmin.txt', "a");
//fwrite($fh, $tableName."\n");
//fwrite($fh, $retVal."\n"); 
//fclose($fh);

    if($ok && ($tableName=='organisation')){ //set up the organisation types

//$fh = fopen('saveadmin.txt', "a");
//fwrite($fh, $id."\n");
//fwrite($fh, $detailIDList."\n"); 
//fclose($fh);
    
        $currentID = $id;
        // first of all, unset any existing links which were previously set
        $detailSet = str_replace(",","','",$detailIDList);
		$query1=sprintf("UPDATE `".$extraTable."` SET deleted=1 WHERE ".$extraID."=".$currentID." AND ".$extraField." NOT IN ('".$detailSet."') AND deleted=0");
		$retVal.=$query1."\n";		
		$ok = ($dbi->isInteger( $dbi->query($query1)));

        $retVal.=$detailIDList."\n";
        $details = explode(",",$detailIDList);
        if($detailIDList != "") {
            for($i = 0;$i<sizeof($details);$i++){
                $detailID = $details[$i];
                if ($ok) {
                    $query2 =	sprintf("SELECT ".$extraField." FROM `".$extraTable."` WHERE ".$extraID."=".$currentID." AND ".$extraField."=".$detailID);
                    $retVal.=$query2."\n";
                    $result =	$dbi->query($query2);
			
                    if(mysql_num_rows($result)	==	0) {// doesn't already exist
                        $query=sprintf("INSERT INTO `".$extraTable."`(".$extraID.",".$extraField.") VALUES ('$currentID','$detailID')");
                        $retVal.=$query."\n";
                        $ok = ($dbi->isInteger($dbi->query($query)));
                    }
                    else { //undelete if it has been deleted
                        $query=sprintf("UPDATE `".$extraTable."` SET deleted=0 WHERE ".$extraID."=".$currentID." AND ".$extraField."=$detailID AND deleted=1");
                        $retVal.=$query."\n";
                        $ok = ($dbi->isInteger($dbi->query($query)));
                    }
                }
			}
        }
    }
    
    if ($ok)			
        $dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
    else
        $dbi->query($dbi->printTransactionInfo($query));// force rollback
        //$dbi->test_file("tableName:$tableName \n $query");	
            
	$dbi->disconnect();
		
/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('saveadmin.txt', "a");
fwrite($fh, $query."\n");
fwrite($fh, $retVal."\n"); 
fclose($fh); 
*/
	
?>
