
		public var comboVisaPost:ComboBoxNew;
		public var comboVisaPostHolder:ComboBoxNew;
		public var textVisaPost:TextInput;
		public var comboIssueCountry:ComboBoxNew;
		public var comboStatus:ComboBoxNew;
		public var comboType:ComboBoxNew;
		public var comboSubType:ComboBoxNew;
		public var dateIssueDate:DateField;
		public var dateExpiryDate:DateField;
		public var dateEntryDate:DateField;
		public var textNumber:TextInput;
		public var textIssueCity:TextInput;
		public var scanHolder:Image;
		public var textScanName:TextInput;
		public var textScan:TextInput;
		public var getPhoto:HTTPService;
		[Bindable] protected var visaPostEnabled:Boolean=false;
		
		/* comboVisaName.dataProvider set in inf.mxml
		//search.php(getgetVisaName())
		//inf.mxml(defaultResult()) 
		*/
		[Bindable] private var today:Date = new Date();
	    [Bindable] private var expiryDate:Date = new Date(today.getFullYear()+1,today.getMonth(),today.getDate()); 

		/* Photo uploading script*/
		[Bindable] private var fileName:String;
		private var file:FileReference;
		private var objectId:Object;
    	private var imgFieldID:Object;
	    [Bindable] private var scanName:String="";
	    	    
		protected function selectFile(currentObjectId:Object,imageFieldID:Object):void{
		
			imgFieldID	=	imageFieldID;
			objectId	=	currentObjectId;
			//Alert.show(currentObjectId.toString());		
			if(imgFieldID.text	!=	""){
				fileName	=	imgFieldID.text;	
			}
			else{//if the file is browsed for the first time in this session
				var currentDate:Date	=	new Date();
				fileName	=	currentDate.getTime().toString();
				imgFieldID.text	=	fileName;
			}

			var imageTypes:FileFilter = new FileFilter("Images and PDF (*.jpg, *.jpeg, *.gif, *.png, *.pdf)", "*.jpg; *.jpeg; *.gif; *.png; *.pdf");
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
			objectId.text = file.name;
		
			//status_txt.text = 'upload file: '+ file.name  + '\n';
		
			var request:URLRequest = new URLRequest();

    		request.url = parentApplication.getUploadURL(fileName);
    		//request.url = "../files/upload.php?objectID=1";
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
			
			if(result.status	==	"Error"){
				status_txt.enabled=false;//display message in normal Error indication color [red] if not enabled
				saveEnabled	=	false;
			} 
			else
				status_txt.enabled=true;//display message in normal color if enabled
		
			//scanName	=	imgFieldID.text	+	'.'	+	result.extention;
			scanName	=	result.imageName;
			if (parentApplication.getFileExtension(scanName) == 'pdf') {
				scanHolder.source='';
				textScan.text="PDF preview not available";
				textScan.visible = true;
			}
			else {
				scanHolder.source=downloadImage(scanName,'thumb');
				textScan.text='';
				textScan.visible = false;
			}		
	
			checkValid(null);			
		}
	
		private function uploadComplete(event:Event):void{
		
			//	status_txt.text += 'Upload complete\n';	
			checkValid(null);	
		}

	    override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Entry Date", data:dateEntryDate},{label:"Expiry Date", data:dateExpiryDate},{label:"Issue Date", data:dateIssueDate}]);          	           	
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		override protected function loadNonMandatoryFields():void{
			
         	//nonMandatoryComboFields = new ArrayCollection([{label:"Visa Post", data:comboVisaPost}]);       		
        }
	    
	     override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({ label: "visa_post",data:"visa_history"});
        	listSearchBy.push({ label: "postholder",data:"visa_history"});
        	listSearchBy.push({ label: "number",data:"visa_history"});
	 		listSearchBy.push({ label: "issue_date",data:"visa_history"});
        	listSearchBy.push({ label: "expiry_date",data:"visa_history"});
        	listSearchBy.push({ label: "entry_date",data:"visa_history"});
        	listSearchBy.push({ label: "issue_city",data:"visa_history"});
        	listSearchBy.push({ label: "issue_country",data:"visa_history"});
        	listSearchBy.push({ label: "visa_status",data:"visa_history"});
        	listSearchBy.push({ label: "type",data:"visa_history"});
        	listSearchBy.push({ label: "subtype",data:"visa_history"});       
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "visa_post",data:"visa_history"});
        	listSearchWhom.push({ fields: "postholder",data:"visa_history"});
        	listSearchWhom.push({ fields: "number",data:"visa_history"});
			listSearchWhom.push({ fields: "issue_date",data:"visa_history"});
			listSearchWhom.push({ fields: "expiry_date",data:"visa_history"});
			listSearchWhom.push({ fields: "entry_date",data:"visa_history"});
			listSearchWhom.push({ fields: "issue_city",data:"visa_history"});
			listSearchWhom.push({ fields: "issue_country",data:"visa_history"});
			listSearchWhom.push({ fields: "visa_status",data:"visa_history"});
			listSearchWhom.push({ fields: "type",data:"visa_history"});
			listSearchWhom.push({ fields: "subtype",data:"visa_history"});	           	
        } 
        
        override protected function checkValid(inputObject:Object):void{

			super.checkValid(inputObject);
			
			if((textNumber.text	==	"")||(textIssueCity.text	==	"")){			
				saveEnabled	= false;
			}
			if((dateIssueDate.text == "") || (dateExpiryDate.text == "")|| (dateEntryDate.text == "")){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateIssueDate, dateExpiryDate) == 1) {
				saveEnabled = false;
			}
			if(DateUtils.compareDateFieldDates(dateEntryDate, dateExpiryDate) == 1) {
				saveEnabled = false;
			}
			if(comboIssueCountry.selectedIndex == 0){
				saveEnabled = false;
			}
			if(comboStatus.selectedIndex == 0){
				saveEnabled = false;
			}
			if(comboType.selectedIndex == 0){
				saveEnabled = false;
			}
			if(comboSubType.selectedIndex == 0){
				saveEnabled = false;
			}
			visaPostEnabled = (inputEnabled && (comboType.text=='Official'));
			if (visaPostEnabled) {
				if (comboVisaPost.selectedIndex == 0){
					saveEnabled = false;
				}
			}
			else {
				if (inputEnabled) {
					comboVisaPost.selectedIndex = 0;
					comboVisaPostHolder.selectedItem = 'No';
				}
			}
		}		
	    
	    override protected function loadData(current:Boolean=false):void{
		
			super.loadData();			
			comboIssueCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			comboStatus.dataProvider=parentApplication.getUserRequestTypeResult().visastatus;
			comboType.dataProvider=parentApplication.getUserRequestTypeResult().visatype;
			comboSubType.dataProvider=parentApplication.getUserRequestTypeResult().visasubtype;	
       		comboVisaPost.dataProvider	=  parentApplication.getUserRequestNameResult().visaposts.visapost;//getUserRequestNameResult().visaIdTitle.idTitle;
       		comboVisaPostHolder.dataProvider=parentApplication.getUserRequestTypeResult().postholder;
			chkShowAll.visible = false;	
        } 
        
        override protected function setSendParameters():Object {
	
			var parameters:Object=super.setSendParameters();					
			
			parameters.visaNumber	=	textNumber.text;
        	parameters.countryID	=	comboIssueCountry.value;
        	parameters.city			=	textIssueCity.text;
        	parameters.issueDate	=	DateUtils.dateFieldToString(dateIssueDate,parentApplication.dateFormat);
        	parameters.expiryDate	=	DateUtils.dateFieldToString(dateExpiryDate,parentApplication.dateFormat);
        	parameters.entryDate	=	DateUtils.dateFieldToString(dateEntryDate,parentApplication.dateFormat);
        	parameters.status		=	comboStatus.text; 
        	parameters.type			=	comboType.text; 
        	parameters.subtype		=	comboSubType.text;
        	if (dgList.selectedItem == null) {
        		parameters.visaID		=	0;//adding completely new visa
        	} 
        	else {
        		parameters.visaID		=	dgList.selectedItem.visa_id;
        	}
        	parameters.postID		=	comboVisaPost.value;
        	parameters.postHolder	=	comboVisaPostHolder.text; 
        	parameters.passportID	=	parentApplication.getPassportID();
        	parameters.visaPost		=	comboVisaPost.value;
        	parameters.postholder	=	comboVisaPostHolder.text;
        	
        	parameters.scanName		=	scanName;	
        	if (scanName != "") {	
 				parameters.scanLink		=	downloadImage(scanName); 
 			}
 			else 
 				parameters.scanLink = "";
 				
        	if(modeState=='Edit'){
       			parameters.timestamp =	dgList.selectedItem.visa_history_timestamp;       		
  			}
				
 			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;			
		}

		override protected function defaultResult(event:ResultEvent):void {	
			
			if(userRequestSaveTab.lastResult.errors.status=='success') {
				parentApplication.loadNames(false,"visapost");
			}
			
			super.defaultResult(event);
		} 
        
        override public function refreshData():void{

			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboIssueCountry,comboIssueCountry.text);			
			comboIssueCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboIssueCountry,nameIndex);
			nameIndex = parentApplication.getComboData(comboVisaPost,comboVisaPost.text);			
			comboVisaPost.dataProvider	=  parentApplication..getUserRequestNameResult().visaposts.visapost;//getUserRequestNameResult().visaIdTitle.idTitle;
			parentApplication.setComboData(comboVisaPost,nameIndex);
			/*
			if (comboVisaPost.selectedIndex == -1) {
        		textVisaPost.text = "";
        	}
        	else {
        		textVisaPost.text = comboVisaPost.text;
        	}
        	*/
        	scanName	=	"";
        	
			if (modeState != "View") {
				this.focusManager.setFocus(comboType);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}			
		}	
		
		override protected function refresh():void{
		
			super.refresh();
			
			comboVisaPost.selectedIndex = 0;
			comboVisaPost.dataProvider	=  parentApplication..getUserRequestNameResult().activevisaposts.visapost;//getUserRequestNameResult().visaIdTitle.idTitle;
			
			parentApplication.setComboData(comboVisaPost,parentApplication.getVisaPostID());
			/*
			if (comboVisaPost.selectedIndex > 0) {
        		textVisaPost.text = comboVisaPost.text;
        	}
        	else {
        		textVisaPost.text = "";
        	}
        	*/
			textNumber.text	=	"";							
			textIssueCity.text	=	"";
			dateIssueDate.text	=	DateUtils.dateToString(today,dateIssueDate.formatString);
			dateExpiryDate.text	=	DateUtils.dateToString(expiryDate,dateExpiryDate.formatString);	
			dateEntryDate.text	=	"";		
			comboStatus.selectedIndex	=	0;
			comboType.selectedItem	=	"Official";
			comboSubType.selectedIndex	=	0;
			comboIssueCountry.selectedIndex =	0;
			comboVisaPostHolder.selectedIndex =	0;
			
			textScan.text	=	"";
			textScan.visible = false;	
			textScanName.text	=	"";			
        	scanHolder.source="";
        	visaPostEnabled = false;
		}
		
		override protected function setValues():void{
			
			super.setValues();
			
			//comboVisaName.dataProvider		=	parentApplication.getVisaNames();
			textNumber.text		=	dgList.selectedItem.number;
        	textIssueCity.text	=	dgList.selectedItem.issue_city;
        	dateIssueDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.issue_date,dateIssueDate,parentApplication.dateFormat);
        	dateExpiryDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.expiry_date,dateExpiryDate,parentApplication.dateFormat);       	
        	dateEntryDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.entry_date,dateEntryDate,parentApplication.dateFormat);       	
        	comboStatus.selectedItem	=	dgList.selectedItem.status;
        	comboType.selectedItem	=	dgList.selectedItem.type;
        	comboSubType.selectedItem	=	dgList.selectedItem.subtype;
        	comboVisaPostHolder.selectedItem = dgList.selectedItem.postholder;
        	parentApplication.setComboData(comboIssueCountry,dgList.selectedItem.issue_country_id);
        	parentApplication.setComboData(comboVisaPost,dgList.selectedItem.post_id);
        	parentApplication.setVisaPostID(dgList.selectedItem.post_id);
        	/*
        	if (comboVisaPost.selectedIndex >0) {
        		textVisaPost.text = comboVisaPost.text;
        	}
        	else {
        		textVisaPost.text = "";
        	}
        	*/
        	scanName = parentApplication.getLinkFileName(dgList.selectedItem.scan_link);
        	if (parentApplication.getFileExtension(scanName) == 'pdf') {
				textScan.text="PDF preview not available";
				textScan.visible = true;
			}
			else {
				textScan.text='';
				textScan.visible = false;
			}
        	scanHolder.source	=	downloadImage(dgList.selectedItem.scan_link,'thumb');
		}
		
		override protected function setButtonState():void{
			
			super.setButtonState();
/*
			if((modeState=='Add New') && (comboVisaName.text == ''))
				comboVisaName.enabled=true;
			else*/
				//comboVisaName.enabled=false;
			//addEnabled = addEnabled && (comboVisaName.text != '');
			//if (textVisaPost.text == "") editEnabled = false;
			if (parentApplication.getPassportID() <= 0) addEnabled = false;
			//editEnabled = editEnabled && (comboVisaName.text != '');
		}
		
		protected function displayPopUpCountry(strTitle:String,editMode:Boolean=false):void{		
		
			displayCountryPopUp(strTitle,comboIssueCountry,editMode);
		}
		
		protected function downloadImage(fileLink:String,type:String='image'):String{			
			
			if (parentApplication.getFileExtension(scanName) == 'pdf') {
				type='doc';	
			} 
			return  parentApplication.downloadFile(fileLink,type);
		}
		
		protected function displayPopUpPost(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboVisaPost.selectedIndex > 0) || (!editMode)){
				var pop1:popUpPost = popUpPost(PopUpManager.createPopUp(this, popUpPost, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					// The combo text is made up of the post name (code) followed by ': ' and then description
					// so we need to retrieve just the first part
					var postString:String = comboVisaPost.text;
					var postArray:Array=postString.split(": ");
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('visapost',true,postArray[0],true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('visapost',true,postArray[0],false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('visapost',true);
				}
  			}
		}