// common code for coursesClass and popupCoursesClass

		public var txtAdminDescription:TextArea;
		public var dateAdminStartDate:DateField;
		public var dateAdminEndDate:DateField;
		
		[Bindable] private var today:Date = new Date();
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.description = txtAdminDescription.text;
 			parameters.date_from = DateUtils.dateFieldToString(dateAdminStartDate,parentApplication.dateFormat);
 			parameters.date_until = DateUtils.dateFieldToString(dateAdminEndDate,parentApplication.dateFormat);
 						
			return parameters;			
		}
		   		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminDescription.text	=	"";
			dateAdminStartDate.text = DateUtils.dateToString(today,dateAdminStartDate.formatString);
			dateAdminEndDate.text = DateUtils.dateToString(today,dateAdminEndDate.formatString);			
		}

		override protected function setValues():void{
					
			super.setValues();
			txtAdminDescription.text	=	dgList.selectedItem.description;
			dateAdminStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateAdminStartDate,parentApplication.dateFormat);
			dateAdminEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateAdminEndDate,parentApplication.dateFormat);
		}

		override protected function checkValid(inputObject:Object):void{
					
			super.checkValid(inputObject);
										
			if((dateAdminStartDate.text	==	"") || (dateAdminEndDate.text	==	"")){
				saveEnabled	= false;								
			}
			if(DateUtils.compareDateFieldDates(dateAdminStartDate, dateAdminEndDate) == 1) {
				saveEnabled = false;
			}
			if(txtAdminDescription.text	==	""){
				saveEnabled	= false;								
			}	
		}   	