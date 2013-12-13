
	public var requestCreateBackup:HTTPService;
	public var radioBackupType:RadioButtonGroup;
	public var radioBackupData:RadioButton;
	public var radioBackupStructure:RadioButton;
	public var radioBackupBoth:RadioButton;
	
	public function backupResult(event:ResultEvent):void{
	 	
	 	var fileName:String=requestCreateBackup.lastResult.fileName;
	 	
	 	parentApplication.searching = false;
	 	
	 	status_txt.text	=	requestCreateBackup.lastResult.errors.error;
		if(requestCreateBackup.lastResult.errors.status=='success') {
			status_txt.enabled=true;
			navigateToURL(new URLRequest(parentApplication.downloadFile(fileName)));	 	
		}
		else
			status_txt.enabled=false;
	}
	
	public function createBackup():void{
		
		var strBackupType:String='BackupDataStructure';
		
		parentApplication.searching = true;
				
		if(radioBackupData.selected)
			strBackupType='BackupData';
		else if(radioBackupStructure.selected)
			strBackupType='BackupStructure';
		else if(radioBackupBoth.selected)
			strBackupType='BackupDataStructure';
			
		var parameters:Object=new Object(); 
		parameters.backupType			=	strBackupType;
		parameters.userID	= parentApplication.getCurrentUserID();
		requestCreateBackup.useProxy	=	false;
		requestCreateBackup.url			=	parentApplication.getPath()	+	"createBackup.php";
		requestCreateBackup.send(parameters);    		       	
	}
