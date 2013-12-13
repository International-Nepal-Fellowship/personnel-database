<?php 
include_once (dirname(__FILE__).'/connect.php');
set_error_handler("myErrorHandler");	
	/*
	$name='aDDD';
	$tableName='organisation_rep';
	$action='Add New';
	$organisationID	=	2;
	$email         	=	"eq@mail";
	$knownas		=	"knowaaanas";
	//$timestamp='';
	*/
	$name		=	$_POST['name'];
	
	$tableName	=	$_POST['tableName'];
	$action		=	$_POST['action'];
	$organisationID	=	$_POST['organisationID'];
	$email         	=	$_POST['email'];
	$knownas		=	$_POST['knownas'];
	
	
	if($action	==	"Add New") {	
		
		$query	=	sprintf("INSERT INTO `organisation_rep` (id,name,organisation_id,email,known_as) 
		VALUES ('','$name','$organisationID','$email','$knownas')");	
			
		$dbi->printTransactionInfo($dbi->insertQuery($query));
	}
	else if($action	==	"Edit") {
		$id=$_POST['ORepID'];
		$timestamp	=	$_POST['timestamp'];
		// CHECK IF TIMESTAMP IS OK OR NOT. IF NOT OK THEN exit;
		$dbi->checkTimestamp('organisation_rep','organisation_rep_timestamp',$timestamp,'id',$id);
		
		$query	=	sprintf("UPDATE `organisation_rep` SET name='".$name."',email='".$email."',organisation_id='".$organisationID."',known_as='".$knownas."' where id=".$id);

		$dbi->printTransactionInfo($dbi->query($query));
	}
	
	
	$dbi->disconnect();
?>	
