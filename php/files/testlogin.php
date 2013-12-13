<?php
include_once (dirname(__FILE__).'/connect.php');

$userName='admin';

$userInfo=$dbi->getUserPassword($userName);//UserInfo array =[user_id,role_id,password]
$retVal=$userInfo['forename'];

print $retVal;
$dbi-> test_file("\n login \n	\n",$retVal);
?>