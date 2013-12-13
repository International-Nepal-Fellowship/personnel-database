// common code for queriesClass
import mx.controls.CheckBox;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
import mx.utils.StringUtil;

		public var txtAdminSQL:TextArea;
		public var userRequestCheckDB:HTTPService;
		public var chk4All:CheckBox;
		private var currentAdminName:String;
		
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
			parameters.query = mx.utils.StringUtil.trim(txtAdminSQL.text); 
			if(chk4All.selected)
	 		 	parameters.search_4_all	=	'1';
	 		 else
	 		 	parameters.search_4_all	=	'0';
	 		 							
			return parameters;			
		}
		
		override protected function refresh():void{
					
			super.refresh();
			txtAdminSQL.text	=	"";	
			chk4All.selected = false;		
		}

		override protected function setValues():void{
					
			super.setValues();
			txtAdminSQL.text	=	dgList.selectedItem.query;
			chk4All.selected =	(dgList.selectedItem.search_4_all == 1);
			currentAdminName = comboAdminName.text;		
		}

		override protected function checkValid(inputObject:Object):void{
					
			super.checkValid(inputObject);
			
			var commandsToFilter:Array=new Array(' INSERT ',' UPDATE ',' REPLACE ',' CREATE ',' DELETE ');
			var filterCommands:String='';
		
			var tempString:String =  mx.utils.StringUtil.trim( txtAdminSQL.text );//remove spaces from the beginning and end of the text

			if (tempString.length < 7) {
				saveEnabled = false;
			}
			else {
				//check to see if the first word is 'select' or not
				if(tempString.substring(0,7).toLowerCase() != "select "){
					saveEnabled	= false;
				}
				//now disallow any of the 'update','insert','delete','replace','create' command in the query
				
				var index:int;
				for(var i:int=0;i<commandsToFilter.length;i++){
					
					if( (tempString.toUpperCase()).indexOf(commandsToFilter[i]) != -1 ){	
						
						saveEnabled	= false;
						filterCommands+=commandsToFilter[i];						
					}					
				}
								
				if(filterCommands=='')	status_txt.text ='';
				else 	status_txt.text = filterCommands +" is not allowed.";		
										
			}	
		}		
	
	    public function checkDuplicateSearchName(strSearchName:String):void{
	    	
	    	if((modeState=='Edit')&&(txtAdminName.text==currentAdminName))
	    		return;//search name is not updated so dont check DB
			
			saveEnabled=false;
			parentApplication.searching=true;
			
			var parameters:Object=new Object();	
	 		parameters.searchName=strSearchName;
	 		parameters.userID='0';
	 		parameters.requester='checkSearchHistoryName';
	 		userRequestCheckDB.useProxy = false;
           	userRequestCheckDB.url	=	parentApplication.getPath()	+	"requestCheckDb.php";
           	userRequestCheckDB.send(parameters);               
		}
		
		public function handleDBResult(event:ResultEvent):void{
	 	
		 	parentApplication.searching=false;		 	
		 				
		 	if(userRequestCheckDB.lastResult.searchName=='duplicate'){		 	 	 		 	 	 
		 	 	 status_txt.text ="Duplicate name for search. Please enter unique name.";		 	 	 
		 	}	
		 	else{
		 		checkValid(txtAdminName);
		 		status_txt.text ='';
		 	}
		 }