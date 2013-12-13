<?php
include_once (dirname(__FILE__).'/connect.php');

$modeState	=	$_POST['modeState'];
$permissionType=$_POST['permissionType'];

//$modeState	=	'Edit';
//$permissionType='group';

$retVal='';
if ($dbi->isSuperAdmin()) {

switch($permissionType)
{
case 'user':

	switch($modeState)
	{
		case 'Edit':
			$userID=$_POST['userID'];
			$retVal.= $dbi-> getUserPermission($userID);
		break;
		
		case 'View':
			$userID=$_POST['userID'];
			$retVal.= $dbi-> getUserPermission($userID);			
		break;
	}
	
break;

case 'group':
	
	switch($modeState)
	{
		case 'Add New':
			$groupID=$_POST['groupID'];
			$allTabNames=$_POST['allTabNames'];//string of all tabs delinited by comma
			$retVal.=$dbi->getGroupPermission($groupID);
			
			//$dbi->test_file($groupID);
		break;

		case 'Edit':
			$userID=$_POST['userID'];
			$groupID=$_POST['groupID'];
			$userName=$_POST['userName'];
            $dbi->query("Start transaction");
			$dbi->changeUserGroup($userID,$groupID,$userName,'localhost','Edit');
			$retVal.= $dbi-> getUserPermission($userID);
            $dbi->query("ROLLBACK");
		break;
	}
	
break;	
}		
}		
print $retVal;
/*
$fh = fopen('userperm.txt', "a");
fwrite($fh, "mode: ".$modeState." - .".$permissionType.", user: ".$userID."\n".$retVal."\n");
fclose($fh);
*/
//$dbi->test_file("mode:$modeState, user:$userID\n",$retVal);

?>