// common code for tabDetailClass and popupDetailClass

		public var dateInsuranceStartDate:DateField;
		public var dateInsuranceEndDate:DateField;
		public var btnGetInsurance:Button;
		//public var textInsuranceCompany:TextInput;
		public var textInsuranceReference:TextInput;
		public var textInsuranceContact:TextInput;
		public var comboInsuranceClass:ComboBoxNew;
		public var comboInsuranceCompany:ComboBoxNew;
		public var numberPremium:TextInput;
		public var numberTerrorism:TextInput;

		[Bindable] private var today:Date = new Date(); 

      // load all date fields for validation 
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([
         	{label:"Start Date", data:dateInsuranceStartDate},
         	{label:"End Date", data:dateInsuranceEndDate}         	      	
         	]);
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([
         	{label:"End Date", data:dateInsuranceEndDate}     	      	
         	]);
         	nonMandatoryComboFields  = new ArrayCollection([{label:"Insurance Class", data:comboInsuranceClass},
         	{label:"Company", data:comboInsuranceCompany}]);
       		nonMandatoryTextFields  = new ArrayCollection([
       		//{label:"Company", data:textInsuranceCompany},
       		{label:"Contact", data:textInsuranceContact},
       		{label:"Premium", data:numberPremium},{label:"Terrorism", data:numberTerrorism}
       		]);
          
           }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "company",data:"insurance"}); 
        	listSearchBy.push({label: "reference",data:"insurance"}); 
        	listSearchBy.push({label: "contact",data:"insurance"}); 
        	listSearchBy.push({label: "start_date",data:"insurance"}); 
        	listSearchBy.push({label: "end_date",data:"insurance"});
        	listSearchBy.push({label: "insurance_class",data:"insurance"});
        	listSearchBy.push({label: "premium",data:"insurance"});
        	listSearchBy.push({label: "terrorism",data:"insurance"});       
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields:"company",data:"insurance"}); 
        	listSearchWhom.push({fields:"reference",data:"insurance"}); 
        	listSearchWhom.push({fields:"contact",data:"insurance"}); 
        	listSearchWhom.push({fields:"start_date",data:"insurance"}); 
        	listSearchWhom.push({fields:"end_date",data:"insurance"}); 
        	listSearchWhom.push({fields:"insurance_class",data:"insurance"});
        	listSearchWhom.push({fields:"premium",data:"insurance"});
        	listSearchWhom.push({fields:"terrorism",data:"insurance"}); 
        }
                          
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			       
            chkShowAll.visible = false;
            
            comboInsuranceCompany.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.insurance;
            comboInsuranceClass.dataProvider = parentApplication.getUserRequestTypeResult().insuranceclass; 
          //  dgList.visible = false;
        }

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();

			parameters.insuranceCompanyID =   comboInsuranceCompany.value;
			parameters.insuranceReference= 	textInsuranceReference.text;
			parameters.insuranceContact =  	textInsuranceContact.text;
			parameters.insuranceStart   =	DateUtils.dateFieldToString(dateInsuranceStartDate,parentApplication.dateFormat);
			parameters.insuranceEnd		=	DateUtils.dateFieldToString(dateInsuranceEndDate,parentApplication.dateFormat);
			parameters.insuranceClass	=	comboInsuranceClass.value;
			parameters.insurancePremium = numberPremium.text;
			parameters.insuranceTerrorism = numberTerrorism.text;
			if(modeState == 'Edit'){
                parameters.timestamp		=	dgList.selectedItem.insurance_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}    
          
		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboInsuranceCompany,comboInsuranceCompany.text);			
			comboInsuranceCompany.dataProvider	=	parentApplication.getUserRequestNameResult().organisations.insurance;
			parentApplication.setComboData(comboInsuranceCompany,nameIndex);
			
			if (modeState != "View") {
				this.focusManager.setFocus(textInsuranceReference);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}  

        override protected function refresh():void{
				
			super.refresh();
			//textInsuranceCompany.text="";
			textInsuranceReference.text="";
			textInsuranceContact.text="";
			dateInsuranceStartDate.text="";
			dateInsuranceEndDate.text="";
			comboInsuranceClass.selectedIndex=0;
			comboInsuranceCompany.selectedIndex=0;
			numberPremium.text	=	"0.00";
			numberTerrorism.text	=	"0.00";
		}        
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateInsuranceStartDate.text == ""){
				saveEnabled	= false;
			}
			if(textInsuranceReference.text == ""){
				saveEnabled	= false;
			}
			if(!parentApplication.isZeroOrPositive(numberPremium.text)){
				saveEnabled	= false;								
			}
			if(!parentApplication.isZeroOrPositive(numberTerrorism.text)){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateInsuranceStartDate, dateInsuranceEndDate) == 1) {
				saveEnabled = false;
			}
		}

		override protected function setValues():void{

			super.setValues();

			//textInsuranceCompany.text	=		dgList.selectedItem.company;
			textInsuranceReference.text	=		dgList.selectedItem.reference;
			textInsuranceContact.text	=		dgList.selectedItem.contact;
			dateInsuranceStartDate.text	=		DateUtils.stringToDateFieldString(dgList.selectedItem.start_date,dateInsuranceStartDate,parentApplication.dateFormat);	
			dateInsuranceEndDate.text	=		DateUtils.stringToDateFieldString(dgList.selectedItem.end_date,dateInsuranceEndDate,parentApplication.dateFormat);	
			numberPremium.text	=	Number(dgList.selectedItem.premium).toFixed(2);
			numberTerrorism.text	=	Number(dgList.selectedItem.terrorism).toFixed(2);
			comboInsuranceClass.selectedItem 	= 	dgList.selectedItem.insurance_class;
			comboInsuranceCompany.selectedIndex	=	parentApplication.getComboIndex(comboInsuranceCompany.dataProvider,'data',dgList.selectedItem.company_id);
			//parentApplication.setComboData(comboInsuranceCompany,dgList.selectedItem.company_id);
		}

		protected function displayPopUpDoc():void{		
			
			if (dgList.selectedItem == null) return;
			
			var newDoc:Boolean = (dgList.selectedItem.insurance_doc_id == 0);
			displayDocPopUp('Insurance Document','insurance_doc_id','insurance_notes', newDoc);
		}    
		
		override protected function enableDisableDocumentButton():void{
			
			var rowCount:int=dgList.dataProvider.length;
		//Alert.show(rowCount.toString());			
			if(modeState=='Edit') {
				btnGetInsurance.visible=true;
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				btnGetInsurance.visible=(dgList.selectedItem.insurance_doc_id>0)?true:false;
			}
			else{
				btnGetInsurance.visible=false;
			}		
		}