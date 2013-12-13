<?php
include_once (dirname(__FILE__).'/connect.php');
//print'<nepdate>10/11/12</nepdate>'; 
//print '<engdate>10-11-12</engdate>';
//exit;
include_once ('DateConvert.php');
/*
$retVal='';	
$myarray=$_POST['myArray'];
	$retVal.=' \n'.$myarray;
	$dbi->test_file1($retVal);
exit;
*/
$convert_date=new DateConvert;

function add_day($orgDate,$day){
  $cd = strtotime($orgDate);
  $retDAY = date('Y/m/d', mktime(0,0,0,date('m',$cd),date('d',$cd)+$day,date('Y',$cd)));
  return $retDAY;
} 

$eng_date = '1950/01/01';
$eng_date = '1966/06/06';
$eng_date = '1982/11/09';
$eng_date = '1999/04/14';

//print $convert_date->_getSystemDateFormat();

for ($i=0;$i<6000;$i++) {
    $nep_date	=	$convert_date->engToNepali($eng_date);
    $english = str_replace("/","-",$eng_date);
    $nepali = str_replace("/","-",$nep_date);
    $query = "INSERT INTO `date_convert` (`eng_date`,`nep_date`) VALUES ('$english','$nepali');";
    $ok = ($dbi->isInteger($addressID=$dbi->insertQuery($query)));
    print '<nepdate>'.$nepali.'</nepdate>';
    print '<engdate>'.$english.'</engdate>';
    $eng_date = add_day($eng_date,1);
}

//$dbi->test_file($retVal);
?>