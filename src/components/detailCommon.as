// common code for tabDetailClass and popupDetailClass
import mx.controls.TextArea;


		public var dateDetailStartDate:DateField;
		public var dateDetailEndDate:DateField;
		public var dateDetailRetirementDate:DateField;
		public var dateDetailLeavingDate:DateField;
		public var dateDetailNextReviewDate:DateField;
		public var comboDetailLeavingReason:ComboBoxNew;
		public var comboDetailStaffType:ComboBoxNew;
		public var comboDetailProgramme:ComboBoxNew;
		
		public var textDetailStaffNumber:TextInput;
		public var comboDetailStatus:ComboBoxNew;

		public var textDetailComments:TextArea;
		
		[Bindable] private var today:Date = new Date(); 
        [Bindable] private var nextReviewDate:Date = new Date((new Date()).getFullYear()+1,(new Date()).getMonth(), (new Date()).getDate());   

      // load all date fields for validation 
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([{label:"Leaving Date", data:dateDetailLeavingDate},
         	{label:"End Probation", data:dateDetailEndDate},
         	{label:"Retirement Date", data:dateDetailRetirementDate},
         	{label:"Next Review", data:dateDetailNextReviewDate},
         	{label:"Start Date", data:dateDetailStartDate}         	      	
         	]);
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([{label:"Leaving Date", data:dateDetailLeavingDate},
         	{label:"End Probation", data:dateDetailEndDate},
         	{label:"Next Review", data:dateDetailNextReviewDate},
         	{label:"Retirement Date", data:dateDetailRetirementDate}         	      	
         	]);
         	
       		nonMandatoryComboFields  = new ArrayCollection([{label:"Leaving Reason", data:comboDetailLeavingReason},
       		{label:"Status", data:comboDetailStatus},{label:"Programme", data:comboDetailProgramme}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Staff Number", data:textDetailStaffNumber}, {label:"Comments", data:textDetailComments}]);
          
           }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "staff_type",data:"staff"});//points to staff_type_id
			listSearchBy.push({label: "staff_number",data:"staff"});
        	listSearchBy.push({label: "start_date",data:"staff"});
        	listSearchBy.push({label: "probation_end_date",data:"staff"});
        	listSearchBy.push({label: "next_review_due",data:"staff"});
        	listSearchBy.push({label: "retirement_date",data:"staff"});
        	listSearchBy.push({label: "leaving_date",data:"staff"});
        	listSearchBy.push({label: "leaving_reason",data:"staff"}); //points to leaving_reason_id 
        	//listSearchBy.push({label: "status",data:"staff"}); 
        	listSearchBy.push({label: "programme",data:"staff"}); 
        	listSearchBy.push({label: "comments",data:"staff"});      
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "staff_type",data:"staff"});//points to staff_type_id
			listSearchWhom.push({fields: "staff_number",data:"staff"});
        	listSearchWhom.push({fields: "start_date",data:"staff"});
        	listSearchWhom.push({fields: "probation_end_date",data:"staff"});
        	listSearchWhom.push({fields: "next_review_due",data:"staff"});
        	listSearchWhom.push({fields: "retirement_date",data:"staff"});
        	listSearchWhom.push({fields: "leaving_date",data:"staff"});
        	listSearchWhom.push({fields: "leaving_reason",data:"staff"}); //points to leaving_reason_id
        	//listSearchWhom.push({fields:"status",data:"staff"}); 
        	listSearchWhom.push({fields:"programme",data:"staff"});  
        	listSearchWhom.push({fields:"comments",data:"staff"});  
        }
                          
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
           	comboDetailLeavingReason.dataProvider=parentApplication.getUserRequestNameResult().leavingReasons.reason;
            comboDetailStaffType.dataProvider=parentApplication.getUserRequestNameResult().staffTypes.staffType;   
            comboDetailStatus.dataProvider = parentApplication.getUserRequestTypeResult().staffstatus; 
            comboDetailProgramme.dataProvider=parentApplication.getUserRequestNameResult().programmes.programme;        
            chkShowAll.visible = false;
          //  dgList.visible = false;
        }

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();

			parameters.staffNumber		=	textDetailStaffNumber.text;
			parameters.startDate		=	DateUtils.dateFieldToString(dateDetailStartDate,parentApplication.dateFormat);
			parameters.endDate			=	DateUtils.dateFieldToString(dateDetailEndDate,parentApplication.dateFormat);
			parameters.retirementDate	=	DateUtils.dateFieldToString(dateDetailRetirementDate,parentApplication.dateFormat);
			parameters.nextReviewDate	=	DateUtils.dateFieldToString(dateDetailNextReviewDate,parentApplication.dateFormat);
			parameters.leavingDate		=	DateUtils.dateFieldToString(dateDetailLeavingDate,parentApplication.dateFormat);
			parameters.leavingReasonID	=	comboDetailLeavingReason.value;
			parameters.staffTypeID		=	comboDetailStaffType.value;
			parameters.status			=	comboDetailStatus.value;
			parameters.programmeID		=	comboDetailProgramme.value;
			parameters.comments			=	textDetailComments.text;
			
			if(modeState == 'Edit'){
                parameters.timestamp		=	dgList.selectedItem.staff_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}    
          
		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboDetailLeavingReason,comboDetailLeavingReason.text);			
			comboDetailLeavingReason.dataProvider=parentApplication.getUserRequestNameResult().leavingReasons.reason;
           	parentApplication.setComboData(comboDetailLeavingReason,nameIndex);
			nameIndex = parentApplication.getComboData(comboDetailStaffType,comboDetailStaffType.text);			
			comboDetailStaffType.dataProvider=parentApplication.getUserRequestNameResult().staffTypes.staffType;           
            parentApplication.setComboData(comboDetailStaffType,nameIndex);
            nameIndex = parentApplication.getComboData(comboDetailProgramme,comboDetailProgramme.text);			
			comboDetailProgramme.dataProvider=parentApplication.getUserRequestNameResult().programmes.programme;        
            parentApplication.setComboData(comboDetailProgramme,nameIndex);

			if (modeState != "View") {
				this.focusManager.setFocus(comboDetailStatus);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}  

        override protected function refresh():void{
				
			super.refresh();
			textDetailStaffNumber.text="";
			dateDetailStartDate.text=DateUtils.dateToString(today,dateDetailStartDate.formatString);
			dateDetailEndDate.text=""; //DateUtils.dateToString(nextReviewDate,dateDetailEndDate.formatString);
			dateDetailNextReviewDate.text=DateUtils.dateToString(nextReviewDate,dateDetailNextReviewDate.formatString);
			dateDetailRetirementDate.text="";//DateUtils.dateToString(nextReviewDate,dateDetailRetirementDate.formatString);
			dateDetailLeavingDate.text="";//DateUtils.dateToString(nextReviewDate,dateDetailLeavingDate.formatString);
			comboDetailLeavingReason.selectedIndex=0;	
			comboDetailStaffType.selectedIndex=0;
			comboDetailProgramme.selectedIndex=0;
			comboDetailStatus.selectedIndex=0;
			textDetailComments.text="";
		}        
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateDetailStartDate.text == ""){
				saveEnabled	= false;
			}
			if(comboDetailStatus.selectedIndex == 0){
				saveEnabled	= false;
			}
			if(DateUtils.compareDateFieldDates(dateDetailStartDate, dateDetailEndDate) == 1) {
				saveEnabled = false;
			}
			if(DateUtils.compareDateFieldDates(dateDetailStartDate, dateDetailNextReviewDate) == 1) {
				saveEnabled = false;
			}
			if(DateUtils.compareDateFieldDates(dateDetailStartDate, dateDetailRetirementDate) == 1) {
				saveEnabled = false;
			}
			if(DateUtils.compareDateFieldDates(dateDetailStartDate, dateDetailLeavingDate) == 1) {
				saveEnabled = false;
			}
		}

		override protected function setValues():void{

			super.setValues();

			textDetailStaffNumber.text		=	dgList.selectedItem.staff_number;
			dateDetailStartDate.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.start_date,dateDetailStartDate,parentApplication.dateFormat);	
			dateDetailEndDate.text			=	DateUtils.stringToDateFieldString(dgList.selectedItem.probation_end_date,dateDetailEndDate,parentApplication.dateFormat);
			dateDetailRetirementDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.retirement_date,dateDetailRetirementDate,parentApplication.dateFormat);
			dateDetailLeavingDate.text		=	DateUtils.stringToDateFieldString(dgList.selectedItem.leaving_date,dateDetailLeavingDate,parentApplication.dateFormat);
			dateDetailNextReviewDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.next_review_due,dateDetailNextReviewDate,parentApplication.dateFormat);	
			comboDetailStaffType.selectedIndex		=	parentApplication.getComboIndex(comboDetailStaffType.dataProvider,'data',dgList.selectedItem.staff_type_id);	
			comboDetailLeavingReason.selectedIndex	=	parentApplication.getComboIndex(comboDetailLeavingReason.dataProvider,'data',dgList.selectedItem.leaving_reason_id);
			comboDetailProgramme.selectedIndex		=	parentApplication.getComboIndex(comboDetailProgramme.dataProvider,'data',dgList.selectedItem.programme_id);	
			
			comboDetailStatus.selectedItem 	= 	dgList.selectedItem.status;
			textDetailComments.text		=	dgList.selectedItem.comments;
			/*if(dateDetailNextReviewDate.text=='0000-00-00'){
				dateDetailStartDate.text		=	DateDisplay.format(today);
          		dateDetailNextReviewDate.text	=	DateDisplay.format(nextReviewDate);
			}*/
		}

        protected function displayPopUpLeavingReason(strTitle:String,editMode:Boolean=false):void{		
		
			displayAdminPopUp(strTitle,comboDetailLeavingReason.text,'leaving_reason',editMode);
		}
		
        protected function displayPopUpStaffType(strTitle:String,editMode:Boolean=false):void{		
		
			displayAdminPopUp(strTitle,comboDetailStaffType.text,'staff_type',editMode);
		}
	/*	
		override protected function extraInitialise():void{
			
			super.extraInitialise();

	    	if(dgList.dataProvider.length>0){
		    	btnAddNew.visible = false; // can't have more than one staff record per person
		    	btnEdit.visible = true;
		    }
		    else {
		    	btnAddNew.visible = true;
		    	btnEdit.visible = false;	    
		    }
		}
		*/
		
		protected function displayPopUpProgramme(strTitle:String,editMode:Boolean=false):void{		
		
			var tabType:String = getModeType(editMode);
			
			if((comboDetailProgramme.selectedIndex > 0) || (!editMode)){
				var pop1:popUpProgramme = popUpProgramme(PopUpManager.createPopUp(this,popUpProgramme, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				
				switch (tabType) {
					
					case 'edit':
					
						pop1.title="Edit "+strTitle;
						pop1.setup('programme',true,comboDetailProgramme.text,true);
						break;
					
					case 'view':
					
						pop1.title="View "+strTitle;
						pop1.setup('programme',true,comboDetailProgramme.text,false);
						break;
						
					case 'add':
					
						pop1.title="Add New "+strTitle;
						pop1.initialise('programme',true);
						break;
				}
  			}
		}