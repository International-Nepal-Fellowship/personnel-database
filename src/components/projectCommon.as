// common code for projectClass and popupProjectClass

		public var comboAdminType:ComboBoxNew;
		public var comboAdminProgramme:ComboBoxNew;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.programme_id = comboAdminProgramme.value;
 			parameters.type = comboAdminType.text;				
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			comboAdminProgramme.selectedIndex	=	0;	
			comboAdminType.selectedIndex	=	0;
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboAdminProgramme,comboAdminProgramme.text);			
			comboAdminProgramme.dataProvider	=  parentApplication.getUserRequestNameResult().programmes.programme;
			parentApplication.setComboData(comboAdminProgramme,nameIndex);
		}
		
		override protected function setValues():void{
					
			super.setValues();
			//parentApplication.setComboData(comboAdminProgramme,dgList.selectedItem.programme_id);
			comboAdminProgramme.selectedItem	=	dgList.selectedItem.programme_id;
			comboAdminType.selectedItem	=	dgList.selectedItem.type;	
		}

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
						
			if(comboAdminProgramme.selectedIndex	==	0){
				saveEnabled	= false;								
			}
		}
		
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			comboAdminProgramme.dataProvider = parentApplication.getUserRequestNameResult().programmes.programme;
			comboAdminType.dataProvider = parentApplication.getUserRequestTypeResult().posttype;
		}
        
        protected function displayPopUpProgramme(strTitle:String):void{
        			
			var pop1:popUpWindow = popUpWindow(PopUpManager.createPopUp(this, popUpWindow, true));
			PopUpManager.centerPopUp(pop1);
			pop1.title="Add New "+strTitle;
            pop1.showCloseButton=true;
			pop1.initialise('programme',true);		
		}