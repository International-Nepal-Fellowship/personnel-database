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
		

	
/*---for caste and id*/

	$result =$dbi-> get_type("caste","id","name");		
		
	$retVal.="<casts>";	
	
	if(!$result)
		$retVal.="<caste></caste>";
	
	foreach($result as $result=>$value){
		
			$retVal.='<caste data="'.$result.'">'.$value.'</caste>';		
	
		}	
	$retVal.="</casts>";
	

	
	
	$dbi->disconnect();		
	
print $retVal;


?>

