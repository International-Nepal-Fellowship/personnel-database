<?PHP
include_once (dirname(__FILE__).'/connect.php');

$allSites=array('Patient','INF_Worldwide','INF_Nepal');
$dataGot=array();
$retVal='';
$query="select site.site_specific_id,site.name,site.id,site.timestamp,site.email_domain,site.smtp_server,site.user_id,site.password from site WHERE site.deleted='0' ORDER BY  `site`.`name` ASC ";
		$result=mysql_query($query);
				if($result)
					{
						if(mysql_num_rows($result)	==	0)
						$retVal	.=	'<siteInfo><id>0</id></siteInfo>';
						
						while($row=mysql_fetch_assoc($result)){				
						array_push ($dataGot,$row['name']);
						//$retVal.='<'.$row['name'].'>';
						$retVal.='<siteInfo>';
						$retVal.='<__id>'.$row['id'].'</__id>';
						$retVal.='<name>'.$row['name'].'</name>';
						$retVal.='<site_specific_id>'.$row['site_specific_id'].'</site_specific_id>';						
						$retVal.='<email_domain>'.$row['email_domain'].'</email_domain>';
						$retVal.='<smtp_server>'.$row['smtp_server'].'</smtp_server>';
						$retVal.='<user_id>'.$row['user_id'].'</user_id>';
						$retVal.='<password>'.$row['password'].'</password>';
						$retVal.='<timestamp>'.$row['timestamp'].'</timestamp>';
						$retVal.='</siteInfo>';
						//$retVal.='</'.$row['name'].'>';	
						
						}
				}


	$allSites=array_diff ($allSites,$dataGot);
	foreach($allSites as $eachSite){
		//$retVal.='<'.$eachSite.'></'.$eachSite.'>';	
		$retVal.='<siteInfo><name>'.$eachSite.'</name><id>0</id></siteInfo>';
	}

//	$dbi->test_file("getsite ".$retVal);
	echo $retVal;	

?>