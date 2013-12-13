<?php
	include_once (dirname(__FILE__).'/connect.php');
/*
	function dl_file($file){
	//source <http://php.net/manual/en/function.header.php> 
	    //First, see if the file exists
	    if (!is_file($file)) { die("<b>404 File not found!</b>"); }

	    //Gather relevent info about file
	    $len = filesize($file);
	    $filename = basename($file);
	    $file_extension = strtolower(substr(strrchr($filename,"."),1));

	    //This will set the Content-Type to the appropriate setting for the file
	    switch( $file_extension ) {
	          case "pdf": $ctype="application/pdf"; break;
	      case "exe": $ctype="application/octet-stream"; break;
	      case "zip": $ctype="application/zip"; break;
	      case "doc": $ctype="application/msword"; break;
	      case "xls": $ctype="application/vnd.ms-excel"; break;
	      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	      case "gif": $ctype="image/gif"; break;
	      case "png": $ctype="image/png"; break;
	      case "jpeg":
	      case "jpg": $ctype="image/jpg"; break;
	      case "mp3": $ctype="audio/mpeg"; break;
	      case "wav": $ctype="audio/x-wav"; break;
	      case "mpeg":
	      case "mpg":
	      case "mpe": $ctype="video/mpeg"; break;
	      case "mov": $ctype="video/quicktime"; break;
	      case "avi": $ctype="video/x-msvideo"; break;

	      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
	      case "php":
	      case "htm":
	      case "html":
	      case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

	      default: $ctype="application/force-download";
	    }

	    //Begin writing headers
	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	   
	    //Use the switch-generated Content-Type
	    header("Content-Type: $ctype");

	    //Force the download
	    $header="Content-Disposition: attachment; filename=".$filename.";";
	    header($header );
	    header("Content-Transfer-Encoding: binary");
	    header("Content-Length: ".$len);
	    @readfile($file);
	    exit;
	}
*/
		$type   = $_GET['type'];
		$tab    = $_GET['tab'];
		$person = (int)$_GET['person'];

		if ($dbi->isNotAllowed($tab,'view')){
			die("<b>Disallowed</b>");
		}
//		$fileName = $_GET['file'];	//2/photos_4b188d87a7d92.zip
		
       	//Artur Neumann
		//INF W / INF N - Projects
		//25.10.2011 16:24
		//lets make the filenames safe
		$origFileName = $_GET['file'];
		$fileName = $dbi->makeSafeFilename($_GET['file']);
		
		$file_extension = strtolower(substr(strrchr($fileName,"."),1));
	    //This will set the Content-Type to the appropriate setting for the file
	    switch( $file_extension ) {
	      case "pdf": $ctype="application/pdf"; break;
	      case "sql": $ctype="text/x-sql"; break;
	      case "doc": $ctype="application/msword"; break;
	      case "gif": $ctype="image/gif"; break;
	      case "png": $ctype="image/png"; break;
	      case "jpeg":
	      case "jpg": $ctype="image/jpg"; break;

	      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
	      case "php":
	      case "htm":
	      case "html":
	      case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

	      default: $ctype="application/force-download";
	    }

	    //Begin writing headers
	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	   
	    //Use the switch-generated Content-Type
	    header("Content-Type: $ctype");

	    //Force the download
	    $header="Content-Disposition: attachment; filename=".$fileName.";";
	    header($header );
	    //header("Content-Transfer-Encoding: binary");
	    //header("Content-Length: ".$len);

        if ($type == 'file') {
            $upload_dir = $dbi->getUserBackupDir($person);
        }
        else {
            $upload_dir = $dbi->getUserUploadDir($person);
        }
        
		$source = $upload_dir.$fileName;
        
        if ($type == 'thumb') {
            $source = $upload_dir.$dbi->getThumbName($fileName);
		}
        else {
            if ($type == 'image') {
                if (!is_file($source)) 
                    $source = $upload_dir.$dbi->getThumbName($fileName);
            }
        }
        
        
/*
$fh = fopen('test.txt', "a"); 
fwrite($fh, $type."\n");
fwrite($fh, $origFileName." -> ".$fileName."\n");
fwrite($fh, $source."\n"); 
fclose($fh);
*/        
        $dbi->dl_file($source);
        
	?>