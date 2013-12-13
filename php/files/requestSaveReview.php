<?php
	include_once (dirname(__FILE__).'/connect.php');
		$nameID		=	$_POST['nameid'];
		$reviewDate	=	$_POST['reviewDate'];
		$action		=	$_POST['action'];
		$reviewID	=	$_POST['reviewid'];
		$reviewedBy	=	$_POST['reviewedBy'];
		$description=	$_POST['description'];
		$DB=$dbi->getdbname();
//	$dbi-> test_file("Review \n",$reviewDate);		

				
	if($action ==	"Add New"){
	
			$query=sprintf("INSERT INTO `".$DB."`.`review` (`id`, `name_id`, `comments`, 
			`review_date`, `reviewed_by_id`) 
			VALUES ('','$nameID', '$description', '$reviewDate', '$reviewedBy')");				
			
			$dbi->printTransactionInfo($dbi->query($query));
						
	}
	else if ($action == "Edit"){	
			
			$query=sprintf("UPDATE `".$DB."`.`review` SET `comments` = '".$description."',
			`review_date` = '".$reviewDate."', 	`reviewed_by_id` = '".$reviewedBy."'
			WHERE `review`.`id` =".$reviewID  );		
			
			$dbi->printTransactionInfo($dbi->query($query));
			
	}
	
	$dbi->disconnect();	
	

	
?>
