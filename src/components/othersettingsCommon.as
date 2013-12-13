// ActionScript file

	public var txtSiteSpecificID:TextInput;
	public var txtEmailDomain:TextInput;
	public var txtSMTPServer:TextInput;
	public var txtSMTPUserID:TextInput;
	public var txtSMTPPassword:TextInput;
	public var comboFiscalType:ComboBoxNew;
	public var txtTimeout:TextInput;
	public var txtHospitalLimit:TextInput;
	public var chk4Maintenance:CheckBox;
	public var comboServiceLocation:ComboBoxNew;
	public var comboServiceProgramme:ComboBoxNew;
	
	override protected function loadData(current:Boolean=false):void{
					
		super.loadData();
		comboAdminName.dataProvider	=	parentApplication.getUserRequestTypeResult().sitetype;    
		comboFiscalType.dataProvider=	parentApplication.getUserRequestTypeResult().fiscalstart;  
		comboServiceLocation.dataProvider=parentApplication.getUserRequestNameResult().servicelocations.servicelocation;
		comboServiceProgramme.dataProvider=parentApplication.getUserRequestNameResult().programmes.programme;
    }
        
    override protected function setValues():void{
	
		super.setValues();  
		
		txtAdminName.text = dgList.selectedItem.name;
		comboAdminName.selectedItem = txtAdminName.text;			    	
      	txtSiteSpecificID.text=dgList.selectedItem.site_specific_id;
      	txtEmailDomain.text=dgList.selectedItem.email_domain;
      	comboFiscalType.selectedItem=dgList.selectedItem.fiscal_year_start;
      	txtTimeout.text = dgList.selectedItem.timeout;
      	txtHospitalLimit.text = dgList.selectedItem.hospitalisation_limit;
      	chk4Maintenance.selected = (dgList.selectedItem.maintenance == 1);
      	//txtSMTPServer.text=dgList.selectedItem.smtp_server;
      	//txtSMTPUserID.text=dgList.selectedItem.user_id;
      	//txtSMTPPassword.text=dgList.selectedItem.password;  
      	comboServiceLocation.selectedIndex	=	parentApplication.getComboIndex(comboServiceLocation.dataProvider,'data',dgList.selectedItem.location_id); 
      	comboServiceProgramme.selectedItem	=	dgList.selectedItem.programme_id; 
   	}	

	override public function comboChange():void{
			
		txtAdminName.text = comboAdminName.text;
		checkValid(txtAdminName);
	}

	override protected function setButtonState():void{

		super.setButtonState();
	  	
		switch(modeState) {
		
		case "Add New":
  			txtAdminName.visible = false;
			comboAdminName.visible = true;
			txtSiteSpecificID.editable=true;
			txtAdminName.editable = true;
  			break;
	  		
  		case "Edit":
			comboAdminName.visible = false;
			txtAdminName.visible = true;
			if (txtSiteSpecificID.text=='') {
				txtSiteSpecificID.editable=true;
			}
			else {
				txtSiteSpecificID.editable=false;
				txtSiteSpecificID.toolTip = txtSiteSpecificID.toolTip + " - cannot edit once entered";
			}
			if (txtAdminName.text=='') {
				txtAdminName.editable=true;
			}
			else {
				txtAdminName.editable=false;
				txtAdminName.toolTip = txtAdminName.toolTip + " - cannot edit once entered";
			}
			txtAdminName.editable=(txtAdminName.text=='');
			this.focusManager.setFocus(txtEmailDomain); //override focus set in super()
  			break;
	  		
  		case "View":
			comboAdminName.visible = false;
			txtAdminName.visible = true;
			txtAdminName.editable = false;
  			break;
  		}	  	
	  	
	  	if (txtAdminName.visible && txtAdminName.editable) this.focusManager.setFocus(txtAdminName);		
  		if (comboAdminName.visible) this.focusManager.setFocus(comboAdminName);	
  		//trace("settings combo: "+comboAdminName.visible+", text: "+txtAdminName.visible+", text: "+txtAdminName.editable);
	}

	override protected function extraInitialise():void{
			
		super.extraInitialise();

	    if(dgList.dataProvider.length>0){
		   	btnAddNew.visible = false; // can't have more than one site record
		   	btnEdit.visible = true;
		}
		else {
		   	btnAddNew.visible = true;
		   	btnEdit.visible = false;	    
		}
	 }
	
     override protected function setSendParameters():Object {
		
		var parameters:Object=super.setSendParameters();		

		parameters.siteSpecificID=	txtSiteSpecificID.text;
		parameters.emailDomain=txtEmailDomain.text;
		parameters.fiscalStart = comboFiscalType.text;
		parameters.timeout = txtTimeout.text;
		parameters.hospital_limit = txtHospitalLimit.text;
		parameters.location_id		=	comboServiceLocation.value;
		parameters.programme_id		=	comboServiceProgramme.value;
		if(chk4Maintenance.selected) {
			parameters.maintenance = '1';
		}
		else {
			parameters.maintenance = '0';
		}
		//parameters.smtpServer=txtSMTPServer.text;
		//parameters.smtpUserID=txtSMTPUserID.text;
		//parameters.smtpPassword=txtSMTPPassword.text;
		
		return parameters;			
	}

	override protected function checkValid(inputObject:Object):void{
		
		super.checkValid(inputObject);						
				
		if((txtSiteSpecificID.text=='')||(txtEmailDomain.text=='')||(txtTimeout.text=='')||(txtTimeout.text=='0')) { //||(txtSMTPServer.text=='')||(txtSMTPUserID.text=='')||(txtSMTPPassword.text=='')){
			saveEnabled	= false;
		}			
	}	
			
	override protected function refresh():void{
		 
		super.refresh();
		
		txtAdminName.text = comboAdminName.text;
		txtSiteSpecificID.text	=	"";		
		//txtSMTPServer.text	=	"";		
		//txtSMTPUserID.text	=	"";		
		//txtSMTPPassword.text	=	"";		
		txtEmailDomain.text	=	"";
		txtTimeout.text	=	"0";
		txtHospitalLimit.text	=	"10000";	
		comboFiscalType.selectedIndex=0;
		chk4Maintenance.selected = false;
		comboServiceLocation.selectedIndex=0;
		comboServiceProgramme.selectedIndex=0;			 						
	}
	
	protected function displayPopUpLocation(strTitle:String,editMode:Boolean=false):void{		
		
		if((comboServiceLocation.selectedIndex > 0) || (!editMode)){
			var pop1:popUpLocation = popUpLocation(PopUpManager.createPopUp(this, popUpLocation, true));
			PopUpManager.centerPopUp(pop1);
			pop1.showCloseButton=true;
			if (editMode){
				if (editAdminEnabled && inputEnabled){
					pop1.title="Edit "+strTitle;
					pop1.setup('location',true,comboServiceLocation.text,true);
				}
				else {
					pop1.title="View "+strTitle;
					pop1.setup('location',true,comboServiceLocation.text,false);
				}
			}
			else
			{
				pop1.title="Add New "+strTitle;
				pop1.initialise('location',true);
			}
  		}
	}
	
	private function displayAdminPopUp(strTitle:String,thisText:String,strTable:String,editMode:Boolean=false):void{		
		
		if(((thisText != "None") && (thisText != "Select..")) || (!editMode)){
			var pop1:popUpWindow = popUpWindow(PopUpManager.createPopUp(this, popUpWindow, true));
			PopUpManager.centerPopUp(pop1);
			pop1.showCloseButton=true;
			if (editMode){
				if (editAdminEnabled && inputEnabled){
					pop1.title="Edit "+strTitle;
					pop1.setup(strTable,true,thisText,true);
				}
				else {
					pop1.title="View "+strTitle;
					pop1.setup(strTable,true,thisText,false);
				}
			}
			else
			{
				pop1.title="Add New "+strTitle;
				pop1.initialise(strTable,true);
			}
  		}
 	}	
	
	protected function displayPopUpProgramme(strTitle:String,editMode:Boolean=false):void{		
		
		displayAdminPopUp(strTitle,comboServiceProgramme.text,'programme',editMode);
	}
