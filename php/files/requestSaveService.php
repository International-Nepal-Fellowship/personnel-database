<?php
	include_once (dirname(__FILE__).'/connect.php');
	
		$name_id			=	$_POST['nameid'];
		$working_week		=	$_POST['workingWeek'];
		$special_contract	=	$_POST['specialContract'];
		$percentage_time	=	$_POST['percentageTime'];
		$start_date			=	$_POST['startDate'];
		$end_date			=	$_POST['endDate'];
		$action				=	$_POST['action'];	
		$srv_id				=	$_POST['sid'];
		$post_id			=	$dbi->set_value('post','title',$_POST['post']);
		$grade_id			=	$dbi->set_value('grade','name',$_POST['grade']);
		$location_id		=	$dbi->set_value('location','name',$_POST['location']);
		$DB=$dbi->getdbname();
	
	
	if($action ==	"Add New"){			
						
			$query=sprintf("INSERT INTO `".$DB."`.`service` (`id`, `name_id`, `post_id`, `grade_id`, 
			`location_id`, `date_from`, 	 
			`date_until`, `percent_of_time`, `special_contract`, `working_week`)
			VALUES ('', '$name_id', '$post_id',	'$grade_id','$location_id', '$start_date', '$end_date', 
			'$percentage_time', '$special_contract', '$working_week'
			)");			
			$dbi->printTransactionInfo($dbi->query($query));
					
	}
	else if ($action == "Edit"){	
	
			$query=sprintf("UPDATE `".$DB."`.`service` SET `date_from` = '".$start_date."',
			`date_until` = '".$end_date."',`working_week` = '".$working_week."',
			`percent_of_time` = '".$percentage_time."',`special_contract` = '".$special_contract."',
			`location_id` = '".$location_id."',`grade_id` = '".$grade_id."',`post_id` = '".$post_id."' 
			WHERE `service`.`id` =".$srv_id);	
			
			$dbi->printTransactionInfo($dbi->query($query));
			
	}
	
	$dbi->disconnect();	
		
?>