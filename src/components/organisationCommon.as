// common code for organisationClass and popupOrganisationClass
import mx.controls.CheckBox;
import mx.core.UIComponent;
import mx.controls.DataGrid;
		
		public var chkShowORepInfo:CheckBox;
		//public var chkSecondmentFrom:CheckBox;
		//public var chkSecondmentTo:CheckBox;
		//public var chkLocalSupportProvider:CheckBox;
		//public var chkChurch:CheckBox;
		//public var chkEmbassy:CheckBox;
		public var comboAdminPhone:ComboBoxNew;
		public var comboAdminAddress:ComboBoxNew;
		public var comboAdminEmail:ComboBoxNew;
		public var txtAdminORepPerson:TextInput;
		public var txtAdminORepKnownas:TextInput;
		public var txtAdminORepEmail:TextInput;
		public var comboAddressType:ComboBoxNew;
		public var comboPhoneType:ComboBoxNew;
		public var comboEmailType:ComboBoxNew;
		public var dgOrganisationTypes:DataGrid;
		
		[Bindable]public var enableDisableCheckBox:Boolean=false;
		
		[Bindable]public var newAddressEnabled:Boolean = false;
		[Bindable]public var newEmailEnabled:Boolean = false;
		[Bindable]public var newPhoneEnabled:Boolean = false;
		
		private var tempTable:String; // store temp values for delayed initialise()
		private var tempPopup:Boolean;
		private var tempCurrent:Boolean;
		
		private var currentAddressID:int = 0;
		private var currentPhoneID:int = 0;
		private var currentEmailID:int = 0;
		private var currentFamilyID:int = 0;
				
		override protected function loadData(current:Boolean=false):void{
			
			super.loadData(current);
			dgOrganisationTypes.dataProvider=parentApplication.getUserRequestNameResult().organisation_types.organisation_type;
			
			txtAdminName.toolTip = "60 chars";
			txtAdminName.maxChars = 60;	
			
			if (popupMode && !current) { //popup add
				comboAdminEmail.dataProvider=null;
       			comboAdminPhone.dataProvider=null;
       			comboAdminAddress.dataProvider=null; 
			}
			else {
				//comboAdminEmail.dataProvider=requestNewAdminData.lastResult.organisationemails.email;
       			//comboAdminPhone.dataProvider=requestNewAdminData.lastResult.organisationphones.phone;
       			//comboAdminAddress.dataProvider=requestNewAdminData.lastResult.organisationaddresses.address;  
			}
			dualTableName = "organisation_rep";
		}	
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			//parameters.secondment_from = (chkSecondmentFrom.selected)?"Yes":"No";
 			//parameters.secondment_to = (chkSecondmentTo.selected)?"Yes":"No";
 			//parameters.local_support_provider = (chkLocalSupportProvider.selected)?"Yes":"No";
 			//parameters.church = (chkChurch.selected)?"Yes":"No";
 			//parameters.embassy = (chkEmbassy.selected)?"Yes":"No";
 			parameters.phoneID		=	comboAdminPhone.value;
			parameters.addressID	=	comboAdminAddress.value;
			parameters.emailID		=	comboAdminEmail.value;
			parameters.organisationTypes	=	getOrganisationTypesID();
						
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.timestamp;
			}	
					
			return parameters;			
		}

		override public function refreshData():void{

			super.refreshData();
			
			dgOrganisationTypes.dataProvider=parentApplication.getUserRequestNameResult().organisation_types.organisation_type;
			loadCheckBoxes();
			
			/*
			if (modeState != "View") {
				this.focusManager.setFocus(comboTrainingCourse);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}*/
		}
				
		override protected function refresh():void{
					
			super.refresh();
			
			//chkSecondmentFrom.selected = false;
			//chkSecondmentTo.selected = false;
			//chkLocalSupportProvider.selected = false;
			//chkChurch.selected = false;	
			//chkEmbassy.selected = false;
			clearCheckBoxes();
			
			currentAddressID = 0;
			currentPhoneID = 0;
			currentEmailID = 0;
			currentFamilyID = 0;
			
			updateAddresses();
		}
		
		override protected function setValues():void{
					
			super.setValues();
			
			//chkSecondmentFrom.selected = (dgList.selectedItem.secondment_from == "Yes");
			//chkSecondmentTo.selected = (dgList.selectedItem.secondment_to == "Yes");
			//chkLocalSupportProvider.selected = (dgList.selectedItem.local_support_provider == "Yes");
			//chkChurch.selected = (dgList.selectedItem.church == "Yes");
			//chkEmbassy.selected = (dgList.selectedItem.embassy == "Yes");
			
			currentAddressID = dgList.selectedItem.address_id;
			currentPhoneID = dgList.selectedItem.phone_id;
			currentEmailID = dgList.selectedItem.email_id;
			currentFamilyID = dgList.selectedItem.__id;
			
			updateAddresses();
		}

		override protected function setButtonState():void{
	  		
	  		super.setButtonState();
	  		
	  		//trace("setButtonState: "+comboAdminAddress.selectedIndex+","+comboAdminEmail.selectedIndex+","+comboAdminPhone.selectedIndex);
			newAddressEnabled = (modeState == "View"); //&& (comboAdminAddress.selectedIndex < 1);
			newEmailEnabled = (modeState == "View"); // && (comboAdminEmail.selectedIndex < 1);
			newPhoneEnabled = (modeState == "View"); // && (comboAdminPhone.selectedIndex < 1);
			enableDisableCheckBox=inputEnabled;
		}
				
		protected function displayPopUpAddress(strTitle:String,editable:Boolean=false):void{
        	
        	displayAddressPopUp('Organisation',strTitle,comboAdminAddress,editable);
		}
			
		protected function displayPopUpEmail(strTitle:String,editable:Boolean=false):void{
        			
			displayEmailPopUp('Organisation',strTitle,comboAdminEmail,editable);	
		}
		
		protected function displayPopUpPhone(strTitle:String,editable:Boolean=false):void{
        			
			displayPhonePopUp('Organisation',strTitle,comboAdminPhone,editable);
		}
		
		override protected function sendDualDataParameters():Object{
						
			var parameters:Object	=	super.sendDualDataParameters();          		
  
        	parameters.name			= 	txtAdminORepPerson.text;
        	parameters.knownas		=	txtAdminORepKnownas.text;
        	parameters.email		=	txtAdminORepEmail.text;
        	parameters.organisationID =	dgList.selectedItem.__id;
        	 	
       		if(modeStateDual=='Edit'){
       			parameters.timestamp =	dgDualList.selectedItem.organisation_rep_timestamp; 	
  			}

			trace(parameters);
			return parameters;
		}
		
		override protected function populateDual(event:ResultEvent):void{
			
			dgDual.dataProvider=	userRequestGetDualData.lastResult.orepdatas.orepdata;	
			super.populateDual(event);	
		}	
		
		override protected function fillDualData():void{
			
			super.fillDualData();
			
			txtAdminORepPerson.text=dgDualList.selectedItem.name;
			txtAdminORepKnownas.text=dgDualList.selectedItem.known_as;
			txtAdminORepEmail.text=dgDualList.selectedItem.email;
			parentApplication.CheckValidEmail(txtAdminORepEmail);
		}

		override protected function getDualObj():Object {
			
			var dualObj:Object = super.getDualObj();
              				 
			dualObj.name = dgDual.selectedItem.name;
			dualObj.organisation_rep_timestamp = dgDual.selectedItem.organisation_rep_timestamp;
			dualObj.organisation_id = dgDual.selectedItem.organisation_id;
			dualObj.email = dgDual.selectedItem.email;
			dualObj.known_as = dgDual.selectedItem.known_as;
			
			return dualObj;
		}	
		
		override protected function checkDualValid(inputObject:Object):void{

			super.checkDualValid(inputObject);
			
			if(txtAdminORepPerson.text=='') {
				saveEnabledDual=false;	
			}			
			if(parentApplication.CheckValidEmail(txtAdminORepEmail)==false){	
                saveEnabledDual	= false;
            }		
		}
		
		override protected function refreshDual():void{
		
			super.refreshDual();
			
			txtAdminORepPerson.text='';
			txtAdminORepKnownas.text='';
			txtAdminORepEmail.text="";
		}

		override protected function refreshDualData():void{

			super.refreshDualData();
			if (modeStateDual != "View") {
				this.focusManager.setFocus(txtAdminORepPerson);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}

		public function afterPopupAddresses():void{
			
			//trace("organisation - after popup addresses");
			if (comboAdminAddress == null) return;
			if (comboAdminPhone == null) return;
			if (comboAdminEmail == null) return;
			
			currentAddressID = Number(comboAdminAddress.value);
			currentPhoneID = Number(comboAdminPhone.value);
			currentEmailID = Number(comboAdminEmail.value);
			
			updateAddresses();
		}
								
		private function updateAddresses():void{
			
			//trace("organisation - update addresses");
			parentApplication.searching	=	true;
			
			var parameters:Object=new Object();      	
        	
        	parameters.caller = tableName;
        	parameters.familyID = currentFamilyID;	
		
			requestNewAdminData.useProxy	=	false;
           	requestNewAdminData.url			=	parentApplication.getPath()	+	"requestNewAdminData.php";
           	parentApplication.searching		=	true;
      		requestNewAdminData.send(parameters); 
		}

		protected function refreshAdminDataResult(event:ResultEvent):void {        	
           	
           	parentApplication.searching	=	false;
           		
           	comboAdminEmail.dataProvider=requestNewAdminData.lastResult.organisationemails.email;
       		comboAdminPhone.dataProvider=requestNewAdminData.lastResult.organisationphones.phone;
       		comboAdminAddress.dataProvider=requestNewAdminData.lastResult.organisationaddresses.address; 
       			
           	comboAdminAddress.selectedIndex		=	parentApplication.getComboIndex(comboAdminAddress.dataProvider,'data',currentAddressID);
			comboAdminEmail.selectedIndex		=	parentApplication.getComboIndex(comboAdminEmail.dataProvider,'data',currentEmailID);
			comboAdminPhone.selectedIndex		=	parentApplication.getComboIndex(comboAdminPhone.dataProvider,'data',currentPhoneID);
			
			loadCheckBoxes();
			//trace("setValues: "+comboAdminAddress.selectedIndex+","+comboAdminEmail.selectedIndex+","+comboAdminPhone.selectedIndex);						
     	}
     	
     	private function getOrganisationTypesID():String{
			
			var organisationTypesID:String='';
			
			for each(var item:Object in dgOrganisationTypes.dataProvider){
         		
         		if(item.check==true)      			
         			organisationTypesID+=item.id+',';
   			}
          	if(organisationTypesID!='')
         	    organisationTypesID=organisationTypesID.slice( 0, -1 );//delete the last comma character     		    
		//	Alert.show(organisationTypesID);
			return organisationTypesID;
		}
		
		private function loadCheckBoxes():void{
		
		 	//first clear the previously set checkboxes
		 	clearCheckBoxes();
		 	
		 	if (dgList.selectedItem == null) return;
		 	
		 	if (requestNewAdminData.lastResult.organisationtypes == null) return;
	       	
			var organisationTypesId:String=(requestNewAdminData.lastResult.organisationtypes).toString();
			var selectedIDs:Array=organisationTypesId.split(',');
			
		///check if a item.id is present in selectedIDs array	
	
		 	for each(var item:Object in dgOrganisationTypes.dataProvider){    			      		
	        	var checkedID:String=(item.id).toString();
	        	if((selectedIDs.indexOf(checkedID))>=0){ //if item is in array 
	        		item.check=true;
	        	}
	        	else{
	        		item.check=false;
	        	}         		      		
	        }	
	    }
	   
	   	private function clearCheckBoxes():void{

	   		for each(var item:Object in dgOrganisationTypes.dataProvider)      		
	       		item.check=false;         		
		} 