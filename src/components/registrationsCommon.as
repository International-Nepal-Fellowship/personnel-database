// common code for tabDetailClass and popupDetailClass

		public var dateRegistrationStartDate:DateField;
		public var dateRegistrationEndDate:DateField;
		public var btnGetRegistration:Button;
		public var textRegistrationReference:TextInput;
		public var comboRegistrationType:ComboBoxNew;
		public var comboRegistrationOrganisation:ComboBoxNew;

		[Bindable] private var today:Date = new Date(); 

      // load all date fields for validation 
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([
         	{label:"Start Date", data:dateRegistrationStartDate},
         	{label:"End Date", data:dateRegistrationEndDate}         	      	
         	]);
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([
         	{label:"End Date", data:dateRegistrationEndDate}     	      	
         	]);
           }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "registration_type",data:"registrations"}); 
        	listSearchBy.push({label: "organisation",data:"registrations"}); 
        	listSearchBy.push({label: "reference",data:"registrations"}); 
        	listSearchBy.push({label: "start_date",data:"registrations"}); 
        	listSearchBy.push({label: "end_date",data:"registrations"});       
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields:"registration_type",data:"registrations"}); 
        	listSearchWhom.push({fields:"organisation",data:"registrations"}); 
        	listSearchWhom.push({fields:"reference",data:"registrations"}); 
        	listSearchWhom.push({fields:"start_date",data:"registrations"}); 
        	listSearchWhom.push({fields:"end_date",data:"registrations"}); 
        }
                          
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			       
            chkShowAll.visible = false;
            
            comboRegistrationOrganisation.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.registration;
            comboRegistrationType.dataProvider	=	parentApplication.getUserRequestNameResult().registration_types.registration_type;
          //  dgList.visible = false;
        }

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();

			parameters.organisationID =   comboRegistrationOrganisation.value;
			parameters.reference= 	textRegistrationReference.text;
			parameters.startDate   =	DateUtils.dateFieldToString(dateRegistrationStartDate,parentApplication.dateFormat);
			parameters.endDate		=	DateUtils.dateFieldToString(dateRegistrationEndDate,parentApplication.dateFormat);
			parameters.typeID	=	comboRegistrationType.value;
			if(modeState == 'Edit'){
                parameters.timestamp		=	dgList.selectedItem.registrations_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}    
          
		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboRegistrationOrganisation,comboRegistrationOrganisation.text);			
			comboRegistrationOrganisation.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.registration;
			parentApplication.setComboData(comboRegistrationOrganisation,nameIndex);
			nameIndex = parentApplication.getComboData(comboRegistrationType,comboRegistrationType.text);			
			comboRegistrationType.dataProvider	=	parentApplication.getUserRequestNameResult().registration_types.registration_type;
			parentApplication.setComboData(comboRegistrationType,nameIndex);
			
			if (modeState != "View") {
				this.focusManager.setFocus(comboRegistrationType);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}  

        override protected function refresh():void{
				
			super.refresh();
			textRegistrationReference.text="";
			dateRegistrationStartDate.text="";
			dateRegistrationEndDate.text="";
			comboRegistrationType.selectedIndex=0;
			comboRegistrationOrganisation.selectedIndex=0;
		}        
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateRegistrationStartDate.text == ""){
				saveEnabled	= false;
			}
			if(textRegistrationReference.text == ""){
				saveEnabled	= false;
			}
			if(DateUtils.compareDateFieldDates(dateRegistrationStartDate, dateRegistrationEndDate) == 1) {
				saveEnabled = false;
			}
			if((comboRegistrationType.selectedIndex == 0) || (comboRegistrationOrganisation.selectedIndex == 0)){
				saveEnabled = false;
			}
		}

		override protected function setValues():void{

			super.setValues();

			textRegistrationReference.text	=		dgList.selectedItem.reference;
			dateRegistrationStartDate.text	=		DateUtils.stringToDateFieldString(dgList.selectedItem.start_date,dateRegistrationStartDate,parentApplication.dateFormat);	
			dateRegistrationEndDate.text	=		DateUtils.stringToDateFieldString(dgList.selectedItem.end_date,dateRegistrationEndDate,parentApplication.dateFormat);	
			comboRegistrationType.selectedIndex	=	parentApplication.getComboIndex(comboRegistrationType.dataProvider,'data',dgList.selectedItem.registration_type_id);
			comboRegistrationOrganisation.selectedIndex	=	parentApplication.getComboIndex(comboRegistrationOrganisation.dataProvider,'data',dgList.selectedItem.organisation_id);
		}

		protected function displayPopUpDoc():void{		
			
			if (dgList.selectedItem == null) return;
			
			var newDoc:Boolean = (dgList.selectedItem.insurance_doc_id == 0);
			displayDocPopUp('Registration Document','registration_doc_id','registration_notes', newDoc);
		}    
		
		override protected function enableDisableDocumentButton():void{
			
			var rowCount:int=dgList.dataProvider.length;
		//Alert.show(rowCount.toString());			
			if(modeState=='Edit') {
				btnGetRegistration.visible=true;
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				btnGetRegistration.visible=(dgList.selectedItem.registration_doc_id>0)?true:false;
			}
			else{
				btnGetRegistration.visible=false;
			}		
		}
		
		protected function displayPopUpRegistrationType(strTitle:String,editMode:Boolean=false):void{		
		
			displayAdminPopUp(strTitle,comboRegistrationType.text,'registration_type',editMode);
		}