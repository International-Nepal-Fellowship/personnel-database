// common code for tabAddressClass and popupAddressClass
	
		public var chkCurrent:CheckBox;
		public var comboAddressCountry:ComboBoxNew;
		public var comboAddressType:ComboBoxNew;
		public var textAddressCityTown:TextInput;
		public var textAddressPostCode:TextInput;
		public var textAddressLatitude:TextInput;
		public var textAddressLongitude:TextInput;
		public var textAddressAddress:TextArea;
		public var textAddressStateProvince:TextInput;
		private var popUpRequester:String='default';
		
		override protected function loadCopyToClipBoardFields():void{
	 		copyTabFields  = new ArrayCollection([
			{label:"Address", data:textAddressAddress},{label:"City/Town",data:textAddressCityTown},
			{label:"State/District",data:textAddressStateProvince},
			{label:"Post Code / Zip", data:textAddressPostCode},{label:"Country",data:comboAddressCountry},
			//{label:"Latitude", data:textAddressLatitude},{label:"Longitude", data:textAddressLongitude}			
			 ]);
	 	}
		
        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({ label: "address",data:"address"});  
        	listSearchBy.push({ label: "include_all",data:"address"}); 
        	listSearchBy.push({ label: "include_family",data:"address"});    
        	listSearchBy.push({ label: "city_town",data:"address"});
        	listSearchBy.push({ label: "state_province",data:"address"});	
        	listSearchBy.push({ label: "type",data:"address"});     
        	listSearchBy.push({ label: "postcode_zip",data:"address"});
        	listSearchBy.push({ label: "country",data:"address"});   //points to country_id
        	listSearchBy.push({ label: "latitude",data:"address"});
        	listSearchBy.push({ label: "longitude",data:"address"});
        	
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "address",data:"address"}); 
        	listSearchWhom.push({ fields: "include_all",data:"address"});
        	listSearchWhom.push({ fields: "include_family",data:"address"});   
        	listSearchWhom.push({ fields: "city_town",data:"address"});
        	listSearchWhom.push({ fields: "state_province",data:"address"});	
        	listSearchWhom.push({ fields: "type",data:"address"});     
        	listSearchWhom.push({ fields: "postcode_zip",data:"address"});
        	listSearchWhom.push({ fields: "country",data:"address"}); //points to country_id
        	listSearchWhom.push({ fields: "latitude",data:"address"});
        	listSearchWhom.push({ fields: "longitude",data:"address"});	
        }

        override protected function loadNonMandatoryFields():void{
	// 		nonMandatoryComboFields = new ArrayCollection([{label:"Country", data:comboAddressCountry}, {label:"Type", data:comboAddressType}]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Address", data:textAddressAddress},{label:"Latitude", data:textAddressLatitude},{label:"Longitude", data:textAddressLongitude}, {label:"Post Code / Zip", data:textAddressPostCode}]);
 	 	}
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
		   	comboAddressCountry.dataProvider	=	parentApplication.getUserRequestNameResult().countries.country;
		   	comboAddressType.dataProvider	=	parentApplication.getUserRequestTypeResult().type; 	
        }
        
		override protected function setSendParameters():Object {

			//trace("address.setsendparameters()");
			var parameters:Object=super.setSendParameters();					
			
			parameters.type	=	comboAddressType.text;
			parameters.citytown	= textAddressCityTown.text;
			parameters.postcode	=	textAddressPostCode.text;
			parameters.latitude	= textAddressLatitude.text;
			parameters.longitude	=	textAddressLongitude.text;
			parameters.address	=	textAddressAddress.text;
			parameters.stateprovince	= textAddressStateProvince.text;
			parameters.countryID	=	comboAddressCountry.value;
			parameters.current	=	chkCurrent.selected;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.address_timestamp;
			}
			
			if (popupMode){
				parameters.popUpRequester	=	popUpRequester;			
				parameters.requestor		=	'popup.mxml';
			}								
			else
				parameters.requestor	=	'tab.mxml';
 	//Alert.show(popUpRequester);		
			return parameters;			
		}

		override public function refreshData():void{

			super.refreshData();
			var nameIndex:int = parentApplication.getComboData(comboAddressCountry,comboAddressCountry.text);			
			comboAddressCountry.dataProvider	=  parentApplication.getUserRequestNameResult().countries.country;
			parentApplication.setComboData(comboAddressCountry,nameIndex);
			
			if (modeState != "View") {
				this.focusManager.setFocus(comboAddressType);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}
		        
        override protected function refresh():void{
				
			super.refresh();
			textAddressLongitude.text	=	"";
			textAddressLatitude.text	=	"";
			textAddressPostCode.text	=	"";
			textAddressCityTown.text	=	"";
			textAddressStateProvince.text	=	"";
			textAddressAddress.text	=	"";
			comboAddressCountry.selectedIndex	=	0;
			comboAddressType.selectedIndex	=	0;
			chkCurrent.selected = false;			
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if((textAddressCityTown.text	==	"")||(textAddressStateProvince.text	==	"")){
				saveEnabled	= false;								
			}
			if(comboAddressCountry.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if((comboAddressType.selectedIndex	==	0)&&(comboAddressType.text	!= "Organisation")&&(comboAddressType.text	!= "Location")){
				saveEnabled	= false;								
			}
		}

		override protected function setValues():void{

			super.setValues();	
			
			textAddressPostCode.text	=	dgList.selectedItem.postcode_zip;
			textAddressLatitude.text	=	dgList.selectedItem.latitude;
			textAddressLongitude.text	=	dgList.selectedItem.longitude;
			//comboAddressCountry.selectedIndex	=	dgList.selectedItem.country;
			textAddressAddress.text	=	dgList.selectedItem.address;
			textAddressStateProvince.text	=	dgList.selectedItem.state_province;
			textAddressCityTown.text	=	dgList.selectedItem.city_town;
			comboAddressType.selectedItem		=	dgList.selectedItem.type;	
			comboAddressCountry.selectedItem	=	dgList.selectedItem.country_id;
			chkCurrent.selected = dgList.selectedItem.current;
		}
		
		protected function displayPopUpCountry(strTitle:String,editMode:Boolean=false):void{		
		
			displayCountryPopUp(strTitle,comboAddressCountry,editMode);
		}
		
		override protected function extraInitialise():void{
			
			if (popupMode) { // hide current checkbox
				chkCurrent.visible = false;
				if (parentCaller != "") {
					comboAddressType.text=parentCaller;
					comboAddressType.enabled=false;
					popUpRequester=parentCaller.toLowerCase();
				}
				else {
					popUpRequester="address";
				}
			}
		}