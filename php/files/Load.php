<?php
 /* Author Shashi */ 
	include_once (dirname(__FILE__).'/connect.php');

//    set_error_handler("myErrorHandler");		

	//salinate $_POST
	$_POST=$dbi->prepareUserData($_POST);
    $tabname=$_POST['tabname'];
	$retVal="";	

//$dbi->test_file('load.php',$tabname);
	
	//return a list of all the users		
/*	$result =$dbi-> get_user_names();
		
	$retVal.="<usernames>";	
	
	if(!$result)
		$retVal.="<username></username>";	
	 
//---for username and id
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<username data="'.$result.'">'.$value.'</username>';		
	}
		
	$retVal.="</usernames>";*/

/*---dummy for site admin tab*/


//get Fiscal Year
$retVal.="<fiscalYearStart>".$dbi->getFiscalYearStart()."</fiscalYearStart>";

$result =$dbi-> get_type("leave_type","entitlement","name");		
	$retVal.="<leaveEntitlements>";	
	$leaveEntitles='';
	foreach($result as $result=>$value){

		if($result!=0)
			$leaveEntitles.="$value=$result,";
					
	}
	$retVal.=$leaveEntitles;
	$retVal.="</leaveEntitlements>";

if (($tabname == "all") || ($tabname == "site")) {
    $result =$dbi-> get_type("site","id","email_domain");
    
	$retVal.="<site>";
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<email_domain>'.$value.'</email_domain>';				
	}	
	$retVal.="</site>";
}
    
/*---for review_type and id*/

if (($tabname == "all") || ($tabname == "review_type")) {
	$result =$dbi-> get_type("review_type","id","name");
	
	$retVal.="<reviewtypes>";
	$retVal.='<reviewtype data="0">None</reviewtype>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<reviewtype data="'.$result.'">'.$value.'</reviewtype>';				
	}	
	$retVal.="</reviewtypes>";
}

if (($tabname == "all") || ($tabname == "agreement")) {
	$result =$dbi-> get_type("agreement","id","name");
	
	$retVal.="<agreements>";
	$retVal.='<agreement data="0">None</agreement>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<agreement data="'.$result.'">'.$value.'</agreement>';				
	}	
	$retVal.="</agreements>";
}
	
if (($tabname == "all") || ($tabname == "movement_reason")) {		
/*---for movement_reason and id*/
	$result =$dbi-> get_type("movement_reason","id","name");
	
	$retVal.="<movementreasons>";
	$retVal.='<movementreason data="0">None</movementreason>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<movementreason data="'.$result.'">'.$value.'</movementreason>';				
	}	
	$retVal.="</movementreasons>";
}
	
if (($tabname == "all") || ($tabname == "qualification_type")) {	
	/*---for qualification and id*/
	$result =$dbi-> get_type("qualification_type","id","name");
	
	$retVal.="<qualifications>";
	$retVal.='<qualification data="0">None</qualification>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<qualification data="'.$result.'">'.$value.'</qualification>';				
	}	
	$retVal.="</qualifications>";
}
	
if (($tabname == "all") || ($tabname == "speciality_type")) {	
	/*---for speciality and id*/
	$result =$dbi-> get_type("speciality_type","id","name");
	
	$retVal.="<specialities>";
	$retVal.='<speciality data="0">None</speciality>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<speciality data="'.$result.'">'.$value.'</speciality>';				
	}	
	$retVal.="</specialities>";	
}
	
if (($tabname == "all") || ($tabname == "country")) {	
/*---for country and istd*/
	$result =$dbi-> get_type("country","istd_code","name");
	
	$retVal.="<istd_codes>";
	$retVal.='<country data="0">None</country>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<country data="'.$result.'">'.$value.'</country>';				
	}	
	$retVal.="</istd_codes>";

/*---for country and id*/
	$result =$dbi-> get_type("country","id","name");
	
	$retVal.="<countries>";
	$retVal.='<country data="0">None</country>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<country data="'.$result.'">'.$value.'</country>';				
	}	
	$retVal.="</countries>";
}
	
if (($tabname == "all") || ($tabname == "security_role")) {	
/*---for groupNames and id*/
	$result =$dbi-> get_type("security_role","id","name");
	
	$retVal.="<groupnames>";
	$retVal.='<groupname data="0">Select..</groupname>';	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<groupname data="'.$result.'">'.$value.'</groupname>';				
	}	
	$retVal.="</groupnames>";	
}
	
if (($tabname == "all") || ($tabname == "users")) {	
/*---for userNames and id*/
	$result =$dbi-> get_type("users","id","user_name");
	
	$retVal.="<usernames>";
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<username data="'.$result.'">'.$value.'</username>';				
	}	
	$retVal.="</usernames>";	
}
	
if (($tabname == "all") || ($tabname == "religion")) {	
/*---for religions and id*/
	$result =$dbi-> get_type("religion","id","name");	
	
	$retVal.="<religions>";
	$retVal.='<religion data="0">None</religion>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<religion data="'.$result.'">'.$value.'</religion>';			
	}	
	$retVal.="</religions>";
}
	
if (($tabname == "all") || ($tabname == "leave_type")) {		
/*---for leave_type and id*/
	$result =$dbi-> get_type("leave_type","id","name");
		
	$retVal.="<leaveTypes>";
	$retVal.='<leaveType data="0">None</leaveType>';	
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<leaveType data="'.$result.'">'.$value.'</leaveType>';			
	}	
	$retVal.="</leaveTypes>";
}	
	
if (($tabname == "all") || ($tabname == "caste")) {		
/*---for caste and id*/
	$result =$dbi-> get_type("caste","id","name");		
		
	$retVal.="<casts>";	
	$retVal.='<caste data="0">None</caste>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<caste data="'.$result.'">'.$value.'</caste>';			
	}
	
	$retVal.="</casts>";
}

if (($tabname == "all") || ($tabname == "organisation_type")) {		
/*---for organisation type and id*/
	$result =$dbi-> get_type("organisation_type","id","name");				
		
	$retVal.="<organisation_types>";	
	if(mysql_num_rows($result)	==	0)
		$retVal	.=	'<organisation_type></organisation_type>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<organisation_type><id>'.$result.'</id><name>'.$value.'</name></organisation_type>';			
	}
	
	$retVal.="</organisation_types>";
}
	
if (($tabname == "all") || ($tabname == "training") || ($tabname == "personnel_training_needs")) {				
/*---for training and id*/
	$result =$dbi-> get_type("personnel_training_needs","id","name");
		
	$retVal.="<personneltrainingneeds>";	
	$retVal.='<personneltrainingneed data="0">None</personneltrainingneed>';	
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<personneltrainingneed data="'.$result.'">'.$value.'</personneltrainingneed>';		
	}
	
	$retVal.="</personneltrainingneeds>";

	$result =$dbi-> get_type("personnel_training_needs","id","name");		
		
	$retVal.="<trainingneeds>";	
	if(mysql_num_rows($result)	==	0)
		$retVal	.=	'<trainingneed></trainingneed>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			//$retVal.='<caste data="'.$result.'">'.$value.'</caste>';
			$retVal.='<trainingneed><id>'.$result.'</id><name>'.$value.'</name></trainingneed>';			
	}
	
	$retVal.="</trainingneeds>";
}
	
if (($tabname == "all") || ($tabname == "staff_type")) {		
/*---for staff type and id*/
	$result =$dbi-> get_type("staff_type","id","name");
		
	$retVal.="<staffTypes>";	
	$retVal.='<staffType data="0">None</staffType>';	
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<staffType data="'.$result.'">'.$value.'</staffType>';		
	}
	
	$retVal.="</staffTypes>";
}
	
if (($tabname == "all") || ($tabname == "unit")) {		
/*---for staff type and id*/
	$result =$dbi-> get_type("unit","id","name");
		
	$retVal.="<units>";	
	$retVal.='<unit data="0">None</unit>';	
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<unit data="'.$result.'">'.$value.'</unit>';		
	}
	
	$retVal.="</units>";
}
	
if (($tabname == "all") || ($tabname == "section")) {		
/*---for staff type and id*/
	$result =$dbi-> get_type("section","id","name");
		
	$retVal.="<sections>";	
	$retVal.='<section data="0">None</section>';	
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<section data="'.$result.'">'.$value.'</section>';		
	}
	
	$retVal.="</sections>";
}
	
if (($tabname == "all") || ($tabname == "programme")) {		
/*---for programme and id*/
	$result =$dbi-> get_type("programme","id","name");
		
	$retVal.="<programmes>";	
	$retVal.='<programme data="0">None</programme>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<programme data="'.$result.'">'.$value.'</programme>';		
	}
	
	$retVal.="</programmes>";	
}
	
if (($tabname == "all") || ($tabname == "project")) {		
/*---for project and id*/
	$result =$dbi-> get_type("project","id","name");
		
	$retVal.="<projects>";	
	$retVal.='<project data="0">None</project>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<project data="'.$result.'">'.$value.'</project>';		
	}
	
	$retVal.="</projects>";	
}
	
if (($tabname == "all") || ($tabname == "visa")) {		
/*---for title  and number from visa table*/
	$result =$dbi-> get_type("visa","id","name");
	
	$retVal.="<visaIdTitle>";	
	$retVal.='<idTitle data="0">None</idTitle>';	
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<idTitle data="'.$result.'">'.$value.'</idTitle>';			
	}
	
	$retVal.="</visaIdTitle>";
}
	
if (($tabname == "all") || ($tabname == "leaving_reason")) {		
/*---for leaving reason and id*/
	$result =$dbi-> get_type("leaving_reason","id","name");
		
	$retVal.="<leavingReasons>";	
	$retVal.='<reason data="0">None</reason>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)		
			$retVal.='<reason data="'.$result.'">'.$value.'</reason>';		
	}
	
	$retVal.="</leavingReasons>";
}
	
if (($tabname == "all") || ($tabname == "grade")) {			
/*---for grade and id*/
	$result =$dbi-> get_type("grade","id","name");
		
	$retVal.="<grades>";	
	$retVal.='<grade data="0">None</grade>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)				
			$retVal.='<grade data="'.$result.'">'.$value.'</grade>';		
	}
	
	$retVal.="</grades>";	
}
	
if (($tabname == "all") || ($tabname == "post") || ($tabname == "service")) {		
/*---for post and id*/
	//$result =$dbi-> get_type("post","id","name");
    $result =$dbi-> get_values_2("post","id","name,description","active","Yes","type","Internal");
	
	$retVal.="<activeserviceposts>";	
	$retVal.='<servicepost data="0">None</servicepost>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<servicepost data="'.$result.'">'.str_replace(", ",": ",$value).'</servicepost>';		
	}
	
	$retVal.="</activeserviceposts>";	
}

if (($tabname == "all") || ($tabname == "post") || ($tabname == "service")) {		
/*---for post and id*/
	//$result =$dbi-> get_type("post","id","name");
    $result =$dbi-> get_values("post","id","name,description","type","Internal",false);
	
	$retVal.="<serviceposts>";	
	$retVal.='<servicepost data="0">None</servicepost>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<servicepost data="'.$result.'">'.str_replace(", ",": ",$value).'</servicepost>';		
	}
	
	$retVal.="</serviceposts>";	
}

if (($tabname == "all") || ($tabname == "post") || ($tabname == "visapost") || ($tabname == "visa_history")) {		
/*---for post and id*/
	//$result =$dbi-> get_type("post","id","name");
    $result =$dbi-> get_values_2("post","id","name,description","active","Yes","type","Official");
	
	$retVal.="<activevisaposts>";	
	$retVal.='<visapost data="0">None</visapost>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<visapost data="'.$result.'">'.str_replace(", ",": ",$value).'</visapost>';		
	}
	
	$retVal.="</activevisaposts>";	
}

if (($tabname == "all") || ($tabname == "post") || ($tabname == "visapost") || ($tabname == "visa_history")) {		
/*---for post and id*/
	//$result =$dbi-> get_type("post","id","name");
    $result =$dbi-> get_values("post","id","name,description","type","Official",false);
	
	$retVal.="<visaposts>";	
	$retVal.='<visapost data="0">None</visapost>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<visapost data="'.$result.'">'.str_replace(", ",": ",$value).'</visapost>';		
	}
	
	$retVal.="</visaposts>";	
}
	
if (($tabname == "all") || ($tabname == "location")) {		
/*---for location and id*/
	$result =$dbi-> get_type("location","id","name");
	
	$retVal.="<servicelocations>";	
	$retVal.='<servicelocation data="0">None</servicelocation>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)		
			$retVal.='<servicelocation data="'.$result.'">'.$value.'</servicelocation>';		
	}	
	
	$retVal.="</servicelocations>";
}
	
if (($tabname == "all") || ($tabname == "post") || ($tabname == "service")) {	
/*-- name_id and full name of personnel reviewer --*/	
	$result=$dbi->selectPersons('personnel_reviewer');
	$retVal.="<personnelReviewers>";
	$retVal.='<personnelReviewer data="0">None</personnelReviewer>';
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<personnelReviewer data="'.$result.'">'.$value.'</personnelReviewer>';		
	}	
	
	$retVal.="</personnelReviewers>";
	
	/*-- name_id and full name of job reviewer --*/	
	$result=$dbi->selectPersons('job_reviewer');
	$retVal.="<jobReviewers>";
	$retVal.='<jobReviewer data="0">None</jobReviewer>';
		
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<jobReviewer data="'.$result.'">'.$value.'</jobReviewer>';		
	}	
	
	$retVal.="</jobReviewers>";
	
	/*-- name_id and full name of medical reviewer --*/	
	$result=$dbi->selectPersons('medical_reviewer');
	$retVal.="<medicalReviewers>";
	$retVal.='<medicalReviewer data="0">None</medicalReviewer>';
		
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<medicalReviewer data="'.$result.'">'.$value.'</medicalReviewer>';		
	}	
	
	$retVal.="</medicalReviewers>";
    
	/*-- post_id and full name of job reviewer --*/	
	$result=$dbi->selectPosts('job_reviewer');
	$retVal.="<jobManagers>";
	$retVal.='<jobManager data="0">None</jobManager>';
		
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<jobManager data="'.$result.'">'.$value.'</jobManager>';		
	}	
	
	$retVal.="</jobManagers>";
}
	
if (($tabname == "all") || ($tabname == "name")) {		
/*-- name_id and full name --*/
	
	$result=$dbi->get_staff_name_id();
	
	$retVal.="<idFullNames>";
	$retVal.='<idFullName data="0">None</idFullName>';
		
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<idFullName data="'.$result.'">'.$value.'</idFullName>';		
	}	
	
	$retVal.="</idFullNames>";
}
	
if (($tabname == "all") || ($tabname == "illness")) {		
/*-- illness id and full name --*/

	$result =$dbi-> get_type("illness","id","name");
	
	$retVal.="<illnesses>";	
	$retVal.='<illness data="0">None</illness>';	
		
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<illness data="'.$result.'">'.$value.'</illness>';			
	}
		
	$retVal.="</illnesses>";
}
	
if (($tabname == "all") || ($tabname == "hospital")) {		
/*-- hospital id and full name --*/

	$result =$dbi-> get_type("hospital","id","name");
	
	$retVal.="<hospitals>";	
	$retVal.='<hospital data="0">None</hospital>';	
		
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<hospital data="'.$result.'">'.$value.'</hospital>';			
	}
		
	$retVal.="</hospitals>";
}
	
if (($tabname == "all") || ($tabname == "course_type")) {		
/*-- course type id and full name --*/

	$result =$dbi-> get_type("course_type","id","name");
	
	$retVal.="<coursetypes>";	
	$retVal.='<coursetype data="0">None</coursetype>';	
		
	foreach($result as $result=>$value){

		if($result!=0)	
			$retVal.='<coursetype data="'.$result.'">'.$value.'</coursetype>';			
	}
	
	$retVal.="</coursetypes>";

/*-- course type id and full name --*/
}
	
if (($tabname == "all") || ($tabname == "course")) {	
	$result =$dbi-> get_type("course","id","name");
	
	$retVal.="<courses>";	
	$retVal.='<course data="0">None</course>';	
		
	foreach($result as $result=>$value){

		if($result!=0)	
			$retVal.='<course data="'.$result.'">'.$value.'</course>';			
	}
	
	$retVal.="</courses>";
}
	
if (($tabname == "all") || ($tabname == "course_subject_type")) {		
	/*-- course subject  id and full name --*/

	$result =$dbi-> get_type("course_subject_type","id","name");
	
	$retVal.="<course_subjects>";	
	$retVal.='<subject data="0">None</subject>';	
		
	foreach($result as $result=>$value){

		if($result!=0)	
			$retVal.='<subject data="'.$result.'">'.$value.'</subject>';			
	}
	
	$retVal.="</course_subjects>";
}

if (($tabname == "all") || ($tabname == "registration_type")) {		

	$result =$dbi-> get_type("registration_type","id","name");
	
	$retVal.="<registration_types>";	
	$retVal.='<registration_type data="0">None</registration_type>';	
		
	foreach($result as $result=>$value){

		if($result!=0)	
			$retVal.='<registration_type data="'.$result.'">'.$value.'</registration_type>';			
	}
	
	$retVal.="</registration_types>";
}
	
if (($tabname == "all") || ($tabname == "organisation") || ($tabname == "organisation_type")) {		
/*---for organization and id*/
	$retVal.="<organisations>";
	
	$result =$dbi-> get_type("organisation","id","name");
	$retVal.='<organisation data="0">None</organisation>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<organisation data="'.$result.'">'.$value.'</organisation>';	
			
		}
    
    $cat_result =$dbi-> get_type("organisation_type","id","name");
	
    foreach($cat_result as $cat_result=>$cat_value){
		
		if($cat_result!=0) {
            $cat_value=strtolower(str_replace(" ","_",$cat_value)); //replace space by underscore
            $retVal.='<'.$cat_value.' data="0">None</'.$cat_value.'>';
            $org_ids =$dbi-> get_comma_list('organisation_link','organisation_id','organisation_type_id',$cat_result);
            $result =$dbi-> get_values("organisation","id","name","id","$org_ids",false);
            foreach($result as $result=>$value){		
                if($result!=0) {
                    $retVal.='<'.$cat_value.' data="'.$result.'">'.$value.'</'.$cat_value.'>';
                }
            }
        }
    } 
	
	$retVal.="</organisations>";	
	
	/*---for organization address and id*/
	$result =$dbi-> get_values("address","id","address,city_town,state_province","type","Organisation",true);
	//$result =$dbi-> get_values("address","id","address","type","Office",false);
	
	$retVal.="<organisationaddresses>";
	$retVal.='<address data="0">None</address>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<address data="'.$result.'">'.$value.'</address>';				
	}	
	$retVal.="</organisationaddresses>";
	
	/*---for organization phone and id*/
	//$result =$dbi-> get_values("phone","id","phone","type","Office",false);
	$result =$dbi-> get_values("phone","id","phone","type","Organisation",true);
	$retVal.="<organisationphones>";
	$retVal.='<phone data="0">None</phone>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<phone data="'.$result.'">'.$value.'</phone>';				
	}	
	$retVal.="</organisationphones>";
    
	/*---for organization email and id*/
	//$result =$dbi-> get_values("email","id","email","type","Office",false);
	$result =$dbi-> get_values("email","id","email","type","Organisation",true);
	$retVal.="<organisationemails>";
	$retVal.='<email data="0">None</email>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<email data="'.$result.'">'.$value.'</email>';				
	}	
	$retVal.="</organisationemails>";
}
	
if (($tabname == "all") || ($tabname == "location")) {			
	/*---for organization phone and id*/
	//$result =$dbi-> get_values("phone","id","phone","type","Office",false);
	$result =$dbi-> get_values("phone","id","phone","type","Location",true);
	$retVal.="<locationphones>";
	$retVal.='<phone data="0">None</phone>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<phone data="'.$result.'">'.$value.'</phone>';				
	}	
	$retVal.="</locationphones>";
    
	/*---for organization email and id*/
	//$result =$dbi-> get_values("email","id","email","type","Office",false);
	$result =$dbi-> get_values("email","id","email","type","Location",true);
	$retVal.="<locationemails>";
	$retVal.='<email data="0">None</email>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<email data="'.$result.'">'.$value.'</email>';				
	}	
	$retVal.="</locationemails>";
	
	/*---for organization address and id*/
	$result =$dbi-> get_values("address","id","address,city_town,state_province","type","Location",true);
	//$result =$dbi-> get_values("address","id","address","type","Office",false);
	
	$retVal.="<locationaddresses>";
	$retVal.='<address data="0">None</address>';
	
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<address data="'.$result.'">'.$value.'</address>';				
	}	
	$retVal.="</locationaddresses>";
}
	
if (($tabname == "all") || ($tabname == "location")) {		
	/*---for organization address and id*/
	$result =$dbi-> get_type("location","id","name");
	//$result =$dbi-> get_values("address","id","address","type","Office",false);	
	$retVal.="<frommovementlocations>";    
    
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<frommovementlocation data="'.$result.'">'.$value.'</frommovementlocation>';				
	}
	$retVal.='<frommovementlocation data="0">Other</frommovementlocation>';
    $retVal.='<frommovementlocation data="-1">Home Country</frommovementlocation>';

    $result =$dbi-> get_type("country","id","name"); // add country names
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<frommovementlocation data="'.$result.'">'.$value.'</frommovementlocation>';				
	}

	$retVal.="</frommovementlocations>";
    
	$result =$dbi-> get_type("location","id","name");
	//$result =$dbi-> get_values("address","id","address","type","Office",false);	
	$retVal.="<tomovementlocations>";   
    
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<tomovementlocation data="'.$result.'">'.$value.'</tomovementlocation>';				
	}
	$retVal.='<tomovementlocation data="0">Other</tomovementlocation>';
    $retVal.='<tomovementlocation data="-1">Home Country</tomovementlocation>';

    $result =$dbi-> get_type("country","id","name"); // add country names
	foreach($result as $result=>$value){		

		if($result!=0)
			$retVal.='<tomovementlocation data="'.$result.'">'.$value.'</tomovementlocation>';				
	}

	$retVal.="</tomovementlocations>";
}	

//***

	
if (($tabname == "all") || ($tabname == "patient_service_type")) {		
/*---for patient_service_type  and id*/
	$result =$dbi-> get_type("patient_service_type","id","name");		
		
	$retVal.="<patientservicetypes>";	
	$retVal.='<patientservicetype data="0">None</patientservicetype>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<patientservicetype data="'.$result.'">'.$value.'</patientservicetype>';			
	}
	
	$retVal.="</patientservicetypes>";
}

	
if (($tabname == "all") || ($tabname == "patient_surgery_type")) {		
/*---for patient_surgery_type  and id*/
	$result =$dbi-> get_type("patient_surgery_type","id","name");		
		
	$retVal.="<patientsurgerytypes>";	
	$retVal.='<patientsurgerytype data="0">None</patientsurgerytype>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<patientsurgerytype data="'.$result.'">'.$value.'</patientsurgerytype>';			
	}
	
	$retVal.="</patientsurgerytypes>";
}


	
if (($tabname == "all") || ($tabname == "treatment_category")) {		
/*---for treatment_category  and id*/
	$result =$dbi-> get_type("treatment_category","id","name");		
		
	$retVal.="<patienttreatmentcategories>";	
	$retVal.='<patienttreatmentcategory data="0">None</patienttreatmentcategory>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<patienttreatmentcategory data="'.$result.'">'.$value.'</patienttreatmentcategory>';			
	}
	
	$retVal.="</patienttreatmentcategories>";
}

	
if (($tabname == "all") || ($tabname == "referred_from")) {		

	$result =$dbi-> get_type("referred_from","id","name");		
		
	$retVal.="<patientreferredfroms>";	
	$retVal.='<patientreferredfrom data="0">None</patientreferredfrom>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<patientreferredfrom data="'.$result.'">'.$value.'</patientreferredfrom>';			
	}
	
	$retVal.="</patientreferredfroms>";
}
	
if (($tabname == "all") || ($tabname == "requested_from")) {		

	$result =$dbi-> get_type("requested_from","id","name");		
		
	$retVal.="<patientrequestedfroms>";	
	$retVal.='<patientrequestedfrom data="0">None</patientrequestedfrom>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<patientrequestedfrom data="'.$result.'">'.$value.'</patientrequestedfrom>';			
	}
	
	$retVal.="</patientrequestedfroms>";
	
	//$dbi->test_file('load.php',$retVal);
}
	
if (($tabname == "all") || ($tabname == "health_staff_type")) {		

	$result =$dbi-> get_type("health_staff_type","id","name");		
		
	$retVal.="<patienthealthstafftypes>";	
	$retVal.='<patienthealthstafftype data="0">None</patienthealthstafftype>';
	
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<patienthealthstafftype data="'.$result.'">'.$value.'</patienthealthstafftype>';			
	}
	
	$retVal.="</patienthealthstafftypes>";
}
	
if (($tabname == "all") || ($tabname == "patient_appliance_type")) {		
/*-- patient_appliance_type id and name --*/

	$result =$dbi-> get_type("patient_appliance_type","id","name");
	
	$retVal.="<patientappliancetypes>";	
	$retVal.='<patientappliancetype data="0">None</patientappliancetype>';	
		
	foreach($result as $result=>$value){

		if($result!=0)
			$retVal.='<patientappliancetype data="'.$result.'">'.$value.'</patientappliancetype>';			
	}
		
	$retVal.="</patientappliancetypes>";
}
	
if (($tabname == "all") || ($tabname == "treatment_result") || ($tabname == "treatment_category")) {		
/*---for treatment_result and id*/
	$result =$dbi-> get_type("treatment_result","id","name");
		
	$retVal.="<patienttreatmentresults>";	
	$retVal.='<patienttreatmentresult data="0">None</patienttreatmentresult>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<patienttreatmentresult data="'.$result.'">'.$value.'</patienttreatmentresult>';		
	}

    $retVal.='<None><treatmentresult data="0">None</treatmentresult></None>';
    
    $cat_result =$dbi-> get_type("treatment_category","id","name");
	
    foreach($cat_result as $cat_result=>$cat_value){
		
		if($cat_result!=0) {
        
            $retVal.='<'.$cat_value.'>';
            $retVal.='<treatmentresult data="0">None</treatmentresult>';
            $result =$dbi-> get_values("treatment_result","id","name","treatment_category_id","$cat_result",false);
            foreach($result as $result=>$value){		
                if($result!=0)
                    $retVal.='<treatmentresult data="'.$result.'">'.$value.'</treatmentresult>';
            }
            $retVal.='</'.$cat_value.'>';
        }
    }
	
	$retVal.="</patienttreatmentresults>";	
}
	
if (($tabname == "all") || ($tabname == "treatment_case") || ($tabname == "treatment_category")) {	
/*---for treatment_case and id*/
	$result =$dbi-> get_type("treatment_case","id","name");
		
	$retVal.="<patienttreatmentcases>";	
	$retVal.='<patienttreatmentcase data="0">None</patienttreatmentcase>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<patienttreatmentcase data="'.$result.'">'.$value.'</patienttreatmentcase>';		
	}

    $retVal.='<None><treatmentcase data="0">None</treatmentcase></None>';
    
    $cat_result =$dbi-> get_type("treatment_category","id","name");
	
    foreach($cat_result as $cat_result=>$cat_value){
		
		if($cat_result!=0) {
        
            $retVal.='<'.$cat_value.'>';
            $retVal.='<treatmentcase data="0">None</treatmentcase>';
            $result =$dbi-> get_values("treatment_case","id","name","treatment_category_id","$cat_result",false);
            foreach($result as $result=>$value){		
                if($result!=0)
                    $retVal.='<treatmentcase data="'.$result.'">'.$value.'</treatmentcase>';
            }
            $retVal.='</'.$cat_value.'>';
        }
    }
	
	$retVal.="</patienttreatmentcases>";	
}
	
if (($tabname == "all") || ($tabname == "treatment_reason") || ($tabname == "treatment_category")) {		
/*---for treatment_reason and id*/
	$result =$dbi-> get_type("treatment_reason","id","name");
		
	$retVal.="<patienttreatmentreasons>";	
	$retVal.='<patienttreatmentreason data="0">None</patienttreatmentreason>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<patienttreatmentreason data="'.$result.'">'.$value.'</patienttreatmentreason>';		
	}
	
	$retVal.="</patienttreatmentreasons>";	
	
	//get main treatment reason
	$result =$dbi-> get_values("treatment_reason","id","name","main","Yes",false);
	
	$retVal.="<mainpatienttreatmentreasons>";	
	$retVal.='<mainpatienttreatmentreason data="0">None</mainpatienttreatmentreason>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<mainpatienttreatmentreason data="'.$result.'">'.$value.'</mainpatienttreatmentreason>';		
	}
	
	$retVal.="</mainpatienttreatmentreasons>";	
	
	//get detail treatment reason
	
	$result =$dbi-> get_values("treatment_reason","id","name","main","No",false);
	
	$retVal.="<detailpatienttreatmentreasons>";
    
    if(mysql_num_rows($result)	==	0)
		$retVal	.=	'<detailpatienttreatmentreason></detailpatienttreatmentreason>';
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<detailpatienttreatmentreason><id>'.$result.'</id><name>'.$value.'</name></detailpatienttreatmentreason>';
    }
    
    $retVal.='<None><treatmentreason></treatmentreason></None>';
    
    $cat_result =$dbi-> get_values("treatment_reason","id","name","main","Yes",false);
    $i=0;    
    foreach($cat_result as $cat_result=>$cat_value){
		
		if($cat_result!=0) {
        
            $retVal.='<'.$cat_value.'>';
            $result =$dbi-> get_detail_reasons("$cat_result");
            //$cat_result2 =$dbi-> get_values("treatment_reason","treatment_category_id","name","name","$cat_value",false);
            //$retVal.='<id></id><name></name>';
            //$result =$dbi-> get_values("treatment_reason","id","name","treatment_category_id","$cat_result2",false);
            
            if(mysql_num_rows($result)==0)
                $retVal	.=	'<treatmentreason></treatmentreason>';
                
            foreach($result as $result=>$value){		
                if(($result!=0))
                    $retVal.='<treatmentreason><id>'.$result.'</id><name>'.$value.'</name></treatmentreason>';
            }
            $retVal.='</'.$cat_value.'>';
        }
        $i++;
    }

	//
	$retVal.="</detailpatienttreatmentreasons>";		
}
	
if (($tabname == "all") || ($tabname == "treatment_regimen") || ($tabname == "treatment_category")) {	
/*---for treatment_result and id*/
	$result =$dbi-> get_type("treatment_regimen","id","name");
		
	$retVal.="<patienttreatmentregimens>";	
	$retVal.='<patienttreatmentregimen data="0">None</patienttreatmentregimen>';	
	
	foreach($result as $result=>$value){
		
		if($result!=0)
			$retVal.='<patienttreatmentregimen data="'.$result.'">'.$value.'</patienttreatmentregimen>';		
	}	
    
    $retVal.='<None><treatmentregimen data="0">None</treatmentregimen></None>';
    
    $cat_result =$dbi-> get_type("treatment_category","id","name");
	
    foreach($cat_result as $cat_result=>$cat_value){
		
		if($cat_result!=0) {
        
            $retVal.='<'.$cat_value.'>';
            $retVal.='<treatmentregimen data="0">None</treatmentregimen>';
            $result =$dbi-> get_values("treatment_regimen","id","name","treatment_category_id","$cat_result",false);
            foreach($result as $result=>$value){		
                if($result!=0)
                    $retVal.='<treatmentregimen data="'.$result.'">'.$value.'</treatmentregimen>';
            }
            $retVal.='</'.$cat_value.'>';
        }
    }
	
	$retVal.="</patienttreatmentregimens>";	    
}

/*-- finished --*/
	
	$dbi->disconnect();		

print $retVal;

/*JUST FOR TESTING PURPOSE*/
/*
$fh = fopen('loadtest.txt', "a"); 
fwrite($fh, $tabname.": ".$retVal); 
fwrite($fh,"\n");
fclose($fh); 
*/	

?>