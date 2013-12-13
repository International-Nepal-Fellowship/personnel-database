// common code for coursesClass and popupCoursesClass
import mx.controls.Button;

		public var comboAdminType:ComboBoxNew;
		public var comboAdminSubject:ComboBoxNew;
		public var txtAdminLocation:TextInput;
		public var txtAdminProvider:TextInput;
		public var chkAdminFixedDates:CheckBox;
		public var dateAdminStartDate:DateField;
		public var dateAdminEndDate:DateField;
		public var btnCourseDetails:Button;
		
		[Bindable] private var today:Date = new Date();
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.course_type_id = comboAdminType.value;
 			parameters.course_subject_id = comboAdminSubject.value;
 			parameters.location = txtAdminLocation.text;
 			parameters.provider = txtAdminProvider.text;
 			parameters.date_from = DateUtils.dateFieldToString(dateAdminStartDate,parentApplication.dateFormat);
 			parameters.date_until = DateUtils.dateFieldToString(dateAdminEndDate,parentApplication.dateFormat);
 			parameters.dates_fixed = (chkAdminFixedDates.selected)?"Yes":"No";
 						
			return parameters;			
		}

		override public function refreshData():void{

			super.refreshData();
			var nameIndex:int = parentApplication.getComboData(comboAdminType,comboAdminType.text);			
			comboAdminType.dataProvider	=  parentApplication.getUserRequestNameResult().coursetypes.coursetype;
			parentApplication.setComboData(comboAdminType,nameIndex);
			
			nameIndex= parentApplication.getComboData(comboAdminSubject,comboAdminSubject.text);			
			comboAdminSubject.dataProvider	=  parentApplication.getUserRequestNameResult().course_subjects.subject;	
			parentApplication.setComboData(comboAdminSubject,nameIndex);
		}
		   		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminLocation.text	=	"";
			txtAdminProvider.text	=	"";
			comboAdminType.selectedIndex	=	0;
			comboAdminSubject.selectedIndex	=	0;
			chkAdminFixedDates.selected = false;
			dateAdminStartDate.text = DateUtils.dateToString(today,dateAdminStartDate.formatString);
			dateAdminEndDate.text = DateUtils.dateToString(today,dateAdminEndDate.formatString);			
		}

		override protected function setValues():void{
					
			super.setValues();
			txtAdminLocation.text	=	dgList.selectedItem.location;
			txtAdminProvider.text	=	dgList.selectedItem.provider;
			dateAdminStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateAdminStartDate,parentApplication.dateFormat);
			dateAdminEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateAdminEndDate,parentApplication.dateFormat);
			chkAdminFixedDates.selected = (dgList.selectedItem.dates_fixed == "Yes");
			comboAdminType.selectedItem	=	dgList.selectedItem.course_type_id;	
			comboAdminSubject.selectedItem	=	dgList.selectedItem.course_subject_id;		
		}
		
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			comboAdminType.dataProvider = parentApplication.getUserRequestNameResult().coursetypes.coursetype;
			comboAdminSubject.dataProvider = parentApplication.getUserRequestNameResult().course_subjects.subject;	
		}

		override protected function checkValid(inputObject:Object):void{
					
			super.checkValid(inputObject);

			if(comboAdminType.selectedIndex	==	0){
				saveEnabled	= false;								
			}	
			if(comboAdminSubject.selectedIndex	==	0){
				saveEnabled	= false;								
			}			
			if((dateAdminStartDate.text	==	"") || (dateAdminEndDate.text	==	"")){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateAdminStartDate, dateAdminEndDate) == 1) {
				saveEnabled = false;
			}
			if((txtAdminLocation.text	==	"") || (txtAdminProvider.text	==	"")){
				saveEnabled	= false;								
			}	
		}
        
        protected function displayPopUpCourseType(strTitle:String):void{
        			
			var pop1:popUpWindow = popUpWindow(PopUpManager.createPopUp(this, popUpWindow, true));
			PopUpManager.centerPopUp(pop1);
			pop1.title="Add New "+strTitle;
            pop1.showCloseButton=true;
			pop1.initialise('course_type',true);		
		}
		
		protected function displayPopUpDoc():void{		
				
			var pop1:popupDocuments = popupDocuments(PopUpManager.createPopUp(this, popupDocuments, true));
			PopUpManager.centerPopUp(pop1);
			pop1.showCloseButton=true;		
			pop1.title="Course Notes";					
			var docID:int = dgList.selectedItem.__id;
			pop1.pre_init('course_notes',true,modeState,docID,'notes_id');
		}    
					
		override protected function enableDisableDocumentButton():void{
			
			var rowCount:int=dgList.dataProvider.length;
			
			if(modeState=='Edit') {				
				btnCourseDetails.visible=true;							
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				
				 if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 			
				 btnCourseDetails.visible=(dgList.selectedItem.notes_id>0)?true:false;					 
			}
			else { 				
				 btnCourseDetails.visible=false;				
			}
		}   	