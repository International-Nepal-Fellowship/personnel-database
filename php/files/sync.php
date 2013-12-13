<?php
include_once (dirname(__FILE__).'/connect.php');

$_POST=$dbi->prepareUserData($_POST);
$action      =	$_POST['action'];
    
function get_site_id()
{
	$queryResult = mysql_query("SELECT site_specific_id FROM site LIMIT 1");
	$row = mysql_fetch_assoc($queryResult);
	return $row['site_specific_id']; 
}
    
function array2table($arr,$width,$queryTable)
{
    $count = count($arr);
    if($count > 0){
        echo "<p>Records from ".$queryTable." table</p>";
        reset($arr);
        $num = count(current($arr));
        echo "<table align=\"centre\" border=\"1\"cellpadding=\"5\" cellspacing=\"0\" width=\"$width\">\n";
        echo "<tr>\n";
        foreach(current($arr) as $key => $value){
            echo "<th>";
            echo $key."&nbsp;";
            echo "</th>\n";  
        }  
        echo "</tr>\n";
        while ($curr_row = current($arr)) {
            echo "<tr>\n";
            $col = 1;
            while (false !== ($curr_field = current($curr_row))) {
                echo "<td>";
                echo $curr_field."&nbsp;";
                echo "</td>\n";
                next($curr_row);
                $col++;
            }
            while($col <= $num){
                echo "<td>&nbsp;</td>\n";
                $col++;      
            }
            echo "</tr>\n";
            next($arr);
        }
        echo "</table>\n";
        echo "<p></p>";
    }
}

function array2xml($arr,$queryTable,$fileName)
{    
    $retVal="";
    $count = count($arr);
    if($count > 0){
        $fh = fopen($fileName."_".$queryTable.".xml", "a"); 
        $retVal.="<changerecord><table>".$queryTable."</table>";
        reset($arr);
        foreach(current($arr) as $key => $value){
            $retVal.="<".$key.">".$value."</".$key.">"; 
        }
        $retVal.="</changerecord>";
        //print $retVal;       
        fwrite($fh, $retVal); 
        fclose($fh);
    }
}

function changed_table($queryTable,$fileName,$htmlOutput)
{
    $query = "SELECT i_site.*,u_site.user_name AS modified_by FROM

    ((SELECT cl.* FROM
        ((SELECT t0.* FROM
            ((SELECT `table`, record_id FROM
                ((SELECT `table`, record_id, COUNT(site_id) AS site_count FROM 
                    ((SELECT site_id, `table`, record_id, MAX(`id`) AS last_id FROM
                        sync_log WHERE `table`='".$queryTable."'
                        GROUP BY site_id, `table`, record_id
                        ORDER BY `table`, record_id, site_id) AS t1)
                    GROUP BY `table`, record_id) AS t2)
                WHERE site_count=2) AS t3)
                LEFT JOIN ((SELECT site_id, `table`, record_id, MAX(`id`) AS last_id FROM
                    sync_log WHERE site_id = ".get_site_id()."
                    GROUP BY site_id, `table`, record_id ORDER BY id) AS t0) 
                USING (`table`,record_id)
                ORDER BY `table`, record_id, site_id) AS tn)
            LEFT JOIN sync_log AS cl ON tn.last_id=cl.id) AS t_site)
        LEFT JOIN ".$queryTable." AS i_site ON i_site.id = t_site.record_id
    LEFT JOIN users AS u_site ON u_site.id = t_site.user_id";

    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result)){
        $array[] = $row; 
    }
    if ($htmlOutput) array2table($array,600,$queryTable); // Will output a table of 600px width
    array2xml($array,$queryTable,$fileName);
}

function show_changed_data($fileName,$htmlOutput)
{
    if ($htmlOutput) {
        echo "Writing to ".$fileName."\n";
        echo "<p></p>";
    }
    
	changed_table("address",$fileName,$htmlOutput);
	changed_table("caste",$fileName,$htmlOutput);
//	changed_table("change_log",$fileName,$htmlOutput);
	changed_table("country",$fileName,$htmlOutput);
	changed_table("course",$fileName,$htmlOutput);
	changed_table("course_subject_type",$fileName,$htmlOutput);
	changed_table("course_type",$fileName,$htmlOutput);
	changed_table("document_notes",$fileName,$htmlOutput);
	changed_table("documentation",$fileName,$htmlOutput);
	changed_table("education",$fileName,$htmlOutput);
	changed_table("email",$fileName,$htmlOutput);
	changed_table("family",$fileName,$htmlOutput);
	changed_table("grade",$fileName,$htmlOutput);
	changed_table("health_staff",$fileName,$htmlOutput);
	changed_table("health_staff_type",$fileName,$htmlOutput);
	changed_table("home_assignment",$fileName,$htmlOutput);
	changed_table("hospital",$fileName,$htmlOutput);
	changed_table("hospitalisation",$fileName,$htmlOutput);
	changed_table("illness",$fileName,$htmlOutput);
	changed_table("inf_staff",$fileName,$htmlOutput);
	changed_table("leave",$fileName,$htmlOutput);
	changed_table("leave_type",$fileName,$htmlOutput);
	changed_table("leaving_reason",$fileName,$htmlOutput);
	changed_table("location",$fileName,$htmlOutput);
	changed_table("movement",$fileName,$htmlOutput);
	changed_table("movement_reason",$fileName,$htmlOutput);
	changed_table("name",$fileName,$htmlOutput);
	changed_table("name_address",$fileName,$htmlOutput);
	changed_table("name_email",$fileName,$htmlOutput);
	changed_table("name_phone",$fileName,$htmlOutput);
	changed_table("name_post",$fileName,$htmlOutput);
	changed_table("nationality",$fileName,$htmlOutput);
	//changed_table("nepali_day",$fileName,$htmlOutput);
	changed_table("organisation",$fileName,$htmlOutput);
	changed_table("organisation_link",$fileName,$htmlOutput);
	changed_table("organisation_rep",$fileName,$htmlOutput);
	changed_table("organisation_type",$fileName,$htmlOutput);
	changed_table("orientation",$fileName,$htmlOutput);
	changed_table("orientation_arrangement",$fileName,$htmlOutput);
	changed_table("passport",$fileName,$htmlOutput);
	changed_table("patient_appliance",$fileName,$htmlOutput);
	changed_table("patient_appliance_type",$fileName,$htmlOutput);
	changed_table("patient_bill",$fileName,$htmlOutput);
	changed_table("patient_inf",$fileName,$htmlOutput);
	changed_table("patient_service",$fileName,$htmlOutput);
	changed_table("patient_service_type",$fileName,$htmlOutput);
	changed_table("patient_surgery",$fileName,$htmlOutput);
	changed_table("patient_surgery_type",$fileName,$htmlOutput);
	changed_table("patient_visit",$fileName,$htmlOutput);
	changed_table("phone",$fileName,$htmlOutput);
	changed_table("photo",$fileName,$htmlOutput);
	changed_table("post",$fileName,$htmlOutput);
	changed_table("programme",$fileName,$htmlOutput);
	changed_table("project",$fileName,$htmlOutput);
	changed_table("qualification_type",$fileName,$htmlOutput);
	changed_table("referred_from",$fileName,$htmlOutput);
	changed_table("relation",$fileName,$htmlOutput);
	changed_table("religion",$fileName,$htmlOutput);
	changed_table("requested_from",$fileName,$htmlOutput);
	changed_table("review",$fileName,$htmlOutput);
	changed_table("review_type",$fileName,$htmlOutput);
	changed_table("search_history",$fileName,$htmlOutput);
	changed_table("secondment",$fileName,$htmlOutput);
	changed_table("security_permission",$fileName,$htmlOutput);
	changed_table("security_role",$fileName,$htmlOutput);
	changed_table("security_user_permission",$fileName,$htmlOutput);
	changed_table("security_role_permission",$fileName,$htmlOutput);
	changed_table("service",$fileName,$htmlOutput);
//	changed_table("site",$fileName,$htmlOutput);
	changed_table("speciality_type",$fileName,$htmlOutput);
    changed_table("staff",$fileName,$htmlOutput);
	changed_table("staff_type",$fileName,$htmlOutput);
	changed_table("surname",$fileName,$htmlOutput);
//	changed_table("sync",$fileName,$htmlOutput);
//	changed_table("sync_log",$fileName,$htmlOutput);
	changed_table("training",$fileName,$htmlOutput);
	changed_table("personnel_training_needs",$fileName,$htmlOutput);
	changed_table("treatment",$fileName,$htmlOutput);
	changed_table("treatment_case",$fileName,$htmlOutput);
	changed_table("treatment_category",$fileName,$htmlOutput);
	changed_table("treatment_reason",$fileName,$htmlOutput);
	changed_table("treatment_regimen",$fileName,$htmlOutput);
	changed_table("treatment_result",$fileName,$htmlOutput);
	changed_table("users",$fileName,$htmlOutput);
	changed_table("visa",$fileName,$htmlOutput);
	changed_table("visa_history",$fileName,$htmlOutput);
    changed_table("visit_treatment_reasons",$fileName,$htmlOutput);
    changed_table("name_training_needs",$fileName,$htmlOutput);
}

function generate_job_files($job_dir,$source_db,$target_db,$db_name) 
{
    $notify_job_header = '<?xml version="1.0" encoding="UTF-8"?>
    <job version="8.13">
    <notifyjob>
    <abortonerror value="no" />
    <result returned="no" />';
    
    $one_way_job_header = '<?xml version="1.0" encoding="UTF-8"?>
    <job version="8.13">
    <syncjob>
    <fkcheck check="yes" />';

    $two_way_job_header = $one_way_job_header.'<twowaysync twoway="yes" />';

    $common_source_target = '<compressed>0</compressed>
    <ssl>0</ssl>
    <sslauth>0</sslauth>
    <clientkey></clientkey>
    <clientcert></clientcert>
    <cacert></cacert>
    <cipher></cipher>
    <charset></charset>';
   
    $source = $source_db.$common_source_target.'<database>'.$db_name.'</database>';
    $target = $target_db.$common_source_target.'<database>'.$db_name.'</database>';

    $site_id = get_site_id();

    $stamp_query = '<query>
    update sync set last_time = this_time where sync_from = '.$site_id.';
    </query>';
    
    $change_query = '<query>
    update sync set this_time = current_timestamp where sync_from = '.$site_id.';
    truncate table sync_log;
    insert into sync_log 
    (select * from change_log where 
    site_id in (select site_specific_id from site)
    and `timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.'));
    </query>';

    $notify_job_footer = '</notifyjob></job>';

    $change_tables = '<tables all="no">
    <table>
    <name>`sync_log`</name>
    <columns all="yes" />
    </table>
    </tables>';
  
    $sync_tables = '<tables all="no">
    <table>
    <name>`address`</name>`
    <columns all="no">
    <column>address_timestamp</column>
    </columns>
    <sqlwhere>`address_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')</sqlwhere>
	</table>
	<table>
	<name>`caste`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`country`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`course`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`course_subject_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`course_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`document_notes`	</name>`
	<columns all="no">
	<column>documentation_timestamp	</column>
	</columns>
	<sqlwhere>`documentation_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`documentation`	</name>`
	<columns all="no">
	<column>documentation_timestamp	</column>
	</columns>
	<sqlwhere>`documentation_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`education`	</name>`
	<columns all="no">
	<column>education_timestamp	</column>
	</columns>
	<sqlwhere>`education_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`email`	</name>`
	<columns all="no">
	<column>email_timestamp	</column>
	</columns>
	<sqlwhere>`email_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`family`	</name>`
	<columns all="no">
	<column>family_timestamp	</column>
	</columns>
	<sqlwhere>`family_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`grade`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`health_staff`	</name>`
	<columns all="no">
	<column>health_staff_timestamp	</column>
	</columns>
	<sqlwhere>`health_staff_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`health_staff_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`home_assignment`	</name>`
	<columns all="no">
	<column>home_assignment_timestamp	</column>
	</columns>
	<sqlwhere>`home_assignment_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`hospital`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`hospitalisation`	</name>`
	<columns all="no">
	<column>hospitalisation_timestamp	</column>
	</columns>
	<sqlwhere>`hospitalisation_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`illness`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`inf_staff`	</name>`
	<columns all="no">
	<column>inf_staff_timestamp	</column>
	</columns>
	<sqlwhere>`inf_staff_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`staff`	</name>`
	<columns all="no">
	<column>staff_timestamp	</column>
	</columns>
	<sqlwhere>`staff_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`leave`	</name>`
	<columns all="no">
	<column>leave_timestamp	</column>
	</columns>
	<sqlwhere>`leave_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`leave_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`leaving_reason`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`location`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`movement`	</name>`
	<columns all="no">
	<column>movement_timestamp	</column>
	</columns>
	<sqlwhere>`movement_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`movement_reason`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`name`	</name>`
	<columns all="no">
	<column>name_timestamp	</column>
	</columns>
	<sqlwhere>`name_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`name_address`	</name>`
	<columns all="no">
	<column>name_address_timestamp	</column>
	</columns>
	<sqlwhere>`name_address_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`name_email`	</name>`
	<columns all="no">
	<column>name_email_timestamp	</column>
	</columns>
	<sqlwhere>`name_email_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`name_phone`	</name>`
	<columns all="no">
	<column>name_phone_timestamp	</column>
	</columns>
	<sqlwhere>`name_phone_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`name_post`	</name>`
	<columns all="no">
	<column>name_post_timestamp	</column>
	</columns>
	<sqlwhere>`name_post_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`nationality`	</name>`
	<columns all="no">
	<column>nationality_timestamp	</column>
	</columns>
	<sqlwhere>`nationality_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`organisation`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`organisation_link`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`organisation_rep`	</name>`
	<columns all="no">
	<column>organisation_rep_timestamp	</column>
	</columns>
	<sqlwhere>`organisation_rep_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`organisation_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`orientation`	</name>`
	<columns all="no">
	<column>orientation_timestamp	</column>
	</columns>
	<sqlwhere>`orientation_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`orientation_arrangement`	</name>`
	<columns all="no">
	<column>orientation_arrangement_timestamp	</column>
	</columns>
	<sqlwhere>`orientation_arrangement_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`passport`	</name>`
	<columns all="no">
	<column>passport_timestamp	</column>
	</columns>
	<sqlwhere>`passport_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_appliance`	</name>`
	<columns all="no">
	<column>patient_appliance_timestamp	</column>
	</columns>
	<sqlwhere>`patient_appliance_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_appliance_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_bill`	</name>`
	<columns all="no">
	<column>patient_bill_timestamp	</column>
	</columns>
	<sqlwhere>`patient_bill_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_inf`	</name>`
	<columns all="no">
	<column>patient_inf_timestamp	</column>
	</columns>
	<sqlwhere>`patient_inf_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_service`	</name>`
	<columns all="no">
	<column>patient_service_timestamp	</column>
	</columns>
	<sqlwhere>`patient_service_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_service_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_surgery`	</name>`
	<columns all="no">
	<column>patient_surgery_timestamp	</column>
	</columns>
	<sqlwhere>`patient_surgery_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_surgery_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`patient_visit`	</name>`
	<columns all="no">
	<column>patient_visit_timestamp	</column>
	</columns>
	<sqlwhere>`patient_visit_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`phone`	</name>`
	<columns all="no">
	<column>phone_timestamp	</column>
	</columns>
	<sqlwhere>`phone_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`photo`	</name>`
	<columns all="no">
	<column>phone_timestamp	</column>
	</columns>
	<sqlwhere>`phone_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`post`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`programme`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`project`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`qualification_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`referred_from`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`relation`	</name>`
	<columns all="no">
	<column>relation_timestamp	</column>
	</columns>
	<sqlwhere>`relation_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`religion`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`requested_from`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`review`	</name>`
	<columns all="no">
	<column>review_timestamp	</column>
	</columns>
	<sqlwhere>`review_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`review_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`search_history`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`secondment`	</name>`
	<columns all="no">
	<column>secondment_timestamp	</column>
	</columns>
	<sqlwhere>`secondment_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`service`	</name>`
	<columns all="no">
	<column>service_timestamp	</column>
	</columns>
	<sqlwhere>`service_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`speciality_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`staff_type`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`surname`	</name>`
	<columns all="no">
	<column>surname_timestamp	</column>
	</columns>
	<sqlwhere>`surname_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`training`	</name>`
	<columns all="no">
	<column>training_timestamp	</column>
	</columns>
	<sqlwhere>`training_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`personnel_training_needs`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`treatment`	</name>`
	<columns all="no">
	<column>treatment_timestamp	</column>
	</columns>
	<sqlwhere>`treatment_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`treatment_case`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`treatment_category`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`treatment_reason`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`treatment_regimen`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`treatment_result`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`users`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`visa`	</name>`
	<columns all="no">
	<column>timestamp	</column>
	</columns>
	<sqlwhere>`timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`visa_history`	</name>`
	<columns all="no">
	<column>visa_history_timestamp	</column>
	</columns>
	<sqlwhere>`visa_history_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
    <table>
	<name>`name_training_needs`	</name>`
	<columns all="no">
	<column>name_training_needs_timestamp	</column>
	</columns>
	<sqlwhere>`name_training_needs_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	<table>
	<name>`visit_treatment_reasons`	</name>`
	<columns all="no">
	<column>name_treatment_reasons_timestamp	</column>
	</columns>
	<sqlwhere>`name_treatment_reasons_timestamp` &gt;= (select last_time from sync where sync_from = '.$site_id.')	</sqlwhere>
	</table>
	</tables>';
    
    $sync_job_footer = '<sync_action type="directsync" />
    <abortonerror abort="no" />
    <sendreport send="no" /></syncjob>
    </job>';
    
    $inf_stamp_local = $notify_job_header.'<source>'.$source.'</source>'.$stamp_query.$notify_job_footer;
    $inf_stamp_remote = $notify_job_header.'<source>'.$target.'</source>'.$stamp_query.$notify_job_footer;
    $inf_change_local = $notify_job_header.'<source>'.$source.'</source>'.$change_query.$notify_job_footer;
    $inf_change_remote = $notify_job_header.'<source>'.$target.'</source>'.$change_query.$notify_job_footer;
    $inf_sync_changes = $two_way_job_header.'<source>'.$source.'</source><target>'.$target.'</target>'.$change_tables.$sync_job_footer;
    $inf_sync_to = $one_way_job_header.'<source>'.$source.'</source><target>'.$target.'</target>'.$sync_tables.$sync_job_footer;
    $inf_sync_from = $one_way_job_header.'<source>'.$target.'</source><target>'.$source.'</target>'.$sync_tables.$sync_job_footer;

    $fh = fopen($job_dir."inf_stamp_local.xml", "w");
    fwrite($fh, $inf_stamp_local); 
    fclose($fh);

    $fh = fopen($job_dir."inf_stamp_remote.xml", "w");
    fwrite($fh, $inf_stamp_remote); 
    fclose($fh);

    $fh = fopen($job_dir."inf_change_local.xml", "w");
    fwrite($fh, $inf_change_local); 
    fclose($fh);

    $fh = fopen($job_dir."inf_change_remote.xml", "w");
    fwrite($fh, $inf_change_remote); 
    fclose($fh);

    $fh = fopen($job_dir."inf_sync_changes.xml", "w");
    fwrite($fh, $inf_sync_changes); 
    fclose($fh);

    $fh = fopen($job_dir."inf_sync_to.xml", "w");
    fwrite($fh, $inf_sync_to); 
    fclose($fh);

    $fh = fopen($job_dir."inf_sync_from.xml", "w");
    fwrite($fh, $inf_sync_from); 
    fclose($fh);
}

function update_timestamps ($job_dir,$log_file)
{
    exec($job_dir."../sja ".$job_dir."inf_stamp_local.xml -l".$log_file);
    exec($job_dir."../sja ".$job_dir."inf_stamp_remote.xml -l".$log_file);
}

function store_local_changes ($job_dir,$log_file)
{
    exec($job_dir."../sja ".$job_dir."inf_change_local.xml -l".$log_file);
    exec($job_dir."../sja ".$job_dir."inf_change_remote.xml -l".$log_file);
    exec($job_dir."../sja ".$job_dir."inf_sync_changes.xml -l".$log_file);
}

function run_sync ($job_dir,$log_file)
{
    exec($job_dir."../sja ".$job_dir."inf_sync_from.xml -l".$log_file);
    exec($job_dir."../sja ".$job_dir."inf_sync_to.xml -l".$log_file);
}

$dbfiles_dir = $dbi->get_dbfiles_dir()."jobs/";
$time_stamp = date("_Y_m_d_H_i_s");
$logfile = "inf_sync_log".$time_stamp.".txt";

switch($action){
	
	case 'Generate':
        generate_job_files($dbfiles_dir,$dbi->get_source(),$dbi->get_target(),$dbi->getdbname());
        break;
        
    case 'PreSync':
        update_timestamps($dbfiles_dir,$logfile);
        store_local_changes($dbfiles_dir,$logfile);
        break;
        
    case 'Sync':
        show_changed_data("local".$time_stamp,true);
        run_sync($dbfiles_dir,$logfile);
        show_changed_data("master".$time_stamp,true);
        break;
        
    case 'All':
        generate_job_files($dbfiles_dir,$dbi->get_source(),$dbi->get_target(),$dbi->getdbname());
        update_timestamps($dbfiles_dir,$logfile);
        store_local_changes($dbfiles_dir,$logfile);
        show_changed_data("local".$time_stamp,false);
        run_sync($dbfiles_dir,$logfile);
        show_changed_data("master".$time_stamp,false);
        break;

    case 'Logs':
        $retVal = '<loglist><logfile data="">None</logfile>';
        foreach (glob("inf_sync_log_*.txt") as $filename) {
            $filename = str_replace(".txt","",$filename);
            $retVal .= '<logfile data="'.str_replace("inf_sync_log_","",$filename).'">'.$filename.'</logfile>';
        }
        $retVal .= '</loglist>';
        print $retVal;
        break;

    case 'Tables':
        $fileroot = $_POST['fileroot'];
        $retVal = '<tablelist><table data="">None</table>';
        foreach (glob("local_".$fileroot."*.xml") as $filename) {
            $filename = str_replace(".xml","",$filename);
            $filename = str_replace("local_","",$filename);
            //$retVal .= '<table data="'.str_replace($fileroot."_","",$filename).'">'.$filename.'</table>';
            $retVal .= '<table data="'.$filename.'">'.str_replace($fileroot."_","",$filename).'</table>';
        }
        $retVal .= '</tablelist>';
        print $retVal;
        break;
        
    case 'Show':
        $fileroot = $_POST['fileroot'];
        $retVal = '<rootTag><subTagOld>';
        $filename = "local_".$fileroot.".xml";
        if (file_exists($filename)) {
            $filecontents = file_get_contents($filename);
            $retVal .= $filecontents;
        }
        $retVal .= '</subTagOld><subTagNew>';
        $filename = "master_".$fileroot.".xml";
        if (file_exists($filename)) {
            $filecontents = file_get_contents($filename);
            $retVal .= $filecontents;
        }
        $retVal .= '</subTagNew></rootTag>';
        print $retVal;
        break;
}

/*
$fh = fopen('synctest.txt', "a");
fwrite($fh, $action);
fwrite($fh,"\n");
fwrite($fh, $retVal); 
fwrite($fh,"\n");
fclose($fh);
*/
?>