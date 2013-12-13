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
$eng_date=$_POST["englishdate"];
$nep_date=$_POST["nepalidate"];

if($nep_date=="")
	{
		$nep_date	=	$convert_date->engToNepali($eng_date);
		if ($nep_date == '00/00/00') $nep_date = '0000/00/00';
	}
elseif($eng_date=="")
	{
		$eng_date	=	$convert_date->nepToEnglish($nep_date);
		if ($eng_date == '00/00/00') $eng_date = '00/00/0000';
	}	
	
//$age	=	floor(((time() - strtotime($eng_date)) / 86400) / 365);	//works if the date is from 1959 BS or after

    $BirthDate= str_replace('/','-',$eng_date) ;
	$BirthDate= str_replace('\\','-',$BirthDate) ;
    list($Year, $Month, $Day) = explode("-", $BirthDate);
	$current_date=date('Y-m-d');	
	list($curYear, $curMonth, $curDay) = explode("-",$current_date);
	$age= $curYear - $Year;
    // If the birthday hasn't arrived yet this year, the person is one year younger
    if(($curMonth < $Month) || (($curMonth == $Month) && ($curDay < $Day)))
    {              
		$age--;
    }

print '<nepdate>'.$nep_date.'</nepdate>';
print '<engdate>'.$eng_date.'</engdate>';
print '<age>'.$age.'</age>';
$retVal="<nepdate>".$nep_date."</nepdate>\n <engdate>".$eng_date."</engdate>";
//$dbi->test_file($retVal);
?>