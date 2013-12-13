	
        public var dateGivenDate:DateField;     
        public var comboSurgery:ComboBoxNew;          
        
        [Bindable] private var today:Date = new Date(); 

        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Date Given", data:dateGivenDate}]);           	           	
        }
        		
        override protected function loadData(current:Boolean=false):void{
        	
        	super.loadData();		 			
			comboSurgery.dataProvider	=	parentApplication.getUserRequestNameResult().patientsurgerytypes.patientsurgerytype;	
           	//chkShowAll.visible = false;   
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "date_given",data:"patient_surgery"});        
        	listSearchBy.push({label: "surgery_type",data:"patient_surgery"});        	        			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "date_given",data:"patient_surgery"});
        	listSearchWhom.push({fields: "surgery_type",data:"patient_surgery"});
        }		

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.dateGiven	= DateUtils.dateFieldToString(dateGivenDate,parentApplication.dateFormat);
			parameters.surgery	=	comboSurgery.value;
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.patient_surgery_timestamp;				
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
			if(comboSurgery.selectedIndex==0){
				saveEnabled	= false;	
			}					
		}
		
		override public function refreshData():void{
			
			super.refreshData();	

			var nameIndex:int = parentApplication.getComboData(comboSurgery,comboSurgery.text);			
			comboSurgery.dataProvider	=	parentApplication.getUserRequestNameResult().patientsurgerytypes.patientsurgerytype;	
           	parentApplication.setComboData(comboSurgery,nameIndex);
           					
			if (modeState != "View") {
				this.focusManager.setFocus(dateGivenDate);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}		
		
        override protected function refresh():void{
				
			super.refresh();
						
			dateGivenDate.text=DateUtils.dateToString(today,dateGivenDate.formatString);
			comboSurgery.selectedIndex=0;			
		}

		override protected function setValues():void{

			super.setValues();
			
         	dateGivenDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_given,dateGivenDate,parentApplication.dateFormat);
			comboSurgery.selectedIndex	= parentApplication.getComboIndex(comboSurgery.dataProvider,'data',dgList.selectedItem.surgery_type_id);
		}		

		protected function displayPopUpService(strTitle:String,editMode:Boolean=false):void{		
			
			displayAdminPopUp(strTitle,comboSurgery.text,'patient_surgery_type',editMode);
		}

