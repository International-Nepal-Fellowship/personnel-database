	
        public var dateGivenDate:DateField;     
        public var comboService:ComboBoxNew;          
        
        [Bindable] private var today:Date = new Date(); 

        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Date Given", data:dateGivenDate}]);           	           	
        }
        		
        override protected function loadData(current:Boolean=false):void{
        	
        	super.loadData();			
			comboService.dataProvider	=	parentApplication.getUserRequestNameResult().patientservicetypes.patientservicetype;	
           	//chkShowAll.visible = false;   
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "date_given",data:"patient_service"});        
        	listSearchBy.push({label: "service_type",data:"patient_service"});        	        			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "date_given",data:"patient_service"});
        	listSearchWhom.push({fields: "service_type",data:"patient_service"});
        }		

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.dateGiven	= DateUtils.dateFieldToString(dateGivenDate,parentApplication.dateFormat);
			parameters.service	=	comboService.value;
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.patient_service_timestamp;				
			}	
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		} 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateGivenDate.text == ""){
				saveEnabled	= false;								
			}
			if(comboService.selectedIndex==0){
				saveEnabled	= false;	
			}					
		}
		
		override public function refreshData():void{
			
			super.refreshData();	

			var nameIndex:int = parentApplication.getComboData(comboService,comboService.text);			
			comboService.dataProvider	=	parentApplication.getUserRequestNameResult().patientservicetypes.patientservicetype;	
           	parentApplication.setComboData(comboService,nameIndex);
			
			if (modeState != "View") {
				this.focusManager.setFocus(dateGivenDate);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}		
		
        override protected function refresh():void{
				
			super.refresh();
						
			dateGivenDate.text=DateUtils.dateToString(today,dateGivenDate.formatString);
			comboService.selectedIndex=0;			
		}

		override protected function setValues():void{

			super.setValues();
			
         	dateGivenDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_given,dateGivenDate,parentApplication.dateFormat);
			comboService.selectedIndex	= parentApplication.getComboIndex(comboService.dataProvider,'data',dgList.selectedItem.service_type_id);			
		}		

		protected function displayPopUpService(strTitle:String,editMode:Boolean=false):void{		
			
			displayAdminPopUp(strTitle,comboService.text,'patient_service_type',editMode);
		}

