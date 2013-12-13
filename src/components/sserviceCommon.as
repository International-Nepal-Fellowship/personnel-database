// common code for tabServiceClass and popupServiceClass

		public var dateServiceStartDate:DateField;
		public var dateServiceEndDate:DateField;
		public var comboServiceSpecialContract:ComboBoxNew;
		public var comboServiceWorkingWeek:ComboBoxNew;
		public var comboServicePost:ComboBoxNew;
		public var comboServiceGrade:ComboBoxNew;
		public var comboServiceLocation:ComboBoxNew;
		public var numServicePercentageTime:TextInput;
		
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([{label:"End Date", data:dateServiceEndDate},{label:"Start Date", data:dateServiceStartDate}])
           	           	
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([{label:"End Date", data:dateServiceEndDate}
         	]);
       		nonMandatoryComboFields  = new ArrayCollection([{label:"Working Week", data:comboServiceWorkingWeek},{label:"Special Contract",data:comboServiceSpecialContract}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Percentage Time", data:numServicePercentageTime}]);
       
           }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "INF_role",data:"service"});
			listSearchBy.push({label: "grade",data:"service"});
        	listSearchBy.push({label: "location",data:"service"});		
			listSearchBy.push({label: "date_from",data:"service"});
			listSearchBy.push({label: "date_until",data:"service"});
			listSearchBy.push({label: "percent_of_time",data:"service"});
			listSearchBy.push({label: "special_contract",data:"service"});
			listSearchBy.push({label: "working_week",data:"service"});
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "INF_role",data:"service"});
			listSearchWhom.push({fields: "grade",data:"service"});
        	listSearchWhom.push({fields: "location",data:"service"});		
			listSearchWhom.push({fields: "date_from",data:"service"});
			listSearchWhom.push({fields: "date_until",data:"service"});
			listSearchWhom.push({fields: "percent_of_time",data:"service"});
			listSearchWhom.push({fields: "special_contract",data:"service"});
			listSearchWhom.push({fields: "working_week",data:"service"});
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
           	comboServiceWorkingWeek.dataProvider=parentApplication.getUserRequestTypeResult().workingWeek;
			comboServiceSpecialContract.dataProvider=parentApplication.getUserRequestTypeResult().specialcontract;
          	comboServiceGrade.dataProvider=parentApplication.getUserRequestNameResult().grades.grade;
			comboServicePost.dataProvider=parentApplication.getUserRequestNameResult().serviceposts.servicepost;
			comboServiceLocation.dataProvider=parentApplication.getUserRequestNameResult().servicelocations.servicelocation;
			chkShowAll.visible = false;
        } 

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.startDate		=	DateUtils.dateFieldToString(dateServiceStartDate,parentApplication.dateFormat);
			parameters.endDate			=	DateUtils.dateFieldToString(dateServiceEndDate,parentApplication.dateFormat);
			parameters.post_id			=	comboServicePost.value;
			if (dgList.selectedItem != null)
				if (comboServicePost.text == dgList.selectedItem.post){ //editing non-active post
					parameters.post_id = dgList.selectedItem.post_id;
				}
			//trace(comboServicePost.value);
			//trace(comboServicePost.selectedItem);
			//trace(dgList.selectedItem.post_id);
			//trace(dgList.selectedItem.post);
			parameters.grade_id			=	comboServiceGrade.value;
			parameters.location_id		=	comboServiceLocation.value;
			parameters.workingWeek		=	comboServiceWorkingWeek.text;
			parameters.specialContract	=	comboServiceSpecialContract.text;
			parameters.percentageTime	=	numServicePercentageTime.text;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.service_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}

		private function checkPostActive():void{
			
			if (comboServicePost.selectedIndex == -1) {//if post isn't in the list (no longer active) then set to post text value
				comboServicePost.text		=	dgList.selectedItem.post;
				comboServicePost.selectedItem = dgList.selectedItem.post;
			}	
		}
		
		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboServiceGrade,comboServiceGrade.text);			
           	comboServiceGrade.dataProvider=parentApplication.getUserRequestNameResult().grades.grade;
			parentApplication.setComboData(comboServiceGrade,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboServicePost,comboServicePost.text);
			comboServicePost.dataProvider=parentApplication.getUserRequestNameResult().serviceposts.servicepost;
			parentApplication.setComboData(comboServicePost,nameIndex);
			checkPostActive();
			
			nameIndex = parentApplication.getComboData(comboServiceLocation,comboServiceLocation.text);
			comboServiceLocation.dataProvider=parentApplication.getUserRequestNameResult().servicelocations.servicelocation;
			parentApplication.setComboData(comboServiceLocation,nameIndex);
				
			if (modeState != "View") {
				this.focusManager.setFocus(comboServicePost);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}  

        override protected function refresh():void{
				
			super.refresh();
			dateServiceStartDate.text=DateUtils.dateToString(today,dateServiceStartDate.formatString);
			dateServiceEndDate.text="";
			numServicePercentageTime.text="100";
			comboServiceGrade.selectedIndex=0;
			comboServicePost.dataProvider=parentApplication.getUserRequestNameResult().activeserviceposts.servicepost;
			comboServicePost.selectedIndex=0;	
			comboServiceLocation.selectedIndex=0;	
			comboServiceWorkingWeek.selectedIndex=0;
			comboServiceSpecialContract.selectedIndex=0;
		}        
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateServiceStartDate.text == ""){
				saveEnabled	= false;								
			}
			if(!parentApplication.isPositiveInteger(numServicePercentageTime.text)){
				saveEnabled	= false;								
			}
			if(int(numServicePercentageTime.text)>100){
				saveEnabled	= false;
			}
			if(comboServiceGrade.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(comboServiceLocation.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(comboServicePost.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateServiceStartDate, dateServiceEndDate) == 1) {
				saveEnabled = false;
			}
		}

		override protected function setValues():void{

			super.setValues();

         	comboServiceSpecialContract.text	=	dgList.selectedItem.special_contract;
         	comboServiceWorkingWeek.text	=	dgList.selectedItem.working_week;
         	numServicePercentageTime.text	=	dgList.selectedItem.percent_of_time;         	
         	dateServiceStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateServiceStartDate,parentApplication.dateFormat);
			dateServiceEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateServiceEndDate,parentApplication.dateFormat);			

			comboServicePost.selectedIndex	=	parentApplication.getComboIndex(comboServicePost.dataProvider,'data',dgList.selectedItem.post_id);	 
			checkPostActive();

			comboServiceGrade.selectedIndex	=	parentApplication.getComboIndex(comboServiceGrade.dataProvider,'data',dgList.selectedItem.grade_id);
			comboServiceLocation.selectedIndex	=	parentApplication.getComboIndex(comboServiceLocation.dataProvider,'data',dgList.selectedItem.location_id);
		}

        protected function displayPopUpLocation(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboServiceLocation.selectedIndex > 0) || (!editMode)){
				var pop1:popUpLocation = popUpLocation(PopUpManager.createPopUp(this, popUpLocation, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('location',true,comboServiceLocation.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('location',true,comboServiceLocation.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('location',true);
				}
  			}
		}
				
		protected function displayPopUpGrade(strTitle:String,editMode:Boolean=false):void{		
		
			displayAdminPopUp(strTitle,comboServiceGrade.text,'grade',editMode);
		}
				
	    protected function displayPopUpPost(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboServicePost.selectedIndex > 0) || (!editMode)){
				var pop1:popUpPost = popUpPost(PopUpManager.createPopUp(this, popUpPost, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					// The combo text is made up of the post name (code) followed by ': ' and then description
					// so we need to retrieve just the first part
					var postString:String = comboServicePost.text;
					var postArray:Array=postString.split(": ");
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('post',true,postArray[0],true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('post',true,postArray[0],false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('post',true);
				}
  			}
		}
