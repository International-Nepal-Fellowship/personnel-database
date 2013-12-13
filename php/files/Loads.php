<?php
 /* Author Shashi */ 
	include_once (dirname(__FILE__).'/connect.php');
	$retVal="";	
	
	//return a list of all the users		
	$result =$dbi-> get_staff_name_id();
	
		
	
	$retVal.="<users>";	
	
	if(!$result)
	 $retVal.="<username></username>";
	
	 
/*---for username and id*/
	foreach($result as $result=>$value){
		
			$retVal.='<username data="'.$result.'">'.$value.'</username>';		
	
		}		
	$retVal.="</users>";
	
/*---for country and id*/
	$result =$dbi-> get_type("country","id","name");
	
	
	$retVal.="<countries>";
	$retVal.='<country data="Please Select">Please Select</country>';
	foreach($result as $result=>$value){
		
			$retVal.='<country data="'.$result.'">'.$value.'</country>';		
	
		}	
	$retVal.="</countries>";	
	
/*---for religions and id*/
	$result =$dbi-> get_type("religion","id","name");
	
	
	$retVal.="<religions>";
	
	if(!$result)
		$retVal.="<religion></religion>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<religion data="'.$result.'">'.$value.'</religion>';		
	
		}	
	$retVal.="</religions>";
		
/*---for leave_type and id*/
	$result =$dbi-> get_type("leave_type","id","name");
	
		
	$retVal.="<leaveTypes>";	
	if(!$result)
		$retVal.="<leaveType></leaveType>";
	
	foreach($result as $result=>$value){
		
			$retVal.='<leaveType data="'.$result.'">'.$value.'</leaveType>';		
	
		}	
	$retVal.="</leaveTypes>";
	
/*---for caste and id*/

	$result =$dbi-> get_type("caste","id","name");		
		
	$retVal.="<casts>";	
	
	if(!$result)
		$retVal.="<caste></caste>";
	
	foreach($result as $result=>$value){
		
			$retVal.='<caste data="'.$result.'">'.$value.'</caste>';		
	
		}	
	$retVal.="</casts>";
	
/*---for staff type and id*/
	$result =$dbi-> get_type("staff_type","id","name");
	
		
	$retVal.="<staffTypes>";	
	
	if(!$result)
		$retVal.="<staffType></staffType>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<staffType>'.$value.'</staffType>';		
	
		}	
	$retVal.="</staffTypes>";
	
/*---for programme and id*/
	$result =$dbi-> get_type("programme","id","name");
		
	$retVal.="<programmes>";	
	
	if(!$result)
		$retVal.="<programme></programme>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<programme data="'.$result.'">'.$value.'</programme>';		
	
		}	
	$retVal.="</programmes>";
	
	
/*---for title  and number from visa table*/
	$result =$dbi-> get_type("visa","id","title");
	
	$retVal.="<visaIdTitle>";	
	
	if(!$result)
		$retVal.="<idTitle></idTitle>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<idTitle data="'.$result.'">'.$value.'</idTitle>';		
	
		}	
	$retVal.="</visaIdTitle>";
	
/*---for leaving reason and id*/
	$result =$dbi-> get_type("leaving_reason","id","name");
	
		
	$retVal.="<leavingReasons>";	
	
	if(!$result)
		$retVal.="<reason></reason>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<reason>'.$value.'</reason>';		
	
		}	
	$retVal.="</leavingReasons>";
	
	
/*---for grade and id*/
	$result =$dbi-> get_type("grade","id","name");
		
	$retVal.="<grades>";	
	
	if(!$result)
		$retVal.="<grade></grade>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<grade>'.$value.'</grade>';		
	
		}	
	$retVal.="</grades>";
	
/*---for post and id*/
	$result =$dbi-> get_type("post","id","title");
	
	$retVal.="<serviceposts>";	
	
	if(!$result)
		$retVal.="<servicepost></servicepost>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<servicepost>'.$value.'</servicepost>';		
	
		}	
	$retVal.="</serviceposts>";
	
/*---for location and id*/
	$result =$dbi-> get_type("location","id","name");
	
	$retVal.="<servicelocations>";	
	
	if(!$result)
		$retVal.="<servicelocation></servicelocation>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<servicelocation>'.$value.'</servicelocation>';		
	
		}	
	$retVal.="</servicelocations>";

/*-- name_id and full name --*/
	
	$result=$dbi->get_staff_name_id();
	
	$retVal.="<idFullNames>";
	
	if(!$result)
		$retVal.="<idFullName></idFullName>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<idFullName data="'.$result.'">'.$value.'</idFullName>';		
	
		}	
	$retVal.="</idFullNames>";
	
/*-- illness id and full name --*/

	$result =$dbi-> get_type("illness","id","name");
	
	$retVal.="<illnesses>";	
	
	if(!$result)
		$retVal.="<illness></illness>";	
		
	foreach($result as $result=>$value){
		
			$retVal.='<illness>'.$value.'</illness>';		
	
		}	
	$retVal.="</illnesses>";
	
	
	
	$dbi->disconnect();		
	
print $retVal;


?>

