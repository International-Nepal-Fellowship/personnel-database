<?php
include_once (dirname(__FILE__).'/connect.php');
$retVal="";
$emailData=array();
$addressData=array();
$phoneData=array();
$retVal='<changeLog>';

$query="SELECT `change_log`.`id`, `change_log`.`record_id`,`change_log`.`site_id`,`change_log`.`table`,`change_log`.`update_type`,`change_log`.`user_id`,`change_log`.`comment`, `change_log`.`timestamp`, `users`.`user_name`
FROM `change_log`, `users`
WHERE `users`.`id` = `change_log`.`user_id` ORDER BY  `change_log`.`timestamp` DESC ";
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)
			{
			while($row=mysql_fetch_array($result)){	
					$retVal.=	'<changeLog>';
					//$retVal.=	'<id>'.$row['id'].'</id>';
				//	$retVal.=	'<user_id>'.$row['user_id'].'</user_id>';
				//	$retVal.=	'<record_id>'.$row['record_id'].'</record_id>';
				//	$retVal.=	'<site_id>'.$row['site_id'].'</site_id>';
					$retVal.=	'<Comments>'.$row['comment'].'</Comments>';
					$retVal.=	'<Table>'.$row['table'].'</Table>';
					$retVal.=	'<Type>'.$row['update_type'].'</Type>';
					$retVal.=	'<User>'.$row['user_name'].'</User>';
					$retVal.=	'<Site>'.$row['site_id'].'</Site>';
					$retVal.=	'<Timestamp>'.$row['timestamp'].'</Timestamp>';
					$retVal.=	'</changeLog>';
				}
			}
		else
		$retVal.='<changeLog></changeLog>';
			//$retVal.='<changeLog><Comments>None</Comments><Table>None</Table><Type>None</Type><User>None</User><Timestamp>None</Timestamp></changeLog>';
		
		}
	else
		$retVal.='<changeLog><Comments>None</Comments><Table>None</Table><Type>None</Type><Site>None</Site><User>None</User><Timestamp>None</Timestamp></changeLog>';
	
$retVal.='</changeLog>';
echo $retVal;
?>