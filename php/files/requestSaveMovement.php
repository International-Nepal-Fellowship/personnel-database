<?php
	include_once (dirname(__FILE__).'/connect.php');
		$action		=	$_POST['action'];
		$movementID	=	$_POST['movementid'];
		$nameID		=	$_POST['nameid'];
		$startDate	=	$_POST['startDate'];
		$endDate	=	$_POST['endDate'];
		$datesFixed	=	$_POST['fixedDates'];
		$travelFrom	=	$_POST['movementFrom'];
		$travelTo	=	$_POST['movementTo'];
		//$emailID	=	$_POST['email'];
	//	$addressID	=	$_POST['address'];
		//$phoneID	=	$_POST['phone'];
		$notes		=	$_POST['notes'];
		$reason		=	$_POST['reason'];
		
		$phoneID	=	$dbi->set_value('phone','phone',$_POST['phone']);	
		$addressID	=	$dbi->set_value('address','address',$_POST['address']);
		$emailID	=	$dbi->set_value('email','email',$_POST['email']);
		$DB=$dbi->getdbname();	



	
	if($action ==	"Add New"){
	
			
			$query=sprintf("INSERT INTO `".$DB."`.`movement` 
			(`id`, `name_id`, `address_id`, `email_id`, `phone_id`, `date_from`, `date_until`,
			`dates_fixed`, `travel_to`, `reason`, `notes`, `travel_from`) 
			VALUES
			('', '$nameID', '$addressID', '$emailID', '$phoneID', '$startDate', '$endDate', 
			'$datesFixed', '$travelTo', '$reason', '$notes', '$travelFrom')");
			
			$dbi->printTransactionInfo($dbi->query($query));
			
//	$dbi-> test_file('Movement',$query);			
			
	}
	else if ($action == "Edit"){	
						
			$query=sprintf("UPDATE `".$DB."`.`movement` SET 
			`address_id` = '".$addressID."',`email_id` = '".$emailID."',`phone_id` = '".$phoneID."',
			`date_from` = '".$startDate."',`date_until` = '".$endDate."',`dates_fixed` = '".$datesFixed."',
			`travel_to` = '".$travelTo."',`reason` = '".$reason."',`notes` = '".$notes."',
			`travel_from` = '".$travelFrom."' WHERE `movement`.`id` =".$movementID);			
			$dbi->printTransactionInfo($dbi->query($query));
			
	//$dbi-> test_file('Movement',$query);		
	}
	
	$dbi->disconnect();	
	

	
?>
