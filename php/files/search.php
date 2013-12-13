<?php
include_once (dirname(__FILE__).'/connect.php');

	/*$fieldName   =	"1";
	$fieldValue  =	"1";
	$action      =	"Search All";
	$fieldNames  =	"name.forenames,training_needs.need";
	$recLimit	 =	10;*/
//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);
	$DB=$dbi->getdbname();
	
function getMostRecentPassportID($name_id,$db)
{
	$query="SELECT passport.id as id from passport where passport.name_id='$name_id' 
    and ((`passport`.`deleted` is NULL) OR (`passport`.`deleted` = '0') ) ORDER BY issue_date DESC limit 1";
	$queryResult = $db->query($query);
    $retval = -1;
	if($queryResult){
        if (mysql_num_rows($queryResult) > 0) {
            $row = mysql_fetch_assoc($queryResult);
            $retval = $row['id']; 
        }
    }
    return $retval;
}

function getMostRecentStaffID($name_id,$db)
{
	$query="SELECT staff.id as id from staff where staff.name_id='$name_id' 
    and ((`staff`.`deleted` is NULL) OR (`staff`.`deleted` = '0') ) ORDER BY start_date DESC limit 1";
	$queryResult = $db->query($query);
    $retval = -1;
	if($queryResult){
        if (mysql_num_rows($queryResult) > 0) {
            $row = mysql_fetch_assoc($queryResult);
            $retval = $row['id']; 
        }
    }
    return $retval;
}

function getMostRecentStatus($name_id,$db)
{
    $id = getMostRecentStaffID($name_id,$db);
	$query="SELECT staff.status as status from staff where staff.id='$id' 
    and ((`staff`.`deleted` is NULL) OR (`staff`.`deleted` = '0') )";
	$queryResult = $db->query($query);
    $retval = '';
	if($queryResult){
        if (mysql_num_rows($queryResult) > 0) {
            $row = mysql_fetch_assoc($queryResult);
            $retval = $row['status']; 
        }
    }
    return $retval;
}

function getMostRecentVisaName($name_id,$db)
{
    $passportID = getMostRecentPassportID($name_id,$db);
    
	$query="SELECT visa.id as id, visa.name as name FROM visa 
	LEFT JOIN passport ON visa.id = passport.visa_id 
	WHERE passport.id = ".$passportID." AND ((`visa`.`deleted` is NULL) OR (`visa`.`deleted` = '0') )";
	$retval = "";
    $queryResult = $db->query($query);
	if($queryResult){
        if (mysql_num_rows($queryResult) > 0) {
            $row = mysql_fetch_assoc($queryResult);
            $retval = $row['name'];
        }
    }
    return $retval;
}

function getVisaName($name_id,$db){
	
	$visaInfo='<visaInfos>';

	$query= "SELECT DISTINCT visa.id as id, visa.name as name FROM visa 
	LEFT JOIN passport ON visa.id = passport.visa_id 
	WHERE passport.name_id ='$name_id' and ((`passport`.`deleted` is NULL) OR (`passport`.`deleted` = '0') )
	and ((`visa`.`deleted` is NULL) OR (`visa`.`deleted` = '0') )
	ORDER BY `passport`.`issue_date` DESC";
	
	$queryResult = $db->query($query);
	if($queryResult){
		if (mysql_num_rows($queryResult) > 0)
			{
				while($row=mysql_fetch_assoc($queryResult)){					
					$visaInfo.='<idTitle data="'.$row['id'].'">'.$row['name'].'</idTitle>';
				}		
			}
		else
			$visaInfo.='<idTitle></idTitle>';			
	}
	else
		$visaInfo.='<idTitle></idTitle>';
	
	$visaInfo.='</visaInfos>';
	return($visaInfo);
}
		
function getRelatedStaffName1($name_id,$db)
{
	$query="SELECT  `name`.`forenames` FROM `name` where `name`.`id`='$name_id' and `name`.`deleted`=0";
	$queryResult = $db->query($query);
	$row = mysql_fetch_assoc($queryResult);
	return $row['forenames'];
}	

//'<relative><zzzid></zzzid><forenames>'.getRelatedStaffName($fieldValue).' </forenames><relationship>Self</relationship></relative>';		
	
function getRelatedStaffInfo($name_id,$site,$db)
{
		$selfName=getRelatedStaffName1($name_id,$db);

		return	'<relative><zzzid>'.$name_id.'</zzzid><forenames>'.$selfName.'</forenames><relationship>Self</relationship><relation_timestamp>0000-00-00 00:00:00</relation_timestamp></relative>';	
}	
	
//eg: select field1 from table where check_field=value
	//select type from email where id=1
function check_types($table,$field1,$check_field,$value,$db)
	{ 			
		$retValue="<$field1></$field1>";
		if(($value==null)||($value==''))
			return $retValue;
		
	//	$query="SELECT  `$field1`,`type` FROM `$table` where `$check_field`='$value' AND `$table`.`deleted`='0'";
		$query="SELECT  `$field1` FROM `$table` where `$check_field`='$value' AND `$table`.`deleted`='0'";

		$queryResult=$db->query($query);
		if (mysql_num_rows($queryResult) > 0)
		{
			$row = mysql_fetch_assoc($queryResult);
			
		//	if ((!strcasecmp ($row['type'] ,'personal'))||(!strcasecmp ($row['type'] ,'office')))//if official or personal
				$retValue="<$field1>".$row[$field1]."</$field1>";									
		}	

		return $retValue;
	}	
	
function get_official_email($name_id,$db)//get official email of the person for all active posts (a person may have many active posts at the same time)
{
	$retval1='';
	$query="SELECT post.email  FROM `post` LEFT JOIN service ON post.id = service.post_id WHERE service.name_id=	'$name_id' AND `post`.`deleted`='0' AND `service`.`deleted`='0' AND (`service`.`date_until` = '0000-00-00' OR `service`.`date_until` >= curdate( )) ORDER BY `service`.`date_from` ASC";
	$queryResult = $db->query($query);
	
		if (mysql_num_rows($queryResult) > 0)
		{
			while($row=mysql_fetch_assoc($queryResult))
			{
			//$row = mysql_fetch_assoc($queryResult);
                $retval1 .= '<officialEmail>'.$row['email'].'</officialEmail>';	
			}
		}
        else
		  	$retval1 .= '<officialEmail></officialEmail>';
		
	return  $retval1;
} 

function get_staff_working_days($name_id,$db){
	
	$workingWeek = $db->daysInWorkingWeek($name_id);
	return  '<working_week>'.$workingWeek.'</working_week>';
}

function get_patient_inf_info($patient_id,$DB,$dbi)
{
	$retval1='';
	$query="SELECT `id` as patient_inf_id,PAL,PWD,care_after_cure,footwear_needed,patient_inf_timestamp  
	FROM `$DB`.`patient_inf` WHERE `$DB`.`patient_inf`.`patient_id`='$patient_id' AND  `patient_inf`.`deleted` = '0'";
	$queryResult = $dbi->query($query);
	
		if (mysql_num_rows($queryResult) > 0)
		{
			$row = mysql_fetch_assoc($queryResult);
			$retval1 .= '<patient_inf_id>'.$row['patient_inf_id'].'</patient_inf_id>';	
			$retval1 .= '<PAL>'.$row['PAL'].'</PAL>';
			$retval1 .= '<PWD>'.$row['PWD'].'</PWD>';
			$retval1 .= '<care_after_cure>'.$row['care_after_cure'].'</care_after_cure>';
			$retval1 .= '<footwear_needed>'.$row['footwear_needed'].'</footwear_needed>';
			$retval1 .= '<patient_inf_timestamp>'.$row['patient_inf_timestamp'].'</patient_inf_timestamp>';
			
		}else
		  	$retval1 .= '<patient_inf_timestamp></patient_inf_timestamp><patient_inf_id></patient_inf_id><PAL></PAL><PWD></PWD><care_after_cure></care_after_cure><footwear_needed></footwear_needed>';
		
	return  $retval1;
} 

function get_surnames($name_id,$db)
{
	$retSurnames='';
	$query="SELECT surname.id as surname_id, surname.surname, min(surname.priority) as min_priority  FROM surname WHERE surname.name_id =	'$name_id' 
	AND surname.deleted = '0' GROUP BY surname.surname ORDER BY min_priority ASC";
	$queryResult = $db->query($query);

		if (mysql_num_rows($queryResult) > 0)
		{		
			while($row=mysql_fetch_assoc($queryResult))
			//$row = mysql_fetch_assoc($queryResult);
			$retSurnames .= '<surnames  data="'.$row['surname_id'].'">'.$row['surname'].'</surnames>';	
			
		}else
		  	$retSurnames .= '<surnames></surnames>';
		
	return $retSurnames;
}

function get_nationality($db,$name_id)
{
	$retnationalitys='';
	$query="SELECT `nationality`.`id` as nationality_id, `nationality`.`nationality`, min(`nationality`.`priority`) as min_priority FROM `nationality` WHERE `nationality`.`name_id` =	'$name_id' AND `nationality`.`deleted`='0' GROUP BY nationality.nationality ORDER BY min_priority ASC";
	$queryResult = $db->query($query);

	if (mysql_num_rows($queryResult) > 0)
	{		
		while($row=mysql_fetch_assoc($queryResult))
		//$row = mysql_fetch_assoc($queryResult);
		$retnationalitys .= '<nationalities  data="'.$row['nationality_id'].'">'.$row['nationality'].'</nationalities>';	
	}
	else
		$retnationalitys .= '<nationalities></nationalities>';		
	
	return $retnationalitys;
}
	
/* input : result , fNames, current,own output : retVal */
 function process_search_list($result,$fNames,$current,$own,$country,$db)
 {	
	$retval = "";

	while($row = mysql_fetch_array($result)){			
                
		$retval	.=	"<subTag>";	
        $retval1	=	"";        
				
		for($i = 0;$i<sizeof($fNames);$i++){
				
			$tag = $fNames[$i];
            $retval .= $db->process_tag($tag,$row[$i]);
		}	

		$retval .=	'<current>'.$current.'</current>';
		$retval .=	'<own>'.$own.'</own>';			
		$retval	.=	"</subTag>";         
	}	
	return $retval; 
 }			
 
	$_POST=$dbi->prepareUserData($_POST);
	$DB=$dbi->getdbname();
	
	$fieldName   =	$_POST['fieldName'];
	$fieldValue  =	$_POST['fieldValue'];
	$action      =	$_POST['action'];
	$fieldNames  =	$_POST['fieldNames'];
	$recLimit	 =	$_POST['recLimit'];
	$requester	 =	$_POST['requester'];
	$rowsAffected =	0;
	$id_country	=	$dbi-> get_type("country","id","name");
	$id_caste	=	$dbi-> get_type("caste","id","name");
	$id_religion	=	$dbi-> get_type("religion","id","name");
	$site	=	$dbi->getSiteName();
	$photoName='';	
	$extraWhere = '';
	$joinTable='';
	
	switch($action){
	
	case 'Search All':
		
		if($site!='Patient'){
			$extraWhere = "AND (staff.leaving_date is null OR staff.leaving_date = '0000-00-00' or staff.leaving_date >= curdate()) AND ((`staff`.`deleted` is NULL) OR (`staff`.`deleted` = '0') ) ";
			//AND (staff.leaving_reason_id = 0  OR staff.leaving_reason_id is null)";
			//AND (staff.retirement_date >= curdate() OR staff.retirement_date = '0000-00-00' OR staff.retirement_date is null)";
			$joinTable .= " LEFT JOIN staff ON name.id = staff.name_id ";
			$location_id=$dbi->getSiteLocationID();
			$programme_id=$dbi->getSiteProgrammeID();
			if (($location_id != 0) || ($programme_id != 0))
			{
				$joinTable .= " LEFT JOIN `service` ON `name`.`id` = `service`.`name_id` ";
			}
			if ($location_id != 0)
			{
				$extraWhere .= "AND (`service`.`location_id` = '$location_id') AND ((`service`.`deleted` is NULL) OR (`service`.`deleted` = '0') ) ";
			}
			if ($programme_id != 0)
			{
				$joinTable .= " LEFT JOIN `post` ON `service`.`post_id` = `post`.`id` ";
				$extraWhere .= "AND (`post`.`programme_id` = '$programme_id') AND ((`post`.`deleted` is NULL) OR (`post`.`deleted` = '0') ) ";
			}
		}
		
    case 'Search One':
    
		$searchWhom	=	$_POST['searchWhom'];
        
//$dbi->test_file("\n searchWhom	: $searchWhom	");
		$tables      =  '';
		
		$infStaffOnly='';
		$mainTable ='';
		$filterDeleted='';
		
		if($site=='Patient'){
			$joinTable .=' LEFT JOIN patient_inf ON name.id = patient_inf.patient_id ';
            $fieldNames .= ",patient_inf.patient_id";
			$infStaffOnly=' name.id in(select patient_inf.patient_id from patient_inf where patient_inf.deleted=0) ';
			$mainTable	='patient_inf';
		}
		else{
			$joinTable .=' LEFT JOIN inf_staff ON name.id = inf_staff.name_id ';
            $fieldNames .= ",inf_staff.name_id";
			$infStaffOnly=' name.id in(select inf_staff.name_id from inf_staff where inf_staff.deleted=0) ';
			$mainTable='inf_staff';
		}
    
        $limited = $_POST['limited'];    
        if($limited!="") {
            $infStaffOnly .= " AND name.id = '".$limited."' ";
        }
    
		$filterDeleted =" `name`.`deleted`='0' AND `surname`.`deleted`='0' ";
        //$filterDeleted .=" AND (`address`.`deleted`='0' OR `address`.`deleted` is null) ";
        $filterDeleted .=" AND (`nationality`.`deleted`='0' OR `nationality`.`deleted` is null) ";

        /*
		//Now check if there is corresponding address value in name table for this person. if yes then check if it is deleted one or not		
		if($dbi->checkIfRowExixts('address_id','name','name_id',$fieldValue))
			$filterDeleted .=" AND `address`.`deleted`='0' ";
		*/
		$tables		 = "name
						LEFT JOIN surname ON name.id = surname.name_id
                        LEFT JOIN nationality ON name.id = nationality.name_id
						$joinTable";
		//$tables .= " LEFT JOIN address ON name.address_id = address.id";				
        $where      = "";
        
		//$relativesOnly	=	" name.id in (select relation_id from relation where relation.deleted='0') ";
		//$searchAll		=	"SELECT `name`.`id` AS name_id FROM `name` UNION SELECT `relation`.`relation_id` AS name_id FROM `relation`";
		$searchFilter	=	$infStaffOnly;
		
		if ($searchWhom	==	'Staff'){	
			//$filterDeleted .= " AND `$mainTable`.`deleted`='0' ";
			$where	=	"where $filterDeleted AND $searchFilter AND surname.priority=1 AND (nationality.priority=1 OR nationality.priority is null) $extraWhere AND $fieldName='".$fieldValue."' 			
			ORDER BY surname, forenames LIMIT $recLimit";
		}	
		else if($searchWhom	==	'Relative'){
			
			$where	=	"where surname.priority=1 AND (nationality.priority=1 OR nationality.priority is null) AND $fieldName='".$fieldValue."'  AND $filterDeleted
			ORDER BY surname, forenames LIMIT $recLimit";
		}
        
		$fieldNames		=	implode(',',array_unique(explode(',',$fieldNames)));
		$query       = "select DISTINCT $fieldNames from $tables $where ";		
// $dbi->test_file($action,$query);
		$result      = $dbi->query($query);
		$retVal      = "<users>";	
        $ok = true;
        
		if(mysql_num_rows($result)==0){
			$retVal.='<user></user>';
            $retVal .= '</users>';
            $retVal .= "<dgColumns>No matching records found</dgColumns>"; 
            $fieldValue = '-1';
            $ok = false;
        }
		
        if($action=="Search All" && $dbi->maintenance()){									
			$retVal .=	"<user></user>";
			$retVal .= '</users>';
            $retVal .= "<dgColumns>Maintenance mode - try again later</dgColumns>"; 
            $fieldValue = '-1';
            $ok = false; 
		}
        
        if ($ok) {
            $fNames      = explode(",",$fieldNames);
			$tagNames=array();			

            while($row = mysql_fetch_array($result)){
                     
                $retVal .= '<user>';
			
                for($i = 0;$i<sizeof($fNames);$i++){
			
                    $fName = $fNames[$i];
					if ($action == 'Search All') {
						$tag = $fName; // pass tablename.fieldname rather than just fieldname
						$tag2   = $dbi->modify_tag($tag,true);
						$retVal .= $dbi->process_tag($tag,$row[$i],true,1);
					}
					else {
						$tag   = substr($fName,strrpos($fName,".")+1);
						$tag2 = $tag;
						$retVal .= $dbi->process_tag($tag,$row[$i],true);
					}
					if (($tag2!="zzzid") && ($tag2!="zzzstaffid"))//need to push zzzstaffid and zzzid at the last so dont push now
						array_push($tagNames,$tag2);                      
                }			
                $retVal .= '</user>';						
            }				
            $retVal .= '</users>';
			
			array_push($tagNames,'zzzstaffid');
			array_push($tagNames,'zzzid');			
			$commaSeparatedFields=implode(',',array_unique($tagNames));
			$retVal .="<dgColumns>$commaSeparatedFields</dgColumns>";
        }

        $retVal.=get_staff_working_days($fieldValue,$dbi);
        $retVal.=getVisaName($fieldValue,$dbi);
        $retVal.="<passportID>".getMostRecentPassportID($fieldValue,$dbi)."</passportID>";
        $retVal.=get_surnames($fieldValue,$dbi);
        $retVal.=get_nationality($dbi,$fieldValue);
        //$retVal.='<staffIDs>'.$dbi->get_staff_id().'</staffIDs>';
        //$retVal.='<relativeStaffIDs>'.$dbi->get_relation_staff_id().'</relativeStaffIDs>';
		break;

	case 'Initialise Relatives'	:

		$retVal = '<relation><relative><__id>-1</__id><forenames>None</forenames><relationship>None</relationship><relation_timestamp></relation_timestamp></relative></relation>';
			
		break;	

	case 'Search Relatives'	:
	
		//$selfForename= $_POST['selfForename'];
		$tables      = 'name LEFT JOIN relation ON name.id = relation.relation_id LEFT JOIN surname ON name.id = surname.name_id';		
		$where       = 'WHERE surname.priority=1 AND relation.name_id='.$fieldValue.' AND name.id !='.$fieldValue;
        
		if($site!='Patient'){
			$tables .= ' LEFT JOIN inf_staff ON name.id = inf_staff.name_id ';
		}
		
		$query   	 = "select $fieldNames from $tables $where AND name.deleted='0' AND relation.deleted = '0' ORDER BY forenames";		
		
		$result      = $dbi->query($query);
		$retVal = "<relation>";
		$fNames      = explode(",",$fieldNames);
		
		//if(mysql_num_rows($result)==0)
			//$retVal.='<relative></relative>';
			
		//$rowsAffected	=	mysql_num_rows($result);
		
		while($row = mysql_fetch_array($result)){
                     
			$retVal .= '<relative>';
			
			for($i = 0;$i<sizeof($fNames);$i++){
			
				$fName = $fNames[$i];
				$tag   = substr($fName,strrpos($fName,".")+1);
                $retVal .= $dbi->process_tag($tag,$row[$i],true);
/*
					if ($tag=="id")$tag='zzzid';
					if ($tag	!= "country_id")
						{
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
						}
					
					else //($tag	==	"country_id") 
						{	
							$ctry_id=$row[$i];
							$retVal .= '<'.$tag.'>'.$id_country[$ctry_id].'</'.$tag.'>';
						}	
*/                        
			}
            if ($site != 'Patient') {
                $retVal .= '<status>'.getMostRecentStatus($row[0],$dbi).'</status>';
            }
			$retVal .= '</relative>';						
		}

        $where = 'WHERE surname.priority=1 AND name.id ='.$fieldValue;
		$query1 = "select $fieldNames from $tables $where AND name.deleted='0'";

        $result = $dbi->query($query1);
        $row = mysql_fetch_array($result);
		$retVal .= '<relative>';
			
		for($i = 0;$i<sizeof($fNames);$i++){
			
			$fName = $fNames[$i];
			$tag   = substr($fName,strrpos($fName,".")+1);
            if ($tag == 'relationship') {
                $retVal .= "<relationship>Self</relationship>";
            }
            else if ($tag == 'relation_timestamp') {
                $retVal .= "<relation_timestamp>0000-00-00 00:00:00</relation_timestamp>";
            }
            else {
                $retVal .= $dbi->process_tag($tag,$row[$i],true);
            }
        }
        if ($site != 'Patient') {
            $retVal .= '<status>'.getMostRecentStatus($row[0],$dbi).'</status>';
        }        
		$retVal .= '</relative>';						       
        
		//$retVal.=getRelatedStaffInfo($fieldValue,$site,$dbi);	
			
	//	$retVal.='<relative><zzzid></zzzid><forenames> '.ltrim($selfForename).' </forenames><relationship>Self</relationship></relative>';
		$retVal .= '</relation>';
		
		break;
		
	case 'Search List'	:
	//$dbi->test_file($action.' szs  '.$table);
		$table		 = $_POST['table1']; 
		$table2		 = $_POST['table2'];
		$subQField	 = $_POST['subQField'];
		$showAll	 = $_POST['showAll'];
		$familyID	 = $dbi->get_family_id($fieldValue,true);
		
		// default first
		$select1	= ' (select '.$subQField.' from name where id='.$fieldValue.'  AND name.deleted = 0) ';
		$where1		= 'where id in '.$select1;
		$query1		= "select $fieldNames from `$table` $where1 AND `$table`.`deleted` = '0'";
// $dbi->test_file("$action 1",$query1);
		$result1	= $dbi->query($query1);
		$maxresult1	= mysql_num_rows($result1);
		if($maxresult1 == 0)
			$select1 = "(0)";
		
		// for selected name (except for default)
		$select2	= '(select '.$subQField.' from `'.$table2.'` where name_id='.$fieldValue.' AND `'.$table2.'`.`deleted` = 0)';
		$where2		= 'where id in '.$select2.' and id not in '.$select1;
		$query2		= "select $fieldNames from `$table` $where2  AND `$table`.`deleted`='0'";
// $dbi->test_file("$action 2",$query2);		
		$result2	= $dbi->query($query2);
		$maxresult2	= mysql_num_rows($result2);
		if($maxresult2 == 0)
			$select2 = $select1;
		
		if ($showAll == "true")
		{
			// other for family (except for name)
			$select3	= '(select id from `'.$table.'` where family_id IN ('.$familyID.') AND `'.$table.'`.`deleted`=0)'; 
			$where3		= 'where id in '.$select3.' and id not in '.$select2;
			$query3		= "select $fieldNames from `$table` $where3 AND `$table`.`deleted`='0'";
// $dbi->test_file("$action 3",$query3);
			$result3	= $dbi->query($query3);
			$maxresult3	= mysql_num_rows($result3);
		}
		else 
			$maxresult3	= 0;
		
		$ok = true;		
		$fNames      = explode(",",$fieldNames);
		$retVal = "<rootTag>";
		
		if ($dbi->isNotAllowed($table,'view')) { //hide data if no permissions for given table
			$retVal	.=	'<subTag>disallowed</subTag>';
			$ok = false;
		}
		else if(($maxresult1 + $maxresult2 + $maxresult3) == 0) {
			$retVal	.=	'<subTag></subTag>';
			$ok = false;
		}
		if ($ok) {
			$retVal .= process_search_list($result1,$fNames,"true","true",$id_country,$dbi);
			$retVal .= process_search_list($result2,$fNames,"false","true",$id_country,$dbi);
			if ($showAll == "true")
				$retVal .= process_search_list($result3,$fNames,"false","false",$id_country,$dbi);	
		}
		
		$retVal .= '</rootTag>';		
		
        if($table=='email')
            $retVal.=get_official_email($fieldValue,$dbi);
        
		break;
		
	case 'Search Others':
	
		$table		 = $_POST['table1'];		
		$field_ID	='name_id';
        $extra_patient_where = '';
        
        if (($table == 'patient_service') || ($table == 'patient_surgery') || ($table == 'patient_appliance') || ($table == 'patient_bill') || ($table == 'treatment')){
            $field_ID = 'patient_id';
            $showAll	 = $_POST['showAll'];
            $sinceDate=$dbi->get_last_visit_date($fieldValue,$showAll);
        }
        
		switch($table){

		case 'photo':
			$orderby	= "order by id desc";
			$currentUserID=$_POST['currentUserID'];
			break;
			
		case 'passport':
			$currentUserID=$_POST['currentUserID'];
		case 'visa':
        case 'visa_history':
			$orderby	= "order by issue_date desc";
			break;
		
        case 'training':
            $orderby    = "order by course_id desc";
            break;
            
		case 'staff':
		case 'education':
		case 'insurance':
		case 'registrations':
		case 'registrations':
		case 'work_experience':
			$orderby	= "order by start_date desc";
			break; 
		
		case 'review':
			$orderby	= "order by review_date desc";
			break;

		case 'leave':
		case 'movement':
		case 'hospitalisation':
		case 'service':		
			$orderby	= "order by date_from desc";
			break;
			
		case 'secondment':
			$orderby	= "order by osa_member_sent_date desc";
			break;
			
		case 'documentation':
			$orderby	= "order by application_recvd_date desc";
			break;
		
		case 'patient_visit':
			$field_ID='patient_id';
			//$orderby	= "order by date_attended desc";
			//$patient_inf_info=get_patient_inf_info($fieldValue,$DB,$dbi);// get the patient_inf information needed
			// set the query fields for searching from patient_visit
			//$fieldNames='id,patient_id,type,affected,date_referred,referred_from_id,date_attended,date_discharged,main_treatment_reason_id,detail_treatment_reason_id,discharged_to,patient_number,hospital_id,patient_visit_timestamp';
			$orderby	= "order by date_attended desc,patient_visit_timestamp DESC";
			break;
		
		case 'treatment':
			$orderby	= "order by date_from desc,treatment_timestamp DESC";
            $extra_patient_where = " AND date_from >= '$sinceDate' ";
			break;
		
		case 'patient_service':
			$extra_patient_where = " AND date_given >= '$sinceDate' ";
			$orderby	= "order by date_given desc,patient_service_timestamp DESC";
			break;
			
		case 'patient_surgery':
			$extra_patient_where = " AND date_given >= '$sinceDate' ";
			$orderby	= "order by date_given desc,patient_surgery_timestamp DESC";
			break;
			
		case 'patient_appliance':
			$extra_patient_where = " AND date_given >= '$sinceDate' ";
			$orderby	= "order by date_given desc,patient_appliance_timestamp DESC";
			break;
			
		case 'patient_bill':
			$extra_patient_where = " AND date_paid >= '$sinceDate' ";
			$orderby	= "order by date_paid desc,patient_bill_timestamp desc";
			break;		
		}
		
		//$where       = 'where name_id	=	'.$fieldValue.' '.$orderby;
			//if()
		//$where       = 'where  '.$field_ID.'	=	'.$fieldValue.' '.$orderby;
		if($table=='visa_history')
			//$where	=	" where visa_history.deleted=0 AND  visa_id in (select visa_id from passport 
			$where	=	" where visa_history.deleted=0 AND passport_id in (select id from passport 
			where passport.deleted=0 AND passport.name_id='$fieldValue' ) $orderby";
		else
            $where       = " where  `$table`.`deleted` = '0' $extra_patient_where AND $field_ID	=	'$fieldValue' $orderby ";

		//
	/*	if($table=='patinet_visit'){
		//	$fieldNames = 'id,patient_id,type,affected,date_referred,referred_from_id,date_attended,date_discharged,main_treatment_reason_id,detail_treatment_reason_id,discharged_to,patient_number,hospital_id,patient_visit_timestamp'
			$query   	 = "select $fieldNames from `$table` $where ";
		}
		else */
		$query   	 = "select $fieldNames from `$table` $where ";	
// $dbi->test_file($action,$query);
		$result      = $dbi->query($query);
		
		$fNames      = explode(",",$fieldNames);
		$retVal = "<rootTag>";		
		$ok = true;
		
		if ($dbi->isNotAllowed($table,'view')) {
			$ok = false;
			$retVal	.=	'<subTag>disallowed</subTag>';
		}
		else if(mysql_num_rows($result)	==	0) {
			$retVal	.=	'<subTag></subTag>';
			$ok = false;
		}
			
	//	$rowsAffected	=	mysql_num_rows($result);
		if ($ok) {
			while($row = mysql_fetch_array($result)){			
               
				$retVal .=	"<subTag>";		
				
				for($i = 0;$i<sizeof($fNames);$i++){
				  
					$tag = $fNames[$i];
					if(($tag=='photo')||($tag=='scan')||($tag=='certificate_scan')){
						//nameID=$fieldValue
						$upload_dir = $dbi->getUserUploadDir($fieldValue);
						//$upload_dir .="../main/fileUploads/$currentUserID/$fieldValue/";		
						//$dbi->creatDirectoryRecursive($upload_dir);

                        if ($row[$i] != '') {
                            file_put_contents($upload_dir.$dbi->getThumbName($photoName),$row[$i]);	
                        }
                        else {
                            $dbi->makeThumbImage($photoName,$fieldValue);
                        }
                    }
					else if (($tag == 'link') || ($tag == 'photo_link') || ($tag == 'scan_link')) {
						$photoName=$dbi->getFileName($row[$i]);
                        $retVal .= $dbi->process_tag($tag,$row[$i],0,true,$photoName,$fieldValue);
                    }
                    else if ($tag == 'detail_treatment_reason_id') {
                        $retVal .= $dbi->process_tag($tag,$row[0]); //filter on visit_id
                    }
                    else if ($tag == 'training_needs') {
                        $retVal .= $dbi->process_tag('training_need_id',$row[0]); //filter on training_id
                    }
                    else {
                        $retVal .= $dbi->process_tag($tag,$row[$i]);
                    }
				}
				
				if($table=='patient_visit')
					$retVal	.=$patient_inf_info;
		//$retVal.="<PAL>No</PAL><PWD>Yes</PWD><care_after_cure>No</care_after_cure><footwear_needed>Yes</footwear_needed>";
				$retVal	.="</subTag>";						
			}
		}
		$retVal .= '</rootTag>';
		
		break;
	
	case 'Search Settings':
	
		$retVal = "<rootTag>";
		$retVal	.=	'<subTag></subTag>';
		$retVal	.=	'</rootTag>';
		
		break;

	case 'Search Tab':
	
		$tableName	= $_POST['tableName'];
		$fieldNames	= $_POST['fieldNames'];
		$whereID	= $_POST['whereID'];
		
		if($whereID == "") 
		{
			$whereClause = "where id > 0";
		}
		else
		{
			$whereClause = "where id = '".$whereID."'";
		}
		
		$query	=	sprintf("select $fieldNames from `$tableName` $whereClause AND `$tableName`.`deleted`='0' order by id");
// $dbi->test_file("$action ",$query);		
		$result      = $dbi->query($query);
		
		$fNames      = explode(",",$fieldNames);
		$retVal = "<rootTag>";		
		$ok = true;
		
		if ($dbi->isNotAllowed($tableName,'view')) {
			$ok = false;
			$retVal	.=	'<subTag>disallowed</subTag>';
		}
		else if(mysql_num_rows($result)	==	0) {
			$retVal	.=	'<subTag></subTag>';
			$ok = false;
		}
		
		if ($ok) {
		while($row = mysql_fetch_array($result)){			
               
			$retVal .=	"<subTag>";		
				
			for($i = 0;$i<sizeof($fNames);$i++){
				  
				$tag = $fNames[$i];
				$retVal .= $dbi->process_tag($tag,$row[$i]);                
			}				
		
			$retVal	.="</subTag>";				
		}	
		}
		
		$retVal .= '</rootTag>';
		
		break;
		
	case 'Search Admin':
	
		$tableName	= $_POST['tableName'];
		$fieldNames	= $_POST['fieldNames'];
		$whereName	= $_POST['whereName'];
		
		if($whereName == "") 
		{
			$whereClause = "where id > 0";
		}
		else
		{
			$whereClause = "where name = '".$whereName."'";
		}

		if ($tableName == "post")
		{
			$whereClause .= " AND `$tableName`.`type`='Internal'";
		}
		
		if ($tableName == "visapost")
		{
			$tableName = "post";
			$whereClause .= " AND `$tableName`.`type`='Official'";
		}
		
        $whereClause .= " AND `$tableName`.`deleted`='0'";
		$whereClause .= " AND length(replace(`name`,' ',''))>0"; //filter out blank names
        
        if ($tableName == "search_history")
        {
            $whereClause .= " and saved is null";
        }
				
		$query	=	sprintf("select $fieldNames from `$tableName` $whereClause order by name");
// $dbi->test_file("$action ",$query);		
		$result      = $dbi->query($query);
		
		$fNames      = explode(",",$fieldNames);
		$retVal = "<rootTag>";
		
		if(mysql_num_rows($result)	==	0)
			$retVal	.=	'<subTag></subTag>';
			
		while($row = mysql_fetch_array($result)){			
               
			$retVal .=	"<subTag>";		
				
			for($i = 0;$i<sizeof($fNames);$i++){				  
				$tag = $fNames[$i];
                $retVal .= $dbi->process_tag($tag,$row[$i],false,2);                    
			}				
		
			$retVal	.="</subTag>";				
		}

		$query2	=	sprintf("select name from `$tableName` $whereClause order by name");
		$result      = $dbi->query($query2);

		if(mysql_num_rows($result)	==	0)
			$retVal	.=	'<nameTag></nameTag>';
		
		while($row = mysql_fetch_array($result)){			
			$retVal .= '<nameTag>'.$row[0].'</nameTag>';								
		}	
		
		$retVal .= '</rootTag>';		
		break;			
	}

	print $retVal;	
	
/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('searchtest.txt', "a");
fwrite($fh, $action);
fwrite($fh,"\n");
fwrite($fh, $query);
fwrite($fh,"\n");
fwrite($fh, $query1);
fwrite($fh,"\n");
fwrite($fh, $query2);
fwrite($fh,"\n");
fwrite($fh, $query3);
fwrite($fh,"\n");
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/

?>