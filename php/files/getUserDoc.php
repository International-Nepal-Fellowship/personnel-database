<?php
include_once (dirname(__FILE__).'/connect.php');
$nameID=$_POST['nameID'];
$docID=$_POST['docID'];
$docField=$_POST['docField'];
$table=$_POST['tableName'];
$currentUserID=$_POST['currentUserID'];

/*$query=sprintf("select `document_notes`.`id`, `document_notes`.`notes`, `document_notes`.`link` from `document_notes`,`documentation` where 
`document_notes`.`id`=`documentation`.`$docField` and
`document_notes`.`id` = $docID");*/
/*
$query=sprintf("select `document_notes`.`id`, `document_notes`.`notes`, `document_notes`.`link` from `document_notes` 
LEFT JOIN `documentation` ON  `document_notes`.`id`=`documentation`.`$docField`
where `document_notes`.`id` in (select `$docField` from `documentation` where `documentation`.`name_id`=$nameID)
");
*/
//$query =	sprintf("SELECT `".$docField."` from `documentation` where id=".$docID);
$retVal='';

$fieldNames ="";
$fieldNames = "id,notes,link";
$fNames      = explode(",",$fieldNames);

switch($table){
	case 'document_notes':
		$extraTable	=	"documentation";
		break;
	case 'orientation_notes':
		$table = "document_notes";
		$extraTable	=	"orientation";
		break;
	case 'orientation_arrangement_notes':
		$table = "document_notes";
		$extraTable	=	"orientation_arrangement";
		break;
	case 'home_assignment_notes':
		$table = "document_notes";
		$extraTable	=	"home_assignment";
		break;
	case 'course_notes':
		$table = "document_notes";
		$extraTable	=	"course";
		break;
	case 'secondment_notes':
		$table = "document_notes";
		$extraTable	=	"secondment";
		break;	
	case 'review_notes':
		$table = "document_notes";
		$extraTable	=	"review";
		break;	
	case 'training_notes':
		$table = "document_notes";
		$extraTable	=	"training";
		break;
	case 'insurance_notes':
		$table = "document_notes";
		$extraTable	=	"insurance";
		break;
	case 'registration_notes':
		$table = "document_notes";
		$extraTable	=	"registrations";
		break;
}

$permissionsTable = $extraTable;
if ($extraTable == 'inf_staff') $permissionsTable = 'personal';
	
$retVal='<doc>';

if ($dbi->isNotAllowed($permissionsTable,'view') === false) {

	$fields = $table.".".$fNames[0].",".$table.".".$fNames[1].",".$table.".".$fNames[2];
	$query =sprintf("SELECT ".$fields." from ".$table.",".$extraTable." where ((`$table`.`deleted` is NULL) OR (`$table`.`deleted` = '0') ) AND ((`$extraTable`.`deleted` is NULL) OR (`$extraTable`.`deleted` = '0') ) AND	".$extraTable.".".$docField."=".$table.".id and ".$extraTable.".id=".$docID." and ".$table.".id > 0");	

	$result=$dbi->query($query);
	
	if($result)
	{
		if(mysql_num_rows($result)>0){
			while($row=mysql_fetch_array($result)){					
				//$retVal.=$row[0];	
				
				for($i = 0;$i<sizeof($fNames);$i++){
					$tag = $fNames[$i];
					if ($tag	==	"id") 	$tag	=	'__id';
					
					if ($tag == "link") {
						$docName=$dbi->getFileName($row[$i]);
                        $retVal .= '<'.$tag.'>'.$dbi->getUploadURL().'requestDownload.php?type=doc&person='.$nameID.'&file='.$docName.'</'.$tag.'>';
						
                        $retVal .= '<filename>'.$docName.'</filename>';
                        //$retVal.="<link>".$dbi->copyUserFiles($currentUserID,$nameID,$row['link'])."</link>";
					//	$retVal.="<link>".$row['link']."</link>";
					} else {
						$retVal .= '<'.$tag.'>'.$row[$i].'</'.$tag.'>';
					}
				}
			}
		}
	}
}	

$retVal.='</doc>';
	
print $retVal;

//$dbi->test_file("getUserDoc.php \n nameID : $nameID \n currentUserID : $currentUserID \n $query",$retVal);
/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('getdoc.txt', "a");
fwrite($fh, $query); 
fwrite($fh,"\n");
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh);  
*/

?>