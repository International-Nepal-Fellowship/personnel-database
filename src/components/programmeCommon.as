// common code for servicelocationClass and popupLocationClass
	
		public var txtAdminCode:TextInput;
				
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
 			parameters.code 		= 	txtAdminCode.text;						
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminCode.text	=	"";			
		}

		override protected function setValues():void{
					
			super.setValues();
			
			txtAdminCode.text	=	dgList.selectedItem.code;	
		}		