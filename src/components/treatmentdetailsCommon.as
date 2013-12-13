//import components.patient.popUpTreatmentcase;	
				
        public var dateStartDate:DateField;   
        public var dateEndDate:DateField;  
        public var comboCase:ComboBoxNew;          
        public var comboCategory:ComboBoxNew;
        public var comboRegimen:ComboBoxNew;
        public var comboResult:ComboBoxNew;
        
        [Bindable] private var today:Date = new Date(); 

        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Date Started", data:dateStartDate},{label:"Date Ended", data:dateEndDate}]);           	           	
        }

        override protected function loadNonMandatoryFields():void{

       		nonMandatoryComboFields  = new ArrayCollection([{label:"Result", data:comboResult}]);
       		nonMandatoryDateFields  = new ArrayCollection([{label:"Date Ended", data:dateEndDate}]);        
        }
                		
        override protected function loadData(current:Boolean=false):void{
        	
        	super.loadData();
			comboResult.dataProvider	=	parentApplication.getUserRequestNameResult().patienttreatmentresults.patienttreatmentresult;	
			comboRegimen.dataProvider	=	parentApplication.getUserRequestNameResult().patienttreatmentregimens.patienttreatmentregimen;				
			comboCase.dataProvider		=	parentApplication.getUserRequestNameResult().patienttreatmentcases.patienttreatmentcase;	
			comboCategory.dataProvider	=	parentApplication.getUserRequestNameResult().patienttreatmentcategories.patienttreatmentcategory;		
			//chkShowAll.visible = false;   
        }

        override protected function pushSearchByVariables():void{
        	listSearchBy.push({label: "category",data:"treatment"});	  
        	listSearchBy.push({label: "date_started",data:"treatment"});   
        	listSearchBy.push({label: "date_ended",data:"treatment"});     
        	listSearchBy.push({label: "case",data:"treatment"});        
        	listSearchBy.push({label: "regimen",data:"treatment"});	      
        	listSearchBy.push({label: "result",data:"treatment"});	    			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "category",data:"treatment"});	  
        	listSearchWhom.push({fields: "date_started",data:"treatment"});   
        	listSearchWhom.push({fields: "date_ended",data:"treatment"});     
        	listSearchWhom.push({fields: "case",data:"treatment"});        
        	listSearchWhom.push({fields: "regimen",data:"treatment"});	      
        	listSearchWhom.push({fields: "result",data:"treatment"});	
        }		

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.startDate	=	DateUtils.dateFieldToString(dateStartDate,parentApplication.dateFormat);
			parameters.endDate		=	DateUtils.dateFieldToString(dateEndDate,parentApplication.dateFormat);
			parameters.caseID		=	comboCase.value;
			parameters.categoryID	=	comboCategory.value;
			parameters.regimenID	=	comboRegimen.value;
			parameters.resultID		=	comboResult.value;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.treatment_timestamp;				
			}	
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		} 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(inputObject == comboCategory){
				updateCategory();
			} 			
			if(dateStartDate.text == ""){
				saveEnabled	= false;								
			}
			if(comboCategory.selectedIndex==0){
				saveEnabled	= false;	
			}
			if(comboCase.selectedIndex==0){
				saveEnabled	= false;	
			}
			if(comboRegimen.selectedIndex==0){
				saveEnabled	= false;	
			}
			if(DateUtils.compareDateFieldDates(dateStartDate, dateEndDate) == 1) {
				saveEnabled = false;
			}					
		}

		private function updateCategory():void{
			
			var nameIndex:int = parentApplication.getComboData(comboCategory,comboCategory.text);			
			comboCategory.dataProvider	=	parentApplication.getUserRequestNameResult().patienttreatmentcategories.patienttreatmentcategory;		
			parentApplication.setComboData(comboCategory,nameIndex);

			nameIndex = parentApplication.getComboData(comboCase,comboCase.text);
			comboCase.dataProvider		=	parentApplication.getUserRequestNameResult().patienttreatmentcases[comboCategory.text].treatmentcase;	
			parentApplication.setComboData(comboCase,nameIndex);
			if (comboCase.selectedIndex == -1) comboCase.selectedIndex = 0;
			
			nameIndex = parentApplication.getComboData(comboResult,comboResult.text);
			comboResult.dataProvider	=	parentApplication.getUserRequestNameResult().patienttreatmentresults[comboCategory.text].treatmentresult;	
			parentApplication.setComboData(comboResult,nameIndex);
			if (comboResult.selectedIndex == -1) comboResult.selectedIndex = 0;
			
			nameIndex = parentApplication.getComboData(comboRegimen,comboRegimen.text);
			comboRegimen.dataProvider	=	parentApplication.getUserRequestNameResult().patienttreatmentregimens[comboCategory.text].treatmentregimen;				
			parentApplication.setComboData(comboRegimen,nameIndex);
			if (comboRegimen.selectedIndex == -1) comboRegimen.selectedIndex = 0;
		}			
					
		override public function refreshData():void{
			
			super.refreshData();	

			updateCategory();
				
			if (modeState != "View") {
				this.focusManager.setFocus(comboCategory);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}		
		
        override protected function refresh():void{
				
			super.refresh();			
			dateStartDate.text=DateUtils.dateToString(today,dateStartDate.formatString);
			dateEndDate.text='';
			comboCase.selectedIndex=0;
			comboCategory.selectedIndex=0;
			comboRegimen.selectedIndex=0;
			comboResult.selectedIndex=0;
		}

		override protected function setValues():void{

			super.setValues();

			comboCategory.selectedIndex	= parentApplication.getComboIndex(comboCategory.dataProvider,'data',dgList.selectedItem.treatment_category_id);			
			
			updateCategory();
			
         	dateStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateStartDate,parentApplication.dateFormat);
			dateEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateEndDate,parentApplication.dateFormat);
			comboResult.selectedIndex	= parentApplication.getComboIndex(comboResult.dataProvider,'data',dgList.selectedItem.treatment_result_id);
			comboRegimen.selectedIndex	= parentApplication.getComboIndex(comboRegimen.dataProvider,'data',dgList.selectedItem.treatment_regimen_id);
			comboCase.selectedIndex	= parentApplication.getComboIndex(comboCase.dataProvider,'data',dgList.selectedItem.treatment_case_id);
					}		

		protected function displayPopUpCategory(strTitle:String,editMode:Boolean=false):void{		
			
			displayAdminPopUp(strTitle,comboCase.text,'treatment_category',editMode);
		}
		
		protected function displayPopUpCase(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboCase.selectedIndex > 0) || (!editMode)){
				var pop1:popUpTreatmentcase = popUpTreatmentcase(PopUpManager.createPopUp(this, popUpTreatmentcase, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('treatment_case',true,comboCase.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('treatment_case',true,comboCase.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('treatment_case',true);
				}
  			}
		}	
		
		protected function displayPopUpRegimen(strTitle:String,editMode:Boolean=false):void{		
			
			if((comboRegimen.selectedIndex > 0) || (!editMode)){
				var pop1:popUpTreatmentregimen = popUpTreatmentregimen(PopUpManager.createPopUp(this, popUpTreatmentregimen, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('treatment_regimen',true,comboRegimen.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('treatment_regimen',true,comboRegimen.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('treatment_regimen',true);
				}
  			}
		}
		
		protected function displayPopUpResult(strTitle:String,editMode:Boolean=false):void{		
						
			if((comboResult.selectedIndex > 0) || (!editMode)){
				var pop1:popUpTreatmentresult = popUpTreatmentresult(PopUpManager.createPopUp(this, popUpTreatmentresult, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('treatment_result',true,comboResult.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('treatment_result',true,comboResult.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('treatment_result',true);
				}
  			}
		}
