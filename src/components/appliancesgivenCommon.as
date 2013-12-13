//import components.patient.popUpAppliancetype;		
        public var dateGivenDate:DateField;     
        public var comboAppliance:ComboBoxNew;          
        public var comboRequestedFrom:ComboBoxNew;
        
        [Bindable] private var today:Date = new Date(); 

        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Date Given", data:dateGivenDate}]);           	           	
        }
        		
        override protected function loadData(current:Boolean=false):void{
        	
        		super.loadData();			
			    comboAppliance.dataProvider	=	parentApplication.getUserRequestNameResult().patientappliancetypes.patientappliancetype;	
			    comboRequestedFrom.dataProvider	=	parentApplication.getUserRequestNameResult().patientrequestedfroms.patientrequestedfrom;	
				//chkShowAll.visible = false;   
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "date_given",data:"patient_appliance"});        
        	listSearchBy.push({label: "appliance_type",data:"patient_appliance"});        
        	listSearchBy.push({label: "requested_from",data:"patient_appliance"});	        			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "date_given",data:"patient_appliance"});
        	listSearchWhom.push({fields: "appliance_type",data:"patient_appliance"});
        	listSearchWhom.push({fields: "requested_from",data:"patient_appliance"});
        }		

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.dateGiven	=	DateUtils.dateFieldToString(dateGivenDate,parentApplication.dateFormat);
			parameters.appliance	=	comboAppliance.value;
			parameters.requestedFrom=	comboRequestedFrom.value;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.patient_appliance_timestamp;				
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
			if(comboAppliance.selectedIndex==0){
				saveEnabled	= false;	
			}
			if(comboRequestedFrom.selectedIndex==0){
				saveEnabled	= false;	
			}					
		}
		
		override public function refreshData():void{
			
			super.refreshData();
			
			var nameIndex:int = parentApplication.getComboData(comboAppliance,comboAppliance.text);			
			comboAppliance.dataProvider	=	parentApplication.getUserRequestNameResult().patientappliancetypes.patientappliancetype;	
			parentApplication.setComboData(comboAppliance,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboRequestedFrom,comboAppliance.text);			
			comboRequestedFrom.dataProvider	=	parentApplication.getUserRequestNameResult().patientrequestedfroms.patientrequestedfrom;	
			parentApplication.setComboData(comboRequestedFrom,nameIndex);
			
			if (modeState != "View") {
				this.focusManager.setFocus(dateGivenDate);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}	
		}
		
		
        override protected function refresh():void{
				
			super.refresh();			
			dateGivenDate.text=DateUtils.dateToString(today,dateGivenDate.formatString);
			comboAppliance.selectedIndex=0;
			comboRequestedFrom.selectedIndex=0;
			
		}

		override protected function setValues():void{

			super.setValues();
         	dateGivenDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_given,dateGivenDate,parentApplication.dateFormat);
			comboAppliance.selectedIndex	= parentApplication.getComboIndex(comboAppliance.dataProvider,'data',dgList.selectedItem.appliance_type_id);
			comboRequestedFrom.selectedIndex	= parentApplication.getComboIndex(comboRequestedFrom.dataProvider,'data',dgList.selectedItem.requested_from_id);
			
		}

		protected function displayPopUpRequestedFrom(strTitle:String,editMode:Boolean=false):void{		
			
			displayAdminPopUp(strTitle,comboAppliance.text,'requested_From',editMode);
		}

		
			
		protected function displayPopUpAppliance(strTitle:String,editMode:Boolean=false):void{	
			if((comboAppliance.selectedIndex > 0) || (!editMode)){
				var pop1:popUpAppliancetype = popUpAppliancetype(PopUpManager.createPopUp(this, popUpAppliancetype, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('patient_appliance_type',true,comboAppliance.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('patient_appliance_type',true,comboAppliance.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('patient_appliance_type',true);
				}
  			}
		}