<?PHP

	include_once (dirname(__FILE__).'/connect.php');	
//$str = substr($str, 0, -1);removes the last char of $str	

function getFieldNames($query,$extra) {

    $query = str_replace(' distinct ',' ',$query);
    $query = str_replace(' DISTINCT ',' ',$query);
    $fieldNamesArr=explode(' from ',strtolower($query));
	$fieldNamesArr=explode('select ',strtolower($fieldNamesArr[0]));
	//$fieldNames=rtrim($fieldNamesArr[0]);
	$fieldNames=str_replace(' ', '', $fieldNamesArr[1]);// to remove unnecessay space characters from beginning and end of $fieldNames

    $fieldNames.=$extra;
    $fieldNames		=	implode(',',array_unique(explode(',',$fieldNames)));

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps1: ".$query."\n");
fwrite($fh, "ps1: ".$fieldNames."\n");
fwrite($fh, "ps1: ".$fieldNamesArr[0]."\n");
fwrite($fh, "ps1: ".$fieldNamesArr[1]."\n");
//fwrite($fh, "ps1: ".$retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/
    
    return $fieldNames;
}

	$_POST=$dbi->prepareUserData($_POST);	
	$DB=$dbi->getdbname();
	$preparedQueryAvailable=$_POST['preparedQueryAvailable'];
	$query='';
	$searchDescription='';
	$fieldNames='';
	$joinTables	=	'';
	$infStaffOnly='';
	$mainInfTable='';
    $includeArchive = $_POST['includeArchive'];
    $includeExpired = $_POST['includeArchive'];
    $expiryDate = $dbi->getFiscalYearStart(); //"(select DATE_SUB(curdate(),interval 1 year))";

	$searchHistorySaved='';	
	$site	=	$dbi->getSiteName();
	
	if($site=='Patient'){
		$joinTable=' LEFT JOIN patient_inf ON name.id = patient_inf.patient_id ';
        $extraWhere = "";
        $extraFieldNames = ",patient_inf.patient_id";
        $extraTables = ",patient_inf";
        $selectName     =   "SELECT `patient_inf`.`patient_id` FROM `patient_inf` WHERE `patient_inf`.`deleted` = '0' $extraWhere";
		$infStaffOnly=" name.id IN ($selectName) AND `name`.`deleted` = '0'";
		$mainInfTable ='patient_inf';
	}
	else{
			$joinTable=' LEFT JOIN inf_staff ON name.id = inf_staff.name_id ';
			$extraFieldNames = ",inf_staff.name_id";
            $extraTables = ",inf_staff";
            if ($includeArchive == 'No') {
                $extraWhere = "AND (staff.leaving_date is null OR staff.leaving_date = '0000-00-00' or staff.leaving_date >= curdate())";
				//AND (staff.leaving_reason_id = 0  OR staff.leaving_reason_id is null)";
				//AND (staff.retirement_date >= curdate() OR staff.retirement_date = '0000-00-00' OR staff.retirement_date is null)";
				$extraTables .= ",staff";
				/*
				if (($location_id=$dbi->getSiteLocationID()) != 0)
				{
					$extraTables .= ",service";
					$extraWhere .= "AND (service.location_id = $location_id)";
				}*/
            }
            else {
                $extraWhere = "";
            }
			
			$location_id=$dbi->getSiteLocationID();
			$programme_id=$dbi->getSiteProgrammeID();
			if (($location_id != 0) || ($programme_id != 0))
			{
				$extraTables .= ",site_location";
			}
			if ($location_id != 0)
			{
				$extraWhere .= " AND (service.location_id = '$location_id')";
			}
			if ($programme_id != 0)
			{
				$extraTables .= ",site_programme";
				$extraWhere .= " AND (post.programme_id = '$programme_id')";
			}			
			
            $selectName     =   "SELECT `inf_staff`.`name_id` FROM `inf_staff` LEFT JOIN staff ON inf_staff.name_id = staff.name_id WHERE `inf_staff`.`deleted` = '0' AND (staff.deleted='0' OR staff.deleted is null)";
			$infStaffOnly=" name.id IN ($selectName) $extraWhere AND `name`.`deleted` = '0'";
			$mainInfTable='inf_staff';
//$dbi->test_file('selectName',$selectName);	
	}
    
    $limited = $_POST['limited'];    
    if($limited!="") {
        $infStaffOnly .= " AND name.id = '".$limited."' ";
    }
    
	$filterDeleted="name.deleted='0' ";
    $recLimit	 =	$_POST['recLimit'];
    
	if($preparedQueryAvailable=='yes'){
		
		$queryID=$_POST['query'];
				
		if($_POST['requester']=='inf'){
					
			if($queryID==0){		
				$query="select name.id,surname.surname,name.forenames".$extraFieldNames." from  name
							LEFT JOIN surname ON name.id = surname.name_id
							$joinTable
											
							where $infStaffOnly AND surname.priority=1 AND $mainInfTable.deleted='0' AND name.deleted='0' 
							And surname.deleted='0' AND 1='1' ORDER BY surname, forenames LIMIT $recLimit";
				$fieldNames='name.id,name.forenames,surname.surname'.$extraFieldNames;				
			}
			else{
		
				//get the required query from search_history
				$queryResult = $dbi->query("SELECT search_history.query,search_history.saved FROM search_history where search_history.id='$queryID' and search_history.deleted='0'");
			
				$row = mysql_fetch_assoc($queryResult);
				$query=$row['query']; 	
				$searchHistorySaved=	$row['saved']; 	
                $fieldNames = getFieldNames($query,$extraFieldNames);
			}			
		}
		else{		
			$query=$_POST['query'];		
			$query=str_replace("\'","'",$query);
			$fieldNames = getFieldNames($query,$extraFieldNames);
        }		

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-p: ".$query."\n");
fclose($fh); 
*/	
    }
	else{	

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-a: ".$query."\n");
fclose($fh); 
*/	
	$userID		=	$_POST['userID'];
	$searchWhichPeople=$_POST['searchWhichPeople'];
	$searchDescription=	$_POST['searchDescription'];
   	// $blob=	$_POST['blob'];
	$whereOperations=	$_POST['whereOperations'];
	$whereAndOrOperations=$_POST['whereAndOrOperations'];
	$fieldNames		=	$_POST['fieldNames'];	//this might have duplicate fields also ,eg. ' name.gender,name.gemder' and so on so need to remove duplicate entry
	//$fieldNamesToSave = str_replace('name.id,','',$fieldNames);//chop out the 'name.id,' field since we should not save it
	
//	$fieldNamesToSave =$fieldNames;
	$arrFieldsToFilterOut=array('name.id','inf_staff.name_id');
	$fieldNamesToSave=	implode(',',array_diff((explode(',',$fieldNames)),$arrFieldsToFilterOut));
	
	$fieldNames     .=  $extraFieldNames;
    $fieldNames		=	implode(',',array_unique(explode(',',$fieldNames)));	
	$whereFields	=	$_POST['whereFields'];
	$whereValues	=	$_POST['whereValues'];
	//$dbi->test_file("fields:$fieldNames \n where:\n $whereFields \n values : $whereValues");

	$saved=$fieldNamesToSave."/#/".$whereFields."/#/".$whereValues."/#/".$whereOperations."/#/".$whereAndOrOperations;
//$dbi->test_file( $fieldNames,"$saved");
    $whereOperations = str_replace('@','',$whereOperations); // remove @ hidden fields
	$tableString	=	$_POST['tables'];
    $tableString .= $extraTables;
	$requesterTab	=	$_POST['requester'];
	$moreFieldsFrom =	strtolower($_POST['moreFieldsFrom']);
	$currentUserID  =	$_POST['currentUserID'];	
	$moreFieldsFrom=str_replace ( 'personal', 'general', $moreFieldsFrom);
	//$nameID			=	$_POST['nameID'];
    $includeLevel =   $_POST['includeLevel'];
	//$individual_email_only=" email.id IN ( SELECT email_id FROM name_email WHERE name_id ='$nameID') and name_email.deleted = '0' ";

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-b: ".$requesterTab."\n");
fwrite($fh, "ps-b: ".$moreFieldsFrom."\n");
fclose($fh); 
*/	
	$whereClause	=	"";
	$arrWhereFields	=	explode(',',$whereFields);
	$arrWhereOperations	=	explode(',',$whereOperations);
	$arrWhereValues	=	explode(',',$whereValues);	
	$arrWhereAndOrOperation=explode(',',$whereAndOrOperations);
	
	//Make sure we get the tables included for re-running saved queries
	foreach($arrWhereFields as $eachWhere){
		$whereData = explode('.',$eachWhere);
		$moreFieldsFrom .= ",".$whereData[0];
	}
	
/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-b: ".$tableString."\n");
fwrite($fh, "ps-b: ".$moreFieldsFrom."\n");
fclose($fh); 
*/	
/*
if the last operation in $arrWhereAndOrOperation is OR then we need to append ')' to the where clause prepared from user search values 
since  the  applyParenthesis() method (which is called in the code later )cant handle this case. For other cases it is handled
 */
	$closeBraces4whereClause=false;
	if($arrWhereAndOrOperation[count($arrWhereAndOrOperation)-1]=='OR')
		$closeBraces4whereClause=true;
	//$arrWhereAndOrOperation=$dbi->applyParenthesis($whereAndOrOperations);
//	$arrWhereAndOrOperation=implode(',',$arrWhereAndOrOperation);
//$dbi->test_file("wherFields: $whereFields \n Values: $whereValues\n wherOpen: $whereOperations \n whereAndOrOperations : $whereAndOrOperations ");	
	
	$tables			=	array_unique(split (',',$tableString));
	$tables			=   array_values(array_diff($tables,array('name')));//removing 'name' table from the array of tables since we dont LEFT JOIN name table
	$arrExtraFields = array();
//$dbi->test_file('$moreFieldsFrom',$moreFieldsFrom);
	$arrMoreFieldsFrom =	$tables; //explode(',',$moreFieldsFrom);
	array_push($arrMoreFieldsFrom,$requesterTab);
	$arrMoreFieldsFrom =	array_unique($arrMoreFieldsFrom);		

	$relativesOnly	=	"`name`.`id` IN (SELECT `relation`.`relation_id` FROM `relation` WHERE `relation`.`deleted`='0' AND `relation`.`name_id` IN ($selectName)) AND `name`.`deleted` = '0'";
	$searchAll		=	"`name`.`id` IN ($selectName UNION SELECT `relation`.`relation_id`  AS `name_id` FROM `relation` WHERE `relation`.`deleted` = '0' AND `relation`.`name_id` IN ($selectName)) AND `name`.`deleted` = '0'";
		
	if (!strcasecmp($searchWhichPeople,'Staff only'))
        $whereClause	= $infStaffOnly;
	else if (!strcasecmp($searchWhichPeople,'Relatives'))
		$whereClause	=	$relativesOnly;	
	else
		$whereClause	=	$searchAll;

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-c: ".$whereClause."\n");
fclose($fh); 
*/        
    //default to current address/email/phone
	$join_email=" LEFT JOIN email ON name.email_id = email.id LEFT JOIN name_email ON name.email_id = name_email.email_id "; 
    $join_phone=" LEFT JOIN phone ON name.phone_id = phone.id LEFT JOIN name_phone ON name.phone_id = name_phone.phone_id ";    
    $join_address=" LEFT JOIN address ON name.address_id = address.id LEFT JOIN name_address ON name.address_id = name_address.address_id ";	
	$join_passport=" LEFT JOIN passport ON name.id = passport.name_id ";
    //$join_visa=" LEFT JOIN `visa_history` ON passport.visa_id = visa_history.visa_id ";
	$join_visa=" LEFT JOIN `visa_history` ON passport.id = visa_history.passport_id ";
    //$join_reason= " LEFT JOIN `visit_treatment_reasons` ON patient_visit.id = visit_treatment_reasons.visit_id LEFT JOIN `treatment_reason` ON treatment_reason.id = visit_treatment_reasons.detail_treatment_reason_id";
    $join_reason= " LEFT JOIN `visit_treatment_reasons` ON (patient_visit.id = visit_treatment_reasons.visit_id OR visit_treatment_reasons.visit_id=0)";
    //$join_training= " LEFT JOIN `name_training_needs` ON training.id = name_training_needs.training_id LEFT JOIN `personnel_training_needs` ON personnel_training_needs.id = name_training_needs.training_need_id LEFT JOIN `course` ON `training`.`course_id` = `course`.`id`";
    $join_training= " LEFT JOIN `name_training_needs` ON (training.id = name_training_needs.training_id OR name_training_needs.training_id=0)";
	$join_service= " LEFT JOIN service ON service.name_id = name.id ";
	$join_post= " LEFT JOIN `post` ON `service`.`post_id` = `post`.`id`";
    
    if ($includeLevel == 'name') {
        $join_address=" LEFT JOIN name_address ON name.id = name_address.name_id LEFT JOIN address ON address.id = name_address.address_id ";
        $join_phone=" LEFT JOIN name_phone ON name.id = name_phone.name_id LEFT JOIN phone ON phone.id = name_phone.phone_id ";
        $join_email=" LEFT JOIN name_email ON name.id = name_email.name_id LEFT JOIN email ON email.id = name_email.email_id ";
    }
    if ($includeLevel == 'family') {
        $join_address=" LEFT JOIN relation ON relation.name_id = name.id LEFT JOIN name_address ON (name_address.name_id = relation.relation_id OR name_address.name_id = relation.name_id OR name_address.address_id = name.address_id) LEFT JOIN address ON address.id = name_address.address_id ";
        $join_phone=" LEFT JOIN relation ON relation.name_id = name.id LEFT JOIN name_phone ON (name_phone.name_id = relation.relation_id OR name_phone.name_id = relation.name_id OR name_phone.phone_id = name.phone_id) LEFT JOIN phone ON phone.id = name_phone.phone_id ";
        $join_email=" LEFT JOIN relation ON relation.name_id = name.id LEFT JOIN name_email ON (name_email.name_id = relation.relation_id OR name_email.name_id = relation.name_id OR name_email.email_id = name.email_id) LEFT JOIN email ON email.id = name_email.email_id ";
    }
	
foreach($arrMoreFieldsFrom as $requester)
{	
	if($requester=='annualreview')
		array_push($arrMoreFieldsFrom,'review');
	if($requester=='annual_review')
		array_push($arrMoreFieldsFrom,'review');
	if($requester=='homeassignment')
		array_push($arrMoreFieldsFrom,'home_assignment');
	if($requester=='home assignment')
		array_push($arrMoreFieldsFrom,'home_assignment');
	if($requester=='visa history')
		array_push($arrMoreFieldsFrom,'visa_history');

	switch($requester){
	
//Handle cases for the biodata components

	case 'general':
		
		$fieldNames = str_replace('ethnicity', 'caste_id',$fieldNames);
		$fieldNames = str_replace('religion', 'religion_id',$fieldNames);
		$fieldNames = str_replace('birth_country', 'birth_country_id',$fieldNames);
		$fieldNames = str_replace('inf_staff.embassy', 'inf_staff.embassy_id',$fieldNames);
        
		if(in_array('name.dob',$arrWhereFields))
			{	
				$index=array_search('name.dob', $arrWhereFields);//since 'name.dob' may be present more than once in the where fields we need to look for all the index
				$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
		if(in_array('inf_staff.embassy_reg',$arrWhereFields))
			{	
				$index=array_search('inf_staff.embassy_reg', $arrWhereFields);
				$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
		
		if(in_array('name.birth_country',$arrWhereFields))
			{				
				$arrIndex=array_keys ($arrWhereFields,'name.birth_country' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='name.birth_country_id/#/country.id/#/country/#/country.name';		
			}
		if(in_array('inf_staff.embassy',$arrWhereFields))
			{				
				$arrIndex=array_keys ($arrWhereFields,'inf_staff.embassy' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='inf_staff.embassy_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('inf_staff.ethnicity',$arrWhereFields))
			{			
				$arrIndex=array_keys ($arrWhereFields,'inf_staff.ethnicity' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='inf_staff.caste_id/#/caste.id/#/caste/#/caste.name';		
			}
		
		$arrExtraFieldsTemp	=	array('inf_staff.embassy_id/#/organisation.id/#/organisation/#/organisation.name',"name.birth_country_id/#/country.id/#/country/#/country.name",'inf_staff.caste_id/#/caste.id/#/caste/#/caste.name',"$mainInfTable.religion");
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);//Default 
		break;
		
	case 'address':			
   
		$filterAddressDeleted.=" AND ((`address`.`deleted` is NULL) OR (`address`.`deleted` = '0')) AND ((`name_address`.`deleted` is NULL) OR (`name_address`.`deleted` = '0')) ";		
		$fieldNames = str_replace('address.country', 'address.country_id',$fieldNames);// address.country_id		

		$joinTables	.=$join_address;
		$filterDeleted.=$filterAddressDeleted;			
		// to avoid JOIN command to appear more than once. Once it is appended it is made empty so no case of duplication appears in query
		$join_address="";
		$filterAddressDeleted='';		
		
		if(in_array('address.country',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'address.country' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='address.country_id/#/country.id/#/country/#/country.name';		
				//    here '/#/' is delimeter SUCH THAT	address.country_id in (select country.id from country where country.name='SOMEVALUE') 									
			}
				
		if(in_array('address.address',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'address.address');
			
				foreach($arrIndex as $index){
					
					if(($arrWhereOperations[$index]=='=')&&($arrWhereValues[$index]=='')){
						$arrWhereFields[$index]='name.address_id';
						$arrWhereOperations[$index]='IS'; //'IS' checked in default operation case in buildWhereClauseSimple()
						$arrWhereValues[$index]='NULL';					
					}
					else if($arrWhereValues[$index]!='')						
						$arrWhereFields[$index]='name_address.address_id/#/address.id/#/address/#/address.address';
					else					
						$arrWhereFields[$index]='name_address.address_id';				
				}			
			}
		
		$arrExtraFieldsTemp	=	array('name_address.address_id/#/address.id/#/address/#/address.address','address.country_id/#/country.id/#/country/#/country.name','address.id/#/address.id/#/address/#/address.address','name.address_id/#/address.id/#/address/#/address.address');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);			

		break;				
		
	case 'phone':
    
		$filterPhoneDeleted.=" AND ((`phone`.`deleted` is NULL) OR (`phone`.`deleted` = '0')) AND ((`name_phone`.`deleted` is NULL) OR (`name_phone`.`deleted` = '0')) ";		
		$fieldNames = str_replace('phone.country', 'phone.country_id',$fieldNames);// phone.country_id		

		$joinTables	.=$join_phone;
		$filterDeleted.=$filterPhoneDeleted;			
		// to avoid JOIN command to appear more than once. Once it is appended it is made empty so no case of duplication appears in query
		$join_phone="";
		$filterPhoneDeleted='';		
		
		if(in_array('phone.country',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'phone.country' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='phone.country_id/#/country.id/#/country/#/country.name';		
				//    here '/#/' is delimeter SUCH THAT	phone.country_id in (select country.id from country where country.name='SOMEVALUE') 									
			}
				
		if(in_array('phone.phone',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'phone.phone');
			
				foreach($arrIndex as $index){
					
					if(($arrWhereOperations[$index]=='=')&&($arrWhereValues[$index]=='')){
						$arrWhereFields[$index]='name.phone_id';
						$arrWhereOperations[$index]='IS'; //'IS' checked in default operation case in buildWhereClauseSimple()
						$arrWhereValues[$index]='NULL';					
					}
					else if($arrWhereValues[$index]!='')						
						$arrWhereFields[$index]='name_phone.phone_id/#/phone.id/#/phone/#/phone.phone';
					else
						$arrWhereFields[$index]='name_phone.phone_id';				
				}			
			}
		
		$arrExtraFieldsTemp	=	array('name_phone.phone_id/#/phone.id/#/phone/#/phone.phone','phone.country_id/#/country.id/#/country/#/country.name','phone.id/#/phone.id/#/phone/#/phone.phone','name.phone_id/#/phone.id/#/phone/#/phone.phone');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);			

		break;
				
	case 'email':	

        $filterEmailDeleted.=" AND ((`email`.`deleted` is NULL) OR (`email`.`deleted` = '0')) AND ((`name_email`.`deleted` is NULL) OR (`name_email`.`deleted` = '0')) ";		
		
		$joinTables	.=$join_email;
		$filterDeleted.=$filterEmailDeleted;			
		// to avoid JOIN command to appear more than once. Once it is appended it is made empty so no case of duplication appears in query
		$join_email="";
		$filterEmailDeleted='';
        
//$dbi->test_file('pagespSrch',"where: $arrWhereFields \n fields:   $fieldNames");			
		if(in_array('email.email',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'email.email');
			
				foreach($arrIndex as $index){
					
					if(($arrWhereOperations[$index]=='=')&&($arrWhereValues[$index]=='')){
						$arrWhereFields[$index]='name.email_id';
						$arrWhereOperations[$index]='IS'; //'IS' checked in default operation case in buildWhereClauseSimple()
						$arrWhereValues[$index]='NULL';					
					}
					else if($arrWhereValues[$index]!='')						
						$arrWhereFields[$index]='name_email.email_id/#/email.id/#/email/#/email.email';
					
					else
						$arrWhereFields[$index]='name_email.email_id';			
				}			
			}					
        
		$arrExtraFieldsTemp	=	array('email.id/#/email.id/#/email/#/email.email','name.email_id/#/email.id/#/email/#/email.email','name_email.email_id/#/email.id/#/email/#/email.email');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
        
		break;		

	case 'photo':

		break;

	case 'passport':
		
		$passportDates=array('passport.expiry_date','passport.issue_date');
        
        if (($includeExpired == 'No') && !in_array('passport.expiry_date',$arrWhereFields) && in_array($requester,$tables)) {           
            $filterDeleted .= " AND (passport.expiry_date >= '$expiryDate' OR passport.expiry_date IS NULL OR passport.expiry_date = '0000-00-00')";
        }
		foreach($passportDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
			
		$fieldNames = str_replace('passport.issue_country', 'passport.issue_country_id',$fieldNames);
		$fieldNames = str_replace('passport.passport_country', 'passport.passport_country_id',$fieldNames);
		//$fieldNames = str_replace('passport.visa_post', 'passport.visa_id',$fieldNames);
		
		if(in_array('passport.issue_country',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'passport.issue_country' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='passport.issue_country_id/#/country.id/#/country/#/country.name';		
				//    here '/#/' is delimeter SUCH THAT	passport.issue_country_id in (select country.id from country where country.name='SOMEVALUE') 									
			}
		if(in_array('passport.passport_country',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'passport.passport_country' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='passport.passport_country_id/#/country.id/#/country/#/country.name';								
			}
		/*
		if(in_array('passport.visa_post',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'passport.visa_post' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='passport.visa_id/#/visa.id/#/visa/#/visa.name';	
			}
		*/
		$arrExtraFieldsTemp		=	
		array('passport.issue_country_id/#/country.id/#/country/#/country.name','passport.passport_country_id/#/country.id/#/country/#/country.name');//,'passport.visa_id/#/visa.id/#/visa/#/visa.name');//Default 
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
        
		break;
		
		case 'visa_history':
		case 'visa history':
        
			$visaDates=array('visa_history.entry_date','visa_history.expiry_date','visa_history.issue_date');
            
            if (($includeExpired == 'No') && !in_array('visa_history.expiry_date',$arrWhereFields) && in_array($requester,$tables)) {  
                $filterDeleted .= " AND (visa_history.expiry_date >= '$expiryDate' OR visa_history.expiry_date IS NULL OR visa_history.expiry_date = '0000-00-00')";
            }
			foreach($visaDates as $eachField)
				if(in_array($eachField,$arrWhereFields))
				{	
					$arrIndex=array_keys ($arrWhereFields, $eachField);
					foreach($arrIndex as $index)	
						$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
				}
				
			$fieldNames = str_replace('visa_history.issue_country', 'visa_history.issue_country_id',$fieldNames);
			//$fieldNames = str_replace('visa_history.visa_post', 'visa_history.visa_id',$fieldNames);
			$fieldNames = str_replace('visa_history.visa_post', 'visa_history.post_id',$fieldNames);
			$fieldNames = str_replace('visa_history.visa_status', 'visa_history.status',$fieldNames);
			if(in_array('visa_history.visa_status',$arrWhereFields))
				{	
					$arrIndex=array_keys ($arrWhereFields,'visa_history.visa_status' );
					foreach($arrIndex as $index)
						$arrWhereFields[$index]='visa_history.status';																
				}
			if(in_array('visa_history.issue_country',$arrWhereFields))
				{	
					$arrIndex=array_keys ($arrWhereFields,'visa_history.issue_country' );
					foreach($arrIndex as $index)
						$arrWhereFields[$index]='visa_history.issue_country_id/#/country.id/#/country/#/country.name';		
					//    here '/#/' is delimeter SUCH THAT	passport.issue_country_id in (select country.id from country where country.name='SOMEVALUE') 									
				}
			/*
			if(in_array('visa_history.visa_post',$arrWhereFields))
				{	
					$arrIndex=array_keys ($arrWhereFields,'visa_history.visa_post' );
					foreach($arrIndex as $index)
						$arrWhereFields[$index]='visa_history.visa_id/#/visa.id/#/visa/#/visa.name';	
				}
			*/
			if(in_array('visa_history.visa_post',$arrWhereFields))
				{	
					$arrIndex=array_keys ($arrWhereFields,'visa_history.visa_post' );
					foreach($arrIndex as $index)
						$arrWhereFields[$index]='visa_history.post_id/#/post.id/#/post/#/post.name';	
				}
			$arrExtraFieldsTemp		=	
			array('visa_history.issue_country_id/#/country.id/#/country/#/country.name',
			'visa_history.post_id/#/post.id/#/post/#/post.name');//,'visa_history.visa_id/#/visa.id/#/visa/#/visa.name');//Default 
			$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	

        break;			
	
//Handle cases for the 'service' Components	

	case 'home_assignment':		
	case 'homeassignment':
    
		$homeAssDates=array('home_assignment.date_from','home_assignment.date_until','home_assignment.interview_date','home_assignment.rsent_date_member','home_assignment.rsent_date_agency','home_assignment.medical_arranged','home_assignment.medical_report_received','home_assignment.msent_date_agency','home_assignment.infopack_sent','home_assignment.report_received','home_assignment.STV_manager_comments_received','home_assignment.invitation_letter_received');
        
        if (($includeExpired == 'No') && !in_array('home_assignment.date_until',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (home_assignment.date_until >= '$expiryDate' OR home_assignment.date_until IS NULL OR home_assignment.date_until = '0000-00-00')";
        }
        
		foreach($homeAssDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}

		//'interviewer' is not the actual database field it is 'interview_by'
		$fieldNames = str_replace('interviewer', 'interview_by',$fieldNames);	
		//Default 
		$arrExtraFieldsTemp		=	array
		('home_assignment.interview_by/#/name.id/#/name/#/name.forenames');
//		('home_assignment.interview_by/#/name.id/#/name/#/name.forenames','home_assignment.interview_by/#/surname.name_id/#/surname/#/surname.surname');

		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	

		if(in_array('home_assignment.interviewer',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'home_assignment.interviewer' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='home_assignment.interview_by/#/name.id/#/name/#/name.forenames';					
			}
		break;
        
	case 'review':	
	case 'annualreview':
    
        if (($includeExpired == 'No') && !in_array('review.review_date',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (review.review_date >= '$expiryDate' OR review.review_date IS NULL OR review.review_date = '0000-00-00')";
        }
        
		if(in_array('review.review_date',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'review.review_date');
				foreach($arrIndex as $index)	
				$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}		
	
		//'reviewer' is not the actual database field it is 'reviewed_by_id'
		$fieldNames = str_replace('reviewer', 'reviewed_by_id',$fieldNames);
		$fieldNames = str_replace('review_type', 'review_type_id',$fieldNames);
		//Default 
		$arrExtraFieldsTemp		=	array
		('review.review_type_id/#/review_type.id/#/review_type/#/review_type.name',
		'review.reviewed_by_id/#/name.id/#/name/#/name.forenames');
		//'review.reviewed_by_id/#/surname.name_id/#/surname/#/surname.surname');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	
		if(in_array('review.review_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'review.review_type' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='review.review_type_id/#/review_type.id/#/review_type/#/review_type.name';						
			}
		if(in_array('review.reviewer',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'review.reviewer' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='review.reviewed_by_id/#/name.id/#/name/#/name.forenames';						
			}
	   /* if(in_array('surname.reviewer-Surname',$arrWhereFields))
			{	
				$index=array_search('surname.reviewer-Surname', $arrWhereFields);
				$arrWhereFields[$index]='review.reviewed_by_id/#/surname.name_id/#/surname/#/surname.surname';	
			}
*/		break;
		
	case 'hospitalisation':
        
        if (($includeExpired == 'No') && !in_array('hospitalisation.date_until',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (hospitalisation.date_until >= '$expiryDate' OR hospitalisation.date_until IS NULL OR hospitalisation.date_until = '0000-00-00')";
        }
        
		$hospitalisationDates=array('hospitalisation.date_from','hospitalisation.date_until');
		foreach($hospitalisationDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}

		$fieldNames = str_replace('hospital_admitted', 'hospital_id',$fieldNames);
		$fieldNames = str_replace('relative', 'relative_id',$fieldNames);
		$fieldNames = str_replace('illness', 'illness_id',$fieldNames);
		$arrExtraFieldsTemp		=	array
		('hospitalisation.illness','hospitalisation.hospital_id/#/hospital.id/#/hospital/#/hospital.name',
		'hospitalisation.relative_id/#/name.id/#/name/#/name.forenames');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	
		
		if(in_array('hospitalisation.hospital_admitted',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'hospitalisation.hospital_admitted' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='hospitalisation.hospital_id/#/hospital.id/#/hospital/#/hospital.name';					
			}
		if(in_array('hospitalisation.relative',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'hospitalisation.relative' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='hospitalisation.relative_id/#/name.id/#/name/#/name.forenames';					
			}
            
	break;
	
	case 'training':	
	
		//$fieldNames = str_replace('training_needs.trainee', 'training_needs.name_id',$fieldNames);
        $fieldNames = str_replace('training.training_need', 'name_training_needs.training_need_id',$fieldNames);
        $fieldNames = str_replace('training.course', 'training.course_id',$fieldNames);

		$arrExtraFieldsTemp	=	array (
		//'training_needs.name_id/#/name.id/#/name/#/name.forenames');
        'name_training_needs.training_need_id/#/personnel_training_needs.id/#/personnel_training_needs/#/personnel_training_needs.name',
        'training.course_id/#/course.id/#/course/#/course.name');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	
		/*
		if(in_array('training_needs.trainee',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'training_needs.trainee' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='training_needs.name_id/#/name.id/#/name/#/name.forenames';		
			}*/
        if(in_array('training.training_need',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'training.training_need' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='name_training_needs.training_need_id/#/personnel_training_needs.id/#/personnel_training_needs/#/personnel_training_needs.name';
			}
        if(in_array('training.course',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'training.course' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='training.course_id/#/course.id/#/course/#/course.name';		
			}
		break;
	
	case 'leave':
	
		$leaveDates=array('leave.date_from','leave.date_until');
        
        if (($includeExpired == 'No') && !in_array('leave.date_until',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (leave.date_until >= '$expiryDate' OR leave.date_until IS NULL OR leave.date_until = '0000-00-00')";
        }
        
		foreach($leaveDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
		$fieldNames = str_replace('leave.leave_type', 'leave.leave_type_id',$fieldNames);		
		$arrExtraFieldsTemp	=	array('leave.leave_type');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
		break;
	
	case 'movement':
	
		$mvmtDates=array('movement.date_from','movement.date_until');
        
        if (($includeExpired == 'No') && !in_array('movement.date_until',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (movement.date_until >= '$expiryDate' OR movement.date_until IS NULL OR movement.date_until = '0000-00-00')";
        }
        
		foreach($mvmtDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
		//$dbi->test_file("fields2:$fieldNames \n where:\n $whereFields \n values : $whereValues");

		$fieldNames = str_replace('movement.reason', 'movement.reason_id',$fieldNames);
		$fieldNames = str_replace('movement.email', 'movement.email_id',$fieldNames);		
		$fieldNames = str_replace('movement.address', 'movement.address_id',$fieldNames);
		
		$arrExtraFieldsTemp	=	
		array('movement.address_id/#/address.id/#/address/#/address.address','movement.email_id/#/email.id/#/email/#/email.email','movement.reason_id/#/movement_reason.id/#/movement_reason/#/movement_reason.name');	
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
		
		if(in_array('movement.email',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'movement.email' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='movement.email_id/#/email.id/#/email/#/email.email';					
			}
		if(in_array('movement.address',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'movement.address' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='movement.address_id/#/address.id/#/address/#/address.address';					
			}
		if(in_array('movement.reason',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'movement.reason');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='movement.reason_id/#/movement_reason.id/#/movement_reason/#/movement_reason.name';				
			}
            
	break;
	
	case 'service':
    
		$serviceDates=array('service.date_from','service.date_until');
        
        if (($includeExpired == 'No') && !in_array('service.date_until',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (service.date_until >= '$expiryDate' OR service.date_until IS NULL OR service.date_until = '0000-00-00')";
        }
        
		foreach($serviceDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}

		$fieldNames = str_replace('service.location', 'service.location_id',$fieldNames);	
		$fieldNames = str_replace('service.grade', 'service.grade_id',$fieldNames);		
		$fieldNames = str_replace('service.INF_role', 'service.post_id',$fieldNames);

		if(in_array('service.INF_role',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'service.INF_role' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='service.post_id/#/post.id/#/post/#/post.name';	
			}	//*/	
		$arrExtraFieldsTemp	=	array('service.location','service.grade','service.post_id/#/post.id/#/post/#/post.name');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
        
	break;
	
	//case 'inf_staff':
    case 'staff': 	
    
		$staffDates=array('staff.start_date','staff.probation_end_date','staff.next_review_due','staff.retirement_date','staff.leaving_date');

		foreach($staffDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}			
		$fieldNames = str_replace('staff.leaving_reason', 'staff.leaving_reason_id',$fieldNames);
		$fieldNames = str_replace('staff.programme', 'staff.programme_id',$fieldNames);		
		$fieldNames = str_replace('staff.staff_type', 'staff.staff_type_id',$fieldNames);	
		$arrExtraFieldsTemp	=		
		array('staff.leaving_reason','staff.staff_type','staff.programme');		
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
        
	break;
	
	case 'insurance': 	
    
		$fieldNames = str_replace('insurance.company', 'insurance.company_id',$fieldNames);
		
		$insuranceDates=array('insurance.start_date','insurance.end_date');
				
		if (($includeExpired == 'No') && !in_array('insurance.end_date',$arrWhereFields) && in_array($requester,$tables)) {           
            $filterDeleted .= " AND (insurance.end_date >= '$expiryDate' OR insurance.end_date IS NULL OR insurance.end_date = '0000-00-00')";
        }
		foreach($insuranceDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
        
		if(in_array('insurance.company',$arrWhereFields))
			{				
				$arrIndex=array_keys ($arrWhereFields,'insurance.company' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='insurance.company_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		
		$arrExtraFieldsTemp	=	array('insurance.company_id/#/organisation.id/#/organisation/#/organisation.name');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
        
	break;
	
	case 'registrations': 	
    
		$fieldNames = str_replace('registrations.organisation', 'registrations.organisation_id',$fieldNames);
		$fieldNames = str_replace('registrations.registration_type', 'registrations.registration_type_id',$fieldNames);
		
		$registrationDates=array('registrations.start_date','registrations.end_date');
				
		if (($includeExpired == 'No') && !in_array('registrations.end_date',$arrWhereFields) && in_array($requester,$tables)) {           
            $filterDeleted .= " AND (registrations.end_date >= '$expiryDate' OR registrations.end_date IS NULL OR registrations.end_date = '0000-00-00')";
        }
		foreach($registrationDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
        
		if(in_array('registrations.organisation',$arrWhereFields))
			{				
				$arrIndex=array_keys ($arrWhereFields,'registrations.organisation' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='registrations.organisation_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		
		$arrExtraFieldsTemp	=	array('registrations.organisation_id/#/organisation.id/#/organisation/#/organisation.name','registrations.registration_type');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
        
	break;
	
	case 'secondment':	
    
		$secondmentDates=array('secondment.osa_member_sent_date','secondment.osa_member_received_date','secondment.osa_member_copy_sent_date','secondment.osa_member_signed_date','secondment.osa_agency_sent_date','secondment.osa_agency_received_date','secondment.osa_agency_copy_sent_date','secondment.osa_agency_signed_date','secondment.osa_infw_sent_date','secondment.osa_infw_received_date','secondment.osa_infw_copy_sent_date','secondment.osa_infw_signed_date','secondment.fsa_member_sent_date','secondment.fsa_member_received_date','secondment.fsa_member_copy_sent_date','secondment.fsa_member_signed_date','secondment.fsa_agency_sent_date','secondment.fsa_agency_received_date','secondment.fsa_agency_copy_sent_date','secondment.fsa_agency_signed_date','secondment.fsa_infw_sent_date','secondment.fsa_infw_received_date','secondment.fsa_infw_copy_sent_date','secondment.fsa_infw_signed_date','secondment.invitation_letter_rcvd_date');
		foreach($secondmentDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
				
		$fieldNames = str_replace('secondment.seconded_from', 'secondment.seconded_from_id',$fieldNames);	
		$fieldNames = str_replace('secondment.seconded_to', 'secondment.seconded_to_id',$fieldNames);		
		$fieldNames = str_replace('secondment.local_support', 'secondment.local_support_id',$fieldNames);	
		$fieldNames = str_replace('secondment.church', 'secondment.church_id',$fieldNames);	
		$fieldNames = str_replace('secondment.post_agreed', 'secondment.post_agreed_id',$fieldNames);
		$fieldNames = str_replace('secondment.link_person_forename', 'secondment.link_person_id',$fieldNames);
		$fieldNames = str_replace('secondment.further_seconded_to', 'secondment.further_seconded_to_id',$fieldNames);		
		$fieldNames = str_replace('secondment.osa_approval_agency','secondment.osa_approval_agency_id',$fieldNames);
		$fieldNames = str_replace('secondment.fsa_approval_agency','secondment.fsa_approval_agency_id',$fieldNames);
		$fieldNames = str_replace('secondment.osa_approval_infw','secondment.osa_approval_infw_id',$fieldNames);
		$fieldNames = str_replace('secondment.fsa_approval_infw','secondment.fsa_approval_infw_id',$fieldNames);
		
		if(in_array('secondment.fsa_approval_infw',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.fsa_approval_infw' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.fsa_approval_infw_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.osa_approval_infw',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'secondment.osa_approval_infw');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.osa_approval_infw_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.fsa_approval_agency',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.fsa_approval_agency' );
				foreach($arrIndex as $index)	
					$arrWhereFields[$index]='secondment.fsa_approval_agency_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.osa_approval_agency',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.osa_approval_agency' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.osa_approval_agency_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.seconded_from',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.seconded_from' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.seconded_from_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.seconded_to',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.seconded_to' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.seconded_to_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.further_seconded_to',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.further_seconded_to' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.further_seconded_to_id/#/organisation.id/#/organisation/#/organisation.name';	
			}
		if(in_array('secondment.local_support',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.local_support' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.local_support_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.church',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.church' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.church_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		if(in_array('secondment.post_agreed',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'secondment.post_agreed' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.post_agreed_id/#/post.id/#/post/#/post.name';		
			}
		if(in_array('secondment.link_person_forename',$arrWhereFields))
			{				
				$arrIndex=array_keys ($arrWhereFields,'secondment.link_person_forename' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='secondment.link_person_id/#/name.id/#/name/#/name.forenames';		
			}
			
		$arrExtraFieldsTemp		=	
		array('secondment.seconded_from_id/#/organisation.id/#/organisation/#/organisation.name',	 
		'secondment.seconded_to_id/#/organisation.id/#/organisation/#/organisation.name',
		'secondment.local_support_id/#/organisation.id/#/organisation/#/organisation.name',
		'secondment.post_agreed_id/#/post.id/#/post/#/post.name',
		'secondment.link_person_id/#/name.id/#/name/#/name.forenames',
		'secondment.church_id/#/organisation.id/#/organisation/#/organisation.name');//Default 
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
		
	break;
	
	case 'orientation':
    
		$orientationDates=array('orientation.email_address_requested_date','orientation.email_address_created_date','orientation.email_installed_date','orientation.LOT_duration_discussed_date','orientation.LOT_requested_date','orientation.LOT_confirmed_date','orientation.ktm_LOT_scheduled_date','orientation.ktm_LOT_confirmed_date','orientation.dates_of_KTM_LOT_date','orientation.housing_preferences_date','orientation.pkr_LOT_housing_arranged_date','orientation.housing_requested_date','orientation.housing_arranged_date','orientation.housing_confirmed_date','orientation.link_names_sent_date');
		foreach($orientationDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}

		$fieldNames = str_replace('orientation.pkr_link_person', 'orientation.pkr_link_person_id',$fieldNames);
		$fieldNames = str_replace('orientation.ktm_link_person', 'orientation.ktm_link_person_id',$fieldNames);
		$fieldNames = str_replace('orientation.work_link_person', 'orientation.work_link_person_id',$fieldNames);
		$fieldNames = str_replace('orientation.housing_link_person', 'orientation.housing_link_person_id',$fieldNames);
		$fieldNames = str_replace('orientation.school_children_link_person', 'orientation.school_children_link_person_id',$fieldNames);
		
		if(in_array('orientation.pkr_link_person',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'orientation.pkr_link_person');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='orientation.pkr_link_person_id/#/name.id/#/name/#/name.forenames';		
			}
		if(in_array('orientation.ktm_link_person',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'orientation.ktm_link_person' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='orientation.ktm_link_person_id/#/name.id/#/name/#/name.forenames';		
			}
		if(in_array('orientation.work_link_person',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'orientation.work_link_person' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='orientation.work_link_person_id/#/name.id/#/name/#/name.forenames';		
			}
		if(in_array('orientation.housing_link_person',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'orientation.housing_link_person' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='orientation.housing_link_person_id/#/name.id/#/name/#/name.forenames';		
			}
		if(in_array('orientation.school_children_link_person',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'orientation.school_children_link_person');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='orientation.school_children_link_person_id/#/name.id/#/name/#/name.forenames';		
			}	
		
		$arrExtraFieldsTemp		=	
		array('orientation.work_link_person_id/#/name.id/#/name/#/name.forenames',
		'orientation.school_children_link_person_id/#/name.id/#/name/#/name.forenames',
		'orientation.housing_link_person_id/#/name.id/#/name/#/name.forenames',
		'orientation.ktm_link_person_id/#/name.id/#/name/#/name.forenames',
		'orientation.pkr_link_person_id/#/name.id/#/name/#/name.forenames'
		);//Default 
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);		
			
	break;
	
	case 'education':
		
		$fieldNames = str_replace('education.speciality_type', 'education.speciality_id',$fieldNames);	
		$fieldNames = str_replace('education.second_speciality_type', 'education.second_speciality_id',$fieldNames);	
		$fieldNames = str_replace('education.qualification_type', 'education.qualification_id',$fieldNames);
		$fieldNames = str_replace('education.institution', 'education.institution_id',$fieldNames);
		
		if(in_array('education.start_date',$arrWhereFields))
			{					
				$arrIndex=array_keys ($arrWhereFields,'education.start_date' );
				foreach($arrIndex as $index)
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
		if(in_array('education.end_date',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'education.end_date');
				foreach($arrIndex as $index)
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}			
		if(in_array('education.qualification_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'education.qualification_type' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='education.qualification_id/#/qualification_type.id/#/qualification_type/#/qualification_type.name';		
			}
		if(in_array('education.speciality_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'education.speciality_type' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='education.speciality_id/#/speciality_type.id/#/speciality_type/#/speciality_type.name';	
			}
		if(in_array('education.second_speciality_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'education.second_speciality_type' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='education.second_speciality_id/#/speciality_type.id/#/speciality_type/#/speciality_type.name';	
			}			
		if(in_array('education.institution',$arrWhereFields))
			{				
				$arrIndex=array_keys ($arrWhereFields,'education.institution' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]	='education.institution_id/#/organisation.id/#/organisation/#/organisation.name';		
			}
		
        $arrExtraFieldsTemp		=	
		array('education.qualification_id/#/qualification_type.id/#/qualification_type/#/qualification_type.name',
		'education.speciality_id/#/speciality_type.id/#/speciality_type/#/speciality_type.name',
		'education.second_speciality_id/#/speciality_type.id/#/speciality_type/#/speciality_type.name',
		'education.institution_id/#/organisation.id/#/organisation/#/organisation.name'
		);//Default 
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	    
	break;
	
	case 'work_experience':
		
		$fieldNames = str_replace('work_experience.leaving_reason', 'work_experience.leaving_reason_id',$fieldNames);	
		$fieldNames = str_replace('work_experience.country', 'work_experience.country_id',$fieldNames);
		
		if(in_array('work_experience.start_date',$arrWhereFields))
			{					
				$arrIndex=array_keys ($arrWhereFields,'work_experience.start_date' );
				foreach($arrIndex as $index)
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
		if(in_array('work_experience.end_date',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'work_experience.end_date');
				foreach($arrIndex as $index)
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}			
		/*if(in_array('work_experience.country',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'work_experience.country' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='work_experience.country_id/#/country.id/#/country/#/country.name';										
			}*/
		$arrExtraFieldsTemp	=		
		array('work_experience.leaving_reason','work_experience.country');//'work_experience.country_id/#/country.id/#/country/#/country.name');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	    
	break;
	
	case 'health_staff':	

		$fieldNames = str_replace('health_staff.staff_type', 'health_staff.health_staff_type_id',$fieldNames);		
		$arrExtraFieldsTemp	=	array('health_staff.health_staff_type_id/#/health_staff_type.id/#/health_staff_type/#/health_staff_type.name');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
		
		if(in_array('health_staff.staff_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'health_staff.staff_type' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='health_staff.health_staff_type_id/#/health_staff_type.id/#/health_staff_type/#/health_staff_type.name';				
			}
            
		break;
	
	case 'patient_appliance':

        if (($includeExpired == 'No') && !in_array('patient_appliance.date_given',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (patient_appliance.date_given >= '$expiryDate' OR patient_appliance.date_given IS NULL OR patient_appliance.date_given = '0000-00-00')";
        }
        
		if(in_array('patient_appliance.date_given',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'patient_appliance.date_given' );
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
	
		$fieldNames = str_replace('patient_appliance.appliance_type', 'patient_appliance.appliance_type_id',$fieldNames);
		$fieldNames = str_replace('patient_appliance.requested_from', 'patient_appliance.requested_from_id',$fieldNames);
		$arrExtraFieldsTemp	=		array(
		'patient_appliance.appliance_type_id/#/patient_appliance_type.id/#/patient_appliance_type/#/patient_appliance_type.name',
		'patient_appliance.requested_from_id/#/requested_from.id/#/requested_from/#/requested_from.name',		
		);
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);
		if(in_array('patient_appliance.appliance_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'patient_appliance.appliance_type' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='patient_appliance.appliance_type_id/#/patient_appliance_type.id/#/patient_appliance_type/#/patient_appliance_type.name';				
			}
		if(in_array('patient_appliance.requested_from',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'patient_appliance.requested_from' );
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='patient_appliance.requested_from_id/#/requested_from.id/#/requested_from/#/requested_from.name';				
			}
		
		break;
	
	case 'patient_surgery':

        if (($includeExpired == 'No') && !in_array('patient_surgery.date_given',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (patient_surgery.date_given >= '$expiryDate' OR patient_surgery.date_given IS NULL OR patient_surgery.date_given = '0000-00-00')";
        }
        
		if(in_array('patient_surgery.date_given',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_surgery.date_given');
				foreach($arrIndex as $index)
				$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
		$fieldNames = str_replace('patient_surgery.surgery_type', 'patient_surgery.surgery_type_id',$fieldNames);
		$arrExtraFieldsTemp	=array(	'patient_surgery.surgery_type_id/#/patient_surgery_type.id/#/patient_surgery_type/#/patient_surgery_type.name');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	
		if(in_array('patient_surgery.surgery_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields,'patient_surgery.surgery_type' );
				foreach($arrIndex as $index)				
					$arrWhereFields[$index]='patient_surgery.surgery_type_id/#/patient_surgery_type.id/#/patient_surgery_type/#/patient_surgery_type.name';				
			}
            
		break;
	
	case 'treatment':
		
		$fieldNames = str_replace('treatment.date_started', 'treatment.date_from',$fieldNames);
		$fieldNames = str_replace('treatment.date_ended', 'treatment.date_until',$fieldNames);
		$fieldNames = str_replace('treatment.category', 'treatment.treatment_category_id',$fieldNames);		
		$fieldNames = str_replace('treatment.case', 'treatment.treatment_case_id',$fieldNames);
		$fieldNames = str_replace('treatment.result', 'treatment.treatment_result_id',$fieldNames);
		$fieldNames = str_replace('treatment.regimen', 'treatment.treatment_regimen_id',$fieldNames);
		$arrExtraFieldsTemp	=array(	
		'treatment.treatment_category_id/#/treatment_category.id/#/treatment_category/#/treatment_category.name',
		'treatment.treatment_case_id/#/treatment_case.id/#/treatment_case/#/treatment_case.name',
		'treatment.treatment_regimen_id/#/treatment_regimen.id/#/treatment_regimen/#/treatment_regimen.name',
		'treatment.treatment_result_id/#/treatment_result.id/#/treatment_result/#/treatment_result.name'		
		);
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	

        if (($includeExpired == 'No') && !in_array('treatment.date_until',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (treatment.date_until >= '$expiryDate' OR treatment.date_until IS NULL OR treatment.date_until = '0000-00-00')";
        }
		
		if(in_array('treatment.date_started',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'treatment.date_started');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='treatment.date_from';				
			}
		if(in_array('treatment.date_ended',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'treatment.date_ended');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='treatment.date_until';				
			}
		
		if(in_array('treatment.category',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'treatment.category');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='treatment.treatment_category_id/#/treatment_category.id/#/treatment_category/#/treatment_category.name';				
			}
		if(in_array('treatment.case',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'treatment.case');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='treatment.treatment_case_id/#/treatment_case.id/#/treatment_case/#/treatment_case.name';
			}
		if(in_array('treatment.result',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'treatment.result');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='treatment.treatment_result_id/#/treatment_result.id/#/treatment_result/#/treatment_result.name';				
			}
		if(in_array('treatment.regimen',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'treatment.regimen');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='treatment.treatment_regimen_id/#/treatment_regimen.id/#/treatment_regimen/#/treatment_regimen.name';				
			}
		
		$treatmentDates=array('treatment.date_from','treatment.date_until');
		foreach($treatmentDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)		
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
            
		break;
		
	case 'patient_visit':
	case 'visit details':

        if (($includeExpired == 'No') && !in_array('patient_visit.date_discharged',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (patient_visit.date_discharged >= '$expiryDate' OR patient_visit.date_discharged IS NULL OR patient_visit.date_discharged = '0000-00-00')";
        }
        
		$visitDates=array('patient_visit.date_referred','patient_visit.date_attended','patient_visit.date_discharged');
		foreach($visitDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)		
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}

		$fieldNames = str_replace('patient_visit.main_reason', 'patient_visit.main_treatment_reason_id',$fieldNames);
		$fieldNames = str_replace('patient_visit.detail_reason', 'visit_treatment_reasons.detail_treatment_reason_id',$fieldNames);
		$fieldNames = str_replace('patient_visit.hospital', 'patient_visit.hospital_id',$fieldNames);
		$fieldNames = str_replace('patient_visit.referred_from', 'patient_visit.referred_from_id',$fieldNames);
		
		$arrExtraFieldsTemp	=array(	
		'patient_visit.main_treatment_reason_id/#/treatment_reason.id/#/treatment_reason/#/treatment_reason.name',
		'visit_treatment_reasons.detail_treatment_reason_id/#/treatment_reason.id/#/treatment_reason/#/treatment_reason.name',
		'patient_visit.hospital_id/#/hospital.id/#/hospital/#/hospital.name',
		'patient_visit.referred_from_id/#/referred_from.id/#/referred_from/#/referred_from.name'
		);
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	
		if(in_array('patient_visit.main_reason',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_visit.main_reason');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='patient_visit.main_treatment_reason_id/#/treatment_reason.id/#/treatment_reason/#/treatment_reason.name';				
			}
		if(in_array('patient_visit.detail_reason',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_visit.detail_reason');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='visit_treatment_reasons.detail_treatment_reason_id/#/treatment_reason.id/#/treatment_reason/#/treatment_reason.name';				
			}
		if(in_array('patient_visit.hospital',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_visit.hospital');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='patient_visit.hospital_id/#/hospital.id/#/hospital/#/hospital.name';				
			}
		if(in_array('patient_visit.referred_from',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_visit.referred_from');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='patient_visit.referred_from_id/#/referred_from.id/#/referred_from/#/referred_from.name';		
			}
		break;
		
	case 'documentation':
    
		 $documentationDates=explode(',','documentation.cv_recvd_date,documentation.application_recvd_date,documentation.medical_recvd_date,documentation.medical_to_MO_date,documentation.medical_accepted_date,documentation.psych_recvd_date,documentation.psych_to_MO_date,documentation.psych_from_MO_date,documentation.psych_to_PD_date,documentation.psych_accepted_date,documentation.employer_ref_recvd_date,documentation.colleague_ref_recvd_date,documentation.friend_ref_recvd_date,documentation.minister_ref_recvd_date,documentation.interview_recvd_date,documentation.secondment_accepted_date,documentation.INF_role_agreed_date,documentation.contract_recvd_date,documentation.certificates_recvd_date,documentation.photos_recvd_date,documentation.passport_recvd_date,documentation.professional_recvd_date');
		foreach($documentationDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
	
		$fieldNames = str_replace('documentation.medical_accepted_by', 'documentation.medical_accepted_id',$fieldNames);
		$fieldNames = str_replace('documentation.link_person', 'documentation.link_person_id',$fieldNames);
		$fieldNames = str_replace('documentation.INF_role_agreed_date', 'documentation.post_agreed_date',$fieldNames);
		$fieldNames = str_replace('documentation.INF_role_agreed', 'documentation.post_agreed_id',$fieldNames);

		$arrExtraFieldsTemp	=array(	
		'documentation.link_person_id/#/name.id/#/name/#/name.forenames',
		'documentation.medical_accepted_id/#/name.id/#/name/#/name.forenames',
		'documentation.post_agreed_id/#/post.id/#/post/#/post.name'	
		);
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);

		if(in_array('documentation.INF_role_agreed',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'documentation.INF_role_agreed');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='documentation.post_agreed_id/#/post.id/#/post/#/post.name';				
			}
		if(in_array('documentation.INF_role_agreed_date',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'documentation.INF_role_agreed_date');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='documentation.post_agreed_date';				
			}
		if(in_array('documentation.link_person',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'documentation.link_person');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='documentation.link_person_id/#/name.id/#/name/#/name.forenames';				
			}
		if(in_array('documentation.medical_accepted_by',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'documentation.medical_accepted_by');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='documentation.medical_accepted_id/#/name.id/#/name/#/name.forenames';				
			}
			
		break;
	
	case 'orientation_arrangement':
    
		$orientationArrangementDates=array('orientation_arrangement.arrival_date','orientation_arrangement.pickup_arranged_date','orientation_arrangement.accomodation_arranged_date','orientation_arrangement.bus_ticket_arranged_date','orientation_arrangement.ticket_info_sent_to_pkr_date','orientation_arrangement.survival_orientation_booklet_date','orientation_arrangement.welcome_pack_date','orientation_arrangement.welcome_letter_date');
		foreach($orientationArrangementDates as $eachField)
			if(in_array($eachField,$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, $eachField);
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
	
	break;
		
	case 'patient_bill':

        if (($includeExpired == 'No') && !in_array('patient_bill.date_paid',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (patient_bill.date_paid >= '$expiryDate' OR patient_bill.date_paid IS NULL OR patient_bill.date_paid = '0000-00-00')";
        }
        
		if(in_array('patient_bill.date_paid',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_bill.date_paid');
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
	
	break;
	
	case 'patient_service':

        if (($includeExpired == 'No') && !in_array('patient_service.date_given',$arrWhereFields) && in_array($requester,$tables)) {  
            $filterDeleted .= " AND (patient_service.date_given >= '$expiryDate' OR patient_service.date_given IS NULL OR patient_service.date_given = '0000-00-00')";
        }
        
		if(in_array('patient_service.date_given',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_service.date_given');
				foreach($arrIndex as $index)	
					$arrWhereValues[$index]=$dbi->processDate($arrWhereValues[$index]);				
			}
	
		$fieldNames = str_replace('patient_service.service_type', 'patient_service.service_type_id',$fieldNames);
		$arrExtraFieldsTemp	=array(	'patient_service.service_type_id/#/patient_service_type.id/#/patient_service_type/#/patient_service_type.name');
		$arrExtraFields		=	array_merge($arrExtraFields,$arrExtraFieldsTemp);	
		if(in_array('patient_service.service_type',$arrWhereFields))
			{	
				$arrIndex=array_keys ($arrWhereFields, 'patient_service.service_type');
				foreach($arrIndex as $index)
					$arrWhereFields[$index]='patient_service.service_type_id/#/patient_service_type.id/#/patient_service_type/#/patient_service_type.name';				
			}
	//$s=implode(',',$arrWhereFields);
//$dbi->test_file("arrWhereFields: $s"); exit;

		break;
		
	default:
		break;
	}

}	
	
/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-d: ".$fieldNames."\n");
fclose($fh); 
*/
	//$query	=	" SELECT $fieldNames FROM name ";
	$query	=	'';
	$patientIDTable	= array('patient_visit','treatment','patient_surgery','patient_service','patient_bill','patient_appliance','patient_inf');
///	$joinTables	=	'';
	
		foreach($tables as $eachTable){
				//FOR email,phone and address table JOIN and $filterDeleted is not set here it is set above .But for all other tables it is done here
			if((strcasecmp($eachTable,'address')==0)||(strcasecmp($eachTable,'email')==0)||(strcasecmp($eachTable,'phone')==0)){
				
			}
			elseif(strcasecmp($eachTable,'training')==0){					
                $joinTables	.=	" LEFT JOIN `$eachTable` ON name.id = $eachTable.name_id ";	
                $joinTables	.= $join_training;
                $join_training='';
                $filterDeleted.=" AND ((`name_training_needs`.`deleted` is NULL) OR (`name_training_needs`.`deleted` = '0') ) ";
                $filterDeleted.=" AND ((`$eachTable`.`deleted` is NULL) OR (`$eachTable`.`deleted` = '0') ) ";
			}
			elseif(strcasecmp($eachTable,'visa_history')==0){					
				$joinTables	.= $join_passport;
				$joinTables .= $join_visa;
				$join_passport='';
                $join_visa='';
                $filterDeleted.=" AND ((`passport`.`deleted` is NULL) OR (`passport`.`deleted` = '0') ) ";
                $filterDeleted.=" AND ((`$eachTable`.`deleted` is NULL) OR (`$eachTable`.`deleted` = '0') ) ";
			}
			elseif(strcasecmp($eachTable,'passport')==0){
				$joinTables	.= $join_passport;
				$join_passport='';
                $filterDeleted.=" AND ((`$eachTable`.`deleted` is NULL) OR (`$eachTable`.`deleted` = '0') ) ";
			}
			elseif(strcasecmp($eachTable,'service')==0){
				$joinTables	.= $join_service;
				$join_service='';
                $filterDeleted.=" AND ((`$eachTable`.`deleted` is NULL) OR (`$eachTable`.`deleted` = '0') ) ";
			}
			elseif(strcasecmp($eachTable,'site_programme')==0){
				$joinTables .= $join_post;
				$join_post='';
				$filterDeleted.=" AND ((`post`.`deleted` is NULL) OR (`post`.`deleted` = '0') ) ";	
			}
			elseif(strcasecmp($eachTable,'site_location')==0){
				$joinTables	.= $join_service;
				$join_service='';
				$filterDeleted.=" AND ((`service`.`deleted` is NULL) OR (`service`.`deleted` = '0') ) ";	
			}
			else{			
				if (in_array ($eachTable, $patientIDTable)) {				
					$joinTables	.=	" LEFT JOIN `$eachTable` ON `name`.`id` = `$eachTable`.`patient_id` ";				      
					if(strcasecmp($eachTable,'patient_visit')==0){					
                        $joinTables	.= $join_reason;
                        $join_reason='';
                        $filterDeleted.=" AND ((`visit_treatment_reasons`.`deleted` is NULL) OR (`visit_treatment_reasons`.`deleted` = '0') ) ";                        
                    }
                }
				else{
                    if (strcasecmp($eachTable,'relation')==0) {
                        $joinTables	.=	" LEFT JOIN `$eachTable` ON name.id = $eachTable.relation_id ";
                    }
                    else {
                    	//Artur Neumann INF/N
                    	//ict.projects@nepal.inf.org
                    	//01.08.2013
                    	//suggesting to change to :  
                    	//$joinTables .= " LEFT JOIN `$eachTable` ON name.id = $eachTable.name_id AND ((`$eachTable`.`deleted` is NULL) OR (`$eachTable`.`deleted` = '0') ) ";
                    	//because of the bug that a person with a deleted staff entry is not shown in a search with  pressed ALT key
                    	//see bugreport: https://secure.inf.org/moodle/mod/forum/discuss.php?d=211#p753
                        $joinTables .= " LEFT JOIN `$eachTable` ON name.id = $eachTable.name_id "; 	
                    }                        
				}
				$filterDeleted.=" AND ((`$eachTable`.`deleted` is NULL) OR (`$eachTable`.`deleted` = '0') ) ";			
			}			
		}	
		//Now parse the operations i.e either AND or OR and  add parenthesis to the operations. If the last operation is OR then we need to add a closing parenthesis ')' to the end of user's search clause
		$arrWhereAndOrOperation=$dbi->applyParenthesis($arrWhereAndOrOperation);		

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-e: ".$whereFields."\n");
fwrite($fh, "ps-e: ".$whereValues."\n");
fwrite($fh, "ps-e: ".$joinTables."\n");
fclose($fh); 
*/
		if($whereFields !='')
		{
			//$s=implode(',',$arrWhereFields);
//$dbi->test_file("ExtraFields :$s"); 
			$arrFieldsInfo	=	$dbi-> getFieldsInfo($arrExtraFields,$arrWhereOperations,$arrWhereFields,$arrWhereFields,$arrWhereValues,$arrWhereAndOrOperation);//returns an array of strings[CSV]
			$strWhereFields	=	$arrFieldsInfo[0];
			$strWhereOperations=$arrFieldsInfo[1];
			$strWhereValues	=	$arrFieldsInfo[2];
			$strWhereAndOrOperations = $arrFieldsInfo[6];

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-e2: ".$strWhereFields."\n");
fwrite($fh, "ps-e2: ".$strWhereValues."\n");
fwrite($fh, "ps-e2: ".$strWhereFieldsExtra."\n");
fwrite($fh, "ps-e2: ".$strWhereValues."\n");
fclose($fh); 
*/
			if($strWhereFields!="")	
				$whereClausePart=	$dbi-> buildWhereClauseSimple($strWhereFields,$strWhereValues,$strWhereOperations,$strWhereAndOrOperations);// here No third table needed to search 
			$arrWhereFieldsSimple=explode(',',$strWhereFields);

			$strWhereOperationsExtra=	$arrFieldsInfo[3];
			$strWhereValuesExtra	=	$arrFieldsInfo[4];
			$strWhereFieldsExtra	=	$arrFieldsInfo[5];
			$strWhereAndOrOperationsExtra =	$arrFieldsInfo[7];
		
			if($strWhereFieldsExtra!='')//now the remaining fields are to be searched in more than one table
				$whereClausePartExtra	=	$dbi->buildWhereClauseExtra($strWhereAndOrOperationsExtra,$strWhereFieldsExtra,$strWhereValuesExtra,$strWhereOperationsExtra)	;		

			//Now build  whereClause in the field order as specified by the user during the search (array_key_exists is in the required order)

			foreach($arrWhereFields as $wf){

				if(in_array($wf,$arrWhereFieldsSimple)){
					$whereClause.=array_shift ($whereClausePart);
				}
				else{			
					//FIRST GET THE EXTRA FIELDS WHERE CLAUSE AS SPECIFIED IN THE $whereField by the user and add to $whereClause
					$whereClause	.=array_shift ($whereClausePartExtra);		
				}		
			}
			if($closeBraces4whereClause)
				$whereClause.=' )';		
		}//end if		
		
//$dbi->test_file("page sp search: whereClause:".$whereClause);		
		$whereClause.=" AND $filterDeleted ";

		$query	.=" SELECT DISTINCT $fieldNames FROM name  $joinTables WHERE $whereClause ";
		//$query	.="order by forenames,surname";
		
		if(strpos ( $fieldNames,'surname.surname'))
		$query.=' and surname.priority=1';	

		if(strpos ( $fieldNames,'nationality.nationality'))
		$query.=' and nationality.priority=1 ';
        $query.=' LIMIT '.$recLimit;	
	}			

/*      
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps-f: ".$query."\n");
fclose($fh); 
*/
//$dbi->test_file("page sp search: \n $query");		

        $retVal.='<staffIDs></staffIDs>'; 
		$retVal.='<relativeStaffIDs></relativeStaffIDs>';

		$retVal .= "<rootTag>";
        
        if($dbi->maintenance()){				
			$retVal .=	"<searchMatch>false</searchMatch>";						
			$retVal .=	"<subTag></subTag>";
			$retVal .= '</rootTag>';
            $retVal .= "<dgColumns>Maintenance mode - try again later</dgColumns>";            
			print $retVal;        
			exit;
		}
        
		$result      = $dbi->query($query);
		if($searchDescription!='')
			$dbi->logSearchQuery($userID,$searchDescription,$query,$saved);
	
		$fNames      = explode(',',$fieldNames);
		$tagNames=array();
	/*
	foreach($fNames as $eachField){
			$tag   = substr($eachField,strrpos($eachField,".")+1);
			array_push($tagNames,$tag);
		}
		//$tagNames	= array_diff($tagNames,$tables);
		$commaSeparatedFields=implode(',',$tagNames);
	*/			
		
		if(mysql_num_rows($result)	==	0){				
			$retVal .=	"<searchMatch>false</searchMatch>";						
			$retVal .=	"<subTag></subTag>";
			$retVal .= '</rootTag>';
            $retVal .= "<dgColumns>No matching records found</dgColumns>";             
			print $retVal;

/*
$fh = fopen('searchtest.txt', "a");
fwrite($fh, "ps: ".$query."\n");
fwrite($fh, "ps: ".$retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/                
                
			exit;
		}
		
		while($row = mysql_fetch_array($result)){	               
			$retVal .=	"<subTag>";						
			for($i = 0;$i<sizeof($fNames);$i++){				  
				$fName = $fNames[$i];
				//$tag   = substr($fName,strrpos($fName,".")+1);
				//$tagroot   = substr($fName,0,strrpos($fName,".")+1);
				
				// pass tablename.fieldname rather than just fieldname
                $tag2   = $dbi->modify_tag($fName,true,1);
                $retVal .= $dbi->process_tag($fName,$row[$i],true,1);
                if ($tag2!="zzzid")//need to push zzzid at the last so dont push now
                    array_push($tagNames,$tag2);
            }
			$retVal	.="</subTag>";						
		}					
		$retVal .= '</rootTag>';
	
	array_push($tagNames,'zzzid');		
	$commaSeparatedFields=implode(',',array_unique($tagNames));
	$retVal .="<dgColumns>$commaSeparatedFields</dgColumns>";
	$retVal .="<searchHistorySaved>$searchHistorySaved</searchHistorySaved>";
	print $retVal;	
	
	//$dbi->test_file("page sp search:  $query",$saved);

	//$dbi->test_file("page sp search:  $query",$searchHistorySaved);

/*JUST FOR TESTING PURPOSE*/

$fh = fopen('/tmp/searchtest.txt', "a");
fwrite($fh, "ps: ".$query."\n");
fwrite($fh, "ps: ".$searchDescription."\n");
fwrite($fh, "ps: ".$retVal); 
fwrite($fh,"\n");
fclose($fh); 


?>