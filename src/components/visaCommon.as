// common code for visaClass and popupVisaClass
	
		public var comboIssueCountry:ComboBoxNew;
		public var comboStatus:ComboBoxNew;
		public var comboType:ComboBoxNew;
		public var comboSubType:ComboBoxNew;
		public var dateIssueDate:DateField;
		public var dateExpiryDate:DateField;
		public var dateEntryDate:DateField;
		public var textNumber:TextInput;
		public var textIssueCity:TextInput;
		
		[Bindable] private var today:Date = new Date();
	    [Bindable] private var expiryDate:Date = new Date(today.getFullYear()+1,today.getMonth(),today.getDate()); 
		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			
			comboIssueCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			comboStatus.dataProvider=parentApplication.getUserRequestTypeResult().visastatus;
			comboType.dataProvider=parentApplication.getUserRequestTypeResult().visatype;
			comboSubType.dataProvider=parentApplication.getUserRequestTypeResult().visasubtype;
			
			dualTableName = "visa_history";
        }
        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;			
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboIssueCountry,comboIssueCountry.text);			
			comboIssueCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboIssueCountry,nameIndex);	
		}	

		override protected function refreshDualData():void{

			super.refreshDualData();
			if (modeStateDual != "View") {
				this.focusManager.setFocus(textNumber);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		
		protected function displayPopUpCountry(strTitle:String):void{		
		
			var pop1:popUpCountries = popUpCountries(PopUpManager.createPopUp(this,popUpCountries, true));
			PopUpManager.centerPopUp(pop1);
			pop1.title="Add New "+strTitle;
            pop1.showCloseButton=true;
            pop1.initialise('country',true);
		}
		
		override protected function checkDualValid(inputObject:Object):void{

			super.checkDualValid(inputObject);
			
			if((textNumber.text	==	"")||(textIssueCity.text	==	"")){			
				saveEnabledDual	= false;
			}
			if((dateIssueDate.text == "") || (dateExpiryDate.text == "")|| (dateEntryDate.text == "")){
				saveEnabledDual	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateIssueDate, dateExpiryDate) == 1) {
				saveEnabledDual = false;
			}
			if(DateUtils.compareDateFieldDates(dateEntryDate, dateExpiryDate) == 1) {
				saveEnabledDual = false;
			}
			if(comboIssueCountry.selectedIndex == 0){
				saveEnabledDual = false;
			}
			if(comboStatus.selectedIndex == 0){
				saveEnabledDual = false;
			}
			if(comboType.selectedIndex == 0){
				saveEnabledDual = false;
			}
			if(comboSubType.selectedIndex == 0){
				saveEnabledDual = false;
			}	
		}
		
		override protected function refreshDual():void{
		
			super.refreshDual();
			
			textNumber.text	=	"";							
			textIssueCity.text	=	"";
			dateIssueDate.text	=	DateUtils.dateToString(today,dateIssueDate.formatString);
			dateExpiryDate.text	=	DateUtils.dateToString(expiryDate,dateExpiryDate.formatString);	
			dateEntryDate.text	=	"";		
			comboStatus.selectedIndex	=	0;
			comboType.selectedIndex	=	0;
			comboSubType.selectedIndex	=	0;
			comboIssueCountry.selectedIndex =	0;
		}
		
		override protected function fillDualData():void{
			
			super.fillDualData();
			
			textNumber.text		=	dgDualList.selectedItem.number;
        	textIssueCity.text	=	dgDualList.selectedItem.issue_city;
        	dateIssueDate.text	=	DateUtils.stringToDateFieldString(dgDualList.selectedItem.issue_date,dateIssueDate,parentApplication.dateFormat);
        	dateExpiryDate.text	=	DateUtils.stringToDateFieldString(dgDualList.selectedItem.expiry_date,dateExpiryDate,parentApplication.dateFormat);       	
        	dateEntryDate.text	=	DateUtils.stringToDateFieldString(dgDualList.selectedItem.entry_date,dateEntryDate,parentApplication.dateFormat);       	
        	comboStatus.selectedItem	=	dgDualList.selectedItem.status;
        	comboType.selectedItem	=	dgDualList.selectedItem.type;
        	comboSubType.selectedItem	=	dgDualList.selectedItem.subtype;
        	parentApplication.setComboData(comboIssueCountry,dgDualList.selectedItem.issue_country_id);
		}
	
		override protected function getDualObj():Object {
			
			var dualObj:Object = super.getDualObj();
			
			dualObj.number = dgDual.selectedItem.number;
			dualObj.visa_history_timestamp = dgDual.selectedItem.visa_history_timestamp;
			dualObj.visa_id = dgDual.selectedItem.visa_id;
			dualObj.issue_city = dgDual.selectedItem.issue_city;
			dualObj.issue_date = dgDual.selectedItem.issue_date;
			dualObj.expiry_date = dgDual.selectedItem.expiry_date;
			dualObj.entry_date = dgDual.selectedItem.entry_date;
			dualObj.status = dgDual.selectedItem.status;
			dualObj.type = dgDual.selectedItem.type;
			dualObj.subtype = dgDual.selectedItem.subtype;
			dualObj.country = dgDual.selectedItem.country;
			dualObj.issue_country_id = dgDual.selectedItem.issue_country_id;
			
			return dualObj;
		}
		
		override protected function populateDual(event:ResultEvent):void{
	
			dgDual.dataProvider=	userRequestGetDualData.lastResult.visadatas.visadata;	
			super.populateDual(event);			
		}
		
		override protected function sendDualDataParameters():Object{
						
			var parameters:Object	=	super.sendDualDataParameters();
			
			parameters.visaNumber	=	textNumber.text;
        	parameters.countryID	=	comboIssueCountry.value;
        	parameters.city			=	textIssueCity.text;
        	parameters.issueDate	=	DateUtils.dateFieldToString(dateIssueDate,parentApplication.dateFormat);
        	parameters.expiryDate	=	DateUtils.dateFieldToString(dateExpiryDate,parentApplication.dateFormat);
        	parameters.entryDate	=	DateUtils.dateFieldToString(dateEntryDate,parentApplication.dateFormat);
        	parameters.status		=	comboStatus.text; 
        	parameters.type			=	comboType.text; 
        	parameters.subtype		=	comboSubType.text;
        	parameters.visaID 		=	dgList.selectedItem.__id;
        	
        	if(modeStateDual=='Edit'){
       			parameters.timestamp =	dgDualList.selectedItem.visa_history_timestamp;       		
  			}
				
  			return parameters;
  		}