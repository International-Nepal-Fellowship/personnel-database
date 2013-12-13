// common code for tabPhoneClass and popupPhoneClass
	
		public var chkCurrent:CheckBox;
		public var comboPhoneCountry:ComboBoxNew;
		public var comboPhoneShared:ComboBoxNew;
		public var comboPhoneFax:ComboBoxNew;
		public var comboPhoneMobile:ComboBoxNew;
		public var comboPhoneType:ComboBoxNew;
		public var textPhonePhone:TextInput;
		public var textPhoneExtension:TextInput;
		public var comboPhoneIstd:ComboBoxNew;
		public var textPhoneIstd:TextInput;
		private var popUpRequester:String='default';
		//public var btnDelete:Button;
			
        override protected function pushSearchByVariables():void{      	
        	    
        	listSearchBy.push({ label: "phone",data:"phone"});
        	listSearchBy.push({ label: "include_all",data:"phone"}); 
        	listSearchBy.push({ label: "include_family",data:"phone"});     
        	listSearchBy.push({ label: "type",data:"phone"});            
        	listSearchBy.push({ label: "extn",data:"phone"});
        	listSearchBy.push({ label: "shared",data:"phone"});
        	listSearchBy.push({ label: "fax",data:"phone"});
        	listSearchBy.push({ label: "mobile",data:"phone"});
        	listSearchBy.push({ label: "country",data:"phone"});   //points to country_id
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();        	    
        	listSearchWhom.push({ fields: "phone",data:"phone"}); 
        	listSearchWhom.push({ fields: "include_all",data:"phone"});
        	listSearchWhom.push({ fields: "include_family",data:"phone"});   
        	listSearchWhom.push({ fields: "type",data:"phone"});    
        	listSearchWhom.push({ fields: "extn",data:"phone"});
        	listSearchWhom.push({ fields: "shared",data:"phone"});
        	listSearchWhom.push({ fields: "fax",data:"phone"});
        	listSearchWhom.push({ fields: "mobile",data:"phone"});
        	listSearchWhom.push({ fields: "country",data:"phone"});   //points to country_id
        }

        override protected function loadNonMandatoryFields():void{
         	//nonMandatoryComboFields = new ArrayCollection([{label:"Country", data:comboPhoneCountry}, {label:"Type", data:comboPhoneType}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Extension", data:textPhoneExtension}]);
           }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
            comboPhoneType.dataProvider	=	parentApplication.getUserRequestTypeResult().type;
       		comboPhoneShared.dataProvider	=	parentApplication.getUserRequestTypeResult().sharedphone;
       		comboPhoneFax.dataProvider	=	parentApplication.getUserRequestTypeResult().faxphone;
       		comboPhoneMobile.dataProvider	=	parentApplication.getUserRequestTypeResult().mobilephone;
       		comboPhoneCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;	
       		comboPhoneIstd.dataProvider		=	parentApplication.getUserRequestNameResult().istd_codes.country;
       		updateIstdCode(comboPhoneCountry);//set the istd code in invisible testfield
        } 
        	
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
		
			parameters.type	=	comboPhoneType.text;
			parameters.phone	= textPhonePhone.text;
			parameters.extension	=	textPhoneExtension.text;
			parameters.shared	= comboPhoneShared.text;
			parameters.fax	= comboPhoneFax.text;
			parameters.mobile	= comboPhoneMobile.text;
			
			parameters.countryID	= comboPhoneCountry.value;
			parameters.current	=	chkCurrent.selected;
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.phone_timestamp;
				//Alert.show(parameters.timestamp);
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
			
			var nameIndex:int = parentApplication.getComboData(comboPhoneCountry,comboPhoneCountry.text);			
			comboPhoneCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboPhoneCountry,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboPhoneIstd,comboPhoneIstd.text);			
			comboPhoneIstd.dataProvider		=	parentApplication.getUserRequestNameResult().istd_codes.country;
       		parentApplication.setComboData(comboPhoneIstd,nameIndex);
				
			if (modeState != "View") {
				this.focusManager.setFocus(comboPhoneType);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		        
        override protected function refresh():void{
				
			super.refresh();
			textPhoneExtension.text	=	"";
			textPhonePhone.text	=	"";
			comboPhoneCountry.selectedIndex	=	0;
			comboPhoneType.selectedIndex	=	0;
			comboPhoneShared.selectedIndex	=	0;
			comboPhoneFax.selectedIndex	=	0;
			comboPhoneMobile.selectedIndex	=	0;
			chkCurrent.selected = false;			
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
						
			if(textPhonePhone.text	==	""){
				saveEnabled	= false;								
			}
			if(comboPhoneCountry.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if((comboPhoneType.selectedIndex	==	0)&&(comboPhoneType.text	!= "Organisation")&&(comboPhoneType.text	!= "Location")){
				saveEnabled	= false;								
			}
		}

		override protected function setValues():void{

			super.setValues();	
			textPhonePhone.text	=	dgList.selectedItem.phone;
			textPhoneExtension.text	=	dgList.selectedItem.extn;
			comboPhoneType.selectedItem	=	dgList.selectedItem.type;
			comboPhoneShared.text	=	dgList.selectedItem.shared;
			comboPhoneFax.text	=	dgList.selectedItem.fax;
			comboPhoneMobile.text	=	dgList.selectedItem.mobile;
			comboPhoneCountry.selectedItem = dgList.selectedItem.country_id;
			chkCurrent.selected = dgList.selectedItem.current;
		}

		protected function displayPopUpCountry(strTitle:String,editMode:Boolean=false):void{		
		
			displayCountryPopUp(strTitle,comboPhoneCountry,editMode);
		}
		
		override protected function extraInitialise():void{
			
			if (popupMode) { // hide current checkbox
				chkCurrent.visible = false;
				if (parentCaller != "") {
					comboPhoneType.text=parentCaller;
					comboPhoneType.enabled=false;
					popUpRequester=parentCaller.toLowerCase();
				}
				else {
					popUpRequester="phone";
				}
			}
		}
		
		override protected function loadCopyToClipBoardFields():void{
	 		copyTabFields  = new ArrayCollection([{label:"Istd Code",data:textPhoneIstd}]);
	 	}

	 	public function updateIstdCode(comboCountry:Object):void{	

	 		comboPhoneIstd.selectedItem=comboCountry.text;	 		
	 		if(parentApplication.getComboData(comboPhoneIstd,comboCountry.text)!= -1)
	 			textPhoneIstd.text="("+parentApplication.getComboData(comboPhoneIstd,comboCountry.text).toString()+")";
	 		else
	 			textPhoneIstd.text="";	 		
	 		
	 		textPhoneIstd.text=textPhoneIstd.text+textPhonePhone.text;
	 		
	 		if (textPhoneExtension.text != "")
	 			textPhoneIstd.text=textPhoneIstd.text+" x "+textPhoneExtension.text;
		}
