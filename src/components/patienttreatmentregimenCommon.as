
		public var comboPatientAdminCategory:ComboBoxNew;

		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.treatmentCategoryID= comboPatientAdminCategory.value;
 	
 						
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			comboPatientAdminCategory.selectedIndex	=	0;
		
		}
	/*	
		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboPatientAdminCategory,comboPatientAdminCategory.text);			
			comboPatientAdminCategory.dataProvider	=  parentApplication.getUserRequestNameResult().patienttreatmentcategories.patienttreatmentcategory;
			//comboPatientAdminCategory.dataProvider	=  parentApplication.getUserRequestNameResult().patienttreatmentcategories;
			
			//patienttreatmentcategories
			parentApplication.setComboData(comboPatientAdminCategory,nameIndex);
		}
*/
		override protected function setValues():void{
					
			super.setValues();			
			//comboPatientAdminCategory.selectedItem	=	dgList.selectedItem.treatment_category_id;
			parentApplication.setComboData(comboPatientAdminCategory,dgList.selectedItem.treatment_category_id);

		}

		override protected function checkValid(inputObject:Object):void{
					
			super.checkValid(inputObject);
			
			if(comboPatientAdminCategory.selectedIndex==0){
				saveEnabled	= false;								
			}	
		}
		
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			comboPatientAdminCategory.dataProvider = parentApplication.getUserRequestNameResult().patienttreatmentcategories.patienttreatmentcategory;
			//comboPatientAdminCategory.dataProvider	=  parentApplication.getUserRequestNameResult().patienttreatmentcategories;

		}
        