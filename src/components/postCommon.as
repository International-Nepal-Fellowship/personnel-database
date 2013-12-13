// common code for postClass and popupPostClass
import mx.containers.FormItem;

		public var txtAdminDesc:TextArea;
		public var comboAdminType:ComboBoxNew;
		public var comboAdminHours:ComboBoxNew;
		public var comboAdminStatus:ComboBoxNew;
		public var comboAdminProgramme:ComboBoxNew;
		public var comboAdminSection:ComboBoxNew;
		public var comboAdminUnit:ComboBoxNew;
		//public var comboAdminVisa:ComboBoxNew;
		public var txtAdminEmail:TextInput;
		public var comboAdminManager:ComboBoxNew;
		public var 	comboAdminJobReviewer:ComboBoxNew;
		public var 	comboAdminProvisional:ComboBoxNew;
		public var 	comboAdminActive:ComboBoxNew;
		
		public var 	comboAdminPeriod:ComboBoxNew;
		public var 	comboAdminPersonnelReviewer:ComboBoxNew;
		public var 	comboAdminMedicalReviewer:ComboBoxNew;
		public var  txtLabelEmail:FormItem;
		public var  comboLabelManager:FormItem;
		public var 	comboLabelJobReviewer:FormItem;
		public var 	comboLabelPersonnelReviewer:FormItem;
		public var 	comboLabelMedicalReviewer:FormItem;
		public var 	comboLabelPeriod:FormItem;
		private var postType:String;
		
		private var postPeriodID:int = 0;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

 			parameters.description = txtAdminDesc.text;
 			parameters.programme_id = comboAdminProgramme.value;
 			parameters.section_id = comboAdminSection.value;
 			parameters.unit_id = comboAdminUnit.value;
 			//parameters.visa_id = comboAdminVisa.value;
 			parameters.email = txtAdminEmail.text;
 			parameters.type = comboAdminType.text;
 			parameters.hours = comboAdminHours.text;
 			parameters.status = comboAdminStatus.text;
 			parameters.jobReviewer = comboAdminJobReviewer.value;
 			parameters.medicalReviewer = comboAdminMedicalReviewer.value;
 			parameters.personnelReviewer = comboAdminPersonnelReviewer.value;
 			parameters.provisional = comboAdminProvisional.value;
 			parameters.active = comboAdminActive.value;
 			parameters.managerID = comboAdminManager.value;
 			parameters.agreementID = comboAdminPeriod.value;
 	//	Alert.show(parameters.timestamp);				
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminDesc.text	=	"";
			comboAdminProgramme.selectedIndex	=	0;	
			//comboAdminVisa.selectedIndex	=	0;
			comboAdminSection.selectedIndex	=	0;
			comboAdminUnit.selectedIndex	=	0;
			txtAdminEmail.text	=	"";
			comboAdminType.selectedItem	=	postType;
			comboAdminHours.selectedIndex	=	0;
			comboAdminStatus.selectedIndex	=	0;	
			comboAdminManager.selectedIndex	=	0;	
			comboAdminJobReviewer.selectedIndex	=	0;
			comboAdminProvisional.selectedIndex	=	0;
			comboAdminActive.selectedIndex	=	1;
			comboAdminPersonnelReviewer.selectedIndex	=	0;
			comboAdminMedicalReviewer.selectedIndex	=	0;
			comboAdminPeriod.selectedIndex	=	0;	
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int;
			nameIndex = parentApplication.getComboData(comboAdminProgramme,comboAdminProgramme.text);			
			comboAdminProgramme.dataProvider	=  parentApplication.getUserRequestNameResult().programmes.programme;
			parentApplication.setComboData(comboAdminProgramme,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboAdminUnit,comboAdminUnit.text);	
			comboAdminUnit.dataProvider = parentApplication.getUserRequestNameResult().units.unit;
			parentApplication.setComboData(comboAdminUnit,nameIndex);
			nameIndex = parentApplication.getComboData(comboAdminSection,comboAdminSection.text);	
			comboAdminSection.dataProvider = parentApplication.getUserRequestNameResult().sections.section;
			parentApplication.setComboData(comboAdminSection,nameIndex);
			
			//nameIndex = parentApplication.getComboData(comboAdminVisa,comboAdminVisa.text);	
			//comboAdminVisa.dataProvider = parentApplication.getUserRequestNameResult().visaIdTitle.idTitle;
			//parentApplication.setComboData(comboAdminVisa,nameIndex);
			nameIndex = parentApplication.getComboData(comboAdminManager,comboAdminManager.text);	
			comboAdminManager.dataProvider =parentApplication.getUserRequestNameResult().jobManagers.jobManager;
			parentApplication.setComboData(comboAdminManager,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboAdminPeriod,comboAdminPeriod.text);	
			comboAdminPeriod.dataProvider =parentApplication.getUserRequestNameResult().agreements.agreement;
			parentApplication.setComboData(comboAdminPeriod,nameIndex);
		}
		
		override protected function setValues():void{
					
			super.setValues();
			txtAdminDesc.text	=	dgList.selectedItem.description;
			comboAdminProgramme.selectedItem	=	dgList.selectedItem.programme_id;
			//parentApplication.setComboData(comboAdminProgramme,dgList.selectedItem.programme_id);
			comboAdminSection.selectedItem	=	dgList.selectedItem.section_id;
			//parentApplication.setComboData(comboAdminUnit,dgList.selectedItem.unit_id);
			comboAdminUnit.selectedItem	=	dgList.selectedItem.unit_id;
			//parentApplication.setComboData(comboAdminSection,dgList.selectedItem.section_id)
			//comboAdminVisa.selectedItem	=	dgList.selectedItem.visa_id;
			txtAdminEmail.text	=	dgList.selectedItem.email;
			parentApplication.CheckValidEmail(txtAdminEmail);
			comboAdminType.selectedItem	=	dgList.selectedItem.type;	
			comboAdminHours.selectedItem	=	dgList.selectedItem.hours;
			comboAdminStatus.selectedItem	=	dgList.selectedItem.status;	
			comboAdminJobReviewer.selectedItem	=	dgList.selectedItem.job_reviewer;	
			comboAdminPersonnelReviewer.selectedItem	=	dgList.selectedItem.personnel_reviewer;
			comboAdminMedicalReviewer.selectedItem	=	dgList.selectedItem.medical_reviewer;	
			comboAdminProvisional.selectedItem	=	dgList.selectedItem.provisional;
			comboAdminActive.selectedItem	=	dgList.selectedItem.active;	
			comboAdminManager.selectedIndex	=	parentApplication.getComboIndex(comboAdminManager.dataProvider,'data',dgList.selectedItem.manager_id);
		//	comboEduSpecialityType.selectedIndex	=	parentApplication.getComboIndex(comboEduSpecialityType.dataProvider,'data',dgList.selectedItem.speciality_id);
			comboAdminPeriod.selectedIndex	=	parentApplication.getComboIndex(comboAdminPeriod.dataProvider,'data',dgList.selectedItem.agreement_id);
		}

		override protected function foundDuplicateName(txtName:String):Boolean{
			
			foundIndex = comboAdminName.dataProvider.source.indexOf(txtName);
			noDuplicate = true;
			
			while ((foundIndex != -1) && noDuplicate) {
				
				postPeriodID = dgList.dataProvider[foundIndex].agreement_id;
				if (modeState == 'Edit') { //ignore the current match
					if (foundIndex != comboAdminName.selectedIndex) {
						noDuplicate = (postPeriodID != comboAdminPeriod.value);
					}
				} else
				{
					noDuplicate = (postPeriodID != comboAdminPeriod.value);
				}
				trace("foundIndex = ("+foundIndex+") :"+txtName);
				foundIndex = comboAdminName.dataProvider.source.indexOf(txtName,foundIndex+1);
			}
			return !noDuplicate;
			
		}

		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
						
			if(comboAdminProgramme.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			/*
			if(comboAdminUnit.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(comboAdminSection.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			*/
			if(txtAdminDesc.text	==	""){
				saveEnabled	= false;								
			}			
			if(parentApplication.CheckValidEmail(txtAdminEmail)==false){	
                saveEnabled	= false;
            }
		}
		
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData(current);
			if (tableName=='visapost') {
				postType='Official';
			}
			else {
				postType='Internal';
			}
			comboAdminProgramme.dataProvider = parentApplication.getUserRequestNameResult().programmes.programme;
			comboAdminUnit.dataProvider = parentApplication.getUserRequestNameResult().units.unit;
			comboAdminSection.dataProvider = parentApplication.getUserRequestNameResult().sections.section;
			
			//comboAdminVisa.dataProvider = parentApplication.getUserRequestNameResult().visaIdTitle.idTitle;
			comboAdminManager.dataProvider =parentApplication.getUserRequestNameResult().jobManagers.jobManager;
			comboAdminType.dataProvider = parentApplication.getUserRequestTypeResult().posttype;
			comboAdminHours.dataProvider = parentApplication.getUserRequestTypeResult().posthour;
			comboAdminStatus.dataProvider = parentApplication.getUserRequestTypeResult().poststatus;
			comboAdminProvisional.dataProvider = parentApplication.getUserRequestTypeResult().postprovisional;
			comboAdminActive.dataProvider = parentApplication.getUserRequestTypeResult().postactive;
			comboAdminMedicalReviewer.dataProvider = parentApplication.getUserRequestTypeResult().postmedicalreviewer;
			comboAdminJobReviewer.dataProvider = parentApplication.getUserRequestTypeResult().postjobreviewer;
			comboAdminPersonnelReviewer.dataProvider = parentApplication.getUserRequestTypeResult().postpersonnelreviewer;	
			
			comboAdminPeriod.dataProvider =parentApplication.getUserRequestNameResult().agreements.agreement;
			
			txtLabelEmail.visible=(postType == 'Internal');
			comboLabelManager.visible=(postType == 'Internal');
			comboLabelMedicalReviewer.visible=(postType == 'Internal');
			comboLabelJobReviewer.visible=(postType == 'Internal');
			comboLabelPersonnelReviewer.visible=(postType == 'Internal');
			comboLabelPeriod.visible=(postType == 'Official');
			txtAdminEmail.visible=(postType == 'Internal');
			comboAdminManager.visible=(postType == 'Internal');
			comboAdminMedicalReviewer.visible=(postType == 'Internal');
			comboAdminJobReviewer.visible=(postType == 'Internal');
			comboAdminPersonnelReviewer.visible=(postType == 'Internal');
			comboAdminPeriod.visible=(postType == 'Official');
			
			txtAdminName.toolTip = "60 chars";
			txtAdminName.maxChars = 60;			
		}
        
        protected function displayPopUpProject(strTitle:String):void{
        			
			var pop1:popUpWindow = popUpWindow(PopUpManager.createPopUp(this, popUpWindow, true));
			PopUpManager.centerPopUp(pop1);
			pop1.title="Add New "+strTitle;
            pop1.showCloseButton=true;
			pop1.initialise('project',true);		
		}