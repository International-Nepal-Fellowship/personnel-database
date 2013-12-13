// common code for tabOrientationClass
import mx.controls.DateField;

	    [Bindable]public var showDatagrid:Boolean;
	    
		public var dateEmailAddressRequested:DateField;
		public var dateEmailAddressCreated:DateField;
		public var dateEmailInstalled:DateField;
		public var dateLOTDurationDiscussed:DateField;
		public var dateLOTRequested:DateField;
		public var dateLOTConfirmed:DateField;
		//public var dateLOTDetails:DateField;
		public var dateKTMLOTScheduled:DateField;
		public var dateKTMLOTConfirmed:DateField;
		public var dateDatesOfKTMLOT:DateField;
		public var dateHousingPreferences:DateField;
		public var dateHousingRequested:DateField;
		public var datePKRLOTHousingArranged:DateField;
		//public var datePKRLOTHousingDetails:DateField;
	   
		public var dateHousingArranged:DateField;
		public var dateHousingConfirmed:DateField;
		//public var dateHousingDetails:DateField;
		
		public var dateLinkNamesSent:DateField;
		/*
		public var dateDate:DateField;
		public var txtInputTime:TextInput;
		public var txtArrivalFlight:TextInput;
		public var datePickUpArranged:DateField;
		public var dateAccoArranged:DateField;
		//public var dateAccoDetails:DateField;
		public var dateBusTicketArranged:DateField;
		public var dateTicketInfoSentToPKR:DateField;
		public var dateSurvivalOrientationBooklet:DateField;
		public var dateWelcomePack:DateField;
		public var dateWelcomeLetter:DateField;
		*/
		public var btnLOTConfirmed:Button;
		//public var btnAccoArranged:Button;
		public var btnPKRLOTHousingArranged:Button;
		public var btnHousingConfirmed:Button;
		
		public var comboHousingLinkPerson:ComboBoxNew;
		public var comboKtmLinkPerson:ComboBoxNew;
		public var comboPkrLinkPerson:ComboBoxNew;
		public var comboSchoolChildrenLinkPerson:ComboBoxNew;
		public var comboWorkLinkPerson:ComboBoxNew;
				
		
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([{label:"Email Address Requested", data:dateEmailAddressRequested},
         	{label:"Email Address Created",data:dateEmailAddressCreated}, {label:"Email Installed",data:dateEmailInstalled} ,
         	 {label:"LOT Duration Discussed", data:dateLOTDurationDiscussed},{label:"LOT Requested",data:dateLOTRequested},
         	 {label:"LOT Confirmed",data:dateLOTConfirmed },
         	 {label:"KTMLOT Scheduled",data:dateKTMLOTScheduled },{label:"KTMLO Confirmed",data:dateKTMLOTConfirmed },
         	 {label:"Dates Of KTMLOT",data:dateDatesOfKTMLOT },{label:"Housing Preferences",data:dateHousingPreferences },
         	 {label:"PKRLOT Housing Arranged",data:datePKRLOTHousingArranged },
         	 {label:"Housing Requested",data:dateHousingRequested },{label:"Housing Arranged ",data:dateHousingArranged },
         	 {label:"Housing Confirmed",data:dateHousingConfirmed },
         	 /*
         	 {label:"Date",data:dateDate },
			 {label:"Pick Up Arranged",data:datePickUpArranged },{label:"Acco Arranged",data:dateAccoArranged },
			 {label:"Bus Ticket Arranged",data:dateBusTicketArranged },
         	 {label:"Ticket Info Sent To PKR",data:dateTicketInfoSentToPKR },
         	 {label:"Survival Orientation Booklet",data:dateSurvivalOrientationBooklet },
         	 {label:"Welcome Pack",data:dateWelcomePack },{label:"Welcome Letter",data:dateWelcomeLetter },
         	 */
         	 ]);
        }
        
        //load non mandatory fields for alerting if they are left empty while filling forms
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([{label:"Email Address Requested", data:dateEmailAddressRequested},
         	{label:"Email Address Created",data:dateEmailAddressCreated}, {label:"Email Installed",data:dateEmailInstalled} ,
         	 {label:"LOT Duration Discussed", data:dateLOTDurationDiscussed},{label:"LOT Requested",data:dateLOTRequested},
         	 {label:"LOT Confirmed",data:dateLOTConfirmed },
         	 {label:"KTMLOT Scheduled",data:dateKTMLOTScheduled },{label:"KTMLO Confirmed",data:dateKTMLOTConfirmed },
         	 {label:"Dates Of KTMLOT",data:dateDatesOfKTMLOT },{label:"Housing Preferences",data:dateHousingPreferences },
         	 {label:"PKRLOT Housing Arranged",data:datePKRLOTHousingArranged },
         	 {label:"Housing Requested",data:dateHousingRequested },{label:"Housing Arranged ",data:dateHousingArranged },
         	 {label:"Housing Confirmed",data:dateHousingConfirmed },
         	 /*
         	 {label:"Date",data:dateDate },
			 {label:"Pick Up Arranged",data:datePickUpArranged },{label:"Acco Arranged",data:dateAccoArranged },
			 {label:"Bus Ticket Arranged",data:dateBusTicketArranged },
         	 {label:"Ticket Info Sent To PKR",data:dateTicketInfoSentToPKR },
         	 {label:"Survival Orientation Booklet",data:dateSurvivalOrientationBooklet },
         	 {label:"Welcome Pack",data:dateWelcomePack },{label:"Welcome Letter",data:dateWelcomeLetter },
         	 */
         	 ]);
       		//nonMandatoryTextFields  = new ArrayCollection([{label:"Time", data:txtInputTime},{label:"Arrival/flight", data:txtArrivalFlight}]);
       		nonMandatoryComboFields  = new ArrayCollection([{label:"Housing Link Person", data:comboHousingLinkPerson},
       		{label:"Ktm Link Person", data:comboKtmLinkPerson},{label:"Pkr Link Person", data:comboPkrLinkPerson},
       		{label:"School Children Link Person", data:comboSchoolChildrenLinkPerson},{label:"Work Link Person", data:comboWorkLinkPerson}
       		]);
          	
           }
		
		override protected function pushSearchByVariables():void{
			listSearchBy.push({ label: "email_address_requested_date",data:"orientation"});   
	        listSearchBy.push({ label: "email_address_created_date",data:"orientation"});
			listSearchBy.push({ label: "email_installed_date",data:"orientation"});
			
			listSearchBy.push({ label: "LOT_duration_discussed_date",data:"orientation"});   
	        listSearchBy.push({ label: "LOT_requested_date",data:"orientation"});
			listSearchBy.push({ label: "LOT_confirmed_date",data:"orientation"});	
		
	        listSearchBy.push({ label: "ktm_LOT_scheduled_date",data:"orientation"});
			listSearchBy.push({ label: "ktm_LOT_confirmed_date",data:"orientation"});	
			
			listSearchBy.push({ label: "dates_of_KTM_LOT",data:"orientation"});   
	        listSearchBy.push({ label: "housing_preferences_date",data:"orientation"});
			listSearchBy.push({ label: "pkr_LOT_housing_arranged_date",data:"orientation"});	
			 
	        listSearchBy.push({ label: "housing_requested_date",data:"orientation"});
			listSearchBy.push({ label: "housing_arranged_date",data:"orientation"});	
			
			listSearchBy.push({ label: "housing_confirmed_date",data:"orientation"});
			/*			
			listSearchBy.push({ label: "arrival_flight",data:"orientation"});
			listSearchBy.push({ label: "arrival_date",data:"orientation"});	
			
			listSearchBy.push({ label: "arrival_time",data:"orientation"});				
			listSearchBy.push({ label: "pickup_arranged_date",data:"orientation"});  
			listSearchBy.push({ label: "accomodation_arranged_date",data:"orientation"});
			listSearchBy.push({ label: "bus_ticket_arranged_date",data:"orientation"});	
			
			listSearchBy.push({ label: "ticket_info_sent_to_pkr_date",data:"orientation"});   
	        listSearchBy.push({ label: "survival_orientation_booklet_date",data:"orientation"});
			listSearchBy.push({ label: "welcome_pack_date",data:"orientation"});	
			listSearchBy.push({ label: "welcome_letter_date",data:"orientation"});
			*/
			listSearchBy.push({ label: "link_names_sent_date",data:"orientation"});
			listSearchBy.push({ label: "ktm_link_person",data:"orientation"});//points to link person
			listSearchBy.push({ label: "pkr_link_person",data:"orientation"});//points to link person
			listSearchBy.push({ label: "work_link_person",data:"orientation"});//points to link person
			listSearchBy.push({ label: "housing_link_person",data:"orientation"});//points to link person
			listSearchBy.push({ label: "school_children_link_person",data:"orientation"});//points to link person			
        	super.pushSearchByVariables();
        }
        
        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "email_address_requested_date",data:"orientation"});   
	        listSearchWhom.push({ fields: "email_address_created_date",data:"orientation"});
			listSearchWhom.push({ fields: "email_installed_date",data:"orientation"});
			
			listSearchWhom.push({ fields: "LOT_duration_discussed_date",data:"orientation"});   
	        listSearchWhom.push({ fields: "LOT_requested_date",data:"orientation"});
			listSearchWhom.push({ fields: "LOT_confirmed_date",data:"orientation"});	
			
			//listSearchWhom.push({ fields: "LOT_details",data:"orientation"});   
	        listSearchWhom.push({ fields: "ktm_LOT_scheduled_date",data:"orientation"});
			listSearchWhom.push({ fields: "ktm_LOT_confirmed_date",data:"orientation"});	
			
			listSearchWhom.push({ fields: "dates_of_KTM_LOT",data:"orientation"});   
	        listSearchWhom.push({ fields: "housing_preferences_date",data:"orientation"});
			listSearchWhom.push({ fields: "pkr_LOT_housing_arranged_date",data:"orientation"});	
			
			//listSearchWhom.push({ fields: "PKR_LOT_housing_details",data:"orientation"});   
	        listSearchWhom.push({ fields: "housing_requested_date",data:"orientation"});
			listSearchWhom.push({ fields: "housing_arranged_date",data:"orientation"});	
			
			listSearchWhom.push({ fields: "housing_confirmed_date",data:"orientation"});
			//listSearchWhom.push({ fields: "housing_details",data:"orientation"});	
			/*
			listSearchWhom.push({ fields: "arrival_flight",data:"orientation"});
			listSearchWhom.push({ fields: "arrival_date",data:"orientation"});	
			
			listSearchWhom.push({ fields: "arrival_time",data:"orientation"});	
			
			listSearchWhom.push({ fields: "pickup_arranged_date",data:"orientation"});  
			listSearchWhom.push({ fields: "acco_arranged_date",data:"orientation"}); 
	        //listSearchWhom.push({ fields: "acco_details",data:"orientation"});
			listSearchWhom.push({ fields: "bus_ticket_arranged_date",data:"orientation"});	
			
			listSearchWhom.push({ fields: "ticket_info_sent_to_pkr_date",data:"orientation"});   
	        listSearchWhom.push({ fields: "survival_orientation_booklet_date",data:"orientation"});
			listSearchWhom.push({ fields: "welcome_pack_date",data:"orientation"});	
			listSearchWhom.push({ fields: "welcome_letter_date",data:"orientation"});
			*/
			listSearchWhom.push({ fields: "link_names_sent_date",data:"orientation"});
			
			listSearchWhom.push({ fields: "ktm_link_person",data:"orientation"}); //points to link person id
			listSearchWhom.push({ fields: "pkr_link_person",data:"orientation"}); //points to link person id
			listSearchWhom.push({ fields: "housing_link_person",data:"orientation"}); //points to link person id
			listSearchWhom.push({ fields: "work_link_person",data:"orientation"}); //points to link person id
			listSearchWhom.push({ fields: "school_children_link_person",data:"orientation"}); //points to link person id			
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
			chkShowAll.visible = false;	
			showDatagrid=true;
			comboHousingLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			comboKtmLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			comboPkrLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			comboSchoolChildrenLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			comboWorkLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;			
        }

		override protected function setSendParameters():Object {
		
		   var parameters:Object=super.setSendParameters();
		 
			parameters.emailAddressRequested = DateUtils.dateFieldToString(dateEmailAddressRequested,parentApplication.dateFormat);
			parameters.emailAddressCreated = DateUtils.dateFieldToString(dateEmailAddressCreated,parentApplication.dateFormat);
			parameters.emailInstalled =DateUtils.dateFieldToString(dateEmailInstalled,parentApplication.dateFormat);
			parameters.lotDurationDiscussed = DateUtils.dateFieldToString(dateLOTDurationDiscussed,parentApplication.dateFormat);
			parameters.lotRequested =  DateUtils.dateFieldToString(dateLOTRequested,parentApplication.dateFormat);
			parameters.lotConfirmed = DateUtils.dateFieldToString(dateLOTConfirmed,parentApplication.dateFormat);
			//parameters.lotDetails = DateUtils.dateFieldToString(dateLOTDetails,parentApplication.dateFormat);			
			
			parameters.ktmLOTScheduled = DateUtils.dateFieldToString(dateKTMLOTScheduled,parentApplication.dateFormat);
			parameters.ktmLOTConfirmed = DateUtils.dateFieldToString(dateKTMLOTConfirmed,parentApplication.dateFormat);
			
			parameters.ktmLOT= DateUtils.dateFieldToString(dateDatesOfKTMLOT,parentApplication.dateFormat);
			parameters.housingPreferences = DateUtils.dateFieldToString(dateHousingPreferences,parentApplication.dateFormat);
			parameters.housingRequested = DateUtils.dateFieldToString(dateHousingRequested,parentApplication.dateFormat);
			parameters.pkrLOTHousingArranged = DateUtils.dateFieldToString(datePKRLOTHousingArranged,parentApplication.dateFormat);
			
			//parameters.pkrLOTHousingDetails = DateUtils.dateFieldToString(datePKRLOTHousingDetails,parentApplication.dateFormat);
			parameters.housingArranged = DateUtils.dateFieldToString(dateHousingArranged,parentApplication.dateFormat);
			parameters.housingConfirmed = DateUtils.dateFieldToString(dateHousingConfirmed,parentApplication.dateFormat);
			//parameters.dateHousingDetails = DateUtils.dateFieldToString(dateHousingDetails,parentApplication.dateFormat);			
			
			parameters.linkNamesSent = DateUtils.dateFieldToString(dateLinkNamesSent,parentApplication.dateFormat);
			/*
			parameters.date = DateUtils.dateFieldToString(dateDate,parentApplication.dateFormat);
			parameters.time = txtInputTime.text;
			parameters.arrivalFlight=txtArrivalFlight.text;
			parameters.pickUpArranged = DateUtils.dateFieldToString(datePickUpArranged,parentApplication.dateFormat);
			parameters.accoArranged = DateUtils.dateFieldToString(dateAccoArranged,parentApplication.dateFormat);
			
			//parameters.accoDetails = DateUtils.dateFieldToString(dateAccoDetails,parentApplication.dateFormat);
			parameters.busTicketArranged = DateUtils.dateFieldToString(dateBusTicketArranged,parentApplication.dateFormat);
			parameters.ticketInfoSentToPKR= DateUtils.dateFieldToString(dateTicketInfoSentToPKR,parentApplication.dateFormat);
			
			parameters.survivalOrientationBooklet = DateUtils.dateFieldToString(dateSurvivalOrientationBooklet,parentApplication.dateFormat);
			parameters.welcomePack = DateUtils.dateFieldToString(dateWelcomePack,parentApplication.dateFormat);
			parameters.welcomeLetter = DateUtils.dateFieldToString(dateWelcomeLetter,parentApplication.dateFormat);
			*/
		//need to update	
			parameters.housingLinkPersonID= comboHousingLinkPerson.value;
			parameters.ktmLinkPersonID=  comboKtmLinkPerson.value;
			parameters.pkrLinkPersonID= comboPkrLinkPerson.value;
			parameters.schoolChildrenLinkPersonID=  comboSchoolChildrenLinkPerson.value;
			parameters.workLinkPersonID= comboWorkLinkPerson.value;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.orientation_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
 						
 						
			return parameters;
		}

		override public function refreshData():void{

			super.refreshData();

			var nameIndex:int = parentApplication.getComboData(comboHousingLinkPerson,comboHousingLinkPerson.text);
			comboHousingLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			parentApplication.setComboData(comboHousingLinkPerson,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboKtmLinkPerson,comboKtmLinkPerson.text);
			comboKtmLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			parentApplication.setComboData(comboKtmLinkPerson,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboPkrLinkPerson,comboPkrLinkPerson.text);
			comboPkrLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			parentApplication.setComboData(comboPkrLinkPerson,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboSchoolChildrenLinkPerson,comboSchoolChildrenLinkPerson.text);
			comboSchoolChildrenLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;
			parentApplication.setComboData(comboSchoolChildrenLinkPerson,nameIndex);
			
			nameIndex = parentApplication.getComboData(comboWorkLinkPerson,comboWorkLinkPerson.text);
			comboWorkLinkPerson.dataProvider	=	parentApplication.getUserRequestNameResult().idFullNames.idFullName;			
			parentApplication.setComboData(comboWorkLinkPerson,nameIndex);
			
			if (modeState != "View") {
				this.focusManager.setFocus(dateEmailAddressRequested);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}  
		
		protected function displayPopUpDoc(strTitle:String,fieldName:String,dateOrient:DateField):void{		
			
			if (dgList.selectedItem == null) return;
			
			var today:Date = new Date(); 
        	if((dateOrient.text=='')&&(modeState=='Edit'))
		 		dateOrient.text=DateUtils.dateToString(today,dateOrient.formatString);
		 		
			var newDoc:Boolean = false;
			
			switch(fieldName) {
				case 'housing_confirmed_doc_id':
					newDoc = (dgList.selectedItem.housing_confirmed_doc_id == 0);
				break;
				case 'LOT_confirmed_doc_id':
					newDoc = (dgList.selectedItem.LOT_confirmed_doc_id == 0);
				break;
				case 'pkr_LOT_housing_arranged_doc_id':
					newDoc = (dgList.selectedItem.pkr_LOT_housing_arranged_doc_id == 0);
				break;
			}
			displayDocPopUp(strTitle,fieldName,'orientation_notes', newDoc);											
		}      
				
        override protected function refresh():void{
			
			super.refresh();
			
			dateEmailAddressRequested.text="";
			dateEmailAddressCreated.text="";
			dateLOTDurationDiscussed.text="";
			dateEmailInstalled.text="";
			dateLOTRequested.text="";
			dateLOTConfirmed.text="";
			
			dateKTMLOTScheduled.text="";
			dateKTMLOTConfirmed.text="";
			dateDatesOfKTMLOT.text="";
			dateHousingPreferences.text="";
			dateHousingRequested.text="";
			datePKRLOTHousingArranged.text="";			
	   
			dateHousingArranged.text="";
			dateHousingConfirmed.text="";		
			/*
			dateDate.text="";
			txtInputTime.text="";
			txtArrivalFlight.text="";
			datePickUpArranged.text="";
			dateAccoArranged.text="";
		
			dateBusTicketArranged.text="";
			dateTicketInfoSentToPKR.text="";
			dateSurvivalOrientationBooklet.text="";
			dateWelcomePack.text="";
			dateWelcomeLetter.text="";	
			*/
			dateLinkNamesSent.text="";
			
			comboHousingLinkPerson.selectedIndex	=	0;
			comboKtmLinkPerson.selectedIndex	=	0;
			comboPkrLinkPerson.selectedIndex	=	0;
			comboSchoolChildrenLinkPerson.selectedIndex	=	0;
			comboWorkLinkPerson.selectedIndex	=	0;						
		}        

		override protected function setValues():void{

			super.setValues();

				dateEmailAddressRequested.text= DateUtils.stringToDateFieldString(dgList.selectedItem.email_address_requested_date,dateEmailAddressRequested,parentApplication.dateFormat);
				dateEmailAddressCreated.text=DateUtils.stringToDateFieldString(dgList.selectedItem.email_address_created_date,dateEmailAddressCreated,parentApplication.dateFormat);
				dateLOTDurationDiscussed.text=DateUtils.stringToDateFieldString(dgList.selectedItem.LOT_duration_discussed_date,dateLOTDurationDiscussed,parentApplication.dateFormat);
				dateEmailInstalled.text=DateUtils.stringToDateFieldString(dgList.selectedItem.email_installed_date,dateEmailInstalled,parentApplication.dateFormat);
				dateLOTRequested.text=DateUtils.stringToDateFieldString(dgList.selectedItem.LOT_requested_date,dateLOTRequested,parentApplication.dateFormat);
				dateLOTConfirmed.text=DateUtils.stringToDateFieldString(dgList.selectedItem.LOT_confirmed_date,dateLOTConfirmed,parentApplication.dateFormat);
				//dateLOTDetails.text=DateUtils.stringToDateFieldString(dgList.selectedItem.LOT_details,dateLOTDetails,parentApplication.dateFormat);				
				
				dateKTMLOTScheduled.text=DateUtils.stringToDateFieldString(dgList.selectedItem.ktm_LOT_scheduled_date,dateKTMLOTScheduled,parentApplication.dateFormat);
				dateKTMLOTConfirmed.text=DateUtils.stringToDateFieldString(dgList.selectedItem.ktm_LOT_Confirmed_date,dateKTMLOTConfirmed,parentApplication.dateFormat);
				dateDatesOfKTMLOT.text=DateUtils.stringToDateFieldString(dgList.selectedItem.Dates_of_ktm_LOT,dateDatesOfKTMLOT,parentApplication.dateFormat);
				dateHousingPreferences.text=DateUtils.stringToDateFieldString(dgList.selectedItem.Housing_preferences_date,dateHousingPreferences,parentApplication.dateFormat);
				dateHousingRequested.text=DateUtils.stringToDateFieldString(dgList.selectedItem.housing_requested_date,dateHousingRequested,parentApplication.dateFormat);
				datePKRLOTHousingArranged.text=DateUtils.stringToDateFieldString(dgList.selectedItem.pkr_LOT_housing_arranged_date,datePKRLOTHousingArranged,parentApplication.dateFormat);
				//datePKRLOTHousingDetails.text=DateUtils.stringToDateFieldString(dgList.selectedItem.PKR_LOT_housing_details,datePKRLOTHousingDetails,parentApplication.dateFormat);
		   
				dateHousingArranged.text=DateUtils.stringToDateFieldString(dgList.selectedItem.housing_arranged_date,dateHousingArranged,parentApplication.dateFormat);
				dateHousingConfirmed.text=DateUtils.stringToDateFieldString(dgList.selectedItem.housing_confirmed_date,dateHousingConfirmed,parentApplication.dateFormat);
				//dateHousingDetails.text=DateUtils.stringToDateFieldString(dgList.selectedItem.housing_details,dateHousingDetails,parentApplication.dateFormat);
			
				dateLinkNamesSent.text=DateUtils.stringToDateFieldString(dgList.selectedItem.link_names_sent_date,dateLinkNamesSent,parentApplication.dateFormat);
				/*
				dateDate.text=DateUtils.stringToDateFieldString(dgList.selectedItem.arrival_date,dateDate,parentApplication.dateFormat);
				txtInputTime.text=dgList.selectedItem.arrival_time;
				txtArrivalFlight.text=dgList.selectedItem.arrival_flight;
				datePickUpArranged.text=DateUtils.stringToDateFieldString(dgList.selectedItem.pickup_arranged_date,datePickUpArranged,parentApplication.dateFormat);
				dateAccoArranged.text=DateUtils.stringToDateFieldString(dgList.selectedItem.accomodation_arranged_date,dateAccoArranged,parentApplication.dateFormat);
				//dateAccoDetails.text=DateUtils.stringToDateFieldString(dgList.selectedItem.acco_details,dateAccoDetails,parentApplication.dateFormat);
				dateBusTicketArranged.text=DateUtils.stringToDateFieldString(dgList.selectedItem.bus_ticket_arranged_date,dateBusTicketArranged,parentApplication.dateFormat);
				dateTicketInfoSentToPKR.text=DateUtils.stringToDateFieldString(dgList.selectedItem.ticket_info_sent_to_pkr_date,dateTicketInfoSentToPKR,parentApplication.dateFormat);
				dateSurvivalOrientationBooklet.text=DateUtils.stringToDateFieldString(dgList.selectedItem.survival_orientation_booklet_date,dateSurvivalOrientationBooklet,parentApplication.dateFormat);
				dateWelcomePack.text=DateUtils.stringToDateFieldString(dgList.selectedItem.welcome_pack_date,dateWelcomePack,parentApplication.dateFormat);
				dateWelcomeLetter.text=DateUtils.stringToDateFieldString(dgList.selectedItem.welcome_letter_date,dateWelcomeLetter,parentApplication.dateFormat);
				*/
				dateLinkNamesSent.text=DateUtils.stringToDateFieldString(dgList.selectedItem.link_names_sent_date,dateLinkNamesSent,parentApplication.dateFormat);
				
				comboHousingLinkPerson.selectedIndex	=	parentApplication.getComboIndex(comboHousingLinkPerson.dataProvider,'data',dgList.selectedItem.housing_link_person_id);
				comboKtmLinkPerson.selectedIndex	=	parentApplication.getComboIndex(comboKtmLinkPerson.dataProvider,'data',dgList.selectedItem.ktm_link_person_id);
				comboPkrLinkPerson.selectedIndex	=	parentApplication.getComboIndex(comboPkrLinkPerson.dataProvider,'data',dgList.selectedItem.pkr_link_person_id);
				comboSchoolChildrenLinkPerson.selectedIndex	=	parentApplication.getComboIndex(comboSchoolChildrenLinkPerson.dataProvider,'data',dgList.selectedItem.school_children_link_person_id);
				comboWorkLinkPerson.selectedIndex	=	parentApplication.getComboIndex(comboWorkLinkPerson.dataProvider,'data',dgList.selectedItem.work_link_person_id);				
			}
			
			override protected function enableDisableDocumentButton():void{

			var rowCount:int=dgList.dataProvider.length;
	
			if(modeState=='Edit') {
				btnLOTConfirmed.visible=true;
				btnPKRLOTHousingArranged.visible=true;
				btnHousingConfirmed.visible=true;
				//btnAccoArranged.visible=true;						
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				
				 if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 
				 btnLOTConfirmed.visible=(dgList.selectedItem.LOT_confirmed_doc_id>0)?true:false;				
				 btnPKRLOTHousingArranged.visible=(dgList.selectedItem.pkr_LOT_housing_arranged_doc_id>0)?true:false;
				 btnHousingConfirmed.visible=(dgList.selectedItem.housing_confirmed_doc_id>0)?true:false;				 
			}
			else { 
				 btnLOTConfirmed.visible=false;
				 btnPKRLOTHousingArranged.visible=false;
				 btnHousingConfirmed.visible=false;
				 //btnAccoArranged.visible=false;				
			}
		}   