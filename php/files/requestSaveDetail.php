<?php
include_once (dirname(__FILE__).'/connect.php');
		$action				=$_POST['action'];	
		$staffNumber		=$_POST['staffNumber'] ;	
		$startDate			=$_POST['startDate'] ;
		$probationEndDate	=$_POST['endDate'] ;
		$retirementDate		=$_POST['retirementDate'] ;
		$nextReviewDate		=$_POST['nextReviewDate'] ;
		$nameID				=$_POST['nameid'];
		$leavingDate		=$_POST['leavingDate'];
		$detailID			=$_POST['datailid'];
		
		$staffTypeID		=$dbi->set_value('staff_type','name',$_POST['staffType']);	
		$leavingReasonID	=$dbi->set_value('leaving_reason','name',$_POST['leavingReason']);
		
		
		if($action ==	"Add New"){		
		
				
		}
		else if ($action == "Edit"){	
		
		$query =("update inf_staff set 
		staff_number='".$staffNumber."', staff_type_id='".$staffTypeID."', start_date='".$startDate."', 
		probation_end_date='".$probationEndDate."', retirement_date='".$retirementDate."',
		leaving_reason_id='".$leavingReasonID."',leaving_date='".$leavingDate."',
		next_review_due='".$nextReviewDate."' where id=".$detailID);

		$dbi->printTransactionInfo($dbi->query($query));
		}		
		
	$dbi->disconnect();	
	
		
?>