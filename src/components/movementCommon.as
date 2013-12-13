// common code for tabMovementClass and popupMovementClass

		import mx.events.DropdownEvent;
		
		public var dgLoc:DataGridNew;
		public var dateMovementStartDate:DateField;
		public var dateMovementEndDate:DateField;
		public var comboMovementFixedDates:ComboBoxNew;
		public var comboMovementReason:ComboBoxNew;
		public var comboMovementPhone:ComboBoxNew;
		public var comboMovementAddress:ComboBoxNew;
		public var comboMovementEmail:ComboBoxNew;
		public var textMovementNotes:TextArea;
		public var notesLabel:Label;
		public var comboMovementTo:ComboBoxNew;
		public var comboMovementFrom:ComboBoxNew;
		public var userRequestGetLocData:HTTPService;
		
		[Bindable] public var showLocCombo:Boolean=false;
		[Bindable] public var fixAddEmailPhone:Boolean=false;
		
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{        	
        	allDateFields = new ArrayCollection([{label:"Start Date", data:dateMovementStartDate},{label:"End Date", data:dateMovementEndDate}]);          	           	
        }
		
		//load non mandatory fields for alerting if they are left empty while filling forms		
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryComboFields  = new ArrayCollection([{label:"Address", data:comboMovementAddress},
       		{label:"Email",data:comboMovementEmail},{label:"Phone",data:comboMovementPhone}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Movement Notes", data:textMovementNotes}]);
           }
		
        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({ label: "address",data:"movement"});//points address_id
			listSearchBy.push({ label: "email",data:"movement"});  //points to email_id
        	listSearchBy.push({label: "date_from",data:"movement"});
			listSearchBy.push({label: "date_until",data:"movement"});
			listSearchBy.push({ label: "dates_fixed",data:"movement"});
			listSearchBy.push({ label: "travel_from",data:"movement"});
			listSearchBy.push({ label: "travel_to",data:"movement"});
			listSearchBy.push({ label: "reason",data:"movement"});
			listSearchBy.push({ label: "notes",data:"movement"});	
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "address",data:"movement"});//points to address_id
			listSearchWhom.push({ fields: "email",data:"movement"});//points to email_id
			listSearchWhom.push({ fields: "date_from",data:"movement"});
			listSearchWhom.push({ fields: "date_until",data:"movement"});
			listSearchWhom.push({ fields: "dates_fixed",data:"movement"});
			listSearchWhom.push({ fields: "travel_from",data:"movement"});
			listSearchWhom.push({ fields: "travel_to",data:"movement"});
			listSearchWhom.push({ fields: "reason",data:"movement"});
			listSearchWhom.push({ fields: "notes",data:"movement"});	
        }

		private function initiateComboChange(event:DropdownEvent):void{
			
			//trace("comboChangeTo: "+comboMovementTo.selectedIndex);
			//trace("comboChangeFrom: "+comboMovementFrom.selectedIndex);
	      	checkValid(null);   
   		}
   		        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();	
			
			comboMovementTo.addEventListener(Event.CLOSE,initiateComboChange);
			comboMovementFrom.addEventListener(Event.CLOSE,initiateComboChange);
			
			requestLocationData();			
          
           	showLocCombo=true; //false;
           	fixAddEmailPhone=false;
				
           	comboMovementTo.dataProvider 	=	parentApplication.getUserRequestNameResult().tomovementlocations.tomovementlocation;
           	comboMovementFrom.dataProvider 	=	parentApplication.getUserRequestNameResult().frommovementlocations.frommovementlocation;
           	
           	comboMovementReason.dataProvider =	parentApplication.getUserRequestNameResult().movementreasons.movementreason;
           	comboMovementFixedDates.dataProvider =	parentApplication.getUserRequestTypeResult().movementfixeddates;	
   	
        	comboMovementAddress.dataProvider	=	parentApplication.getFamilyDetailsResult().movementlocaddresses.address;//addresses.address;
        	comboMovementEmail.dataProvider		=	parentApplication.getFamilyDetailsResult().movementlocemails.email;//emails.email;
        	comboMovementPhone.dataProvider		=	parentApplication.getFamilyDetailsResult().movementlocphones.phone;
			chkShowAll.visible = false;
 			//mergeDataProviders();
			parentApplication.getUserPermission('movement');						
        }
        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
		
			parameters.startDate	=	DateUtils.dateFieldToString(dateMovementStartDate,parentApplication.dateFormat);
			parameters.endDate		=	DateUtils.dateFieldToString(dateMovementEndDate,parentApplication.dateFormat);
			parameters.notes		=	textMovementNotes.text;
			parameters.reason		=	comboMovementReason.value;
			parameters.phoneID		=	comboMovementPhone.value;
			parameters.addressID	=	comboMovementAddress.value;
			parameters.emailID		=	comboMovementEmail.value;
			parameters.fixedDates	=	comboMovementFixedDates.text;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.movement_timestamp;
			}
			
			parameters.movementTo	=	comboMovementTo.text;
			parameters.movementFrom	=	comboMovementFrom.text;				
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;			
		}

		private function checkFromTo():void{
			
			if (comboMovementFrom.selectedIndex == -1) {//if travel_from isn't in combo list, add it to notes instead
				comboMovementFrom.selectedItem="Other";
				comboMovementFrom.text="Other";
				textMovementNotes.text = textMovementNotes.text+" From: "+dgList.selectedItem.travel_from;
			}	
			if (comboMovementTo.selectedIndex == -1) {//if travel_to isn't in combo list, add it to notes instead
				comboMovementTo.selectedItem="Other";
				comboMovementTo.text="Other";
				textMovementNotes.text = textMovementNotes.text+" To: "+dgList.selectedItem.travel_to;
			}
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboMovementPhone,comboMovementPhone.text);			
			comboMovementPhone.dataProvider		=	parentApplication.getFamilyDetailsResult().movementlocphones.phone;
			parentApplication.setComboData(comboMovementPhone,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboMovementAddress,comboMovementAddress.text);			
			comboMovementAddress.dataProvider	=	parentApplication.getFamilyDetailsResult().movementlocaddresses.address;
			parentApplication.setComboData(comboMovementAddress,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboMovementEmail,comboMovementEmail.text);			
			comboMovementEmail.dataProvider		=	parentApplication.getFamilyDetailsResult().movementlocemails.email;
        	parentApplication.setComboData(comboMovementEmail,nameIndex);
        	
        	nameIndex = parentApplication.getComboData(comboMovementTo,comboMovementTo.text);			
			comboMovementTo.dataProvider 	=	parentApplication.getUserRequestNameResult().tomovementlocations.tomovementlocation;
           	parentApplication.setComboData(comboMovementTo,nameIndex);
			
           	nameIndex = parentApplication.getComboData(comboMovementFrom,comboMovementFrom.text);			
			comboMovementFrom.dataProvider 	=	parentApplication.getUserRequestNameResult().frommovementlocations.frommovementlocation;
           	parentApplication.setComboData(comboMovementFrom,nameIndex);
			
           	nameIndex = parentApplication.getComboData(comboMovementReason,comboMovementReason.text);			
			comboMovementReason.dataProvider =	parentApplication.getUserRequestNameResult().movementreasons.movementreason;
        	parentApplication.setComboData(comboMovementReason,nameIndex);
			
        	checkFromTo();

			if (modeState != "View") {
				this.focusManager.setFocus(comboMovementTo);
				//trace(this.focusManager.getFocus());
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		        
        override protected function refresh():void{
			
			showLocCombo=true; //false;
			fixAddEmailPhone=false;
			comboMovementTo.selectedItem="Other";
			comboMovementTo.text="Other";			
			comboMovementFrom.selectedItem="Other";
			comboMovementFrom.text="Other";
						
			super.refresh();
			
			dateMovementStartDate.text=DateUtils.dateToString(today,dateMovementStartDate.formatString);
			dateMovementEndDate.text=DateUtils.dateToString(today,dateMovementEndDate.formatString);
			textMovementNotes.text="";	
			comboMovementAddress.selectedIndex=0;
			comboMovementReason.selectedIndex=0;
			comboMovementEmail.selectedIndex=0;
			comboMovementPhone.selectedIndex=0;
			comboMovementFixedDates.selectedIndex=0;
		}
		
		override protected function checkValid(inputObject:Object):void{
			
			super.checkValid(inputObject);
			
			if((comboMovementTo.selectedItem == "Other") || (comboMovementFrom.selectedItem == "Other")){
				notesLabel.enabled = false;							
			}
			else {
				notesLabel.enabled = true;
			}
			if((!notesLabel.enabled)&&(textMovementNotes.text=='')){
				saveEnabled	= false;								
			}
			if((dateMovementStartDate.text == "") || (dateMovementEndDate.text == "")){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateMovementStartDate, dateMovementEndDate) == 1) {
				saveEnabled = false;
			}
			if(comboMovementReason.selectedIndex	==	0){
				saveEnabled	= false;								
			}			
		}

		override protected function setValues():void{

			super.setValues();

         	dateMovementStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateMovementStartDate,parentApplication.dateFormat);
			dateMovementEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateMovementEndDate,parentApplication.dateFormat);			
			textMovementNotes.text		=	dgList.selectedItem.notes;
			comboMovementFixedDates.text=	dgList.selectedItem.dates_fixed;
			//comboMovementReason.selectedItem	=	dgList.selectedItem.reason;
			comboMovementReason.selectedIndex	=	parentApplication.getComboIndex(comboMovementReason.dataProvider,'data',dgList.selectedItem.reason_id);
			
			comboMovementFrom.selectedItem		=	dgList.selectedItem.travel_from;
			comboMovementFrom.text		=	dgList.selectedItem.travel_from;	 
			comboMovementTo.selectedItem		=	dgList.selectedItem.travel_to;	
			comboMovementTo.text		=	dgList.selectedItem.travel_to;	
			checkFromTo();
			
			comboMovementAddress.selectedIndex		=	parentApplication.getComboIndex(comboMovementAddress.dataProvider,'data',dgList.selectedItem.address_id);
			comboMovementEmail.selectedIndex		=	parentApplication.getComboIndex(comboMovementEmail.dataProvider,'data',dgList.selectedItem.email_id);
			comboMovementPhone.selectedIndex		=	parentApplication.getComboIndex(comboMovementPhone.dataProvider,'data',dgList.selectedItem.phone_id);
		}
	
		protected function displayPopUpAddress(strTitle:String,editable:Boolean=false):void{
        			
			displayAddressPopUp('',strTitle,comboMovementAddress,editable);
		}
			
		protected function displayPopUpEmail(strTitle:String,editable:Boolean=false):void{
        			
			displayEmailPopUp('',strTitle,comboMovementEmail,editable);	
		}
		
		protected function displayPopUpPhone(strTitle:String,editable:Boolean=false):void{
        			
			displayPhonePopUp('',strTitle,comboMovementPhone,editable);
		}

		override protected function setButtonState():void{
			
			super.setButtonState();
			if(modeState=="Add New")
				showLocCombo=true;
			else
				showLocCombo=false;
			if(modeState=="Edit")
				fixAddEmailPhone=false;
					
			if(!inputEnabled)
				fixAddEmailPhone=true;
			
			showLocCombo = true;
		}
		
		private function requestLocationData():void{			
			
			var parameters:Object	=	new Object();        	
        		//parameters.location		=	location;        		
        	parameters.requester	=	'movement';
        	   	
        	userRequestGetLocData.url	=	parentApplication.getPath()	+	"getLocationData.php";
        	userRequestGetLocData.send(parameters);       	 
		}
		
		public function populateLoc(event:ResultEvent):void{
			dgLoc.dataProvider=	userRequestGetLocData.lastResult.locationdata.location;				
		}  