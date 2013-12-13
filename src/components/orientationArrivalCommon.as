// common code for tabOrientationClass
import mx.controls.Button;
import mx.controls.DateField;

	  //  [Bindable]public var showDatagrid:Boolean;
	    
		
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
		
		public var btnAccoArranged:Button;		
		
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([
         	 {label:"Date",data:dateDate },
			 {label:"Pick Up Arranged",data:datePickUpArranged },{label:"Acco Arranged",data:dateAccoArranged },
			 {label:"Bus Ticket Arranged",data:dateBusTicketArranged },
         	 {label:"Ticket Info Sent To PKR",data:dateTicketInfoSentToPKR },
         	 {label:"Survival Orientation Booklet",data:dateSurvivalOrientationBooklet },
         	 {label:"Welcome Pack",data:dateWelcomePack },{label:"Welcome Letter",data:dateWelcomeLetter },
         	 ]);
        }
		
		 //load non mandatory fields for alerting if they are left empty while filling forms
		override protected function loadNonMandatoryFields():void{
         	nonMandatoryDateFields = new ArrayCollection([
         	 {label:"Date",data:dateDate },
			 {label:"Pick Up Arranged",data:datePickUpArranged },{label:"Acco Arranged",data:dateAccoArranged },
			 {label:"Bus Ticket Arranged",data:dateBusTicketArranged },
         	 {label:"Ticket Info Sent To PKR",data:dateTicketInfoSentToPKR },
         	 {label:"Survival Orientation Booklet",data:dateSurvivalOrientationBooklet },
         	 {label:"Welcome Pack",data:dateWelcomePack },{label:"Welcome Letter",data:dateWelcomeLetter },
         	 ]);
       		nonMandatoryTextFields  = new ArrayCollection([{label:"Time", data:txtInputTime},{label:"Arrival/flight", data:txtArrivalFlight}]);         	
           }
		
		override protected function pushSearchByVariables():void{
				
			listSearchBy.push({ label: "arrival_flight",data:"orientation_arrangement"});
			listSearchBy.push({ label: "arrival_date",data:"orientation_arrangement"});	
			
			listSearchBy.push({ label: "arrival_time",data:"orientation_arrangement"});				
			listSearchBy.push({ label: "pickup_arranged_date",data:"orientation_arrangement"});  
			listSearchBy.push({ label: "accomodation_arranged_date",data:"orientation_arrangement"});
			listSearchBy.push({ label: "bus_ticket_arranged_date",data:"orientation_arrangement"});	
			
			listSearchBy.push({ label: "ticket_info_sent_to_pkr_date",data:"orientation_arrangement"});   
	        listSearchBy.push({ label: "survival_orientation_booklet_date",data:"orientation_arrangement"});
			listSearchBy.push({ label: "welcome_pack_date",data:"orientation_arrangement"});	
			listSearchBy.push({ label: "welcome_letter_date",data:"orientation_arrangement"});
			super.pushSearchByVariables();
        }
        
        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	
			listSearchWhom.push({ fields: "arrival_flight",data:"orientation_arrangement"});
			listSearchWhom.push({ fields: "arrival_date",data:"orientation_arrangement"});	
			
			listSearchWhom.push({ fields: "arrival_time",data:"orientation_arrangement"});	
			
			listSearchWhom.push({ fields: "pickup_arranged_date",data:"orientation_arrangement"});  
			listSearchWhom.push({ fields: "accomodation_arranged_date",data:"orientation_arrangement"}); 
	        //listSearchWhom.push({ fields: "acco_details",data:"orientation_arrangement"});
			listSearchWhom.push({ fields: "bus_ticket_arranged_date",data:"orientation_arrangement"});	
			
			listSearchWhom.push({ fields: "ticket_info_sent_to_pkr_date",data:"orientation_arrangement"});   
	        listSearchWhom.push({ fields: "survival_orientation_booklet_date",data:"orientation_arrangement"});
			listSearchWhom.push({ fields: "welcome_pack_date",data:"orientation_arrangement"});	
			listSearchWhom.push({ fields: "welcome_letter_date",data:"orientation_arrangement"});			
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
			chkShowAll.visible = false;	
			//showDatagrid=false;
	   }

		override protected function setSendParameters():Object {
		
		    var parameters:Object=super.setSendParameters();

		    parameters.date = DateUtils.dateFieldToString(dateDate,parentApplication.dateFormat);

			parameters.time = txtInputTime.text;
			parameters.arrivalFlight=txtArrivalFlight.text;
			parameters.pickUpArranged = DateUtils.dateFieldToString(datePickUpArranged,parentApplication.dateFormat);
			parameters.accoArranged = DateUtils.dateFieldToString(dateAccoArranged,parentApplication.dateFormat);
			
			parameters.busTicketArranged = DateUtils.dateFieldToString(dateBusTicketArranged,parentApplication.dateFormat);
			parameters.ticketInfoSentToPKR= DateUtils.dateFieldToString(dateTicketInfoSentToPKR,parentApplication.dateFormat);
			
			parameters.survivalOrientationBooklet = DateUtils.dateFieldToString(dateSurvivalOrientationBooklet,parentApplication.dateFormat);
			parameters.welcomePack = DateUtils.dateFieldToString(dateWelcomePack,parentApplication.dateFormat);
			parameters.welcomeLetter = DateUtils.dateFieldToString(dateWelcomeLetter,parentApplication.dateFormat);
					
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.orientation_arrangement_timestamp;
			}
			
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 												
			return parameters;
		}

		override public function refreshData():void{

			super.refreshData();
				
			if (modeState != "View") {
				this.focusManager.setFocus(txtArrivalFlight);
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
				case 'accomodation_arranged_doc_id':
					newDoc = (dgList.selectedItem.accomodation_arranged_doc_id == 0);
				break;
			}
			displayDocPopUp(strTitle,fieldName,'orientation_arrangement_notes', newDoc);											
		}            		
		
        override protected function refresh():void{
			
			super.refresh();
			
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
		}        
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
		}

		override protected function setValues():void{

			super.setValues();

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
		}
			
		override protected function enableDisableDocumentButton():void{
			var rowCount:int=dgList.dataProvider.length;
//Alert.show('test e/d button');			
			if(modeState=='Edit') {				
				btnAccoArranged.visible=true;							
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				
				 if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 			
				 btnAccoArranged.visible=(dgList.selectedItem.accomodation_arranged_doc_id>0)?true:false;					 
			}
			else { 				
				 btnAccoArranged.visible=false;				
			}
		}   	