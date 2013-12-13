// common code for qualificationClass and popupQualificationClass
		
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			txtAdminName.toolTip = "60 chars";
			txtAdminName.maxChars = 60;			
		}