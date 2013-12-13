// common code for adminCanvasClass and popupWindowClass

		import flash.events.Event;
		import flash.events.FocusEvent;
		
		import mx.events.DropdownEvent;
		
		protected var savedName:String = "";
		
		private var reclaimFocus:Boolean = true;
		
		protected var foundIndex:int = 0;
		protected var noDuplicate:Boolean = true;
			
		public var txtAdminName:TextInput;
		public var comboAdminName:ComboBoxNew;
		public var btnMore:Button;
		public var requestSaveAdmin:HTTPService;
		public var requestCurrentAdmin:HTTPService;
		//public var requestNewAdminData:HTTPService;

		override protected function loadData(current:Boolean=false):void{
			super.loadData();
			comboAdminName.addEventListener(Event.CLOSE,initiateComboChange);
			comboAdminName.addEventListener(FocusEvent.FOCUS_OUT,initiateComboChangeFocus);
			txtAdminName.toolTip = "40 chars";
			txtAdminName.maxChars = 40;
			if (current) {
				dgList.dataProvider	= requestCurrentAdmin.lastResult.rootTag.subTag;
				comboAdminName.dataProvider = requestCurrentAdmin.lastResult.rootTag.nameTag;
				//Alert.show('loaddata(true)@ adminCommon');
			}
			else {
				dgList.dataProvider	= parentApplication.getAdminSearchResult();
				comboAdminName.dataProvider = parentApplication.getAdminSearchName(); 
			}       		
		}

		private function initiateComboChange(event:DropdownEvent):void{
			
			trace("comboChange: "+comboAdminName.selectedIndex);
	      	changeCombo();   
   		}		

		private function initiateComboChangeFocus(event:FocusEvent):void{
			
			trace("comboChangeFocus: "+txtAdminName+": "+comboAdminName.selectedIndex);
			reclaimFocus = false;
	      	changeCombo();   
	      	reclaimFocus = true;
   		}
   		
	    public function changeCombo():void {
	    	
	    	trace("sidebar: "+parentApplication.getSidebarIndex());
	    	if (parentApplication.getSidebarIndex() == 3) {
	    		if (dgList.selectedIndex != comboAdminName.selectedIndex) {  	
					dgList.selectedIndex = comboAdminName.selectedIndex;
					////savedIndex = -1;
					fillData(); 
	    		}
	    	}
   		}
   				
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();				

		 	parameters.name = txtAdminName.text;
		 	if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.timestamp;
				//Alert.show(parameters.timestamp);
			}
			//Alert.show(tableName);
			
			return parameters;			
		}
      	
		override protected function sendData():void{

			super.sendData();

			//savedName = txtAdminName.text; // store current name
			//trace("sendData(): "+savedName);			
			requestSaveAdmin.useProxy = false;
			requestSaveAdmin.url	=	parentApplication.getPath()	+	"requestSaveAdmin.php";
			requestSaveAdmin.send(setSendParameters());		
		}
				
		override protected function defaultResult(event:ResultEvent):void {	
			
			super.defaultResult(event);
			status_txt.text=requestSaveAdmin.lastResult.errors.error;
			if(requestSaveAdmin.lastResult.errors.status=='success')
				status_txt.enabled=true;
			else
				status_txt.enabled=false;						
			
			if (popupMode) {
				if (status_txt.text.indexOf("Error") == -1){
					removeMe(); // close window if successful
					parentApplication.loadNames(false,tableName); // reload names	
				}
			} else
				parentApplication.loadNames(false,tableName); // reload names					
		}

		override public function afterPopupClose():void{	
								
			if (popupMode) {
				parentApplication.refreshData();
			}
			else
				viewMode(false);
		}
		
		override protected function reload():void{
			
			//trace("sidebar - reload(): "+parentApplication.getSidebarIndex());
			parentApplication.loadNames(false,tableName); // reload names	
		}
				
		protected function currentAdminResult(event:ResultEvent):void {	
			
			trace("currentAdminResult "+tableName+" "+popupMode+" true");
			parentApplication.searching=false;
			initialise(tableName,popupMode,true);
		}
				
		override protected function refresh():void{
			
			super.refresh();		
			txtAdminName.text	=	"";			
		}

		override protected function setValues():void{ 
			
			super.setValues();
			comboAdminName.selectedIndex = dgList.selectedIndex;
			txtAdminName.text	=	comboAdminName.text;	
		}
		
		protected function foundDuplicateName(txtName:String):Boolean{
			
			foundIndex = comboAdminName.dataProvider.source.indexOf(txtName);
			noDuplicate = true;
			
			while ((foundIndex != -1) && noDuplicate) {
				
				if (modeState == 'Edit') { //ignore the current match
					noDuplicate = (foundIndex == comboAdminName.selectedIndex);
				} else
				{
					noDuplicate = false
				}
				trace("foundIndex = ("+foundIndex+") :"+txtName);
				foundIndex = comboAdminName.dataProvider.source.indexOf(txtName,foundIndex+1);
			}
			return !noDuplicate;
			
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			var spacePattern:RegExp = / /g;
			var nameNoSpaces:String = txtAdminName.text.replace(spacePattern,"");
			trace("nameNoSpaces = ("+nameNoSpaces+")");
			if(nameNoSpaces.length == 0){
				saveEnabled = false;								
			} else {
				if (foundDuplicateName(txtAdminName.text))
					saveEnabled = false;
			}
		}
		
		override protected function reset():void{ // complete override (don't call super)
			
			//savedIndex = dgList.selectedIndex; // store current index
			//savedName = txtAdminName.text;
			trace("reset: "+savedIndex);
			parentApplication.searchAdminList(tableName);
			//initialise(tableName); // reset
		}

		override protected function store():void{ 
		
			super.store();
			//trace("Store index: "+comboAdminName.value);
		}
		
		public function setup(currentTable:String, popup:Boolean=false, givenName:String="", editable:Boolean=false):void{
			
			//trace("sidebar - setup(): "+parentApplication.getSidebarIndex());
			tableName	=	currentTable;
			popupMode	=	popup;
			savedName	=	givenName;
			if (editable && (parentApplication.isAdmin())) {
				modeState = "Edit";
			}
			else {
				modeState = "View";
				btnOk.visible = false;
			}
			parentApplication.setAppMode(modeState);
			
			//trace("admin setup: "+modeState+", "+tableName+", "+popupMode+", "+savedName);
        	var parameters:Object=new Object();      	
        	
        	parameters.tableName = tableName;			
			parameters.fieldNames	=	parentApplication.getAdminFieldNames(tableName);
			parameters.whereName = givenName;
				
			parameters.action	=	'Search Admin';

			requestCurrentAdmin.useProxy	=	false;
           	requestCurrentAdmin.url			=	parentApplication.getPath()	+	"search.php";
           	parentApplication.searching=true;
      		requestCurrentAdmin.send(parameters);       
        }

		public function initialise(currentTable:String, popup:Boolean=false, currentData:Boolean=false):void{

			//trace("sidebar - initialise(): "+parentApplication.getSidebarIndex());
			tableName	=	currentTable;
			popupMode	=	popup;
			btnCancel.label = "Cancel";
			
			//trace("admin initialise: "+modeState+", "+tableName+", "+popupMode+", "+currentData);
			if (popupMode) { // hide edit/add buttons
				btnEdit.visible = false;
				btnAddNew.visible = false;
				if (currentData){
					loadData(true);
					if (dgList.dataProvider != null)
						if (dgList.dataProvider.length>0) // ensure first item is selected
  		 					dgList.selectedIndex = 0;
					fillData();
					if (modeState == "View") {
						btnCancel.enabled = true;
						btnCancel.label = "Close";
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
				//trace("calling setButtonState from admin initialise");
				setButtonState();
				fillData();
			}
			extraInitialise();
		}
    	
		public function comboChange():void{ //not used anymore - use changeCombo() instead
			//trace("comboChange called");
		}
		
		override protected function setButtonState():void{

			super.setButtonState();
	  					
			switch(modeState) {
		
			case "Add New":
	  			txtAdminName.visible = true;
				comboAdminName.visible = false;
				txtAdminName.editable = true;
	  			break;
	  		
	  		case "Edit":
				comboAdminName.visible = false;
				txtAdminName.visible = true;
				txtAdminName.editable = true;
	  			break;
	  		
	  		case "View":
				comboAdminName.visible = (dgList.dataProvider.length>1);
				txtAdminName.visible = !comboAdminName.visible;
				txtAdminName.editable = false;
	  			break;
	  		}	
	  		
	  		if (reclaimFocus) {
	  			//trace("sidebar - setButtonState(): "+parentApplication.getSidebarIndex());
	  			if (txtAdminName.visible && txtAdminName.editable) this.focusManager.setFocus(txtAdminName);	
	  			if (comboAdminName.visible)
	  			 	if (this.focusManager.getFocus() != comboAdminName) this.focusManager.setFocus(comboAdminName);	
	  			if (this.focusManager.getFocus() != null)
	  				this.focusManager.getFocus().drawFocus(true);
	  		}
	  		trace("admin combo: "+comboAdminName.visible+", text: "+txtAdminName.visible+", text: "+txtAdminName.editable);
		}