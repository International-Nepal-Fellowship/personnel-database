<?php include_once (dirname(__FILE__).'/connect.php');
//salinate $_POST
$_POST=$dbi->prepareUserData($_POST);
	
	function buildQueries2update($dbi,$oldSearchIDs,$finalSearchIDs,$updateType){
		$strUpdatedIDs='';
		$subQuery='';
		
		$arrOldSrchIDs=split (',', $oldSearchIDs);
		$arrFinalSrchIDs=split (',', $finalSearchIDs);
		$arrIds = array_diff($arrFinalSrchIDs, $arrOldSrchIDs);//these ids should be updated
		
		
		foreach($arrIds as $sid) {
			if($sid!=''){// sometimes $sid was blank when there is no any id SO just check to aoid that case 
				$subQuery.=" id=".$sid." OR";
				$strUpdatedIDs.=$sid.',';
			}
		}		
		
		if($subQuery=='') return '';// ie no ids are changed TO current $updateType
		
		$strUpdatedIDs = substr($strUpdatedIDs, 0, -1);//removes the last comma (,) char of $strUpdatedIDs
		//now remove the excessive OR from end of  $subQuery
		$subQuery= substr($subQuery, 0, -1);// trims 'R'
		$subQuery= substr($subQuery, 0, -1);// trims 'O'
	
		$updateQuery="UPDATE  `search_history`  SET  `search_4_all` =  '$updateType' WHERE $subQuery ";	

		return $updateQuery."##".$strUpdatedIDs;//retruns query and Search IDs whose row are changed(separated by ##)
	}	
	
	$finalPublicSearchIDs=$_POST['finalPublicSearchIDs'];
	$finalPrivateSearchIDs=$_POST['finalPrivateSearchIDs'];	
	$publicSearchIDs=$_POST['publicSearchIDs'];
	$privateSearchIDs=$_POST['privateSearchIDs'];
	$userID=$_POST['userID'];
	
//$dbi->test_file("finalPublicSearchIDs: $finalPublicSearchIDs \n  previousPublicSearchIDs: $publicSearchIDs ");
//$dbi->test_file("finalPrivateSearchIDs: $finalPrivateSearchIDs \n previousPrivateSearchIDs: $privateSearchIDs ");
	
	$updatePublicFlag=false;
	$updatePrivateFlag=false;
	$query4PublicSearch='';
	$query4PrivateSearch='';
	$tempPublicIds=array();
	$tempPrivateIds==array();
	$query4PublicSearchnID=buildQueries2update($dbi,$publicSearchIDs,$finalPublicSearchIDs,'1');
	if($query4PublicSearchnID!=='') $updatePublicFlag=true;
	//NOW separate IDs and query	
	if($updatePublicFlag){
		$splited=explode('##',$query4PublicSearchnID);
		$query4PublicSearch=$splited[0];
		$tempPublicIds=explode(',',$splited[1]);
	}
	
	$query4PrivateSearchnID=buildQueries2update($dbi,$privateSearchIDs,$finalPrivateSearchIDs,'0');
	if($query4PrivateSearchnID!='') $updatePrivateFlag=true;
	if( $updatePrivateFlag){
		$splited1=explode('##',$query4PrivateSearchnID);
		$query4PrivateSearch=$splited1[0];
		$tempPrivateIds=explode(',',$splited1[1]);
	}
	$updatesIDs=array_merge($tempPrivateIds,$tempPublicIds);
//$strid=implode(',',$updatesIDs);
//$dbi->test_file(count($updatesIDs)." ids:  $strid");	
	
	$dbi->query("Start transaction");
    
    if($dbi->maintenance()) { //knobble any further update
        $action = "None";
        $ok = false;
        $updatePublicFlag = false;
        $updatePrivateFlag = false;
        $query = "Maintenance mode - try again later";
    }
    else {
        $ok=true;
        $query='search history';
    }
    
	if($updatePublicFlag)
		$ok=($dbi->isInteger($dbi->query($query4PublicSearch)));//query for public search
	
	if($ok && $updatePrivateFlag)//query for private search
		$ok=($dbi->isInteger($dbi->query($query4PrivateSearch)));
/*	
	if($ok){
		foreach($updatesIDs as $uid){
			
			if($ok){
						$ok=$dbi->createChangeLog($userID,'search_history',$uid,'update','search_history');		
					}			
		}	
	}
	*/
	if($ok)
		$dbi->query($dbi->printTransactionInfo("COMMIT"));// commit transaction
	else{		
		$dbi->query($dbi->printTransactionInfo($query));// force rollback	
	}	
	
	//$dbi->test_file($query4PublicSearch,$query4PrivateSearch);
		
?>	