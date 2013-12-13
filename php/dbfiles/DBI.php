<?php
/*Author shashi */

// the following line should be uncommented if using the PEAR:Mail package
include_once("Mail.php"); 
    
class DBI {  
	var $version='1.0.0';
	
	function DBI(){						
	}
	
	function connect($server='',$username='',$password='',$databasename=''){	
	
		$this->connected=true;
		
		//connect to the database.
		$this->dbh = mysql_connect($server,$username,$password);
		$this->query("SET NAMES UTF8"); 
		
		if(!$this->dbh){
			//$this->logError(mysql_error(),'connect()');
			die("\Database could not be connected");
			$this->connected=false;			
		}
		
		$result= mysql_select_db($databasename);
		
		if(!$result){
			//$this->logError(mysql_error(),'connect()');
			die("\nCould not connect to database ". mysql_error());
			$this->connected=false;			
		}				
	}
	
	function connect_default(){		
		$this->connect("localhost","dbUser","dbPASS","dbName");
	}

	function getdbname() {    
        return "dbName"; 
    }

	function get_source() {
		return "<host>127.0.0.1</host><user>sussol</user><pwd>xxxxxxx</pwd><port>3306</port>";
	}

	function get_target() {
		return "<host>127.0.0.1</host><user>adrian</user><pwd>xxxxxx</pwd><port>3307</port>";
	}
	
	function isConnected(){	
		return $this->connected;		
	}		
	
	function disconnect(){		
		
		if (isset($this->dbh)){
		
			mysql_close($this->dbh);
			return 1;			
		}
		else			
			return 0;			
	}
	
	function insertQuery($statement=''){
		//If database error then return error else return integer value	i.e. last inserted row ID
		$result= mysql_query($statement);
		
		if($result)			
			return mysql_insert_id ();
		else			
			return mysql_error();
			
		//$dbi->test_file("dbi",$statement);	
	}
	
	function query($statement=''){
	//If database error then return error else return integer value	
		$result= mysql_query($statement);
		
		if($result)
			return $result;			
		else
			return mysql_error();					
	}
		
	function errorHandle($errno, $errstr){
    
		 echo "<b>Error:</b> [$errno] $errstr";
	}
    
	/* FOR Error logging PURPOSE ONLY*/			
	function logError($errorString,$functionName){
	
		$date=date("r");		
		$content="\n Error @ $date [$functionName]\n ".$errorString."\n";
		$fh = fopen('errorLog.txt', "a"); 
		fwrite($fh, $content); 
		fclose($fh); 						
	}

    function sendEmail($To, $Subject, $Message, $From) {

        //
        // Either uncomment this block (to use standard sendmail if configured on the server)
        //
    
//

        $Headers = 'MIME-Version: 1.0' . "\r\n";
        $Headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $Headers .= "From:$From". "\r\n";

        return (mail($To, $Subject, $Message, $Headers)); 
//

        //
        // or this block (to use PEAR:Mail if installed on the server)
        //
    
/* 
        $Host = "smtp.inf.org"; // set your SMTP server name here
        $Username = "user@inf.org"; // set your SMTP login here
        $Password = "smtp_pw"; // set your SMTP password here
        $Port = "25";
    
        // If SSL enabled, uncomment the following two lines
        $Host = "ssl://".$Host;
        $Port = "465";
 
        $Headers = array ('From' => $From, 'To' => $To, 'Subject' => $Subject); 

        $Mail = new Mail();
        $SMTP = $Mail->factory('smtp', array ('host' => $Host, 'port' => $Port, 'auth' => true, 'username' => $Username, 'password' => $Password)); 
       
        $mail = $SMTP->send($To, $Headers, $Message); 
       
        $not_ok = (PEAR::isError($mail)); // {

        //if ($not_ok) echo("<p>Failed</p>"); else echo("<p>Succeeded</p>");
    
        return(!$not_ok);
*/
    }
}

?>
