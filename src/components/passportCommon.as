// common code for tabPassportClass and popupPassportClass

	import mx.collections.ArrayCollection;  
		public var photoHolder:Image;
		public var scanHolder:Image;
		public var comboIssueCountry:ComboBoxNew;
		public var comboPassportCountry:ComboBoxNew;
		//public var comboVisa:ComboBoxNew;
		public var dateIssueDate:DateField;
		public var dateExpiryDate:DateField;
		public var textNumber:TextInput;
		public var textIssueCity:TextInput;
		public var textPhotoName:TextInput;
		public var textScanName:TextInput;
		public var textPhoto:TextInput;
		public var textScan:TextInput;
		public var getPhoto:HTTPService;
		
		[Bindable] private var today:Date = new Date();
	    [Bindable] private var expiryDate:Date = new Date(today.getFullYear()+10,today.getMonth(),today.getDate()); 
		
		/* Photo uploading script*/
		[Bindable] private var fileName:String;
		private var file:FileReference;
		private var objectId:Object;
    	private var imgFieldID:Object;
	    [Bindable] private var photoName:String="";
	    [Bindable] private var scanName:String="";
          
       // load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Expiry Date", data:dateExpiryDate},{label:"Issue Date", data:dateIssueDate}]);          	           	
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		
		override protected function loadNonMandatoryFields():void{
			
         	//nonMandatoryComboFields = new ArrayCollection([{label:"Visa", data:comboVisa}]);       		
        }
		
        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({ label: "number",data:"passport"});
	 		listSearchBy.push({ label: "issue_date",data:"passport"});
        	listSearchBy.push({ label: "expiry_date",data:"passport"});
        	listSearchBy.push({ label: "issue_city",data:"passport"});
        	listSearchBy.push({ label: "issue_country",data:"passport"});
        	listSearchBy.push({ label: "passport_country",data:"passport"});
        	//listSearchBy.push({ label: "visa_post",data:"passport"});        
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "number",data:"passport"});
			listSearchWhom.push({ fields: "issue_date",data:"passport"});
			listSearchWhom.push({ fields: "expiry_date",data:"passport"});
			listSearchWhom.push({ fields: "issue_city",data:"passport"});
			listSearchWhom.push({ fields: "issue_country",data:"passport"});
			listSearchWhom.push({ fields: "passport_country",data:"passport"});	   
        	//listSearchWhom.push({ fields: "visa_post",data:"passport"});
        }
        		
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

			var filterLabel:String;
			var filterTypes:String;
			
			if (objectId == textPhoto) {
				filterLabel = "Images (*.jpg, *.jpeg, *.gif, *.png)";
				filterTypes = "*.jpg; *.jpeg; *.gif; *.png";
			}
			else {
				filterLabel = "Images and PDF (*.jpg, *.jpeg, *.gif, *.png, *.pdf)";
				filterTypes = "*.jpg; *.jpeg; *.gif; *.png; *.pdf";
			}
			var imageTypes:FileFilter = new FileFilter(filterLabel, filterTypes);
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
		
			if(imgFieldID.id	==	"textPhotoName"){
		
				//photoName	=	imgFieldID.text	+	'.'	+	result.extention;	
				photoName	=	result.imageName;		
				photoHolder.source=downloadImage(photoName,'thumb'); 
			
				if(textScanName.text==''){//if scan has not been uploaded in this transaction
					scanName='';
				}	
			}
			else
			{
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
				if(textPhotoName.text==''){//if photo has not been uploaded in this transaction
					photoName='';
				}	
			}
	
			checkValid(null);			
		}
	
		private function uploadComplete(event:Event):void{
		
			//	status_txt.text += 'Upload complete\n';	
			checkValid(null);	
		}
		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();
			comboIssueCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
           	comboPassportCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
           	//comboVisa.dataProvider	=	parentApplication.getUserRequestNameResult().visaIdTitle.idTitle;			
       		chkShowAll.visible = false;	
        }       
        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

        	parameters.passportNumber=	textNumber.text;
        	parameters.issueCountryID	=	comboIssueCountry.value;
        	parameters.passportCountryID	=	comboPassportCountry.value;
        	parameters.city			=	textIssueCity.text;
        	parameters.issueDate	=	DateUtils.dateFieldToString(dateIssueDate,parentApplication.dateFormat);
        	parameters.expiryDate	=	DateUtils.dateFieldToString(dateExpiryDate,parentApplication.dateFormat);
        	//parameters.visaID		=	comboVisa.value;
        	parameters.scanName		=	scanName;
        	parameters.photoName	=	photoName; 

        	if (photoName != "") {	
 				parameters.photoLink	=	downloadImage(photoName);
 			}
 			else 
 				parameters.photoLink = "";
 				
        	if (scanName != "") {	
 				parameters.scanLink		=	downloadImage(scanName); 
 			}
 			else 
 				parameters.scanLink = "";
       	
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.passport_timestamp;				
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';	
 						
			return parameters;			
		}

		override public function refreshData():void{

			//trace("passport.refreshData()");
			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboIssueCountry,comboIssueCountry.text);			
			comboIssueCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboIssueCountry,nameIndex);
			nameIndex = parentApplication.getComboData(comboPassportCountry,comboPassportCountry.text);			
			comboPassportCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboPassportCountry,nameIndex);
			//nameIndex = parentApplication.getComboData(comboVisa,comboVisa.text);			
			//comboVisa.dataProvider	=	parentApplication.getUserRequestNameResult().visaIdTitle.idTitle;			
       		//parentApplication.setComboData(comboVisa,nameIndex);

			scanName	=	"";	
			photoName	=	"";	
			
			if (modeState != "View") {
				this.focusManager.setFocus(textNumber);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}	
			        
        override protected function refresh():void{
			
			//trace("passport.refresh()");	
			super.refresh();
			
			textNumber.text	=	"";							
			textIssueCity.text	=	"";
			dateIssueDate.text	=	DateUtils.dateToString(today,dateIssueDate.formatString);
			dateExpiryDate.text	=	DateUtils.dateToString(expiryDate,dateExpiryDate.formatString);			
			//comboVisa.selectedIndex	=	0;
			//if (dgList.selectedItem != null) {
			//	parentApplication.setComboData(comboVisa,dgList.selectedItem.visa_id);		
			//}
			comboIssueCountry.selectedIndex =	0;
			comboPassportCountry.selectedIndex =	0;
			textPhoto.text	=	"";
			textScan.text	=	"";
			textScan.visible = false;
			textPhotoName.text	=	"";			
			textScanName.text	=	"";			
        	textPhoto.text = "";
        	photoHolder.source="";
        	scanHolder.source="";	
		}
		
		override protected function checkValid(inputObject:Object):void{
			
			super.checkValid(inputObject);
			
			if((textNumber.text	==	"")||(textIssueCity.text	==	"")){			
				saveEnabled	= false;
			}			
			if((dateIssueDate.text == "") || (dateExpiryDate.text == "")){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateIssueDate, dateExpiryDate) == 1) {
				saveEnabled = false;				
			}			
			if((comboIssueCountry.selectedIndex == 0) || (comboPassportCountry.selectedIndex == 0)){
				saveEnabled = false;
			}
		}

		override protected function setValues():void{

			//trace("passport.setValues()");
			super.setValues();
			
			photoHolder.source	=	downloadImage(dgList.selectedItem.photo_link,'thumb');
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
        	textNumber.text		=	dgList.selectedItem.number;
        	textIssueCity.text	=	dgList.selectedItem.issue_city;
        	dateIssueDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.issue_date,dateIssueDate,parentApplication.dateFormat);
        	dateExpiryDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.expiry_date,dateExpiryDate,parentApplication.dateFormat);       	
        	//parentApplication.setComboData(comboVisa,dgList.selectedItem.visa_id);
        	parentApplication.setComboData(comboIssueCountry,dgList.selectedItem.issue_country_id);
        	parentApplication.setComboData(comboPassportCountry,dgList.selectedItem.passport_country_id);
        	parentApplication.setPassportID(dgList.selectedItem.__id);
        	//parentApplication.setVisaID(dgList.selectedItem.post_id);
		}

		protected function displayPopUpCountry(strTitle:String,strType:String,editMode:Boolean=false):void{		
		
			if (strType=="issue")
				displayCountryPopUp(strTitle,comboIssueCountry,editMode);
			else
				displayCountryPopUp(strTitle,comboPassportCountry,editMode);
		}
		/*
		protected function displayPopUpVisa(strTitle:String,editMode:Boolean=false):void{		
		
			var tabType:String = getModeType(editMode);
			if (!parentApplication.isTabEnabled('visa_history',tabType)) return;
			
			if((comboVisa.selectedIndex > 0) || (!editMode)){
				var pop1:popUpVisa = popUpVisa(PopUpManager.createPopUp(this,popUpVisa, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				
				switch (tabType) {
					
					case 'edit':
					
						pop1.title="Edit "+strTitle;
						pop1.setup('visa',true,comboVisa.text,true);
						break;
					
					case 'view':
					
						pop1.title="View "+strTitle;
						pop1.setup('visa',true,comboVisa.text,false);
						break;
						
					case 'add':
					
						pop1.title="Add New "+strTitle;
						pop1.initialise('visa',true);
						break;
				}
  			}
		}
		*/
		
		protected function downloadImage(fileLink:String,type:String='image'):String{			
			
			if (parentApplication.getFileExtension(scanName) == 'pdf') {
				type='doc';	
			} 
			return  parentApplication.downloadFile(fileLink,type);
		}