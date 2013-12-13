// common code for tabTrainingClass and popupTrainingClass
import mx.controls.DataGrid;

		public var dateTrainingStartDate:DateField;
		public var dateTrainingEndDate:DateField;
		public var textTrainingNeeds:TextArea;
		public var textAspirations:TextArea;
		public var textDevelopment:TextArea;
		public var comboTrainingCourse:ComboBoxNew;
		public var btnGetTraining:Button;
		public var dgTrainingNeeds:DataGrid;
		
		[Bindable]public var enableDisableCheckBox:Boolean=false;
		[Bindable] private var today:Date = new Date(); 
		
		override protected function loadNonMandatoryFields():void{
			
         	//nonMandatoryDateFields = new ArrayCollection([{label:"Start Date", data:dateTrainingStartDate},{label:"End Date", data:dateTrainingEndDate}]);
       		nonMandatoryComboFields  = new ArrayCollection([{label:"Course", data:comboTrainingCourse}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Aspirations", data:textAspirations},{label:"Development", data:textDevelopment}]);
       
        }

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "course",data:"training"});
        	listSearchBy.push({label: "training_need",data:"training"});
        	listSearchBy.push({label: "career_aspirations",data:"training"});
        	listSearchBy.push({label: "career_development",data:"training"});
			//listSearchBy.push({label: "trainee",data:"personnel_training_needs"});//points to name_id        
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({fields: "course",data:"training"});
        	listSearchWhom.push({fields: "training_need",data:"training"});
        	listSearchWhom.push({fields: "career_aspirations",data:"training"});
        	listSearchWhom.push({fields: "career_development",data:"training"});
			//listSearchWhom.push({fields: "trainee",data:"personnel_training_needs"});//points to name_id
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
			comboTrainingCourse.dataProvider=parentApplication.getUserRequestNameResult().courses.course;
			dgTrainingNeeds.dataProvider=parentApplication.getUserRequestNameResult().trainingneeds.trainingneed;			
			chkShowAll.visible = false;
        } 

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if((comboTrainingCourse.selectedIndex == 0) && (dgTrainingNeeds.dataProvider.length == 0)){
				saveEnabled	= false;								
			}		
		}
		
		private function getTrainingNeedsID():String{
			
			var trainingNeedsID:String='';
			
			for each(var item:Object in dgTrainingNeeds.dataProvider){
         		
         		if(item.check==true)      			
         			trainingNeedsID+=item.id+',';
   			}
          	if(trainingNeedsID!='')
         	    trainingNeedsID=trainingNeedsID.slice( 0, -1 );//delete the last comma character     		    
		//	Alert.show(trainingNeedsID);
			return trainingNeedsID;
		}
		
		private function loadCheckBoxes():void{
		
		 	//first clear the previously set checkboxes
		 	clearCheckBoxes();
		 	
		 	if (dgList.selectedItem == null) return;
		 	
		 	textAspirations.text = dgList.selectedItem.career_aspirations;
	       	textDevelopment.text = dgList.selectedItem.career_development;
	       	
		 	if (dgList.selectedItem.training_need_id == null) return;
		 	
			var userNeedsId:String=(dgList.selectedItem.training_need_id).toString();
			var selectedIDs:Array=userNeedsId.split(',');
			
		///check if a item.id is present in selectedIDs array	
	
		 	for each(var item:Object in dgTrainingNeeds.dataProvider){   
		//	Alert.show('loadcheckbox: '+item.id+'\n selected: '+dgList.selectedItem.training_needs);   			      		
	        	var checkedID:String=(item.id).toString();
	        	if((selectedIDs.indexOf(checkedID))>=0){ //if item is in array   			
	        		//strPublicSearchIDs+=item.id+',';
	        		item.check=true;
	        	}
	        	else{
	        		item.check=false;
	        	}         		      		
	        }// end for         	
		   		
		   		//if(strPublicSearchIDs!='')
		   		//	strPublicSearchIDs=strPublicSearchIDs.slice( 0, -1 ); // delete the last comma character	
	    }
	   
	   	private function clearCheckBoxes():void{

	   		for each(var item:Object in dgTrainingNeeds.dataProvider)      		
	       		item.check=false;
	       	textAspirations.text = "";
	       	textDevelopment.text = "";	         		
		} 
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.course_id		=	comboTrainingCourse.value;
			//parameters.needs			=	textTrainingNeeds.text;
			parameters.trainingNeeds	=	getTrainingNeedsID();
			parameters.aspirations		=	textAspirations.text;
			parameters.development		=	textDevelopment.text;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.training_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboTrainingCourse,comboTrainingCourse.text);
			comboTrainingCourse.dataProvider=parentApplication.getUserRequestNameResult().courses.course;
			parentApplication.setComboData(comboTrainingCourse,nameIndex);
			
			dgTrainingNeeds.dataProvider=parentApplication.getUserRequestNameResult().trainingneeds.trainingneed;
			loadCheckBoxes();
			
			if (modeState != "View") {
				this.focusManager.setFocus(comboTrainingCourse);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}  

        override protected function refresh():void{
				
			super.refresh();			
			comboTrainingCourse.selectedIndex=0;
			dateTrainingStartDate.text="";
			dateTrainingEndDate.text="";
			//textTrainingNeeds.text	=	"";//parentApplication.getFamilyDetailsResult().need;
			loadCheckBoxes();
		}

		override protected function setValues():void{

			super.setValues();

         	dateTrainingStartDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_from,dateTrainingStartDate,parentApplication.dateFormat);
			dateTrainingEndDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.date_until,dateTrainingEndDate,parentApplication.dateFormat);			
			comboTrainingCourse.selectedIndex	=	parentApplication.getComboIndex(comboTrainingCourse.dataProvider,'data',dgList.selectedItem.course_id);
			
			//textTrainingNeeds.text	=	parentApplication.getFamilyDetailsResult().need;
			loadCheckBoxes();
		}

        protected function displayPopUpCourse(strTitle:String,editMode:Boolean=false):void{		
		
			if((comboTrainingCourse.selectedIndex > 0) || (!editMode)){
				var pop1:popUpCourses = popUpCourses(PopUpManager.createPopUp(this, popUpCourses, true));
				PopUpManager.centerPopUp(pop1);
				pop1.showCloseButton=true;
				if (editMode){
					if (editAdminEnabled && inputEnabled){
						pop1.title="Edit "+strTitle;
						pop1.setup('course',true,comboTrainingCourse.text,true);
					}
					else {
						pop1.title="View "+strTitle;
						pop1.setup('course',true,comboTrainingCourse.text,false);
					}
				}
				else
				{
					pop1.title="Add New "+strTitle;
					pop1.initialise('course',true);
				}
  			}
		}
		
        protected function displayPopUpNeeds(strTitle:String,editMode:Boolean=false):void{		
		
			if (dgTrainingNeeds.selectedIndex == -1) {
				displayAdminPopUp(strTitle,'None','personnel_training_needs',editMode);
			}
			else {
				displayAdminPopUp(strTitle,dgTrainingNeeds.selectedItem.name,'personnel_training_needs',editMode);
			}
		}

		protected function displayPopUpDoc():void{		
			
			if (dgList.selectedItem == null) return;
			
			var newDoc:Boolean = (dgList.selectedItem.training_doc_id == 0);
			displayDocPopUp('Training Document','training_doc_id','training_notes', newDoc);
		}    
		
		override protected function enableDisableDocumentButton():void{
		
			var rowCount:int=dgList.dataProvider.length;
		//Alert.show(rowCount.toString());			
			if(modeState=='Edit') {
				btnGetTraining.visible=true;
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 
				btnGetTraining.visible=(dgList.selectedItem.training_doc_id>0)?true:false;
			}
			else{
				btnGetTraining.visible=false;
			}			
		}
	
		override protected function setButtonState():void{
		
			super.setButtonState();
			enableDisableCheckBox=inputEnabled;
		}	