<?php
 /* Author Bidur */ 
 /*
USED BY service/hospitalization
 */
	include_once (dirname(__FILE__).'/connect.php');
	$retVal="";

	//$forename=array();
	//$surname=array();
	$nameID=$_POST['nameID'];
	$result =$dbi->get_relatives($nameID);
	$retVal.="<relatives>";
	foreach($result as $result=>$value){		
			$retVal.='<relative data="'.$result.'">'.$value.'</relative>';		
		}	
	$retVal.="</relatives>";	

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

