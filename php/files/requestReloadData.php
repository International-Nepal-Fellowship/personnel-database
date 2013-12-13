<?php
 /* Author Bidur */ 
 /*
USED BY service/sservice.mxml ,   
 */
	include_once (dirname(__FILE__).'/connect.php');
	$retVal="";
	
		
	/*---for staff type and id*/
	$result =$dbi-> get_type("staff_type","id","name");
	
	$retVal.="<staffTypes>";	
	foreach($result as $result=>$value){
		
			$retVal.='<staffType>'.$value.'</staffType>';		
	
		}	
	$retVal.="</staffTypes>";
	
		
	/*---for leaving reason and id*/
	$result =$dbi-> get_type("leaving_reason","id","name");
	
	$retVal.="<leavingReasons>";	
	foreach($result as $result=>$value){
		
			$retVal.='<reason>'.$value.'</reason>';		
	
		}	
	$retVal.="</leavingReasons>";
	
	
	/*---for grade and id*/
	$result =$dbi-> get_type("grade","id","name");
	
	$retVal.="<grades>";	
	foreach($result as $result=>$value){
		
			$retVal.='<grade>'.$value.'</grade>';		
	
		}	
	$retVal.="</grades>";
	
	/*---for post and id*/
	$result =$dbi-> get_type("post","id","title");
	
	$retVal.="<serviceposts>";	
	foreach($result as $result=>$value){
		
			$retVal.='<servicepost>'.$value.'</servicepost>';		
	
		}	
	$retVal.="</serviceposts>";
	
/*---for location and id*/
	$result =$dbi-> get_type("location","id","name");
	
	$retVal.="<servicelocations>";	

	foreach($result as $result=>$value){
		
			$retVal.='<servicelocation >'.$value.'</servicelocation>';		
			
		}	
	$retVal.="</servicelocations>";


	
	
	$dbi->disconnect();		
	
print $retVal;


?>

