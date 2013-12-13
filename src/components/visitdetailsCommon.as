		import components.service.popUpHospital;
													
        public var dateVisitReferred:DateField;   
        public var dateVisitAttended:DateField;  
        public var dateVisitDischarged:DateField;         
        public var comboVisitHospital:ComboBoxNew;          
        public var comboVisitType:ComboBoxNew;        
        public var chkVisitAffected:CheckBox; 
        public var chkNewCase:CheckBox;       
        public var comboVisitReferredFrom:ComboBoxNew;
     //   public var comboVisitPAL:ComboBoxNew;
        public var chkVisitPWD:CheckBox;
       // public var comboVisitCAC:ComboBoxNew;
      //  public var comboVisitFootwearNeeded:ComboBoxNew;
        public var comboVisitMainTreatmentReason:ComboBoxNew;
        public var dgDetailTreatmentReasons:DataGrid;
        public var comboVisitDischargedTo:ComboBoxNew;
        public var txtVisitPatientNumber:TextInput;
        
  		public var chkPIPal:CheckBox;
  		public var chkPICAC:CheckBox;
  		public var chkPIFootwearNeeded:CheckBox;
  		
  		[Bindable]public var enableDisableCheckBox:Boolean=false;
        [Bindable] private var today:Date = new Date(); 
        [Bindable] protected var patientInfAddable:Boolean;

        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Date Referred", data:dateVisitReferred},{label:"Date Attended", data:dateVisitAttended},{label:"Date Discharged", data:dateVisitDischarged}]);           	           	
        }
                
        override protected function loadNonMandatoryFields():void{
        	
         	nonMandatoryDateFields = new ArrayCollection([{label:"Date Discharged", data:dateVisitDischarged}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Patient Number", data:txtVisitPatientNumber}]); 
       		nonMandatoryComboFields  = new ArrayCollection([{label:"Discharged To", data:comboVisitDischargedTo}]);        
        }
		
        override protected function loadData(current:Boolean=false):void{
        //Alert.show('visitdetails@loaddata()');
       // Alert.show('visitdetails@loaddata() : ' + parentApplication.getUserRequestTypeResult().patientPAL);
    	
        	super.loadData();
        		
			patientInfAddable=true;
			
			//comboPIPal.dataProvider	=	parentApplication.getUserRequestTypeResult().patientPAL;
			//comboPICAC.dataProvider	=	parentApplication.getUserRequestTypeResult().patientCAC;   	
			//comboPIFootwearNeeded.dataProvider	=	parentApplication.getUserRequestTypeResult().patientfootwearneeded;

		    comboVisitHospital.dataProvider		=	parentApplication.getUserRequestNameResult().hospitals.hospital;	
		    comboVisitReferredFrom.dataProvider	=	parentApplication.getUserRequestNameResult().patientreferredfroms.patientreferredfrom;	
		    comboVisitMainTreatmentReason.dataProvider = parentApplication.getUserRequestNameResult().mainpatienttreatmentreasons.mainpatienttreatmentreason;	
		    dgDetailTreatmentReasons.dataProvider = parentApplication.getUserRequestNameResult().detailpatienttreatmentreasons.detailpatienttreatmentreason;	
			  
		    //comboVisitAffected.dataProvider	=	parentApplication.getUserRequestTypeResult().patientaffected;
		    comboVisitDischargedTo.dataProvider	=	parentApplication.getUserRequestTypeResult().patientdischargedto;		 
		    comboVisitType.dataProvider	=	parentApplication.getUserRequestTypeResult().patientvisittype;						
				
			//comboVisitPWD.dataProvider	=	parentApplication.getUserRequestTypeResult().patientPWD;
			chkShowAll.visible = false;   	
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "hospital",data:"patient_visit"});
        	listSearchBy.push({label: "patient_number",data:"patient_visit"});
        	listSearchBy.push({label: "type",data:"patient_visit"});
        	listSearchBy.push({label: "affected",data:"patient_visit"});
        	listSearchBy.push({label: "date_referred",data:"patient_visit"});
        	listSearchBy.push({label: "referred_from",data:"patient_visit"});
        	listSearchBy.push({label: "date_attended",data:"patient_visit"});
        	listSearchBy.push({label: "date_discharged",data:"patient_visit"});
        	listSearchBy.push({label: "main_reason",data:"patient_visit"});
        	listSearchBy.push({label: "detail_reason",data:"patient_visit"});
        	listSearchBy.push({label: "discharged_to",data:"patient_visit"});
        	listSearchBy.push({label: "new_case",data:"patient_visit"});
        	listSearchBy.push({label: "PAL",data:"patient_visit"});
        	listSearchBy.push({label: "PWD",data:"patient_visit"});
        	listSearchBy.push({label: "care_after_cure",data:"patient_visit"});
        	listSearchBy.push({label: "footwear_needed",data:"patient_visit"});       		  
        				     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	
        	listSearchWhom.push({fields: "hospital",data:"patient_visit"});
        	listSearchWhom.push({fields: "patient_number",data:"patient_visit"});
        	listSearchWhom.push({fields: "type",data:"patient_visit"});
        	listSearchWhom.push({fields: "affected",data:"patient_visit"});
        	listSearchWhom.push({fields: "date_referred",data:"patient_visit"});
        	listSearchWhom.push({fields: "referred_from",data:"patient_visit"});
        	listSearchWhom.push({fields: "date_attended",data:"patient_visit"});
        	listSearchWhom.push({fields: "date_discharged",data:"patient_visit"});
        	listSearchWhom.push({fields: "main_reason",data:"patient_visit"});
        	listSearchWhom.push({fields: "detail_reason",data:"patient_visit"});
        	listSearchWhom.push({fields: "discharged_to",data:"patient_visit"});
        	listSearchWhom.push({fields: "new_case",data:"patient_visit"});
        	listSearchWhom.push({fields: "PAL",data:"patient_visit"});
        	listSearchWhom.push({fields: "PWD",data:"patient_visit"});
        	listSearchWhom.push({fields: "care_after_cure",data:"patient_visit"});
        	listSearchWhom.push({fields: "footwear_needed",data:"patient_visit"});
        }
        		
		private function getReasonsID():String{
			
			var reasonsID:String='';
			
			for each(var item:Object in dgDetailTreatmentReasons.dataProvider){
         		
         		if(item.check==true)      			
         			reasonsID+=item.id+',';
   			}
          	if(reasonsID!='')
         	    reasonsID=reasonsID.slice( 0, -1 );//delete the last comma character     		    
		//	Alert.show(trainingNeedsID);
			return reasonsID;
		}

		private function loadCheckBoxes():void{
		
		 	//first clear the previously set checkboxes
		 	clearCheckBoxes();
		 	
		 	if (dgList.selectedItem == null) return;
		 	if (dgList.selectedItem.detail_treatment_reason_id == null) return;
		 	
			var reasonsId:String=(dgList.selectedItem.detail_treatment_reason_id).toString();
			var selectedIDs:Array=reasonsId.split(',');
			
		///check if a item.id is present in selectedIDs array	
	
		 	for each(var item:Object in dgDetailTreatmentReasons.dataProvider){   
		//	Alert.show('loadcheckbox: '+item.id+'\n selected: '+dgList.selectedItem.training_needs);   			      		
	        	var checkedID:String=(item.id).toString();
	        	if((selectedIDs.indexOf(checkedID))>=0){ //if item is in array   			
	        		//strPublicSearchIDs+=item.id+',';
	        		item.check=true;
	        	}
	        	else{
	        		item.check=false;
	        	}         		      		
	        }// end for         	
		   		
		   		//if(strPublicSearchIDs!='')
		   		//	strPublicSearchIDs=strPublicSearchIDs.slice( 0, -1 ); // delete the last comma character	
	    }
	   
	   	private function clearCheckBoxes():void{

	   		for each(var item:Object in dgDetailTreatmentReasons.dataProvider)      		
	       		item.check=false;	         		
		} 
	
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.dateReferred	=	DateUtils.dateFieldToString(dateVisitReferred,parentApplication.dateFormat);
			parameters.dateAttended	=	DateUtils.dateFieldToString(dateVisitAttended,parentApplication.dateFormat);
			parameters.dateDischarged=	DateUtils.dateFieldToString(dateVisitDischarged,parentApplication.dateFormat);
			parameters.hospitalID	=	 comboVisitHospital.value;
			parameters.type	=	comboVisitType.text;
			parameters.affected	= (chkVisitAffected.selected)?"Self":"Relative";
			parameters.referredFromID	=	comboVisitReferredFrom.value;
			parameters.mainReasonID		=	comboVisitMainTreatmentReason.value;
			parameters.detailReasonID	=	getReasonsID();
			parameters.dischargedTo		=	comboVisitDischargedTo.text;
			parameters.patientNumber	=	txtVisitPatientNumber.text;
		
			parameters.PWD	=	(chkVisitPWD.selected)?"Yes":"No";
			parameters.CAC	=	(chkPICAC.selected)?"Yes":"No";
			parameters.footwearNeeded	=	(chkPIFootwearNeeded.selected)?"Yes":"No";
			parameters.PIPal	= (chkPIPal.selected)?"Yes":"No";
			parameters.newCase	= (chkNewCase.selected)?"Yes":"No";
			parameters.patient_inf_timestamp=parentApplication.getUserRequestResult().patient_inf_timestamp;
	//Alert.show('pit : '+ parameters.patient_inf_timestamp);		
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.patient_visit_timestamp;	
				//parameters.patient_inf_timestamp=dgList.selectedItem.patient_inf_timestamp;
				parameters.patient_inf_id	=	dgList.selectedItem.patient_inf_id;
				parameters.addPatientInf='yes';						
			}
			else{//modestate=='Add New'
				if(dgList.dataProvider.length>0)
					parameters.addPatientInf='no';
				else
					parameters.addPatientInf='yes';	
			}
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 				
			return parameters;
		} 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(inputObject == comboVisitMainTreatmentReason){
				updateCategory();
			} 
			if(dateVisitReferred.text == ""){
				saveEnabled	= false;								
			}
			if( comboVisitHospital.selectedIndex==0){
				saveEnabled	= false;	
			}
			if( comboVisitReferredFrom.selectedIndex==0){
				saveEnabled	= false;	
			}
			if( comboVisitMainTreatmentReason.selectedIndex==0){
				saveEnabled	= false;	
			}
			//if( comboVisitDetailTreatmentReason.selectedIndex==0){
			//	saveEnabled	= false;	
			//}
			if(DateUtils.compareDateFieldDates(dateVisitReferred, dateVisitAttended) == 1) {
				saveEnabled = false;
			}
			if(DateUtils.compareDateFieldDates(dateVisitAttended,dateVisitDischarged) == 1) {
				saveEnabled = false;
			}					
		}

		protected function updateCategory():void{
			
			var nameIndex:int = parentApplication.getComboData(comboVisitMainTreatmentReason,comboVisitMainTreatmentReason.text);	
		    comboVisitMainTreatmentReason.dataProvider = parentApplication.getUserRequestNameResult().mainpatienttreatmentreasons.mainpatienttreatmentreason;	
		    parentApplication.setComboData(comboVisitMainTreatmentReason,nameIndex);

			//nameIndex = parentApplication.getComboData(comboVisitDetailTreatmentReason,comboVisitDetailTreatmentReason.text);
			dgDetailTreatmentReasons.dataProvider		=	parentApplication.getUserRequestNameResult().detailpatienttreatmentreasons[comboVisitMainTreatmentReason.text].treatmentreason;	
			loadCheckBoxes();
			//parentApplication.setComboData(comboVisitDetailTreatmentReason,nameIndex);
			//if (comboVisitDetailTreatmentReason.selectedIndex == -1) comboVisitDetailTreatmentReason.selectedIndex = 0;
		}
				
		override public function refreshData():void{
			
			super.refreshData();	

			var nameIndex:int = parentApplication.getComboData(comboVisitHospital,comboVisitHospital.text);	
		    comboVisitHospital.dataProvider		=	parentApplication.getUserRequestNameResult().hospitals.hospital;	
		    parentApplication.setComboData(comboVisitHospital,nameIndex);
		    nameIndex = parentApplication.getComboData(comboVisitReferredFrom,comboVisitReferredFrom.text);	
		    comboVisitReferredFrom.dataProvider	=	parentApplication.getUserRequestNameResult().patientreferredfroms.patientreferredfrom;	
		    parentApplication.setComboData(comboVisitReferredFrom,nameIndex);
		    
			updateCategory();
				
			if (modeState != "View") {
				this.focusManager.setFocus(comboVisitHospital);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}		
		
        override protected function refresh():void{
		
			//comboVisitPAL.selectedIndex=0;

			//comboVisitCAC.selectedIndex=0;
			//comboVisitFootwearNeeded.selectedIndex=0;
				
			super.refresh();
					
			chkVisitPWD.selected=false;
			chkPIPal.selected=false;
			chkPICAC.selected=false;
			chkPIFootwearNeeded.selected=false;
			dateVisitReferred.text=DateUtils.dateToString(today,dateVisitReferred.formatString);
			dateVisitAttended.text=DateUtils.dateToString(today,dateVisitReferred.formatString);
			dateVisitDischarged.text='';
			txtVisitPatientNumber.text='';
			comboVisitHospital.selectedIndex=0;
			comboVisitType.selectedIndex=0;
			chkVisitAffected.selected=true;
			chkNewCase.selected=false;
			comboVisitReferredFrom.selectedIndex=0;
			comboVisitMainTreatmentReason.selectedIndex=0;
			updateCategory();
			comboVisitDischargedTo.selectedIndex=0;//-1;//since default is specified to be none
			
			patientInfAddable=true;
		}

		override protected function setValues():void{

			super.setValues();
//Alert.show(dgList.selectedItem.PAL);
			
			comboVisitHospital.selectedIndex	= parentApplication.getComboIndex(comboVisitHospital.dataProvider,'data',dgList.selectedItem.hospital_id);
			txtVisitPatientNumber.text	=	dgList.selectedItem.patient_number;
			dateVisitDischarged.text=   DateUtils.stringToDateFieldString(dgList.selectedItem.date_discharged,dateVisitDischarged,parentApplication.dateFormat);
         	dateVisitReferred.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_referred,dateVisitReferred,parentApplication.dateFormat);
			dateVisitAttended.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_attended,dateVisitAttended,parentApplication.dateFormat);
			comboVisitReferredFrom.selectedIndex	= parentApplication.getComboIndex(comboVisitReferredFrom.dataProvider,'data',dgList.selectedItem.referred_from_id);
			chkVisitAffected.selected = (dgList.selectedItem.affected == "Self");
			chkNewCase.selected = (dgList.selectedItem.new_case == "Yes");
			comboVisitType.selectedItem	= dgList.selectedItem.type;
			comboVisitDischargedTo.selectedItem = dgList.selectedItem.discharged_to;
			comboVisitMainTreatmentReason.selectedIndex=parentApplication.getComboIndex(comboVisitMainTreatmentReason.dataProvider,'data',dgList.selectedItem.main_treatment_reason_id);
			
			updateCategory();
			
			//comboVisitDetailTreatmentReason.selectedIndex=parentApplication.getComboIndex(comboVisitDetailTreatmentReason.dataProvider,'data',dgList.selectedItem.detail_treatment_reason_id);		
			chkPIPal.selected = (dgList.selectedItem.PAL == "Yes");	
			chkVisitPWD.selected	=	(dgList.selectedItem.PWD == "Yes");
			chkPICAC.selected	=	(dgList.selectedItem.care_after_cure == "Yes");
			chkPIFootwearNeeded.selected	=	(dgList.selectedItem.footwear_needed == "Yes");
		}		

		protected function displayPopUpHospital(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboVisitHospital.selectedIndex > 0) || (!editMode)){
				var pop1:popUpHospital = popUpHospital(PopUpManager.createPopUp(this, popUpHospital, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('hospital',true,comboVisitHospital.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('hospital',true,comboVisitHospital.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('hospital',true);
				}
  			}
		}
		
		protected function displayPopUpReferredFrom(strTitle:String,editMode:Boolean=false):void{		
			
			displayAdminPopUp(strTitle,comboVisitReferredFrom.text,'referred_from',editMode);
		}

		protected function displayPopUpReason(strTitle:String,thisText:String,editMode:Boolean=false):void{		
		
			if(((thisText != "None") && (thisText != "Select..")) || (!editMode)){
				var pop1:popUpTreatmentreason = popUpTreatmentreason(PopUpManager.createPopUp(this, popUpTreatmentreason, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;

				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('treatment_reason',true,thisText,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('treatment_reason',true,thisText,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('treatment_reason',true);
				}
				if (strTitle == "Detail Reason") {
					pop1.comboAdminMain.selectedItem = "No";
				}
				else {
					pop1.comboAdminMain.selectedItem = "Yes";
				}
  			}
		}

        protected function displayPopUpDetailReason(strTitle:String,editMode:Boolean=false):void{		
		
			if (dgDetailTreatmentReasons.selectedIndex == -1) {
				displayPopUpReason(strTitle,'None',editMode);
			}
			else {
				displayPopUpReason(strTitle,dgDetailTreatmentReasons.selectedItem.name,editMode);
			}
		}
		
		override protected function setButtonState():void{
		
			super.setButtonState();
			enableDisableCheckBox=inputEnabled;
		}
/*
		override protected function addMode():void{
			
			super.addMode();
			patientInfAddable=(dgList.dataProvider.length>0);			
		}
		
		override protected function viewMode():void{
			
			super.viewMode();
			patientInfAddable=true;
		}
		*/