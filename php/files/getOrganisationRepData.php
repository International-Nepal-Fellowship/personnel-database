<?php
include_once (dirname(__FILE__).'/connect.php');


$retVal.="<orepdatas>";
$query="SELECT id,organisation_rep_timestamp,organisation_id,name,email,known_as FROM organisation_rep where organisation_rep.deleted='0' order by name";
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)
			{
			while($row  = 	mysql_fetch_array($result)){	
				$retVal.="<orepdata>";
							
				$retVal.=	'<name>'.$row['name'].'</name>';	
				$retVal.=	'<organisation_id>'.$row['organisation_id'].'</organisation_id>';
				$retVal.=	'<email>'.$row['email'].'</email>';	
				$retVal.=	'<known_as>'.$row['known_as'].'</known_as>';
				$retVal.=	'<organisation_rep_timestamp>'.$row['organisation_rep_timestamp'].'</organisation_rep_timestamp>';
				$retVal.=	'<id>'.$row['id'].'</id>';
				$retVal.="</orepdata>";
				}
			}
		else
			$retVal.='<orepdata><name>None</name><email>None</email><organisation_id>0</organisation_id><organisation>None</organisation><id>0</id><known_as>None</known_as><organisation_rep_timestamp></organisation_rep_timestamp></orepdata>';
		}
	else
$retVal.='<orepdata><name>None</name><email>None</email><organisation_id>0</organisation_id><organisation>None</organisation><id>0</id><known_as>None</known_as><organisation_rep_timestamp></organisation_rep_timestamp></orepdata>';
$retVal.="</orepdatas>";	
//$dbi->test_file($retVal);
echo $retVal;
?>