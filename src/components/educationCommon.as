// code for education class
import mx.controls.Text;

		public var dateEndDate:DateField; 
        public var dateStartDate:DateField;     
        public var comboEduQualification:ComboBoxNew;
        public var textEduSpeciality:TextInput;
        public var textEduDivGrade:TextInput;
        public var comboEduInstitution:ComboBoxNew;
        public var comboEduQualificationType:ComboBoxNew;
        public var comboEduSpecialityType:ComboBoxNew;
        public var comboEduSpecialityType2:ComboBoxNew;
        public var scanHolder:Image;
        public var textScanName:TextInput;
		public var textScan:TextInput;
		public var getPhoto:HTTPService;
        
        [Bindable] private var today:Date = new Date(); 
        
        /* Photo uploading script*/
		[Bindable] private var fileName:String;
		private var file:FileReference;
		private var objectId:Object;
    	private var imgFieldID:Object;
	    [Bindable] private var scanName:String="";
	
	// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([{label:"End Date", data:dateEndDate},{label:"Start Date", data:dateStartDate}]);
           	           	
           }
		override protected function loadNonMandatoryFields():void{
			nonMandatoryComboFields  = new ArrayCollection([{label:"Qualification", data:comboEduQualificationType},
       		{label:"Speciality",data:comboEduSpecialityType},{label:"Second Speciality",data:comboEduSpecialityType2}]);
         	nonMandatoryDateFields = new ArrayCollection([{label:"End Date", data:dateEndDate}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Division/Grade", data:textEduDivGrade}]);         
           }
		
        override protected function loadData(current:Boolean=false):void{
        	
        		super.loadData();			
			    comboEduQualificationType.dataProvider=	parentApplication.getUserRequestNameResult().qualifications.qualification;	
			    comboEduSpecialityType.dataProvider	=	parentApplication.getUserRequestNameResult().specialities.speciality;	
			    comboEduSpecialityType2.dataProvider	=	parentApplication.getUserRequestNameResult().specialities.speciality;	
			    comboEduQualification.dataProvider	=	parentApplication.getUserRequestTypeResult().qualification;
			    comboEduInstitution.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.educational_institution;	
           		chkShowAll.visible = false;
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "start_date",data:"education"});
        	listSearchBy.push({label: "end_date",data:"education"});
        	listSearchBy.push({label: "qualification_level",data:"education"});
        	listSearchBy.push({label: "division_grade",data:"education"});
        	listSearchBy.push({label: "institution",data:"education"});
        	listSearchBy.push({label: "speciality_type",data:"education"});	
        	listSearchBy.push({label: "second_speciality_type",data:"education"});	
        	listSearchBy.push({label: "qualification_type",data:"education"});			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "start_date",data:"education"});
        	listSearchWhom.push({fields: "end_date",data:"education"});
        	listSearchWhom.push({fields: "qualification_level",data:"education"});
        	listSearchWhom.push({fields: "division_grade",data:"education"});
        	listSearchWhom.push({fields: "institution",data:"education"});
        	listSearchWhom.push({fields: "speciality_type",data:"education"});
        	listSearchWhom.push({fields: "second_speciality_type",data:"education"});
        	listSearchWhom.push({fields: "qualification_type",data:"education"});
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

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.start_date = DateUtils.dateFieldToString(dateStartDate,parentApplication.dateFormat);
 			parameters.end_date = DateUtils.dateFieldToString(dateEndDate,parentApplication.dateFormat);
			parameters.qualification		=	comboEduQualification.text;
			parameters.division_grade	=	textEduDivGrade.text;
			parameters.institutionID	=	comboEduInstitution.value;
		//	parameters.speciality	=	textEduSpeciality.text;
			parameters.specialityID	=	comboEduSpecialityType.value;
			parameters.second_specialityID	=	comboEduSpecialityType2.value;
			parameters.qualificationID = comboEduQualificationType.value;
		
		    if (scanName != "") {	
 				parameters.scanLink		=	downloadImage(scanName);//txtDocumentPath.text; 
 			}
 			else 
 				parameters.scanLink = "";
      
      		parameters.scanName	=	scanName;
      		
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.education_timestamp;				
			}	
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		} 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateStartDate.text == ""){
				saveEnabled	= false;								
			}
			if(comboEduInstitution.selectedIndex == 0){
				saveEnabled	= false;								
			}
			if(comboEduQualification.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateStartDate, dateEndDate) == 1) {
				saveEnabled = false;
			}
		}
		
		override public function refreshData():void{

			super.refreshData();	
							
			var nameIndex:int = parentApplication.getComboData(comboEduQualificationType,comboEduQualificationType.text);
			comboEduQualificationType.dataProvider=	parentApplication.getUserRequestNameResult().qualifications.qualification;	
			parentApplication.setComboData(comboEduQualificationType,nameIndex);
						
			nameIndex = parentApplication.getComboData(comboEduSpecialityType,comboEduSpecialityType.text);
			comboEduSpecialityType.dataProvider	=	parentApplication.getUserRequestNameResult().specialities.speciality;	
			parentApplication.setComboData(comboEduSpecialityType,nameIndex);
			nameIndex = parentApplication.getComboData(comboEduSpecialityType2,comboEduSpecialityType2.text);
			comboEduSpecialityType2.dataProvider	=	parentApplication.getUserRequestNameResult().specialities.speciality;	
			parentApplication.setComboData(comboEduSpecialityType2,nameIndex);
			nameIndex = parentApplication.getComboData(comboEduInstitution,comboEduInstitution.text);
			comboEduInstitution.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.educational_institution;	
			parentApplication.setComboData(comboEduInstitution,nameIndex);
			
			scanName	=	"";
			    			
			if (modeState != "View") {
				this.focusManager.setFocus(dateStartDate);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		
        override protected function refresh():void{
				
			super.refresh();			
			
			dateStartDate.text=DateUtils.dateToString(today,dateStartDate.formatString);
			dateEndDate.text="";
			comboEduQualification.selectedIndex=0;
			comboEduQualificationType.selectedIndex	=0;
			comboEduSpecialityType.selectedIndex	=0;
			comboEduSpecialityType2.selectedIndex	=0;
			textEduDivGrade.text="";
			comboEduInstitution.selectedIndex	=0;
			//textEduSpeciality.text="";
			textScan.text	=	"";
			textScan.visible = false;
			textScanName.text	=	"";			
        	scanHolder.source="";
		}

		override protected function setValues():void{

			super.setValues();

         	dateStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.start_date,dateStartDate,parentApplication.dateFormat);
			dateEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.end_date,dateEndDate,parentApplication.dateFormat);			
			comboEduQualification.selectedItem	=	dgList.selectedItem.qualification_level;
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
			textEduDivGrade.text=dgList.selectedItem.division_grade;
			comboEduInstitution.selectedIndex	=	parentApplication.getComboIndex(comboEduInstitution.dataProvider,'data',dgList.selectedItem.institution_id);
		//	textEduSpeciality.text=dgList.selectedItem.speciality;
			comboEduQualificationType.selectedIndex	=	parentApplication.getComboIndex(comboEduQualificationType.dataProvider,'data',dgList.selectedItem.qualification_id);
			comboEduSpecialityType.selectedIndex	=	parentApplication.getComboIndex(comboEduSpecialityType.dataProvider,'data',dgList.selectedItem.speciality_id);
			comboEduSpecialityType2.selectedIndex	=	parentApplication.getComboIndex(comboEduSpecialityType2.dataProvider,'data',dgList.selectedItem.second_speciality_id);
		}

		protected function displayPopUpSpeciality(strTitle:String,editMode:Boolean=false):void{		
			
			var comboIndex:int = 0;
			var comboText:String = '';
			
			if (strTitle == "Speciality") {
				comboText = comboEduSpecialityType.text;
				comboIndex = comboEduSpecialityType.selectedIndex;
				//displayAdminPopUp(strTitle,comboEduSpecialityType.text,'speciality_type',editMode);
			} else {
				comboText = comboEduSpecialityType2.text;
				comboIndex = comboEduSpecialityType2.selectedIndex;
				//displayAdminPopUp(strTitle,comboEduSpecialityType2.text,'speciality_type',editMode);
			}
			
			if((comboIndex > 0) || (!editMode)){
				var pop1:popUpSpeciality = popUpSpeciality(PopUpManager.createPopUp(this, popUpSpeciality, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('speciality_type',true,comboText,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('speciality_type',true,comboText,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('speciality_type',true);
				}
  			}
		}
		
		protected function displayPopUpQualification(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboEduQualificationType.selectedIndex > 0) || (!editMode)){
				var pop1:popUpQualification = popUpQualification(PopUpManager.createPopUp(this, popUpQualification, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('qualification_type',true,comboEduQualificationType.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('qualification_type',true,comboEduQualificationType.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('qualification_type',true);
				}
  			}
		}
		
		protected function downloadImage(fileLink:String,type:String='image'):String{			
			
			if (parentApplication.getFileExtension(scanName) == 'pdf') {
				type='doc';	
			}
			return  parentApplication.downloadFile(fileLink,type);
		}
		
		private function handleError(event:IOErrorEvent):void{
			
			status_txt.text = 'ERROR: ' + event.text + '\n';
		}