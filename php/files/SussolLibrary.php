<?php
$inc_string = $_SERVER['DOCUMENT_ROOT'];
$inc_string .= str_replace('/files/','/conf/',dirname($_SERVER['PHP_SELF']).'/');
include_once ($inc_string.'DBIConnect.php');
include_once ('DateConvert.php');


class SussolLibrary extends DBIConnect
{	
	
	private $isNotAllowedTable = array(); 
	private $process_tagTable = array();
	
	
	
/* input : table_name , output : all content of table , target_table: table_name*/	
	function getall($table)
	{ 		
		$queryResult = $this->query("SELECT * FROM $table where $table.deleted='0'");
		return  $queryResult;		
	}	

	function get_fullname($name_id)
	{ 	
		$queryResult = $this->query("SELECT name.id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname 
		FROM name,surname where name.id=surname.name_id  and name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') ) AND name.id=$name_id AND surname.priority=1");		
		$row=mysql_fetch_assoc($queryResult);
		return $row['fullname'];
	}
	
/*  output : associative array of full name indexed by id., target_table: name*/
	function get_name_id()
	{ 	
		$id_name=array();
		$queryResult = $this->query("SELECT name.id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname 
		FROM name,surname where name.id=surname.name_id  and name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') ) order by `fullname`");				
		while($row=mysql_fetch_assoc($queryResult))
		{
		//$id_name[$row->id]=$row->forenames.' '.$row->surname;  
			$id_name[$row['id']]=$row['fullname'];			
		}
		
		return $id_name;
	}
	
/*  output : associative array of full name indexed by id., target_table: name*/
//extracts names from name table that are not present in given table $table
//used by passport and visa 
	function get_new_name_id($table)
	{ 	
		$id_name=array();	
		
//$query = $CI->db->query("SELECT name.id,name.forenames,surname.surname FROM name,surname where name.id=surname.name_id AND name.id NOT IN(select name_id from passport)");
		$queryResult= $this->query("SELECT name.id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname  FROM name,surname 
		where name.id=surname.name_id   and name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') ) AND  name.id NOT IN(select name_id from $table) ORDER BY `fullname`");
	
		while($row=mysql_fetch_assoc($queryResult))
		{
			$id_name[$row['id']]=$row['fullname']; 
		}
		
		return $id_name;
	}
			
/*  output : associative array of full name indexed by id., target_table: name*/
//extracts names from name table that are not present in given table $table
//used by post
	function get_new_name_id_4_post()
	{ 	
		$id_name=array();		
		
		$queryResult = $this->query("SELECT inf_staff.name_id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname  FROM inf_staff,name,surname where name.id=surname.name_id 
		 and name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') )and inf_staff.deleted='0'
		AND inf_staff.name_id=name.id AND name.id NOT IN(select name_id from name_post) ORDER BY `fullname`");
	
			
		while($row=mysql_fetch_assoc($queryResult))
		{
			$id_name[$row['name_id']]=$row['fullname'];  
		}
		
		return $id_name;
	}
	
		
/*  output : associative array of full name indexed by id., target_table: inf_staff*/
//used by detail 
	function get_new_staff_name_id()
	{ 	
		$id_name=array();
		
		$queryResult = $this->query("SELECT inf_staff.name_id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname  FROM inf_staff,name,surname 
		where inf_staff.name_id=surname.name_id and name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') ) and inf_staff.deleted='0'
		and inf_staff.name_id=name.id and inf_staff.staff_number=0 order by `fullname`");		
		
		while($row=mysql_fetch_array($queryResult))
		{$id_name[$row['name_id']]=$row['fullname'];  }
		
		return $id_name;
	}
	
	
	/*  output : associative array of full name indexed by id., target_table: name*/
//used by relatives
	function get_staff_name_id()
	{ 	
		$id_name=array();
		
		$queryResult = $this->query("SELECT inf_staff.name_id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname  FROM inf_staff,name,surname where 
		inf_staff.name_id=surname.name_id and name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') ) and inf_staff.deleted='0'
		and inf_staff.name_id=name.id and inf_staff.leaving_date = '0000-00-00' order by `fullname`");		
		
		while($row=mysql_fetch_assoc($queryResult))
		{$id_name[$row['name_id']]=$row['fullname'];  }
		
		return $id_name;
	}

	//returns inf_staff ids as comma separated string  as default
	function get_staff_id($returnAs='string')//if $returnAs=='array' array is returned
	{ 	
		//getSiteName()
		$staff_ids='';
		$arrStaffIds=array();
		if($this->getSiteName()=='Patient'){
			$table='patient_inf';
			$field='patient_id';
		}
		else{
			$table='inf_staff';
			$field='name_id';
		}
		$queryResult = $this->query("SELECT $table.$field FROM $table where $table.deleted=0");		
		if (mysql_num_rows($queryResult) > 0)
		{
			while($row=mysql_fetch_assoc($queryResult)){
				$staff_ids.=$row[$field].',';  
				array_push($arrStaffIds,$row[$field]);
			}			
			$staff_ids = substr($staff_ids, 0, -1);//removes the last comma (,)char of $staff_ids
		}
		else
		{
			$staff_ids='0';
			array_push($arrStaffIds,0);
		}
		if($returnAs=='array')
			return $arrStaffIds;
		else
		return $staff_ids;
	}
	
/*check the  $table to get $vale_id  if the $value not present then enter it in the table
 input :$table,$check_field,$value ; output :$value_id,*/
	function set_value($table,$check_field,$value)
	{ 
		$value_id=0;
		$query="SELECT  `id` FROM `$table` where `$check_field`='$value' and `$table`.`deleted`='0' ";
		$queryResult = $this->query($query);

		if (mysql_num_rows($queryResult) > 0)
		{
			$row = mysql_fetch_assoc($queryResult);
			
			if($row)
				return $row['id'];		
			else
				echo mysql_error();
		}
		else{			
			$query="INSERT INTO `$table` (`id`,`$check_field`)VALUES ('','$value')";
			$queryResult=$this->query($query);		
			
			if($queryResult)
				$value_id = mysql_insert_id ();	
			else
				echo mysql_error();			
		}

		return $value_id;
	}	
	
/* input : id , output : family_id, target_table: name*/	
 function get_family_id($name_id,$all=false)
 {
    $query = sprintf("SELECT `family_id` FROM `name` where `id`= '".$name_id."' and `name`.`deleted`='0'");
    $queryResult = $this->query($query);
    
    $family = '';
    
    $row=mysql_fetch_assoc($queryResult);
	if($row)
		$family = $row['family_id']; 
	else
		return null;
    
    if ($all) {
        $query=sprintf("SELECT DISTINCT `family_id` FROM `name` LEFT JOIN relation ON relation.relation_id = name.id WHERE `relation`.`name_id`= '".$name_id."' and `relation`.`deleted`='0' AND `family_id` != $family");
        $queryResult = $this->query($query);
        
        if(mysql_num_rows($queryResult)!=0)		
		{		
			while($row=mysql_fetch_array($queryResult)){
				$family .= ",". $row['family_id'];
			}
		}
    }

//$fh = fopen('test.txt', "a"); 
//fwrite($fh, $query."\n".$family."\n"); 
//fclose($fh); 
    
    return $family;
 }
 
 #parent_model.php,personnel_model.php
  function get_default_kin_id()
	{		
	//	$query = $this->db->query("SELECT max(`id`) a FROM `name` ");
		$queryResult = $this->query("SELECT id FROM name where `name`.`deleted`='0' ORDER BY id DESC LIMIT 1");
		
		if(!$queryResult)
			return mysql_error();
		
		if(mysql_num_rows($queryResult)!=0)		
		{
		
			while($row=mysql_fetch_array($queryResult)){
				return $row['id']+1;
			}
			//foreach($ids as $id)	return ($id->id + 1);
		}
		else 
			return $id=1;		
	}
	
 function get_email_id($name_id)
 {
	$queryResult= $this->query("SELECT email_id FROM name where name.deleted='0' AND id= ".$name_id);
	
	if($queryResult){
			$row=mysql_fetch_assoc($queryResult);
			return $row['email_id'];		
	}
	else{	
		echo mysql_error();		
	}	
 }
 
 /* input : id , output :visa_id, target_table: name*/	
 /* Eg. : query= select visa_id fron name where id=1*/
 /*
 function get_id($table,$op_column,$test_column,$test_value)
 {	
	$query="SELECT ".$op_column." FROM ".$table." where ".$test_column."='".$test_value."'";
	$queryResult = $this->query($query);
	$row = mysql_fetch_assoc($queryResult);
	return $row[$op_column]; 
 }
 */
 
 function get_table_item($table,$op_column,$test_column,$test_value)
 {	
	$query="SELECT ".$op_column." FROM ".$table." where $table.deleted='0' AND ".$test_column."='".$test_value."'";
	
/*
$fh = fopen('test.txt', "a"); 
fwrite($fh, $query); 
fclose($fh); 
*/
	
	$queryResult = $this->query($query);
	$row = mysql_fetch_assoc($queryResult);
	
	return $row[$op_column]; 
 }

 
 /* input : id , output :visa_id, target_table: name*/	
 /* Eg. : query= select visa_id fron name where id=1*/
 function get_visa_id($name_id)
 {
	$queryResult = $this->query("SELECT visa_id FROM name where name.deleted='0' AND id=".$name_id);
	$row = mysql_fetch_assoc($queryResult);
	return $row['visa_id']; 
 }

 function get_last_visit_date($patient_id,$showAll)
 {
    $query = "SELECT date_attended FROM patient_visit WHERE deleted='0' AND patient_id=".$patient_id." ORDER BY date_attended DESC LIMIT 1";
    //$this->test_file($query."\t".$showAll);
    $queryResult = $this->query($query);
    $retDate = '0000-00-00';
    
    if (($showAll!='true') && (mysql_num_rows($queryResult) > 0))
	{
        $row=mysql_fetch_assoc($queryResult);
        $retDate = $row['date_attended']; 
        //$this->test_file($retDate);
    }
    
	return $retDate;
 }

 /* input : five arguments , output : target ID if successful else zero   target_table: table inputted*/
#used in address_model.php 
 function check_null_row($table,$op_colm,$null_colm,$test_colm,$test_colm_value)
	{ 	
		$queryResult =$this->query(
		"SELECT ".$op_colm." FROM ".$table." WHERE  $table.deleted='0' AND  ".$test_colm."=".$test_colm_value." AND 	".$null_colm." IS NULL ");
		
		if (mysql_num_rows($queryResult) > 0)
		{
			$row = mysql_fetch_assoc($queryResult);
		//	echo"row_id".$row[$op_colm];
			return $row['$op_colm'];		
		}
		else  return 0;
	}
	
  /* input : five arguments , output : target ID if successful else zero   target_table: table inputted*/
#used in phone_model.php 
 function check_zeroed_row($table,$op_colm,$zero_colm,$test_colm,$test_colm_value)
	{ 		
		$queryResult =$this->query(
		"SELECT ".$op_colm." FROM ".$table." WHERE  $table.deleted='0' AND ".$test_colm."=".$test_colm_value." AND 	".$zero_colm." = 0 ");
		# e.g.: $query = $CI->db->query("SELECT `id` FROM `phone` where family_id=28 AND `phone`=0");
		if($queryResult){
			if (mysql_num_rows($queryResult) > 0){
				$row = mysql_fetch_assoc($queryResult);
				return $row['$op_colm'];		
			}
			else  return 0;
		}else{
		 echo mysql_error();
		}
		
	}
  /* input : five arguments , output : target ID if successful else zero   target_table: table inputted*/
#used in  email_model
	function check_empty_row($table,$op_colm,$zero_colm,$test_colm,$test_colm_value)
	{ 
	
		//$query = $CI->db->query("SELECT `id` FROM `country` where `name`= '".$country_name."'");
		$queryResult =$this->query(
		"SELECT ".$op_colm." FROM ".$table." WHERE  $table.deleted='0' AND ".$test_colm."=".$test_colm_value." AND 	".$zero_colm." = '' ");
		# e.g.: $query = $CI->db->query("SELECT `id` FROM `address` where family_id=28 AND `address`IS NULL");
		if (mysql_num_rows() > 0)
		{
			$row = mysql_fetch_assoc($queryResult);
			return $row['$op_colm'];		
		}
		else  return 0;
	}	
/*Added new----june08,2008*/
	
	function get_type($tableName,$fieldOne,$fieldTwo)
	{ /* changed july 01, 2008*/
	
		$types=array();
		$query="Select ".$fieldOne.",".$fieldTwo." from ".$tableName." WHERE  $tableName.deleted='0' order by ".$fieldTwo;
		
		$result=$this->query($query);
		
		if($result)
		{
			while($row=mysql_fetch_assoc($result)){
					
				$types[$row[$fieldOne]]=$row[$fieldTwo];
				//$types[$row['id']]=$row['name'];
				//echo $row[$fieldTwo];
			}
		}
			
		return $types;		
	}	
	
 // Quote variable to make safe 
	function quote_smart($value){
	
		// Stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		// Quote if not integer
 		if (!is_numeric($value)) {
			$value = "'" . mysql_real_escape_string($value) . "'";
		}			
	}

	function isLoggedIn($userID) {
		
		$query="Select user_name FROM users WHERE users.id=".$userID;
		$this->test_file($query);
		$result=$this->query($query);	
		if ($result)
		{
			$row = mysql_fetch_assoc($result);	
			$this->test_file($row["user_name"].':'.$_SESSION["name"]);
			return ($row["user_name"]==$_SESSION["name"]);		
		}	

		return false;
	}
		
	function getUserPassword($userName){
		
		//$query="Select password from users where user_name=".$userName;		
		$query="Select users.id as user_id,users.timestamp,users.password_changed,users.search_id,users.role_id,users.password,
		security_role.name as security_role,users.name as forename, users.lastname as surname, users.email, 
		users.incomplete_warning from users 
		LEFT JOIN security_role ON users.role_id = security_role.id
		where users.user_name='".$userName."' AND users.deleted='0' and security_role.deleted='0'";		
		$result=$this->query($query);	
		if ($result)
		{
			$row = mysql_fetch_assoc($result);	
			return $row;		
		}	

		return "";
	}
	
	//eg: select id , name from hospital where country_id=1
	//returns an associative array like this: $types[$field1]=$fiels2
	function get_values($table,$field1,$fieldNames,$check_field,$value,$replace)
	{ 		
		$types=array();
		$fNames      = explode(",",$fieldNames);
        $value = str_replace(",","','",$value);
		$query="SELECT  `$field1`, $fieldNames FROM `$table` where `$table`.`deleted`='0' AND `$check_field` IN ('$value') ORDER BY `$fNames[0]`";
		$result=$this->query($query);
		
		if ($replace) {
			$order   = array("\r\n", "\n", "\r");
		}
		else {
			$order	= ""; // dummy
		}

//$this->test_file("get_values: ".$query);
		
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_array($result))
			{	
				$res=str_replace($order," ",$row[1]); // first fieldName
				for($i = 1;$i<sizeof($fNames);$i++){
					$res.=", ".str_replace($order," ",$row[$i+1]);
				}
				$types[$row[$field1]]=$res;	
//$this->test_file("get_values: ".$row[$field1]." = ".$res);                
			}
		}

		return $types;				
	}

function get_detail_reasons($main_reason)
	{ 		
		$types=array();
		$query1="SELECT treatment_category_id FROM treatment_reason WHERE id = $main_reason";
		$result=$this->query($query1);

//$this->test_file("get_values: ".$query1);
        
        if (mysql_num_rows($result) == 1)
		{
			$row=mysql_fetch_array($result);
            $category = $row['treatment_category_id'];
            $query="SELECT id,name FROM treatment_reason WHERE treatment_category_id = $category AND main='No' ORDER BY name";
            $result=$this->query($query);
            
//$this->test_file("get_values: ".$query);
		
            if (mysql_num_rows($result) > 0)
            {
                while($row=mysql_fetch_array($result))
                {	
                    $res=$row['name'];
                    $types[$row['id']]=$res;	
//$this->test_file("get_values: ".$row['id']." = ".$res);                
                }
            }
        }

		return $types;				
	}
    
	//returns a string of relative_id=inf_Staff_id separated by commas
	function get_relation_staff_id($returnAs='string')//if $returnAs=='array'  associative array is returned
	{
		$arrRelativeInfStaff=array();
		$relative_staff='';
		$query="SELECT `relation`.`relation_id` , `relation`.`name_id`  FROM `relation` WHERE `relation`.`deleted`='0'";
		$result=$this->query($query);
	
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_assoc($result)){
				$relationID=$row['relation_id'];
				$relative_staff.=$relationID.'='.$row['name_id'].',';  
				$arrRelativeInfStaff[$relationID]=$row['name_id'];
			}		
			$relative_staff = substr($relative_staff, 0, -1);//removes the last comma (,)char of $relative_staff
		}
		else
		{
			$relative_staff='0=0';
			$arrRelativeInfStaff[0]=0;
		}
		
		if($returnAs=='array')
			return $arrRelativeInfStaff;
		else
		return $relative_staff;
	
	} 
	
	 /* input : nameID , output : an associative array of relatives name and relationship of the person indeded by their id*/
	 //used by requestLoadRelatives.php
	function get_relatives($nameID)
	{					
//query to extract relative's full name and their respective id
		$id_name=array();		
		$query="SELECT relation.relation_id AS name_id, relation.relationship AS rel, name.forenames, surname.surname from relation left join name on name.id=relation.relation_id left join surname on surname.name_id=name.id where surname.priority=1 and name.deleted=0 and relation.deleted=0 and surname.deleted=0 and relation.name_id=$nameID";
		
//		echo "<br>";
//		print_r($query);
//		echo "</pre>";
//$this->test_file("get_relatives: ".$query); 
		$result=$this->query($query);
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_assoc($result))
			{
			$id_name[$row['name_id']]=$row['forenames'].' '.$row['surname'].'-'.$row['rel'];					//echo $row['name_id']."".$row['forenames'].' '.$row['surname'].''.$row['rel']."\n";
			}			
		}
		
		return $id_name; 		
	}
		
	//eg: select id , email from email where family_id=1 & type= family;
	//returns an associative array like this: $types[$field1]=$fiels2
	function get_values_2($table,$field1,$fieldNames,$check_field_1,$value_1,$check_field_2,$value_2)
	{ 		
		$types=array();
		$fNames      = explode(",",$fieldNames);
		$query="SELECT  `$field1`,$fieldNames FROM `$table` where `$table`.`deleted`='0' AND `$check_field_1`='$value_1' && `$check_field_2`='$value_2' ORDER BY `$fNames[0]`";
//$this->test_file("get_values_2: ".$query); 
		$result=$this->query($query);
		$order   = array("\r\n", "\n", "\r");		
	
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_array($result))
			{	
				$res=str_replace($order," ",$row[1]); // first fieldName
				for($i = 1;$i<sizeof($fNames);$i++){
					$res.=", ".str_replace($order," ",$row[$i+1]);
				}
				$types[$row[$field1]]=$res;	
//$this->test_file("get_values_2: ".$row[$field1]." = ".$res);                 
			}
		}

		return $types; 				
	}	

	//This is a function that will return true if the Variable presented to it is an integer.
	function isInteger($n) {
	
		if (preg_match("/[^0-^9]+/",$n) > 0) { return false; } return true;
	} 
	
	function checkTimestamp($table,$timestamp_field,$timestamp,$check_field,$check_value,$extraCheck='')
	{
        if ($timestamp == 'null') return ''; //record didn't exist (surname/nationality)
        if ($timestamp == '0000-00-00 00:00:00') return ''; //record hasn't been modified
        
        $query="SELECT `$table`.`$check_field` FROM `$table` WHERE `$table`.`deleted`='0' AND `$table`.`$timestamp_field` ='$timestamp' AND `$table`.`$check_field`='$check_value' $extraCheck";
    
		$result=$this->query($query);
		if (mysql_num_rows($result) < 1)
		{
			$this->logErrors("Someone else has modified the record. Please reload it and try again.\n ".$query);		
			print"<errors><error>Someone else has modified the record. Please reload it and try again.</error>";		
			print"<status>error</status></errors>";
//$this->test_file("timestamp exit");
			exit;
		}

		return '';//NO Timestamp problem if $retVal is blank ;		
		
	}
	
	function printForeignKeyError($tables,$returnValue=false)
	{
		//$tables = substr($tables, 0, -1);//removes the last char of $tables
        	/*	$arrDisplayName = array(
		'name' => 'personal', 'name_address' => 'address',
		'name_email' => 'email', 'name_phone' => 'phone',
		'inf_staff'=>'staff','review'=>'annual_review',
		'relation'=>'relationship','name_post' => 'post',
		);
		if (array_key_exists($tables, $arrDisplayName))
			$table=$arrDisplayName[$tables];
		*/
        $tables = substr_replace($tables,"",-1);//remove the last character which is  a comma "," 
		$tables = str_replace('name_post', 'post',$tables);
		$tables = str_replace('name_phone', 'phone',$tables);
		$tables = str_replace('name_email', 'email',$tables);
		$tables = str_replace('name_address', 'address',$tables);
		$tables = str_replace('relation', 'relationship',$tables);	
		$tables = str_replace('inf_staff', 'staff',$tables);	
		$tables = str_replace('review', 'annual_review',$tables);			
		$tables = str_replace('name', 'personal',$tables);//always call this line at last . At least AFTER name_post,name_address, name_phone , name_email
	
		$this->logErrors("Cannot delete - value used in ".$tables);	
		
        if ($returnValue) {
            return "Cannot delete - value used in ".$tables;
        }
        else {
            print "<errors><error>Cannot delete - value used in ".$tables." </error>";
            print "<status>fail</status></errors>";		
        }
		
	}	
	
	/*
	//this function builds the where clause, by refering other tables which are not explicity given by the clients query
  	//  $strWhereValuesExtra  gives values of the fields, 
	//$strWhereOperationsExtra  is one of the '=','!=','>','<','>=','<='
	//$strWhereFieldsExtra=''are used to get the ids of some other table by compairing $whereValues 
	//$subQuery (string)  is ready made query to be appended in the return type
	$strWhereFieldsExtra can be of two type: 1. not delomated by '/#/'       2. delimated by '/#/' ( to provide more information for building query) which are handled accordingly
	*/
	function buildWhereClauseExtra($strWhereAndOrOperationsExtra,$strWhereFieldsExtra='',$strWhereValuesExtra='',$strWhereOperationsExtra='',$subQuery='')	
	{

		$whereClause	=	array();		
		$tableField		= array();
		if($strWhereFieldsExtra!='')		
		{	
		
			$arrExtraFields	=	explode(',',$strWhereFieldsExtra);	
			$arrWhereOperations	=	explode(',',$strWhereOperationsExtra);
			$arrWhereValues	=	explode(',',$strWhereValuesExtra);	
			$arrAndOr	=	explode(',',$strWhereAndOrOperationsExtra);	
		
			for( $i = 0 ; $i < count($arrExtraFields) ; $i++ )
			{
				
				if(strpos ( $arrExtraFields[$i],'/#/'))
				{
					$tableField= explode('/#/',$arrExtraFields[$i]);
				}
				else
					$tableField= explode('.',$arrExtraFields[$i]);
				
				switch($arrWhereOperations[$i])	
				{						
					case '!=':
						
						if(!strchr($arrWhereValues[$i],'%'))//if '%' wild card character is not present 
							$arrWhereValues[$i]	=	" $arrWhereOperations[$i] '$arrWhereValues[$i]' ";
						else		
							$arrWhereValues[$i]=" NOT LIKE '$arrWhereValues[$i]' ";
							
							break;
							
					case '=':
						if(!strchr($arrWhereValues[$i],'%'))//if '%' wild card character is not present 
							$arrWhereValues[$i]	=	" $arrWhereOperations[$i] '$arrWhereValues[$i]' ";
						else		
							$arrWhereValues[$i]=" LIKE '$arrWhereValues[$i]' ";
						
						break;
					
					case '>':					
					case '<':
					case '>=':
					case '<=':
		
					default :
						$arrWhereValues[$i]	=	str_replace ('%' ,'',$arrWhereValues[$i]);//ignore the '%' wild card characteer
						$arrWhereValues[$i]	=	" $arrWhereOperations[$i] '$arrWhereValues[$i]' ";								
	
						break;				
				}

				if(strpos ( $arrExtraFields[$i],'/#/'))
				{
					switch($tableField[0])
					{
					case 'secondment.link_person_id':
					case 'orientation.pkr_link_person_id';
					case 'orientation.ktm_link_person_id':
					case 'orientation.work_link_person_id':
					case 'orientation.housing_link_person_id':
					case 'orientation.school_children_link_person_id':
					case 'documentation.link_person_id':
					case 'documentation.medical_accepted_id':
					case 'home_assignment.interview_by':
					case 'review.reviewed_by_id':
					case 'hospitalisation.relative_id':
						array_push( $whereClause,"  $arrAndOr[$i] ".$tableField[0]."  IN (SELECT name_id FROM (
SELECT name.id AS name_id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname 
FROM name,surname where name.id=surname.name_id  and name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') )
) as t1 WHERE fullname $arrWhereValues[$i]) ");	
						break;
					
					default:
						array_push( $whereClause,"  $arrAndOr[$i] ".$tableField[0]."  IN (select ".$tableField[1]."
								from ".$tableField[2]." where ".$tableField[2].".deleted='0' AND
								".$tableField[3]." $arrWhereValues[$i]) ");	
						break;
					}
				}
				else
					array_push($whereClause," $arrAndOr[$i] ".$tableField[0].".".$tableField[1]."_id  IN (select ".$tableField[1].".id
								from ".$tableField[1]." where ".$tableField[1].".deleted='0' AND
								".$tableField[1].".name $arrWhereValues[$i]) ");
			}//end for
		
		}// end if	
		
		/*
		if($extraQuery!='')
		{
			$whereClause	.=$extraQuery;
		}
			*/
		return($whereClause);		
	}
	
	// returns an array of strings.
	//$arrWhereFields contains all the fields for where clause
	//$arrWhereExtraFields contains only the extra fields part of where clause
	//This function compares $arrWhereExtraFields and $arrWhereFields and separates simple cases(i.e.  NO needs to refer to third table in where clause) of where clause and Extra Field cases(i.e. needs to refer to third table in where clause) of where clause
	function getFieldsInfo($arrWhereExtraFields,$arrWhereOperations,$arrWhereFields,$arrWhereFieldsCopy,$arrWhereValues,$arrWhereAndOrOperation)
	{	
		$arrExtraFields=array();
		$arrExtraOperations=array();
		$arrExtraValues=array();
		$arrExtraAndOrOperations=array();		
			
	for($i=0;$i<count($arrWhereFieldsCopy);$i++)	
		{	

			if(in_array($arrWhereFieldsCopy[$i],$arrWhereExtraFields))
			{

				$index=array_search($arrWhereFieldsCopy[$i], $arrWhereFields);				
				array_push($arrExtraFields,$arrWhereFields[$index]);
				array_push($arrExtraOperations,$arrWhereOperations[$index]);
				array_push($arrExtraValues,$arrWhereValues[$index]);
				array_push($arrExtraAndOrOperations,$arrWhereAndOrOperation[$index]);

				$arrWhereValues	=	array_merge( array_slice ( $arrWhereValues, $index+1),(array_slice ( $arrWhereValues,0, $index)));
				$arrWhereFields	=	array_merge( array_slice ( $arrWhereFields, $index+1),(array_slice ( $arrWhereFields,0, $index)));
				$arrWhereOperations	=	array_merge( array_slice ( $arrWhereOperations, $index+1),(array_slice ( $arrWhereOperations,0, $index)));
				$arrWhereAndOrOperation	=	array_merge( array_slice ( $arrWhereAndOrOperation, $index+1),(array_slice ( $arrWhereAndOrOperation,0, $index)));
			}	
			
			}			
			$strExtraFields=implode(",", $arrExtraFields);
			$strExtraOperations=implode(",", $arrExtraOperations);
			$strExtraValues=implode(",", $arrExtraValues);
			$strExtraAndOrOperations=implode(",", $arrExtraAndOrOperations);
			
			$strWhereValues=implode(",", $arrWhereValues);
			$strWhereFields=implode(",", $arrWhereFields);
			$strWhereOperations=implode(",", $arrWhereOperations);
			$strWhereAndOrOperation=implode(",", $arrWhereAndOrOperation);
			
		$arrExtraFieldInfo=array ($strWhereFields,$strWhereOperations,$strWhereValues,$strExtraOperations,$strExtraValues,$strExtraFields,$strWhereAndOrOperation,$strExtraAndOrOperations);		
//$this->test_file("sussolLib: \n strExtraOperations: $strExtraOperations \n strExtraValues: $strExtraValues \n strExtraFields: $strExtraFields \n strWhereAndOrOperation: $strExtraAndOrOperations \n ");	

		return($arrExtraFieldInfo);	
	}
	
	// here No third table(i.e not refered by client's query explicity) needed to search 
	function buildWhereClauseSimple($strWhereFields,$strWhereValues,$strWhereOperations,$strWhereAndOrOperations)
	{
	
		$arrWhereFields	=	explode(',',$strWhereFields);
		$arrWhereOperations	=	explode(',',$strWhereOperations);
		$arrWhereValues	=	explode(',',$strWhereValues);	
		$arrAndOr		=	explode(',',$strWhereAndOrOperations);
			
		$whereClause	=	array();
			
			for( $i = 0 ; $i < count($arrWhereOperations) ; $i++ ) {
					
				switch($arrWhereOperations[$i])	{	
					
					case '!=':
						
							 if(!strchr($arrWhereValues[$i],'%'))//if '%' wild card character is not present 
								$arrWhereValues[$i]	=	" $arrWhereOperations[$i] '$arrWhereValues[$i]' ";
							else		
								$arrWhereValues[$i]=" NOT LIKE '$arrWhereValues[$i]' ";
					
							//$whereClause[$arrWhereFields[$i]]= " $arrAndOr[$i] $arrWhereFields[$i] $arrWhereValues[$i] ";								
							$strPart=	" $arrAndOr[$i] $arrWhereFields[$i] $arrWhereValues[$i] ";
							array_push($whereClause,$strPart);
							break;
							
					case '=':
						if(!strchr($arrWhereValues[$i],'%'))//if '%' wild card character is not present 
							$arrWhereValues[$i]	=	" $arrWhereOperations[$i] '$arrWhereValues[$i]' ";
						else		
							$arrWhereValues[$i]=" LIKE '$arrWhereValues[$i]' ";
				
					//	$whereClause[$arrWhereFields[$i]]=" $arrAndOr[$i] $arrWhereFields[$i]  $arrWhereValues[$i] ";								
						$strPart=	" $arrAndOr[$i] $arrWhereFields[$i]  $arrWhereValues[$i] ";
						array_push($whereClause,$strPart);
						break;
					
					case '>':					
					case '<':
					case '>=':
					case '<=':
					case 'IS':// 'IS' can be operation sometimes for eg 'IS NULL' used for email, phone , address 
							  // this operation is defined in pagespecific search 
					default :
						$arrWhereValues[$i]	=	str_replace ('%' ,'',$arrWhereValues[$i]);//ignore the '%' wild card characteer
						$arrWhereValues[$i]	=	" $arrWhereOperations[$i] '$arrWhereValues[$i]' ";								
					//	$whereClause[$arrWhereFields[$i]]= " $arrAndOr[$i] $arrWhereFields[$i] $arrWhereValues[$i] ";	
						if($arrWhereValues[$i]=='NULL')// occurs only if operation is'IS'
							$strPart=" $arrAndOr[$i] $arrWhereFields[$i] IS NULL ";
						else
							$strPart=" $arrAndOr[$i] $arrWhereFields[$i] $arrWhereValues[$i] ";
						
						array_push($whereClause,$strPart);
						break;					
				}			
			}//end for
		
		return($whereClause);
	}
	
	
	
	
	function changeUserGroup($userID,$groupID,$name,$host,$action)
	{	
        //$this->query("Start transaction");	
        $ok = true;
        $arrAllTabNames=array();//to hold all the tab names
        $query="UPDATE `users` SET `users`.`role_id` = '$groupID' WHERE `users`.`id` =$userID";
		$ok=($this->isInteger( $this->query($query)));	
        //	return $this->printTransactionInfo($this->query($query));
		
		if($ok){
		
            //echo"allTabNames,userID,groupID <br>$allTabNames<br><br>$userID<br>$groupID";
            $strGroupPermission=$this->getGroupPermission($groupID,'',false);
            $tab_permission= explode(",",$strGroupPermission);
	
            $arrSupID=array();//gets the id of the currently available entries from table "security_user_permission" which can be edited for the given userID
		
            $query=sprintf("select  `security_user_permission`.`id` as supID , `security_user_permission`.`tab_name`, 
			`security_permission`.`name` from  `security_user_permission`
			LEFT JOIN `security_permission` ON  `security_user_permission`.`permission_id`=`security_permission`.`id`
			WHERE 
			`security_user_permission`.`deleted`='0' AND `security_permission`.`deleted`='0'			
			AND `security_user_permission`.`user_id`=$userID order by supID");
            $result=$this->query($query);
            if (mysql_num_rows($result) > 0)
            {
                while($row=mysql_fetch_array($result))
                {	
                    $index=$row['tab_name'].'_'.$row['name'];
                    $arrSupID[$index]=$row['supID'];	
					array_push($arrAllTabNames,$row['tab_name']);
                }
            }

            // Now update the group permissions
            //$permissionsName= array('add','edit','delete','view');
            $query=" UPDATE `security_user_permission` SET  `security_user_permission`.`allow_deny`=  case id  "; 
	
            foreach($tab_permission as $currentPermission)
            {
            //	echo"<br> $currentPermission";	
				$currentTabPerm=explode("=",$currentPermission);
				$currentTab=$currentTabPerm[0];
				$current_id=$arrSupID[$currentTab];				//echo"<br> $current_id tab_mode: $currentTab <br>";
				$current_allow_deny=$currentTabPerm[1];
				$query.=" when '$current_id' then '$current_allow_deny' ";		
			
            }		
            $query.=" ELSE allow_deny END ";
            $ok=($this->isInteger( $this->query($query)));	

        }
        
/*		//Now update mysql user privileges for this usser
		
		$arrPermission	=	explode(",",$strGroupPermission);
		$arrAllTabNames	=	array_unique($arrAllTabNames);//remove duplicate entries (since every tab has 4 entries in the array)
		if ($ok) {

                $ok = $this->updateMysqlUserPermission($action,$arrAllTabNames,$arrPermission,$name,$host);
            }
*/
		return $ok;
		
		
	}
	function updateUserPermission($arrAllTabNames,$arrPermission,$userID,$appUserID,$updateType)
	{	

	$arrSupID=array();//gets the id of the currently available entries from table "security_role_permission" which can be edited for the given userID
		$query=sprintf("select  `security_user_permission`.`id` as supID , `security_user_permission`.`tab_name`, 
		`security_permission`.`name` from  `security_user_permission`
LEFT JOIN `security_permission` ON  `security_user_permission`.`permission_id`=`security_permission`.`id`
		WHERE 
		`security_user_permission`.`deleted`='0' AND `security_permission`.`deleted`='0'	
		AND `security_user_permission`.`user_id`=$userID");
	$result=$this->query($query);
    
    $arrPermissionIDs=$this->get_type('security_permission','name','id');	
    
//echo"suparr:  $query";
/*
	$fh = fopen('saveadmin.txt', "a");
	fwrite($fh, $query."\n");
    fwrite($fh, "tabs: ".$arrAllTabNames."\n");
    fwrite($fh, "perms: ".$arrPermission."\n");
	fwrite($fh, mysql_num_rows($result)."\n");
*/	
	if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_array($result))
			{	
				$index=$row['tab_name'].'_'.$row['name'];
				$arrSupID[$index]=$row['supID'];			
			}
		}
//Eg: $arrSupID['phone_add']='y';
	//Now update the user input values and the timestamps	
	//$this->query("Start transaction");	
    $ok = true;
    $queryNew = "";
    
	$query="";
	
		foreach($arrPermission as $eachPermission)
		{
			$tab_perm=explode("=",$eachPermission);//eg: $eachPermission="email_view=y";
			$current_tab=$tab_perm[0];
			
            if (isset($arrSupID[$current_tab])) {
                $current_id=$arrSupID[$current_tab];
                $current_allow_deny=$tab_perm[1];
                $query.=" when '$current_id' then '$current_allow_deny' ";
            } else { //doesn't exist yet - build up INSERT statement instead
                $current_perm = substr(strrchr($current_tab,"_"),1);
                $current_tab = substr($current_tab,0,-strlen($current_perm)-1);
                $queryNew.="('$userID','$arrPermissionIDs[$current_perm]','$current_allow_deny','$current_tab'),";
            }
		}	
        
        if ($query != "") { //have added a clause
            $query=" UPDATE `security_user_permission` SET  `security_user_permission`.`allow_deny`=  case id  ".$query." ELSE allow_deny END";
            $ok=($this->isInteger( $this->query($query)));	
        }
		
//$this->test_file($query);
	//	return $this->printTransactionInfo($this->query($query));

        if(($queryNew != "") && $ok) {
            $queryNew = 'INSERT INTO `security_user_permission` (`user_id`,`permission_id`,`allow_deny`,`tab_name`) VALUES'.$queryNew;
            $queryNew=substr_replace($queryNew,"",-1);//remove the last character which is  a comma "," 
            //$this->test_file($queryNew);
            $ok=($this->isInteger($this->insertQuery($queryNew)));
        }
        
		if($ok){//insert changes information to change log table	
	
			$query		=" Cannot create change log. Please retry ";//message to display when createChangeLog() fails
			$retVal=$query."\n";
			$ok=$this->createChangeLog($appUserID,'security_user_permission',$userID,$updateType,'security_user_permission');	//here $userID is inf.users.id 	
		}	
		
		if($ok){
		//update timestamp
			$query="UPDATE  `security_user_permission` SET  `timestamp` = NOW( ) WHERE  `security_user_permission`.`user_id` =".$userID;
			$ok = ($this->isInteger($this->insertQuery($query)));		
		}
		//if ($ok)			
		//	$this->query($this->printTransactionInfo("COMMIT"));// commit transaction
		//else
		//	$this->query($this->printTransactionInfo($query));// force rollback

//CALL A FUNCTION TO DUPLICATE THE CURRENT USER PRIVILEGES IN MYSQL USER PRIVILEGES  AND RETURN $ok IF THIS FUNCTION RUNS CORRECTLY


		return $ok;	
	}
	
	function updateGroupPermission($arrPermission,$groupID,$userID,$updateType)
	{	
    
	$arrSupID=array();//gets the id of the currently available entries from table "security_role_permission" which can be edited for the given userID
    
	$query=sprintf("select  `security_role_permission`.`id` as supID , `security_role_permission`.`tab_name`,
	`security_permission`.`name` from  `security_role_permission`
LEFT JOIN `security_permission` ON  `security_role_permission`.`permission_id`=`security_permission`.`id`
	WHERE
	`security_role_permission`.`deleted`='0' AND `security_permission`.`deleted`='0'	AND
	`security_role_permission`.`role_id`=$groupID");

	$result=$this->query($query);
/*	
	$fh = fopen('saveadmin.txt', "a");
	fwrite($fh, $query."\n");
	fwrite($fh, mysql_num_rows($result)."\n");
*/	
	if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_array($result))
			{	
				$index=$row['tab_name'].'_'.$row['name'];
				$arrSupID[$index]=$row['supID'];			
			}
		}	
	
    $arrPermissionIDs=$this->get_type('security_permission','name','id');	
	
    //$this->query("Start transaction");	
    $ok = true;
    
        $queryNew="";
		//Now update the user input values and the timestamps
		$query=" UPDATE `security_role_permission` SET  `security_role_permission`.`allow_deny`=  case id  ";
		
		foreach($arrPermission as $eachPermission)
		{
			$tab_perm=explode("=",$eachPermission);//eg: $eachPermission="email_view=y";
			$current_tab=$tab_perm[0];
			
            if (isset($arrSupID[$current_tab])) {
                $current_id=$arrSupID[$current_tab];
                $current_allow_deny=$tab_perm[1];
                $query.=" when '$current_id' then '$current_allow_deny' ";
            } else { //doesn't exist yet - build up INSERT statement instead
                $current_perm = substr(strrchr($current_tab,"_"),1);
                $current_tab = substr($current_tab,0,-strlen($current_perm)-1);
                $queryNew.="('$groupID','$arrPermissionIDs[$current_perm]','$current_allow_deny','$current_tab'),";
            }
		}
		$query.=" ELSE allow_deny END";			

        $ok=($this->isInteger( $this->query($query)));	
        
        if(($queryNew != "") && $ok) {
            $queryNew = 'INSERT INTO `security_role_permission` (`role_id`,`permission_id`,`allow_deny`,`tab_name`) VALUES'.$queryNew;
            $queryNew=substr_replace($queryNew,"",-1);//remove the last character which is  a comma "," 
            //$this->test_file($queryNew);
            $ok=($this->isInteger($this->insertQuery($queryNew)));
        }
		
		if($ok){//insert changes information to change log table
		
			$query		=" Cannot create change log. Please retry ";//message to display when createChangeLog() fails
			$retVal=$query."\n";
			$ok=$this->createChangeLog($userID,'security_role_permission',$groupID,$updateType,'security_role_permission');	
		}
		
		if($ok){
		//update timestamp
			$query="UPDATE  `security_role_permission` SET  `timestamp` = NOW( ) WHERE  `security_role_permission`.`role_id` =".$groupID;
//$this->test_file($query);
			$ok = ($this->isInteger($this->insertQuery($query)));		
		}
		//if ($ok)			
		//	$this->query($this->printTransactionInfo("COMMIT"));// commit transaction
		//else
		//	$this->query($this->printTransactionInfo($query));// force rollback

		return $ok;		
	}
	
	function getGroupPermission($groupID='',$userID='',$getTimestamp=true)
	{			
		$subQuery='';
		$retVal='';
		
		if($groupID!='')
		{
			$subQuery="role_id=$groupID";
			
			//Make sure there are default values for all tabs
			$insertQuery="INSERT IGNORE INTO `security_role_permission` (`id`,`role_id`,`permission_id`,`allow_deny`,`tab_name`) (SELECT '',$groupID,
`permission_id`,`allow_deny`,`tab_name` FROM `security_role_permission` WHERE `role_id` = (SELECT id from `security_role` WHERE `name`='disabled')
);";
			$result=$this->query($insertQuery);
			//$this->test_file($insertQuery);
			
			$query='select `security_role_permission`.`timestamp` from `security_role_permission` where
			`security_role_permission`.`role_id`='.$groupID.' AND `security_role_permission`.`deleted`=0 LIMIT 1';
			$result=$this->query($query);
			if($result && $getTimestamp)
				while($row=mysql_fetch_assoc($result))
				{
					$retVal.='timestamp='.$row['timestamp'].',';
				}
		}
		else
		{
			$subQuery="role_id in (select users.role_id from users where users.deleted='0' AND users.id=$userID)";		
		}
		
		//Now get the group setting
		$query="SELECT  security_permission.name as permission, security_role_permission.allow_deny,
					security_role_permission.tab_name 
						FROM security_permission
						LEFT JOIN security_role_permission ON 
						security_permission.id = security_role_permission.permission_id
						WHERE security_permission.deleted='0' AND security_role_permission.deleted='0' AND $subQuery";
		$result=$this->query($query);
		if($result)
			{
				while($row=mysql_fetch_assoc($result))
				{									
					$retVal.=$row['tab_name']."_".$row['permission']."=".$row['allow_deny'].",";	
				}
			}
			
		$retVal=substr_replace($retVal ,"",-1);//remove the last character which is  "," 	
	//$this->test_file($retVal);	
		return $retVal;		
	}
		
	function getUserPermission($userID)
	{			
		//Make sure there are default values for all tabs
		$insertQuery="INSERT IGNORE INTO `security_user_permission` (`id`,`user_id`,`permission_id`,`allow_deny`,`tab_name`) (SELECT '',$userID,
`permission_id`,`allow_deny`,`tab_name` FROM `security_user_permission` WHERE `user_id` = (SELECT id from `users` WHERE `user_name`='disabled')
);";
		$result=$this->query($insertQuery);
		//$this->test_file($insertQuery);
				
		$query="SELECT  security_permission.name as permission,security_user_permission.id as supID,
		security_user_permission.allow_deny, 
		security_user_permission.tab_name 
		FROM security_permission
		LEFT JOIN security_user_permission ON security_permission.id = security_user_permission.permission_id		
		WHERE security_permission.deleted='0' AND security_user_permission.deleted='0' AND security_user_permission.user_id=".$userID;
		$result=$this->query($query);
		if($result)
		{
			$retVal.="personal_setting=true";
			while($row=mysql_fetch_assoc($result)){					
					//$retVal.=",".$row['tab_name']."_".$row['permission']."=".$row['allow_deny']."_".$row['supID'];	
					$retVal.=",".$row['tab_name']."_".$row['permission']."=".$row['allow_deny'];	
				}
		}
		
		$query="select security_role.name as role_name, email, search_id from users 
				LEFT JOIN security_role ON users.role_id = security_role.id
				where users.deleted='0' AND security_role.deleted='0' AND users.id=".$userID;
			$result=$this->query($query);
					if($result)
					{
						while($row=mysql_fetch_assoc($result)){				
						$retVal.=",roleName=".$row['role_name'];	
                        $retVal.=",userEmail=".$row['email']; 
                        $retVal.=",searchID=".$row['search_id'];                         
						}
					}
		$query='select `security_user_permission`.`timestamp` from `security_user_permission` where
		`security_user_permission`.`deleted`=0 AND
		`security_user_permission`.`user_id`='.$userID.' LIMIT 1';
			$result=$this->query($query);
			if($result)
				while($row=mysql_fetch_assoc($result))
				{
					$retVal.=',timestamp='.$row['timestamp'].',';
				}
                
		return $retVal;					
	}

    function get_comma_list($table,$op_column,$test_column,$test_value)
    {	
        $test_value = str_replace(",","','",$test_value);
        $query="SELECT ".$op_column." FROM ".$table." where $table.deleted='0' AND ".$test_column." IN ('".$test_value."')";

		$retVal='';

		$result=$this->query($query);
		if($result)
		{
			while($row=mysql_fetch_array($result))
			{									
				$retVal.=$row[0].",";
			}
		}
			
		$retVal=substr_replace($retVal ,"",-1);//remove the last character which is  "," 	
//$this->test_file($query."\n".$retVal);	
		return $retVal;		
	}
	
	function getAllTabMode($arrAllTabNames)
	{		$arrayAllTabMode=array();
			$permissionsName= array('add','edit','delete','view');
			$count1=0;
			foreach($permissionsName as $currentPermission)
			{
				foreach($arrAllTabNames as $eachTab)
				{
					$arrayAllTabMode[$count]=$eachTab."_".$currentPermission;
					$count++;
				}
			}
		return $arrayAllTabMode;
	}
	
	function insertDefaultGroupPermission($groupID,$arrAllTabNames)
	{
		
		$arrPermissionIDs=$this->get_type('security_permission','name','id');		
			$query='INSERT INTO `security_role_permission` (`role_id`,`permission_id`,`allow_deny`,`tab_name`) VALUES';
			
			$permissionsName= array('add','edit','delete','view');	
				foreach($permissionsName as $current_perm)
					foreach($arrAllTabNames as $current_tab){
						$query.="('$groupID','$arrPermissionIDs[$current_perm]','n','$current_tab'),";	
					}	
			$query=substr_replace($query,"",-1);//remove the last character which is  a comma "," 	
			$result=$this->query($query);
            return $result;
	//$this->test_file1(" $query");
	}
	
	function insertDefaultUserPermission($userID,$arrAllTabNames)
	{
		
		$arrPermissionIDs=$this->get_type('security_permission','name','id');		
			$query='INSERT INTO `security_user_permission` (`user_id`,`permission_id`,`allow_deny`,`tab_name`) VALUES';
			
			$permissionsName= array('add','edit','delete','view');	
				foreach($permissionsName as $current_perm)
					foreach($arrAllTabNames as $current_tab){
						$query.="('$userID','$arrPermissionIDs[$current_perm]','n','$current_tab'),";
					}	
			$query=substr_replace($query,"",-1);//remove the last character which is  a comma "," 	
			$result=$this->query($query);
	//$this->test_file('insertDefaultUserPermission()',"$result \n".$query);
            return $result;
	//$this->test_file1("bbdd \n $query");
	}

	//creates a directory with widest possible permission
	function creatDirectoryRecursive($dirName, $rights=0777){
		
        if (!is_dir($dirName))
        { 
        	//Artur Neumann INFN 9.8.2012
        	//the mkdir command sometimes does not respect the $rights but take them from umask
        	$oldumask = umask(0);
        	mkdir($dirName, $rights, true);
        	umask($oldumask);
        }
        /*
		$dirs = explode('/', $dirName);
		$dir='';
		foreach ($dirs as $part) {
			$dir.=$part.'/';
			if (!is_dir($dir) && strlen($dir)>0)
				mkdir($dir, $rights);
		}
                */
	}
	
	function removeUserTempDir($dir) {
      $files = scandir($dir);
      array_shift($files); // remove '.' from array
      array_shift($files); // remove '..' from array
      foreach ($files as $file) {
		$file = $dir . '/' . $file;
		if (is_dir($file)) {  
			$this->removeUserTempDir($file);  
			rmdir($file);  
		} 
		else {  
			unlink($file);			
		}  
      }  
	  
     rmdir($dir);//comment this line if you dont want to remove directory  		
    }     
	
	function full_copy( $source, $target )// not used
    {
		if( !is_dir( $source ) )
		{
            copy( $source, $target );			
        }		
        else
        {
            @mkdir( $target );
           
            $d = dir( $source );
           
            while ( FALSE !== ( $entry = $d->read() ) )
            {
                if ( $entry == '.' || $entry == '..' )
                {
                    continue;
                }
               
                $Entry = $source . '/' . $entry;           
                if ( is_dir( $Entry ) )
                {
                    full_copy( $Entry, $target . '/' . $entry );
                    continue;
                }
                copy( $Entry, $target . '/' . $entry );
            }
           
            $d->close();
        }   
    }

//function for avoiding sql injectiion.
	function sql_injection_immunization($input){
		if(is_array($input)){
			foreach($input as $k=>$i){
				$output[$k]=sql_injection_immunization($i);
			}
		}
		else{
			if(get_magic_quotes_gpc()){
				$input=stripslashes($input);
			}       
			$output=mysql_real_escape_string($input);
    }   
   
    return $output;
}

    function getUploadDir() {
        return str_replace('/files/','/',$_SERVER['DOCUMENT_ROOT'] .  dirname($_SERVER['PHP_SELF']) . '/');
    }

    function getUploadURL() {
    
        $http = "http://";
        if (($_SERVER['HTTPS'] != '') && ($_SERVER['HTTPS'] != 'off')) $http = "https://";
        return $http.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) . '/';
    }
    
    function getUserUploadDir($user) {
        $fileDir = $this->getUploadDir().$this->get_dbfiles_dir()."fileUploads/$user/";
        //Artur Neumann INFN 8.9.2012
        //changed the group permissions, so we don't need to sync as root or www-data
        $this->creatDirectoryRecursive($fileDir,0770);
        return $fileDir;
    }    

    function getUserBackupDir($user) {
        $fileDir = $this->getUploadDir().$this->get_dbfiles_dir()."backups/$user/";
        $this->creatDirectoryRecursive($fileDir,0700);
        return $fileDir;
    }

    function getFileName($file_link) {
 
        if (strpos($file_link, '/') !== false) { //if file/http link
            $file_info=split('/',$file_link);
            $index=count($file_info)-1;
            $file_name=$file_info[$index];
        }

        if (strpos($file_name, '=') !== false) { //if php link
            $file_info=split('=',$file_name);
            $index=count($file_info)-1;
            $file_name=$file_info[$index];
        }

        return $file_name;
    }
    
    function getThumbName($file_name,$nameID='') {
        
        $base_name = $file_name;
        $ext_index = strrpos($file_name, '.');
        if ($ext_index > 0) {
            $base_name = substr($file_name, 0, $ext_index); //strip extension if it exists
            $ext = substr($file_name, $ext_index + 1);
            $base_name .= "_thumb.".$ext;
        }
        else $base_name .= "_thumb";
        
        if ($nameID != '') $base_name = $this->getUserUploadDir($nameID).$base_name; //add full path
        
        return $base_name;
    }

    function makeThumbImage($photoName,$nameID) {
    
        $temp_file_dir = $this->getUserUploadDir($nameID);
	    $temp_photo_path = $temp_file_dir."$photoName";
        
        if (!is_file($temp_photo_path)) return '';
        
        $photo_path = $temp_file_dir.$this->getThumbName($photoName);        
		if (!is_file($photo_path)) {
            $photo = new SimpleImage();
            $photo->load($temp_photo_path);
            $photo->resizeToWidth(150);     
            $photo->save($photo_path);
        }
        if (!is_file($photo_path)) return '';
        
        //Artur Neumann INFN 8.8.2012
        //change the permissions, so we can sync the files as group member
       	chmod( $photo_path, 0640);

        
        $fh = fopen($photo_path, "r");
        $photo_data= addslashes(fread($fh, filesize($photo_path)));
        fclose($fh);
        
        return $photo_data;
    }
    
	function copyUserFiles($currentUserID,$nameID,$linkFromDB,$picNamePrefix='')
	{		//$picNamePrefix=='Original_' then it will be the Original sized image case NOT thumbNail image case: THIS CASE NOT USED FOR NOW
		if ($linkFromDB=='')return;//if not checked warning is thrown 
		
		$targetLink=str_replace('userID',$currentUserID,$linkFromDB);	
		$arrNames=explode('/',$linkFromDB);
		$position=count($arrNames)-1;
		$fileName=$arrNames[$position];
		
		$upload_dir = $this->getUploadDir();

		$source = $this->getUserUploadDir($fileName);
		$temp_file_dir ="../main/fileUploads/$currentUserID/$nameID/"; 
		$destination = $upload_dir.$temp_file_dir.$fileName;
		
		//regenerate URL for file download link on current server (make independent of the base URL in the link stored in the database)
		//$fileName=$picNamePrefix.$fileName;
		if ($_SERVER['HTTPS'] == "on") {$targetLink = "https://";} else {$targetLink = "http://";}
		$targetLink .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."/../".$temp_file_dir.$picNamePrefix.$fileName;
	
		if($picNamePrefix=='')//no need to copy image but return link only
			return $targetLink;
		else
			$destination = $upload_dir.$temp_file_dir.$picNamePrefix.$fileName;

		//copy current  file to destination - first create the destination folder (if not exists)
		if(! is_dir ( $temp_file_dir)){
			$this->creatDirectoryRecursive($temp_file_dir);		
		}		
		
		if (file_exists($source))		
			$this->copyr( $source,$destination );	
			
		else
			$targetLink = '';//return blank since file does not exist in the sorce directory	
		
		return $targetLink;		
	}
	
	/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/repos/v/function.copyr.php
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function copyr($source, $dest)
{

//$this->test_file($source,$dest);
	//$source="../../fileUploads/15/1231651755390.png"
	//$dest="http://localhost/files/fileUploads/2/15/1231651755390.png";
    // Simple copy for a file
    if (is_file($source)) {
        copy($source, $dest);
		return ;
    }
 
    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }
    
    // If the source is a symlink
    if (is_link($source)) {
        $link_dest = readlink($source);
        return symlink($link_dest, $dest);
    }
 
    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Deep copy directories
        if ($dest !== "$source/$entry") {
            $this->copyr("$source/$entry", "$dest/$entry");
        }
    }
 
    // Clean up
    $dir->close();
    return true;
}
	
	
	function copyFileFromUrl($srcURL){//not used till now 			
	$content = file_get_contents($srcURL);
	$dir = dirname($_SERVER['SCRIPT_FILENAME']);
	$fp = fopen($dir.'/1232417654015.png', 'w');//copies to current dir
	fwrite($fp, $content);
	fclose($fp);
	
	}

	function logSearchQuery($userID,$description,$queryToInsert,$blob)
	{
		$query="INSERT INTO `search_history` (`user_id`,`query`,`name`,`saved`) VALUES('$userID',\"$queryToInsert\",'$description','$blob')";
		$this->query($query);
		//$this->test_file('sussolLibrary-logSearchQuery()',$query);
	}	

	function check_foreign_keys($action,$table,$field,$value,$select_field='id',$extra_where='')
	{//$retVal returns '' (blank) if the operation goes successfully
		$retVal='';
	
	//$select_field can be any field just a dummy field. For most of the tables(except: name_address, name_phone , name_email) id is taken
		$query="select `$table`.`$select_field` from `$table` where `$table`.`deleted`='0' AND `$table`.`$field`='$value' $extra_where AND `$table`.`deleted`='0'";
		$result=$this->query($query);
		if (mysql_num_rows($result) > 0)
			$retVal = $table ;		//foreign key used in this $table		
		else
			$retVal ='';//no foreign key used . So this can be deleted 
/*
$fh = fopen('test.txt', "a"); 
fwrite($fh, $query." : ".$retVal."\n"); 
fclose($fh);
*/                
		if(($action=='Force Delete')&&($retVal!=''))
		{ //for deleting the deleted field in the table is set to 1
			//	$query="DELETE FROM `$table` WHERE `$table`.`$field`='$value'";
			$query="UPDATE  `$table` SET `$table`.`deleted` =  '1' WHERE `$table`.`$field`='$value' $extra_where";
			$result=$this->query($query);
			if(!$result)
				$retVal = $table; 
			else
				$retVal ='';			
/*
$fh = fopen('test.txt', "a"); 
fwrite($fh, $query." : ".$retVal."\n"); 
fclose($fh);
*/
        }
        
		if($retVal!='')$retVal.=',';
		return $retVal;		
	}
	
	//set foreign key to ''(blank) 
	function clear_foreign_key($foreignTable,$field,$value)	
	{
		$query="UPDATE `$foreignTable` SET `$foreignTable`.`$field`='' where `$foreignTable`.`$field`='$value'";
		$result=$this->query($query);
		if (!$result) 
			return $foreignTable;
		else 
			return '';
	}

	
	//for eg. if $targetField= 'personnel_reviewer'  then thsi function selects persons with this authority
	function selectPersons($targetField)
	{
	$id_name=array();
	//Artur Neumann INF/N 14.08.2012
	//the query checked against `inf_staff`, but this does not make sence, changed to `staff`
	//And teh query didn't check if the name-post relation was deleted. so I add  AND name_post.deleted = 0
	$query="SELECT `name`.`id` as name_id, CONCAT( `name`.`known_as`,' ', `surname`.`surname` ) as fullname FROM `name`
	LEFT JOIN `name_post` ON `name`.`id` = `name_post`.`name_id` 
	LEFT JOIN `surname` ON name.id = surname.name_id 
    LEFT JOIN `staff` ON name.id = staff.name_id 
	LEFT JOIN `post` ON name_post.post_id = post.id AND name_post.deleted = 0
	WHERE `post`.`".$targetField."` =  'yes' AND `staff`.`deleted`='0' AND `name`.`deleted`='0'AND `post`.`deleted`='0' 
	AND `surname`.`priority` =1 AND `staff`.`leaving_date` = '0000-00-00'   AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') ) ORDER BY `fullname`";
// 	$this->test_file($query);
	$result=$this->query($query);
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_assoc($result))
			{
                $id_name[$row['name_id']]=$row['fullname'];			
			}			
		}
		
	return $id_name; 		
	}

	//for eg. if $targetField= 'personnel_reviewer'  then thsi function selects posts with this authority
	function selectPosts($targetField)
	{
        $id_name=array();
        $query="SELECT `post`.`name` as fullname, `post`.`id` as post_id FROM  `post` 
		WHERE `post`.`".$targetField."` =  'yes' AND `post`.`deleted`='0' ORDER BY `fullname`";
        $result=$this->query($query);
        
		if (mysql_num_rows($result) > 0)
		{
			while($row=mysql_fetch_assoc($result))
			{
                $id_name[$row['post_id']]=$row['fullname'];			
			}			
		}
		
        return $id_name; 		
	}
	
	// checks if $input has correct slashes,otherwise adds slashes.	
	function addslashes_once($input){
    
        //These characters are single quote ('), double quote ("), backslash (\) and NUL (the NULL byte).
        $pattern = array("\\'", "\\\"", "\\\\", "\\0");
        $replace = array("", "", "", "");
        if(preg_match("/[\\\\'\"\\0]/", str_replace($pattern, $replace, $input))){
            return addslashes($input);
        }
        else{
            return $input;
        }
    }
	
	//salinate the $postArray
	function prepareUserData($postArray) {
			
		foreach ($postArray as $key => $value) {	
			$newvalue = str_replace("%","%%",$value);
			$newvalue = $this->addslashes_once($newvalue);
			$postArray[$key] = mysql_real_escape_string($newvalue);
	    }	
		
        return $postArray;
	} 

	//$userID: user who make changes,
	//$table: affected table,
	//$recordID: affected record's id in the $table(FOR userPermission and rolePermission the recordID is inserted as 0 in change_log SO this variable is used to pass the userID and roleID)
	//$updateType: new,update or delete, $nameID: id of the person whose data is changed(only needed in case of $changedFor='tab')
	//$changedFore:either 'admin' or 'tab', used for generating comments
	//$comments:if $table is one of the admin setting table then comments= $table.name for that $recordID
	//if $tabled is one of the Tabs then $comment=Full name of the person whose record is changed
	
	function createChangeLog($userID,$table,$recordID,$updateType,$changedFor,$nameID='')
	{ //$comment is usually a subQuery
		$comments='';//initialisation
	//Need to set SiteID after deployment
		$siteID="(select `site_specific_id` from `site` WHERE `site`.`deleted`='0')"; //0 junk value for testing

		if($changedFor=='admin'){
            $comments="(select `$table`.`name` from `$table` where `$table`.`id`='$recordID' AND `$table`.`deleted`='0')";
		}
		elseif($changedFor=='tab'){//tab	
			if ($table == "relation") {
				//Artur Neumann INFN
				//19.07.2013
				//get some more information if the relation was changed
				$comments="(SELECT CONCAT(`name`.`forenames`,' ',`surname`.`surname`, ' is ',relation.relationship , ' of ', `name_relative`.`forenames`,' ',`surname_relative`.`surname` ) 
						        FROM 
									`name`,
									`surname`,
									`relation`,
									`name` as name_relative,
									`surname` as surname_relative
									
								WHERE `name`.`id`=`surname`.`name_id` AND `surname`.`priority`=1 AND name.id = relation.relation_id
									AND name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') )
									AND `name_relative`.`id`= `surname_relative`.`name_id` AND `name_relative`.`id`= relation.name_id
									AND `surname_relative`.`priority`=1 
									AND name_relative.deleted='0' AND ((`surname_relative`.`deleted` is NULL) OR (`surname_relative`.`deleted` = '0') )
									AND relation.id='$recordID')";
				
			}	else {
				$comments="(select CONCAT(`name`.`forenames`,' ',`surname`.`surname`) from `name`,`surname` where `name`.`id`=".$nameID." AND `surname`.`name_id`=".$nameID." AND `surname`.`priority`=1 AND name.deleted='0' AND ((`surname`.`deleted` is NULL) OR (`surname`.`deleted` = '0') ))";
				
			}
		}
		elseif($changedFor=='security_role_permission'){
			//$recordID gives groupID in this case
			$comments="(select `security_role`.`name` from `security_role` where `security_role`.`deleted`='0' AND `security_role`.`id`=$recordID )";
			//now clear the $recordID and assign as 0
			$recordID=0;		
		}
		elseif($changedFor=='security_user_permission'){
			//$recordID gives groupID in this case
			$comments="(select `users`.`user_name` from `users` where `users`.`deleted`='0' AND `users`.`id`=$recordID )";
			//now clear the $recordID and assign as 0
			$recordID=0;		
		}
        elseif($changedFor=='visa_history') {
            $comments="(select CONCAT(`visa`.`name`,': ',`visa_history`.`number`) from `visa`,`visa_history` where `visa`.`id` = `visa_history`.`visa_id` AND `visa_history`.`id`=".$recordID." AND ((`visa`.`deleted` is NULL) OR (`visa`.`deleted` = '0') ) AND ((`visa_history`.`deleted` is NULL) OR (`visa_history`.`deleted` = '0') )
			)";
		}
        elseif($changedFor=='organisation_rep') {
            $comments="(select CONCAT(`organisation`.`name`,': ',`organisation_rep`.`name`) from `organisation`,`organisation_rep` where ((`organisation`.`deleted` is NULL) OR (`organisation`.`deleted` = '0') ) AND ((`organisation_rep`.`deleted` is NULL) OR (`organisation_rep`.`deleted` = '0') ) AND `organisation`.`id` = `organisation_rep`.`organisation_id` AND `organisation_rep`.`id`=".$recordID.")";
		}
        elseif($changedFor=='search_history') {
            $comments="(select `name` from `search_history` where `search_history`.`deleted`='0' AND `search_history`.`id`=".$recordID.")";
		}
        elseif(($changedFor=='location') || ($changedFor=='organisation')){
            $comments="(select CONCAT('$changedFor',': ',`$table`.`$table`) from `$table` where `$table`.`id`='$recordID' AND `$table`.`deleted`='0')";
		}
//$this->test_file($changedFor.":".$comments); 
        if ($comments=='') {
		$query="INSERT INTO `change_log` (`id`,`site_id`, `user_id`, `table`, `record_id`, `update_type`, `comment`, `timestamp`) VALUES ('', $siteID,'$userID', '$table', '$recordID', '$updateType', '', NOW())";
        }
        else {
            $query="INSERT INTO `change_log` (`id`,`site_id`, `user_id`, `table`, `record_id`, `update_type`, `comment`, `timestamp`) VALUES ('', $siteID,'$userID', '$table', '$recordID', '$updateType', $comments, NOW())";
        }
		$result=$this->insertQuery($query);//If database error then returns error else returns integer value i.e. last inserted row ID
		
//$this->test_file($query);

		return ($this->isInteger($result));//returns either true or false
	}
	
	function setPasswordChange($userID){
    
		$query="UPDATE  `users` SET  `password_changed` =  '1' WHERE  `users`.`id` =".$userID;
		$result=$this->query(sprintf($query));
		
		return ($this->isInteger($result));//returns either true or false		
	}
	
	function generatePassword ($length = 8)
	{
	  // start with a blank password
	  $password = "";

	  // define possible characters
	  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
	    
	  // set up a counter
	  $i = 0; 
	    
	  // add random characters to $password until $length is reached
	  while ($i < $length) { 

	    // pick a random character from the possible ones
	    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
	        
	    // we don't want this character if it's already in the password
	    if (!strstr($password, $char)) { 
	      $password .= $char;
	      $i++;
	    }
	  }
	  // done!
	  return $password;
	}
		
	function getSiteName(){	
    
		$DB=$this->getdbname();
		$queryResult= $this->query("SELECT `site`.`name` FROM `".$DB."`.`site` where `site`.`id`=1 and `site`.`deleted`=0");
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
                return $row['name'];
            }
            else
                return 'none';
        }
        else{	
            echo mysql_error();
			return 'none';
        }	
	}

	function getSiteID(){
	
		$DB=$this->getdbname();
		$queryResult= $this->query("SELECT `site`.`site_specific_id` FROM `".$DB."`.`site` where `site`.`id`=1");
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
                return $row['site_specific_id'];
            }
            else
                return '0';
        }
        else{	
            echo mysql_error();
			return '0';
        }	
	}

	function isLocalAdmin(){
	
		$role = $_SESSION["role"];
		return ($role == "localadmin");
	}
	
	function isAdmin(){
	
		$role = $_SESSION["role"];
		return (($role == "admin") || ($role == "localadmin") || ($role == "superadmin"));
	}
	
	function isSuperAdmin(){
	
		$role = $_SESSION["role"];
		return ($role == "superadmin");
	}
	
	//Artur Neumann INF/N 26.07.2012 12:00
	//we spend a lot of time to get the correct permissions from the database, because this function
	//is called so often 
	//so I changed it to use an own array $this->isNotAllowedTable to store the permissions. If the permission
	//exists it don't have to ask the DB again
	//I my measurments the execution is up to 4 times faster
	//additionly I made some minor changes for performance improvements
	//1. if with === is the fastest posibility to compare
	//2. return values as early as possible
	//3. it's faster to get an non-empty result from the DB than an empty one 
	function isNotAllowed($tableName,$permissionType,$userID=0){

		if ($tableName === 'none') return false;
		
		$userName = "";
		
		if ($userID === 0) {
			$userName = $_SESSION["name"];
			if ($userName === "") return true;
		}

		$cacheKey =  sha1($tableName.$permissionType.$userID.$userName);
		
		if (isset($this->isNotAllowedTable[$cacheKey])) {
// 			$this->test_file("fount permission in this->isNotAllowedTable",$tableName.$permissionType.$userID.$userName );
				
			return $this->isNotAllowedTable[$cacheKey];
		}
		
		if ($userID === 0) {
			$queryUser = " AND users.user_name='".$userName."'";
		}
		else
		{
			$queryUser = " AND users.id=".$userID;
		}

		$tabName = $tableName;


		if ($tableName === 'name' || $tableName === 'relation') {
			$tabName = 'personal';
		} elseif ($tableName === 'review' ) {
			$tabName = 'annual_review';
		} elseif ($tableName === 'patient_visit' ) {
			$tabName = 'visit';
		} elseif ($tableName === 'patient_service' ) {
			$tabName = 'services';
		} elseif ($tableName === 'patient_surgery' ) {
			$tabName = 'surgery';
		} elseif ($tableName === 'patient_appliance' ) {
			$tabName = 'appliances';
		} elseif ($tableName === 'patient_bill' ) {
			$tabName = 'billing';
		}

		
		$query="SELECT  security_user_permission.allow_deny
				FROM security_permission
				LEFT JOIN security_user_permission ON security_permission.id = security_user_permission.permission_id
				LEFT JOIN users ON users.id = security_user_permission.user_id
				WHERE
				((`security_permission`.`deleted` is NULL) OR (`security_permission`.`deleted` = '0') )
				AND ((`security_user_permission`.`deleted` is NULL) OR (`security_user_permission`.`deleted` = '0') )
				AND security_user_permission.tab_name='".$tabName."' AND security_permission.name='".$permissionType."' ".$queryUser;
			
			
// 		$this->test_file($query);
		$result=mysql_query($query);

		$row = mysql_fetch_row($result);
		if (empty($row[0]) || $row[0] === 'y') {
			$this->isNotAllowedTable[$cacheKey] = false;
		} else {
			$this->isNotAllowedTable[$cacheKey] = true;
		}
			
			
		return $this->isNotAllowedTable[$cacheKey];

		

	}
	
	
	
	
	
	function getSiteLocationID($ignoreAdmin=true){
	
		if ($this->isLocalAdmin()) $ignoreAdmin=false;
		
		if ($this->isAdmin() && $ignoreAdmin) //ignore location if admin or superadmin
			return '0';
			
		$DB=$this->getdbname();
		$query = "SELECT `site`.`location_id` FROM `".$DB."`.`site` where `site`.`id`=1 AND `site`.`name` != 'Patient'";
		$queryResult= $this->query($query);
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
				//$this->test_file($query);
                return $row['location_id'];
            }
            else
                return '0';
        }
        else{	
            echo mysql_error();
			return '0';
        }	
	}
	
	function getSiteProgrammeID($ignoreAdmin=true){
	
		if ($this->isLocalAdmin()) $ignoreAdmin=false;
		
		if ($this->isAdmin() && $ignoreAdmin) //ignore if admin or superadmin
			return '0';
			
		$DB=$this->getdbname();
		$query = "SELECT `site`.`programme_id` FROM `".$DB."`.`site` where `site`.`id`=1 AND `site`.`name` != 'Patient'";
		$queryResult= $this->query($query);
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
				//$this->test_file($query);
                return $row['programme_id'];
            }
            else
                return '0';
        }
        else{	
            echo mysql_error();
			return '0';
        }	
	}
	
	function str2int($string, $concat = true) {
	
		$length = strlen($string);   
		for ($i = 0, $int = '', $concat_flag = true; $i < $length; $i++) {
			if (is_numeric($string[$i]) && $concat_flag) {
				$int .= $string[$i];
			} elseif(!$concat && $concat_flag && strlen($int) > 0) {
				$concat_flag = false;
			}       
		}
   
		return (int) $int;
	}
	
    function maintenance(){
		
        $DB=$this->getdbname();
		//$DB=getdbname();
		$queryResult= $this->query("SELECT `site`.`maintenance` FROM `".$DB."`.`site` where `site`.`id`=1");
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
                $retVal=$row['maintenance'];
            }
            else
                $retVal=0;
        }
        else{	
            echo mysql_error();
			$retVal=0;
        }
        if ($retVal == 1) return true;
        return false;
	}
    
    function getTimeout(){
	
		$DB=$this->getdbname();
		$queryResult= $this->query("SELECT `site`.`timeout` FROM `".$DB."`.`site` where `site`.`id`=1");
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
                return $row['timeout'];
            }
            else
                return '0';
        }
        else{	
            echo mysql_error();
			return '0';
        }	
	}
    
	function getLastSyncTime(){
	
		$DB=$this->getdbname();
		$queryResult= $this->query("SELECT `sync`.`this_time` FROM `".$DB."`.`sync`");
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
                return $row['this_time'];
            }
            else
                return 'never';
        }
        else{	
            echo mysql_error();
			return 'never';
        }	
	}

	function getLastSyncFrom(){
	
		$DB=$this->getdbname();
		$queryResult= $this->query("SELECT `sync`.`sync_from` FROM `".$DB."`.`sync`");
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
                return $row['sync_from'];
            }
            else
                return 'none';
        }
        else{	
            echo mysql_error();
			return 'none';
        }	
	}

	function checkIfRowExixts($field,$table,$check_field,$value){
		// checks if a row having specified field value exists or not. if exists return true else false
		$query="SELECT $field FROM $table WHERE $check_field='$value' and $table.deleted='0'";
		$result=$this->query($query);
        
        if ($result) {
            if (mysql_num_rows($result) > 0)
				return true;				
            else
				return false;
        }
        else
            return false;
	}

	function processDate($originalDate){ //gets a date in dd-mm-yyyy and converts into yyyy-mm-dd format	
		if(($originalDate=='')||($originalDate=='00-00-0000')||($originalDate=='00-00-00')) 
			return '0000-00-00';		
		
		//get the date separator
		If (strpos($originalDate,"/")>0)
			$separator="/";
		Elseif(strpos($originalDate,".")>0)
			$separator=".";		
		ElseIf(strpos($originalDate," ")>0)				
			$separator=" ";	
		ElseIf(strpos($originalDate,"-")>0)	
			$separator="-";	
		
		//now check if the input date is in dd-mm-yyyy format. if true then chage it to yyyy-mm-dd fornat
		if( preg_match( '`^\d{1,2}'.$separator.'\d{1,2}'.$separator.'\d{4}$`' , $originalDate ) )
		{	$pieces = explode($separator, $originalDate);
			$newDate = date("Y-m-d",strtotime($pieces[2]."-".$pieces[1]."-".$pieces[0]));
			return $newDate ;

		}
		return $originalDate;//if the date is not in dd-mm-yyyy format return original date
	}
		
	/*
	This function applies required parenthesis so as to separate the Operator's domain.
	E.g.:If we have  fieldA AND fieldB OR fieldC , then it should be in the form  fieldA AND (fieldB OR fieldC)
	      If we have  fieldA AND fieldB OR fieldC OR fieldD , then it should be in the form  fieldA AND (fieldB OR fieldC OR fieldD)	
	This is done by comparing the operator in the $arrWhereAndOrOperation array . Parenthesis is needed if there is OR operator .
	If there is no any OR operator then no parenthesis is need.	
	NOTE: if the last element of this aarray $arrWhereAndOrOperation is 'OR' then, we need to manually append ')' at the end of the where clause  prepared from the user supplied values.
	*/	
	function applyParenthesis($arrWhereAndOrOperation){
	
		$arrStart=array();
		$arrEnd=array();
		for($i=0;$i<count($arrWhereAndOrOperation);$i++){
			if($arrWhereAndOrOperation[$i]=='OR'){
				
				if($arrWhereAndOrOperation[$i-1]=='AND'){					
					array_push($arrStart,$i-1);
				}
								
				if(($arrWhereAndOrOperation[$i+1]=='AND')&&($i+1<count($arrWhereAndOrOperation))){//if OR is the last element in the array then closing braces ') ' should be applied manually outside this function
					array_push($arrEnd,$i+1);
			//CODE below not necessary cases redundancy. But does not effect the correctness	
				//if($arrWhereAndOrOperation[$i+2]=='OR')
					//	array_push($arrStart,$i+1);				
				}
			
			}
		
		}//end for
		$arrStartEnd=array_intersect ($arrEnd,$arrStart);
		$arrStart=array_diff ($arrStart,$arrStartEnd);
		$arrEnd=array_diff ($arrEnd,$arrStartEnd);
		
		for($i=0;$i<count($arrWhereAndOrOperation);$i++){
			
			if(in_array($i,$arrStartEnd))
				$arrWhereAndOrOperation[$i]=') AND (';
			elseif(in_array($i,$arrStart))
				$arrWhereAndOrOperation[$i]=' AND (';
			elseif(in_array($i,$arrEnd))
				$arrWhereAndOrOperation[$i]=') AND ';
		
		}
		
		$str=implode(',',$arrWhereAndOrOperation);

		return $arrWhereAndOrOperation;
	}

	
	function updateMysqlUserPermission($action,$arrAllTabNames,$arrPermission,$userName,$host){
		//$this->query('use mysql');
		
		$DB=$this->getdbname();		
		$user="$userName@$host";	
		$tabPerm='';
		$tabTables=array();
		
		foreach($arrAllTabNames as $tab){
		$tabPerm='';//clean permission
		$tabTables=array();//clean array
		
			if(in_array($tab.'_add=y',$arrPermission))
				$tabPerm.='INSERT,';
			if(in_array($tab.'_edit=y',$arrPermission))
				$tabPerm.='UPDATE,';
			if(in_array($tab.'_view=y',$arrPermission))
				$tabPerm.='SELECT,';
			
			$tabPerm=substr_replace($tabPerm,"",-1);//remove the last character which is  "," 
			
			if($tab=='personal')
				$tabTables=array('name','inf_staff','family','surname','relation','nationality');
			elseif($tab=='address')
				$tabTables=array('address','name_address','name');
			elseif($tab=='email')
				$tabTables=array('email','name_email','name');
			elseif($tab=='phone')
				$tabTables=array('phone','name_phone','name');
			elseif($tab=='staff')
				$tabTables=array('inf_staff','relation');
			elseif($tab=='home assignment')
				$tabTables=array('home_assignment');
            elseif($tab=='orientation arrangement')
				$tabTables=array('orientation_arrangement');
			elseif($tab=='annual_review')
				$tabTables=array('review');
            else{
				$tabTables=array($tab);
			}
			
            //$this->test_file("updmysql",$tabTables);
			foreach($tabTables as $eachTable){
			
				$dbTable="$DB.$eachTable";
				$query='';
				$queryRevoke='';
				
				//FIRST revoke all permission for this user  if EDIT mode (i.e noe Add New mode)
				if($action	==	"Edit"){
				//if there is no any grants for $user for $dbTable then it throws error while Revoking
				//This  'grant select ' is done so as to avoid that error
					$query="GRANT SELECT on $dbTable to $user";
                    //$this->test_file("updmysql1",$query);
					$result=$this->query($query);
					if(!$result)   return false;
				//revoke all privileges 
					$queryRevoke="REVOKE SELECT,INSERT,UPDATE on $dbTable from $user";
					//$this->test_file("updmysql2",$queryRevoke);
                    $result=$this->query($queryRevoke);
					if(!$result)   return false;					
				}
				
				//NOW graant the newly assigned privileges	
				if($tabPerm!=''){
					$query="GRANT $tabPerm on $dbTable to $user";
                    //$this->test_file("updmysql3",$query);
					$result=$this->query($query);
					if(!$result)   return false;					
				}
			}//end foreach
			

		}//end foreach
		//$this->query("use $DB");
		return true;//successfully updated user permission in mysql privileges
	}
	
		
	function initaliseDbGrants($userType,$user,$host,$action){
/*
Sets permissions for user as per its type i.e if admin user then allow admin level (mostly insert,select,update)access  else allow non admin level access(mostly Select only).
If $action is 'Edit' then change the access level to admin or non-admin as per the $userType.
*/

		$DB=$this->getdbname();
		$adminOnlyUpdateTables=array('caste','country','course','course_subject_type','course_type','grade','hospital',
		'illness','leave_type','leaving_reason','location','movement_reason','nepali_day','organisation',
		'organisation_link','organisation_rep','organisation_type','post','programme','project',
		'qualification_type','referred_from','religion','requested_from','review_type','site','speciality_type',
		'staff_type','visa','visa_history','training_needs','users','security_role','security_permission',
		'security_role','security_role_permission','security_user_permission','change_log','unit','section','site');
		
		
		$hiddenTable=array('sync','sync_log');//No one should access these table
		
		$result = mysql_list_tables($DB);    
	    if (!$result) return false;

	    while ($row = mysql_fetch_row($result)) {

			
			//grant select for all non admin table  during 'Add New' action for 'Edit' action not necessary since already done in 'Add New' action
			if($action=='Add New'){
			
				if($userType=='admin'){
					if((in_array($row[0],$hiddenTable)))
						continue;
					else{
						if(in_array($row[0],$adminOnlyUpdateTables))
							$query="GRANT SELECT,INSERT,UPDATE ON $DB.$row[0] TO '$user'@'$host'";						
						else //for tables like email, name, 
							$query="GRANT SELECT ON $DB.$row[0] TO '$user'@'$host'";
						
						$result1 =$this->query($query);
						if(!$result1) return false;
					}
					
				}//end if($userType=='admin')
				else{//not admin 
					
					if(!(in_array($row[0],$hiddenTable))){
						$query="GRANT SELECT ON $DB.$row[0] TO '$user'@'$host'";
						$result1 =$this->query($query);
						if(!$result1) return false;
					}				
				}
			//all users users can insert into change_log	
				$query="GRANT SELECT,INSERT ON $DB.change_log TO '$user'@'$host'";
				$result1 =$this->query($query);
				if(!$result1) return false;
				
			//all users can insert in search_history table	
				$query="GRANT INSERT,SELECT ON $DB.search_history TO '$user'@'$host'";
				$result1 =$this->query($query);
				if(!$result1) return false;
			//all users can update in users table
				$query="GRANT UPDATE ON $DB.users TO '$user'@'$host'";
				$result1 =$this->query($query);
				if(!$result1) return false;
				
			}//end if($action='Add New')

			else{//edit action
		
				if($userType=='admin'){
					if((in_array($row[0],$hiddenTable)))
						continue;
					if(in_array($row[0],$adminOnlyUpdateTables)){
						$query="GRANT SELECT,INSERT,UPDATE ON $DB.$row[0] TO '$user'@'$host'";								
						$result1 =$this->query($query);
						if(!$result1)  return false;
					}
				}//end if($userType=='admin')
				else{
					if((in_array($row[0],$hiddenTable)))
						continue;
					if(in_array($row[0],$adminOnlyUpdateTables)){
		
						$query="REVOKE INSERT,UPDATE ON $DB.$row[0] FROM '$user'@'$host'";					
						$result1 =$this->query($query);
						if(!$result1) return false;
					}
				
				}
			
			}//end else		

		
	    }//end while
	
		return true;
	}
	//if fiscal year starts from shrawan 1 (of 6 April) get the full fiscal start date  (i.e. current fiscal date start converted in AD)
	function getFiscalYearStart($given_date=''){
		
		//$this->test_file("sussol lib fiscal start",$given_date);
		$convert_date=new DateConvert;
		
		if ($given_date == '')
			$timeStamp = time();
		else
			$timeStamp = strtotime($given_date);
		
		$today = date("Y-m-d",$timeStamp);
		$fiscalStart=date('Y-m-d');
		$query="select fiscal_year_start from site where id = 1";
		$result =$this->query($query);
		      
        $fiscal_start = '01-January';
        
		if($result){//if no result then  $fiscalYearShrawan=true;
			if (mysql_num_rows($result) > 0){
				$row = mysql_fetch_row($result);
                $fiscal_start = $row[0];
			}
		}
		
		if($fiscal_start == '01-Shrawan'){//if fiscal year starts at shrawan 1 then get the equivalent full AD date of current fiscal date start

			$year1=date('Y',$timeStamp) + 56;
			$year2=date('Y',$timeStamp) + 57;
			
			$date1	=	$convert_date->nepToEnglish($year1.'-04-01');
			$date2	=	$convert_date->nepToEnglish($year2.'-04-01');
			$date1	=	date( 'Y-m-d',strtotime($date1));
			$date2	=	date( 'Y-m-d',strtotime($date2));
			
			if($date2 > $today)
				$fiscalStart=$date1;
			else
				$fiscalStart=$date2;		
		}
        
		if($fiscal_start == '01-Baishakh'){//if fiscal year starts at shrawan 1 then get the equivalent full AD date of current fiscal date start

            $year1=date('Y',$timeStamp) + 56;
			$year2=date('Y',$timeStamp) + 57;
			
			$date1	=	$convert_date->nepToEnglish($year1.'-01-01');
			$date2	=	$convert_date->nepToEnglish($year2.'-01-01');
			$date1	=	date( 'Y-m-d',strtotime($date1));
			$date2	=	date( 'Y-m-d',strtotime($date2));
			
			if($date2 > $today)
				$fiscalStart=$date1;
			else
				$fiscalStart=$date2;		
		}
        
        if ($fiscal_start == '06-April') {
			$currentYear=date('Y',$timeStamp);
			$dateStr="06-04-".$currentYear;	
			$fiscalStart=date( 'Y-m-d',strtotime($dateStr));
			
			if($fiscalStart > $today){
			
				$currentYear=date('Y',$timeStamp) -1;
				$dateStr="06-04-".$currentYear;	
				$fiscalStart=date( 'Y-m-d',strtotime($dateStr));
			}
        } 
        
        if ($fiscal_start == '01-January') {
			$currentYear=date('Y',$timeStamp);
			$dateStr="01-01-".$currentYear;	
			$fiscalStart=date( 'Y-m-d',strtotime($dateStr));
			
			if($fiscalStart > $today){
			
				$currentYear=date('Y',$timeStamp) -1;
				$dateStr="01-01-".$currentYear;	
				$fiscalStart=date( 'Y-m-d',strtotime($dateStr));
			}
        }        
//$this->test_file("sussol lib fiscal start",$fiscal_start.": ".$fiscalStart);		
		return $fiscalStart;//returns date as yyyy-mm-dd
							//if format change then update on loaddata() in leaveCommon.as
	}
	
	function test_file($from,$value='')
	{
		$string="\n".$from." \n \n ".$value."\n";
		$fh = fopen('ztest_file.txt', "a"); 
		fwrite($fh, $string); 
		fclose($fh); 
	}

    function modify_tag($tag,$zzz,$source=0,$dotstring='_') {
    	
		$orig_tag = $tag;
		$dot = strrpos($tag,'.');
		if ($dot === false) { //fieldname only
			$tableName = 'none';
			$roottag = '';
		}
		else { //tablename.fieldname
			$tableName = substr($tag,0,$dot);
			$roottag = $tableName.$dotstring; //replace the '.' with $dotstring (Flex doesn't like dot in XML)
			$tag = substr($tag,$dot+1);	
		}
		$tag2 = str_replace('_id','',$tag); //default
		
        switch($tag){
            case 'id':
				if ($zzz) {$tag2 = 'zzzid';} else {$tag2 = '__id';}
                break;
            case 'name_id':
				if ($zzz) {$tag2 = 'zzzstaffid';} else {$tag2 = 'name_id';}
                break;
            case 'patient_id':
				if ($zzz) {$tag2 = 'zzzstaffid';} else {$tag2 = 'patient_id';}
                break;
			case 'caste_id':
				$tag2 = 'ethnicity';
				break;
            case 'medical_accepted_id':
                $tag2 = 'medical_accepted_by';
                break;
            case 'reviewed_by_id':
                $tag2 = 'reviewer';
                break;
            case 'interview_by':
                $tag2 = 'interviewer';
                break;
            case 'post_agreed_id':
				if ($tableName != 'secondment') {
					$tag2 = 'INF_role';
				}
				else {
					$tag2 = 'post';
				}
				break;
			case 'post_agreed_date':
                $tag2 = 'INF_role_agreed_date';
                break;
			case 'post_id':
                if ($tableName != 'visa_history') {
					$tag2 = 'INF_role';
				}
				else {
					$tag2 = 'visa_post';
				}
                break;
            case 'qualification_id':
            case 'speciality_id':
			case 'second_speciality_id':
                $tag2 = $tag2.'_type';
                break;
			//case 'issue_country_id':
            case 'birth_country_id':
                $tag2 = 'country';
                break;
            case 'visa_id':
				$tag2 = 'visa_post';
				$tableName = 'visa';
				break;
			case 'address_id':
            case 'email_id':
            case 'phone_id':
				//$tableName = str_replace('_id','',$tag);
                break;
        }
		
		//if ($source == 1) { //from page specific search
			if (strrpos($tag2,'zzz') === false)
				$tag2=$roottag.$tag2; //return tablename_fieldname
		//}
		
	
			
			

// 		if (false)
		if ($this->isNotAllowed($tableName,'view'))			
			if (($tag2 != 'zzzid') && ($tag2 != 'zzzstaffid') && ($tag2 != 'name_forenames'))
				$tag2 .='_disallowed';  //blank result if disallowed table
		//if (($_SESSION["tables"] == '') || (strpos ($_SESSION["tables"], ','.$tableName.','))) $tag2 .='_disallowed';  //blank result if disallowed table
		
        return $tag2;
    }                

    //Artur Neumann INF/N 26.07.2012 12:00
    //some perfomance improvements
    //see point 10: http://blogs.digitss.com/php/php-performance-improvement-tips/
    function escape_for_xml($row) {

        return str_replace(array("\\'", "\\\"", "\\\\", "\\0",'&','<','>','"',"'"),  array("'", "\"", "\\", "",'&amp;','&lt;','&gt;','&quot;','&apos;'), $row);
    
    }
    
    
    //Artur Neumann INF/N 27.07.2012 
    //this function is also called super often, and often with the same values
    //so I changed it to use an own array $this->process_tagTable to store the data. If the data
    //exists in the array it don't need to calculate again  
    function process_tag($tag,$row,$zzz=false,$source=0,$photoName='',$nameID='') { //$source == 1 (page specific), == 2 (search admin)
    
    	$cacheKey =  sha1($tag.$row.$zzz.$source.$photoName.$nameID);
    	 
    	if (isset($this->process_tagTable[$cacheKey])) {
//     		$this->test_file("process_tag_", "found in \$this->process_tagTable ");
    		
    	
    		return $this->process_tagTable[$cacheKey];
    	}

    	
        $newtag = '';
		$orig_tag = $tag;
        $tag2 = $this->modify_tag($tag,$zzz,$source);
		
		if (strpos($tag2,'_disallowed')) return '<'.$tag2.'></'.$tag2.'>'; //blank result if disallowed table
		
		/*
		$dot2 = strrpos($tag2,'.');
		if ($dot2 === false) { //fieldname only
			//$table = $tag2;
		}
		else {	//tablename.fieldname
			$table = substr($tag2,0,$dot2);
			$tag2 = substr($tag2,$dot2+1);
		}*/
		$dot = strrpos($tag,".");
		if ($dot === false) {  //fieldname only
			$table = str_replace('_id','',$tag);
		}
		else { //tablename.fieldname
			$table = substr($tag,0,$dot);
			$tag = substr($tag,$dot+1);
		}
        $field = 'name';
        $original_tag = ($source != 1); // not from page specific
        $extra_tag = true;
        $row = $this->escape_for_xml($row);
    
        switch($tag){
            case 'id':
            case 'name_id':
            case 'patient_id':
				$tag	= $tag2;
                $extra_tag  = false;
                $original_tag = true;
                break;
			case 'post_agreed_date':
				$tag = 'INF_role_agreed_date';
				$extra_tag  = false;
                $original_tag = true;
				break;
            case 'country_id':	
            case 'caste_id':
            case 'religion_id':
            case 'course_type_id':
            //case 'programme_id':
            case 'project_id':
            case 'section_id':
            case 'unit_id':
				$table = str_replace('_id','',$tag);
                $original_tag  = false;
                if ($source != 1) $tag2 = $tag; // not from page specific
                break;
            case 'course_subject_id':
                $table = 'course_subject_type';
                $original_tag  = false;
                if ($source != 1) $tag2 = $tag; // not from page specific
                break;
            case 'relative_id':
            case 'link_person_id':
            case 'school_children_link_person_id':
            case 'ktm_link_person_id':
            case 'pkr_link_person_id':
            case 'work_link_person_id':
            case 'housing_link_person_id':
            case 'medical_accepted_id':
            case 'reviewed_by_id':
            case 'interview_by':
                $table = 'name';
				$extra_tag = false;
                $newtag .= '<'.$tag2.'>'.$this->get_fullname($row).'</'.$tag2.'>';
                break;
            case 'address_id':
            case 'email_id':
            case 'phone_id':
				$table = str_replace('_id','',$tag);
                $field = $table;
                break;
            case 'reason_id':
                $table = 'movement_reason';
                break;
            case 'course_id': 
				$table = 'course';
                if ($source == 0) {
                    $newtag .= '<date_from>'.$this->get_table_item('course','date_from','id',$row).'</date_from>';
                    $newtag .= '<date_until>'.$this->get_table_item('course','date_until','id',$row).'</date_until>';
                }
                if ($source == 2) { //admin
                    $tag2 = $tag;
                    $original_tag = false;
                }
                break;
            case 'visa_id':
                if ($source == 2) { //admin
                    $tag2 = $tag;
                    $original_tag = false;
                }
                $table = 'visa';
                break;
			case 'programme_id':
				if ($source == 2) { //admin
                    $tag2 = $tag;
                    $original_tag = false;
                }
                $table = 'programme';
                break;
            case 'illness_id':
            case 'hospital_id':
            case 'review_type_id':
            case 'leave_type_id':
            case 'staff_type_id':
			case 'registration_type_id':
            case 'leaving_reason_id':
            case 'grade_id':
            case 'location_id':
            case 'requested_from_id':
            case 'treatment_category_id':
            case 'treatment_case_id':
            case 'treatment_result_id':
            case 'treatment_regimen_id':
            case 'referred_from_id':
            case 'health_staff_type_id':
				$table = str_replace('_id','',$tag);
				break;
            case 'issue_country_id':
			case 'passport_country_id':
            case 'birth_country_id':
				$table = 'country';
				break;
            case 'post_agreed_id':
			case 'post_id':
				$table = 'post';
				$original_tag  = true;
				$extra_tag = false;
				$result = $this->get_values("post","id","name,description","id",$row,false);
				foreach($result as $result=>$value){
					$newtag .= '<'.$tag2.'>'.str_replace(", ",": ",$value).'</'.$tag2.'>';
				}
                break;
            case 'seconded_from_id':
            case 'seconded_to_id':
			case 'organisation_id':
			case 'institution_id':
			case 'embassy_id':
			case 'company_id':
            case 'further_seconded_to_id':
            case 'local_support_id':
            case 'church_id':
            case 'osa_approval_agency_id':
            case 'fsa_approval_agency_id':
            case 'osa_approval_infw_id':
            case 'fsa_approval_infw_id':
                $table = 'organisation';
                break;
            case 'appliance_type_id':
				$table = 'patient_appliance_type';
                break;
            case 'surgery_type_id':
				$table = 'patient_surgery_type';
                break;
            case 'service_type_id':
                $table = 'patient_service_type';
                break;
            case 'qualification_id':
				$table = 'qualification_type';
                break;
			case 'speciality_id':
			case 'second_speciality_id':
                $table = 'speciality_type';
                break;
            case 'training_need_id':
                $table = 'personnel_training_needs';
                if ($source == 0) {
                    $original_tag = false;
                    $extra_tag = false;
                    $id_list = $this->get_comma_list('name_training_needs','training_need_id','training_id',$row);
                    $newtag .= '<'.$tag.'>'.$id_list.'</'.$tag.'>';
                    $newtag .= '<'.$tag2.'>'.$this->get_comma_list($table,'name','id',$id_list).'</'.$tag2.'>';
                }
                break;
            case 'organisation_type_id':
                $table = 'organisation_type';
                if ($source == 0) {
                    $original_tag = false;
                    $extra_tag = false;
                    $id_list = $this->get_comma_list('organisation_link','organisation_type_id','organisation_id',$row);
                    $newtag .= '<'.$tag.'>'.$id_list.'</'.$tag.'>';
                    $newtag .= '<'.$tag2.'>'.$this->get_comma_list($table,'name','id',$id_list).'</'.$tag2.'>';
                }
                break;
            case 'main_treatment_reason_id':
                $table = 'treatment_reason';
                break;
            case 'detail_treatment_reason_id':
                $table = 'treatment_reason';
                if ($source == 0) {
                    $original_tag = false;
                    $extra_tag = false;
                    $id_list = $this->get_comma_list('visit_treatment_reasons','detail_treatment_reason_id','visit_id',$row);
                    $newtag .= '<'.$tag.'>'.$id_list.'</'.$tag.'>';
                    $newtag .= '<'.$tag2.'>'.$this->get_comma_list($table,'name','id',$id_list).'</'.$tag2.'>';
                }
                break;
            case 'link':				
            case 'photo_link':
            case 'scan_link':
                $original_tag = ($row == '');
                $extra_tag = false;
                if (!$original_tag) {
                    $newtag .= '<'.$tag.'>'.$this->getUploadURL().'requestDownload.php?type=thumb&person='.$nameID.'&file='.$photoName.'</'.$tag.'>';
                }
				//just get the imaage source link which is copied from database to temporary location
                //$newtag .= '<'.$tag.'>'.$this->copyUserFiles($currentUserID,$nameID,$row).'</'.$tag.'>';
			/*	UNCOMMENT IF WE NEED TO COPY PICTURES TO TEMP DIR FROM PERMANENT DIR
				//Copy the image and get the temporary image source. Images are copied from permanent location to temporary location
				//$newtag .= '<org_'.$tag.'>'.$this->copyUserFiles($currentUserID,$nameID,$row,'Original_').'</org_'.$tag.'>';
			*/	
                break;
	/*		case 'photo':
				$upload_dir = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']) . '/';
	$this->test_file('sussollb PIC',$upload_dir);
				//file_put_contents('dwnload/image1.jpg',$bytes);
				break;
			*/
            default:
                $original_tag = true;
                $extra_tag = false;
                break;
        }
		
		if ($source == 1) { //from page specific search
			if (strrpos($tag2,'zzz') === false) {
				$tag=$table.'_'.$tag; //return tablename_fieldname
				//$tag2=$table.'_'.$tag2; //return tablename_fieldname
			}
		}
		
        if ($original_tag) $newtag .= '<'.$tag.'>'.$row.'</'.$tag.'>';
        if ($extra_tag) {
			$newtag .= '<'.$tag2.'>';
			if ($this->isNotAllowed($table,'view')) //(strpos ($_SESSION["tables"], ','.$table.','))
				$newtag .= 'disallowed';
			else
				$newtag .= $this->get_table_item($table,$field,'id',$row);
			$newtag .= '</'.$tag2.'>';
		}

/*
$fh = fopen('test.txt', "a"); 
fwrite($fh, $original_tag.", ".$extra_tag."\n");
fwrite($fh, $orig_tag.", ".$tag.", ".$tag2."\n");
fwrite($fh, $newtag."\n"); 
fwrite($fh, $table." : ".$field." : ".$row."\n"); 
fclose($fh); 
*/
		
		$this->process_tagTable[$cacheKey] = $newtag;
		
        return $newtag;
    }
		
	function dl_file($file){
	//source <http://php.net/manual/en/function.header.php> 
	    //First, see if the file exists
	    if (!is_file($file)) { die("<b>404 File not found!</b>"); }

	    //Gather relevent info about file
	    $len = filesize($file);
	    $filename = basename($file);
	    $file_extension = strtolower(substr(strrchr($filename,"."),1));

	    //This will set the Content-Type to the appropriate setting for the file
	    switch( $file_extension ) {
	          case "pdf": $ctype="application/pdf"; break;
	      case "exe": $ctype="application/octet-stream"; break;
	      case "zip": $ctype="application/zip"; break;
	      case "doc": $ctype="application/msword"; break;
	      case "xls": $ctype="application/vnd.ms-excel"; break;
	      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	      case "gif": $ctype="image/gif"; break;
	      case "png": $ctype="image/png"; break;
	      case "jpeg":
	      case "jpg": $ctype="image/jpg"; break;
	      case "mp3": $ctype="audio/mpeg"; break;
	      case "wav": $ctype="audio/x-wav"; break;
	      case "mpeg":
	      case "mpg":
	      case "mpe": $ctype="video/mpeg"; break;
	      case "mov": $ctype="video/quicktime"; break;
	      case "avi": $ctype="video/x-msvideo"; break;

	      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
	      case "php":
	      case "htm":
	      case "html":
	      case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

	      default: $ctype="application/force-download";
	    }

	    //Begin writing headers
	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	   
	    //Use the switch-generated Content-Type
	    header("Content-Type: $ctype");

	    //Force the download
	    $header="Content-Disposition: attachment; filename=".$filename.";";
	    header($header );
	    header("Content-Transfer-Encoding: binary");
	    header("Content-Length: ".$len);
	    @readfile($file);
	    exit;
	}

	
	function daysInWorkingWeek($name_id,$until=''){
	
		if ($until == '') $until = date('Y-m-d');
		
		$query=sprintf("select working_week from service where name_id=$name_id and deleted=0 and date_from < '".$until."' order by date_from DESC limit 1");
//$this->test_file('5 day query: '.$query);
		$queryResult = $this->query($query);

		if ((mysql_num_rows($queryResult))==1)
		{
			$row = mysql_fetch_assoc($queryResult);
			$workingWeek = $row['working_week']; 		
		}
		else
		{	
			$workingWeek=5;
		
			$query=sprintf("select name from site where id = 1");
			$result =$this->query($query);
		
			if($result){
				if (mysql_num_rows($result) > 0){
					$row = mysql_fetch_row($result);
					if ($row[0] != 'INF_Worldwide') {$workingWeek = 6;};
				}
			}
		}
		
		return  $workingWeek;
	}

	function getLeaveDays($date1, $date2, $fivedayweek) {

		$holidays = 0;
		$day1 = strtotime($date1);
		$day2 = strtotime($date2);
  
		for ($day = $day1; $day <= $day2; $day += 24 * 3600) 
		{
			$day_of_week = date('w', $day);
	
			if($day_of_week == 6) 
			{ //Saturday
				$holidays++;
			}
	
			if(($fivedayweek == 5) & ($day_of_week == 0)) 
			{ //Sunday
				$holidays++;
			}
		}
  
		return ($day2 - $day1)/(24*3600) +1 - $holidays;
	}
	
	function splitIfYearBoundary($leave_id,$date_from,$date_until)
	{	
		$newID = $leave_id;
		$dateFrom = strtotime($date_from);
		$yearStart = $this->getFiscalYearStart($date_until);
		$yearBoundary = strtotime($yearStart);
		//$this->test_file("Split leave record for $leave_id ($date_from to $date_until) at $yearStart?");
	
		if ($dateFrom < $yearBoundary) {// different fiscal year
			
			$query=sprintf("select name_id, date_from,date_until,leave_type_id,replacement,half_day_from,half_day_until from `leave` where id=$leave_id and deleted=0");
			//$this->test_file("Splitting leave record for $leave_id at $yearStart... ");
			$queryResult = $this->query($query);
	
			if ((mysql_num_rows($queryResult))==1)
			{
				$row = mysql_fetch_assoc($queryResult);
				
				$nameID=$row['name_id'];
				$startDate = $row['date_from'];	
				$endDate = $row['date_until'];				
				$halfDayStart=$row['half_day_from'];
				$working=$this->daysInWorkingWeek($nameID,$endDate);
				
				$yearEnd = date('Y-m-d',$yearBoundary - 24*3600);
				$days=$this->getLeaveDays($startDate,$yearEnd,$working) - ($halfDayStart/2);

				$query=sprintf("update `leave` set date_until = '".$yearEnd."', leave_days = '".$days."', half_day_until = 0 where id=$leave_id");
				//$this->test_file($query);
				$queryResult = $this->query($query);
				if ($queryResult) {

					$halfDayEnd=$row['half_day_until'];
					$leaveTypeID=$row['leave_type_id'];
					$replacement=$row['replacement'];
					
					$days=$this->getLeaveDays($yearStart,$endDate,$working) - ($halfDayEnd/2);
					
					$query=sprintf("INSERT INTO `leave` (id,name_id,date_from,date_until,leave_type_id,replacement,half_day_from,half_day_until,leave_days) VALUES ('','$nameID','$yearStart','$endDate','$leaveTypeID','$replacement','0','$halfDayEnd','$days')");
					//$this->test_file($query);
					$newID = $this->insertQuery($query);
				}
			}
			return $newID;
		}
	}

	function getPreviousFiscalYear($fiscal_date,$offset=0){

		$fiscal_start = '01-January'; //default
		$convert_date = new DateConvert;
	
		$query=sprintf("select fiscal_year_start from site where id = 1");
		$result =$this->query($query);
	
		if($result){
			if (mysql_num_rows($result) > 0){
				$row = mysql_fetch_row($result);
				$fiscal_start = $row[0];
			}
		}
	
		if(($fiscal_start == '01-Shrawan') | ($fiscal_start == '01-Baishakh'))
		{
			$nepDate = $convert_date->engToNepali($fiscal_date); //convert to Nepali - yyyy/mm/dd (Nep)
			//echo $fiscal_date." -> ".$nepDate;
			$s_date = explode('/', $nepDate);
			$fiscal_year = sprintf("%04d",$s_date[0]-$offset); //subtract $offset Nepali years
			$fiscal_month = $s_date[1];
			$fiscal_day = $s_date[2];
			//echo " -> ".$fiscal_year."/".$fiscal_month."/".$fiscal_day;
			$fiscal_date = $convert_date->nepToEnglish($fiscal_year."/".$fiscal_month."/".$fiscal_day); //convert back to yyyy/mm/dd (Eng)
			$fiscal_date = str_replace('/','-',$fiscal_date); // and then to yyyy-mm-dd
		}
		else
		{
			$s_date = explode('-', $fiscal_date);
			$fiscal_year = sprintf("%04d",$s_date[0]-$offset); //subtract $offset years
			$fiscal_month = $s_date[1];
			$fiscal_day = $s_date[2];
			$fiscal_date = $fiscal_year."-".$fiscal_month."-".$fiscal_day; 
		}

		return $fiscal_date;
	}
	
	public static function makeSafeFilename($file)
	{
		//Artur Neumann
		//INF W / INF N - Projects
		//25.10.2011 16:24
		//got from the joomla API to secure the file names
		//http://joomlacode.org/gf/project/joomla/scmsvn/?action=browse&path=/development/tags/1.6.x/1.6.3/libraries/joomla/filesystem/file.php&view=markup
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		return preg_replace($regex, '', $file);
	}
}

/* FOR TESTING PURPOSE ONLY*/	
/*
$string="testing: \n   $query";
$fh = fopen('test.txt', "w"); 
fwrite($fh, $string); 
fclose($fh); 
*/
?>