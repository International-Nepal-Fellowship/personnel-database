<?php
include_once (dirname(__FILE__).'/connect.php');

	$_POST=$dbi->prepareUserData($_POST);
 
	$retVal      = '<rootTag>';
    $queryID   =	$_POST['queryID'];

	$queryResult = $dbi->query("SELECT search_history.query FROM search_history where search_history.id=".$queryID);
	$row = mysql_fetch_assoc($queryResult);
	$query=$row['query']; 
    
    $query=str_replace("\'","'",$query); 

	$result      = $dbi->query($query);	
 
	if(mysql_num_rows($result)==0)
		$retVal.='<subTag></subTag>';
	$arrTags=array();
	
	while($values = mysql_fetch_row($result)){  
        $retVal.='<subTag>';
        //$columns = array_keys($row);
        //$values = array_values($row);
        for($i = 0;$i<mysql_num_fields($result);$i++){
			$meta = mysql_fetch_field($result, $i);
			$column = $meta ->name;
			$tableName = $meta ->table;
            if (($column != "family_id") &&($column != "id") && ($column != "name_id")){ //hide id columns
                // pass tablename.fieldname rather than just fieldname
				$retVal .= $dbi->process_tag($tableName.'.'.$column,$values[$i],true,1);
                $tag2   = $dbi->modify_tag($tableName.'.'.$column,true,1);
				array_push($arrTags,$tag2);
			}
        }
        $retVal.='</subTag>';            
	}    
    	
	$retVal.= '</rootTag>';
	
	//Now get the order of fields
	$strFields=implode(',',array_unique($arrTags));
	$retVal.='<columnOrder>'.$strFields.'</columnOrder>';
	
	print $retVal;	
	
	//$dbi->test_file($strFields);
/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('dashtest.txt', "a");
fwrite($fh, $query."\n");
fwrite($fh, $retVal."\n");
fclose($fh); 
*/
?>
