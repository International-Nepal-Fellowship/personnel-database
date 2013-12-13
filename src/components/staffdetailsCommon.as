      
        public var comboHealthStaffType:ComboBoxNew;    
		
        override protected function loadData(current:Boolean=false):void{
        	
        	super.loadData();			
			comboHealthStaffType.dataProvider	=	parentApplication.getUserRequestNameResult().patienthealthstafftypes.patienthealthstafftype;	
           	chkShowAll.visible = false;       		
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "staff_type",data:"health_staff"});                	        			     
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "staff_type",data:"health_staff"});     
        }		

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
		
			parameters.staffTypeID	=	comboHealthStaffType.value;
		
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.health_staff_timestamp;				
			}	
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		} 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(comboHealthStaffType.selectedIndex == 0){
				saveEnabled	= false;
			}		
		}
		
		override public function refreshData():void{
			
			super.refreshData();	
			
			var nameIndex:int = parentApplication.getComboData(comboHealthStaffType,comboHealthStaffType.text);	
			comboHealthStaffType.dataProvider	=	parentApplication.getUserRequestNameResult().patienthealthstafftypes.patienthealthstafftype;	
           	parentApplication.setComboData(comboHealthStaffType,nameIndex);
           		
			if (modeState != "View") {
				this.focusManager.setFocus(comboHealthStaffType);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}		
		
        override protected function refresh():void{
				
			super.refresh();			
			comboHealthStaffType.selectedIndex=0;		
		}

		override protected function setValues():void{

			super.setValues();      
			comboHealthStaffType.selectedIndex	= parentApplication.getComboIndex(comboHealthStaffType.dataProvider,'data',dgList.selectedItem.health_staff_type_id);
		}
		

		protected function displayPopUpStaffType(strTitle:String,editMode:Boolean=false):void{		
			
			displayAdminPopUp(strTitle,comboHealthStaffType.text,'health_staff_type',editMode);
		}

/*	
		override protected function setButtonState():void{
			
			super.setButtonState();
			if(dgList.dataProvider.length>0)
				addEnabled=false;
			else
				addEnabled=true;
		}
*/

		override protected function extraInitialise():void{
			
			super.extraInitialise();

	    	if(dgList.dataProvider.length>0){
		    	btnAddNew.visible = false; // can't have more than one staff record per person
		    	btnEdit.visible = true;
		    }
		    else {
		    	btnAddNew.visible = true;
		    	btnEdit.visible = false;	    
		    }
		}		