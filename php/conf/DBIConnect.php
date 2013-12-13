<?php
/*Author Bidur*/
	
include_once (dirname(__FILE__).'/../dbfiles/DBI.php');
include_once('SimpleImage.php'); 
 
class DBIConnect extends DBI
{	
	function DBIConnect()
	{		
		$this->connected=false;	
	}

	function insertQuery($statement=''){
		//If database error then return error else return integer value	i.e. last inserted row ID
		$result= mysql_query($statement);
		
		if($result)			
			return mysql_insert_id ();
		else {
			$this->logErrors("insertQuery error: ".mysql_error()." for query: ".$statement);
			return mysql_error();	
		}	
	}
	
	function query($statement=''){
	//If database error then return error else return integer value	
		$result= mysql_query($statement);
		
		if($result)
			return $result;			
		else {
			$this->logErrors("query error: ".mysql_error()." for query: ".$statement);
			return mysql_error();	
		}
	}
	
	function get_dbfiles_dir() {
		return "dbfiles/";
	}
	
	function logErrors($errorMessage)
	{	
		$fhE = fopen(dirname(__FILE__).'/'.$this->get_dbfiles_dir().'errorLog.txt',"a");
		$date=date("r");
		$message="\n[".$date."] ".$errorMessage;			
		fwrite($fhE, $message); 
		fclose($fhE); 
	}
	
	function sendMail($mail_to,$password,$userID,$name){	

//$this->logErrors("sendMail"); 	
        $from = $this->get_table_item('users','email','id',$userID);
        $mail_subject = "Your details have been reset";
        $mail_body = " The new password for $name is:\n";
        $mail_body .= " $password\n";
		
//$this->logErrors("$from $mail_to $mail_subject"); 
        $ok = $this->sendEmail($mail_to, $mail_subject, $mail_body, $from);
        if (!$ok){
            $this->logErrors("Failed to send email to $mail_to");    
		}
        //else $this->logErrors("Email sent to $mail_to"); 
        
        return $ok;
	}
	
	function printTransactionInfo($arg)
	{
		if ($arg == "COMMIT") $arg=0; //force integer
		if (is_numeric($arg)) //if the $arg is not integer then it is error
		{	
			print "<errors><error>Successfully updated database (".$arg.")</error>";
			print "<status>success</status></errors>";
			return("COMMIT") ;
		}
		else 
		{
			print "<errors><error>Error! Cannot update database (".$arg.")</error>";
			print "<status>fail</status></errors>";		
			$this->logErrors("Error! Cannot update database (".$arg.")");
			return("ROLLBACK");
		}		
		//$arg='';//clear
	}
}
?>
