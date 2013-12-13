// ActionScript file
import mx.messaging.channels.StreamingAMFChannel;


	[Bindable]public var arrSelectedFields:Array=new Array();	
	[Bindable]public var mainApp:Object = null; 
	[Bindable]public var parentINF:Object = null; 
	[Bindable]public var defaultSearchFields:Array;
	//public var siteType:String=''; 
	public var dgSelectedFields:DataGrid;
	public var comboMainTabs:ComboBoxNew;
	public var comboLeafTabs:ComboBoxNew;
	//public var dgList:DataGrid;
	
	private var arrPersonalWorld:Array = [ { fields: "surname",data:"surname"},{ fields: "forenames",data:"name"},{ fields: "relationship",data:"relation"},{ fields: "title",data:"name"},{ fields: "known_as",data:"name"},{ fields: "gender",data:"name"},	{ fields: "dob",data:"name"},{ fields: "marital_status",data:"name"},
		{ fields: "blood_group",data:"name"},{ fields: "birth_country",data:"name"},{ fields: "birth_town",data:"name"},{ fields: "birth_district",data:"name"},
		{ fields: "religion",data:"inf_staff"},{ fields: "ethnicity",data:"inf_staff"},{ fields: "nationality",data:"nationality"},
		{ fields: "embassy_reg",data:"inf_staff"},{ fields: "embassy",data:"inf_staff"}];

	private var arrPersonalNepal:Array = [ { fields: "surname",data:"surname"},{ fields: "forenames",data:"name"},{ fields: "relationship",data:"relation"},{ fields: "title",data:"name"},{ fields: "known_as",data:"name"},{ fields: "gender",data:"name"},	{ fields: "dob",data:"name"},{ fields: "marital_status",data:"name"},
		{ fields: "blood_group",data:"name"},{ fields: "birth_country",data:"name"},{ fields: "birth_town",data:"name"},{ fields: "birth_district",data:"name"},
		{ fields: "religion",data:"inf_staff"},{ fields: "ethnicity",data:"inf_staff"},{ fields: "nationality",data:"nationality"},{ fields: "citizenship",data:"inf_staff"},];

	private var arrPersonalPatient:Array = [ { fields: "surname",data:"surname"},{ fields: "forenames",data:"name"},{ fields: "relationship",data:"relation"},{ fields: "title",data:"name"},{ fields: "known_as",data:"name"},{ fields: "gender",data:"name"},	{ fields: "dob",data:"name"},{ fields: "marital_status",data:"name"},
		{ fields: "blood_group",data:"name"},{ fields: "birth_country",data:"name"},{ fields: "birth_town",data:"name"},{ fields: "birth_district",data:"name"},
		{ fields: "religion",data:"patient_inf"},{ fields: "ethnicity",data:"patient_inf"}];
		
	private var arrPersonal:Array = new Array();
	private var tabAll:Array	=	new Array();
				
	private var arrAddress:Array=[{ fields: "address",data:"address"},{ fields: "include_all",data:"address"},{ fields: "include_family",data:"address"},{ fields: "city_town",data:"address"},{ fields: "state_province",data:"address"},{ fields: "type",data:"address"},{ fields: "postcode_zip",data:"address"},{ fields: "latitude",data:"address"},
	{ fields: "longitude",data:"address"},{ fields: "country",data:"address"}];
	private var arrPhoto:Array=[{ fields: "description",data:"photo"}];
	private var arrEmail:Array=[{ fields: "email",data:"email"},{ fields: "include_all",data:"email"},{ fields: "include_family",data:"email"},{ fields: "type",data:"email"}];
	private var arrPhone:Array=[{ fields: "type",data:"phone"},{ fields: "phone",data:"phone"},{ fields: "include_all",data:"phone"},{ fields: "include_family",data:"phone"},{ fields: "extn",data:"phone"} ,{ fields: "shared",data:"phone"},{ fields: "country",data:"phone"}];
	private var arrEducation:Array=[{fields: "start_date",data:"education"},{fields: "end_date",data:"education"} ,{fields: "qualification_level",data:"education"} ,{fields: "division_grade",data:"education"},{fields: "institution",data:"education"} ,{fields: "speciality_type",data:"education"},{fields: "qualification_type",data:"education"} ];
	private var arrPassport:Array=[{ fields: "number",data:"passport"},{ fields: "issue_date",data:"passport"},{ fields: "expiry_date",data:"passport"},{ fields: "issue_city",data:"passport"},{ fields: "issue_country",data:"passport"},{ fields: "passport_country",data:"passport"}];	
	private var arrVisaHistory:Array=[{ fields: "visa_post",data:"visa_history"},{ fields: "number",data:"visa_history"},{ fields: "issue_date",data:"visa_history"},{ fields: "expiry_date",data:"visa_history"},{ fields: "entry_date",data:"visa_history"},{ fields: "issue_city",data:"visa_history"},{ fields: "issue_country",data:"visa_history"},{ fields: "visa_status",data:"visa_history"},{ fields: "type",data:"visa_history"},{ fields: "subtype",data:"visa_history"},{ fields: "postholder",data:"visa_history"}];	

	private var arrWorkExperience:Array=
		[{ fields: "start_date",data:"work_experience"},{ fields: "end_date",data:"work_experience"}
        ,{ fields: "workplace",data:"work_experience"},{ fields: "job_title",data:"work_experience"}
        ,{ fields: "description",data:"work_experience"},{fields: "city_town",data:"work_experience"}
        ,{ fields: "country",data:"work_experience"},{ fields: "leaving_reason",data:"work_experience"}
        ];
        	
	private var arrDocumentation:Array=
		[{ fields: "cv_recvd_date",data:"documentation"},{ fields: "application_recvd_date",data:"documentation"},{ fields: "medical_recvd_date",data:"documentation"},{ fields: "medical_to_MO_date",data:"documentation"},{ fields: "medical_accepted_date",data:"documentation"},{ fields: "medical_accepted_by",data:"documentation"}
		,{ fields: "psych_recvd_date",data:"documentation"},{ fields: "psych_to_MO_date",data:"documentation"},{ fields: "psych_from_MO_date",data:"documentation"},{ fields: "psych_to_PD_date",data:"documentation"},{ fields: "psych_accepted_date",data:"documentation"},{ fields: "employer_ref_recvd_date",data:"documentation"}		
		,{ fields: "colleague_ref_recvd_date",data:"documentation"},{ fields: "friend_ref_recvd_date",data:"documentation"}	,{ fields: "minister_ref_recvd_date",data:"documentation"},{ fields: "interview_recvd_date",data:"documentation"},{ fields: "secondment_accepted_date",data:"documentation"},{ fields: "INF_role_agreed_date",data:"documentation"}
		,{ fields: "INF_role_agreed",data:"documentation"},{ fields: "contract_recvd_date",data:"documentation"},{ fields: "certificates_recvd_date",data:"documentation"},{ fields: "photos_recvd_date",data:"documentation"},{ fields: "passport_recvd_date",data:"documentation"},
		{ fields: "professional_recvd_date",data:"documentation"} ,{ fields: "link_person",data:"documentation"}
		];    
		       	
	private var arrOrientation:Array=[
	    { fields: "email_address_requested_date",data:"orientation"}, 
	     { fields: "email_address_created_date",data:"orientation"},
		{ fields: "email_installed_date",data:"orientation"},
		{ fields: "LOT_duration_discussed_date",data:"orientation"},   
	    { fields: "LOT_requested_date",data:"orientation"},
	    { fields: "LOT_confirmed_date",data:"orientation"},
	    
	    { fields: "ktm_LOT_scheduled_date",data:"orientation"},
	    { fields: "ktm_LOT_confirmed_date",data:"orientation"},
	    { fields: "dates_of_ktm_LOT",data:"orientation"},   
	    { fields: "housing_preferences_date",data:"orientation"},
	    { fields: "pkr_LOT_housing_arranged_date",data:"orientation"},
	  //  { fields: "pkr_LOT_housing_details_date",data:"orientation"},   
	    { fields: "housing_requested_date",data:"orientation"},
	    { fields: "housing_arranged_date",data:"orientation"},	
	    { fields: "housing_confirmed_date",data:"orientation"},
		//{ fields: "housing_details",data:"orientation"},
		//{ fields: "arrival_flight",data:"orientation"},
		//{ fields: "arrival_date",data:"orientation"},	
		//{ fields: "arrival_time",data:"orientation"},
		//{ fields: "pickup_arranged_date",data:"orientation"},
		//{ fields: "accomodation_arranged_date",data:"orientation"}, 
	  //  { fields: "acco_details",data:"orientation"},
	   // { fields: "bus_ticket_arranged_date",data:"orientation"},
	    //{ fields: "ticket_info_sent_to_pkr_date",data:"orientation"},   
	    //{ fields: "survival_orientation_booklet_date",data:"orientation"},
	    //{ fields: "welcome_pack_date",data:"orientation"},
	   // { fields: "welcome_letter_date",data:"orientation"},	
	    { fields: "link_names_sent_date",data:"orientation"},
	    
	    { fields: "ktm_link_person",data:"orientation"}, //points to link person id
		{ fields: "pkr_link_person",data:"orientation"}, //points to link person id
		{ fields: "work_link_person",data:"orientation"}, //points to link person id
		{ fields: "housing_link_person",data:"orientation"}, //points to link person id
		{ fields: "school_children_link_person",data:"orientation"} //points to link person id	   	
	];
	
	private var arrOrientationArrangement:Array=[
	   	{ fields: "arrival_flight",data:"orientation_arrangement"},
		{ fields: "arrival_date",data:"orientation_arrangement"},	
		{ fields: "arrival_time",data:"orientation_arrangement"},
		{ fields: "pickup_arranged_date",data:"orientation_arrangement"},
		{ fields: "accomodation_arranged_date",data:"orientation_arrangement"}, 
	  //  { fields: "acco_details",data:"orientation_arrangement"},
	    { fields: "bus_ticket_arranged_date",data:"orientation_arrangement"},
	    { fields: "ticket_info_sent_to_pkr_date",data:"orientation_arrangement"},   
	    { fields: "survival_orientation_booklet_date",data:"orientation_arrangement"},
	    { fields: "welcome_pack_date",data:"orientation_arrangement"},
	    { fields: "welcome_letter_date",data:"orientation_arrangement"},		    
	];	
	
/*	private var arrSecondment1:Array=[
	{ fields: "OSA_status",data:"secondment"},
    { fields: "OSA_date",data:"secondment"},
    { fields: "FSA_status",data:"secondment"},
    { fields: "FSA_date",data:"secondment"},
    { fields: "seconded_from",data:"secondment"},
    { fields: "seconded_to",data:"secondment"},
    { fields: "local_support",data:"secondment"},
    { fields: "church",data:"secondment"},
	];
	
*/	
	private var arrSecondment:Array=[
	{ fields:"seconded_from",data:"secondment"},
    { fields:"seconded_to",data:"secondment"},
    { fields:"local_support",data:"secondment"},
    { fields:"church",data:"secondment"},   	  
    
    { fields:"osa_member_sent_date",data:"secondment"},
    { fields:"osa_member_reveived_date",data:"secondment"},
    { fields:"osa_member_copy_sent_date",data:"secondment"},
    { fields:"osa_member_signed_date",data:"secondment"},
    
    { fields:"osa_agency_sent_date",data:"secondment"},
    { fields:"osa_agency_reveived_date",data:"secondment"},
    { fields:"osa_agency_copy_sent_date",data:"secondment"},
    { fields:"osa_agency_signed_date",data:"secondment"},
    
    { fields:"osa_infw_sent_date",data:"secondment"},
    { fields:"osa_infw_reveived_date",data:"secondment"},
    { fields:"osa_infw_copy_sent_date",data:"secondment"},
    { fields:"osa_infw_signed_date",data:"secondment"},
    
    { fields:"fsa_member_sent_date",data:"secondment"},
    { fields:"fsa_member_reveived_date",data:"secondment"},
    { fields:"fsa_member_copy_sent_date",data:"secondment"},
    { fields:"fsa_member_signed_date",data:"secondment"},
    
    { fields:"fsa_agency_sent_date",data:"secondment"},
    { fields:"fsa_agency_reveived_date",data:"secondment"},
    { fields:"fsa_agency_copy_sent_date",data:"secondment"},
    { fields:"fsa_agency_signed_date",data:"secondment"},
    
    { fields:"fsa_infw_sent_date",data:"secondment"},
    { fields:"fsa_infw_reveived_date",data:"secondment"},
    { fields:"fsa_infw_copy_sent_date",data:"secondment"},
    { fields:"fsa_infw_signed_date",data:"secondment"},
    { fields:"further_seconded_to",data:"secondment"},//points to respective id
    { fields:"post_agreed",data:"post"},//points to respective id
    { fields:"link_person",data:"name"},//points to respective id
    
    { fields:"osa_approval_agency",data:"secondment"},//points to respective id
    { fields:"fsa_approval_agency",data:"secondment"},//points to respective id
    { fields:"osa_approval_infw",data:"secondment"},//points to respective id
    { fields:"fsa_approval_infw",data:"secondment"} //points to respective id   				
	];
	
	private var arrStaff:Array=[
	{fields: "staff_type",data:"staff"},
	{fields: "staff_number",data:"staff"},
    {fields: "start_date",data:"staff"},
    {fields: "probation_end_date",data:"staff"},
    {fields: "next_review_due",data:"staff"},
    {fields: "retirement_date",data:"staff"},
    {fields: "leaving_date",data:"staff"},
    {fields: "leaving_reason",data:"staff"},
    {fields: "programme",data:"staff"},
    //{fields: "status",data:"staff"}, 
	];//staff_type points to staff_type_id
	
	private var arrInsurance:Array=[
    {fields:"company",data:"insurance"}, 
    {fields:"reference",data:"insurance"}, 
    {fields:"contact",data:"insurance"}, 
    {fields:"start_date",data:"insurance"}, 
    {fields:"end_date",data:"insurance"},
    {fields:"insurance_class",data:"insurance"}, 
    {fields:"premium",data:"insurance"}, 
    {fields:"terrorism",data:"insurance"},   
	];
	
	private var arrRegistrations:Array=[
    {fields:"registration_type",data:"registrations"},
    {fields:"organisation",data:"registrations"}, 
    {fields:"reference",data:"registrations"}, 
    {fields:"start_date",data:"registrations"}, 
    {fields:"end_date",data:"registrations"},   
	];

	private var arrService:Array=[
	{fields: "INF_role",data:"service"},
	{fields: "grade",data:"service"},
    {fields: "location",data:"service"},		
	{fields: "date_from",data:"service"},
	{fields: "date_until",data:"service"},
	{fields: "percent_of_time",data:"service"},
	{fields: "special_contract",data:"service"},
	{fields: "working_week",data:"service"},
	];
	
	private var arrMovement:Array=[
	{ fields: "address",data:"movement"},
	{ fields: "email",data:"movement"},
	{ fields: "date_from",data:"movement"},
	{ fields: "date_until",data:"movement"},
	{ fields: "dates_fixed",data:"movement"},
	{ fields: "travel_from",data:"movement"},
	{ fields: "travel_to",data:"movement"},
	{ fields: "reason",data:"movement"},
	{ fields: "notes",data:"movement"}
	];//address points to address_id, email points to email_id
	
	private var arrLeave:Array=[
	{ fields: "date_from",data:"leave"},
	{ fields: "date_until",data:"leave"},
	{ fields: "leave_type",data:"leave"},
	{ fields: "replacement",data:"leave"}
	];
	
	private var arrTraining:Array=[
	{fields: "course",data:"training"},
	{fields: "training_need",data:"training"},
	{fields: "career_aspirations",data:"training"},
	{fields: "career_development",data:"training"}
	//{fields: "trainee",data:"personnel_training_needs"}
	];
	
	private var arrHospitalisation:Array=[
	{fields: "date_from",data:"hospitalisation"},
	{fields: "date_until",data:"hospitalisation"},
    {fields: "illness",data:"hospitalisation"},
    {fields: "births",data:"hospitalisation"},
    {fields: "cost",data:"hospitalisation"},
    {fields: "hospital_admitted",data:"hospitalisation"},
    {fields: "relative",data:"hospitalisation"}
	];//hospital_admitted points to hospital_id,relative points to relative_id
	
	private var arrAnnualReview:Array=[
	{ fields: "comments",data:"review"},  
	{ fields: "review_date",data:"review"},
	{ fields: "review_type",data:"review"},
	{ fields: "reviewer",data:"review"}
	];
	
	private var arrHomeAssignment:Array=[
	{ fields: "date_from",data:"home_assignment"},   
	{ fields: "date_until",data:"home_assignment"},
	{ fields: "interviewer",data:"home_assignment"},  
	{ fields: "interview_date",data:"home_assignment"},
	{ fields: "rsent_date_member",data:"home_assignment"},
	{ fields: "rsent_date_agency",data:"home_assignment"},		
	{ fields: "medical_arranged",data:"home_assignment"},  
	{ fields: "medical_report_received",data:"home_assignment"},
	{ fields: "msent_date_agency",data:"home_assignment"},  
	{ fields: "infopack_sent",data:"home_assignment"},	
	{ fields: "report_received",data:"home_assignment"},   
	{ fields: "STV_manager_comments_received",data:"home_assignment"},	
	{ fields: "invitation_letter_received",data:"home_assignment"},
	{ fields: "notes",data:"home_assignment"}
	];	
	
	private var arrStaffDetails:Array=[
	{fields: "staff_type",data:"health_staff"}
	];
	
	private var arrVisitDetails:Array=[
	{fields: "hospital",data:"patient_visit"},
	{fields: "patient_number",data:"patient_visit"},
    {fields: "type",data:"patient_visit"},
    {fields: "affected",data:"patient_visit"},
    {fields: "date_referred",data:"patient_visit"},
    {fields: "referred_from",data:"patient_visit"},
    {fields: "date_attended",data:"patient_visit"},
    {fields: "date_discharged",data:"patient_visit"},
    {fields: "main_reason",data:"patient_visit"},
    {fields: "detail_reason",data:"patient_visit"},
    {fields: "discharged_to",data:"patient_visit"},
    {fields: "new_case",data:"patient_inf"},
    {fields: "PAL",data:"patient_inf"},
    {fields: "PWD",data:"patient_inf"},
    {fields: "care_after_cure",data:"patient_inf"},
    {fields: "footwear_needed",data:"patient_inf"}
	];
	
	private var arrTreatmentDetails:Array=[
	{fields: "category",data:"treatment"},	  
    {fields: "date_started",data:"treatment"},   
    {fields: "date_ended",data:"treatment"},     
    {fields: "case",data:"treatment"},        
    {fields: "regimen",data:"treatment"},	      
    {fields: "result",data:"treatment"},
	];
	
	private var arrServicesGiven:Array=[
	{fields: "date_given",data:"patient_service"},
    {fields: "service_type",data:"patient_service"}
	];
	
	private var arrSurgeryGiven:Array=[
	{fields: "date_given",data:"patient_surgery"},
    {fields: "surgery_type",data:"patient_surgery"}
	];
	
	private var arrAppliancesGiven:Array=[
	{fields: "date_given",data:"patient_appliance"},
    {fields: "appliance_type",data:"patient_appliance"},
    {fields: "requested_from",data:"patient_appliance"}
	];
	private var arrBillingDetails:Array=[
	{fields: "date_paid",data:"patient_bill"},
    {fields: "amount",data:"patient_bill"},
    {fields: "paid_by",data:"patient_bill"}
	];

//	private var mainTabs:Array 		=	[{label:"Biodata",data:tabBiodata},{label:"Application",data:tabApplication},{label:"Service",data:tabService},{label:"Patient",data:tabPatient},{label:"Health Staff",data:tabHealthStaff}];

	//private var mainPatientTabs:Array 		=	[{label:"Biodata",data:tabBiodata},{label:"Patient",data:tabPatient},{label:"Health Staff",data:tabHealthStaff}];	
	//private var mainINFPersonnelTabs:Array 		=	[{label:"Biodata",data:tabBiodata},{label:"Application",data:tabApplication},{label:"Service",data:tabService}];
	private var mainTabs:Array 		=	new Array();
	
	private var tabBiodata:Array	=	new Array();
	private var tabApplication:Array=	new Array();
	private var tabService:Array	=	new Array();
	private var tabPatient:Array	=	new Array();	
	private var tabHealthStaff:Array=	new Array();
	
	private var tabBiodataList:Array	=	new Array();
	private var tabApplicationList:Array=	new Array();
	private var tabServiceList:Array	=	new Array();
	private var tabPatientList:Array	=	new Array();	
	private var tabHealthStaffList:Array=	new Array();
	
	private function getTabTitle(tabName:String):String{
		
		var tabTitle:String = tabName.substr(0,1).toUpperCase()+tabName.substr(1,tabName.length-1);
		tabTitle = tabTitle.replace('_',' ');
		return tabTitle;
	}
	
	private function getTableName(tabTitle:String):String{
		
		var tableName:String = tabTitle.toLowerCase().replace(' ','_');
		return tableName;
	}
	
	private function pushMainTabs():void{
		
		tabBiodataList = parentApplication.biodatas.getChildLabels().split(",");
			
		if (parentApplication.patientSystem) {
			arrPersonal = arrPersonalPatient;
			tabPatientList = parentApplication.patient.getChildLabels().split(",");
			tabHealthStaffList = parentApplication.staff.getChildLabels().split(",");
		}
		if (parentApplication.personnelNepalSystem) {
			arrPersonal = arrPersonalNepal;
			//tabApplicationList = parentApplication.app.getChildLabels().split(",");
			tabServiceList = parentApplication.tests.getChildLabels().split(",");
		}
		if (parentApplication.personnelWorldwideSystem) {
			arrPersonal = arrPersonalWorld;
			tabApplicationList = parentApplication.app.getChildLabels().split(",");
			tabServiceList = parentApplication.tests.getChildLabels().split(",");
		}
	
		tabAll['personal']=arrPersonal;
		tabAll['address']=arrAddress;
		tabAll['email']=arrEmail;
		tabAll['phone']=arrPhone;
		tabAll['photo']=arrPhoto;
		tabAll['passport']=arrPassport;
		tabAll['visa_history']=arrVisaHistory;
		
		tabAll['documentation']=arrDocumentation;
		tabAll['secondment']=arrSecondment;
		tabAll['education']=arrEducation;
		tabAll['orientation']=arrOrientation;
		tabAll['orientation_arrangement']=arrOrientationArrangement;
		tabAll['work_experience']=arrWorkExperience;
		
		tabAll['staff']=arrStaff;
		tabAll['service']=arrService;
		tabAll['movement']=arrMovement;
		tabAll['leave']=arrLeave;
		tabAll['training']=arrTraining;
		tabAll['hospitalisation']=arrHospitalisation;
		tabAll['annual_review']=arrAnnualReview;
		tabAll['home_assignment']=arrHomeAssignment;
		tabAll['insurance']=arrInsurance;
		tabAll['registrations']=arrRegistrations;
		
		tabAll['visit']=arrVisitDetails;
		tabAll['treatment']=arrTreatmentDetails;
		tabAll['services']=arrServicesGiven;
		tabAll['surgery']=arrSurgeryGiven;
		tabAll['appliances']=arrAppliancesGiven;
		tabAll['billing']=arrBillingDetails;
		
		tabAll['health_staff']=arrStaffDetails;
		
		for (var i:Number = 0; i < tabBiodataList.length; i++) {
          	if (parentApplication.getuserLoginInfo(tabBiodataList[i]+'_view')=='y')
          		tabBiodata.push({label:getTabTitle(tabBiodataList[i]),data:tabAll[tabBiodataList[i]]});
     	}
     	
     	for (var j:Number = 0; j < tabApplicationList.length; j++) {
          	if (parentApplication.getuserLoginInfo(tabApplicationList[j]+'_view')=='y')
          		tabApplication.push({label:getTabTitle(tabApplicationList[j]),data:tabAll[tabApplicationList[j]]});
     	}
		
		for (var k:Number = 0; k < tabServiceList.length; k++) {
          	if (parentApplication.getuserLoginInfo(tabServiceList[k]+'_view')=='y')
          		tabService.push({label:getTabTitle(tabServiceList[k]),data:tabAll[tabServiceList[k]]});
     	}
    	
    	for (var l:Number = 0; l < tabPatientList.length; l++) {
          	if (parentApplication.getuserLoginInfo(tabPatientList[l]+'_view')=='y')
          		tabPatient.push({label:getTabTitle(tabPatientList[l]),data:tabAll[tabPatientList[l]]});
     	}
     	
     	for (var m:Number = 0; m < tabHealthStaffList.length; m++) {
          	if (parentApplication.getuserLoginInfo(tabHealthStaffList[m]+'_view')=='y')
          		tabHealthStaff.push({label:getTabTitle(tabHealthStaffList[m]),data:tabAll[tabHealthStaffList[m]]});
     	}
     	
		mainTabs.push({label:"Biodata",data:tabBiodata});
		if (parentApplication.patientSystem) {
			mainTabs.push({label:"Patient",data:tabPatient});
			mainTabs.push({label:"Health Staff",data:tabHealthStaff});
		}
		else {
			if (parentApplication.personnelWorldwideSystem)
				mainTabs.push({label:"Application",data:tabApplication});
			mainTabs.push({label:"Service",data:tabService});
		}
	}
	
	override protected function loadData(current:Boolean=false):void{
		super.loadData();
	//Alert.show(parentApplication.getuserLoginInfo('siteType'));
	
		pushMainTabs();
	
		comboLeafTabs.dataProvider	=	tabBiodata;
		comboMainTabs.dataProvider	=	mainTabs;
		dgList.dataProvider=arrPersonal;	
		btnCancel.label = "Close"; 
		btnOk.label = "Apply";			
	}
	
	private var tabToTableMap:Object = {'Annual Review':"review",'Visit':"patient_visit",
	 	'Services':"patient_service",'Surgery':"patient_surgery",
	 	'Appliances':"patient_appliance",'Billing':"patient_bill"};

	override protected function sendData():void {
 
				defaultSearchFields=defaultSearchFields.concat(arrSelectedFields);
		//need to remove duplicate fields	
				defaultSearchFields= removeDuplicate(defaultSearchFields);
				
                mainApp.dg.dataProvider	     =	defaultSearchFields ;
                mainApp.currentSearchFields  =	defaultSearchFields ;  //retains fields used in previous popup 
        		
        		if(tabToTableMap[comboLeafTabs.text])        	
         			mainApp.moreFieldsFrom		+= tabToTableMap[comboLeafTabs.text]+',';     
         		else      
               		mainApp.moreFieldsFrom		+= getTableName(comboLeafTabs.text)+',';
                
                comboMainTabs.enabled=true;
                comboLeafTabs.enabled=true;
                arrSelectedFields=new Array();
                dgSelectedFields.dataProvider=new Array(); 
        
                //removeMe();
    }   
 
   	private function removeDuplicate(myArray:Array):Array {
   		
     	for (var i:Number = 0; i < myArray.length; i++) {
          	for (var j:Number = i + 1; j < myArray.length; j++) {
          	//srchValue
          	
          		if (myArray[i].operation == myArray[j].operation)
          			if (myArray[i].srchValue == myArray[j].srchValue) 
               			if (myArray[i].fields == myArray[j].fields) 
               				if (myArray[i].data == myArray[j].data)
                    			myArray.splice(j, 1);
               
          	}
     	}
     	return myArray;//unique array
	}

    override protected function viewMode(fromCancel:Boolean=true):void{
    	mainApp.arrMoreSearchFields= new Array();//empty array
    	removeMe();
    }
    
    public function pushSelectedField(event:MouseEvent):void
	{	
		var selectedRow:Object; 
		var uniqueString:String=new Date().getTime().toString();

		/*if(event==null){
			if(!dgList.selectedItem)
				Alert.show("Please select the row");		
			else
				selectedRow = dgList.selectedItem;	
        		
		}
		else
		*/
  		  selectedRow = event.currentTarget.selectedItem;
  		 if(selectedRow)
  		  { 
  					//  Alert.show(selectedRow.fields+' '+selectedRow.data);
  		   arrSelectedFields.push({uniqueString:uniqueString, fields:selectedRow.fields,data:selectedRow.data, operation:'*',valuesArray:[' ','*','=','!=','>','<','>=','<=','@=','@!=','@>','@<','@>=','@<='], srchValue:""});
  	 	  dgSelectedFields.dataProvider	=  arrSelectedFields;	
		  dgList.dataProvider.removeItemAt(dgList.selectedIndex);
		 		// if(dgList.dataProvider.length>0)
				 //	 dgList.selectedIndex=0;
		  comboMainTabs.enabled=false;
		  comboLeafTabs.enabled=false;
		  btnOk.enabled=true; 
  		  }
	}
		
	public function removeSelectedField(event:ListEvent):void
	{	
		var selectedRow:Object; 
		selectedRow = event.currentTarget.selectedItem
  		if(selectedRow)
  		  {
  		  	dgList.dataProvider.addItem({ fields:selectedRow.fields,data:selectedRow.data});
  			//var a:ArrayCollection;
  			
  			//arrPersonal.push({ fields:selectedRow.fields,data:selectedRow.data});
  			//dgList.dataProvider=arrPersonal;
  			dgSelectedFields.dataProvider.removeItemAt(dgSelectedFields.selectedIndex);  		
  		  }
	}
	
	public function updateSearchWindow(callee:Object):void{
		
		if (callee==comboMainTabs){
			
        	switch(comboMainTabs.text)
        	{	
        		case 'Biodata':
        			comboLeafTabs.dataProvider=tabBiodata;        			
					//dgList.dataProvider=arrPersonal;
        		break;
        		case 'Application':
        			comboLeafTabs.dataProvider=tabApplication;
        			//dgList.dataProvider=arrDocumentation;
        		break;
        		case 'Service':
        			comboLeafTabs.dataProvider=tabService;
        			//dgList.dataProvider=arrStaff;
        		break;
        		case 'Patient':
        			comboLeafTabs.dataProvider=tabPatient;
        			//dgList.dataProvider=arrStaff;
        		break;    
        		case 'Health Staff':
        			comboLeafTabs.dataProvider=tabHealthStaff;
        			//dgList.dataProvider=arrStaff;
        		break;        		
        	}        	
		}
		dgList.dataProvider=comboLeafTabs.value;

	}
	
		protected function applyDefaultClickOperation(event:ListEvent):void{

			if(dgSelectedFields.selectedIndex == -1) return;
			
			//trace("Op1"+event.rowIndex+" "+event.columnIndex);
			//trace(dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].srchValue);
			
			if (dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation == " ") {
				dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation = "*";
				if (dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].srchValue != "") {
					dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation = "=";
				}
				dgSelectedFields.invalidateList();
			}	
		}	

		protected function applyDefaultKeyboardOperation(event:KeyboardEvent):void{

			if(dgSelectedFields.selectedIndex == -1) return;
			
			if(event.ctrlKey && event.altKey){   //ctrl and alt keys pressed  
				
				//trace(event.keyCode + " "+event.charCode);
				if(event.charCode == 32) {// space
                    dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation = " ";
					dgSelectedFields.invalidateList();
                }   
                
                if((event.charCode == 42) || (event.charCode == 56)){// star or 8
                    dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation = "*";
					dgSelectedFields.invalidateList();
                }   
                               
                if((event.charCode == 33) || (event.charCode == 49)) {// exclamation or 1
                    dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation = "!=";
					dgSelectedFields.invalidateList();
                }
                
                if((event.charCode == 61) || (event.charCode == 43)) {// equals or plus
                    dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation = "=";
					dgSelectedFields.invalidateList();
                }
            }
            else { // alphanumeric
            	if(	( event.charCode > 47 && event.charCode < 58 ) ||
				   	( event.charCode > 64 && event.charCode < 91 ) ||
					( event.charCode > 96 && event.charCode < 123 ) ) {
					//trace("Op3: "+dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].srchValue);
					if ((dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation == "*") || (dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation == " ")) {
						dgSelectedFields.dataProvider[dgSelectedFields.selectedIndex].operation = "=";
						dgSelectedFields.invalidateList();
					}
				}	
            }
		}		