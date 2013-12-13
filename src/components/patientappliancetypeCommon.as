
		public var txtPatientAdminType:TextInput;
		//public var txtAdminCurrency:TextInput;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.type = txtPatientAdminType.text;
 		
 						
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtPatientAdminType.text	=	"";
			
		}

		override protected function setValues():void{
					
			super.setValues();
			txtPatientAdminType.text	=	dgList.selectedItem.type;
	
		}

		override protected function checkValid(inputObject:Object):void{
					
			super.checkValid(inputObject);
			
			if(txtPatientAdminType.text==''){
				saveEnabled	= false;								
			}	
		}