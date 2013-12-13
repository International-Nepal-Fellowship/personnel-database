// common code for tabCanvasClass and popupTabClass
	import components.biodata.popUpCountries;
	import components.popUpWindow;
	import components.application.popUpOrganisation;
	
	import flash.events.Event;
	import flash.events.KeyboardEvent;
	import flash.events.MouseEvent;
	import flash.system.System;
	
	import mx.collections.ArrayCollection;
	import mx.events.CloseEvent;
	import mx.events.ListEvent;
	
	import packages.ComboBoxNew;
		 
		public var userRequestToggleVisitData:HTTPService;  
		//[Bindable]public var dpList:ArrayCollection;

		[Bindable]protected var addButtonEnabled:Boolean = false;
		[Bindable]protected var editButtonEnabled:Boolean = false;
		private var deletingOption:String='Delete';	
		protected var titleWindowInstance:TitleWindowData;
		protected var nepaliWindowInstance:NepaliDateWindow;
	//	protected var popUpRequester:String='default';
		
		public var btnDelete:Button;
		public var btnMore:Button;
		public var toggleResultHeight:Button;
		public var chkShowAll:CheckBox;		
		public var userRequestSaveTab:HTTPService;
		public var userRequestDeleteTab:HTTPService;
		public var requestCurrentTab:HTTPService;
		public var copyResultToClipboard:Button;
		public var printDG:Button;
	    		
		[Bindable]protected var defaultListHeight:uint = 160;
		[Bindable]protected var expandedListHeight:uint = 230;
		
		public var listSearchBy:Array 	=	new Array(); 
	    public var listSearchWhom:Array 	=	new Array();
	
	//for alerting when non mandatory fields are empty   
	    public var nonMandatoryTextFields:ArrayCollection;
		public var nonMandatoryDateFields:ArrayCollection;
		public var nonMandatoryComboFields:ArrayCollection;
		public var checkEmptyNonMandatoryFields:Boolean=true;
		public var allDateFields:ArrayCollection;

	    private var parentID:uint = 0; //for popups
	    public var parentCaller:String = ""; //for popups

	 	//check date validation for a single date field  
	    protected function CheckSingleDateValidity(objDate:Object):Boolean {	

			 return true; //dummy - not needed any more - all done through checkValid()
		}
		
		//check date validation for a group of date fields in an arraycollection	    
	    private function CheckValidDates(arrCollDates:ArrayCollection):Boolean {	
			
			var retval:Boolean = true;
			
			//check to see if calle has send data in this arrayCollection
        	if(arrCollDates)//AVOIDING this line will malfunction senddata() in those tabs where this function is not called
        	for(var i:int=0;i<arrCollDates.length;i++){ 
        		
        		var dateObject:Object=arrCollDates.getItemAt(i).data;
        		if (parentApplication.CheckValidDate(dateObject) == false) retval = false;		
			 	
	    	}//end for
	    		
	    	return retval;
		} 	    

	 	override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
        	if (CheckValidDates(allDateFields) == false) saveEnabled = false;	
        }
        	    	    
	    //for customizing the search window as per the page's content in the search.mxml				
		protected function pushSearchByVariables():void{
		
		 	//dataProvider for combo 'searchBy' in search.mxml		 	  
			listSearchBy.push({ label: "surname",data:"surname"});
			listSearchBy.push({ label: "forenames",data:"name"});   
        	listSearchBy.push({ label: "gender",data:"name"});
			listSearchBy.push({ label: "known_as",data:"name"});
			listSearchBy.push({ label: "dob",data:"name"});
			listSearchBy.push({ label: "marital_status",data:"name"});		
			//listSearchBy.push({ label: "qualification",data:"education"});
        	//listSearchBy.push({ label: "staff_type",data:"staff_type"});
        	//listSearchBy.push({ label: "address",data:"address"});		
	       	//listSearchBy.push({ label: "city_town",data:"address"});
    	    //listSearchBy.push({ label: "state_province",data:"address"});       
		}
		
		protected function pushSearchWhomVariables():void{
			
	        // dataprovider for the datagrid 'dg' in the form fieldname ('fields: "title") and table name(data:"name")       		
			listSearchWhom.push({ fields: "surname",data:"surname"});
			listSearchWhom.push({ fields: "forenames",data:"name"});
			if (!parentApplication.patientSystem) {
				listSearchWhom.push({fields: "status",data:"staff"}); 
				listSearchWhom.push({fields: "archived",data:"inf_staff"});
			}
			else {
				listSearchWhom.push({fields: "archived",data:"patient_inf"});
			}
		}
		
		override protected function keyPressHandler( e:KeyboardEvent ):void { 

			if(parentApplication.duringAlert()) return; 
			
			super.keyPressHandler(e);                			  
 
          	if( e.ctrlKey ){    //ctrl key pressed
                
                //trace(e.charCode);
                if( e.charCode == 68 ) {//D 
               	//  Delete
                    if(btnDelete.enabled)
                    {
                    	this.focusManager.setFocus( btnDelete );
                       	deleteRecord();
                   	}             
               	}  
               		
                if(( e.charCode == 112) && dgList.visible) {// p
                    if(listEnabled)
                    {
                    	parentApplication.doPrint(dgList);
                    }    
                }
                
                if(( e.charCode == 120) && dgList.visible) {// x  
                    if(listEnabled || (dgList.height == expandedListHeight))
                    {
                    	//this.focusManager.setFocus( dgList );
                    	toggleExpandedList();
                    }    
                }
                
                if(( e.charCode == 99) && dgList.visible) {// c  
                    if(listEnabled)
                    {
                    	exportCSV();
                    }    
                }
                
           	}//end of if( e.ctrlKey)
        }
		
		protected function exportCSV():void{
			
			DataGridDataExporter.exportCSV (dgList);
		}
		
		override protected function showPopupWindow(titleString:String=""):void{ // complete override (don't call super)
			
			titleWindowInstance =  TitleWindowData(PopUpManager.createPopUp(this,  TitleWindowData, false));     //instantiate and show the title window		
			//PopUpManager.centerPopUp(titleWindowInstance);
			titleWindowInstance.title = titleString;

  		 	if (dgList.dataProvider.length>0) // ensure first item is selected
  		 		dgList.selectedIndex = 0;

			//titleWindowInstance.height	=	370;
			//titleWindowInstance.width	=	400;
			titleWindowInstance.mainApp = this;
			titleWindowInstance.dg2.columns=	dgList.columns;			 
		}
   
		protected function showErrorDialog(event:FaultEvent):void {            	
			// Handle operation fault.
			Alert.show(event.fault.faultString, "Error");		        
		}
		
		override protected function defaultResult(event:ResultEvent):void {	
			
			super.defaultResult(event);

			status_txt.text	=	userRequestSaveTab.lastResult.errors.error;
			if(userRequestSaveTab.lastResult.errors.status=='success')
				status_txt.enabled=true;
			else
				status_txt.enabled=false;
	
			if (popupMode) {
				if (status_txt.text.indexOf("Error") == -1){
				    parentApplication.refreshList(tableName);
					removeMe(); // close window if successful
				}
			}
			else
				viewMode(false);
		}   

		override protected function reload():void{
			
			parentApplication.refreshList(tableName);
		}  				

  		private function dgItemClick(event:ListEvent):void {
		
			// single-click always fires first, so store state of shift/ctrl keys for double-click
			trace("item click: "+event.type+" + "+dgList.selectedIndex+" : "+storeIndex);	
			if ((!parentApplication.shiftKeyDown()) && (!parentApplication.ctrlKeyDown())) {
				dgList.height = defaultListHeight;
				fillData();
			}
			else dgDoubleClick(event);
		}
  		
  		private function dgDoubleClick(event:ListEvent):void {
		
			// list event doesn't have state of shift/ctrl keys so take from previous single-click mouse event
			if ((!parentApplication.shiftKeyDown()) && (!parentApplication.ctrlKeyDown())) return;
			
			trace("double click: "+event.type+" : "+storeIndex);	
			// Get the target of this event (Datagrid)
			var myGrid:DataGrid = event.target as DataGrid;
			
			parentApplication.copyRow(event, myGrid);
			parentApplication.copyCell(event, myGrid);

			dgList.selectedIndex = storeIndex;	
			trace("double click: "+dgList.selectedIndex);				
		}
				
		override protected function loadData(current:Boolean=false):void{
			
			super.loadData();
			
			btnDelete.toolTip = "CTRL + D"; 
			btnDelete.label = "Delete";
			btnDelete.labelPlacement = "left";
			
			dgList.doubleClickEnabled = false;
			dgList.toolTip = "Shift + click to copy selected row, ctrl + click to copy cell to clipboard"; 				
			dgList.addEventListener(ListEvent.ITEM_CLICK, dgItemClick);
			dgList.addEventListener(ListEvent.ITEM_DOUBLE_CLICK, dgDoubleClick);
						
			if (dgList.visible) {
				printDG.toolTip = printDG.toolTip + " (CTRL + p)"; 
				copyResultToClipboard.toolTip = copyResultToClipboard.toolTip + " (CTRL + c)";
				toggleResultHeight.toolTip = toggleResultHeight.toolTip + " (CTRL + x)";
			}
			
			modeState = parentApplication.getAppMode();
			chkShowAll.selected = parentApplication.isFamilyIncluded();
			if (!current) {
				pushSearchByVariables();
				pushSearchWhomVariables();
			}
			loadCopyToClipBoardFields();
			
		//	if(parentApplication.isIncompleteWarningOn()) 	
			loadAllDateFields();
			addValidationForDateFields();
			loadNonMandatoryFields();	
			addValidationForNonMandatoryTextFields();
        }
		        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();		

			if (popupMode) {
				parameters.parentID = parentID;
				//if (parentID == 0) {// from another tab rather than admin window
					parameters.nameid	= parentApplication.getCurrentID(); 
				//} else {
				//	parameters.nameid	= 0; 
				//}
			}
			else {
				parameters.parentID = 0;
				parameters.nameid	= parentApplication.getCurrentID(); 
			} 
		//Alert.show('table: ' +tableName);	
			return parameters;			
		}

        override protected function sendData():void{ // complete override (don't call super)
			
			if(!sendDataReady())
				return;
			
			super.sendData();
			
			userRequestSaveTab.useProxy = false;
           	userRequestSaveTab.url	=	parentApplication.getPath()	+	"requestSaveTab.php";
           	userRequestSaveTab.send(setSendParameters());       		      		
        }

		protected function copySelectedCell(event:ListEvent):void {
			//dummy
		}			
			
		override public function fillData():void{
			
			super.fillData();			
			chkShowAll.selected = parentApplication.isFamilyIncluded();
		}
		
		protected function deleteResult(event:ResultEvent):void {	
			
		//	super.defaultResult(event);

			status_txt.text	=	userRequestDeleteTab.lastResult.errors.error;
			if(userRequestDeleteTab.lastResult.errors.status=='success'){
				status_txt.enabled=true;
				if(dgList.selectedItem)	{		
					dgList.dataProvider.removeItemAt(dgList.selectedIndex);
					if (dgList.dataProvider.length > 0) dgList.selectedIndex = 0; 
				}	
			}
			else
				status_txt.enabled=false;
			
	/*		
			if(dgList.selectedItem)			
				dgList.dataProvider.removeItemAt(dgList.selectedIndex);
			else
				Alert.show('select Item to DELETE');
	*/		
		//	refresh();
		//	deleteEnabled	=	false;
			
			//if(tableName!='inf_staff')	
				viewMode();
			//else
			//	parentApplication.setToInitialStage();
		}   
		
		 private function onAlertDelete(evt:CloseEvent):void {
		 	
            switch(evt.detail) {
               case Alert.YES:              		
           			//var parameters:Object=super.setSendParameters();
           			var parameters:Object= new Object();      
	           		parameters.id	=	dgList.selectedItem.__id; 
	            	parameters.tableName	=	tableName; 
	            	parameters.action	=	'Delete'; 
	           		parameters.userID	= 	parentApplication.getCurrentUserID();
           			parameters.nameID	=	parentApplication.getCurrentID();             
      //  Alert.show(parameters.id+" id:nameid "+parameters.nameID);    
					userRequestDeleteTab.useProxy = false;
           			userRequestDeleteTab.url	=	parentApplication.getPath()	+	"requestDeleteTab.php";
           			parentApplication.searching=true;
           			userRequestDeleteTab.send(parameters);   
			
                	break;
                	
               case Alert.CANCEL:
                   //do nothing
                    break;              
            }
       }			
		
		protected function deleteRecord():void{
	
			var alertDelete:Alert = Alert.show("Are you sure?","Delete this record",Alert.YES|Alert.NO,this,onAlertDelete,null,Alert.NO);
			/*
			var parameters:Object=super.setSendParameters();     
            parameters.id	=	dgList.selectedItem.__id; 
            parameters.tableName	=	tableName; 
            parameters.nameID	=	parentApplication.getCurrentID();             
            
			userRequestDeleteTab.useProxy = false;
           	userRequestDeleteTab.url	=	parentApplication.getPath()	+	"requestDeleteTab.php";
           	parentApplication.searching=true;
           	userRequestDeleteTab.send(parameters);   
           	*/	
		}
		
		override protected function reset():void{ // complete override (don't call super)
			
			//savedIndex = dgList.selectedIndex; // store current index
			//trace("Reset index: "+savedIndex);
			parentApplication.setToInitialStage(); // reset
			//fillData();
		}
		
		override protected function setButtonState():void{

			var parentViewMode:Boolean = (parentApplication.getAppMode() == "View");
			
			parentApplication.getUserPermission(tableName);
			
	  		addButtonEnabled 	= 	parentApplication.allowedToAdd();
	  		editButtonEnabled 	= 	parentApplication.allowedToEdit();
	  		
	  		super.setButtonState();
	  			
			switch(modeState) {
	  		
	  		case "View":
	  			
	  			addEnabled 		= 	parentApplication.allowedToAdd() && parentViewMode;
	  			editEnabled 	= 	listEnabled && parentApplication.allowedToEdit() && parentViewMode;
	  			deleteEnabled	=	listEnabled && parentApplication.allowedToDelete() && parentViewMode;
	  			if (!parentViewMode) {
	  				btnCancel.enabled = false;
	  				chkShowAll.enabled = false;
	  			}
	  			
	  			//trace("tab setButtonState b4: "+this.focusManager.getFocus()+" mode="+modeState);
	  			//trace("tab setButtonState "+parentApplication.portals.visible);
	  			if (btnCancel.enabled) this.focusManager.setFocus(btnCancel);
	  			if (btnAddNew.visible && addEnabled) this.focusManager.setFocus(btnAddNew);
	  			if (btnEdit.visible && editEnabled) this.focusManager.setFocus(btnEdit);
	  			if (dgList.visible && listEnabled) this.focusManager.setFocus(dgList);
	  			
	  			break;
	  		}
	  		
	  		if (this.focusManager.getFocus() != null)
	  			this.focusManager.getFocus().drawFocus(true);			
	  		//trace("tab setButtonState "+addEnabled+", "+editEnabled+", "+inputEnabled+", "+listEnabled+", "+btnCancel.enabled);	
	  		//trace("tab setButtonState: "+this.focusManager.getFocus()+" mode="+modeState);				
		}

		protected function showAll():void{
		
			parentApplication.setIncludeFamily(chkShowAll.selected);
			//savedIndex = -1; //reset current address
			parentApplication.setToInitialStage(); // reset
		}

		protected function moveTo(event:KeyboardEvent):void{
	
			var keyCode:int = event.keyCode;
			//trace("moveto: "+keyCode);
			//trace("key down: "+event.type+" + "+parentApplication.shiftKeyDown()+" : "+parentApplication.ctrlKeyDown());
			if((keyCode	==	40) || (keyCode == 38))
				if(dgList.height == defaultListHeight)
	  				fillData();
	  		if (keyCode == 13) {
	  			dgList.height = defaultListHeight;
	  			fillData();
			}
		}

		public function setup(currentTable:String, givenCombo:ComboBoxNew, popup:Boolean=false, givenParentID:uint=0, editable:Boolean=false):void{
			
			tableName	=	currentTable;
			popupMode	=	popup;
			parentID	=	givenParentID;
			
			if (editable && (parentApplication.isAdmin())) {
				modeState = "Edit";
			}
			else {
				modeState = "View";
				btnOk.visible = false;
			}
			parentApplication.setAppMode(modeState);
			
			trace("tab setup: "+modeState+", "+tableName+", "+popupMode+", "+parentID);
        	var parameters:Object=new Object();      	
        	
        	parameters.tableName = tableName;			
			parameters.fieldNames	=	parentApplication.getTabFieldNames(tableName);
			parameters.whereID = givenCombo.value;
				
			parameters.action	=	'Search Tab';

			requestCurrentTab.useProxy	=	false;
           	requestCurrentTab.url			=	parentApplication.getPath()	+	"search.php";
           	parentApplication.searching=true;
      		requestCurrentTab.send(parameters);       
        }
        		
		public function initialise(dataArray:Object,currentTable:String,popup:Boolean=false,givenParentID:uint=0):void{
			
			dgList.dataProvider	= dataArray;
			tableName = currentTable;
			popupMode = popup;
			parentID	=	givenParentID;
			btnCancel.label = "Cancel";
			//btnDelete.visible = false; // for now
			
			//trace("tab initialise: "+modeState+", "+tableName+", "+popupMode+", "+parentID+", "+dgList.dataProvider);
			
			parentApplication.getUserPermission(tableName);
			
			if (popupMode) { // hide edit/add buttons and go directly to add mode	
			
				btnEdit.visible = false;
				btnAddNew.visible = false;
				dgList.visible = false;
				toggleResultHeight.visible = false;
				chkShowAll.visible = false;
				
				if (dataArray != null){ // not adding
					//loadData(true);
					if (dgList.dataProvider.length>0) // ensure first item is selected
  		 				dgList.selectedIndex = 0;
					fillData();
					if (modeState == "View") {
						btnCancel.enabled = true;
						btnCancel.label = "Close";
					}
				}
				else {
					//loadData();
					addMode();
				}			
			}
			else {
		    	if(dgList.dataProvider != null){
		    		if (dgList.dataProvider.length>0)
		    			dgList.selectedIndex	=	0; //default to first
		    	}
		    	parentApplication.search.pageSpecificSearchInitialization(listSearchBy,listSearchWhom,tableName); 	
		    	modeState = "View";		
		    	parentApplication.setAppMode(modeState); 
		    	//trace("calling setButtonState from tab initialise");    		
		    	setButtonState();    		
		    	fillData(); 
		 	}
		 	//this.dispatchEvent(new Event(Event.ACTIVATE));
		 	//trace("tab initialise: "+modeState);	
			//navigateToURL(new URLRequest("javascript: document.getElementById('inf').focus();"), "_self");
			extraInitialise();
		}
		
		protected function toggleExpandedList(forceSmall:Boolean=false):void{
	 	
	 		this.focusManager.setFocus( dgList );	
	 		if(forceSmall || (dgList.height == expandedListHeight)){
	 			dgList.height = defaultListHeight;
	 			fillData();
	 			//toggleResultHeight.label = "->";
	 		}
	 		else {
	 			dgList.height = expandedListHeight;
	 			//toggleResultHeight.label = "<-";
	 		}	 			
	 	}

	 	protected function updateScrollBar():void{
	 	
	 		//dgList.invalidateList();
	 		if (dgList.selectedIndex > -1)
	 			dgList.scrollToIndex(dgList.selectedIndex);
	 	}
	 	
	 	override protected function viewMode(fromCancel:Boolean=true):void{
        	super.viewMode(fromCancel);
        	checkEmptyNonMandatoryFields=true;
        }
        
        private function addValidationForNonMandatoryTextFields():void{
        	
        	if(nonMandatoryTextFields)//check to see if caller has added data to this arrayCollection
        	for(var count:int = 0; count < nonMandatoryTextFields.length; count++){
				var fieldObject:Object=nonMandatoryTextFields.getItemAt(count).data;
				fieldObject.addEventListener( Event.CHANGE, checkValidEvent );          		 
        	} 
        }

	   private function setNepaliDateEvent(event:MouseEvent):void{
			
			nepaliWindowInstance =  NepaliDateWindow(PopUpManager.createPopUp(this, NepaliDateWindow, true));     //instantiate and show the title window
  		 	PopUpManager.centerPopUp(nepaliWindowInstance);
  		 	nepaliWindowInstance.title = "Nepali Date";
  		 			
  			//built-in property  
  			nepaliWindowInstance.mainApp = this; //Reference to the main app scope
  			nepaliWindowInstance.init(event.currentTarget);				
		}

		public function setDate():void{
			
			checkValid(null);	
		}	
			
        private function addValidationForDateFields():void{
        	
        	if(allDateFields)//check to see if caller has added data to this arrayCollection
        	for(var count:int = 0; count < allDateFields.length; count++){
				var fieldObject:Object=allDateFields.getItemAt(count).data;
				fieldObject.addEventListener( KeyboardEvent.KEY_UP, checkValidKeyboardEvent ); 
				fieldObject.doubleClickEnabled = true; 
				fieldObject.addEventListener( MouseEvent.DOUBLE_CLICK, setNepaliDateEvent );  
				fieldObject.toolTip = "Double-click to show Nepali date";        		 
        	} 
        }
        
		private function checkValidEvent(event:Event):void {
			
			//trace(event.currentTarget.toString()+" "+event.target.toString());
			checkValid(event.currentTarget);	
		}   

		private function checkValidKeyboardEvent(event:KeyboardEvent):void {
			
			//trace(event.currentTarget.toString()+" "+event.target.toString());
			if (event.ctrlKey && (event.charCode == 110)) { // ctrl-n
				nepaliWindowInstance =  NepaliDateWindow(PopUpManager.createPopUp(this, NepaliDateWindow, true));     //instantiate and show the title window
  		 		PopUpManager.centerPopUp(nepaliWindowInstance);
  		 		nepaliWindowInstance.title = "Nepali Date";
  		 			
  				//built-in property  
  				nepaliWindowInstance.mainApp = this; //Reference to the main app scope
  				nepaliWindowInstance.init(event.currentTarget);	
			}
			else {
				checkValid(event.currentTarget);
			}	
		}
				     
        protected function checkEmptyFields(comboFields:ArrayCollection ,dateFields:ArrayCollection ,textFields:ArrayCollection):Boolean{
        	
        	var alertMessage:String='';
        	//Alert.show(dateFields.length.toString())
        	
        	//check if any text fields are empty
        	if(textFields)//check to see if calle has send data in this arrayCollection
        	for(var count:int = 0; count < textFields.length; count++){
				var fieldObject:Object=textFields.getItemAt(count).data;
        		 if(fieldObject.text=='')
        		 	 alertMessage += textFields.getItemAt(count).label+'\n';           		 
        	} 
          	//check if any date fields are empty
        	if(dateFields)
        	for(count = 0; count < dateFields.length; count++){
				 fieldObject=dateFields.getItemAt(count).data;
        		 if(fieldObject.text=='')
        		 	 alertMessage += dateFields.getItemAt(count).label+'\n';           		 
        	} 
        	//check if any ComboBox value is not selected
     		if(comboFields)
        	for(count = 0; count < comboFields.length; count++){
				 fieldObject=comboFields.getItemAt(count).data;
        		 if(fieldObject.selectedIndex==0)
        		 	 alertMessage += comboFields.getItemAt(count).label+'\n';           		 
        	} 
        	
        	if(alertMessage!=''){
        	  		Alert.show(alertMessage,'Values not assigned for the following fields:');
        	  		return false;
        	}
        	else 
        		return true;        	
        }
        
        protected function sendDataReady():Boolean{
        	
        	var invalidMsg:String=parentApplication.checkValidFields(nonMandatoryTextFields);
        	if(invalidMsg!=''){//if invalid characters found
        		Alert.show(invalidMsg);
        		return false;
        	}
        	
        	if(parentApplication.isIncompleteWarningOn()){ //Checks if the isIncompleteWarningOn is checked or not
        		var notEmpty:Boolean=false;	
        		if(!checkEmptyNonMandatoryFields){        		
        			return true;
        		}
        		else{       	
        			notEmpty=checkEmptyFields(nonMandatoryComboFields,nonMandatoryDateFields,nonMandatoryTextFields);
        			checkEmptyNonMandatoryFields = false;
       			}
       		
       			return notEmpty;     		
        	}
        	else
          		return true;
        }
        
	 	protected function loadAllDateFields():void{//load all date fields for validation
	 		
	 	}
	 	
	 	protected function loadNonMandatoryFields():void{
	 		
	 	}
		
		protected function currentTabResult(event:ResultEvent):void {	
			
			trace("currentTabResult "+tableName+" "+popupMode+" true");
			parentApplication.searching=false;
			initialise(requestCurrentTab.lastResult.rootTag.subTag,tableName,popupMode,parentID);
		}
		
		protected function displayAdminPopUp(strTitle:String,thisText:String,strTable:String,editMode:Boolean=false):void{		
		
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
		
		protected function displayCountryPopUp(strTitle:String,thisCombo:ComboBoxNew,editMode:Boolean=false):void{		
		
			if((thisCombo.selectedIndex > 0) || (!editMode)){
				var pop1:popUpCountries = popUpCountries(PopUpManager.createPopUp(this,popUpCountries, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('country',true,thisCombo.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('country',true,thisCombo.text,false);
					}					
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('country',true);
				}
  			}
		}
		
		public function toggleRecentVisit():void{
			
			var parameters:Object=new Object();	
	 		 parameters.patientID	=	parentApplication.getCurrentID();
	 		 parameters.requester	=	'patient system';
	 		 parameters.tableName 	= 	tableName;
	 		 if(chkShowAll.selected)
	 		 	parameters.getAllVisitData	=	'true';
	 		 else
	 		 	parameters.getAllVisitData	=	'false';
	 		 	
	 		 userRequestToggleVisitData.useProxy = false;
           	 userRequestToggleVisitData.url	=	parentApplication.getPath()	+	"requestCheckDb.php";
           	 parentApplication.searching=true;
             userRequestToggleVisitData.send(parameters);                    		
		}
		
		public function toggleVisitDataResult(event:ResultEvent):void{
			
			dgList.dataProvider=userRequestToggleVisitData.lastResult.rootTag.subTag;
			parentApplication.setIncludeFamily(chkShowAll.selected);
			
			if(dgList.dataProvider != null){
		    	if (dgList.dataProvider.length>0)
		    		dgList.selectedIndex	=	0; //default to first
		    }
    		
		    fillData();
			parentApplication.searching=false;
		}
		
		protected function displayPopUpOrganisation(strTitle:String,comboOrganisation:ComboBoxNew,editMode:Boolean=false):void{		
		//return;
			if((comboOrganisation.selectedIndex > 0) || (!editMode)){
				var pop1:popUpOrganisation = popUpOrganisation(PopUpManager.createPopUp(this,popUpOrganisation, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('organisation',true,comboOrganisation.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('organisation',true,comboOrganisation.text,false);
					}					
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('organisation',true);
				}
  			}
		}