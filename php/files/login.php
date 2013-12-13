<?php
include_once (dirname(__FILE__).'/connect4auth.php');
//salinate $_POST
$_POST=$dbiAuth->prepareUserData($_POST);
$userName=$_POST['username'];
$userPass=$_POST['password'];

//$userName='admin';
//$userPass='21232f297a57a5a743894a0e4a801fc3';
/*
$fh = fopen('ztest.txt', "w"); 
fwrite($fh, $userName); 
fclose($fh); 
*/
$userInfo=$dbiAuth->getUserPassword($userName);//UserInfo array =[user_id,role_id,password]
$retVal="";
	
if(($userInfo['password']==$userPass) && ($userPass != "")){
        
    if($dbiAuth->maintenance() && ($userInfo['security_role'] != "superadmin")) {
        $_SESSION["name"] = '';
        $_SESSION["role"] = '';
        $retVal.="allowLogin=maintenance";
    }
    else {

	  if ($userInfo['security_role'] == "disabled") {
		$_SESSION["name"] = '';
        $_SESSION["role"] = '';
        $retVal.="allowLogin=disabled";
	  } else {
		
        $_SESSION["name"] = $userName;
        $_SESSION["role"] = $userInfo['security_role'];
	
		//$retVal="true";
		$retVal.="allowLogin=true";		
		$retVal.=",role=".$userInfo['security_role'];
		$retVal.=",userForeName=".$userInfo['forename'];
		$retVal.=",userSurname=".$userInfo['surname'];
		$retVal.=",userEmail=".$userInfo['email'];
		$retVal.=",userID=".$userInfo['user_id'];
        $retVal.=",userNameID=".$userInfo['name_id'];
		$retVal.=",passwordChanged=".$userInfo['password_changed'];
		$retVal.=",userTimestamp=".$userInfo['timestamp'];
		$retVal.=",userIncompleteWarning=".$userInfo['incomplete_warning'];
        $retVal.=",searchID=".$userInfo['search_id'];
		$retVal.=",dashboard_search_1=".$userInfo['dashboard_search_1'];
		$retVal.=",dashboard_search_2=".$userInfo['dashboard_search_2'];
        $retVal.=",dashboard_search_3=".$userInfo['dashboard_search_3'];
		$retVal.=",dashboard_search_4=".$userInfo['dashboard_search_4'];
		$retVal.=",siteType=".$dbiAuth->getSiteName();
        $retVal.=",siteID=".$dbiAuth->getSiteID();
		$retVal.=",siteLocation=".$dbiAuth->getSiteLocation();
		$retVal.=",siteProgramme=".$dbiAuth->getSiteProgramme();
        $retVal.=",lastSyncTime=".$dbiAuth->getLastSyncTime();
        $retVal.=",lastSyncFrom=".$dbiAuth->getLastSyncFrom();
        $retVal.=",timeout=".$dbiAuth->getTimeout();
		
# CHECK THE SECURITY USER PERMISSION TABLE AS PER   security_user_permission.user_id=$userInfo['id']  IF ENTRY FOUND THEN USE IT ELSE CHECK THE  security_role_permission 
		$query="SELECT  security_permission.name, security_user_permission.allow_deny,security_user_permission.tab_name 
		FROM security_permission
		LEFT JOIN security_user_permission ON security_permission.id = security_user_permission.permission_id
		WHERE 
		((`security_permission`.`deleted` is NULL) OR (`security_permission`.`deleted` = '0') )
		AND ((`security_user_permission`.`deleted` is NULL) OR (`security_user_permission`.`deleted` = '0') )
		AND security_user_permission.user_id=".$userInfo['user_id'];
		$result=mysql_query($query);
		if($result)
		{
			//if(mysql_num_rows($result)==0)//no security_user_setting for this user so use group setting
			//	$retVal.=",personal_setting=group_setting";
			//else{	
			$retVal.=",personal_setting=true";
			while($row=mysql_fetch_assoc($result)){	
				$retVal.=",".$row['tab_name']."_".$row['name']."=".$row['allow_deny'];	
			}				
			//}
		}
	  }		
        //create a temporary directory .The directory is named as the userID	
        //$dbiAuth->creatDirectoryRecursive("../main/fileUploads/".$userInfo['user_id']);	//no longer needed
	}
}
else{
	// login failed
	//$retVal="false";
	$_SESSION["name"] = '';
	$_SESSION["role"] = '';
	$retVal.="allowLogin=false";
}
	
print $retVal;
//$dbiAuth-> test_file("\n login \n	\n",$retVal);
?>