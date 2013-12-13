<?php
/*Author Bidur*/

$inc_string = $_SERVER['DOCUMENT_ROOT'];
$inc_string .= str_replace('/files/','/conf/',dirname($_SERVER['PHP_SELF']).'/');	
include_once ($inc_string.'DBIConnect.php');
 
class AuthLibrary extends DBIConnect
{	
 	
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
			$postArray[$key] = $this->addslashes_once($value);
			//$postArray[$key] = addslashes_once($value);
			$postArray[$key] = mysql_real_escape_string($value);
	    }	
		
        return $postArray;
	} 
	
	function getUserPassword($userName){
		
		/*
		$fh = fopen('ztest_file.txt', "a"); 
		fwrite($fh, 'username:' . $userName); 
		fclose($fh); 
		
		$arr=array();
		
		$arr['role']='admin' ;
		$arr['userForeName']='';
		$arr['userSurname']='' ;
		$arr['userEmail']='' ;
		$arr['userID']='2' ;
		$arr['passwordChanged']='0' ;
		$arr['userTimestamp']='2009-11-20 07:43:29' ;
		$arr['userIncompleteWarning']='No' ;
		$arr['searchID']='102' ;
		return $arr;
		*/
		//$query="Select password from users where user_name=".$userName;		
		$query="Select users.id as user_id,users.timestamp,users.password_changed,users.search_id,users.role_id,users.name_id,users.password,
		security_role.name as security_role,users.name as forename, users.lastname as surname, users.email, 
		users.incomplete_warning, users.dashboard_search_1, users.dashboard_search_2, users.dashboard_search_3, users.dashboard_search_4 from users 
		LEFT JOIN security_role ON users.role_id = security_role.id
		where users.user_name='".$userName."' AND users.deleted='0' and security_role.deleted='0'";		
		$result=mysql_query($query);	
		if ($result)
		{
			$row = mysql_fetch_assoc($result);	
			return $row;		
		}	
//test_file('getUserPassword($userName):'. $userName,$query);
		return "";
		
	}	
		
	function getSiteName(){	
    
		$DB=$this->getdbname();
		//$DB=getdbname();
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
    
	function getSiteID(){
		$DB=$this->getdbname();
		//$DB=getdbname();
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
		//$DB=getdbname();
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
	//$DB=getdbname();
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

	function getSiteLocation(){
			
		$DB=$this->getdbname();
		$query = "SELECT `location`.`name` FROM `".$DB."`.`location` LEFT JOIN `".$DB."`.`site` ON `site`.`location_id` = `location`.`id` where `site`.`id`=1";
		$queryResult= $this->query($query);
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
				//$this->test_file($query);
                return $row['name'];
            }
            else
                return '';
        }
        else{	
            echo mysql_error();
			return '';
        }	
	}
	
	function getSiteProgramme(){
			
		$DB=$this->getdbname();
		$query = "SELECT `programme`.`name` FROM `".$DB."`.`programme` LEFT JOIN `".$DB."`.`site` ON `site`.`programme_id` = `programme`.`id` where `site`.`id`=1";
		$queryResult= $this->query($query);
	
        if($queryResult){
            if (mysql_num_rows($queryResult) > 0){
                $row=mysql_fetch_assoc($queryResult);
				//$this->test_file($query);
                return $row['name'];
            }
            else
                return '';
        }
        else{	
            echo mysql_error();
			return '';
        }	
	}
	
	function creatDirectoryRecursive($dirName, $rights=0777){
		
		$dirs = explode('/', $dirName);
		$dir='';
		foreach ($dirs as $part) {
			$dir.=$part.'/';
			if (!is_dir($dir) && strlen($dir)>0)
				mkdir($dir, $rights);
		}
	}

	function test_file($from,$value='')
	{
		$string="\n".$from." \n \n ".$value."\n";
		$fh = fopen('ztest_file.txt', "a"); 
		fwrite($fh, $string); 
		fclose($fh); 
	}	
	
}
?>
