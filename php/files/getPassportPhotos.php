<?PHP
	include_once (dirname(__FILE__).'/connect.php');	

	function getUrl($file){
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
//here $pageURL is current page address e.g.<localhost/files/getPassportPhotos.php> 
		$arr=explode('/',$pageURL);
		array_pop($arr);//drops <getPassportPhotos.php> 
		array_pop($arr);//drops<files>
		$pageURL=implode('/',$arr);
//now append the file name with respect to main web folder
		$pageUrl.='/main/fileUploads/'.$file;
		 return $pageUrl;
	}
	
$currentUserID=$_POST['userID'];
$userIDList=$_POST['userIDList'];
$ok = true;

if ($dbi->isAdmin()===false){
	$ok = false;
	$message = "Disallowed";
}

if ($ok) {
	
	$serv_dir = $dbi->getUserBackupDir($currentUserID);
	$uniqueName=uniqid ( photos."_");
	$new_unique_name="$uniqueName";	
	$targetZipPath 	=	 $serv_dir."$new_unique_name";	
	$upload_dir 	=	 $serv_dir."$new_unique_name/";	
		
	$dbi->creatDirectoryRecursive($upload_dir);
	
	//get photo blob
	$query="SELECT name.id, passport.id as passport_id, passport.photo, passport.photo_link, passport.issue_date,concat( name.forenames , '_' , surname.surname ) as full_name
			FROM name
			LEFT JOIN surname ON name.id = surname.name_id
			LEFT JOIN passport ON name.id = passport.name_id
			WHERE passport.photo_link != '' AND name.id IN ($userIDList)";
			
	$result=$dbi->query($query);
	
	while($row = mysql_fetch_assoc($result)){

		$ext='jpg';
		if($row['photo_link']!=''){
			$picInfo=split('\.',$row['photo_link']);
			$index=count($picInfo)-1;
			$ext=$picInfo[$index];
            $photoName=$row['full_name'].'_'.$row['issue_date'].".$ext";
            $photoName = preg_replace("/[^a-zA-Z0-9s.]/", "_", $photoName);
            //if the picture is available in DB
            if($row['photo']!=''){
                file_put_contents($upload_dir.$photoName,$row['photo']);
            }
            else { //try to generate it from full-size image on disk
                $picInfo=split('/',$row['photo_link']);
                $index=count($picInfo)-1;
                $photoLinkName=$picInfo[$index];
                $dbi->makeThumbImage($photoLinkName,$row['id']);
                copy($dbi->getThumbName($photoLinkName,$row['id']),$upload_dir.$photoName);
            }
        }
	}
	if (mysql_num_rows($result) == 0) {
		$ok = false;
		$message = "No passport photos for selected names";
	}
}
/*
$fh = fopen('uploadtest.txt', "a");
fwrite($fh, $query);
fwrite($fh,"\n");
fwrite($fh, $new_unique_name);
fwrite($fh,"\n");
fclose($fh);
*/
	
//Now create a .zip of all the photos

if ($ok) {
	require_once('pclzip.lib.php');
	//$targetZipPath=rtrim($upload_dir).".zip";
	$archive = new PclZip($targetZipPath.".zip");

    $v_remove = $upload_dir;
    // To support windows and the C: root you need to add the 
    // following 3 lines, should be ignored on linux
    if (substr($targetZipPath, 1,1) == ':') {
      $v_remove = substr($targetZipPath, 2);
    }
    $v_list = $archive->create($targetZipPath,PCLZIP_OPT_REMOVE_ALL_PATH);

	//Now remove the photos from the server file system
	$dbi->removeUserTempDir($targetZipPath);
	//$dbi->removeUserTempDir($targetZipPath.".zip");
	//$downloadUrl=getUrl("$currentUserID/$new_unique_name.zip");
	
    if ($v_list == 0) {
		$ok = false;
		$message = "Error : ".$archive->errorInfo(true);
    }
}

if ($ok) {
	$retVal="<rootTag><requester>getPassportPhotos</requester><fileName>$new_unique_name.zip</fileName></rootTag>";
}
else //Error
{
	$retVal="<rootTag><requester>error</requester><error>$message</error></rootTag>";
}

echo $retVal;
/*
$fh = fopen('uploadtest.txt', "a");
fwrite($fh, $downloadUrl);
fwrite($fh,"\n");
fwrite($fh, $retVal);
fwrite($fh,"\n");
fclose($fh); 
*/   
?>