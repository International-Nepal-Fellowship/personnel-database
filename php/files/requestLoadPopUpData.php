<?php
 /* Author Bidur */ 
 /*
USED BY service/sservice.mxml ,   
 */
	include_once (dirname(__FILE__).'/connect.php');
	$retVal.="<popUpData>";
	$retVal="	
		<posthours>Full-time</posthours>
		<posthours>Part-time</posthours>
		<poststatus>Permanent</poststatus>
		<poststatus>Temporary</poststatus>
		<posttype>Official</posttype>
		<posttype>Interanl</posttype>";	
	
	/*---for programme and id*/
	$result =$dbi-> get_type("programme","id","name");
	
	$retVal.="<programmes>";	
	foreach($result as $result=>$value){		
			$retVal.='<programme data="'.$result.'">'.$value.'</programme>';	
		}	
	$retVal.="</programmes>";
	$retVal.="</popUpData>";

	
	$dbi->disconnect();		
	
print $retVal;


?>

