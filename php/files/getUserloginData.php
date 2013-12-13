<?php
include_once (dirname(__FILE__).'/connect.php');
//salinate $_POST
$_POST=$dbi->prepareUserData($_POST);

$userName=$_POST['username'];
$userPass=$_POST['password'];


$userInfo=$dbi->getUserPassword($userName);//UserInfo array =[user_id,role_id,password]
$retVal.="";
	
	if($userInfo['password']==$userPass){
		//$retVal="true";
		$retVal.="allowLogin=true";
		$retVal.=",role=".$userInfo['security_role'];
#GET  THE USER PERMISSIONS AS PER THE GROUP SETTINGS
		$query="SELECT  security_permission.name, security_role_permission.allow_deny
		FROM security_permission
		LEFT JOIN security_role_permission ON security_permission.id = security_role_permission.permission_id
		WHERE 
		((`security_permission`.`deleted` is NULL) OR (`security_permission`.`deleted` = '0') )
		AND ((`security_role_permission`.`deleted` is NULL) OR (`security_role_permission`.`deleted` = '0') )
		AND role_id =".$userInfo['role_id'];
		$result=mysql_query($query);
		if($result)
		{
			while($row=mysql_fetch_assoc($result)){					
				$retVal.=",".$row['name']."=".$row['allow_deny'];			
			}
		}
		
# CHECK THE SECURITY USER PERMISSION TABLE AS PER   security_user_permission.user_id=$userInfo['id']  IF ENTRY FOUND THEN USE IT ELSE CHECK THE  security_role_permission 
		$query="SELECT  security_permission.name, security_user_permission.allow_deny,security_user_permission.tab_name 
		FROM security_permission
		LEFT JOIN security_user_permission ON security_permission.id = security_user_permission.permission_id
		WHERE  ((`security_permission`.`deleted` is NULL) OR (`security_permission`.`deleted` = '0') )
		AND ((`security_user_permission`.`deleted` is NULL) OR (`security_user_permission`.`deleted` = '0') )
		AND security_user_permission.user_id=".$userInfo['user_id'];
		$result=mysql_query($query);
		if($result)
		{
			if(mysql_num_rows($result)==0)//no security_user_setting for this user so use group setting
				$retVal.=",personal_setting=group_setting";
			else{	
				$retVal.=",personal_setting=true";
				while($row=mysql_fetch_assoc($result)){					
					$retVal.=",".$row['tab_name']."_".$row['name']."=".$row['allow_deny'];			
				}
			}
		}			
	}
	else{
		// login failed
		//$retVal="false";
		$retVal.="allowLogin=false";
	}
	
	print $retVal;
	//$dbi-> test_file("\n login \n	\n",$retVal);
?>