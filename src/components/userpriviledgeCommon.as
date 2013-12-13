
		import mx.containers.FormItem;
		import mx.controls.Button;
		private var arrUIDEmail:Array = new Array();
		private var strEmailDomain:String;
		public var userGroup:ComboBoxNew;
		public var btnCurrentGroup:Button;
		public var txtUserPassword:TextInput;
		public var txtUserEmail:TextInput;
		public var ckResetPassword:CheckBox;
		public var lblPswMandatory:FormItem;
		public var lblPswBlack:FormItem;	
		
		public var userRequestCheckDBUserEmail:HTTPService;	

		private var currentGroup:int;
		private var allTabNames:Array;
		private var userNameID:int;
		private var titleWindowInstance:chooseMatch ;
				
		override protected function refresh():void{
		 	
		 	super.refresh();
		 	txtUserEmail.text='';	
		 	txtUserPassword.text='';	 	
		 	//if(showAddUser)//if 'Add New' mode
		 		//saveEnabled=false;		 		
		 	btnCurrentGroup.label="";							
		}
		
		override protected  function setButtonState():void{
			
			super.setButtonState();
			if(modeState=='Add New'){				
				lblPswMandatory.visible=true;
				lblPswBlack.visible=false;
			}
			else if(modeState=='Edit'){	
				lblPswMandatory.visible=false;
				lblPswBlack.visible=true;
			}
			else{				
				lblPswMandatory.visible=false;
				lblPswBlack.visible=false;
			}
		}
		
		override protected function viewMode(fromCancel:Boolean=true):void{
			super.viewMode(fromCancel);
			txtUserPassword.text='';
		}

	    override protected function changeCombo():void {
	    	
	    	getUserPermission('user');
   		}
   		
		override protected function extraLoadData():void {
			
			userGroup.dataProvider=parentApplication.getUserRequestNameResult().groupnames.groupname;
			userGroup.selectedIndex=0;
			comboNames.dataProvider=parentApplication.getUserRequestNameResult().usernames.username;
			comboNames.selectedIndex=0;
			currentGroup=0;
			strEmailDomain=parentApplication.getUserRequestNameResult().site.email_domain;//'inf.org'; // for now
			//array of userEmail indexed by userID
			//arrUIDEmail =	parentApplication.buildAssociativeArray(parentApplication.getUserRequestNameResult().useremails.useremail)
			//Alert.show(comboNames.value+' '+arrUIDEmail[comboNames.value]);
			//txtUserEmail.text=arrUIDEmail[comboNames.value];				
		}			
		
		override protected function setCurrentUserPermission(tabArray:Array,currentMode:String):void{

			userPermission.removeAll();//clear the old permissions
			
			for each ( var tab:String in tabArray ){ 
							
				var info: userPermissionObject = new userPermissionObject();					
				info.tabName = tab;

				if(userPermissionInfo[tab+'_add']=='y') info.addStatus =true;
				if(userPermissionInfo[tab+'_delete']=='y') info.deleteStatus =true;
				if(userPermissionInfo[tab+'_edit']=='y') info.editStatus =true;
				if(userPermissionInfo[tab+'_view']=='y') info.viewStatus =true;
							
				userPermission.addItem(info);												
			}      	
		}	
			
		override protected function fillUserPermission(currentMode:String):void{
			
			super.fillUserPermission(currentMode);
			if (currentMode != "Add New")
				btnCurrentGroup.label=userPermissionInfo['roleName'];
		}

		private function updateEmail():void {

			//trace("update email: "+txtUserEmail.text+" "+nameChanged+" "+modeState);
			//txtUserEmail.text=arrUIDEmail[comboNames.value];			
			if (nameChanged) {
				txtUserEmail.text=txtNewName.text+'@'+strEmailDomain;
			}	
			else {
				if (modeState == "View") {
					txtUserEmail.text=userPermissionInfo['userEmail'];//arrUIDEmail[comboNames.value];
				}
			}
			checkValid(null);
		}
				
		override protected function currentUserPermissionResult(event:ResultEvent):void {	
			
			super.currentUserPermissionResult(event);	 				
			updateEmail();
		}		
				
		override protected function getUserPermission(getSettingOf:String='default'):void{	
				
			parentApplication.searching=true;
			
			if (getSettingOf=='default') getSettingOf = 'user';
			
			//trace(getSettingOf+", "+currentGroup+", "+comboNames.value);
			if (parentApplication.patientSystem) {
				allTabNames =arrConcatUnique(tabBiodata,tabPatient,tabHealthStaff);
			}
			else {
				allTabNames =arrConcatUnique(tabBiodata,tabApplication,tabService);
			}
			
			//allTabNames = arrConcatUnique(tabBiodata,tabApplication,tabService,tabPatient,tabHealthStaff);
			var parameters:Object=new Object(); 
        	requestCurrentPermission.useProxy	=	false;
           	requestCurrentPermission.url			=	parentApplication.getPath()	+	"getUserPermission.php";
           	
			if(getSettingOf=='user') {
				parameters.userID	=	comboNames.value;
				//parameters.changeGroup	=	'no';					  
			}
			else if((getSettingOf=='group')&&(currentGroup!=0) ){				
				
				parameters.groupID	=	currentGroup;				
				
				if(modeState=="Edit"){
				//	parameters.changeGroup	=	'yes';
					parameters.userID	=	comboNames.value;
					parameters.userName =	comboNames.text;
				
				} 
				parameters.allTabNames	=	allTabNames.join(",");//string delimited by comma
			}
			
			parameters.modeState=	modeState;
			parameters.permissionType=	getSettingOf;			
      		requestCurrentPermission.send(parameters);    
        }        
        
        protected function callAlert():void{
        	
        	var alert:Alert = Alert.show("Change group","Current group settings will be lost!",Alert.OK|Alert.CANCEL,this,onAlertClose);
        }
        
	    protected function onAlertClose(evt:CloseEvent):void {	    	
	    	
            switch(evt.detail) {
               case Alert.OK:  
               		var txtTempUser:String = txtNewName.text;//refresh() called in the below line will clear txtNewName but we need to retain it after refresh()   
               		var txtTempEmail:String= txtUserEmail.text;
               		var txtTempPsw:String  = txtUserPassword.text;
               		//trace("before change group: "+txtUserEmail.text+" ("+txtTempEmail+")");
               		refresh(); 
               		txtNewName.text=txtTempUser;
               		txtUserEmail.text=txtTempEmail;
               		txtUserPassword.text=txtTempPsw;
               		currentGroup=int(userGroup.value);        		      		 
              		getUserPermission('group');  
              		btnCurrentGroup.label=userGroup.text;         			           			
           			userGroup.selectedIndex=0;
                	break;
                	
               case Alert.CANCEL:
                   	//userGroup.selectedIndex=0;  
                   	checkValid(null);             
                   	break;               
            }
       }			
		
		override protected function setSendParameters():Object {
			
			var parameters:Object=commonSendParameters();			
 								
			parameters.tableName = 'security_user_permission'; 			
 			parameters.userEmail=txtUserEmail.text;			
			parameters.password=txtUserPassword.text;
 			if(modeState=="Edit"){				
				parameters.editedUserID	=	comboNames.value;
			//	parameters.password='';		
			}									
 		
 			parameters.userType=btnCurrentGroup.label; 			
 			 
 			txtUserPassword.text='';
 			parameters.groupID=currentGroup;
 			parameters.userNameID=userNameID;			
 			ckResetPassword.selected=false;
 			
 	//	Alert.show(parameters.groupID);	 						
			return parameters;			
		}			

        override protected function sendData():void{ 	
	 		 		
	 		var parameters:Object=new Object();	
	 		
	 		parameters.userEmail=txtUserEmail.text;
	 		if(modeState=="Edit"){				
				parameters.editedUserID	=	comboNames.value;
				parameters.userName=comboNames.text;
			//	parameters.password='';		
			}
			else {
	 		 	parameters.editedUserID=0;
	 		 	parameters.userName=txtNewName.text;
	 		}
	 		parameters.requester='checkUserEmail';
	 		 
	 		userRequestCheckDBUserEmail.useProxy = false;
           	userRequestCheckDBUserEmail.url	=	parentApplication.getPath()	+	"requestCheckDb.php";
          	userRequestCheckDBUserEmail.send(parameters);	      		
        }
        		
		public function generateRandomString(newLength:uint = 6, userAlphabet:String = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"):void{

	    	//generates random string as password and set to password field
      		var alphabet:Array = userAlphabet.split("");
      		var alphabetLength:int = alphabet.length;
      		var randomLetters:String = "";
      		
      		for (var i:uint = 0; i < newLength; i++){
        		randomLetters += alphabet[int(Math.floor(Math.random() * alphabetLength))];
      		}
      		txtUserPassword.text=randomLetters;
  //    Alert.show(randomLetters);
    	}

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);

			//if(showAddUser)	{//i.e. if 'Add New' mode	
				if(btnCurrentGroup.label==''){
					saveEnabled	= false;
				}
				if(txtUserEmail.text==''){
					saveEnabled	= false;
				}
				if(parentApplication.CheckValidEmail(txtUserEmail)==false){
					saveEnabled	= false;	
				}		
				if((txtUserPassword.text=='')&&(modeState=='Add New'))	
					saveEnabled	= false;				
			//}			
		}
				
	  	override protected function handleDBUserResult(event:ResultEvent):void{
	 	
	 		super.handleDBUserResult(event);
	 	
	 		if(userRequestCheckDBUserName.lastResult.userName!='duplicate'){	 		
	 			if(modeState=='Add New'){
	 			//generate password and set to txtUserPassword
	 				generateRandomString();
	 			}
	 			updateEmail();
	 		}	 		
	 	}
	 	
	 	public function selectUserID(nameID:int):void {
	 		userNameID = nameID;
	 		super.sendData();
	 	}
	 	
	 	protected function handleDBUserEmailResult(event:ResultEvent):void{
	 	
	 		var returnMsg:String = userRequestCheckDBUserEmail.lastResult.message;
	 		
	 		if(returnMsg=='match'){	 			
	 			showMatchesWindow();
	 		}
	 		else {
	 			if(returnMsg=='ok'){
	 				selectUserID(0);
	 			}
	 			else {
	 				Alert.show(returnMsg);
	 			}
	 		}	
	 	}
	 	
	 	private function showMatchesWindow():void {
 				
	    	titleWindowInstance =  chooseMatch(PopUpManager.createPopUp(this,  chooseMatch, false));     //instantiate and show the title window
  			//PopUpManager.centerPopUp(titleWindowInstance);
  			titleWindowInstance.title = "Matching records - choose one";
  		 			
  			 //built-in property  
  			titleWindowInstance.mainApp = this; //Reference to the main app scope
  			titleWindowInstance.dg2.dataProvider = userRequestCheckDBUserEmail.lastResult.matches.match;
  			titleWindowInstance.dg2.columns[0].visible = false;
 		}