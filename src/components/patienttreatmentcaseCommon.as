

		public var comboAdminPositive:ComboBoxNew;
		public var comboAdminTreatmentCategory:ComboBoxNew;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.treatmentCategoryID = comboAdminTreatmentCategory.value;
 			parameters.positive = comboAdminPositive.text;				
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			comboAdminTreatmentCategory.selectedIndex	=	0;	
			comboAdminPositive.selectedIndex	=	0;
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboAdminTreatmentCategory,comboAdminTreatmentCategory.text);			
			comboAdminTreatmentCategory.dataProvider	=  parentApplication.getUserRequestNameResult().patienttreatmentcategories.patienttreatmentcategory;
			parentApplication.setComboData(comboAdminTreatmentCategory,nameIndex);
		}
		
		override protected function setValues():void{
					
			super.setValues();
			//parentApplication.setComboData(comboAdminTreatmentCategory,dgList.selectedItem.programme_id);
			//comboAdminTreatmentCategory.selectedItem	=	dgList.selectedItem.treatment_category_id;
			comboAdminPositive.selectedItem	=	dgList.selectedItem.positive;	
			parentApplication.setComboData(comboAdminTreatmentCategory,dgList.selectedItem.treatment_category_id);
	//	Alert.show('setvalues () :'+dgList.selectedItem.treatment_category_id);
		}

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
						
			if(comboAdminTreatmentCategory.selectedIndex	==	0){
				saveEnabled	= false;								
			}
		}
		
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			comboAdminTreatmentCategory.dataProvider = parentApplication.getUserRequestNameResult().patienttreatmentcategories.patienttreatmentcategory;
			comboAdminPositive.dataProvider = parentApplication.getUserRequestTypeResult().treatmentcasepositive;
		}
        
        