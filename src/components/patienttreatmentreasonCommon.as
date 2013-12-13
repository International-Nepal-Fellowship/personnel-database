
		public var comboAdminType:ComboBoxNew;
		public var comboAdminMain:ComboBoxNew;
		public var comboAdminTreatmentCategory:ComboBoxNew;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();	
			parameters.treatmentCategoryID = comboAdminTreatmentCategory.value;
 			parameters.main = comboAdminMain.text;	
 			parameters.type = comboAdminType.text;				
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			comboAdminTreatmentCategory.selectedIndex	=	0;	
			comboAdminMain.selectedIndex	=	1; //default to No
			comboAdminType.selectedIndex	=	0;
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
			comboAdminMain.selectedItem	=	dgList.selectedItem.main;
			comboAdminType.selectedItem	=	dgList.selectedItem.type;			
			parentApplication.setComboData(comboAdminTreatmentCategory,dgList.selectedItem.treatment_category_id);
	
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
			comboAdminMain.dataProvider = parentApplication.getUserRequestTypeResult().treatmentreasonmain;
			comboAdminType.dataProvider = parentApplication.getUserRequestTypeResult().treatmentreasontype;

		}
        
        