<?php

	include_once (dirname(__FILE__).'/connect.php');
	$retVal="";
	$caller =$_POST['caller'];
    $familyID = $_POST['familyID'];
	//$table =	'organisation';
	switch($caller){
	
	case 'organisation':
    
        $retVal .= "<organisationtypes>".$dbi->get_comma_list('organisation_link','organisation_type_id','organisation_id',$familyID)."</organisationtypes>";
        
	case 'location':
	
		/*---for organization address and id*/
		$result =$dbi->get_values_2("address","id","address,city_town,state_province","type",$caller,"family_id",$familyID);
		
		$retVal.="<".$caller."addresses>";
		$retVal.='<address data="0">None</address>';
		
		foreach($result as $result=>$value){		

			if($result!=0)
				$retVal.='<address data="'.$result.'">'.$value.'</address>';				
		}	
		$retVal.="</".$caller."addresses>";
		
		/*---for organization phone and id*/
		$result =$dbi-> get_values_2("phone","id","phone","type",$caller,"family_id",$familyID);
		
		$retVal.="<".$caller."phones>";
		$retVal.='<phone data="0">None</phone>';
		
		foreach($result as $result=>$value){		

			if($result!=0)
				$retVal.='<phone data="'.$result.'">'.$value.'</phone>';				
		}	
		
		$retVal.="</".$caller."phones>";
		/*---for organization email and id*/
		$result =$dbi-> get_values_2("email","id","email","type",$caller,"family_id",$familyID);
		
		$retVal.="<".$caller."emails>";
		$retVal.='<email data="0">None</email>';
		
		foreach($result as $result=>$value){		

			if($result!=0)
				$retVal.='<email data="'.$result.'">'.$value.'</email>';				
		}	
		$retVal.="</".$caller."emails>";
		
	break;
	
	}
	$dbi->disconnect();	
//$dbi-> test_file('requestNewData:'."\n",$retVal);
	$retVal.="<caller>".$caller."</caller>";
	print $retVal;



/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('requestNewData.txt', "a");
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fwrite($fh, $familyID); 
fwrite($fh,"\n");
fclose($fh); 
*/	

?>

