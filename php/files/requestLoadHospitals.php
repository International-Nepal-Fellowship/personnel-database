<?php
 /* Author Bidur */ 
 /*
USED BY service/hospitalization
 */
	include_once (dirname(__FILE__).'/connect.php');
	$retVal="";
	/*-- hospital_id and  name --*/
	$result=$dbi->get_values("hospital","id","name","country_id",$_POST['countryID']);	

	$retVal.="<hospitals>";
	foreach($result as $result=>$value){		
			$retVal.='<hospital data="'.$result.'">'.$value.'</hospital>';		
		}	
	$retVal.="</hospitals>";
	

	$dbi->disconnect();	
	
print $retVal;


/* FOR TESTING PURPOSE ONLY*/	
/*		
$string="testing: \n   cid:	$country_id";
$fh = fopen('test.txt', "w"); 
fwrite($fh, $string); 
fclose($fh); 
*/

?>

