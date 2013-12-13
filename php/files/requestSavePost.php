<?php

	include_once (dirname(__FILE__).'/SussolLibrary.php');
	include_once (dirname(__FILE__).'/connect.php');
	
	$dbi=new SussolLibrary();
			
	if(!$dbi->isConnected())
		$dbi->connect_default();
		
		$nameID=$_POST['infstaffid'];		
		$title=$_POST['title'];
		$type=$_POST['type'];
		$programmeID=$_POST['programmeid'];
		$description=$_POST['description'];		
		$status=$_POST['status'];
		$hours=$_POST['hours'];
		
		$visaID=$dbi->get_visa_id($nameID);			
		$emailID=$dbi->get_email_id($nameID);
		
		
		$query=sprintf("INSERT INTO post(id,email_id,visa_id,title,programme_id,type,status,hours,description)
		values($nameID,$emailID,$visaID,'$title',$programmeID,'$type','$status','$hours','$description')");
		
		$postID=$dbi->insertQuery($query);
	
		$query=sprintf("INSERT INTO name_post(name_id,post_id) values($nameID,$postID)");
		$dbi->query($query);
		
	$dbi->disconnect();	
?>
		
		
		