
	public var requestSyncUsers:HTTPService;
	public var txtUserLogin:TextInput;
	public var txtUserPassword:TextInput;
	
	public function syncResult(event:ResultEvent):void{
	 	
	 	parentApplication.searching = false;
	 	
	 	status_txt.text	=	requestSyncUsers.lastResult.errors.error;
		if(requestSyncUsers.lastResult.errors.status=='success') {
			status_txt.enabled=true;	 	
		}
		else
			status_txt.enabled=false;
	}

	override protected function checkValid(inputObject:Object):void{
		
		inputEnabled = true;
		super.checkValid(inputObject);				
				
		if(txtUserLogin.text==''){
			saveEnabled	= false;
		}
		if(txtUserPassword.text==''){
			saveEnabled	= false;
		}			
	}	

	public function updateMysqlUsers():void{
		
		parentApplication.searching = true;
			
		var parameters:Object=new Object(); 
		parameters.userLogin =	txtUserLogin.text;
		parameters.userPassword =	txtUserPassword.text;
		
		txtUserPassword.text = ''; //clear username/password fields before search
		txtUserLogin.text = '';
		checkValid(null);
		
		requestSyncUsers.useProxy	=	false;
		requestSyncUsers.url			=	parentApplication.getPath()	+	"syncPermission.php";
		requestSyncUsers.send(parameters);    		       	
	}
