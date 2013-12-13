// common code for organisationClass and popupOrganisationClass
		
		public var chkShowDualInfo:CheckBox;

		public var dgDual:DataGridNew;
		public var dgDualList:DataGridNew;
		public var userRequestGetDualData:HTTPService;
		public var userRequestSaveDualData:HTTPService;
		public var dual_status:Label;
		public var requestNewAdminData:HTTPService;
		
		public var btnEditDual:Button;
		public var btnAddNewDual:Button;
		public var btnOkDual:Button;
		public var btnCancelDual:Button;
		//public var btnDeleteDual:Button;
		
		[Bindable]public var inputEnabledDual:Boolean;
		[Bindable]public var editEnabledDual:Boolean;
		[Bindable]public var addEnabledDual:Boolean;
		[Bindable]public var listEnabledDual:Boolean;
		[Bindable]public var saveEnabledDual:Boolean;
		[Bindable]protected var dualItemVisible:Boolean;
		[Bindable]public var showDatagrid:Boolean;
		[Bindable]public var dualEnabled:Boolean;
		
		protected var modeStateDual:String;		
		protected var dualTableName:String;
				
		override protected function loadData(current:Boolean=false):void{ //override with combo tab fields
			
			super.loadData(current);
			
			dualEnabled=(modeState=="View");			 
			dualItemVisible=dualEnabled;
			//requestDualData();
			showDatagrid=false;
			chkShowDualInfo.selected=dualEnabled;
			dual_status.addEventListener("click", copyErrorToClipBoard);//click is handled in each status_txt
			dual_status.toolTip = "Click to copy to clipboard";
			
			btnCancelDual.label = "Cancel";
			btnCancelDual.labelPlacement = "left";
			btnEditDual.label = "Edit";
			btnEditDual.labelPlacement = "left";
			btnAddNewDual.label = "Add";
			btnAddNewDual.labelPlacement = "left";
			btnOkDual.label = "Save";
			btnOkDual.labelPlacement = "left";
			// set dualTableName here
		}	

		override public function fillData():void{
					
			super.fillData();
			requestDualData();
		}
		
		override protected function refresh():void{ //override with tab fields
					
			super.refresh();
			//DualItemVisible=false;
			//toggleHideDualInfo(true);
			//dualEnabled=false;
		}

		override protected function editMode():void{
			
			super.editMode();
			//DualItemVisible=false;
			toggleHideDualInfo(true);
			dualEnabled=false;
			parentApplication.setPanelEnabled(false);
		}

		override protected function addMode():void{
			
			super.addMode();
			parentApplication.setPanelEnabled(false);
			toggleHideDualInfo(true);
			dualEnabled = false;
		}

		override protected function viewMode(fromCancel:Boolean=true):void{
			
			super.viewMode(fromCancel);
			parentApplication.setPanelEnabled(true);
			dualEnabled = true;	
			dualItemVisible=true;
		}
						
		protected function addDualMode():void{
			
			modeStateDual="Add New";
			setDualButtonState();
			refreshDual();
			dualEnabled = false;	
		}
		
		protected function editDualMode():void{
			
			modeStateDual="Edit";
			setDualButtonState();
			refreshDualData();
			dualEnabled = false;	
			//parentApplication.setNavEnabled(false);		
		}

		protected function sendDualDataParameters():Object{ //override with tab fields
						
			var parameters:Object	=	new Object();           		
        	parameters.tableName	=	dualTableName;  
        	parameters.action 		=	modeStateDual; 
        	parameters.userID		= 	parentApplication.getCurrentUserID(); 	
        	
       		if(modeStateDual=='Edit'){
       			parameters.dualID 	=	dgDualList.selectedItem.__id;       		
 			}
			else {
				parameters.dualID	= 0; // new record -> no ID yet
			}
			//trace("dualID = "+parameters.dualID);
  			return parameters;
  		}
  					
		protected function sendDualData():void{

			parentApplication.searching=true;
			userRequestSaveDualData.useProxy = false;
       		userRequestSaveDualData.url	=	parentApplication.getPath()	+	"requestSaveDual.php";
        	//userRequestSaveDualData.url	=	parentApplication.getPath()	+	"requestSaveAdmin.php";
        	userRequestSaveDualData.send(sendDualDataParameters()); 
		}
		
		protected function viewDualMode():void{
			
			modeStateDual='View';
			setDualValues();
			setDualButtonState();
			dualEnabled = true;		
		}
		
		protected function requestDualData():void{	
			
			//trace("requestDualData");
			if (userRequestSaveDualData.lastResult != null) {
				dual_status.text=userRequestSaveDualData.lastResult.errors.error;
				if(userRequestSaveDualData.lastResult.errors.status=='success')
					dual_status.enabled=true;
				else
					dual_status.enabled=false;
			}
				
			parentApplication.searching=true;		
			var parameters:Object	=	new Object();           		
        	parameters.tableName	=	dualTableName;
        	parameters.fieldNames	=	parentApplication.getAdminFieldNames(dualTableName);
        	parameters.fieldValue	=	dgList.selectedItem.__id;        	   	
       //display timestamp,save result in a Dual_txt Label 	  
          // 	Dual_status.text=  userRequestSaveDualData.errors.error;
           	 	
        	userRequestGetDualData.url	=	parentApplication.getPath()	+	"getDualData.php";
        	userRequestGetDualData.send(parameters);       	 
		}
		
		protected function populateDual(event:ResultEvent):void{ //override with tab fields
	
			parentApplication.searching=false;
			status_txt.text = "";
			// set dgDual.dataProvider here
			viewDualMode();			
		}	
		
		protected function setDualValues():void{			
			
			getSelectedDualData();			
			fillDualValues();			
			setDualButtonState();		
		}

		protected function fillDualData():void{ //override with tab fields
		}
				
		protected function fillDualValues():void{
		
			if(dgDualList.selectedItem){
				fillDualData();
			}
			else{
				refreshDual();
			}				
			showDatagrid=false;
		}
	
		protected function getDualObj():Object { //override with tab fields
			
			var dualObj:Object = new Object();
			dualObj.__id = dgDual.selectedItem.__id;
			return dualObj;
		}
		
		private function getSelectedDualData():void{
			
			dgDualList.dataProvider=dgDual.dataProvider;
			
			if (dgDual.dataProvider == null) return;
			if (dgList.selectedItem == null) return;
			
			var column:DataGridColumn;
			var rowCount:int = dgDual.dataProvider.length;
			var columns:Array = dgDual.columns;
			var columnCount:int = columns.length;			
			var arrCollDual:ArrayCollection= new ArrayCollection();
			var dualObj:Object = new Object();               
			var cursor:IViewCursor = dgDual.dataProvider.createCursor ();
			var j:int = 0;
			
			//loop through rows
			while (!cursor.afterLast)
			{
				var obj:Object = null;
				obj = cursor.current;
				
				//loop through all columns for the row
				for(var k:int = 0; k < columnCount; k++)
				{
					column = columns[k];
					
					//Exclude column  other than organisation_id
					if(k==1)
					{		
						//Alert.show(j.toString());		
						if(dgList.selectedItem.__id==column.itemToLabel(obj)){
							 dgDual.selectedIndex	=	j;
							 dualObj = getDualObj();
              				 arrCollDual.addItem(dualObj);
						}									
					}
				}	
				j++;
				cursor.moveNext ();
			}
			
			dgDualList.dataProvider=arrCollDual;
			if(dgDualList.dataProvider.length)
				dgDualList.selectedIndex=0;
		//	return(-1);
		}
		
		private function setDualButtonState():void{
	  					
			switch(modeStateDual) {
		
			case "Add New":
				inputEnabledDual	=	true;
	  			editEnabledDual		=	false;
	  			addEnabledDual		=	false;
	  			listEnabledDual		=	false;
	  			saveEnabledDual		=	false;
	  			parentApplication.setPanelEnabled(false);
	  			inputEnabled	=	false;
	  			editEnabled		=	false;
	  			addEnabled		=	false;
	  			saveEnabled		=	false;
	  			listEnabled		=	false;				
	  			break;
	  		
	  		case "Edit":
	  			inputEnabledDual	=	true;
	  			editEnabledDual		=	false;	
	  			addEnabledDual		=	false;
	  			listEnabledDual		=	false;
	  			saveEnabledDual		=	true;
	  			parentApplication.setPanelEnabled(false);
	  			inputEnabled	=	false;
	  			editEnabled		=	false;
	  			addEnabled		=	false;
	  			saveEnabled		=	false;
	  			listEnabled		=	false;
	  			break;
	  		
	  		case "View":
	  			inputEnabledDual	=	false;
	  			addEnabledDual 		= 	true;	  		  			
	  			saveEnabledDual		=	false;
	  			parentApplication.setPanelEnabled(true);
	  			editEnabledDual 	= Boolean(dgDualList.dataProvider.length);
	  			listEnabledDual		=	editEnabledDual;
	  			setButtonState();					  			
	  			break;
	  		}
	  		
	  		if (!btnOk.visible) { // popup in view mode
	  			btnCancelDual.visible = false;	
	  			btnOkDual.visible = false;	
	  			btnEditDual.visible = false;	
	  			btnAddNewDual.visible = false;	
	  		}	
		}
		
		protected function checkDualValid(inputObject:Object):void{ //override with tab fields

			saveEnabledDual=inputEnabledDual;
			// To check if the text fields contains invalid characters (<, >)
           	if(inputObject != null) {
				var txtCheck:String = inputObject.text;
				inputObject.errorString = "";
				if(txtCheck.length > 0){ 
					if(parentApplication.invalidChars(txtCheck))
					{
						inputObject.errorString = "Remove invalid characters (<,>)";
                   		saveEnabledDual = false;
                	}
   				}
   			}		
		}
		
		protected function refreshDual():void{ //override with tab fields
				
			refreshDualData();
		}

		protected function refreshDualData():void{ //override with tab fields
		}
				
		protected function toggleHideDualInfo(hide:Boolean=false):void{
			
			dualItemVisible=!dualItemVisible;
			if(hide){
				dualItemVisible=false;
			}						
			setDualValues();		
			modeStateDual='View';
			setDualButtonState();
			chkShowDualInfo.selected = dualItemVisible;	
		}
		
		override public function changeCombo():void {
	    	
	    	if (parentApplication.getSidebarIndex() == 3) {
				super.changeCombo();
				setDualValues();
	    	}
   		}