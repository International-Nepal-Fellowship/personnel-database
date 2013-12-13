<?php
	include_once (dirname(__FILE__).'/connect.php');
		$nameID		=	$_POST['nameid'];
		$startDate	=	$_POST['startDate'];
		$endDate	=	$_POST['endDate'];
		$action		=	$_POST['action'];
		$leaveID	=	$_POST['leaveid'];
		$replacement=	$_POST['replacement'];
		$leaveTypeID=	$dbi->set_value('leave_type','name',$_POST['leaveType']);
		$DB=$dbi->getdbname();
		
	if($action ==	"Add New"){			
			
		$query=sprintf("INSERT INTO `".$DB."`.`leave` (`id` ,`name_id` ,`date_from` ,`date_until` ,`leave_type_id` ,
		`replacement`)VALUES 
		('', '$nameID', '$startDate', '$endDate', '$leaveTypeID', '$replacement'
		)");
			
		$dbi->printTransactionInfo($dbi->query($query));
						
	}
	else if ($action == "Edit"){	
			
		$query=sprintf("UPDATE `".$DB."`.`leave` SET `date_from` = '".$startDate."',`date_until` = '".$endDate."',
		`leave_type_id` = '".$leaveTypeID."',`replacement` = '".$replacement."' WHERE `leave`.`id` =".$leaveID);
				
			
		$dbi->printTransactionInfo($dbi->query($query));
			
	}
	
	$dbi->disconnect();	

	
?>
