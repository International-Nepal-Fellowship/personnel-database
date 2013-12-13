<?php
	include_once (dirname(__FILE__).'/connect.php');
	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);
	
	$requester	=	$_POST['requester'];		
    $retVal="";

switch($requester){

case 'checkSearchHistoryName':
	
	$searchName=$_POST['searchName'];
	$userID=$_POST['userID'];
	$query="select search_history.name from search_history where search_history.name='$searchName' ";
	if ($userID!='0')
		$query.="AND search_history.user_id='$userID' ";
	//$dbi-> test_file("requestCheckDb \n",$query);	
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)					
				$retVal.='<searchName>duplicate</searchName>';				
		else
			$retVal.='<searchName>notDuplicate</searchName>';
		}	
	break;

case 'dashboardSearchUpdate':
	
	$search1	=	$_POST['search1'];
	$search2	=	$_POST['search2'];
    $search3	=	$_POST['search3'];
	$search4	=	$_POST['search4'];
	$userID 	=	$_POST['userID'];
	$query=sprintf("update `users` set `dashboard_search_1`='$search1', `dashboard_search_2`='$search2', `dashboard_search_3`='$search3', `dashboard_search_4`='$search4' where `id`='$userID'  ");
	$result = $dbi->query($query);
	
	$retVal='<rootTag><requester>'.$requester.'</requester>';
	if($result){
		$retVal.='<statusMsg>Successfully updated database</statusMsg>';
		$retVal.='<status>success</status>';
	}	
	else{
		$retVal.='<statusMsg>Cannot update database</statusMsg>';
		$retVal.='<status>fail</status>';
	}	
	$retVal.='</rootTag>';

	break;

case 'patient system':
	
	$retVal 	=	'<rootTag>';
	$patientID	=	$_POST['patientID'];
	$tableName	=	$_POST['tableName'];
	$getAllVisitData=	$_POST['getAllVisitData'];
	//$dbi->test_file('requestCheckDB @ patient System',"\n patientID=$patientID ,tableName=$tableName ,getVisitData=$getVisitData");
	
	//if($getVisitData=='recentOnly')	$limitRow='LIMIT 1';
	//else	$limitRow='';
    $limitRow='';
    $sinceDate=$dbi->get_last_visit_date($patientID,$getAllVisitData);
	$tableFields='';
	
		switch($tableName){
		
			case 'patient_bill':
				$query="SELECT id, patient_id, date_paid, amount, paid_by, patient_bill_timestamp FROM patient_bill WHERE patient_id =$patientID AND patient_bill.deleted='0' AND date_paid >= '$sinceDate' ORDER BY  `patient_bill`.`date_paid` DESC,`patient_bill`.`patient_bill_timestamp` DESC  $limitRow";
				$tableFields='id,patient_id,date_paid,amount,paid_by,patient_bill_timestamp';		
			break;
			
			case 'treatment':
				$query="SELECT id,patient_id,treatment_timestamp,treatment_category_id,date_from,date_until,treatment_case_id,treatment_regimen_id,treatment_result_id FROM treatment WHERE patient_id =$patientID AND treatment.deleted='0' AND date_from >= '$sinceDate'  ORDER BY  `treatment`.`date_from` DESC, `treatment`.`treatment_timestamp` DESC $limitRow";
				$tableFields='id,patient_id,treatment_timestamp,treatment_category_id,date_from,date_until,treatment_case_id,treatment_regimen_id,treatment_result_id';
			break;
            
			case 'patient_service':
				$tableFields='id,patient_id,patient_service_timestamp,service_type_id,date_given';
				$query="SELECT id,patient_id,patient_service_timestamp,service_type_id,date_given FROM patient_service WHERE patient_service.deleted='0' AND patient_id =$patientID AND date_given >= '$sinceDate' ORDER BY  `patient_service`.`date_given` DESC, `patient_service`.`patient_service_timestamp` DESC $limitRow";
			break;
            
			case 'patient_surgery':
				$tableFields='id,patient_id,patient_surgery_timestamp,surgery_type_id,date_given';
				$query="SELECT id,patient_id,patient_surgery_timestamp,surgery_type_id,date_given FROM patient_surgery WHERE patient_id =$patientID AND patient_surgery.deleted='0' AND date_given >= '$sinceDate' ORDER BY  `patient_surgery`.`date_given` DESC, `patient_surgery`.`patient_surgery_timestamp` DESC $limitRow";
			break;
            
			case 'patient_appliance':
				$tableFields='id,patient_id,patient_appliance_timestamp,appliance_type_id,date_given,requested_from_id';
				$query="SELECT id,patient_id,patient_appliance_timestamp,appliance_type_id,date_given,requested_from_id FROM patient_appliance WHERE patient_id =$patientID AND patient_appliance.deleted='0' AND date_given >= '$sinceDate' ORDER BY  `patient_appliance`.`date_given` DESC, `patient_appliance`.`patient_appliance_timestamp` DESC $limitRow";
			break;
            /*
			case 'health_staff':
				$tableFields='id,name_id,health_staff_timestamp,health_staff_type_id';
				$query="SELECT id,name_id,health_staff_timestamp,health_staff_type_id FROM health_staff WHERE name_id =$patientID AND health_staff.deleted='0' ORDER BY  `health_staff`.`health_staff_timestamp` DESC $limitRow";
			break;
			case '':
				$tableFields='';
				$query="SELECT  FROM $tableName WHERE patient_id =$patientID AND $tableName.deleted='0' AND date_given >= $sinceDate
				ORDER BY  `patient_surgery`.`date_given` DESC, `patient_surgery`.`patient_surgery_timestamp` DESC $limitRow";
			break;
            */
			
		}// END OF switch($tableName)
            
            //$dbi->test_file($query);
			$result = $dbi->query($query);
			$fields=split(',',$tableFields);
			if($result){
				if(mysql_num_rows($result)	==	0)
				$retVal	.=	'<subTag></subTag>';
		
				while($row = mysql_fetch_array($result)){			
	               
					$retVal .=	"<subTag>";		
			//$dbi->test_file("tableFields: $tableFields \n",$query);		
					for($i = 0;$i<sizeof($fields);$i++){				  
						$tag = $fields[$i];
                        
                        $retVal .= $dbi->process_tag($tag,$row[$i]);
                        /*
						if ($tag	==	"id")$tag	=	'__id';	
						elseif(($tag ==	"treatment_category_id")&&($tableName	==	"treatment"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<treatment_category>'.$dbi->get_table_item('treatment_category','name','id',$row[$i]).'</treatment_category>';
						}
						elseif(($tag ==	"treatment_case_id")&&($tableName	==	"treatment"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<treatment_case>'.$dbi->get_table_item('treatment_case','name','id',$row[$i]).'</treatment_case>';
						}
						elseif(($tag ==	"treatment_result_id")&&($tableName	==	"treatment"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<treatment_result>'.$dbi->get_table_item('treatment_result','name','id',$row[$i]).'</treatment_result>';
						}
						elseif(($tag ==	"treatment_regimen_id")&&($tableName	==	"treatment"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<treatment_regimen>'.$dbi->get_table_item('treatment_regimen','name','id',$row[$i]).'</treatment_regimen>';
						}
						elseif(($tag ==	"service_type_id")&&($tableName	==	"patient_service"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<service_type>'.$dbi->get_table_item('patient_service_type','name','id',$row[$i]).'</service_type>';
						}
						elseif(($tag ==	"surgery_type_id")&&($tableName	==	"patient_surgery"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<surgery_type>'.$dbi->get_table_item('patient_surgery_type','name','id',$row[$i]).'</surgery_type>';
						}
						elseif(($tag ==	"appliance_type_id")&&($tableName	==	"patient_appliance"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<appliance_type>'.$dbi->get_table_item('patient_appliance_type','name','id',$row[$i]).'</appliance_type>';
						}
						elseif(($tag ==	"requested_from_id")&&($tableName	==	"patient_appliance"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<requested_from>'.$dbi->get_table_item('requested_from','name','id',$row[$i]).'</requested_from>';
						}
						elseif(($tag ==	"health_staff_type_id")&&($tableName	==	"health_staff"))
						{	
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
							$retVal .= '<health_staff_type>'.$dbi->get_table_item('health_staff_type','name','id',$row[$i]).'</health_staff_type>';
						}	
						else
							$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
                                                */
					}
				
					$retVal .=	"</subTag>";	
				}	
			}
			else $retVal	.=	'<subTag></subTag>';
		
		$retVal .=	'</rootTag>';
	
	break;// END OF case 'patient system'
 
case 'checkUserName':
	
	$userName=$_POST['userName'];
	$query="select users.user_name from users where users.user_name='$userName' AND users.deleted='0' ";
	//$dbi-> test_file("requestCheckDb \n",$query);	
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)					
			$retVal.='<userName>duplicate</userName>';				
		else
			$retVal.='<userName>notDuplicate</userName>';
		}	
	break;

case 'checkUserEmail':
	
	$userName=$_POST['userName'];
    $userEmail=$_POST['userEmail'];
    $userID=$_POST['editedUserID'];
    
	$query="SELECT users.id FROM users WHERE users.email='$userEmail' AND users.id!=$userID AND users.user_name !='$userName'";
	//$dbi-> test_file("requestCheckDb \n",$query);	
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)					
			$retVal.='<message>Duplicate user email address: '.$userEmail.'</message>';				
		else 
            {
            if ($groupName == 'limited')
                {
                $query2="SELECT name.id, name.forenames, surname.surname FROM name LEFT JOIN name_email ON name_email.name_id=name.id LEFT JOIN email ON email.id=name_email.email_id LEFT JOIN surname ON surname.name_id=name.id WHERE email.email='$userEmail' AND surname.priority=1";
                //$dbi-> test_file("requestCheckDb \n",$query2);	
                $result = $dbi->query($query2);
                if (mysql_num_rows($result) == 0)					
                    {
                    $retVal.='<message>User email address: '.$userEmail.' not found</message>';	
                    }
                else
                    {
                    $retVal.='<message>match</message>';
                    $retVal.='<matches>';
                    while($row = mysql_fetch_array($result)){			
                        $retVal.='<match><userNameID>'.$row[0].'</userNameID><userFullname>'.$row[1].' '.$row[2].'</userFullname></match>';
                    }
					$retVal.='</matches>';
                    }  
                }
            else
                {
                    $retVal.='<message>ok</message>';	
                }
            }
            //$dbi-> test_file("requestCheckDb \n",$retVal);	
		}
	break;
    
case 'checkGroupName':

	$groupName=$_POST['groupName'];
	$query="select security_role.name from security_role where security_role.name='$groupName' AND security_role.deleted='0'";
	//$dbi-> test_file("requestCheckDb \n",$query);	
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)					
				$retVal.='<userName>duplicate</userName>';				
		else
			$retVal.='<userName>notDuplicate</userName>';
		}	
	break;

case 'checkPassword':

	$oldPassword=$_POST['oldPassword'];
	$userID=$_POST['userID'];

	$query="select user_name from users where users.id='$userID' and users.password='$oldPassword' and users.deleted='0'";
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)
			{
			while($row=mysql_fetch_array($result)){				
				$retVal.='<userName>'.$row['user_name'].'</userName>';	
				}
			}
		else
			$retVal.='<userName>None</userName>';
		}
	else
		$retVal.='<userName>None</userName>';	
//$dbi-> test_file("requestCheckDb \n $requester\n psw: $oldPassword \n uid:  $userID \n $query \n",$retVal);
	break;

case 'nextStaffNumber':

	$locationID = $dbi->getSiteLocation(false);
	$programmeID = $_POST['programmeID'];//$dbi->getSiteProgramme(false);
	$next = 1;
	$code = '';
	
	$query= "SELECT staff_number,programme.code FROM staff LEFT JOIN programme ON programme.id= staff.programme_id WHERE staff.programme_id=$programmeID ORDER BY staff.staff_number DESC LIMIT 1";
		
	$result = $dbi->query($query);
	if($result)
	{
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_array($result)){	
				$next = 1+$dbi->str2int($row['staff_number'],false);
				$code = $row['code'];
			}
		}
	}

	$retVal.='<nextNum>'.$code.sprintf("%04d",$next).'</nextNum>';
	
	break;
	
case 'checkHospitalCost':

	$nameID=$_POST['nameid'];
	$ID=$_POST['id'];
	$endDate=$_POST['end'];
	$total=floatval($_POST['cost']);
	
	$startDate=$dbi->getFiscalYearStart($endDate);
	$limit=0.0;
	$warn='';
	
	//work out running total, apart from the current one
	$query="SELECT SUM(cost) as total, name_id FROM `hospitalisation` WHERE name_id=$nameID AND id != $ID AND date_until > '$startDate' GROUP BY name_id";
	
	$result = $dbi->query($query);
	if($result)
	{
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_array($result)){	
				$total = $total+floatval($row['total']);
			}
		}
	}
	
	$query2="SELECT hospitalisation_limit FROM site LIMIT 1";
	$result = $dbi->query($query2);
	if($result)
	{
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_array($result)){	
				$limit = floatval($row['hospitalisation_limit']);
			}
		}
	}
	
	if ($total > $limit) {
		$warn="Yearly limit of ".sprintf("%01.2f",$limit)." exceeded - total so far is ".sprintf("%01.2f",$total);
	}
	$retVal.='<warn>'.$warn.'</warn><limit>'.sprintf("%01.2f",$limit).'</limit><total>'.sprintf("%01.2f",$total).'</total>';
	
	break;
	
case 'existing_names':

 	$surname	=	$_POST['surName'];
	$forenames	=	$_POST['forename'];
    $ID	=	$_POST['id'];
    $staffID	=	$_POST['staffid'];
 
    $fieldNames = 'name.id,name.forenames,surname.surname,name.dob';
	$filterDeleted = "`name`.`deleted`='0' AND `surname`.`deleted`='0'";

    $site	=	$dbi->getSiteName();
    
    /*
	if($site=='Patient'){
		$joinTable = 'LEFT JOIN patient_inf ON name.id = patient_inf.patient_id';
        $filterDeleted .= " AND ((`patient_inf`.`deleted` is NULL) OR (`patient_inf`.`deleted` = '0'))";
        $selectName     =   "SELECT `patient_inf`.`patient_id` FROM `patient_inf` WHERE `patient_inf`.`deleted` = '0'";
        $fieldNames .= ",patient_inf.patient_id";
	}
	else{
		$joinTable = 'LEFT JOIN inf_staff ON name.id = inf_staff.name_id';
        $filterDeleted .= " AND ((`inf_staff`.`deleted` is NULL) OR (`inf_staff`.`deleted` = '0'))";
        $selectName     =   "SELECT `inf_staff`.`name_id` FROM `inf_staff` WHERE `inf_staff`.`deleted` = '0' AND `inf_staff`.`name_id` != $ID";
        $fieldNames .= ",inf_staff.name_id";
	}
       	*/
        
	$tables = "name LEFT JOIN surname ON name.id = surname.name_id $joinTable";

	//$where = "WHERE `name`.`id` IN ($selectName UNION SELECT `relation`.`relation_id`  AS `name_id` FROM `relation` WHERE `relation`.`deleted` = '0' AND `relation`.`name_id` IN ($selectName)) AND `name`.`deleted` = '0'";
    //$where .= " AND name.forenames LIKE '$forenames%' AND surname.surname LIKE '$surname%'";    
    //$where .= " AND `name`.`id` NOT IN (SELECT `relation`.`relation_id` FROM `relation` WHERE `relation`.`name_id` = '$staffID' AND `relation`.`deleted` = 0)";
	//$where .= " AND surname.priority=1 AND `name`.`id` != $staffID AND $filterDeleted ORDER BY surname, forenames";
    $where .= " WHERE name.forenames LIKE '$forenames%' AND surname.surname LIKE '$surname%'";   
    $where .= " AND surname.priority=1 AND $filterDeleted ORDER BY surname, forenames";

	$query       = "SELECT $fieldNames FROM $tables $where ";  

	$result      = $dbi->query($query);
            
	$retVal = "<users>";    
    if(mysql_num_rows($result)==0)
		$retVal .= '<duplicateUser>no</duplicateUser>';
	else
		$retVal .= '<duplicateUser>yes</duplicateUser>';
	
	$fNames      = explode(",",$fieldNames);
		
	if(mysql_num_rows($result)==0)
		$retVal.='<user></user>';
			
	$rowsAffected	=	mysql_num_rows($result);
		
	while($row = mysql_fetch_array($result)){
                     
		$retVal .= '<user>';			
		for($i = 0;$i<sizeof($fNames);$i++){
		
			$fName = $fNames[$i];
			$tag   = substr($fName,strrpos($fName,".")+1);
            $retVal .= $dbi->process_tag($tag,$row[$i],true);
		}			
		$retVal .= '</user>';						
	}
    
	$retVal .= '</users>';

    //$dbi-> test_file("requestCheckDb \n $requester\n query: $query \n",$retVal);    
    
    break;
	
case 'general':
	
	$surname	=	$_POST['surName'];
	$forenames	=	$_POST['forename'];

	$query="select name.id,surname.surname,name.forenames,name.dob from  name
						LEFT JOIN surname ON name.id = surname.name_id 
						where name.forenames like '$forenames%' and surname.surname like '$surname%' 
						and surname.priority=1 and name.deleted='0' and surname.deleted='0' ORDER BY  `name`.`forenames` ASC ";
						
	/*					
	$query="select surname.surname,name.forenames,name.title,name.known_as,name.dob,name.gender from  name
						LEFT JOIN surname ON name.id = surname.name_id 
						where name.forenames='bidur' and surname.surname='devkota'";*/
	$queryResult = $dbi->query($query);
	$duplicateNameIDs='0,';
	
	$retVal      = "<users>";
	if(mysql_num_rows($queryResult)==0)
			$retVal.='<duplicateUser>no</duplicateUser>';
	else
			$retVal.='<duplicateUser>yes</duplicateUser>';
	$arrDuplicateIDs=array();		
	while($row = mysql_fetch_array($queryResult)){
        $retVal .= '<user>';
		$retVal .= '<zzzid>'.$row['id'].'</zzzid>';		
		$retVal .= '<forenames>'.$row['forenames'].'</forenames>';
		$retVal .= '<surname>'.$row['surname'].'</surname>';		
		$retVal .= '</user>';	
		array_push($arrDuplicateIDs,$row['id']);
		$duplicateNameIDs.=$row['id'].',';
	}
	//check to see if the duplicate id is of relative or staff
	
	//$arrInfStaffIDs=$dbi->get_staff_id('array');
	$selectedStaffIDs=array();
	$arrRelativeStaffIDs=$dbi->get_relation_staff_id('array');
	
	//check if relatives and if relatives get their staff's id in $selectedStaffIDs and build query 
	$query=' select name.id,surname.surname,name.forenames from  name
						LEFT JOIN surname ON name.id = surname.name_id 
						where ';
	$staffIDCount=0;					
	foreach($arrDuplicateIDs as $eachID){	
		if (array_key_exists($eachID, $arrRelativeStaffIDs)){
				array_push($selectedStaffIDs,$arrRelativeStaffIDs[$eachID]);
				if($staffIDCount!=0)
					$query.=' OR ';
					
				$query.=' name.id='.$arrRelativeStaffIDs[$eachID];
				$staffIDCount++;
		}
	}	
	$query.=' and surname.priority=1 AND name.deleted=0 AND AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = 0) ) ORDER BY  `name`.`forenames` ASC ';
	$queryResult = $dbi->query($query);
	while($row = mysql_fetch_array($queryResult)){
        $retVal .= '<user>';
		$retVal .= '<zzzid>'.$row['id'].'</zzzid>';		
		$retVal .= '<forenames>'.$row['forenames'].'</forenames>';
		$retVal .= '<surname>'.$row['surname'].'</surname>';		
		$retVal .= '</user>';			
	}		
	
	$duplicateNameIDs = substr($duplicateNameIDs, 0, -1);//removes the last comma (,)char of $duplicateNameIDs
	$retVal	.= '<duplicateNameIDs>'.$duplicateNameIDs.'</duplicateNameIDs>';
	$retVal .='<count>'.count($arrDuplicateIDs).'</count>';
	$retVal .= "</users>";	
	break;	
}

	$dbi->disconnect();		
	print $retVal;

//$dbi-> test_file("requestCheckDb \n $query \n",$retVal);	
?>
