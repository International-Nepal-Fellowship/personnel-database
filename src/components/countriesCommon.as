// common code for countriesClass and popupCountriesClass

		public var txtAdminISTDCode:TextInput;
		public var txtAdminCurrency:TextInput;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.istd_code = txtAdminISTDCode.text;
 			parameters.cur = txtAdminCurrency.text;
 						
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminISTDCode.text	=	"0";
			txtAdminCurrency.text	=	"";			
		}

		override protected function setValues():void{
					
			super.setValues();
			txtAdminISTDCode.text	=	dgList.selectedItem.istd_code;
			txtAdminCurrency.text	=	dgList.selectedItem.currency;		
		}

		override protected function checkValid(inputObject:Object):void{
					
			super.checkValid(inputObject);
			
			if(!parentApplication.isZeroOrPositiveInteger(txtAdminISTDCode.text)){
				saveEnabled	= false;								
			}	
		}