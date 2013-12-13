<?php
include_once (dirname(__FILE__).'/connect.php');

    $_POST=$dbi->prepareUserData($_POST);
    
	$tableName   =	$_POST['tableName'];
	$fieldValue  =	$_POST['fieldValue'];
	$fieldNames  =	$_POST['fieldNames'];
	$disallowed = ($dbi->isAdmin()===false);
    
    switch($tableName){

	case 'organisation_rep':

        $orderby    = 'ORDER BY name ASC';
        $where      = 'where organisation_id	=	'.$fieldValue;
        $tagname    = 'orepdata';
		$disallowed  = $disallowed || ($dbi->isNotAllowed('secondment','view'));
        break;
        
	case 'visa_history':

        $orderby    = 'ORDER BY issue_date DESC';
        $where      = 'where visa_id	=	'.$fieldValue;
        $tagname    = 'visadata';
		$disallowed  = $disallowed || ($dbi->isNotAllowed('passport','view'));
        break;      
    } 
    
	$query   	 = "select $fieldNames from `$tableName` $where  AND deleted=0 $orderby";	
	
	$result      = $dbi->query($query);
		
	$fNames      = explode(",",$fieldNames);
	$retVal = '<'.$tagname.'s>';
	$ok = true;

	if ($disallowed) {
		$ok = false;
		$retVal	.=	'<'.$tagname.'>disallowed</'.$tagname.'>';
	}
	else if(mysql_num_rows($result)	==	0) {
		$retVal	.=	'<'.$tagname.'></'.$tagname.'>';
		$ok = false;
	}
	
	if ($ok) {
	while($row = mysql_fetch_array($result)){			
               
		$retVal .=	'<'.$tagname.'>';		
				
		for($i = 0;$i<sizeof($fNames);$i++){
			$tag = $fNames[$i];
            $retVal .= $dbi->process_tag($tag,$row[$i]);
/*
            $retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';	
			if($tag	==	"issue_country_id")
			{		
				$retVal .= '<country>'.$dbi->get_table_item('country','name','id',$row['issue_country_id']) .'</country>';			
			}
*/
		}
		$retVal	.='</'.$tagname.'>';						
	}
	}	
	$retVal .= '</'.$tagname.'s>';
/*
$fh = fopen('dualsearchtest.txt', "a");
fwrite($fh, $query);
fwrite($fh,"\n");
fwrite($fh, $tableName." : ".$action." : ".$fieldValue." : ".$fieldNames."\n"); 
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh);
*/
    echo $retVal;
?>