		import flash.events.FocusEvent;
		import mx.events.DropdownEvent;
		
		[Bindable]	protected var userPermission:ArrayCollection = new ArrayCollection();
		[Bindable]	public var enableDisableCheckBox:Boolean	=	false;
		[Bindable]	protected var showAddUser:Boolean	=	false;
		
		public var comboMainTabs:ComboBoxNew;
		public var txtNewName:TextInput;
		public var comboNames:ComboBoxNew;
	//	public var btnCurrentGroup:Button;
		protected var userPermissionInfo:Array = new Array();
		protected var tabBiodata:Array;
		protected var tabApplication:Array;
		protected var tabService:Array;
		protected var tabPatient:Array;
		protected var tabHealthStaff:Array;
		protected var nameChanged:Boolean = false;
		
		private var allTabNames:Array;
		
		public var userRequestCheckDBUserName:HTTPService;	
		public var requestCurrentPermission:HTTPService;
		public var requestSaveAdmin:HTTPService;
		
		override protected function defaultResult(event:ResultEvent):void {	
			
			super.defaultResult(event);
			
			status_txt.text=requestSaveAdmin.lastResult.errors.error;
			if(requestSaveAdmin.lastResult.errors.status=='success')
				status_txt.enabled=true;
			else
				status_txt.enabled=false;
									
			parentApplication.loadNames(); // reload names							
		}

		override public function afterPopupClose():void{	
								
			viewMode(false);
		}
			
		override public function refreshData():void{
			
			super.refreshData();				
			//comboNames.dataProvider	=  parentApplication.getUserRequestNameResult().groupnames.groupname;	
			//this.focusManager.setFocus(comboNames);
		}	
			              
		override protected function refresh():void{
			
			super.refresh();
			
		 	saveEnabled=false;
		 	txtNewName.text	=	"";	
		 	userPermission.removeAll();//clear the old permissions
			for each ( var tab:String in tabBiodata ){ 
					
				var info: userPermissionObject = new userPermissionObject();					
				info.tabName = tab;					
				userPermission.addItem(info);										
			}	
		}
		
		protected function setCurrentUserPermission(tabArray:Array,currentMode:String):void{

			userPermission.removeAll();//clear the old permissions
			
			if(comboNames.value==0)			
			 	currentMode='Add New';
			 
			 //trace("setCurrentUserPermission: "+currentMode);
			 switch(currentMode){
        	
        		case 'Add New':	
        			for each ( var tab1:String in tabArray ){ 
							
						var info1: userPermissionObject = new userPermissionObject();					
						info1.tabName = tab1;								
						userPermission.addItem(info1);												
					}	
					break;
        			
        		default:			
					for each ( var tab:String in tabArray ){ 
							
						var info: userPermissionObject = new userPermissionObject();					
						info.tabName = tab;
		
							if(userPermissionInfo[tab+'_add']=='y') info.addStatus =true;
							if(userPermissionInfo[tab+'_delete']=='y') info.deleteStatus =true;
							if(userPermissionInfo[tab+'_edit']=='y') info.editStatus =true;
							if(userPermissionInfo[tab+'_view']=='y') info.viewStatus =true;
							
						userPermission.addItem(info);												
					}
					break;	
        	}// end of switch       	
		}

		private function initiateComboChange(event:DropdownEvent):void{
			
			trace("comboChange: "+comboNames.selectedIndex);
	      	changeCombo();   
   		}		

		private function initiateComboChangeFocus(event:FocusEvent):void{
			
			trace("comboChangeFocus: "+comboNames.selectedIndex);
	      	changeCombo();   
   		}
   		
	    protected function changeCombo():void {
	    	
	    	fillUserPermission(modeState);
   		}
   		
		protected function extraLoadData():void {
			
			comboNames.dataProvider=parentApplication.getUserRequestNameResult().groupnames.groupname;
			comboNames.selectedIndex=1;
		//	comboUserNames.dataProvider=parentApplication.getUserRequestNameResult().usernames.username;
		//	comboUserNames.selectedIndex=0;
		}
		
		override protected function loadData(current:Boolean=false):void{
			
			super.loadData();		
			
			comboNames.addEventListener(Event.CLOSE,initiateComboChange);
			//comboNames.addEventListener(FocusEvent.FOCUS_OUT,initiateComboChangeFocus);
			
			enableDisableCheckBox=inputEnabled;//for enabling/disabling the checkboxs in the datagrid
			var mainTabs:Array =new Array();
			mainTabs.push({ label: "Biodata",data:"biodata"});
		  	if (parentApplication.patientSystem) {
				mainTabs.push({ label: "Patient",data:"patient"});
				mainTabs.push({ label: "Health Staff",data:"healthstaff"});
		  	}
			else {
				if (parentApplication.personnelWorldwideSystem)
					mainTabs.push({ label: "Application",data:"application"});
				mainTabs.push({ label: "Service",data:"service"});
			}
			
			tabBiodata = parentApplication.biodatas.getChildLabels().split(",");
			tabApplication = parentApplication.app.getChildLabels().split(",");
			tabService = parentApplication.tests.getChildLabels().split(",");
			tabPatient = parentApplication.patient.getChildLabels().split(",");
			tabHealthStaff = parentApplication.staff.getChildLabels().split(",");

			if (comboMainTabs.dataProvider.length == 0)
				comboMainTabs.dataProvider=mainTabs;
				
			extraLoadData();			
			if (savedIndex == -1) getUserPermission();
			dgList.dataProvider = userPermission;			 
			setButtonState();
			//btnEdit.enabled=true;
			//btnAddNew.enabled = true;
		}			
		
		protected function getEditedUserPermission():Array{
			
			var arrCheckBoxStatus:Array;
			if((comboMainTabs.value)=='biodata')	arrCheckBoxStatus= getCheckBoxStatus(tabBiodata);
			else if((comboMainTabs.value)=='application')	arrCheckBoxStatus=getCheckBoxStatus(tabApplication);
			else if((comboMainTabs.value)=='service') arrCheckBoxStatus=getCheckBoxStatus(tabService);
			else if((comboMainTabs.value)=='patient') arrCheckBoxStatus=getCheckBoxStatus(tabPatient);
			else if((comboMainTabs.value)=='healthstaff') arrCheckBoxStatus=getCheckBoxStatus(tabHealthStaff);
			return arrCheckBoxStatus;	
		}
			
	//after editing is over and data is to be sent ot database
		protected function getCheckBoxStatus(currentTab:Array):Array{
			
			var strView:String='';
			var strDelete:String='';
			var strAdd:String='';
			var strEdit:String='';
			var arrCheckBoxStatus:Array = new Array;
						
			var len:int = userPermission.length;
				 
				// Alert.show(currentTab[0]);
			for (var i:int=0;i<len;i++){
				
				if (userPermission.getItemAt(i).viewStatus)					
					strView+=currentTab[i]+'_view=y,';				
				else	strView+=currentTab[i]+'_view=n,';	
				
				if (userPermission.getItemAt(i).addStatus)
					strAdd+=currentTab[i]+'_add=y,';					
				else
					strAdd+=currentTab[i]+'_add=n,';
					
				if (userPermission.getItemAt(i).deleteStatus)
					strDelete+=currentTab[i]+'_delete=y,';
				else	
					strDelete+=currentTab[i]+'_delete=n,';				
				
				if (userPermission.getItemAt(i).editStatus)
					strEdit+=currentTab[i]+'_edit=y,';					
				else
					strEdit+=currentTab[i]+'_edit=n,';				
			}
			//Alert.show(strView+"\n"+strAdd+"\n"+strEdit+"\n"+strDelete);
			arrCheckBoxStatus.push(strAdd);
			arrCheckBoxStatus.push(strDelete);
			arrCheckBoxStatus.push(strEdit);
			arrCheckBoxStatus.push(strView);
			return arrCheckBoxStatus;			
		}	
		
		protected function arrConcatUnique(...args):Array
		{
		   	 var retArr:Array = new Array();
		   	 for each (var arg:* in args)
		   	 {
		        if (arg is Array)
		        {
		            for each (var value:* in arg)
		            {
		                if (retArr.indexOf(value) == -1)
		                    retArr.push(value);
		            }
		        }
		    }
		    return retArr;
		}
		
		protected function fillUserPermission(currentMode:String):void{
			
			//trace("fillUserPermission: "+currentMode+", "+comboMainTabs.value);
			if((comboMainTabs.value)=='biodata')	setCurrentUserPermission(tabBiodata,currentMode);
			else if((comboMainTabs.value)=='application')	setCurrentUserPermission(tabApplication,currentMode);
			else if((comboMainTabs.value)=='service')	setCurrentUserPermission(tabService,currentMode);
			else if((comboMainTabs.value)=='patient')	setCurrentUserPermission(tabPatient,currentMode);
			else if((comboMainTabs.value)=='healthstaff')	setCurrentUserPermission(tabHealthStaff,currentMode);
			setButtonState();
		}
		
		protected function currentUserPermissionResult(event:ResultEvent):void {	
		
			//trace(event.result);
			var userInfoPermissionArray: Array = (event.result as String).split(","); 
        
        	for each ( var str:String in userInfoPermissionArray ){
        		var tempArr:Array=str.split('=');
        		userPermissionInfo[tempArr[0]] = tempArr[1];
        	}

			fillUserPermission(modeState); 		
			parentApplication.searching=false; 	
		}		
				
		protected function getUserPermission(getSettingOf:String='default'):void{		
			
				parentApplication.searching==true;
				
				txtNewName.text = comboNames.text;
				nameChanged = true;
				
				if (getSettingOf=='default') getSettingOf = 'group';
				//trace("get perm: "+comboNames.text+" "+nameChanged);
				var parameters:Object=new Object(); 
	        	requestCurrentPermission.useProxy	=	false;
	           	requestCurrentPermission.url		=	parentApplication.getPath()	+	"getGroupPermission.php";
	         	
				if(comboNames.value!=0)
				{
					parameters.groupID	=	comboNames.value;
					//Alert.show(parameters.groupID);
					requestCurrentPermission.send(parameters);  
				}  
				else
					fillUserPermission('Add New');	  		   
        }

		protected function commonSendParameters():Object {
			
			var parameters:Object=new Object();					
						
			parameters.action	=	modeState;
			parameters.name=txtNewName.text;	
			parameters.userID	= parentApplication.getCurrentUserID();	 
			
			var arrCheckBoxStatus:Array = getEditedUserPermission();
 			parameters.addStatus = arrCheckBoxStatus[0];
			parameters.deleteStatus = arrCheckBoxStatus[1];
 			parameters.editStatus = arrCheckBoxStatus[2];
 			parameters.viewStatus = arrCheckBoxStatus[3];
				
			//var allTabNames:Array = arrConcatUnique(tabBiodata,tabApplication,tabService,tabPatient,tabHealthStaff);				
			
			if (parentApplication.patientSystem) {
				allTabNames =arrConcatUnique(tabBiodata,tabPatient,tabHealthStaff);
				trace(allTabNames);
			}
			else {
				if (parentApplication.personnelWorldwideSystem)
					allTabNames =arrConcatUnique(tabBiodata,tabApplication,tabService);
				else
					allTabNames =arrConcatUnique(tabBiodata,tabService);
			}
						
			parameters.allTabNames=allTabNames.join(",");	
		//Alert.show('commonSendParameters() GroupPrivileges.as'+parameters.allTabNames);		
 			if(modeState=='Edit') {			
 				parameters.timestamp=userPermissionInfo['timestamp'];
 			} 
 		/*	else{				
				var allTabNames:Array = arrConcatUnique(tabBiodata,tabApplication,tabService,tabPatient,tabHealthStaff);				
				parameters.allTabNames=allTabNames.join(",");					
			} 		
			*/				

 	//	Alert.show(parameters.groupID);	 						
			return parameters;			
		}			
        
		override protected function setSendParameters():Object {
			
			var parameters:Object=commonSendParameters(); 			
									
			parameters.tableName = 'security_role_permission'; 
						
			if(modeState=='Edit') {			
 				parameters.groupID=comboNames.value;
 			}
 	//Alert.show(parameters.timestamp);

			return parameters;			
		}

        override protected function sendData():void{ 
        
        	super.sendData();
        	
			requestSaveAdmin.useProxy = false;
           	requestSaveAdmin.url	=	parentApplication.getPath()	+	"requestSaveAdmin.php";
           	requestSaveAdmin.send(setSendParameters()); 
           	//viewMode();    		      		
        }
		
		override protected  function setButtonState():void{
			
			super.setButtonState();
			enableDisableCheckBox=inputEnabled;//for enabling/disabling the checkboxs in the datagrid
			if(modeState=='Add New'){
				showAddUser=true;
				txtNewName.editable=true;	
				checkValid(null);	
			}
			else if(modeState=='Edit'){								
				if(comboNames.value!=0){					
					showAddUser=true;
					txtNewName.text=comboNames.text;	
					txtNewName.editable=true;					
				}		
				//if(comboNames.text=='admin') //make uneditable once entered for all users
					txtNewName.editable=false;	
				checkValid(null);						
			}				
			else
				showAddUser=false;
				
			if(comboNames.value==0)
				editEnabled=false;
			
			if (comboMainTabs.visible) this.focusManager.setFocus(comboMainTabs);	
			if (txtNewName.visible && txtNewName.editable) this.focusManager.setFocus(txtNewName);	
	  		if (comboNames.visible && (this.focusManager.getFocus() != comboNames)) this.focusManager.setFocus(comboNames);	
	  		if (this.focusManager.getFocus() != null)
	  			this.focusManager.getFocus().drawFocus(true);			
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			//if(showAddUser)	{//i.e. if 'Add New' mode					
				
				if(txtNewName.text==''){
					saveEnabled	= false;
				}
				if (inputObject == null) nameChanged = false;
				//trace("checkvalid:group ("+txtNewName.text+") "+nameChanged);
			//}			
		}

		override protected function setValues():void{	
			
			var currentID:int;
			
			if (savedIndex > -1) {
				//trace("Restore index: "+savedIndex);
				//dgList.selectedIndex = savedIndex;				
				for (var j:int = 0; j < comboNames.dataProvider.length; j++) {
					currentID = comboNames.dataProvider[j].data;
					//trace(currentID+" "+savedIndex);
					if(comboNames.dataProvider[j].data == savedIndex) {
						savedIndex = j;
						break;
					}	
				}
				//trace("Find index: "+savedIndex);
				comboNames.selectedIndex = savedIndex;
				savedIndex = -1;
				getUserPermission();
			}
			else { 
				if (savedIndex == -2) { //has been reset - find newest (highest id value)
					var maxID:int = 0;
					for (var i:int = 0; i < comboNames.dataProvider.length; i++) {
						currentID = comboNames.dataProvider[i].data;
						//trace(currentID+" "+maxID+" "+savedIndex);
						if(currentID > maxID) {
							savedIndex = i;
							maxID = currentID;
						}	
					}
					//trace("Find index: "+savedIndex);
					comboNames.selectedIndex = savedIndex;
					savedIndex = -1;
					getUserPermission();
				}
			}			
		}
		
		override protected function reload():void{
			parentApplication.loadNames(false,tableName); // reload names	
		}
		
		override protected function store():void{
			
			//savedIndex = dgList.selectedIndex; // store current index
			if (comboNames.selectedItem != null)
				savedIndex = comboNames.selectedItem.data; // store current index
			//trace("Store index: "+savedIndex);
		}
				
		protected function checkDBValues(requestFrom:String):void{ 	
		 	
	 		//trace("checkDBValues: "+nameChanged);
	 		//saveEnabled=true;
	 		if((txtNewName.text==comboNames.text)&&(modeState=='Edit')) return;
	 		
	 		if(!nameChanged) return;
	 		
	 		for each(var tempUser:String in comboNames.dataProvider) {     
			
			   if(tempUser==txtNewName.text){			   	 
			   	   saveEnabled=false;
			   	   txtNewName.text=''		   
			       Alert.show("This name is already used");	
			       return;		     			      
			    } 			    
			}
	//The above code checks the userNames in the comboBox's dataProvider.
	//If it is matched then no need to check the database. Checking database is needed rarely
	//i.e. Only when the comboBox does not have all the userNames  	
	 		 		
	 		 var parameters:Object=new Object();	
	 		 parameters.groupName=txtNewName.text;
	 		 parameters.requester=requestFrom;	 		
	 		 userRequestCheckDBUserName.useProxy = false;
           	 userRequestCheckDBUserName.url	=	parentApplication.getPath()	+	"requestCheckDb.php";
           	 if(txtNewName.text!='')
             	userRequestCheckDBUserName.send(parameters);           	 	
	 }

	  protected function handleDBUserResult(event:ResultEvent):void{
	 	
	 	if(userRequestCheckDBUserName.lastResult.userName=='duplicate'){	 			 		
	 		saveEnabled=false;	
	 		txtNewName.text='';
	 		Alert.show("This name is already used");
	 	}	 	
	 }	