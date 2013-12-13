// common code for leavetypeClass and popupLeavetypeClass

		public var txtAdminDevelopment:TextInput;
		public var txtAdminAspirations:TextInput;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.development = txtAdminDevelopment.text;
 			parameters.aspirations = txtAdminAspirations.text;
 						
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminDevelopment.text	=	"";
			txtAdminAspirations.text	=	"";			
		}

		override protected function setValues():void{
					
			super.setValues();
			txtAdminDevelopment.text	=	dgList.selectedItem.career_development;
			txtAdminAspirations.text	=	dgList.selectedItem.career_aspirations;		
		}

	