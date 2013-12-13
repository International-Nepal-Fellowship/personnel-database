<?php

	include_once (dirname(__FILE__).'/connect.php');
	$retVal="";
	$_POST=$dbi->prepareUserData($_POST);
	$nameID=$_POST['nameID'];
			
//get family addresses and ID 
	//$familyID=$dbi->get_family_id(15);
	$familyID=$dbi->get_family_id($nameID,true);
	$retVal.='<addresses><address data="0">None</address>';
	if ($dbi->isNotAllowed('address','view')===false) {
		$result =$dbi->get_values('address','id','address, city_town, state_province','family_id',$familyID,true);	
		foreach($result as $result=>$value){		
			$retVal.='<address data="'.$result.'">'.$value.'</address>';
		}			
	}	
	$retVal.="</addresses>";
	
//NOW for movement adddresses use the above result PLUS location tables addresses
	$retVal.='<movementlocaddresses><address data="0">None</address>';
	if ($dbi->isNotAllowed('movement','view')===false) {
		$result =$dbi->get_values('address','id','address, city_town, state_province','family_id',$familyID,true);	
		foreach($result as $result=>$value){		
			$retVal.='<address data="'.$result.'">'.$value.'</address>';
		}
		$result =$dbi-> get_values_2("address","id",'address, city_town, state_province',"type","Office","family_id",0);	
		foreach($result as $result=>$value){		

			if($result!=0)
				$retVal.='<address data="'.$result.'">'.$value.'</address>';				
		}
	}
	$retVal.="</movementlocaddresses>";

//get family phone and id
	$retVal.='<phones><phone data="0">None</phone>';
	if ($dbi->isNotAllowed('phone','view')===false) {
		$result =$dbi->get_values('phone','id','phone','family_id',$familyID,true);	
		foreach($result as $result=>$value){		
			$retVal.='<phone data="'.$result.'">'.$value.'</phone>';		
		}
	}	
	$retVal.="</phones>";	

//NOW for movement phone use the above result PLUS location tables phone
	$retVal.='<movementlocphones><phone data="0">None</phone>';
	if ($dbi->isNotAllowed('movement','view')===false) {
		$result =$dbi->get_values('phone','id','phone','family_id',$familyID,true);		
		foreach($result as $result=>$value){		
			$retVal.='<phone data="'.$result.'">'.$value.'</phone>';		
		}	
		$result =$dbi-> get_values_2("phone","id","phone","type","Office","family_id",0);	
		foreach($result as $result=>$value){		

			if($result!=0)
				$retVal.='<phone data="'.$result.'">'.$value.'</phone>';
		}
	}	
	$retVal.="</movementlocphones>";

	
//get family email and id
	$retVal.='<emails><email data="0">None</email>';
	if ($dbi->isNotAllowed('email','view')===false) {
		$result =$dbi->get_values('email','id','email','family_id',$familyID,true);
		foreach($result as $result=>$value){		
			$retVal.='<email data="'.$result.'">'.$value.'</email>';		
		}
	}
	$retVal.="</emails>";	


//NOW for movementemail use the above result PLUS location tablesemail
	$retVal.='<movementlocemails><email data="0">None</email>';
	if ($dbi->isNotAllowed('movement','view')===false) {
		$result =$dbi->get_values('email','id','email','family_id',$familyID,true);	
		foreach($result as $result=>$value){		
			$retVal.='<email data="'.$result.'">'.$value.'</email>';		
		}	
		$result =$dbi-> get_values_2("email","id","email","type","Office","family_id",0);	
		foreach($result as $result=>$value){		

			if($result!=0)
				$retVal.='<email data="'.$result.'">'.$value.'</email>';
		}
	}	
	$retVal.="</movementlocemails>";	
	
	$result =$dbi->get_relatives($nameID);
	$retVal.='<relatives><relative data="0">None</relative>';
    $retVal.='<relative data="'.$nameID.'">Self</relative>';
	foreach($result as $result=>$value){		
		$retVal.='<relative data="'.$result.'">'.$value.'</relative>';
	}	
	$retVal.="</relatives>";
	
	$retVal.="<need>";
	if ($dbi->isNotAllowed('training','view')===false) {
		$result =$dbi->get_values('training_needs','id','need','name_id',$nameID,false);	
		foreach($result as $result=>$value){		
			$retVal.=$value." ";
		}
	}	
	$retVal.="</need>";
	
	$dbi->disconnect();	
	
	print $retVal;

//$dbi-> test_file('requestFamilyDetails:'.$familyID."\n",$retVal);	

/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('searchfamily.txt', "a");
fwrite($fh, "details for: ".$nameID); 
fwrite($fh,"\n");
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/	

?>

