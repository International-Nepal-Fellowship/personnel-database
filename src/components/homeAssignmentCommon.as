// common code for tabReviewClass and popupReviewClass
import mx.controls.Button;
import mx.controls.DateField;


		public var comboInterviewer:ComboBoxNew;
		public var dateFrom:DateField;
		public var dateUntil:DateField;
		public var dateInfopackSent:DateField;
		public var dateInterviewDate:DateField;
		public var dateReportReceived:DateField;
		public var dateRSentDateMember:DateField;
		public var dateRSentDateAgency:DateField;
		public var dateMedicalArranged:DateField;
		public var dateMedicalReportReceived:DateField;
		public var dateMSentDateAgency:DateField;
		public var dateSTVManagerCommentsReceived:DateField;
		public var dateInvitationLetterReceived:DateField;
		public var textHADescription:TextArea;
		public var chkConfirmedDates:CheckBox;
		
		public var btnGetInvitationLetterReceived:Button;
		public var btnGetMedicalArranged:Button;
		public var btnGetSTVManagerCommentsReceived:Button;
		
		
		[Bindable] private var today:Date = new Date();
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([
         	{label:"Start Date",data:dateFrom},
         	{label:"End Date",data:dateUntil},
         	{label:"InfopackSent",data:dateInfopackSent},
         	{label:"Interview Date",data:dateInterviewDate},
         	{label:"Report Received",data:dateReportReceived},
         	{label:"Sent Date(Member)",data:dateRSentDateMember},
         	{label:"Sent Date(Agency)",data:dateRSentDateAgency},
         	{label:"Medical Arranged",data:dateMedicalArranged},
         	{label:"Medical Report Received",data:dateMedicalReportReceived},
         	{label:"Sent Date(Agency)",data:dateMSentDateAgency},
         	{label:"STV Manager Comments Received",data:dateSTVManagerCommentsReceived},
         	{label:"Invitation Letter Received",data:dateInvitationLetterReceived}         	
         	]);       
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		
		override protected function loadNonMandatoryFields():void{
			nonMandatoryComboFields  = new ArrayCollection([{label:"Interviewer", data:comboInterviewer}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Notes", data:textHADescription}]);
       		
         	nonMandatoryDateFields = new ArrayCollection([
         	{label:"Start Date",data:dateFrom},
         	{label:"End Date",data:dateUntil},
         	{label:"InfopackSent",data:dateInfopackSent},
         	{label:"Interview Date",data:dateInterviewDate},
         	{label:"Report Received",data:dateReportReceived},
         	{label:"Sent Date(Member)",data:dateRSentDateMember},
         	{label:"Sent Date(Agency)",data:dateRSentDateAgency},
         	{label:"Medical Arranged",data:dateMedicalArranged},
         	{label:"Medical Report Received",data:dateMedicalReportReceived},
         	{label:"Sent Date(Agency)",data:dateMSentDateAgency},
         	{label:"STV Manager Comments Received",data:dateSTVManagerCommentsReceived},
         	{label:"Invitation Letter Received",data:dateInvitationLetterReceived}         	
         	]);       
           }
		
		override protected function pushSearchByVariables():void{
			listSearchBy.push({ label: "date_from",data:"home_assignment"});   
	        listSearchBy.push({ label: "date_until",data:"home_assignment"});
			listSearchBy.push({label: "interviewer-Forenames",data:"home_assignment"});
        	listSearchBy.push({label: "interviewer-Surname",data:"surname"}); 
        	listSearchBy.push({ label: "interview_date",data:"home_assignment"});
	        listSearchBy.push({ label: "rsent_date_member",data:"home_assignment"});
			listSearchBy.push({ label: "rsent_date_agency",data:"home_assignment"});		
			listSearchBy.push({ label: "medical_arranged",data:"home_assignment"});   
	        listSearchBy.push({ label: "medical_report_received",data:"home_assignment"});
	        listSearchBy.push({ label: "msent_date_agency",data:"home_assignment"});   
	        listSearchBy.push({ label: "infopack_sent",data:"home_assignment"});
			listSearchBy.push({ label: "report_received",data:"home_assignment"});   
	        listSearchBy.push({ label: "STV_manager_comments_received",data:"home_assignment"});	
	        listSearchBy.push({ label: "invitation_letter_received",data:"home_assignment"});
	        listSearchBy.push({ label: "notes",data:"home_assignment"});
     		super.pushSearchByVariables();
		}
		
        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "date_from",data:"home_assignment"});   
	        listSearchWhom.push({ fields: "date_until",data:"home_assignment"});
        	listSearchWhom.push({ fields: "interviewer",data:"home_assignment"});   
	        listSearchWhom.push({ fields: "interview_date",data:"home_assignment"});
	        listSearchWhom.push({ fields: "rsent_date_member",data:"home_assignment"});
			listSearchWhom.push({ fields: "rsent_date_agency",data:"home_assignment"});		
			listSearchWhom.push({ fields: "medical_arranged",data:"home_assignment"});   
	        listSearchWhom.push({ fields: "medical_report_received",data:"home_assignment"});
	        listSearchWhom.push({ fields: "msent_date_agency",data:"home_assignment"});   
	        listSearchWhom.push({ fields: "infopack_sent",data:"home_assignment"});
			listSearchWhom.push({ fields: "report_received",data:"home_assignment"});   
	        listSearchWhom.push({ fields: "STV_manager_comments_received",data:"home_assignment"});	
	        listSearchWhom.push({ fields: "invitation_letter_received",data:"home_assignment"});
	        listSearchWhom.push({ fields: "notes",data:"home_assignment"});
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
			comboInterviewer.dataProvider=parentApplication.getUserRequestNameResult().personnelReviewers.personnelReviewer;
			chkShowAll.visible = false;	
        }

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

		//Alert.show(tableName);
			parameters.dateFrom			=	DateUtils.dateFieldToString(dateFrom,parentApplication.dateFormat);
			parameters.dateUntil		=	DateUtils.dateFieldToString(dateUntil,parentApplication.dateFormat);
			parameters.infoPackSent		=	DateUtils.dateFieldToString(dateInfopackSent,parentApplication.dateFormat);
			parameters.interviewDate	=	DateUtils.dateFieldToString(dateInterviewDate,parentApplication.dateFormat);
			parameters.reportReceived	=	DateUtils.dateFieldToString(dateReportReceived,parentApplication.dateFormat);
			parameters.rSentDateMember	=	DateUtils.dateFieldToString(dateRSentDateMember,parentApplication.dateFormat);
			parameters.rSentDateAgency	=	DateUtils.dateFieldToString(dateRSentDateAgency,parentApplication.dateFormat);
			parameters.medicalArranged	=	DateUtils.dateFieldToString(dateMedicalArranged,parentApplication.dateFormat);
			parameters.reportReceived	=	DateUtils.dateFieldToString(dateMedicalReportReceived,parentApplication.dateFormat);
			parameters.mSentDateAgency	=	DateUtils.dateFieldToString(dateMSentDateAgency,parentApplication.dateFormat);
			parameters.STVManagerCommentsReceived	=	DateUtils.dateFieldToString(dateSTVManagerCommentsReceived,parentApplication.dateFormat);
			parameters.HADescription	=	textHADescription.text;
			parameters.invitationLetterReceived	=	DateUtils.dateFieldToString(dateInvitationLetterReceived,parentApplication.dateFormat);
			parameters.interviewBy		=	comboInterviewer.value;
			parameters.confirmedDates 	=   chkConfirmedDates.selected;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.home_assignment_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 				
			return parameters;
		} 

		protected function displayPopUpDoc(strTitle:String,fieldName:String,dateHA:DateField):void{		
			
			if (dgList.selectedItem == null) return;
			
			var today:Date = new Date(); 
			if((dateHA.text=='')&&(modeState=='Edit'))
		 		dateHA.text=DateUtils.dateToString(today,dateHA.formatString);
		 	
		 	var newDoc:Boolean = false;
			
			switch(fieldName) {
				case 'medical_report_received_id':
					newDoc = (dgList.selectedItem.medical_report_received_id == 0);
				break;
				case 'STV_manager_comments_received_id':
					newDoc = (dgList.selectedItem.STV_manager_comments_received_id == 0);
				break;
				case 'invitation_letter_received_id':
					newDoc = (dgList.selectedItem.invitation_letter_received_id == 0);
				break;
			}
			displayDocPopUp(strTitle,fieldName,'home_assignment_notes', newDoc);											
		}      
		      
		override public function refreshData():void{

			super.refreshData();
			
			var nameIndex:int = parentApplication.getComboData(comboInterviewer,comboInterviewer.text);
			comboInterviewer.dataProvider=parentApplication.getUserRequestNameResult().personnelReviewers.personnelReviewer;
			parentApplication.setComboData(comboInterviewer,nameIndex);	
				
			if (modeState != "View") {
				//this.focusManager.setFocus(comboInterviewer);
				this.focusManager.setFocus(dateFrom);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}			
		}

        override protected function refresh():void{
				
			super.refresh();			
			comboInterviewer.selectedIndex=0;
			dateUntil.text="";
			dateFrom.text="";
			dateInfopackSent.text	=	"";			
         	dateInterviewDate.text	=	"";			
         	dateReportReceived.text	=	"";			
         	dateRSentDateMember.text	=	"";			
         	dateRSentDateAgency.text	=	"";			
         	dateMedicalArranged.text	=	"";			
         	dateMedicalReportReceived.text	=	"";			
         	dateMSentDateAgency.text	=	"";			
         	dateSTVManagerCommentsReceived.text	=	"";			
         	dateInvitationLetterReceived.text	=	"";			
         	textHADescription.text	=  "";	
         	chkConfirmedDates.selected = false;
		}      
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(comboInterviewer.selectedIndex	==	0){
				saveEnabled	= false;								
			}
		}

		override protected function setValues():void{

			super.setValues();
			
			dateUntil.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateUntil,parentApplication.dateFormat);
			dateFrom.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateFrom,parentApplication.dateFormat);			
         	dateInfopackSent.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.infopack_sent,dateInfopackSent,parentApplication.dateFormat);			
         	dateInterviewDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.interview_date,dateInterviewDate,parentApplication.dateFormat);			
         	dateReportReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.report_received,dateReportReceived,parentApplication.dateFormat);			
         	dateRSentDateMember.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.rsent_date_member,dateRSentDateMember,parentApplication.dateFormat);			
         	dateRSentDateAgency.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.rsent_date_agency,dateRSentDateAgency,parentApplication.dateFormat);			
         	dateMedicalArranged.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.medical_arranged,dateMedicalArranged,parentApplication.dateFormat);			
         	dateMedicalReportReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.medical_report_received,dateMedicalReportReceived,parentApplication.dateFormat);			
         	dateMSentDateAgency.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.msent_date_agency,dateMSentDateAgency,parentApplication.dateFormat);			
         	dateSTVManagerCommentsReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.STV_manager_comments_received,dateSTVManagerCommentsReceived,parentApplication.dateFormat);			
         	dateInvitationLetterReceived.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.invitation_letter_received,dateInvitationLetterReceived,parentApplication.dateFormat);			
         	textHADescription.text	=  dgList.selectedItem.notes;	
         	comboInterviewer.selectedIndex	=	parentApplication.getComboIndex(comboInterviewer.dataProvider,'data',dgList.selectedItem.interview_by);		
         	chkConfirmedDates.selected = dgList.selectedItem.confirmed_dates;
		}
		
		override protected function enableDisableDocumentButton():void{
			
			var rowCount:int=dgList.dataProvider.length;
		//Alert.show(rowCount.toString());			
			if(modeState=='Edit') {
				//btnGetApplication.visible=true;
				btnGetInvitationLetterReceived.visible=true;
				btnGetSTVManagerCommentsReceived.visible=true;
				btnGetMedicalArranged.visible=true;
						
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				
				 if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 
				 btnGetMedicalArranged.visible=(dgList.selectedItem.medical_report_received_id>0)?true:false;
				 btnGetSTVManagerCommentsReceived.visible=(dgList.selectedItem.STV_manager_comments_received_id>0)?true:false;
				 btnGetInvitationLetterReceived.visible	= (dgList.selectedItem.invitation_letter_received_id>0)?true:false;
			}
			else { 
				 btnGetInvitationLetterReceived.visible=false;
				 btnGetSTVManagerCommentsReceived.visible=false;
				 btnGetMedicalArranged.visible=false;
				
			}
		} 