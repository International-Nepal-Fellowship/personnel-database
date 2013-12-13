<?php

include_once (dirname(__FILE__).'/connect.php');

function get_leave_stats($name_id,$leave_date,$leave_type_id,$db) {
	
	$leave_used=0;
	$leave_entitlement=0;
	$leave_carry_forward=0;
	$leave_adjustment=0;
	$retval='<leave_stats>';
	
	$start_date = $db->getFiscalYearStart($leave_date); //start of same fiscal year
	$end_date = $db->getPreviousFiscalYear($start_date,-1); //add a year
	
	$query="SELECT sum(`leave_days`) as `leave_sum`, count(`name_id`) as `leave_records` from `leave` where name_id=$name_id and leave_type_id=$leave_type_id and date_from >= '".$start_date."' and date_from < '".$end_date."' and deleted=0 and replacement='No'";
	
	$queryResult = $db->query($query);
	if ($queryResult)
	{
		$row = mysql_fetch_assoc($queryResult);
		$leave_records=$row['leave_records'];
		if ($leave_records > 0)
		{
			$leave_used=$row['leave_sum'];
		}
	}
	$retval.='<leave_used>'.$leave_used.'</leave_used>';
	
	$query="SELECT `entitlement`, `carry_forward` from `leave_type` where id=$leave_type_id and deleted=0";
	
	$queryResult = $db->query($query);
	if (mysql_num_rows($queryResult) > 0)
	{
		$row = mysql_fetch_assoc($queryResult);
		$leave_entitlement=$row['entitlement'];
		$leave_carry_forward=$row['carry_forward'];
		$retval.='<leave_entitlement>'.$leave_entitlement.'</leave_entitlement>';
		$retval.='<leave_carry_forward>'.$leave_carry_forward.'</leave_carry_forward>';
	}
	else
	{
		$retval.='<leave_entitlement>0</leave_entitlement><leave_carry_forward>0</leave_carry_forward>';
	}
	
	$query="SELECT sum(`adjustment`) as `adjustment_sum`, count(`name_id`) as `adjustment_records` from `leave_adjustment` where name_id=$name_id and leave_type_id=$leave_type_id and adjust_date >= '".$start_date."' and adjust_date < '".$end_date."' and deleted=0";
	
	$queryResult = $db->query($query);
	if ($queryResult)
	{
		$row = mysql_fetch_assoc($queryResult);
		$adjustment_records=$row['adjustment_records'];
		//$db->test_file($adjustment_records.' adjustment records');
		if ($adjustment_records > 0)
		{
			$leave_adjustment=$row['adjustment_sum'];
		}
		else //No adjustment record -> calculate it from previous year's leave
		{
			$previous_fiscal_date = $db->getPreviousFiscalYear($start_date,1); //subtract a year
	
			$query="SELECT sum(`leave_days`) as `leave_sum`, count(`name_id`) as `leave_records` from `leave` where name_id=$name_id and leave_type_id=$leave_type_id and date_from >= '".$previous_fiscal_date."' and date_from < '".$start_date."' and deleted=0 and replacement='No'";
			//$db->test_file('adjustment query = '.$query);
			$queryResult = $db->query($query);
			if ($queryResult)
			{
				$row = mysql_fetch_assoc($queryResult);
				$leave_records=$row['leave_records'];
				//$db->test_file($leave_records.' previous leave records');
				if ($leave_records > 0)
				{
					$leave_adjustment=$leave_entitlement-$row['leave_sum'];
					//$db->test_file($leave_adjustment.' days adjustment');
					if ($leave_adjustment>$leave_carry_forward)
					{
						$leave_adjustment=$leave_carry_forward;
					}
				}
			}
		}
	}
	$retval.='<leave_adjustment>'.$leave_adjustment.'</leave_adjustment>';
	
	$retval.='</leave_stats>';

	return $retval;
}

$do      =	$_GET['do'];
$action	=	$_POST['action'];
$name_id   =	$_POST['nameID'];
$leave_date  =	$_POST['leaveDate'];
$leave_type_id   =	$_POST['leaveTypeID'];
$start_date  =	$_POST['startDate'];
$end_date  =	$_POST['endDate'];
$halfdays  =	$_POST['halfDays'];

if ($action == '') {$action = $do;}

//echo "<p>".$action."</p>";

$params = "Action = ".$action."\nName ID = ".$name_id."\nDate = ".$leave_date."\nType = ".$leave_type_id."\n";
	
switch($action){
	
	case 'Fix':
	
	//Split leave records which span fiscal year boundaries

	$query=sprintf("select id, name_id, date_from,date_until from `leave` where deleted=0 order by date_from");
	//echo "<p>".$query."</p>";
	$queryResult = $dbi->query($query);
	
	if (mysql_num_rows($queryResult) > 0)
	{
		while($row=mysql_fetch_assoc($queryResult))
		{
			$leave_id=$row['id'];
			$date_from=$row['date_from'];
			$date_until=$row['date_until'];
			$newID=$dbi->splitIfYearBoundary($leave_id,$date_from,$date_until);
		}
	}

	//Calculate leave days and update leave record
	$query=sprintf("select id, name_id, date_from,date_until,leave_type_id,replacement,half_day_from,half_day_until,leave_days from `leave` where deleted=0 order by date_from");
	//echo "<p>".$query."</p>";
	$queryResult = $dbi->query($query);
	
	if (mysql_num_rows($queryResult) > 0)
	{
		while($row=mysql_fetch_assoc($queryResult))
		{
			$leave_id=$row['id'];
			$leave_days=$row['leave_days'];
			$from=$row['date_from'];
			$to=$row['date_until'];
			$halfdays=$row['half_day_from']+$row['half_day_until'];
			$working=$dbi->daysInWorkingWeek($row['name_id'],$to);
			$days=$dbi->getLeaveDays($from,$to,$working) - ($halfdays/2);
			if ($days != $leave_days) {
				$query2=sprintf("update `leave` set leave_days = '".$days."' where id=$leave_id");
				$queryResult2 = $dbi->query($query2);
				echo "<p>Setting leave days for ID = ".$row['id'].": leave from ".$from." to ".$to." is ".$days." days, taking account of ".$halfdays." half days and a working week of ".$working." days</p>";
			}
		}
	}
	
	break;
	
	case 'CalcLeave':
	
		$retVal = get_leave_stats($name_id,$leave_date,$leave_type_id,$dbi);
		print $retVal;
	
	break;
	
	case 'CalcDays':
	
		$working=$dbi->daysInWorkingWeek($name_id,$end_date);
		$days=$dbi->getLeaveDays($start_date,$end_date,$working) - ($halfdays/2);
		$retVal = '<leave_days>'.$days.'</leave_days>';
		print $retVal;
	
	break;
}

/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('calctest.txt', "a");
fwrite($fh, "sending: ".$params); 
fwrite($fh, "returning: ".$retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/
	
?> 