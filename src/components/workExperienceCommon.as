// code for education class
import mx.controls.Text;

		public var dateEndDate:DateField; 
        public var dateStartDate:DateField;     
        public var textWorkplace:TextInput;
        public var textJobTitle:TextInput;
        public var textCityTown:TextInput;
        public var textDescription:TextArea;
        public var comboCountry:ComboBoxNew;
        public var comboLeavingReason:ComboBoxNew;
        
        [Bindable] private var today:Date = new Date(); 
	
	// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([{label:"End Date", data:dateEndDate},{label:"Start Date", data:dateStartDate}]);
           	           	
           }
           
		override protected function loadNonMandatoryFields():void{
			nonMandatoryComboFields  = new ArrayCollection([{label:"Country", data:comboCountry},
       		{label:"Leaving Reason",data:comboLeavingReason}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Description", data:textDescription},
       		{label:"City/Town", data:textCityTown}]);         
           }
		
        override protected function loadData(current:Boolean=false):void{
        	
        	super.loadData();			
			comboLeavingReason.dataProvider=parentApplication.getUserRequestNameResult().leavingReasons.reason;
            comboCountry.dataProvider	=	parentApplication.getUserRequestNameResult().countries.country;
		   	chkShowAll.visible = false;
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "start_date",data:"work_experience"});
        	listSearchBy.push({label: "end_date",data:"work_experience"});
        	listSearchBy.push({label: "workplace",data:"work_experience"});
        	listSearchBy.push({label: "job_title",data:"work_experience"});
        	listSearchBy.push({label: "description",data:"work_experience"});
        	listSearchBy.push({label: "city_town",data:"work_experience"});	
        	listSearchBy.push({label: "country",data:"work_experience"});	
        	listSearchBy.push({label: "leaving_reason",data:"work_experience"});			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "start_date",data:"work_experience"});
        	listSearchWhom.push({fields: "end_date",data:"work_experience"});
        	listSearchWhom.push({fields: "workplace",data:"work_experience"});
        	listSearchWhom.push({fields: "job_title",data:"work_experience"});
        	listSearchWhom.push({fields: "description",data:"work_experience"});
        	listSearchWhom.push({fields: "city_town",data:"work_experience"});
        	listSearchWhom.push({fields: "country",data:"work_experience"});
        	listSearchWhom.push({fields: "leaving_reason",data:"work_experience"});
        }		

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.start_date = DateUtils.dateFieldToString(dateStartDate,parentApplication.dateFormat);
 			parameters.end_date = DateUtils.dateFieldToString(dateEndDate,parentApplication.dateFormat);
			parameters.description		=	textDescription.text;
			parameters.workplace	=	textWorkplace.text;
			parameters.job_title	=	textJobTitle.text;
			parameters.city_town	=	textCityTown.text;
			parameters.countryID	=	comboCountry.value;
			parameters.leavingReasonID = comboLeavingReason.value;
      		
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.work_experience_timestamp;				
			}	
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		} 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if((dateStartDate.text == "") || (dateEndDate.text == "")){
				saveEnabled	= false;								
			}
			if((textWorkplace.text == "") || (textJobTitle.text == "")){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateStartDate, dateEndDate) == 1) {
				saveEnabled = false;
			}
		}
		
		override public function refreshData():void{

			super.refreshData();	
							
			var nameIndex:int = parentApplication.getComboData(comboLeavingReason,comboLeavingReason.text);			
			comboLeavingReason.dataProvider=parentApplication.getUserRequestNameResult().leavingReasons.reason;
           	parentApplication.setComboData(comboLeavingReason,nameIndex);
						
			nameIndex = parentApplication.getComboData(comboCountry,comboCountry.text);			
			comboCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboCountry,nameIndex);
			    			
			if (modeState != "View") {
				this.focusManager.setFocus(textWorkplace);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		
        override protected function refresh():void{
				
			super.refresh();			
			
			dateStartDate.text=DateUtils.dateToString(today,dateStartDate.formatString);
			dateEndDate.text="";
			comboCountry.selectedIndex=0;
			comboLeavingReason.selectedIndex	=0;
			textWorkplace.text="";
			textDescription.text="";
			textCityTown.text="";
			textJobTitle.text="";
		}

		override protected function setValues():void{

			super.setValues();

         	dateStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.start_date,dateStartDate,parentApplication.dateFormat);
			dateEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.end_date,dateEndDate,parentApplication.dateFormat);			
			comboLeavingReason.selectedIndex	=	parentApplication.getComboIndex(comboLeavingReason.dataProvider,'data',dgList.selectedItem.leaving_reason_id);
			comboCountry.selectedItem	=	dgList.selectedItem.country_id;
			textWorkplace.text=dgList.selectedItem.workplace;
			textDescription.text=dgList.selectedItem.description;
			textJobTitle.text=dgList.selectedItem.job_title;
			textCityTown.text=dgList.selectedItem.city_town;
		}

		protected function displayPopUpCountry(strTitle:String,editMode:Boolean=false):void{		
		
			displayCountryPopUp(strTitle,comboCountry,editMode);
		}
		
        protected function displayPopUpLeavingReason(strTitle:String,editMode:Boolean=false):void{		
		
			displayAdminPopUp(strTitle,comboLeavingReason.text,'leaving_reason',editMode);
		}