<?PHP

    include_once (dirname(__FILE__).'/connect.php');
    
	function connect_to_database($DB,$host,$uname,$pass)
	{
		$status="<errors><error>Problem connecting to database</error>";		
        $status.="<status>error</status></errors>";
            
        $connection= mysql_connect($host,$uname,$pass);
		if(!$connection)
		{	
            die($status);
		}
		else
		{
			//echo "connected";
		}

		$result= mysql_select_db($DB);
		if(!$result)
		{
			die($status);
		}	
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	function updateUserPermission($userID,$DB,$dbUser,$file_name){
				
		//$dbUser='we@localhost';
		//Get Grants from security_user_permission 
		//discard all except view ( Only view allowed )
		//Discard data for security_user_permission.allow_deny='n' ( not necessary )
		$mapPermission=array('add' => 'INSERT', 'edit' => 'UPDATE', 'view' => 'SELECT');

		$tabPermission=array();
		
		$query="SELECT security_permission.name , security_user_permission.tab_name
		FROM security_permission
		LEFT JOIN security_user_permission ON security_permission.id = security_user_permission.permission_id
		WHERE security_permission.deleted='0' AND security_user_permission.deleted='0' 
		AND security_user_permission.user_id=$userID AND  security_user_permission.allow_deny='y'
		AND  security_permission.name='view'";
		
		$result=mysql_query($query);
		
        if(!$result)	return false;
		if($result)
		{
			while($row=mysql_fetch_assoc($result))
			{		
				$currentTab=$row['tab_name'];
				$permName=$row['name'];
				$currentPermission=$mapPermission[$permName];
				// $currentTab may be accessed at most 3 times ( for add, edit, view)
				if(array_key_exists($currentTab,$tabPermission))//eg . if  $tabPermission['email'] already set
				{
				//	$temp=$tabPermission[$currentTab].','. $row['name'];
					$tabPermission[$currentTab]=$tabPermission[$currentTab].','. $currentPermission;
				}
				else//eg . if  $tabPermission['email'] already NOT set 
					$tabPermission[$currentTab]= $currentPermission;				
			//echo $currentTab." : ". $tabPermission[$currentTab]."<br>";
			}
		}
		//HERE, $tabPermission contains key-value pair of tab_name as key and grants as value E.g. $tabPermission['email']='SELECT,UPDATE' 
		//NOW fro each tab name , apply grants for respective table(s)
		
		while (list($tab, $tabPerm) = each($tabPermission)){
			
			$tabTables=array();//clean array
            
            if($tab=='annual_review') $tab='review';
            if($tab=='orientation arrangement') $tab='orientation_arrangement';
            if(($tab=='home assignment') || ($tab=='homeassignment')) $tab='home_assignment';
            if($tab=='visit') $tab='patient_visit';
            if($tab=='services') $tab='patient_service';
            if($tab=='surgery') $tab='patient_surgery';
            if($tab=='appliances') $tab='patient_applicance';
            if($tab=='billing') $tab='patient_bill';
			
			if($tab=='personal')
				$tabTables=array('name','inf_staff','family','surname','relation','nationality','country','caste');
			elseif($tab=='address')
				$tabTables=array('address','name_address','country');
			elseif($tab=='email')
				$tabTables=array('email','name_email');
			elseif($tab=='phone')
				$tabTables=array('phone','name_phone','country');
            elseif($tab=='education')
				$tabTables=array('education','qualification_type','speciality_type');
            elseif($tab=='passport')
				$tabTables=array('passport','visa_history','visa','country');
            elseif($tab=='visa_history')
				$tabTables=array('visa_history','visa','country');
            elseif($tab=='documentation')
				$tabTables=array('documentation','document_notes');
            elseif($tab=='secondment')
				$tabTables=array('secondment','document_notes','organisation','organisation_type','organisation_rep','address','phone','email');
            elseif($tab=='orientation')
				$tabTables=array('orientation','document_notes');
            elseif($tab=='orientation_arrangement')
				$tabTables=array('orientation_arrangement','document_notes');
			elseif($tab=='staff')
				$tabTables=array('staff','staff_type','leaving_reason');
            elseif($tab=='service')
				$tabTables=array('service','post','grade','location','programme','unit','section','address','email','phone','visa');
            elseif($tab=='movement')
				$tabTables=array('movement','location','movement_reason','address','email','phone');
            elseif($tab=='leave')
				$tabTables=array('leave','leave_type');
            elseif($tab=='training')
				$tabTables=array('training','course','course_type','course_subject_type','training_needs');
            elseif($tab=='hospitalisation')
				$tabTables=array('hospitalisation','illness','hospital','relation','country');
            elseif($tab=='review')
				$tabTables=array('review','review_type');
			elseif($tab=='home_assignment')
				$tabTables=array('home_assignment','document_notes');
			elseif($tab=='patient_visit')
				$tabTables=array('patient_visit','hospital','referred_from','treatment_reason','treatment_category');
            elseif($tab=='treatment')
                $tabTables=array('treatment','treatment_case','treatment_regimen','treatment_result','treatment_category');
            elseif($tab=='patient_service')
                $tabTables=array('patient_service','patient_service_type');
            elseif($tab=='patient_surgery')
                $tabTables=array('patient_surgery','patient_surgery_type');
            elseif($tab=='patient_appliance')
                $tabTables=array('patient_appliance','patient_appliance_type','requested_from');
			elseif($tab=='health_staff')
				$tabTables=array('health_staff','health_staff_type');
	        else{
				$tabTables=array($tab);
			}				
	    
		//NOW grant the newly assigned privileges	
			foreach($tabTables as $eachTable){
				
				$dbTable="$DB.$eachTable";
				$query='';
				$queryRevoke='';
				$query="GRANT $tabPerm on $dbTable to $dbUser";
                test_file($file_name,$query.";");
				$result=mysql_query($query);
				if(!$result) return false;			
			}
		
		}//end of while
		
		return true;
		
	}//end of function updateUserPermission()
		
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function initaliseDbGrants($DB,$userType,$user,$host){
	/*
	Sets permissions for user as per its type i.e if admin user then allow admin level (mostly insert,select,update)access  else allow non admin level access(mostly Select only).

	*/

		$adminOnlyUpdateTables=array('caste','country','course','course_subject_type','course_type','grade','hospital',
		'illness','leave_type','leaving_reason','location','movement_reason','nepali_day','organisation',
		'organisation_link','organisation_rep','organisation_type','post','programme','project',
		'qualification_type','referred_from','religion','requested_from','review_type','site','speciality_type',
		'staff_type','visa','visa_history','training_needs','users','security_role','security_permission',
		'security_role','security_role_permission','security_user_permission','change_log','unit','section','site');	
        
        $adminOnlyTables=array('nepali_day','users','security_role','security_permission',
		'security_role','security_role_permission','security_user_permission','change_log','site');		
		
		$hiddenTable=array('sync','sync_log');//No one should access these table
		
		$result = mysql_list_tables($DB);    
	    if (!$result) return false;

	    while ($row = mysql_fetch_row($result)) {

			if((in_array($row[0],$hiddenTable)))
				continue;
			
			if(($userType=='admin') || ($userType=='superadmin')){					
					
				//if(in_array($row[0],$adminOnlyUpdateTables)) 
				//	$query="GRANT SELECT, INSERT, UPDATE ON $DB.$row[0] TO '$user'@'$host'";						
				//else //for tables like email, name, 
					$query="GRANT SELECT ON $DB.$row[0] TO '$user'@'$host'";
                    test_file($file_name,$query.";");
						
				$result1 =mysql_query($query);
				if(!$result1) return false;
					
			}//end if($userType=='admin')
			else if($userType!='limited'){//normal user - not admin but not limited either 
					
				if(!(in_array($row[0],$hiddenTable))){
					$query="GRANT SELECT ON $DB.$row[0] TO '$user'@'$host'";
                    test_file($file_name,$query.";");
					$result1 =mysql_query($query);
					if(!$result1) return false;
				}				
			}
            /*
			//all users users can insert into change_log	
			$query="GRANT SELECT ON $DB.change_log TO '$user'@'$host'";
			$result1 =mysql_query($query);
			if(!$result1) return false;
				
			//all users can insert in search_history table	
			$query="GRANT INSERT,SELECT ON $DB.search_history TO '$user'@'$host'";
			$result1 =mysql_query($query);
			if(!$result1) return false;
			//all users can update in users table
			$query="GRANT UPDATE ON $DB.users TO '$user'@'$host'";
			$result1 =mysql_query($query);
			if(!$result1) return false;	
                        */
	    }//end while
	
		return true;
	}

	function test_file($filename,$from)
	{  
        $debug=true;
        if ($debug==true) {
            $fh = fopen($filename, "a"); 
            fwrite($fh, $from."\n"); 
            fclose($fh); 
        }
	}
	
    function clean_up($DB)
    {
        $status="<errors><error>Problem connecting to database</error>";		
		$status.="<status>error</status></errors>";
        print $status;    
        $DB->connect_default();
        exit;
    }
	//////////////////////////////////////////////////////////////////////////

    $_POST=$dbi->prepareUserData($_POST);
    $uname=$_POST['userLogin'];
	$pass=$_POST['userPassword'];
	$host='localhost';
	$DB=$dbi->getdbname();
    $protected_users="'sussol','zzzza'";
	//connect to database and select the database
	connect_to_database($DB,$host,$uname,$pass); //connect as privileged user
	
	//use email address as default password for new user
	$query="select users.email as password,users.user_name,users.id as user_id,security_role.name as user_type from users
	LEFT JOIN security_role on users.role_id=security_role.id
	where users.deleted='0' AND security_role.name !='limited' AND users.user_name NOT IN ($protected_users) ORDER BY users.user_name";
	$result = mysql_query($query);
    
    if(!result) clean_up($dbi);
    if (mysql_num_rows($result) == 0) clean_up($dbi);
    
    $dbfiles_dir = $dbi->get_dbfiles_dir();
    $time_stamp = date("_Y_m_d_H_i_s");
    $file_name = $dbfiles_dir.'permissions_create'.$time_stamp.'.sql';
    $fh = fopen($file_name, "w");
    fclose($fh);
    
	while($row = mysql_fetch_assoc($result)) {
        
        $userName=$row['user_name'];
        $dbUser="$userName@$host";
        
        $password	=	$row['password'];
		$userID		=	$row['user_id'];
		$userType	=	$row['user_type'];	
        
        $query="START TRANSACTION";
        test_file($file_name,$query.";");
        $retVal=$query."\n";
        $status='';
        
        $ok=mysql_query($query);
        if($ok) {
            //NOW clear the current mysql grants for the user (drop the user)
            //First just create a entry for the user( if not any needed when new user entry is to be made), so that drop user wont give out error
            $query="GRANT SELECT on $DB.name to $dbUser";
            test_file($file_name,$query.";");
            $retVal.=$query."\n";
            $ok=mysql_query($query);
            $query="FLUSH PRIVILEGES";
            test_file($file_name,$query.";");
            $res=mysql_query($query);
        }
        //sleep(2);	//delay for 2 second
        if($ok) {
            $query="DROP user $dbUser";
            test_file($file_name,$query.";");
            $retVal.=$query."\n";
            $ok=mysql_query($query);
            $query="FLUSH PRIVILEGES";
            test_file($file_name,$query.";");
            $res=mysql_query($query);
        }
        //sleep(2);	//delay for 2 second
        if($ok) {
            $query="CREATE USER $dbUser identified by '$password'";
            test_file($file_name,$query.";");
            $retVal.=$query."\n";
            $ok=mysql_query($query);
            $query="FLUSH PRIVILEGES";
            test_file($file_name,$query.";");
            $res=mysql_query($query);
        }
        //initialise grants - don't need any more as updateUserPermission()  takes care of dependent tables
        //if($ok) {
            //$ok=initaliseDbGrants($DB,$userType,$userName,$host);
            //$retVal.="initialise grants\n";
        //}
        //get the user permission set in the security_user_permission and update the mysql permission
        if($ok) {
            $ok=updateUserPermission($userID,$DB,$dbUser,$file_name);
            $retVal.="Update permissions\n";
        }
        if($ok) {
            $query="COMMIT";
            test_file($file_name,$query.";");
            $dbi->test_file("Created mysql user for: ".$userName,$retVal);
        }
        else {
            $query="ROLLBACK";
            test_file($file_name,$query.";");
            $dbi->test_file("Failed to create mysql user for: ".$userName,$retVal);
            $status="<errors><error>Failed to create mysql user for: ".$userName."</error>";		
			$status.="<status>error</status></errors>";
        }
        $ok=mysql_query($query);
	}

    if ($status == '') $status="<errors><error>Successfully updated mysql user permissions</error><status>success</status></errors>";
    
    print $status;
    $dbi->connect_default(); //reconnect as normal user
?>