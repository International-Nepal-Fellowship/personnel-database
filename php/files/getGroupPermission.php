<?php
include_once (dirname(__FILE__).'/connect.php');

$_POST=$dbi->prepareUserData($_POST);

$groupID	=	$_POST['groupID'];
//$groupID	=1;
$retVal='';
$retVal.="personal_setting=group_setting,";
if ($dbi->isSuperAdmin()) {
	$retVal.=$dbi->getGroupPermission($groupID);
}	
print $retVal;
//$dbi->test_file("group:$groupID\n",$retVal);

?>