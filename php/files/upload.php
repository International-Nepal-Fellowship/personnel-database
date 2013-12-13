<?php

/*Used in components/photos  & components/passport & application/documentation*/
include_once (dirname(__FILE__).'/connect.php');

//$name='bd';
//$personal_folder=22;
//$userID=2;

$name=$_GET["imageName"];
//name of the file// NOT USED SO NO NEED TO CALCULATE THE UNIQUE  NAME IN THE CALLE IN SWF APPLICATION- PASSPORT,PHOTO AND DOCUMENTATION
$personal_folder=$_GET["nameID"];
$userID=$_GET["userID"];
$userID=str_replace("/","",$userID);
$type=$_GET["type"];
$tab=$_GET["tab"];
$ok = true;
$error = "Uploading error";

//$dbi->test_file("upload.php \n\n $name $personal_folder $userID $tab");

if ($dbi->isNotAllowed($tab,'edit',$userID) && $dbi->isNotAllowed($tab,'add',$userID)){
	$ok = false;
	$error = "Disallowed";
}

//$dbi->test_file("upload.php \n\n $name $personal_folder $userID $tab");

//$upload_dir = $dbi->getUploadDir();
//$upload_url = $dbi->getUploadURL();

$message =" ";

if ($ok) {
	$temp_name = $_FILES['Filedata']['tmp_name'];

	$file_name = $_FILES['Filedata']['name'];   // echo"file_name: $file_name";exit;

	$file_name = str_replace("\\","",$file_name);
	$file_name = str_replace("'","",$file_name);

	$ext_index = strrpos($name, '.');
	if ($ext_index > 0) $name = substr($name, 0, $ext_index); //strip extension if it exists
	$ext = substr($file_name, strrpos($file_name, '.') + 1);
	$actualName=substr($file_name, 0,  strrpos($file_name, '.'));
	// This will strip out any punctuation and spaces from filenames, replacing such characters with underscores
	$actualName = preg_replace("/[^a-zA-Z0-9s.]/", "_", $name);
	$uniqueName=uniqid ( $actualName."_");
	$ext = strtolower($ext);

	switch( $ext) {
		case "pdf": break;
		case "rtf": break;
		case "doc": break;
		case "docx": break;
		case "csv": break;
		case "xls": break;
		case "xlsx": break;
		case "gif": break;
		case "png": break;
		case "jpeg": break;
		case "jpg": break;

		default: $ok=false; $error="Disallowed extension: ".$ext; break;
	}

}

if ($ok) {
	$new_unique_name="$uniqueName.$ext";
	//$dbfiles_dir = $dbi->get_dbfiles_dir();
	//$perm_upload_dir=$upload_dir.$dbfiles_dir;

	//$fileDir = $upload_dir."imageUploads/$personal_folder";
	$fileDir = $dbi->getUserUploadDir($personal_folder); //$perm_upload_dir."fileUploads/$personal_folder";

	//file_path = $upload_dir."imageUploads/$personal_folder/".$new_unique_name;
	$file_path = $fileDir.$new_unique_name;

	//if(! is_dir ( $fileDir)){
	//		$dbi->creatDirectoryRecursive($fileDir);
	//	}

	/*JUST FOR TESTING PURPOSE*/

	// $fh = fopen('uploadtest.txt', "a");
	// fwrite($fh, $name);
	// fwrite($fh,"\n");
	// fwrite($fh, $file_name);
	// fwrite($fh,"\n");
	// fwrite($fh, $file_path);
	// fwrite($fh,"\n");
	// fclose($fh);


	if (move_uploaded_file($temp_name, $file_path)===false) {
		$ok = false;
		$error = "Upload failure";
	}
	// Artur Neumann INFN 8.8.2012
	//change the permissions, so we can sync the files as group member
	else
	{
		chmod( $file_path, 0640);
	}
}

/*
 $fh = fopen('uploadtest.txt', "a");
fwrite($fh, $temp_name);
fwrite($fh,"\n");
fwrite($fh, $result);
fwrite($fh,"\n");
fclose($fh);
*/

/*
 $temp_file_path = $upload_dir."../main/fileUploads/$userID/$personal_folder/".$new_unique_name;
$temp_file_dir =  $upload_dir."../main/fileUploads/$userID/$personal_folder/";
//$dbi->test_file($temp_file_path,$temp_file_dir );
if(! is_dir ( $temp_file_dir)){
$dbi->creatDirectoryRecursive($temp_file_dir);
}

$dbi->full_copy($file_path, $temp_file_path);
*/

//$dbi->copyr($file_path, $temp_file_path);
//$dbi->test_file("targetLink:$temp_file_path\n sourceLink:$file_path");

if ($ok)
{
	$message =  "<result><status>OK</status><message>$file_name uploaded successfully.</message><imageName>$new_unique_name</imageName><extention>$ext</extention></result>";
	if ($type == 'image') $dbi->makeThumbImage($new_unique_name,$personal_folder);
}
else
{
	$message = "<result><status>Error</status><message>$error</message></result>";
}

echo $message;

?>