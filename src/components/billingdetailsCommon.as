	
        public var datePaidDate:DateField;     
        public var comboPaidBy:ComboBoxNew;       
        public var txtAmount:TextInput;
       
        
        [Bindable] private var today:Date = new Date(); 

        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Paid Date", data:datePaidDate}]);           	           	
        }
		
        override protected function loadData(current:Boolean=false):void{
        	
        		super.loadData();			
			    comboPaidBy.dataProvider	=	parentApplication.getUserRequestTypeResult().patientbillpaidby;	
           		//chkShowAll.visible = false;   
    		
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "date_paid",data:"patient_bill"});        
        	listSearchBy.push({label: "amount",data:"patient_bill"});        	
        	listSearchBy.push({label: "paid_by",data:"patient_bill"});
        			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "date_paid",data:"patient_bill"});
        	listSearchWhom.push({fields: "amount",data:"patient_bill"});
        	listSearchWhom.push({fields: "paid_by",data:"patient_bill"});
        }		

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.datePaid	= DateUtils.dateFieldToString(datePaidDate,parentApplication.dateFormat);
			parameters.paidBy	=	comboPaidBy.text;
			parameters.amount	=	txtAmount.text;
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.patient_bill_timestamp;				
			}	
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		} 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(datePaidDate.text == ""){
				saveEnabled	= false;								
			}
			if(!parentApplication.isPositive(txtAmount.text)){
				saveEnabled	= false;								
			}		
		}
		
		override public function refreshData():void{
			
			super.refreshData();	
			if (modeState != "View") {
				this.focusManager.setFocus(datePaidDate);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		
		
        override protected function refresh():void{
				
			super.refresh();			
			datePaidDate.text=DateUtils.dateToString(today,datePaidDate.formatString);
			comboPaidBy.selectedIndex=0;
			txtAmount.text="0.00";
		}

		override protected function setValues():void{

			super.setValues();

         	datePaidDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_paid,datePaidDate,parentApplication.dateFormat);
			comboPaidBy.selectedItem	=	dgList.selectedItem.paid_by;
			txtAmount.text=dgList.selectedItem.amount;
		}
		