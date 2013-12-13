// common code for hospitalClass and popupHospitalClass

		public var txtAdminType:TextInput;
		public var comboAdminCountry:ComboBoxNew;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.type = txtAdminType.text;
 			parameters.country_id = comboAdminCountry.value;
 						
			return parameters;			
		}

		override public function refreshData():void{

			super.refreshData();
			var nameIndex:int = parentApplication.getComboData(comboAdminCountry,comboAdminCountry.text);			
			comboAdminCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboAdminCountry,nameIndex);
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminType.text	=	"";
			comboAdminCountry.selectedIndex	=	0;			
		}

		override protected function setValues():void{
					
			super.setValues();
			txtAdminType.text	=	dgList.selectedItem.type;
			comboAdminCountry.selectedItem	=	dgList.selectedItem.country_id;		
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
						
			if(comboAdminCountry.selectedIndex	==	0){
				saveEnabled	= false;								
			}
		}

		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			comboAdminCountry.dataProvider = parentApplication.getUserRequestNameResult().countries.country;	
		}
		
		protected function displayPopUpCountry(strTitle:String):void{		
		
			var pop1:popUpCountries = popUpCountries(PopUpManager.createPopUp(this,popUpCountries, true));
			PopUpManager.centerPopUp(pop1);
			pop1.title="Add New "+strTitle;
            pop1.showCloseButton=true;
            pop1.initialise('country',true);
		}