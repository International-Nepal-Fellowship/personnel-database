// common code for tabPhotoClass and popupPhotoClass

	import flash.net.URLRequest;
	
		public var photoHolder:Image;
		public var textPhoto:TextInput;
		public var textDescription:TextArea;
		public var getPhoto:HTTPService;
		
		/* Photo uploading script*/
		private var file:FileReference;
	    [Bindable] private var fileName:String="";
		private var uniqueString:String="";

        override protected function pushSearchByVariables():void{
        	
        	listSearchBy.push({ label: "description",data:"photo"});
        	super.pushSearchByVariables();
        }

        override protected function pushSearchWhomVariables():void{
        	
        	super.pushSearchWhomVariables();
        	listSearchWhom.push({ fields: "description",data:"photo"});
        }
        		
		protected function selectFile():void 
		{			
			photoHolder.source='';
			if(uniqueString	==	""){//generate unique string for fileName 		
				var currentDate:Date	=	new Date();
				uniqueString	=	currentDate.getTime().toString();
			}
			
			//	Alert.show("file: "+uniqueString);
			
			var imageTypes:FileFilter = new FileFilter("Images (*.jpg, *.jpeg, *.gif, *.png)", "*.jpg; *.jpeg; *.gif; *.png");
	        var allTypes:Array = new Array(imageTypes);
	        
			file = new FileReference();
			file.addEventListener(Event.SELECT, fileSelected);
			file.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, uploadDataComplete);
			file.addEventListener(Event.COMPLETE, uploadComplete);
			file.addEventListener(IOErrorEvent.IO_ERROR, handleError);
			
			try{
				file.browse(allTypes);
			}
			catch (error:Error){
	    		Alert.show("Unable to browse for files.");
			}
		}
	
		private function handleError(event:IOErrorEvent):void{
			
			status_txt.text = 'ERROR: ' + event.text + '\n';
		}
		
		private function fileSelected(event:Event):void{
			
			file = FileReference(event.target);
			textPhoto.text =file.name;
			//status_txt.text = 'upload file: '+ file.name  + '\n';
			
			var request:URLRequest = new URLRequest();
	    	//request.url = "../files/upload.php?imageName	=	"	+	uniqueString;
	    	request.url = parentApplication.getUploadURL(uniqueString);
	    	parentApplication.searching = true;
			file.upload(request);			
		}
		
		private function uploadDataComplete(event:DataEvent):void{
		
			var result:XML = new XML(event.data);
			parentApplication.searching = false;
			
			status_txt.text += 'Upload Data Complete\n';
			//status_txt.text += 'RESULT: ' + result.toString()  + '\n'
			//status_txt.text += 'STATUS: ' + result.status + '\n';
			status_txt.text = result.message;
			if(result.status=='OK')
				status_txt.enabled=true;//display message in normal color if enabled
			else{
				status_txt.enabled=false;//display message in normal Error indication color [red] if not enabled
				saveEnabled	=	false;
			}
			//if(result.status	==	"Error") saveEnabled	=	false;
			//fileName	=	uniqueString	+	'.'	+	result.extention;
			fileName	=	result.imageName;
			photoHolder.source=downloadImage(fileName,'thumb');
			
			//status_txt.text=fileName;		
			checkValid(null);			
		}
	
		private function uploadComplete(event:Event):void{
		
			//	status_txt.text += 'Upload complete\n';	
			checkValid(null);	
		}
		            
		override protected function loadData(current:Boolean=false):void{
					
			super.loadData();			
       		chkShowAll.visible = false;	
        }
        
		override protected function setSendParameters():Object {

			var parameters:Object=super.setSendParameters();					
     
            parameters.description	=	textDescription.text;  
            
            if (fileName != "") {	
 				parameters.link		=	downloadImage(fileName);//txtDocumentPath.text; 
 			}
 			else 
 				parameters.link = "";
      
      		parameters.photoName	=	fileName; 	
			if(modeState == 'Edit'){
				parameters.timestamp=dgList.selectedItem.photo_timestamp;
				
			}
				
			if (popupMode)
				parameters.requestor	=	'popup.mxml';
			else
				parameters.requestor	=	'tab.mxml';
 						
			return parameters;			
		}

		override public function refreshData():void{
			
			super.refreshData();
			uniqueString = "";
			fileName	=	"";		
				
			if (modeState != "View") {
				this.focusManager.setFocus(textDescription);
				if (this.focusManager.getFocus() != null) this.focusManager.getFocus().drawFocus(true);
			}
		}	
		
			        
        override protected function refresh():void{
				
			super.refresh();
			textPhoto.text	=	"";
			textDescription.text	=	"";	
			photoHolder.source='';	
		}
		
		override protected function checkValid(inputObject:Object):void{
		
			super.checkValid(inputObject);
			
			if((textDescription.text	==	"")||(photoHolder.source	==	"")){			
				saveEnabled	= false;								
			}
		}

		override protected function setValues():void{

			super.setValues();		
        	textDescription.text = dgList.selectedItem.description;
        	photoHolder.source	=	downloadImage(dgList.selectedItem.link,'thumb');
		}

		protected function downloadImage(fileLink:String,type:String='image'):String{			
			
			return  parentApplication.downloadFile(fileLink,type);
		}