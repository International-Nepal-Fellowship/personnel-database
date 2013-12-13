// code for tabDocumentationClass 

		[Bindable]public var showDatagrid:Boolean;
		
		public var comboMedicalAcceptedBy:ComboBoxNew;	
		public var comboPostAgreed:ComboBoxNew;	
		public var comboLinkPerson:ComboBoxNew;		
		public var dateApplicationFormReceived:DateField;
		public var datePsychReportReceived:DateField;
		public var datePsychReportPassedToMO:DateField;
		public var datePsychReportReceivedFromMO:DateField;
		public var datePsychReportPassedToPD:DateField;		
		public var datePsychReportAccepted:DateField;
		public var dateEmployerRefReceived:DateField;
		public var dateColleagueRefReceived:DateField;
		public var dateMedicalFormReceived:DateField;
		public var dateMedicalFormPassedToMO:DateField;
		public var dateMedicalAccepted:DateField;
		public var dateCVReceived:DateField;
		public var dateSecondmentAccepted:DateField;
		public var dateContractReceived:DateField;
		public var dateCertificatesReceived:DateField;
		public var datePassportDetailsReceived:DateField;
		public var datePhotosReceived:DateField;
		public var dateProfCertificateReceived:DateField;
		public var dateMinisterRefReceived:DateField;
		public var dateChristianFriendRefReceived:DateField;
		public var datePostAgreed:DateField;
		public var dateInterviewReportReceived:DateField;
		public var btnGetCV:Button;
		public var btnGetInterview:Button;
		public var btnGetFriendRef:Button;
		public var btnGetMinisterRef:Button;
		public var btnGetProfCertificate:Button;
		public var btnGetPhotos:Button;
		public var btnGetPassport:Button;
		public var btnGetCertificates:Button;
		public var btnGetContract:Button;
		public var btnGetMedicalForm:Button;
		public var btnGetColleagueRef:Button;
		public var btnGetEmployerRef:Button;
		public var btnGetPsychReport:Button;
		public var btnGetApplication:Button;
		
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{

           	allDateFields= new ArrayCollection([{label:"Psych Report Received", data:datePsychReportReceived},
         	{label:"Psych Report Passed To MO",data:datePsychReportPassedToMO },{label:"Psych Report Received From MO",data:datePsychReportReceivedFromMO },
         	{label:" Psych Report Passed To PD",data: datePsychReportPassedToPD },{label:"Psych Report Accepted",data:datePsychReportAccepted },
         	{label:"Employer Ref Received",data:dateEmployerRefReceived },{label:"Colleague Ref Received",data:dateColleagueRefReceived },
         	{label:"Medical Form Received",data:dateMedicalFormReceived },{label:"Medical Form Passed To MO",data:dateMedicalFormPassedToMO },
         	{label:"Medical Accepted",data:dateMedicalAccepted },{label:"CV Received",data:dateCVReceived },
         	{label:"Secondment Accepted",data:dateSecondmentAccepted },{label:"Contract Received",data:dateContractReceived },
         	{label:"Certificates Received",data:dateCertificatesReceived },{label:"Passport Details Received",data:datePassportDetailsReceived},
         	{label:"Photos Received",data:datePhotosReceived },{label:"Prof Certificate Received",data:dateProfCertificateReceived },
         	{label:"Minister Ref Received",data:dateMinisterRefReceived },{label:"Christian Friend Ref Received",data:dateChristianFriendRefReceived },
         	{label:"INF Role Agreed Date",data:datePostAgreed },{label:"Interview Report Received",data:dateInterviewReportReceived },
         	{label:"Application Form Received", data:dateApplicationFormReceived}
         	]);           	
        }
		
		//for alerting if fields are empty
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([{label:"Psych Report Received", data:datePsychReportReceived},
         	{label:"Psych Report Passed To MO",data:datePsychReportPassedToMO },{label:"Psych Report Received From MO",data:datePsychReportReceivedFromMO },
         	{label:"Psych Report Passed To PD",data: datePsychReportPassedToPD },{label:"Psych Report Accepted",data:datePsychReportAccepted },
         	{label:"Employer Ref Received",data:dateEmployerRefReceived },{label:"Colleague Ref Received",data:dateColleagueRefReceived },
         	{label:"Medical Form Received",data:dateMedicalFormReceived },{label:"Medical Form Passed To MO",data:dateMedicalFormPassedToMO },
         	{label:"Medical Accepted",data:dateMedicalAccepted },{label:"CV Received",data:dateCVReceived },
         	{label:"Secondment Accepted",data:dateSecondmentAccepted },{label:"Contract Received",data:dateContractReceived },
         	{label:"Certificates Received",data:dateCertificatesReceived },{label:"Passport Details Received",data:datePassportDetailsReceived},
         	{label:"Photos Received",data:datePhotosReceived },{label:"Prof Certificate Received",data:dateProfCertificateReceived },
         	{label:"Minister Ref Received",data:dateMinisterRefReceived },{label:"Christian Friend Ref Received",data:dateChristianFriendRefReceived },
         	{label:"INF Role Agreed Date",data:datePostAgreed },{label:"Interview Report Received",data:dateInterviewReportReceived },
         	
         	]);
       		nonMandatoryComboFields  = new ArrayCollection([{label:"Medical Accepted By", data:comboMedicalAcceptedBy},{label:"INF Role Agreed",data:comboPostAgreed},{label:"Link Person",data:comboLinkPerson}]);
          
           }
		
		override protected function pushSearchByVariables():void{

			listSearchBy.push({ label: "cv_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "application_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "medical_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "medical_to_MO_date",data:"documentation"});
			listSearchBy.push({ label: "medical_accepted_date",data:"documentation"});
			listSearchBy.push({ label: "medical_accepted_by",data:"documentation"});//points to medical officer
			listSearchBy.push({ label: "psych_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "psych_to_MO_date",data:"documentation"});
			listSearchBy.push({ label: "psych_from_MO_date",data:"documentation"});
			listSearchBy.push({ label: "psych_to_PD_date",data:"documentation"});
			listSearchBy.push({ label: "psych_accepted_date",data:"documentation"});
			listSearchBy.push({ label: "employer_ref_recvd_date",data:"documentation"});		
			listSearchBy.push({ label: "colleague_ref_recvd_date",data:"documentation"});			
			listSearchBy.push({ label: "friend_ref_recvd_date",data:"documentation"});			
			listSearchBy.push({ label: "minister_ref_recvd_date",data:"documentation"});			
			listSearchBy.push({ label: "interview_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "secondment_accepted_date",data:"documentation"});			
			listSearchBy.push({ label: "INF_role_agreed_date",data:"documentation"});
			listSearchBy.push({ label: "INF_role_agreed",data:"documentation"});//points to post
			listSearchBy.push({ label: "contract_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "certificates_recvd_date",data:"documentation"});			
			listSearchBy.push({ label: "photos_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "passport_recvd_date",data:"documentation"});		
			listSearchBy.push({ label: "professional_recvd_date",data:"documentation"});
			listSearchBy.push({ label: "link_person",data:"documentation"});//points to link person

        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	
        	listSearchWhom.push({ fields: "cv_recvd_date",data:"documentation"});
			listSearchWhom.push({ fields: "application_recvd_date",data:"documentation"});
			listSearchWhom.push({ fields: "medical_recvd_date",data:"documentation"});
			listSearchWhom.push({ fields: "medical_to_MO_date",data:"documentation"});
			listSearchWhom.push({ fields: "medical_accepted_date",data:"documentation"});
			listSearchWhom.push({ fields: "medical_accepted_by",data:"documentation"});//points to medical officer
			listSearchWhom.push({ fields: "psych_recvd_date",data:"documentation"});
			listSearchWhom.push({ fields: "psych_to_MO_date",data:"documentation"});
			listSearchWhom.push({ fields: "psych_from_MO_date",data:"documentation"});
			listSearchWhom.push({ fields: "psych_to_PD_date",data:"documentation"});
			listSearchWhom.push({ fields: "psych_accepted_date",data:"documentation"});
			listSearchWhom.push({ fields: "employer_ref_recvd_date",data:"documentation"});		
			listSearchWhom.push({ fields: "colleague_ref_recvd_date",data:"documentation"});			
			listSearchWhom.push({ fields: "friend_ref_recvd_date",data:"documentation"});			
			listSearchWhom.push({ fields: "minister_ref_recvd_date",data:"documentation"});			
			listSearchWhom.push({ fields: "interview_recvd_date",data:"documentation"});
			listSearchWhom.push({ fields: "secondment_accepted_date",data:"documentation"});			
			listSearchWhom.push({ fields: "INF_role_agreed_date",data:"documentation"});
			listSearchWhom.push({ fields: "INF_role_agreed",data:"documentation"});//points to post
			listSearchWhom.push({ fields: "contract_recvd_date",data:"documentation"});
			listSearchWhom.push({ fields: "certificates_recvd_date",data:"documentation"});			
			listSearchWhom.push({ fields: "photos_recvd_date",data:"documentation"});
			listSearchWhom.push({ fields: "passport_recvd_date",data:"documentation"});		
			listSearchWhom.push({ fields: "professional_recvd_date",data:"documentation"});  
			listSearchWhom.push({ fields: "link_person",data:"documentation"});//points to link person      	
        }
        		
		       
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();	
			
			comboLinkPerson.dataProvider=parentApplication.getUserRequestNameResult().idFullNames.idFullName;		
       		comboMedicalAcceptedBy.dataProvider=parentApplication.getUserRequestNameResult().medicalReviewers.medicalReviewer;		
       		comboPostAgreed.dataProvider=parentApplication.getUserRequestNameResult().activeserviceposts.servicepost;	

       		chkShowAll.visible = false;
       		showDatagrid=false;
        }
        
        protected function displayPopUpDoc(strTitle:String,fieldName:String,dateDoc:DateField):void{
        		
			if (dgList.selectedItem == null) return;
			
			var today:Date = new Date(); 
        	if((dateDoc.text=='')&&(modeState=='Edit'))
		 		dateDoc.text=DateUtils.dateToString(today,dateDoc.formatString);
			var newDoc:Boolean = false;
			
			switch(fieldName) {
				case 'medical_doc_id':
					newDoc = (dgList.selectedItem.medical_doc_id == 0);
				break;
				case 'application_doc_id':
					newDoc = (dgList.selectedItem.application_doc_id == 0);
				break;
				case 'psych_doc_id':
					newDoc = (dgList.selectedItem.psych_doc_id == 0);
				break;
				case 'employer_doc_id':
					newDoc = (dgList.selectedItem.employer_doc_id == 0);
				break;
				case 'colleague_doc_id':
					newDoc = (dgList.selectedItem.colleague_doc_id == 0);
				break;
				case 'cv_doc_id':
					newDoc = (dgList.selectedItem.cv_doc_id == 0);
				break;
				case 'contract_doc_id':
					newDoc = (dgList.selectedItem.contract_doc_id == 0);
				break;
				case 'certificates_doc_id':
					newDoc = (dgList.selectedItem.certificates_doc_id == 0);
				break;
				case 'passport_doc_id':
					newDoc = (dgList.selectedItem.passport_doc_id == 0);
				break;
				case 'photos_doc_id':
					newDoc = (dgList.selectedItem.photos_doc_id == 0);
				break;
				case 'professional_doc_id':
					newDoc = (dgList.selectedItem.professional_doc_id == 0);
				break;
				case 'friend_doc_id':
					newDoc = (dgList.selectedItem.friend_doc_id == 0);
				break;
				case 'interview_doc_id':
					newDoc = (dgList.selectedItem.interview_doc_id == 0);
				break;
				case 'minister_doc_id':
					newDoc = (dgList.selectedItem.minister_doc_id == 0);
				break;
			}		 		
			displayDocPopUp(strTitle,fieldName,'document_notes',newDoc);	
		}    
		
		override protected function enableDisableDocumentButton():void{
			
			var rowCount:int=dgList.dataProvider.length;
		//Alert.show(rowCount.toString());			
			if(modeState=='Edit') {
				btnGetApplication.visible=true;
				btnGetPsychReport.visible=true;
				btnGetEmployerRef.visible=true;
				btnGetColleagueRef.visible=true;
				btnGetMedicalForm.visible=true;
				btnGetCV.visible=true;
				btnGetContract.visible=true;
				btnGetCertificates.visible=true;
				btnGetPassport.visible=true;
				btnGetPhotos.visible=true;
				btnGetProfCertificate.visible=true;
				btnGetMinisterRef.visible=true;
				btnGetFriendRef.visible=true;
				btnGetInterview.visible=true;				
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				
				 if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 
				 btnGetApplication.visible=(dgList.selectedItem.application_doc_id>0)?true:false;
				 btnGetPsychReport.visible=(dgList.selectedItem.psych_doc_id>0)?true:false;
				 btnGetEmployerRef.visible=(dgList.selectedItem.employer_doc_id>0)?true:false;
				 btnGetColleagueRef.visible=(dgList.selectedItem.colleague_doc_id>0)?true:false;
				 btnGetMedicalForm.visible=(dgList.selectedItem.medical_doc_id>0)?true:false;
				 btnGetCV.visible=(dgList.selectedItem.cv_doc_id>0)?true:false;
				 btnGetContract.visible=(dgList.selectedItem.contract_doc_id>0)?true:false;
				 btnGetCertificates.visible=(dgList.selectedItem.certificates_doc_id>0)?true:false;
				 btnGetPassport.visible=(dgList.selectedItem.passport_doc_id>0)?true:false;
				 btnGetPhotos.visible=(dgList.selectedItem.photos_doc_id>0)?true:false;
				 btnGetProfCertificate.visible=(dgList.selectedItem.professional_doc_id>0)?true:false;
				 btnGetMinisterRef.visible=(dgList.selectedItem.minister_doc_id>0)?true:false;
				 btnGetFriendRef.visible=(dgList.selectedItem.friend_doc_id>0)?true:false;
				 btnGetInterview.visible=(dgList.selectedItem.interview_doc_id>0)?true:false;		 
			}
			else { 
				 btnGetApplication.visible=false;
				 btnGetPsychReport.visible=false;
				 btnGetEmployerRef.visible=false;
				 btnGetColleagueRef.visible=false;
				 btnGetMedicalForm.visible=false;
				 btnGetCV.visible=false;
				 btnGetContract.visible=false;
				 btnGetCertificates.visible=false;
				 btnGetPassport.visible=false;
				 btnGetPhotos.visible=false;
				 btnGetProfCertificate.visible=false;
				 btnGetMinisterRef.visible=false;
				 btnGetFriendRef.visible=false;
				 btnGetInterview.visible=false;
			}
		}   
        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();	
     		
      		parameters.medical_accepted_id	=	comboMedicalAcceptedBy.value;	
			parameters.post_agreed_id	=	comboPostAgreed.value;	
			parameters.link_person_id	=	comboLinkPerson.value;		
			parameters.application_recvd_date	=	DateUtils.dateFieldToString(dateApplicationFormReceived,parentApplication.dateFormat);
			parameters.psych_recvd_date	=	DateUtils.dateFieldToString(datePsychReportReceived,parentApplication.dateFormat);
			parameters.psych_to_MO_date	=	DateUtils.dateFieldToString(datePsychReportPassedToMO,parentApplication.dateFormat);
			parameters.psych_from_MO_date	=	DateUtils.dateFieldToString(datePsychReportReceivedFromMO,parentApplication.dateFormat);
			parameters.psych_to_PD_date	=	DateUtils.dateFieldToString(datePsychReportPassedToPD,parentApplication.dateFormat);
			parameters.psych_accepted_date	=	DateUtils.dateFieldToString(datePsychReportAccepted,parentApplication.dateFormat);
			parameters.employer_ref_recvd_date	=	DateUtils.dateFieldToString(dateEmployerRefReceived,parentApplication.dateFormat);
			parameters.colleague_ref_recvd_date	=	DateUtils.dateFieldToString(dateColleagueRefReceived,parentApplication.dateFormat);
			parameters.medical_recvd_date	=	DateUtils.dateFieldToString(dateMedicalFormReceived,parentApplication.dateFormat);
			parameters.medical_to_MO_date	=	DateUtils.dateFieldToString(dateMedicalFormPassedToMO,parentApplication.dateFormat);
			parameters.medical_accepted_date	=	DateUtils.dateFieldToString(dateMedicalAccepted,parentApplication.dateFormat);
			parameters.cv_recvd_date	=	DateUtils.dateFieldToString(dateCVReceived,parentApplication.dateFormat);
			parameters.secondment_accepted_date	=	DateUtils.dateFieldToString(dateSecondmentAccepted,parentApplication.dateFormat);
			parameters.contract_recvd_date	=	DateUtils.dateFieldToString(dateContractReceived,parentApplication.dateFormat);
			parameters.certificates_recvd_date	=	DateUtils.dateFieldToString(dateCertificatesReceived,parentApplication.dateFormat);
			parameters.passport_recvd_date	=	DateUtils.dateFieldToString(datePassportDetailsReceived,parentApplication.dateFormat);
			parameters.photos_recvd_date	=	DateUtils.dateFieldToString(datePhotosReceived,parentApplication.dateFormat);
			parameters.professional_recvd_date	=	DateUtils.dateFieldToString(dateProfCertificateReceived,parentApplication.dateFormat);
			parameters.minister_ref_recvd_date	=	DateUtils.dateFieldToString(dateMinisterRefReceived,parentApplication.dateFormat);
			parameters.friend_ref_recvd_date	=	DateUtils.dateFieldToString(dateChristianFriendRefReceived,parentApplication.dateFormat);
			parameters.post_agreed_date	=	DateUtils.dateFieldToString(datePostAgreed,parentApplication.dateFormat);
			parameters.interview_recvd_date	=	DateUtils.dateFieldToString(dateInterviewReportReceived,parentApplication.dateFormat);
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.documentation_timestamp;
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
						
			nameIndex = parentApplication.getComboData(comboLinkPerson,comboLinkPerson.text);
			comboLinkPerson.dataProvider=parentApplication.getUserRequestNameResult().idFullNames.idFullName;		
       		parentApplication.setComboData(comboLinkPerson,nameIndex);
			nameIndex = parentApplication.getComboData(comboMedicalAcceptedBy,comboMedicalAcceptedBy.text);
			comboMedicalAcceptedBy.dataProvider=parentApplication.getUserRequestNameResult().medicalReviewers.medicalReviewer;		
       		parentApplication.setComboData(comboMedicalAcceptedBy,nameIndex);
       		nameIndex = parentApplication.getComboData(comboPostAgreed,comboPostAgreed.text);
			comboPostAgreed.dataProvider=parentApplication.getUserRequestNameResult().activeserviceposts.servicepost;	
			parentApplication.setComboData(comboPostAgreed,nameIndex);	

			if (modeState != "View") {
				this.focusManager.setFocus(dateApplicationFormReceived);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}			
		}	
			        
        override protected function refresh():void{
				
			super.refresh();
			
			comboMedicalAcceptedBy.selectedIndex=0;	
			comboPostAgreed.selectedIndex=0;
			comboLinkPerson.selectedIndex=0;		
			dateApplicationFormReceived.text=DateUtils.dateToString(today,dateApplicationFormReceived.formatString);	
			datePsychReportReceived.text = "";
			datePsychReportPassedToMO.text = "";
			datePsychReportReceivedFromMO.text = "";
			datePsychReportPassedToPD.text = "";
			datePsychReportAccepted.text = "";
			dateEmployerRefReceived.text = "";
			dateColleagueRefReceived.text = "";
			dateMedicalFormReceived.text = "";
			dateMedicalFormPassedToMO.text = "";
			dateMedicalAccepted.text = "";
			dateCVReceived.text = "";
			dateSecondmentAccepted.text = "";
			dateContractReceived.text = "";
			dateCertificatesReceived.text = "";
			datePassportDetailsReceived.text = "";
			datePhotosReceived.text = "";
			dateProfCertificateReceived.text = "";
			dateMinisterRefReceived.text = "";
			dateChristianFriendRefReceived.text = "";
			datePostAgreed.text = "";
			dateInterviewReportReceived.text = "";	
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateApplicationFormReceived.text	==	""){	
				saveEnabled	= false;								
			}
		}

		override protected function setValues():void{

			super.setValues();
			
			dateCVReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.cv_recvd_date,dateCVReceived,parentApplication.dateFormat);
			dateApplicationFormReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.application_recvd_date,dateApplicationFormReceived,parentApplication.dateFormat);			
			dateMedicalFormReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.medical_recvd_date,dateMedicalFormReceived,parentApplication.dateFormat);
			dateMedicalFormPassedToMO.text = DateUtils.stringToDateFieldString(dgList.selectedItem.medical_to_MO_date,dateMedicalFormPassedToMO,parentApplication.dateFormat);
			dateMedicalAccepted.text = DateUtils.stringToDateFieldString(dgList.selectedItem.medical_accepted_date,dateMedicalAccepted,parentApplication.dateFormat);
			comboMedicalAcceptedBy.selectedIndex	=	parentApplication.getComboIndex(comboMedicalAcceptedBy.dataProvider,'data',dgList.selectedItem.medical_accepted_id);
			datePsychReportReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.psych_recvd_date,datePsychReportReceived,parentApplication.dateFormat);			
			datePsychReportPassedToMO.text = DateUtils.stringToDateFieldString(dgList.selectedItem.psych_to_MO_date,datePsychReportPassedToMO,parentApplication.dateFormat);
			datePsychReportReceivedFromMO.text = DateUtils.stringToDateFieldString(dgList.selectedItem.psych_from_MO_date,datePsychReportReceivedFromMO,parentApplication.dateFormat);
			datePsychReportPassedToPD.text = DateUtils.stringToDateFieldString(dgList.selectedItem.psych_to_PD_date,datePsychReportPassedToPD,parentApplication.dateFormat);
			datePsychReportAccepted.text = DateUtils.stringToDateFieldString(dgList.selectedItem.psych_accepted_date,datePsychReportAccepted,parentApplication.dateFormat);
			dateEmployerRefReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.employer_ref_recvd_date,dateEmployerRefReceived,parentApplication.dateFormat);			
			dateColleagueRefReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.colleague_ref_recvd_date,dateColleagueRefReceived,parentApplication.dateFormat);			
			dateChristianFriendRefReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.friend_ref_recvd_date,dateChristianFriendRefReceived,parentApplication.dateFormat);		
			dateMinisterRefReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.minister_ref_recvd_date,dateMinisterRefReceived,parentApplication.dateFormat);
			dateInterviewReportReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.interview_recvd_date,dateInterviewReportReceived,parentApplication.dateFormat);
			dateSecondmentAccepted.text = DateUtils.stringToDateFieldString(dgList.selectedItem.secondment_accepted_date,dateSecondmentAccepted,parentApplication.dateFormat);			
			datePostAgreed.text = DateUtils.stringToDateFieldString(dgList.selectedItem.INF_role_agreed_date,datePostAgreed,parentApplication.dateFormat);
			comboPostAgreed.selectedIndex	=	parentApplication.getComboIndex(comboPostAgreed.dataProvider,'data',dgList.selectedItem.post_agreed_id);
			dateContractReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.contract_recvd_date,dateContractReceived,parentApplication.dateFormat);
			dateCertificatesReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.certificates_recvd_date,dateCertificatesReceived,parentApplication.dateFormat);			
			datePhotosReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.photos_recvd_date,datePhotosReceived,parentApplication.dateFormat);
			datePassportDetailsReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.passport_recvd_date,datePassportDetailsReceived,parentApplication.dateFormat);			
			dateProfCertificateReceived.text = DateUtils.stringToDateFieldString(dgList.selectedItem.professional_recvd_date,dateProfCertificateReceived,parentApplication.dateFormat);
			comboLinkPerson.selectedIndex	=	parentApplication.getComboIndex(comboLinkPerson.dataProvider,'data',dgList.selectedItem.link_person_id);						
		}
