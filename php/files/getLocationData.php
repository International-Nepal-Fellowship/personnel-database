<?php
include_once (dirname(__FILE__).'/connect.php');
$retVal="";
$emailData=array();
$addressData=array();
$phoneData=array();


$query="SELECT email.id,email.email FROM email WHERE email.deleted='0' AND email.id in(select email_id from location where location.deleted='0')";
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)
			{
			while($row=mysql_fetch_array($result)){	
				$eid=$row['id'];			
				$emailData[$eid]=$row['email'];	
				
				}
			}
		
		}
	
		


$query="SELECT phone.id,phone.phone FROM phone WHERE phone.deleted='0' AND phone.id in(select phone_id from location where location.deleted='0')";
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)
			{
			while($row=mysql_fetch_array($result)){		
				$pid=$row['id'];
				$phoneData[$pid]=$row['phone'];				
				}
			}
		
		}
	


$query="SELECT address.id,address.address, address.city_town, address.state_province FROM address WHERE address.deleted='0' AND address.id in(select address_id from location where location.deleted='0')";
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)
			{
			while($row=mysql_fetch_array($result)){	
				$aid	=	$row['id'];
				$addressData[$aid]=	$row['address'].", ".$row['city_town'].", ".$row['state_province'];				
				}
			}		
		}
	

$retVal.="<locationdata>";
$query="SELECT email_id,address_id,phone_id,name FROM location where location.deleted='0' order by name";
	$result = $dbi->query($query);
	if($result)
		{
		if (mysql_num_rows($result) > 0)
			{
			while($row  = 	mysql_fetch_array($result)){	
				$retVal.="<location>";
				$aid	=	$row['address_id'];
				$pid	=	$row['phone_id'];
				$eid	=	$row['email_id'];
				$email	=	'None';
				if($eid==null){
					$email	=	'None';
					$eid=0;
					}
				else
					$email	=	$emailData[$eid];
				$address	=	'None';
				if($aid==null){
					$address	=	'None';
					$aid=0;					
					}
				else
					$address	=	$addressData[$aid];
					
				$phone	=	'None';
				if($pid==null){
					$phone=	'None';
					$pid=0;
					}
				else
					$phone	=	$phoneData[$pid];
					
				
				$retVal.=	'<name>'.$row['name'].'</name>';	
				$retVal.=	'<email_id>'.$eid.'</email_id>';
				
				$retVal.=	'<email>'.$email.'</email>';
				$retVal.=	'<address_id>'.$aid.'</address_id>';
				$retVal.=	'<address>'.$address.'</address>';				
				$retVal.=	'<phone_id>'.$pid.'</phone_id>';	
				$retVal.=	'<phone>'.$phone.'</phone>';
				$retVal.="</location>";
				}
			}
		else
			$retVal.='<location><name>None</name><email>None</email><email_id>0</email_id><address>None</address><address_id>0</address_id><phone>None</phone><phone_id>0</phone_id></location>';
		}
	else
$retVal.='<location><name>None</name><email>None</email><email_id>0</email_id><address>None</address><address_id>0</address_id><phone>None</phone><phone_id>0</phone_id></location>';
$retVal.="</locationdata>";	
echo $retVal;
?>