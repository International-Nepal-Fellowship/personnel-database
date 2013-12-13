// common code for leavetypeClass and popupLeavetypeClass

		public var txtAdminEntitlement:TextInput;
		public var txtAdminCarryForward:TextInput;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.entitlement = txtAdminEntitlement.text;
 			parameters.carry_forward = txtAdminCarryForward.text;
 						
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminEntitlement.text	=	"0";
			txtAdminCarryForward.text	=	"0";			
		}

		override protected function setValues():void{
					
			super.setValues();
			txtAdminEntitlement.text	=	dgList.selectedItem.entitlement;
			txtAdminCarryForward.text	=	dgList.selectedItem.carry_forward;		
		}

		override protected function checkValid(inputObject:Object):void{
					
			super.checkValid(inputObject);
			
			if((!parentApplication.isZeroOrPositiveInteger(txtAdminCarryForward.text)) || (!parentApplication.isPositiveInteger(txtAdminEntitlement.text))){
				saveEnabled	= false;								
			}	
		}