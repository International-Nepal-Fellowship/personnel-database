// ActionScript file

	public var txtDocumentPath:TextInput;
	public var txtDocumentNotes:TextInput;
	public var btnDocumentBrowse:Button;
	//private var requester:String;
	
	[Bindable]public var setDgListVisible:Boolean=false;
	
	public var requestCurrentDoc:HTTPService;
	public var requestSaveDoc:HTTPService;
	
	/* file-image uploading script*/
	private var file:FileReference;
	[Bindable] private var fileName:String="";
	private var uniqueString:String="";   	
    
    protected function selectFile():void {			
		// if(dgList.selectedItem)
			
		if(uniqueString	==	""){//generate unique string for fileName 		
			var currentDate:Date	=	new Date();
			uniqueString	=	currentDate.getTime().toString();
		}
			
		//	Alert.show(parentApplication.getCurrentID());
			
		var imageTypes:FileFilter = new FileFilter("Documents (*.doc, *.pdf, *.rtf, *.docx, *.csv, *.xls, *.xlsx)", "*.doc; *.pdf; *.rtf, *.docx; *.csv; *.xls; *.xlsx");
	    var allTypes:Array = new Array(imageTypes);
	        
		file = new FileReference();
		file.addEventListener(Event.SELECT, fileSelected);
		file.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, uploadDataComplete);
		file.addEventListener(Event.COMPLETE, uploadComplete);
		file.addEventListener(IOErrorEvent.IO_ERROR, handleError);
			
		try{
			file.browse(allTypes);
		}
		catch (error:Error){
	   		Alert.show("Unable to browse for files.");
		}
	}
	
	private function handleError(event:IOErrorEvent):void{
			
		status_txt.text = 'ERROR: ' + event.text + '\n';
	}
		
	private function fileSelected(event:Event):void{
			
		file = FileReference(event.target);
		txtDocumentPath.text =file.name;
		//txtDocumentPath.text = parentApplication.getCurrentID();
		//status_txt.text = 'upload file: '+ file.name  + '\n';
		
		var request:URLRequest = new URLRequest();
	   	//request.url = "../files/upload.php?imageName	=	"	+	uniqueString;
	   	request.url = parentApplication.getUploadURL(file.name,'doc');
		//Alert.show(uniqueString);
		parentApplication.searching = true;
		file.upload(request);			
	}
		
	private function uploadDataComplete(event:DataEvent):void{
		
		var result:XML = new XML(event.data);
		parentApplication.searching = false;
		status_txt.text += 'Upload Data Complete\n';
		//status_txt.text += 'RESULT: ' + result.toString()  + '\n'
		//status_txt.text += 'STATUS: ' + result.status + '\n';
		
		status_txt.text = result.message;
			if(result.status=='OK')
				status_txt.enabled=true;//display message in normal color if enabled
			else{
				status_txt.enabled=false;//display message in normal Error indication color [red] if not enabled
				saveEnabled	=	false;
			}	
		if(result.status	==	"Error") saveEnabled	=	false;
		//fileName	=	uniqueString	+	'.'	+	result.extention;
		fileName	=	result.imageName;
			
		//status_txt.text=fileName;	
		
		checkValid(null);			
	}
	
	private function uploadComplete(event:Event):void{
		
		//	status_txt.text += 'Upload complete\n';	
		checkValid(null);	
	}
				
	protected function currentDocResult(event:ResultEvent):void {	
							
		dgList.dataProvider = requestCurrentDoc.lastResult.doc;			
		/*
		if(((dgList.dataProvider).length)>0){
			setDgListVisible=true;
			dgList.selectedIndex=0;			
		}			
		else
			setDgListVisible=false;	
		*/
		init(tableName,true,(((dgList.dataProvider).length)>0));
		this.title = modeState+" "+this.title;
	}
				
	override protected function setSendParameters():Object {

		var parameters:Object=super.setSendParameters();
	//	parameters.requester=	requester;
	//Alert.show("table: "+tableName);
		parameters.docID	=	selectQueryID;	
		parameters.docField	=	selectQueryField;					
 		parameters.notes 	= 	txtDocumentNotes.text;
 		if (fileName != "") {	
 			parameters.link		=	downloadDoc(fileName);//txtDocumentPath.text;
 		}
 		else 
 			parameters.link = "";
		return parameters;			
	}
	
	override protected function sendData():void{ // complete override (don't call super)
			
		requestSaveDoc.useProxy = false;
       	requestSaveDoc.url	=	parentApplication.getPath()	+	"requestSaveDoc.php";
       	requestSaveDoc.send(setSendParameters());       		      		
    }

	override protected function defaultResult(event:ResultEvent):void {	
			
		super.defaultResult(event);
		status_txt.text=requestSaveDoc.lastResult.errors.error;
		if(requestSaveDoc.lastResult.errors.status=='success')
			status_txt.enabled=true;
		else
			status_txt.enabled=false;
			
		if (popupMode) {
			if (status_txt.text.indexOf("Error") == -1){
				removeMe(); // close window if successful
			}
		}
		else
			viewMode(false);
	}
		
	override public function pre_init(currentTable:String, popup:Boolean=false, mode:String="View", queryID:int=0, queryField:String=""):void{

		super.pre_init(currentTable,popup,mode,queryID,queryField);
		
		//if (mode != "Add New") {
			var parameters:Object=new Object();
			parameters.nameID	=	parentApplication.getCurrentID();      
			parameters.docID	=	selectQueryID; 
			parameters.docField	=	selectQueryField;  
			parameters.tableName	= 	tableName;
			parameters.currentUserID =  parentApplication.getCurrentUserID();		
       		requestCurrentDoc.useProxy	=	false;
        	trace("doc id: "+parameters.docID+" doc field: "+parameters.docField+" doc table: "+parameters.tablename);
       		requestCurrentDoc.url		=	parentApplication.getPath()	+	"getUserDoc.php";
   			requestCurrentDoc.send(parameters);
  		//}     
   }		

	override public function refreshData():void{
			
		super.refreshData();
		uniqueString = "";
		fileName	 =	"";		
		if (modeState != "View") {
			this.focusManager.setFocus(txtDocumentNotes);
			if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
		}
	}
			
	override protected function refresh():void{
					
		super.refresh();
		txtDocumentNotes.text	=	"";	
		txtDocumentPath.text	=	"";			
	}

	override protected function setValues():void{
					
		super.setValues();		
		txtDocumentNotes.text	=	dgList.selectedItem.notes;	
		txtDocumentPath.text	=	dgList.selectedItem.filename;
		fileName = dgList.selectedItem.filename;
		//if (dgList.selectedItem.link != null) txtDocumentPath.text	=	getFileName(dgList.selectedItem.link);
	}
	
	protected function getFileName(filePath:String=''):String{
		if(filePath=='') return 'null';		
		var words:Array = filePath.split("/");
		return (words.pop()).toString();
	}
	
	protected function downloadDoc(fileLink:String,type:String='doc'):String{			
			
		return  parentApplication.downloadFile(fileLink,type);
	}