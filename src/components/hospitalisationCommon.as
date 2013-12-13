// common code for tabHospitalisationClass and popupHospitalisationClass


		public var dateHospitalisationStartDate:DateField;
		public var dateHospitalisationEndDate:DateField;
		public var comboHospitalisationHospital:ComboBoxNew;
		public var comboHospitalisationRelative:ComboBoxNew;
		public var comboHospitalisationIllness:ComboBoxNew;
		public var numberHospitalisationCost:TextInput;
		public var numberHospitalisationBirths:TextInput;
		
		public var userRequestCheckDB:HTTPService;
		
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Start Date", data:dateHospitalisationStartDate},{label:"End Date", data:dateHospitalisationEndDate}
         	]);
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([{label:"End Date", data:dateHospitalisationEndDate}
         	]);
       		nonMandatoryComboFields  = new ArrayCollection([{label:"Illness", data:comboHospitalisationIllness},{label:"Hospital", data:comboHospitalisationHospital}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Births", data:numberHospitalisationBirths}]);

           }
		
        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "date_from",data:"hospitalisation"});
			listSearchBy.push({label: "date_until",data:"hospitalisation"});
        	listSearchBy.push({label: "illness",data:"hospitalisation"});
        	listSearchBy.push({label: "births",data:"hospitalisation"});
        	listSearchBy.push({label: "cost",data:"hospitalisation"});
        	listSearchBy.push({label: "hospital_admitted",data:"hospitalisation"}); //points to hospital_id
        	listSearchBy.push({label: "relative",data:"hospitalisation"}); //points to relative_id
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "date_from",data:"hospitalisation"});
			listSearchWhom.push({fields: "date_until",data:"hospitalisation"});
        	listSearchWhom.push({fields: "illness",data:"hospitalisation"});
        	listSearchWhom.push({fields: "births",data:"hospitalisation"});
        	listSearchWhom.push({fields: "cost",data:"hospitalisation"});
        	listSearchWhom.push({fields: "hospital_admitted",data:"hospitalisation"}); //points to hospital_id
        	listSearchWhom.push({fields: "relative",data:"hospitalisation"}); //points to relative_id	
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
           	comboHospitalisationIllness.dataProvider=parentApplication.getUserRequestNameResult().illnesses.illness;
			comboHospitalisationRelative.dataProvider=parentApplication.getFamilyDetailsResult().relatives.relative;
			comboHospitalisationHospital.dataProvider=parentApplication.getUserRequestNameResult().hospitals.hospital;
			chkShowAll.visible = false;	
        }

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.startDate		=	DateUtils.dateFieldToString(dateHospitalisationStartDate,parentApplication.dateFormat);
			parameters.endDate			=	DateUtils.dateFieldToString(dateHospitalisationEndDate,parentApplication.dateFormat);
			parameters.relative_id		=	comboHospitalisationRelative.value;
			parameters.illness_id		=	comboHospitalisationIllness.value;
			parameters.hospital_id		=	comboHospitalisationHospital.value;
			parameters.cost				=	numberHospitalisationCost.text;
			parameters.births			=	numberHospitalisationBirths.text;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.hospitalisation_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboHospitalisationIllness,comboHospitalisationIllness.text);			
           	comboHospitalisationIllness.dataProvider=parentApplication.getUserRequestNameResult().illnesses.illness;
			parentApplication.setComboData(comboHospitalisationIllness,nameIndex);
			nameIndex = parentApplication.getComboData(comboHospitalisationRelative,comboHospitalisationRelative.text);
			comboHospitalisationRelative.dataProvider=parentApplication.getFamilyDetailsResult().relatives.relative;
			parentApplication.setComboData(comboHospitalisationRelative,nameIndex);
			nameIndex = parentApplication.getComboData(comboHospitalisationHospital,comboHospitalisationHospital.text);
			comboHospitalisationHospital.dataProvider=parentApplication.getUserRequestNameResult().hospitals.hospital;
			parentApplication.setComboData(comboHospitalisationHospital,nameIndex);

			if (modeState != "View") {
				this.focusManager.setFocus(dateHospitalisationStartDate);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}			
		}  

        override protected function refresh():void{
				
			super.refresh();			
			comboHospitalisationHospital.selectedIndex=0;
			comboHospitalisationRelative.selectedIndex=0;
			numberHospitalisationCost.text	=	"0.00";
			numberHospitalisationBirths.text	=	"0";
			comboHospitalisationIllness.selectedIndex=0;
			dateHospitalisationStartDate.text=DateUtils.dateToString(today,dateHospitalisationStartDate.formatString);
			dateHospitalisationEndDate.text="";
		}        
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateHospitalisationStartDate.text == ""){
				saveEnabled	= false;								
			}
			if(!parentApplication.isZeroOrPositiveInteger(numberHospitalisationBirths.text)){
				saveEnabled	= false;								
			}
			if(!parentApplication.isPositive(numberHospitalisationCost.text)){
				saveEnabled	= false;
			}
			if(comboHospitalisationRelative.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			/*
			if(comboHospitalisationHospital.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(comboHospitalisationIllness.selectedIndex	==	0){
				saveEnabled	= false;								
			}*/
			if(DateUtils.compareDateFieldDates(dateHospitalisationStartDate, dateHospitalisationEndDate) == 1) {
				saveEnabled = false;
			}
		}

		override protected function setValues():void{

			super.setValues();

         	numberHospitalisationBirths.text	=	dgList.selectedItem.births;
         	numberHospitalisationCost.text	=	Number(dgList.selectedItem.cost).toFixed(2);    	
         	dateHospitalisationStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateHospitalisationStartDate,parentApplication.dateFormat);
			dateHospitalisationEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateHospitalisationEndDate,parentApplication.dateFormat);			
			comboHospitalisationRelative.selectedIndex	=	parentApplication.getComboIndex(comboHospitalisationRelative.dataProvider,'data',dgList.selectedItem.relative_id);
			comboHospitalisationIllness.selectedIndex	=	parentApplication.getComboIndex(comboHospitalisationIllness.dataProvider,'data',dgList.selectedItem.illness_id);
			comboHospitalisationHospital.selectedIndex	=	parentApplication.getComboIndex(comboHospitalisationHospital.dataProvider,'data',dgList.selectedItem.hospital_id);
		}

        protected function displayPopUpHospital(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboHospitalisationHospital.selectedIndex > 0) || (!editMode)){
				var pop1:popUpHospital = popUpHospital(PopUpManager.createPopUp(this, popUpHospital, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('hospital',true,comboHospitalisationHospital.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('hospital',true,comboHospitalisationHospital.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('hospital',true);
				}
  			}
		}
				
		protected function displayPopUpIllness(strTitle:String,editMode:Boolean=false):void{		
		
			displayAdminPopUp(strTitle,comboHospitalisationIllness.text,'illness',editMode);
		}
		
		public function checkCostLimit():void{
	    	
	    	saveEnabled=false;
			parentApplication.searching=true;
			
			var parameters:Object=super.setSendParameters();
	 		parameters.cost=numberHospitalisationCost.text;
	 		parameters.end=DateUtils.dateFieldToString(dateHospitalisationEndDate,parentApplication.dateFormat);
	 		parameters.requester='checkHospitalCost';
	 		userRequestCheckDB.useProxy = false;
           	userRequestCheckDB.url	=	parentApplication.getPath()	+	"requestCheckDb.php";
           	userRequestCheckDB.send(parameters);               
		}
		
		public function handleDBResult(event:ResultEvent):void{
	 	
		 	parentApplication.searching=false;
	 		status_txt.text = userRequestCheckDB.lastResult.warn;
	 		status_txt.enabled = (status_txt.text == '');
		 	checkValid(numberHospitalisationCost);
		 	checkValid(dateHospitalisationEndDate);
		 }