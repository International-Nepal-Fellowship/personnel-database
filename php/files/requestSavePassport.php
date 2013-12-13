<?php
	include_once (dirname(__FILE__).'/connect.php');
	
		$nameID=$_POST['nameid'];
		$number=$_POST['passportNumber'];
		$countryID=$_POST['country'];
		$issueCity=$_POST['city'];
		$issueDate=$_POST['issueDate'];
		$expiryDate=$_POST['expiryDate'];
		$visaID=$_POST['visaNumber'];
		$photoName=$_POST['photoName'];
		$scanName=$_POST['scanName'];
		$action	=$_POST['action'];
		$scanLink=$_POST['scanLink'];
		$photoLink=$_POST['photoLink'];
	
	
	
		//	find the photo and scan uploaded directory	
		$upload_dir = $_SERVER['DOCUMENT_ROOT'] .  dirname($_SERVER['PHP_SELF']) . '/';
		$upload_url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) . '/';
		
		
		
		//change the images to blob format
		if($scanName!=''){//may be empty sometimes i.e.  during  edit mode when no new photo is uploaded
			$scan_path = $upload_dir."imageUploads/".$scanName;
			$fh = fopen($scan_path, "r");
			$scan_data= addslashes(fread($fh, filesize($scan_path)));
			fclose($fh);
		}
		if($photoName!=''){//may be empty sometimes during edit mode when no new scan is uploaded
			$photo_path = $upload_dir."imageUploads/".$photoName;
			$fh = fopen($photo_path, "r");
			$photo_data= addslashes(fread($fh, filesize($photo_path)));
			fclose($fh);
		}
			
		
	$dbi->query("Start transaction");
	$ok = true;
	$retVal = $action."\n";		
		
	if($action ==	"Add New") {

		$query="INSERT INTO passport(id,name_id,number,issue_date,expiry_date,issue_city,issue_country_id,visa_id,scan,photo,scan_link,photo_link) 
		VALUES ('',$nameID,$number,'$issueDate','$expiryDate','$issueCity',$countryID,$visaID,'$scan_data','$photo_data','$scanLink','$photoLink')";			
		$retVal.=$query."\n";
		$ok = ($dbi->isInteger( $dbi->query($query)));
								 
	}
	
	else if ($action == "Edit") {
	
	
		$passportID		=	$_POST['passportid'];
		
		if(($scanName!='')&&($photoName!='')){
			$query	=	"UPDATE passport set number='$number',scan_link='$scanLink',scan='$scan_data',photo_link='$photoLink', 
			issue_date='$issueDate', expiry_date='$expiryDate',issue_city='$issueCity',issue_country_id='$countryID',visa_id='$visaID',
			photo='$photo_data' where id='$passportID'";
		}
		else if($photoName==''){
			$query	=	"UPDATE passport set number='$number',scan_link='$scanLink', issue_country_id='$countryID',
			issue_date='$issueDate', expiry_date='$expiryDate',issue_city='$issueCity',visa_id='$visaID',scan='$scan_data'
			where id='$passportID'";
		}
		else if($scanName==''){
			$query	=	"UPDATE passport set number='$number',photo_link='$photoLink', 
			issue_date='$issueDate', expiry_date='$expiryDate',issue_city='$issueCity',issue_country_id='$countryID',visa_id='$visaID',
			photo='$photo_data' where id='$passportID'";
		}
		else{//scan and photo not uploaded
		
			$query	=	"UPDATE passport set number='$number',
			issue_date='$issueDate', expiry_date='$expiryDate',issue_city='$issueCity',
			issue_country_id='$countryID',visa_id='$visaID'
			 where id='$passportID'";
		}
		
		$retVal.=$query."\n";
	
		$ok = ($dbi->isInteger( $dbi->query($query)));
	}	
		
	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
		
		
	
		
		
		
		
		
		
		
		$dbi->disconnect();
	
	/* FOR TESTING PURPOSE ONLY*/	
/*	
$string="testingENd: \n $countryID";
$fh = fopen('test.txt', "w"); 
fwrite($fh, $string); 
fclose($fh); 
		
	*/	
	
?>