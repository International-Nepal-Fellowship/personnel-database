// common code for tabEmailClass and popupEmailClass
		//import mx.controls.LinkButton;
	
		[Bindable]	public var chkCurrent:CheckBox;
		public var comboEmailType:ComboBoxNew;
		public var textEmailEmail:TextInput;
		public var textEmailOfficialEmail:TextInput;
		private var popUpRequester:String='default';
		[Bindable] public var emailButtonEnabled:Boolean=false;
		//[Bindable] public var officialEmailButtonEnabled :Boolean=false;
		//public var linkEmailEmail:LinkButton;
		//public var linkOfficialEmail:LinkButton;
		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
       		comboEmailType.dataProvider	=	parentApplication.getUserRequestTypeResult().type;	
        }
          
        override protected function pushSearchByVariables():void{
        	listSearchBy.push({ label: "email",data:"email"});        	  
        	listSearchBy.push({ label: "include_all",data:"email"}); 
        	listSearchBy.push({ label: "include_family",data:"email"}); 
        	listSearchBy.push({ label: "type",data:"email"});          
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "email",data:"email"});
        	listSearchWhom.push({ fields: "include_all",data:"email"});
        	listSearchWhom.push({ fields: "include_family",data:"email"});
        	listSearchWhom.push({ fields: "type",data:"email"});
        }
        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
		
			parameters.type	=	comboEmailType.text;
			parameters.email	= textEmailEmail.text;
			parameters.current	=	chkCurrent.selected;
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.email_timestamp;				
			}
			if (popupMode){
				parameters.popUpRequester	=	popUpRequester;			
				parameters.requestor		=	'popup.mxml';
			}
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;			
		}
		        
		override public function refreshData():void{

			super.refreshData();
			
			if (modeState != "View") {
				this.focusManager.setFocus(comboEmailType);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		
        override protected function refresh():void{
				
			super.refresh();
			
			textEmailEmail.text	=	"";
			//linkEmailEmail.label =  '';
			textEmailOfficialEmail.text	=	"";
		//	linkOfficialEmail.label=   '';
			comboEmailType.selectedIndex	=	0;
			chkCurrent.selected = false;				
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(textEmailEmail.text == '') {
				saveEnabled = false;
			}		
			if(parentApplication.CheckValidEmail(textEmailEmail)==false){
				saveEnabled	= false;								
			}
			if((comboEmailType.selectedIndex	==	0)&&(comboEmailType.text	!= "Organisation")&&(comboEmailType.text	!= "Location")){
				saveEnabled	= false;								
			}
		}

		override protected function setValues():void{

			super.setValues();
				
			textEmailEmail.text	=	dgList.selectedItem.email;
		//	linkEmailEmail.label =	dgList.selectedItem.email;
			//linkOfficialEmail.label= textEmailOfficialEmail.text;
			parentApplication.CheckValidEmail(textEmailEmail);
			comboEmailType.selectedItem	=	dgList.selectedItem.type;
			chkCurrent.selected = dgList.selectedItem.current;		
		}

		override protected function extraInitialise():void{
			
			if (popupMode) { // hide current checkbox
				chkCurrent.visible = false;
				if (parentCaller != "") {
					comboEmailType.text=parentCaller;
					comboEmailType.enabled=false;
					popUpRequester=parentCaller.toLowerCase();
				}
				else {
					popUpRequester="email";
				}
			}
		}
		
		override protected function loadCopyToClipBoardFields():void{
	 		copyTabFields  = new ArrayCollection([
	 		{label:"Email", data:textEmailEmail},
			{label:"Official Email", data:textEmailOfficialEmail}			
			 ]);
	 	}
	 	
	 	override protected function setButtonState():void{
			
			super.setButtonState();
			
			emailButtonEnabled = ((parentApplication.getAppMode() == "View") && (parentApplication.patientSystem == false));			
	 	}