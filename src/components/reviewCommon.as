// common code for tabReviewClass and popupReviewClass
import flash.events.MouseEvent;

import mx.controls.DateField;
import mx.controls.HSlider;
import mx.controls.sliderClasses.Slider;

	
		public var dateReviewReviewDate:DateField;
		public var comboReviewReviewedBy:ComboBoxNew;
		public var comboReviewReviewType:ComboBoxNew;
		public var textReviewDescription:TextArea;
		public var btnGetReview:Button;
		public var starRating:HSlider;
		private const TICK_INTERVAL:uint = 1;
		[Bindable] private var today:Date = new Date(); 
		
		// load all date fields for validation
        override protected function loadAllDateFields():void{
        	
        	allDateFields= new ArrayCollection([{label:"Review Date", data:dateReviewReviewDate}]);
           	           	
        }
		

		public function slider_rollOver(evt:MouseEvent):void {
			Slider(evt.currentTarget).tickInterval = TICK_INTERVAL;
				
		}

		public function slider_rollOut(evt:MouseEvent):void {
			Slider(evt.currentTarget).tickInterval = 0;
		}
		
        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({label: "comments",data:"review"});  
        	listSearchBy.push({label: "star_rating",data:"review"});   
        	listSearchBy.push({label: "review_type",data:"review"});   
        	listSearchBy.push({label: "review_date",data:"review"});
			listSearchBy.push({label: "reviewer-Forenames",data:"review"});
        	listSearchBy.push({label: "reviewer-Surname",data:"surname"});
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "comments",data:"review"});  
        	listSearchWhom.push({ fields: "star_rating",data:"review"});
        	listSearchWhom.push({ fields: "review_type",data:"review"});   
	        listSearchWhom.push({ fields: "review_date",data:"review"});
			listSearchWhom.push({ fields: "reviewer",data:"review"});		
        }
        		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
			comboReviewReviewedBy.dataProvider=parentApplication.getUserRequestNameResult().jobReviewers.jobReviewer;
			comboReviewReviewType.dataProvider=parentApplication.getUserRequestNameResult().reviewtypes.reviewtype;
			chkShowAll.visible = false;	
        }

		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					

			parameters.reviewDate		=	DateUtils.dateFieldToString(dateReviewReviewDate,parentApplication.dateFormat);
			parameters.reviewedByID		=	comboReviewReviewedBy.value;
			parameters.reviewTypeID		=	comboReviewReviewType.value;
			parameters.description		=	textReviewDescription.text;
			parameters.starRating		=	starRating.value;
			
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.review_timestamp;
			}
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;
		}

		override public function refreshData():void{

			super.refreshData();
			
			var nameIndex:int = parentApplication.getComboData(comboReviewReviewedBy,comboReviewReviewedBy.text);
			comboReviewReviewedBy.dataProvider=parentApplication.getUserRequestNameResult().jobReviewers.jobReviewer;
			parentApplication.setComboData(comboReviewReviewedBy,nameIndex);
		
			nameIndex = parentApplication.getComboData(comboReviewReviewType,comboReviewReviewType.text);
			comboReviewReviewType.dataProvider=parentApplication.getUserRequestNameResult().reviewtypes.reviewtype;
			parentApplication.setComboData(comboReviewReviewType,nameIndex);
				
			if (modeState != "View") {
				this.focusManager.setFocus(dateReviewReviewDate);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}			
		}  

        override protected function refresh():void{
				
			super.refresh();			
			comboReviewReviewedBy.selectedIndex=0;
			comboReviewReviewType.selectedIndex=0;
			starRating.value=0;
			dateReviewReviewDate.text=DateUtils.dateToString(today,dateReviewReviewDate.formatString);
			textReviewDescription.text="";
		}        
           
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if(dateReviewReviewDate.text == ""){
				saveEnabled	= false;								
			}
			if(textReviewDescription.text == ""){
				saveEnabled = false;
			}
			if(comboReviewReviewedBy.selectedIndex	==	0){
				saveEnabled	= false;								
			}
			if(comboReviewReviewType.selectedIndex	==	0){
				saveEnabled = false;
			}	
		}
 
		override protected function setValues():void{

			super.setValues();
			starRating.value			=	dgList.selectedItem.star_rating;
			textReviewDescription.text	=	dgList.selectedItem.comments;				
         	dateReviewReviewDate.text	=	DateUtils.stringToDateFieldString(dgList.selectedItem.review_date,dateReviewReviewDate,parentApplication.dateFormat);
			comboReviewReviewedBy.selectedIndex	=	parentApplication.getComboIndex(comboReviewReviewedBy.dataProvider,'data',dgList.selectedItem.reviewed_by_id);
			comboReviewReviewType.selectedIndex	=	parentApplication.getComboIndex(comboReviewReviewType.dataProvider,'data',dgList.selectedItem.review_type_id);

		}
		
		 protected function displayPopUpDoc(dateReview:DateField):void{	
		 	
			if (dgList.selectedItem == null) return;
			
			var today:Date = new Date(); 
        	if((dateReview.text=='')&&(modeState=='Edit'))
		 		dateReview.text=DateUtils.dateToString(today,dateReview.formatString);
			
			var newDoc:Boolean = (dgList.selectedItem.review_doc_id == 0);
			displayDocPopUp('Review Document','review_doc_id','review_notes', newDoc);
		}    
		
		override protected function enableDisableDocumentButton():void{
			var rowCount:int=dgList.dataProvider.length;
		//Alert.show(rowCount.toString());			
			if(modeState=='Edit') {
				btnGetReview.visible=true;
			}
			else if(rowCount>0 && modeState=="View"){//else if (dgList.dataProvider.length>0)
				if(dgList.selectedIndex<0)dgList.selectedIndex=0;//to select a dglist row when data is deleted 
				btnGetReview.visible=(dgList.selectedItem.review_doc_id>0)?true:false;
			}
			else{
				btnGetReview.visible=false;
			}
			
		}		