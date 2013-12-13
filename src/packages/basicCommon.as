// common code for adminCanvasClass and popupWindowClass

		import components.application.popupDocuments;
		import components.service.popUpAddress;
		import components.service.popUpEmail;
		import components.service.popUpPhone;
		
		import flash.events.KeyboardEvent;
		import flash.system.System;
		
		import mx.collections.ArrayCollection;
		import mx.events.CloseEvent;
		
		protected var modeState:String	=	"View";
		protected var tableName:String = "";
		protected var popupMode:Boolean = false;
		protected var selectQueryID:int = 0;
		protected var selectQueryField:String = "";
		
  		[Bindable]protected var saveEnabled:Boolean = false;
		[Bindable]protected var inputEnabled:Boolean = false;
		[Bindable]protected var inputAdminEnabled:Boolean = false;
  		[Bindable]protected var addEnabled:Boolean = false;
		[Bindable]protected var editEnabled:Boolean = false;
		[Bindable]protected var deleteEnabled:Boolean = false;
		[Bindable]protected var listEnabled:Boolean = false;
		[Bindable]protected var addAdminEnabled:Boolean = false;
		[Bindable]protected var editAdminEnabled:Boolean = false;
		[Bindable]protected var deleteAdminEnabled:Boolean = false;
			
		public var dgList:DataGridNew;
		public var btnEdit:Button;
		public var btnAddNew:Button;
		public var btnOk:Button;
		public var btnCancel:Button;
		//public var btnDelete:Button;
		public var status_txt:Label;
		
		protected var resetFlag:Boolean = false;
		protected var savedIndex:int = -1;
		protected var storeIndex:int = -1;
			   
		protected var copyTabFields:ArrayCollection;//contains the fields that needs to be copied to clipboard
		
		protected function copyTabFieldsToClipBoard(copyFields:ArrayCollection):void{
			
			var strData:String='';
			var csvSeparator:String=String.fromCharCode(13);
			var count:int;
			var fieldObject:Object;
			//var strHeader:String=''; //Enable this if headers needed to be diaplayed
			
			if(copyTabFields){//check to see if calle has send data in this arrayCollection
				for( count = 0; count < copyTabFields.length; count++){
					fieldObject=copyTabFields.getItemAt(count).data;
				//	strHeader    += copyTabFields.getItemAt(count).label +''+ csvSeparator; //Enable this if headers needed to be diaplayed
	        		strData += fieldObject.text +''+ csvSeparator;           		 
	        	} 
				strData=strData.slice( 0, -1 );//remove the trailing comma character
			}      	
        	
        	System.setClipboard(strData);
            Alert.show("Copy to clipboard done");            
		}
	 			 
		protected function setResetFlag():void{
			resetFlag = true;
		}

		protected function loadData(current:Boolean=false):void{  
			
			//trace("loadData");
			this.addEventListener( KeyboardEvent.KEY_DOWN, keyPressHandler );//for keyboard functions  
						
			status_txt.addEventListener("click", copyErrorToClipBoard);//click is handled in each status_txt
			
			status_txt.toolTip = "Click to copy to clipboard";
			btnCancel.toolTip = "CTRL + z";
			btnCancel.label = "Cancel";
			btnCancel.labelPlacement = "left";
			btnEdit.toolTip = "CTRL + e";
			btnEdit.label = "Edit";
			btnEdit.labelPlacement = "left";
			btnAddNew.toolTip = "CTRL + a";
			btnAddNew.label = "Add";
			btnAddNew.labelPlacement = "left";
			btnOk.toolTip = "CTRL + s"; 
			btnOk.label = "Save";
			btnOk.labelPlacement = "left";
		}
		
		protected function copyErrorToClipBoard(eventObj:Event):void{

   		// Use the eventObj parameter 
   		// to capture the event type.
	      	if (eventObj.type == "click"){
	
		      /* Send the value of this to the Output panel.
		      Because copyErrorToClipBoard is a function that is not defined 
		      on a listener object, this is a reference to the 
		      component instance to which copyErrorToClipBoard is registered
		      (myButton). Also, since this doesn't reference an 
		      instance of the Cart class, myGrid is undefined. 
		      */ 
		      	 System.setClipboard(eventObj.currentTarget.text);        
       		}
		}
		
		protected function reload():void{
		}
			
		protected function activate():void{
			//trace("activated "+tableName);
		}
		
		protected function keyPressHandler( e:KeyboardEvent ):void { 
    
    		if(parentApplication.duringAlert()) return; 
    		
          	if( e.ctrlKey ){    //ctrl key pressed
                
                //trace(e.charCode+" at "+e.currentTarget);
                
                if( e.charCode == 46 ) {//Delete
                    
                        //code for delete handeling                            
                }  	 	
                   
               	if( e.charCode == 97) {//a	
                   	if(btnAddNew.enabled)
                   	{
                   		this.focusManager.setFocus( btnAddNew );
                   		addMode();
                   	}                       
                }    

               	if( e.charCode == 122) {//z	
                   	if(btnCancel.enabled)
                   	{
                   		this.focusManager.setFocus( btnCancel );
                   		viewMode();
                   	}                       
                }
                                
               	if( e.charCode == 101) {// e
                    if(btnEdit.enabled)
                    {
                       	this.focusManager.setFocus( btnEdit );                       	
                   		editMode();
                   	}
                }    
                 
               	if( e.charCode == 115) {// s
                    if(btnOk.enabled)
                    {
                       	this.focusManager.setFocus( btnOk );
                  		sendData();
                   	}
                   	else
                   	{
                   		if( e.altKey && (parentApplication.getAppMode != 'View')) {
                   			 var alert:Alert = Alert.show("Confirm forced save","Mandatory fields missing",Alert.OK|Alert.CANCEL,this,onAlertClose);
                   		}
                   	}                       
                }
                
                if( e.charCode == 114) {// r
                	reload();
                }               
           	}//end of if( e.ctrlKey)
        }

	    private function onAlertClose(evt:CloseEvent):void {
	    	
	    	
            switch(evt.detail) {
               case Alert.OK:
               		sendData();
                	break;
                	
               case Alert.CANCEL:
                   //do nothing
                    break;              
            }
       	}

		protected function setSendParameters():Object {

			var parameters:Object=new Object();					
 			
 			parameters.tableName = tableName; 			
			parameters.action	=	modeState;
			parameters.userID	= parentApplication.getCurrentUserID();

			if(modeState	==	"Edit"){	
				//trace("setsend:"+dgList.selectedItem);			
				parameters.id	= dgList.selectedItem.__id;
			}
			else {
				parameters.id	= 0; // new record -> no ID yet
			}
			
			return parameters;			
		}
      	
		protected function sendData():void{
			
			if (resetFlag) {
				savedIndex = -1;
				//trace("Clear index: "+savedIndex);
				resetFlag = false;
			}
			if (modeState == "Add New") {
				savedIndex = -2;
				//trace("Clear index: "+savedIndex);
			}
			parentApplication.searching=true;
		}
		
		protected function showPopupWindow(titleString:String=""):void {		
		}
		
		protected function defaultFault(event:FaultEvent):void {
		
			// Handle service fault.
			if (event.fault is SOAPFault) {
				var fault:SOAPFault=event.fault as SOAPFault;
				var faultElement:XML=fault.element;
			}
				
			Alert.show(event.fault.faultString, "Error");		
		}
		
		protected function defaultResult(event:ResultEvent):void {	
			
			parentApplication.searching=false;
			status_txt.text = "";
		}

		protected function removeMe():void{
			
			PopUpManager.removePopUp(this)
		}
				
		public function refreshData():void{	
			//trace("refreshData");							
		}
		
		protected function refresh():void{
			
			//trace("refresh");
			refreshData();				
		}

		protected function setValues():void{	
			
			//trace("setValues");
			if (savedIndex > -1) {
				//trace("Restore index: "+savedIndex);
				//dgList.selectedIndex = savedIndex;
				for (var j:int = 0; j < dgList.dataProvider.length; j++) {
					if(dgList.dataProvider[j].__id == savedIndex) {
						dgList.selectedIndex = j;
						break;
					}	
				}
				savedIndex = -1;
			}
			else { 
				if (savedIndex == -2) { //has been reset - find newest (highest id value)
					var maxID:int = 0;
					var currentID:int;
					for (var i:int = 0; i < dgList.dataProvider.length; i++) {
						currentID = dgList.dataProvider[i].__id;
						trace(currentID+" "+maxID+" "+savedIndex);
						if(currentID > maxID) {
							savedIndex = i;
							maxID = currentID;
						}	
					}
					trace("Find index: "+savedIndex);
					dgList.selectedIndex = savedIndex;
					savedIndex = -1;
				}
			}	
		}
		
		protected function checkValid(inputObject:Object):void{
		
			//trace("checkvalid");
			//parentApplication.changeToUpper(inputObject);		
			saveEnabled	= inputEnabled;
			// To check if the text fields contains invalid characters (<, >)
           	if(inputObject != null) {
				var txtCheck:String = inputObject.text ;
				inputObject.errorString = "";
				if(txtCheck.length > 0){ 
					if(parentApplication.invalidChars(txtCheck))
					{
						inputObject.errorString = "Remove invalid characters (<,>)";
                   		saveEnabled = false;
                	}
   				}
   			}
		}
		
		public function fillData():void{
			
			//trace("filldata: "+modeState+", "+tableName+", "+popupMode+", "+inputEnabled+", "+dgList.selectedIndex);
			if (inputEnabled) return; // don't allow if editing

			storeIndex = dgList.selectedIndex;	
					
			if (dgList.selectedIndex == -1) {
				refresh();
			}
			else {
				setValues();
			}
			//trace("calling setButtonState from fillData");
			setButtonState();
		}
		
		protected function addMode():void{
		
			modeState	=	"Add New";
			status_txt.text = "";
			if (!popupMode) parentApplication.setAppMode(modeState);
			store();
			refresh();	
			//trace("calling setButtonState from addMode");		
			setButtonState();
		}
		
		protected function editMode():void{
		
			modeState	=	"Edit";
			status_txt.text = "";
			if (!popupMode) parentApplication.setAppMode(modeState);
			store();
			refreshData();
			//trace("calling setButtonState from editMode");
			setButtonState();
		}
		
		protected function viewMode(fromCancel:Boolean=true):void{
	  	
	  		if (fromCancel) {
	  			status_txt.text = '';
		 		status_txt.enabled = true;
	  		}
	  		
			if (popupMode) {
				removeMe(); // close window
			}
			else {
				modeState	=	"View";
				parentApplication.setAppMode(modeState);
				//trace("calling setButtonState from viewMode");
				setButtonState();
				reset();
			}
		}
				
		protected function reset():void{
			
			//trace("reset: "+savedIndex);
			init(tableName); // reset
		}
		
		protected function store():void{
			
			//savedIndex = dgList.selectedIndex; // store current index
			if (dgList.selectedItem != null)
				savedIndex = dgList.selectedItem.__id; // store current index
			//trace("Store index: "+savedIndex);
		}

		public function afterPopupClose():void{	
		}
		
		public function pre_init(currentTable:String, popup:Boolean=false, mode:String="View", queryID:int=0, queryField:String=""):void{
			
			tableName	=	currentTable;
			popupMode	=	popup;
			selectQueryID = queryID;
			selectQueryField = queryField;
			modeState = mode;
				
			parentApplication.setAppMode(modeState);
			
			//trace("setup: "+modeState+", "+tableName+", "+popupMode);      
        }

		public function init(currentTable:String, popup:Boolean=false, currentData:Boolean=false):void{

			tableName	=	currentTable;
			popupMode	=	popup;
			btnCancel.label = "Cancel";
			
			//trace("init: "+modeState+", "+tableName+", "+popupMode+", "+currentData);
			if (popupMode) { // hide edit/add buttons
				btnEdit.visible = false;
				btnAddNew.visible = false;
				if (currentData || (modeState == "View")){
					loadData(true);
					if (dgList.dataProvider != null)
						if (dgList.dataProvider.length>0) // ensure first item is selected
  		 					dgList.selectedIndex = 0;
					fillData();
					if (modeState == "View") {
						btnCancel.enabled = true;
						btnCancel.label = "Close";
						btnOk.visible = false;
					}
				}
				else {
					loadData();
					addMode();
				}
			}
			else {
				modeState	=	"View";	
				parentApplication.setAppMode(modeState);
				loadData();
  		 		if (dgList.dataProvider.length>0) // ensure first item is selected
  		 			dgList.selectedIndex = 0;
				//trace("calling setButtonState from init");
				setButtonState();
				fillData();
			}
		}

		protected function extraInitialise():void {			
		}
		
		protected function enableDisableDocumentButton():void {			
		}
			 	
	 	protected function loadCopyToClipBoardFields():void{	
	 	}
	 	
		protected function setButtonState():void{
			
			addAdminEnabled 	= 	parentApplication.allowedToAddAdmin();
	  		editAdminEnabled 	= 	parentApplication.allowedToEditAdmin();
	  		deleteAdminEnabled 	= 	parentApplication.allowedToDeleteAdmin();
	  					
			switch(modeState) {
		
			case "Add New":
				inputEnabled	=	true;
	  			editEnabled		=	false;
	  			addEnabled		=	false;
	  			listEnabled		=	false;
	  			deleteEnabled	=	false;
	  			inputAdminEnabled = addAdminEnabled;
				checkValid(null);
	  			break;
	  		
	  		case "Edit":
	  			inputEnabled	=	true;
	  			editEnabled		=	false;	
	  			addEnabled		=	false;
	  			listEnabled		=	true;
	  			deleteEnabled	=	false;
	  			inputAdminEnabled = editAdminEnabled;
				checkValid(null);
	  			break;
	  		
	  		case "View":
	  			inputEnabled	=	false;
	  			addEnabled 		= 	parentApplication.allowedToAddAdmin();;
	  			listEnabled		=	(dgList.dataProvider.length>0);
	  			editEnabled 	= 	listEnabled && parentApplication.allowedToEditAdmin();
	  			deleteEnabled	=	listEnabled && parentApplication.allowedToDeleteAdmin();
	  			saveEnabled		=	false;
	  			inputAdminEnabled = false;
	  			
	  			if (btnCancel.enabled) this.focusManager.setFocus(btnCancel);
	  			if (btnAddNew.visible && addEnabled) this.focusManager.setFocus(btnAddNew);
	  			if (btnEdit.visible && editEnabled) this.focusManager.setFocus(btnEdit);
	  			if (dgList.visible && listEnabled) this.focusManager.setFocus(dgList);
	  			checkValid(null);
	  			break;
	  		}
	  		
	  		if (this.focusManager.getFocus() != null)
	  			this.focusManager.getFocus().drawFocus(true);
	  		//trace("basic setButtonState "+addEnabled+", "+editEnabled+", "+inputEnabled+", "+listEnabled+", "+btnCancel.enabled);	
	  		//trace("basic setButtonState "+this.focusManager.getFocus()+" mode="+modeState);
	  		
	  		enableDisableDocumentButton();		  			
		}
		      
        protected function displayDocPopUp(strTitle:String,fieldName:String,docTypeName:String,newDoc:Boolean=false):void{		
				
			var pop1:popupDocuments = popupDocuments(PopUpManager.createPopUp(this, popupDocuments, true));
			PopUpManager.centerPopUp(pop1);
			pop1.showCloseButton=true;		
			pop1.title=strTitle;					
			var docID:int = dgList.selectedItem.__id;
	//Alert.show('docID:'+ docID +' fieldName:'+ fieldName);	
			var docMode:String = modeState;
			if ((docMode == "Edit") && newDoc) docMode = "Add New";
			pop1.pre_init(docTypeName,true,docMode,docID,fieldName);
		}

		protected function getModeType(editMode:Boolean):String {
			
			var tabType:String = 'view';
        	
        	if (editMode){
				if (editAdminEnabled && inputEnabled){
					tabType = 'edit';
				}
				else {
					tabType = 'view';
				}					
			}
			else
			{
				tabType = 'add';
			}
			return tabType;
		}
		
		protected function displayAddressPopUp(source:String,strTitle:String,thisCombo:ComboBoxNew,editMode:Boolean=false):void{
        	
        	var tabType:String = getModeType(editMode);
        	var tabName:String = 'address';

        	if (!parentApplication.isTabEnabled(tabName,tabType)) return;
        	
        	if((thisCombo.selectedIndex > 0) || (!editMode)){		
				var pop1:popUpAddress = popUpAddress(PopUpManager.createPopUp(this, popUpAddress, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				pop1.parentCaller = source;
				
				switch (tabType) {
					
					case 'edit':
					
						pop1.title="Edit "+strTitle;
						pop1.setup(tabName,thisCombo,true,dgList.selectedItem.__id,true);
						break;
					
					case 'view':
					
						pop1.title="View "+strTitle;
						pop1.setup(tabName,thisCombo,true,dgList.selectedItem.__id,false);
						break;
						
					case 'add':
					
						pop1.title="Add New "+strTitle;
						pop1.initialise(null,tabName,true,dgList.selectedItem.__id);
						break;
				}	
        	}
		}
		
		protected function displayPhonePopUp(source:String,strTitle:String,thisCombo:ComboBoxNew,editMode:Boolean=false):void{
        	
        	var tabType:String = getModeType(editMode);
			var tabName:String = 'phone';
			
        	if (!parentApplication.isTabEnabled(tabName,tabType)) return;
        	
        	if((thisCombo.selectedIndex > 0) || (!editMode)){		
				var pop1:popUpPhone = popUpPhone(PopUpManager.createPopUp(this, popUpPhone, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				pop1.parentCaller = source;
				
				switch (tabType) {
					
					case 'edit':
					
						pop1.title="Edit "+strTitle;
						pop1.setup(tabName,thisCombo,true,dgList.selectedItem.__id,true);
						break;
					
					case 'view':
					
						pop1.title="View "+strTitle;
						pop1.setup(tabName,thisCombo,true,dgList.selectedItem.__id,false);
						break;
						
					case 'add':
					
						pop1.title="Add New "+strTitle;
						pop1.initialise(null,tabName,true,dgList.selectedItem.__id);
						break;
				}	
        	}
		}
		
		protected function displayEmailPopUp(source:String,strTitle:String,thisCombo:ComboBoxNew,editMode:Boolean=false):void{
        	
        	var tabType:String = getModeType(editMode);
			var tabName:String = 'email';
			
        	if (!parentApplication.isTabEnabled(tabName,tabType)) return;
        	
        	if((thisCombo.selectedIndex > 0) || (!editMode)){		
				var pop1:popUpEmail = popUpEmail(PopUpManager.createPopUp(this, popUpEmail, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				pop1.parentCaller = source;
				
				switch (tabType) {
					
					case 'edit':
					
						pop1.title="Edit "+strTitle;
						pop1.setup(tabName,thisCombo,true,dgList.selectedItem.__id,true);
						break;
					
					case 'view':
					
						pop1.title="View "+strTitle;
						pop1.setup(tabName,thisCombo,true,dgList.selectedItem.__id,false);
						break;
						
					case 'add':
					
						pop1.title="Add New "+strTitle;
						pop1.initialise(null,tabName,true,dgList.selectedItem.__id);
						break;
				}	
        	}
		}