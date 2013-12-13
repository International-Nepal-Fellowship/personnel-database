<?php

//require_once (dirname(__FILE__).'/../../../dbfiles/DBI.php'); 	
include_once (dirname(__FILE__).'/connect.php');

	$dbname = $dbi->getdbname();

	$dbi->connect_default();

	require_once('mysqldump.php');
	
	$backupType	=$_POST['backupType'];
	$userID		=$_POST['userID'];
	
//	$currentDateTime=date('Y-m-d');//date("d-m-Y h:i:s");
	$currentDateTime=date('Y-m-d_H-i-s');
	$fileDir="../main/backups/".$userID;
	$permFileDir=$dbi->getUserBackupDir($userID);//get_dbfiles_dir()."backups";
	$fileName="inf_".$backupType."_".$currentDateTime.".sql";
	$permFileName=$permFileDir.$fileName;
	
	if ($dbi->isSuperAdmin() === false) {
		$ok = false;
		$retVal.= "<errors><error>Backup disallowed</error>";
		$retVal.="<status>fail</status></errors>";
	}
	
	if ($ok) {

/*	
	if(! is_dir ($fileDir)){
		$dbi->creatDirectoryRecursive($fileDir);		
	}

	if(! is_dir ($permFileDir)){
		$dbi->creatDirectoryRecursive($permFileDir);		
	}
*/
	//Use this for plain text and not compressed file
	$dumper = new MySQLDump($dbname,$permFileName,false,false);
	switch($backupType){
	
	case 'BackupData':
			
			//Dumps all the database data only (no structure)
			$dumper->getDatabaseData();			
		break;
		
	case 'BackupStructure':
			
			//Dumps all the database structure only (no data)
			$dumper->getDatabaseStructure();		
		break;
		
	case 'BackupDataStructure':	
			
			//Dumps all the database
			$dumper->doDump();	
		break;	
	}
	
	$retVal="<fileName>$fileName</fileName>";
	if( file_exists  ( $permFileName )){
		$retVal.="<errors><error>Successfully created backup</error>";
		$retVal.="<status>success</status></errors>";
		//$dbi->full_copy($fileName, $permFileName);
	}
	else{
		$retVal.= "<errors><error>Could not create backup at ".$permFileName."</error>";
		$retVal.="<status>fail</status></errors>";			
	}
	}
	echo $retVal;

?>