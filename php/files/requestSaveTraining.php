<?php
	include_once (dirname(__FILE__).'/connect.php');
		$nameID=$_POST['nameid'];
		$trainingNeed=$_POST['trainingNeed'];
		$action=$_POST['action'];
		$training_needsID=$_POST['training_needsid'];
	
				
	if($action ==	"Add New"){
	
			$query=sprintf("INSERT INTO training_needs(id,name_id,need) values('',$nameID,'$trainingNeed')");
							
			$dbi->printTransactionInfo($dbi->query($query));
	
	}
	else if ($action == "Edit"){
	
			$query=sprintf("UPDATE training_needs set need='".$trainingNeed."' where id=".$training_needsID);
			$dbi->printTransactionInfo($dbi->query($query));
		
	}
	
	$dbi->disconnect();	

	
?>
