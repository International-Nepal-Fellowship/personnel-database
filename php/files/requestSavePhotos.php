<?php
	include_once (dirname(__FILE__).'/connect.php');
	set_error_handler("myErrorHandler");
		
		
		$photoName=$_POST['photoName'];
		$name_id=$_POST['nameid'];
		$description=$_POST['description'];	
		$link=$_POST['link'];
		$action=$_POST['action'];
	
	if($photoName!=''){//may be empty sometimes i.e. during edit mode when no new photo is uploaded
	
		//	find the photo and scan uploaded directory	
		$upload_dir = $_SERVER['DOCUMENT_ROOT'] .  dirname($_SERVER['PHP_SELF']) . '/';
		$upload_url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) . '/';
		
		//change the images to blob format
		//$photo_path = $upload_url."imageUploads/".$photoName;		
		$photo_path = $upload_dir."imageUploads/".$photoName;
		$fh = fopen($photo_path, "r");
		$photo_data= addslashes(fread($fh, filesize($photo_path)));
		fclose($fh);
	}
	
//$dbi-> test_file("requestSavePhotos\n  $action: \n",'');		
	
	$dbi->query("Start transaction");
	$ok = true;
	$retVal = $action."\n";		
		
	if($action ==	"Add New") {
		
	

		$query="INSERT INTO photo(id,name_id,description,link,photo) VALUES ('',$name_id,'$description','$link','$photo_data')";			
		$retVal.=$query."\n";
		$ok = ($dbi->isInteger( $dbi->query($query)));
								 
	}
	else if ($action == "Edit") {
		
		$photoID=$_POST['photoid'];
	
	
		if($photoName!=''){
			$query="UPDATE photo set description='$description',link='$link',photo='$photo_data' where id='$photoID'";
		}
		else{
		$query="UPDATE photo set description='$description' where id='$photoID'";
		}
		
//$dbi-> test_file("requestSavePhotos \n",$query);
		$retVal.=$query."\n";
			
		$ok = ($dbi->isInteger( $dbi->query($query)));
	}	
		
	if ($ok)			
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else
		$dbi->query($dbi->printTransactionInfo($query));// force rollback
		
		
	
		$dbi->disconnect();
	
	
?>
