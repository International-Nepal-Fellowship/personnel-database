// common code for tabLeaveClass and popupLeaveClass
import mx.controls.Alert;
import mx.controls.CheckBox;
import mx.controls.TextInput;
import mx.rpc.events.ResultEvent;
	
		public var userRequestCalcLeave:HTTPService;
	
		public var dateLeaveStartDate:DateField;
		public var dateLeaveEndDate:DateField;
		public var comboLeaveType:ComboBoxNew;
		public var comboLeaveReplacement:ComboBoxNew;
		public var usedLeaveDays:TextInput;
		public var totalLeaveDays:TextInput;
		public var adjustLeaveDays:TextInput;
		public var remainingLeaveDays:TextInput;
		public var currentLeaveDays:TextInput;
		public var chkHalfStart:CheckBox;
		public var chkHalfEnd:CheckBox;
		
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields = new ArrayCollection([{label:"Start Date", data:dateLeaveStartDate},{label:"End Date", data:dateLeaveEndDate}]);
           	           	
        }
		
		private function calculateLeaveDays():void{
		
			totalLeaveDays.text='';
			adjustLeaveDays.text='';
			usedLeaveDays.text='';
			remainingLeaveDays.text='';
			
        	var parameters:Object=new Object();      	
        	
        	parameters.action	=	"CalcLeave";
        	parameters.nameID	=	parentApplication.getCurrentID(); 
        	parameters.leaveDate = DateUtils.dateFieldToString(dateLeaveStartDate,parentApplication.dateFormat);
			parameters.leaveTypeID	=	comboLeaveType.value;
			
			if (comboLeaveType.selectedIndex > 0)
			{
				userRequestCalcLeave.useProxy = false;
				userRequestCalcLeave.url	=	parentApplication.getPath()	+	"leaveCalcDays.php";
				userRequestCalcLeave.send(parameters);
			}	
		}
		
		protected function calcResult(event:ResultEvent):void{
		
			var totalDays:Number = userRequestCalcLeave.lastResult.leave_stats.leave_entitlement; 
			totalLeaveDays.text=String(totalDays);
			var adjustDays:Number = userRequestCalcLeave.lastResult.leave_stats.leave_adjustment; 
			adjustLeaveDays.text=String(adjustDays);
			var usedDays:Number = userRequestCalcLeave.lastResult.leave_stats.leave_used; 
			usedLeaveDays.text=String(usedDays);
			remainingLeaveDays.text=String(totalDays+adjustDays-usedDays);
		}
	
		protected function calcDays():void{		
			
			var elapsedMS:Number = DateUtils.dateFieldToDate(dateLeaveEndDate).valueOf() - DateUtils.dateFieldToDate(dateLeaveStartDate).valueOf();// elapsed milliseconds		
			var weekDayOfStart:Number = DateUtils.dateFieldToDate(dateLeaveStartDate).day;// sunday=0,monday=1.....saturday=6
			
			var elapsedDays:Number=Math.floor(elapsedMS/(1000*60*60*24));//gives a day less than the actual elspsed days
			elapsedDays=elapsedDays + 1;// add 1 to elapsed days
			var workingDays:Number = parentApplication.getWorkingDays();
		 	
	 		var noOfHolidays:Number=int((elapsedDays + weekDayOfStart)/7);//gives number of Saturdays (last day of the week, if week starts on Sunday)
		 	//trace("Saturdays ="+noOfHolidays);
		 	//trace("Working days ="+workingDays);
	 		if(workingDays==5){ //Sunday also a weekend day
	 			weekDayOfStart = (weekDayOfStart+6) % 7; //shift so that sunday=6, monday = 0
	 			noOfHolidays=noOfHolidays+int((elapsedDays + weekDayOfStart)/7);//gives number of Sundays (last day of the week, if week starts on Monday)
	 			//trace("Weekend days ="+noOfHolidays);
	 		}

			if(chkHalfStart.selected) {noOfHolidays = noOfHolidays+0.5;}
			if(chkHalfEnd.selected) {noOfHolidays = noOfHolidays+0.5;}
			
			currentLeaveDays.text = String(elapsedDays - noOfHolidays);
		}	

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "leave_type",data:"leave"});
			listSearchBy.push({label: "replacement",data:"leave"});
        	listSearchBy.push({label: "date_from",data:"leave"});
			listSearchBy.push({label: "date_until",data:"leave"});        
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "date_from",data:"leave"});
			listSearchWhom.push({ fields: "date_until",data:"leave"});
			listSearchWhom.push({ fields: "leave_type",data:"leave"});
			listSearchWhom.push({ fields: "replacement",data:"leave"}); 
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
           	comboLeaveType.dataProvider=parentApplication.getUserRequestNameResult().leaveTypes.leaveType;
           	comboLeaveReplacement.dataProvider=parentApplication.getUserRequestTypeResult().leaveReplacement;  
			chkShowAll.visible = false;			
        }

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.startDate = DateUtils.dateFieldToString(dateLeaveStartDate,parentApplication.dateFormat);
 			parameters.endDate = DateUtils.dateFieldToString(dateLeaveEndDate,parentApplication.dateFormat);
			parameters.leaveTypeID	=	comboLeaveType.value;
			parameters.replacement	=	comboLeaveReplacement.text;
			parameters.leaveDays = currentLeaveDays.text;			
			parameters.adjustDays = adjustLeaveDays.text;
			
			if(chkHalfStart.selected)
				parameters.halfDayStart=1;
			else
				parameters.halfDayStart=0;
			if(chkHalfEnd.selected)
				parameters.halfDayEnd=1;
			else
				parameters.halfDayEnd=0;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.leave_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboLeaveType,comboLeaveType.text);			
			comboLeaveType.dataProvider=parentApplication.getUserRequestNameResult().leaveTypes.leaveType;
           	parentApplication.setComboData(comboLeaveType,nameIndex);
            
			if (modeState != "View") {
				this.focusManager.setFocus(comboLeaveType);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
			
			calculateLeaveDays();
			calcDays();
		}  

        override protected function refresh():void{
				
			dateLeaveStartDate.text=DateUtils.dateToString(today,dateLeaveStartDate.formatString);
			dateLeaveEndDate.text=DateUtils.dateToString(today,dateLeaveEndDate.formatString);
			comboLeaveType.selectedIndex=0;
			comboLeaveReplacement.selectedIndex=0;
			chkHalfStart.selected=false;
			chkHalfEnd.selected=false;
			
			super.refresh();
		}        
        
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if((dateLeaveStartDate.text == "") || (dateLeaveEndDate.text == "")){
				saveEnabled	= false;								
			}
			if(comboLeaveType.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateLeaveStartDate, dateLeaveEndDate) == 1) {
			 			
				saveEnabled = false;
			}
			if ((inputObject == dateLeaveStartDate) || (inputObject == dateLeaveEndDate))
				if(!getWeekDay(inputObject.text)){//check if leave start /end days are on saturday(and/or sunday) holidays
					saveEnabled = false;
			}
			if (inputObject == comboLeaveType) {
				calculateLeaveDays();
			}
		}
		
		private function getWeekDay(dateStr:String):Boolean{
			
			var separator:String='-';
			if( dateStr.indexOf('-')>=0)
				separator='-';
			else if( dateStr.indexOf('/')>=0)
				separator='/';
			var dateParts:Array = dateStr.split(separator );
			var date:Date = new Date(dateParts[2],dateParts[1]-1,dateParts[0]);
			//Alert.show(dateStr+'\n'+ date.toString()+'\n '+date.day.toString());
			var workingDays:Number	= parentApplication.getWorkingDays();
			if(workingDays==5){
				if(date.day==0){//sunday
					
					Alert.show("Leave can't start or end on a weekend");
					return false;					
				}
			}
			if(date.day==6){		//saturday				
				Alert.show("Leave can't start or end on a weekend");
				return false;
			}
			
			return true;//valid leave start/end day
		}

		override protected function setValues():void{

			super.setValues();
         	
         	dateLeaveStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateLeaveStartDate,parentApplication.dateFormat);
			dateLeaveEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateLeaveEndDate,parentApplication.dateFormat);			
			comboLeaveReplacement.text	=	dgList.selectedItem.replacement;
			comboLeaveType.selectedIndex	=	parentApplication.getComboIndex(comboLeaveType.dataProvider,'data',dgList.selectedItem.leave_type_id);
			
			if(dgList.selectedItem.half_day_from=='1')
				chkHalfStart.selected=true;
			else
				chkHalfStart.selected=false;
			if(dgList.selectedItem.half_day_until=='1')
				chkHalfEnd.selected=true;
			else
				chkHalfEnd.selected=false;
			
			calculateLeaveDays();
			currentLeaveDays.text = dgList.selectedItem.leave_days;
		}
	
		override protected function setButtonState():void{

	  		super.setButtonState();	  			
			adjustLeaveDays.enabled = inputEnabled && parentApplication.isAdmin();
	  }
	
        protected function displayPopUpLeaveType(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboLeaveType.selectedIndex > 0) || (!editMode)){
				var pop1:popUpLeaveType = popUpLeaveType(PopUpManager.createPopUp(this, popUpLeaveType, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('leave_type',true,comboLeaveType.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('leave_type',true,comboLeaveType.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('leave_type',true);
				}
  			}
		}