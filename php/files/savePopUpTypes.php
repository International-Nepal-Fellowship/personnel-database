<?php
	include_once (dirname(__FILE__).'/connect.php');
//USED BY THE POPUP WINDOW MAINLY IN THE SERVICE TAB TO SAVE THE DATA ENTERED IN DURING THE POPUP OPEREATION
//USED BY popUp for leave_type,staff_type,leaving_reason , locataion,grade
		$typeToGet	=	$_POST['typeToGet'];	
		$table		=	$_POST['table'];
		$parameters	=	$_POST['queryParameters'];
		$value		=	$_POST['queryValue'];	
		
		$query=sprintf("insert into $table $parameters values $value");	
		//$dbi->test_file("requestSavePhone",$query);	
			
		$dbi->printTransactionInfo($ID=$dbi->insertQuery($query));
		//$dbi->test_file("\n requestSavePhone",$ID.':'.$query);
								
	//reload the dataprovider with the updated data
		
/*---for type specific names and id*/
	$result =$dbi-> get_type($table,"id",$typeToGet);
	
	$retVal.="<rootTag>";	
	
	if(!$result)
		$retVal.="<subTag></subTag>";	
	
	foreach($result as $result=>$value){
		
			$retVal.='<subTag  data="'.$result.'">'.$value.'</subTag>';		
	
		}	
	$retVal.="</rootTag>";
	
	print $retVal;
	//$dbi->test_file("requestSavePhone",$retVal);	
	
	
	$dbi->disconnect();	
	

	
?>
