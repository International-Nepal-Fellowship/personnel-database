// common code for servicelocationClass and popupLocationClass
	
		public var txtAdminDepartment:TextInput;
		public var comboAdminPhone:ComboBoxNew;
		public var comboAdminAddress:ComboBoxNew;
		public var comboAdminEmail:ComboBoxNew;
		
		public var requestNewAdminData:HTTPService;
		
		private var tempTable:String; // store temp values for delayed initialise()
		private var tempPopup:Boolean;
		private var tempCurrent:Boolean;

		[Bindable]public var newAddressEnabled:Boolean = false;
		[Bindable]public var newEmailEnabled:Boolean = false;
		[Bindable]public var newPhoneEnabled:Boolean = false;
		
		private var currentAddressID:int = 0;
		private var currentPhoneID:int = 0;
		private var currentEmailID:int = 0;
		private var currentFamilyID:int = 0;
				
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
 			parameters.dept 		= 	txtAdminDepartment.text;	
 			parameters.phoneID		=	comboAdminPhone.value;
			parameters.addressID	=	comboAdminAddress.value;
			parameters.emailID		=	comboAdminEmail.value;					
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminDepartment.text	=	"";	
			
			currentAddressID = 0;
			currentPhoneID = 0;
			currentEmailID = 0;
			currentFamilyID = 0;
			
			updateAddresses();		
		}

		override protected function setValues():void{
					
			super.setValues();
			
			txtAdminDepartment.text	=	dgList.selectedItem.dept;	
			
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
		}
				
		protected function displayPopUpAddress(strTitle:String,editable:Boolean=false):void{
        			
			displayAddressPopUp('Location',strTitle,comboAdminAddress,editable);
		}
			
		protected function displayPopUpEmail(strTitle:String,editable:Boolean=false):void{
        			
			displayEmailPopUp('Location',strTitle,comboAdminEmail,editable);	
		}
		
		protected function displayPopUpPhone(strTitle:String,editable:Boolean=false):void{
        			
			displayPhonePopUp('Location',strTitle,comboAdminPhone,editable);
		}
		
		override protected function loadData(current:Boolean=false):void{
			
			super.loadData(current);
			
			if (popupMode && !current) { //popup add
				comboAdminEmail.dataProvider=null;
       			comboAdminPhone.dataProvider=null;
       			comboAdminAddress.dataProvider=null; 
			}
			else {
				//comboAdminEmail.dataProvider=requestNewAdminData.lastResult.locationemails.email;
       			//comboAdminPhone.dataProvider=requestNewAdminData.lastResult.locationphones.phone;
       			//comboAdminAddress.dataProvider=requestNewAdminData.lastResult.locationaddresses.address;  
			} 
		}
		
		public function afterPopupAddresses():void{
			
			//trace("location - after popup addresses");
			if (comboAdminAddress == null) return;
			if (comboAdminPhone == null) return;
			if (comboAdminEmail == null) return;
			
			currentAddressID = Number(comboAdminAddress.value);
			currentPhoneID = Number(comboAdminPhone.value);
			currentEmailID = Number(comboAdminEmail.value);
			
			updateAddresses();
		}
								
		private function updateAddresses():void{
			
			//trace("location - update addresses");
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
           		
			comboAdminEmail.dataProvider=requestNewAdminData.lastResult.locationemails.email;
   			comboAdminPhone.dataProvider=requestNewAdminData.lastResult.locationphones.phone;
   			comboAdminAddress.dataProvider=requestNewAdminData.lastResult.locationaddresses.address;   
       			
           	comboAdminAddress.selectedIndex		=	parentApplication.getComboIndex(comboAdminAddress.dataProvider,'data',currentAddressID);
			comboAdminEmail.selectedIndex		=	parentApplication.getComboIndex(comboAdminEmail.dataProvider,'data',currentEmailID);
			comboAdminPhone.selectedIndex		=	parentApplication.getComboIndex(comboAdminPhone.dataProvider,'data',currentPhoneID);
			//trace("setValues: "+comboAdminAddress.selectedIndex+","+comboAdminEmail.selectedIndex+","+comboAdminPhone.selectedIndex);						
     	}		