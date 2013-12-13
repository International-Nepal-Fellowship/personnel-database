// common code for tabSecondmentClass 
	
		[Bindable]public var showDatagrid:Boolean;
		
		public var comboSecondmentFrom:ComboBoxNew;
		public var comboSecondmentTo:ComboBoxNew;
		public var comboLocalSupportProvider:ComboBoxNew;
		public var comboChurch:ComboBoxNew;
		public var comboPostAgreed:ComboBoxNew;
		public var comboFurtherSecondedTo:ComboBoxNew;
		public var comboLinkPerson:ComboBoxNew;
		
		public var comboOSAApprovalAgency:ComboBoxNew;
		public var comboOSAApprovalINFW:ComboBoxNew;
		public var comboFSAApprovalAgency:ComboBoxNew;
		public var comboFSAApprovalINFW:ComboBoxNew;
					
		public var dateInvitationLetterRcvd:DateField;
		public var dateOSAMemberSent:DateField;
		public var dateOSAMemberReceived:DateField;
		public var dateOSAMemberSigned:DateField;
		public var dateOSAMemberCopySent:DateField;
		public var dateOSAAgencySent:DateField;
		public var dateOSAAgencyReceived:DateField;
		public var dateOSAAgencySigned:DateField;
		public var dateOSAAgencyCopySent:DateField;
		public var dateOSAINFWSent:DateField;
		public var dateOSAINFWReceived:DateField;
		public var dateOSAINFWSigned:DateField;
		public var dateOSAINFWCopySent:DateField;
		
		public var dateFSAMemberSent:DateField;
		public var dateFSAMemberReceived:DateField;
		public var dateFSAMemberSigned:DateField;
		public var dateFSAMemberCopySent:DateField;
		public var dateFSAAgencySent:DateField;
		public var dateFSAAgencyReceived:DateField;
		public var dateFSAAgencySigned:DateField;
		public var dateFSAAgencyCopySent:DateField;
		public var dateFSAINFWSent:DateField;
		public var dateFSAINFWReceived:DateField;
		public var dateFSAINFWSigned:DateField;
		public var dateFSAINFWCopySent:DateField;
		
		public var btnInvitationLetter:Button;
		
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([{label:"OSA Send", data:dateOSAMemberSent},
        	{label:"Invitation Letter", data:dateInvitationLetterRcvd},
        	{label:"OSA Signed", data:dateOSAMemberSigned},{label:"OSA Reveived", data:dateOSAMemberReceived},
        	{label:"OSA Copy Send", data:dateOSAMemberCopySent},{label:"OSA Copy Send", data:dateOSAAgencyCopySent},
			{label:"OSA Send", data:dateOSAAgencySent},{label:"OSA Received", data:dateOSAAgencyReceived},
			{label:"OSA Signed", data:dateOSAAgencySigned},
			{label:"OSA Send", data:dateOSAINFWSent},{label:"OSA Received", data:dateOSAINFWReceived},
			{label:"OSA Signed", data:dateOSAINFWSigned},{label:"OSA Copy Send", data:dateOSAINFWCopySent},			
			{label:"FSA Send", data:dateFSAMemberSent},
        	{label:"FSA Signed", data:dateFSAMemberSigned},{label:"FSA Reveived", data:dateFSAMemberReceived},
        	{label:"FSA Copy Send", data:dateFSAMemberCopySent},{label:"FSA Copy Send", data:dateFSAAgencyCopySent},
			{label:"FSA Send", data:dateFSAAgencySent},{label:"FSA Received", data:dateFSAAgencyReceived},
			{label:"FSA Signed", data:dateFSAAgencySigned},
			{label:"FSA Send", data:dateFSAINFWSent},{label:"FSA Received", data:dateFSAINFWReceived},
			{label:"FSA Signed", data:dateFSAINFWSigned},{label:"FSA Copy Send", data:dateFSAINFWCopySent}
        	]);           	           	
        }	
		
		
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryComboFields = new ArrayCollection([{label:"Secondment From", data:comboSecondmentFrom},
         	{label:"Secondment To", data:comboSecondmentTo},{label:"Local Service Provider", data:comboLocalSupportProvider},
         	{label:"Church", data:comboChurch},{label:"Post Agreed", data:comboPostAgreed},
         	{label:"Further Secondment To", data:comboFurtherSecondedTo},{label:"Link Person", data:comboLinkPerson}//,
         	//{label:"OSA Agency", data:comboOSAApprovalAgency},{label:"OSA INFW", data:comboOSAApprovalINFW},
         	//{label:"FSA Agency", data:comboFSAApprovalAgency}//,{label:"FSA INFW", data:comboFSAApprovalINFW}
         	]);
         	
         	nonMandatoryDateFields = new ArrayCollection([{label:"OSA Sent", data:dateOSAMemberSent},
         	{label:"Invitation Letter", data:dateInvitationLetterRcvd},
        	{label:"OSA Signed", data:dateOSAMemberSigned},{label:"OSA Received", data:dateOSAMemberReceived},
        	{label:"OSA Copy Sent", data:dateOSAMemberCopySent},{label:"OSA Copy Sent", data:dateOSAAgencyCopySent},
			{label:"OSA Sent", data:dateOSAAgencySent},{label:"OSA Received", data:dateOSAAgencyReceived},
			{label:"OSA Signed", data:dateOSAAgencySigned},
			//{label:"OSA Send", data:dateOSAINFWSent},{label:"OSA Received", data:dateOSAINFWReceived},
			//{label:"OSA Signed", data:dateOSAINFWSigned},{label:"OSA Copy Send", data:dateOSAINFWCopySent},			
			{label:"FSA Sent", data:dateFSAMemberSent},
        	{label:"FSA Signed", data:dateFSAMemberSigned},{label:"FSA Received", data:dateFSAMemberReceived},
        	{label:"FSA Copy Sent", data:dateFSAMemberCopySent},{label:"FSA Copy Sent", data:dateFSAAgencyCopySent},
			{label:"FSA Sent", data:dateFSAAgencySent},{label:"FSA Received", data:dateFSAAgencyReceived},
			{label:"FSA Signed", data:dateFSAAgencySigned}//,
			//{label:"FSA Send", data:dateFSAINFWSent},{label:"FSA Received", data:dateFSAINFWReceived},
			//{label:"FSA Signed", data:dateFSAINFWSigned},{label:"FSA Copy Send", data:dateFSAINFWCopySent}
        	]);           	    
       		
        }
           
		override protected function pushSearchByVariables():void{
        	
        //	listSearchBy.push({ label: "OSA_status",data:"secondment"});
        //	listSearchBy.push({ label: "OSA_date",data:"secondment"});
               
        	listSearchBy.push({ label: "seconded_from",data:"secondment"});
        	listSearchBy.push({ label: "seconded_to",data:"secondment"});
        	listSearchBy.push({ label: "local_support",data:"secondment"});
        	listSearchBy.push({ label: "church",data:"secondment"});
        	
        	listSearchBy.push({ label: "further_seconded_to",data:"secondment"});//points to respective id
        	listSearchBy.push({ label: "post_agreed",data:"secondment"});//points to respective id
        	listSearchBy.push({ label: "link_person_forename",data:"secondment"});//points to respective id
        	listSearchBy.push({ label: "invitation_letter_rcvd_date",data:"secondment"});
        	
        	//listSearchBy.push({ label: "osa_approval_agency",data:"secondment"});//points to respective id
        	//listSearchBy.push({ label: "fsa_approval_agency",data:"secondment"});//points to respective id
        	//listSearchBy.push({ label: "osa_approval_infw",data:"secondment"});//points to respective id
        	//listSearchBy.push({ label: "fsa_approval_infw",data:"secondment"});//points to respective id
        	
        	listSearchBy.push({ label: "osa_member_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "osa_member_received_date",data:"secondment"});
        	listSearchBy.push({ label: "osa_member_copy_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "osa_member_signed_date",data:"secondment"});
        	
        	listSearchBy.push({ label: "osa_agency_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "osa_agency_received_date",data:"secondment"});
        	listSearchBy.push({ label: "osa_agency_copy_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "osa_agency_signed_date",data:"secondment"});
        	
        	//listSearchBy.push({ label: "osa_infw_sent_date",data:"secondment"});
        	//listSearchBy.push({ label: "osa_infw_received_date",data:"secondment"});
        	//listSearchBy.push({ label: "osa_infw_copy_sent_date",data:"secondment"});
        	//listSearchBy.push({ label: "osa_infw_signed_date",data:"secondment"});
        	
        	listSearchBy.push({ label: "fsa_member_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "fsa_member_received_date",data:"secondment"});
        	listSearchBy.push({ label: "fsa_member_copy_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "fsa_member_signed_date",data:"secondment"});
        	
        	listSearchBy.push({ label: "fsa_agency_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "fsa_agency_received_date",data:"secondment"});
        	listSearchBy.push({ label: "fsa_agency_copy_sent_date",data:"secondment"});
        	listSearchBy.push({ label: "fsa_agency_signed_date",data:"secondment"});
        	
        	//listSearchBy.push({ label: "fsa_infw_sent_date",data:"secondment"});
        	//listSearchBy.push({ label: "fsa_infw_received_date",data:"secondment"});
        	//listSearchBy.push({ label: "fsa_infw_copy_sent_date",data:"secondment"});
        	//listSearchBy.push({ label: "fsa_infw_signed_date",data:"secondment"});
        	
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	//listSearchWhom.push({ fields: "OSA_status",data:"secondment"});
        	//listSearchWhom.push({ fields: "OSA_date",data:"secondment"});
        	//listSearchWhom.push({ fields: "FSA_status",data:"secondment"});
        	//listSearchWhom.push({ fields: "FSA_date",data:"secondment"});
        	listSearchWhom.push({ fields: "seconded_from",data:"secondment"});
        	listSearchWhom.push({ fields: "seconded_to",data:"secondment"});
        	listSearchWhom.push({ fields: "local_support",data:"secondment"});
        	listSearchWhom.push({ fields: "church",data:"secondment"});
        	
        	listSearchWhom.push({ fields: "further_seconded_to",data:"secondment"});//points to respective id
        	listSearchWhom.push({ fields: "post_agreed",data:"secondment"});//points to respective id
        	listSearchWhom.push({ fields: "link_person_forename",data:"secondment"});//points to respective id
        	listSearchWhom.push({ fields: "invitation_letter_rcvd_date",data:"secondment"});
        	
        	//listSearchWhom.push({ fields: "osa_approval_agency",data:"secondment"});//points to respective id
        	//listSearchWhom.push({ fields: "fsa_approval_agency",data:"secondment"});//points to respective id
        	//listSearchWhom.push({ fields: "osa_approval_infw",data:"secondment"});//points to respective id
        	//listSearchWhom.push({ fields: "fsa_approval_infw",data:"secondment"});//points to respective id
        	
        	listSearchWhom.push({ fields: "osa_member_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "osa_member_received_date",data:"secondment"});
        	listSearchWhom.push({ fields: "osa_member_copy_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "osa_member_signed_date",data:"secondment"});
        	
        	listSearchWhom.push({ fields: "osa_agency_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "osa_agency_received_date",data:"secondment"});
        	listSearchWhom.push({ fields: "osa_agency_copy_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "osa_agency_signed_date",data:"secondment"});
        	
        	//listSearchWhom.push({ fields: "osa_infw_sent_date",data:"secondment"});
        	//listSearchWhom.push({ fields: "osa_infw_received_date",data:"secondment"});
        	//listSearchWhom.push({ fields: "osa_infw_copy_sent_date",data:"secondment"});
        	//listSearchWhom.push({ fields: "osa_infw_signed_date",data:"secondment"});
        	
        	listSearchWhom.push({ fields: "fsa_member_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "fsa_member_received_date",data:"secondment"});
        	listSearchWhom.push({ fields: "fsa_member_copy_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "fsa_member_signed_date",data:"secondment"});
        	
        	listSearchWhom.push({ fields: "fsa_agency_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "fsa_agency_received_date",data:"secondment"});
        	listSearchWhom.push({ fields: "fsa_agency_copy_sent_date",data:"secondment"});
        	listSearchWhom.push({ fields: "fsa_agency_signed_date",data:"secondment"});
        	
        	//listSearchWhom.push({ fields: "fsa_infw_sent_date",data:"secondment"});
        	//listSearchWhom.push({ fields: "fsa_infw_received_date",data:"secondment"});
        	//listSearchWhom.push({ fields: "fsa_infw_copy_sent_date",data:"secondment"});
        	//listSearchWhom.push({ fields: "fsa_infw_signed_date",data:"secondment"});       	
        }      		
		       
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();	
			
			showDatagrid=false;
			
			comboSecondmentFrom.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.secondment_from;
		   	comboSecondmentTo.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.secondment_to;
		   	comboLocalSupportProvider.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.local_support_provider;
		   	comboChurch.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;		   			
       		
       		comboFurtherSecondedTo.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.secondment_to;
       		comboPostAgreed.dataProvider		=	parentApplication.getUserRequestNameResult().serviceposts.servicepost;	
       		comboLinkPerson.dataProvider		=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
  
 //NEED TO UPDATE WITH PROPER DATAPROVIDER
       		//comboOSAApprovalAgency.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
       		//comboOSAApprovalINFW.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
       		//comboFSAApprovalAgency.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
       		//comboFSAApprovalINFW.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;

       		chkShowAll.visible = false;
        }
        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();
					
			//parameters.osa_approval_agency_id	=	comboOSAApprovalAgency.value;
			//parameters.fsa_approval_agency_id	=	comboFSAApprovalAgency.value;
			//parameters.osa_approval_infw_id		=	comboOSAApprovalINFW.value;
			//parameters.fsa_approval_infw_id		=	comboFSAApprovalINFW.value;

			parameters.invitation_letter_rcvd	=	DateUtils.dateFieldToString(dateInvitationLetterRcvd,parentApplication.dateFormat);
			
			parameters.osa_member_sent		=	DateUtils.dateFieldToString(dateOSAMemberSent,parentApplication.dateFormat);
			parameters.osa_member_received	=	DateUtils.dateFieldToString(dateOSAMemberReceived,parentApplication.dateFormat);
			parameters.osa_member_signed	=	DateUtils.dateFieldToString(dateOSAMemberSigned,parentApplication.dateFormat);
			parameters.osa_member_copy_sent	=	DateUtils.dateFieldToString(dateOSAMemberCopySent,parentApplication.dateFormat);
			parameters.osa_agency_sent 		=	DateUtils.dateFieldToString(dateOSAAgencySent,parentApplication.dateFormat);
			parameters.osa_agency_received	=	DateUtils.dateFieldToString(dateOSAAgencyReceived,parentApplication.dateFormat);
			parameters.osa_agency_signed	=	DateUtils.dateFieldToString(dateOSAAgencySigned,parentApplication.dateFormat);
			parameters.osa_agency_copy_sent	=	DateUtils.dateFieldToString(dateOSAAgencyCopySent,parentApplication.dateFormat);
			//parameters.osa_infw_sent 		=	DateUtils.dateFieldToString(dateOSAINFWSent,parentApplication.dateFormat);
			//parameters.osa_infw_received	=	DateUtils.dateFieldToString(dateOSAINFWReceived,parentApplication.dateFormat);
			//parameters.osa_infw_signed		=	DateUtils.dateFieldToString(dateOSAINFWSigned,parentApplication.dateFormat);
			//parameters.osa_infw_copy_sent	=	DateUtils.dateFieldToString(dateOSAINFWCopySent,parentApplication.dateFormat);			
			
			parameters.fsa_member_sent		=	DateUtils.dateFieldToString(dateFSAMemberSent,parentApplication.dateFormat);
			parameters.fsa_member_received	=	DateUtils.dateFieldToString(dateFSAMemberReceived,parentApplication.dateFormat);
			parameters.fsa_member_signed	=	DateUtils.dateFieldToString(dateFSAMemberSigned,parentApplication.dateFormat);
			parameters.fsa_member_copy_sent	=	DateUtils.dateFieldToString(dateFSAMemberCopySent,parentApplication.dateFormat);
			parameters.fsa_agency_sent 		=	DateUtils.dateFieldToString(dateFSAAgencySent,parentApplication.dateFormat);
			parameters.fsa_agency_received	=	DateUtils.dateFieldToString(dateFSAAgencyReceived,parentApplication.dateFormat);
			parameters.fsa_agency_signed	=	DateUtils.dateFieldToString(dateFSAAgencySigned,parentApplication.dateFormat);
			parameters.fsa_agency_copy_sent	=	DateUtils.dateFieldToString(dateFSAAgencyCopySent,parentApplication.dateFormat);
			//parameters.fsa_infw_sent 		=	DateUtils.dateFieldToString(dateFSAINFWSent,parentApplication.dateFormat);
			//parameters.fsa_infw_received	=	DateUtils.dateFieldToString(dateFSAINFWReceived,parentApplication.dateFormat);
			//parameters.fsa_infw_signed		=	DateUtils.dateFieldToString(dateFSAINFWSigned,parentApplication.dateFormat);
			//parameters.fsa_infw_copy_sent	=	DateUtils.dateFieldToString(dateFSAINFWCopySent,parentApplication.dateFormat);
			
			//parameters.OSA_status	= textOSAStatus.text;
			//parameters.FSA_status	= textFSAStatus.text;
			//parameters.OSA_date	= DateUtils.dateFieldToString(dateOSADate,parentApplication.dateFormat);
			//parameters.FSA_date	= DateUtils.dateFieldToString(dateFSADate,parentApplication.dateFormat);
					
			parameters.seconded_from_id	=	comboSecondmentFrom.value;
			parameters.seconded_to_id	=	comboSecondmentTo.value;
			parameters.local_support_id	=	comboLocalSupportProvider.value;
			parameters.church_id		=	comboChurch.value;		
			parameters.further_seconded_to_id =	comboFurtherSecondedTo.value;
			parameters.post_agreed_id	=	comboPostAgreed.value;
			parameters.link_person_id	=	comboLinkPerson.value;		
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.secondment_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;			
		}

		override public function refreshData():void{
			
			super.refreshData();	
			
			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboSecondmentFrom,comboSecondmentFrom.text);			
			comboSecondmentFrom.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.secondment_from;
		   	parentApplication.setComboData(comboSecondmentFrom,nameIndex);
		   	
		   	nameIndex = parentApplication.getComboData(comboSecondmentTo,comboSecondmentTo.text);
			comboSecondmentTo.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.secondment_to;
		   	parentApplication.setComboData(comboSecondmentTo,nameIndex);
		   	
		   	nameIndex = parentApplication.getComboData(comboLocalSupportProvider,comboLocalSupportProvider.text);
		   	comboLocalSupportProvider.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.local_support_provider;
		   	parentApplication.setComboData(comboLocalSupportProvider,nameIndex);
		   	
		   	nameIndex = parentApplication.getComboData(comboChurch,comboChurch.text);
		   	comboChurch.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
		   	parentApplication.setComboData(comboChurch,nameIndex);  				
			
       		nameIndex = parentApplication.getComboData(comboFurtherSecondedTo,comboFurtherSecondedTo.text);
       		comboFurtherSecondedTo.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.secondment_to;
       		parentApplication.setComboData(comboFurtherSecondedTo,nameIndex);
       		
       		nameIndex = parentApplication.getComboData(comboPostAgreed,comboPostAgreed.text);
       		comboPostAgreed.dataProvider		=	parentApplication.getUserRequestNameResult().serviceposts.servicepost;	
       		parentApplication.setComboData(comboPostAgreed,nameIndex);
       		
       		nameIndex = parentApplication.getComboData(comboLinkPerson,comboLinkPerson.text);
       		comboLinkPerson.dataProvider		=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
  			parentApplication.setComboData(comboLinkPerson,nameIndex);
  			
 //NEED TO UPDATE WITH PROPER DATAPROVIDER
       		//nameIndex = parentApplication.getComboData(comboOSAApprovalAgency,comboOSAApprovalAgency.text);
       		//comboOSAApprovalAgency.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
       		//parentApplication.setComboData(comboOSAApprovalAgency,nameIndex);
       		
       		//nameIndex = parentApplication.getComboData(comboOSAApprovalINFW,comboOSAApprovalINFW.text);
       		//comboOSAApprovalINFW.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
       		//parentApplication.setComboData(comboOSAApprovalINFW,nameIndex);
       		
       		//nameIndex = parentApplication.getComboData(comboFSAApprovalAgency,comboFSAApprovalAgency.text);
       		//comboFSAApprovalAgency.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
       		//parentApplication.setComboData(comboFSAApprovalAgency,nameIndex);
       		
       		//nameIndex = parentApplication.getComboData(comboFSAApprovalINFW,comboFSAApprovalINFW.text);
       		//comboFSAApprovalINFW.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.church;
			//parentApplication.setComboData(comboFSAApprovalINFW,nameIndex);
       		
			if (modeState != "View") {
				this.focusManager.setFocus(comboSecondmentFrom);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}	
			        
        override protected function refresh():void{
				
			super.refresh();
						
			comboSecondmentFrom.selectedIndex=0;	
			comboSecondmentTo.selectedIndex=0;	
			comboLocalSupportProvider.selectedIndex=0;	
			comboChurch.selectedIndex=0;
			comboFurtherSecondedTo.selectedIndex=0;	
			comboPostAgreed.selectedIndex=0;
			comboLinkPerson.selectedIndex=0;					
	
			//comboOSAApprovalAgency.selectedIndex=0;	
       		//comboOSAApprovalINFW.selectedIndex=0;	
       		//comboFSAApprovalAgency.selectedIndex=0;	
       		//comboFSAApprovalINFW.selectedIndex=0;	
			
			 dateOSAMemberSent.text="";
			 dateInvitationLetterRcvd.text='';
			 dateOSAMemberReceived.text="";
			 dateOSAMemberSigned.text="";
			 dateOSAMemberCopySent.text="";
			 dateOSAAgencySent.text="";
			 dateOSAAgencyReceived.text="";
			 dateOSAAgencySigned.text="";
			 dateOSAAgencyCopySent.text="";
			 //dateOSAINFWSent.text="";
			 //dateOSAINFWReceived.text="";
			 //dateOSAINFWSigned.text="";
			 //dateOSAINFWCopySent.text="";
			
			 dateFSAMemberSent.text="";
			 dateFSAMemberReceived.text="";
			 dateFSAMemberSigned.text="";
			 dateFSAMemberCopySent.text="";
			 dateFSAAgencySent.text="";
			 dateFSAAgencyReceived.text="";
			 dateFSAAgencySigned.text="";
			 dateFSAAgencyCopySent.text="";
			 //dateFSAINFWSent.text="";
			 //dateFSAINFWReceived.text="";
			 //dateFSAINFWSigned.text="";
			 //dateFSAINFWCopySent.text="";
			
			//dateOSADate.text	=	"";
			//dateFSADate.text	=	"";		
			//textOSAStatus.text	=	"";
			//textFSAStatus.text	=	"";	
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
		}

		override protected function setValues():void{

			super.setValues();	
			
			comboSecondmentFrom.selectedIndex	=	parentApplication.getComboIndex(comboSecondmentFrom.dataProvider,'data',dgList.selectedItem.seconded_from_id);
			comboSecondmentTo.selectedIndex	=	parentApplication.getComboIndex(comboSecondmentTo.dataProvider,'data',dgList.selectedItem.seconded_to_id);
			comboLocalSupportProvider.selectedIndex	=	parentApplication.getComboIndex(comboLocalSupportProvider.dataProvider,'data',dgList.selectedItem.local_support_id);
			comboChurch.selectedIndex	=	parentApplication.getComboIndex(comboChurch.dataProvider,'data',dgList.selectedItem.church_id);
			
			comboFurtherSecondedTo.selectedIndex	=	parentApplication.getComboIndex(comboFurtherSecondedTo.dataProvider,'data',dgList.selectedItem.further_seconded_to_id);
			comboPostAgreed.selectedIndex	=	parentApplication.getComboIndex(comboPostAgreed.dataProvider,'data',dgList.selectedItem.post_agreed_id);
			comboLinkPerson.selectedIndex	=	parentApplication.getComboIndex(comboLinkPerson.dataProvider,'data',dgList.selectedItem.link_person_id);
	
			//comboOSAApprovalAgency.selectedIndex	=	parentApplication.getComboIndex(comboOSAApprovalAgency.dataProvider,'data',dgList.selectedItem.osa_approval_agency_id);
			//comboOSAApprovalINFW.selectedIndex	=	parentApplication.getComboIndex(comboOSAApprovalINFW.dataProvider,'data',dgList.selectedItem.osa_approval_infw_id);
       		//comboFSAApprovalAgency.selectedIndex	=	parentApplication.getComboIndex(comboFSAApprovalAgency.dataProvider,'data',dgList.selectedItem.fsa_approval_agency_id);
     	 	//comboFSAApprovalINFW.selectedIndex	=	parentApplication.getComboIndex(comboFSAApprovalINFW.dataProvider,'data',dgList.selectedItem.fsa_approval_infw_id);
	//	Alert.show(dgList.selectedItem.fsa_approval_agency_id.toString);
			 
			 dateInvitationLetterRcvd.text=DateUtils.stringToDateFieldString(dgList.selectedItem.invitation_letter_rcvd_date,dateInvitationLetterRcvd,parentApplication.dateFormat);
			 dateOSAMemberSent.text		= 	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_member_sent_date,dateOSAMemberSent,parentApplication.dateFormat);
			 dateOSAMemberReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_member_received_date,dateOSAMemberReceived ,parentApplication.dateFormat);
			 dateOSAMemberSigned.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_member_signed_date,dateOSAMemberSigned ,parentApplication.dateFormat);
			 dateOSAMemberCopySent.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_member_copy_sent_date,dateOSAMemberCopySent,parentApplication.dateFormat);
			 dateOSAAgencySent.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_agency_sent_date,dateOSAAgencySent ,parentApplication.dateFormat);
			 dateOSAAgencyReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_agency_received_date,dateOSAAgencyReceived,parentApplication.dateFormat);
			 dateOSAAgencySigned.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_agency_signed_date,dateOSAAgencySigned ,parentApplication.dateFormat);
			 dateOSAAgencyCopySent.text =	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_agency_copy_sent_date ,dateOSAAgencyCopySent,parentApplication.dateFormat);
			 //dateOSAINFWSent.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_infw_sent_date,dateOSAINFWSent,parentApplication.dateFormat);
			 //dateOSAINFWReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_infw_received_date ,dateOSAINFWReceived,parentApplication.dateFormat);
			 //dateOSAINFWSigned.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_infw_signed_date,dateOSAINFWSigned,parentApplication.dateFormat);
			 //dateOSAINFWCopySent.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.osa_infw_copy_sent_date,dateOSAINFWCopySent,parentApplication.dateFormat);
			
			 dateFSAMemberSent.text		= 	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_member_sent_date,dateFSAMemberSent,parentApplication.dateFormat);
			 dateFSAMemberReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_member_received_date,dateFSAMemberReceived ,parentApplication.dateFormat);
			 dateFSAMemberSigned.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_member_signed_date,dateFSAMemberSigned ,parentApplication.dateFormat);
			 dateFSAMemberCopySent.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_member_copy_sent_date,dateFSAMemberCopySent,parentApplication.dateFormat);
			 dateFSAAgencySent.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_agency_sent_date,dateFSAAgencySent ,parentApplication.dateFormat);
			 dateFSAAgencyReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_agency_received_date,dateFSAAgencyReceived,parentApplication.dateFormat);
			 dateFSAAgencySigned.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_agency_signed_date,dateFSAAgencySigned ,parentApplication.dateFormat);
			 dateFSAAgencyCopySent.text =	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_agency_copy_sent_date ,dateFSAAgencyCopySent,parentApplication.dateFormat);
			 //dateFSAINFWSent.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_infw_sent_date,dateFSAINFWSent,parentApplication.dateFormat);
			 //dateFSAINFWReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_infw_received_date ,dateFSAINFWReceived,parentApplication.dateFormat);
			 //dateFSAINFWSigned.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_infw_signed_date,dateFSAINFWSigned,parentApplication.dateFormat);
			 //dateFSAINFWCopySent.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.fsa_infw_copy_sent_date,dateFSAINFWCopySent,parentApplication.dateFormat);			
		}
		
		protected function displayPopUpDoc(strTitle:String,fieldName:String,dateSecondment:DateField):void{		
			
			if (dgList.selectedItem == null) return;
			
			var today:Date = new Date(); 
			if((dateSecondment.text=='')&&(modeState=='Edit'))
		 		dateSecondment.text=DateUtils.dateToString(today,dateSecondment.formatString);
		 	
		 	var newDoc:Boolean = false;
			
			switch(fieldName) {
				case 'invitation_letter_id':
					newDoc = (dgList.selectedItem.invitation_letter_id == 0);
				break;
			}
			displayDocPopUp(strTitle,fieldName,'secondment_notes', newDoc);										
		} 
		
		override protected function enableDisableDocumentButton():void{
			
			var rowCount:int=dgList.dataProvider.length;
			
			if(modeState=='Edit') {				
				btnInvitationLetter.visible=true;							
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				
				 if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 			
				 btnInvitationLetter.visible=(dgList.selectedItem.invitation_letter_id>0)?true:false;					 
			}
			else { 				
				 btnInvitationLetter.visible=false;				
			}
		} 	