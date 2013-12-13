// ActionScript file
import mx.collections.ArrayCollection;
import mx.controls.CheckBox;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.mxml.HTTPService;

	private var junkArrayColl:ArrayCollection =new ArrayCollection([{label:"junk", data:0}]);

	public var requestSaveAdmin:HTTPService;

	public var ckWarningOnOff:CheckBox;

	override protected function loadData(current:Boolean=false):void{					
			super.loadData();
			dgList.dataProvider=junkArrayColl;   
	
			if(parentApplication.getUserRequestNameResult().warningmessage=='No')     
				ckWarningOnOff.selected=false;
			else{				
				ckWarningOnOff.selected=true;
			}				        	       	
        	setButtonState();     	
              				
        }
        
     override protected function defaultResult(event:ResultEvent):void{ 
		super.defaultResult(event);
			status_txt.text	=	requestSaveAdmin.lastResult.errors.error;
			if(requestSaveAdmin.lastResult.errors.status=='success')
				status_txt.enabled=true;
			else
				status_txt.enabled=false;
		
	}
     override protected function setSendParameters():Object {
			
			var parameters:Object=new Object();							
			parameters.tableName= 	'warning'; 			
			parameters.action	=	'Add New';
			parameters.name		=	'';
			if(ckWarningOnOff.selected)
				parameters.warning	=	'Yes';
			else
				parameters.warning	=	'No';

			return parameters;			
		}
        
     override protected function sendData():void{ // complete override (don't call super)
			requestSaveAdmin.useProxy = false;
           	requestSaveAdmin.url	=	parentApplication.getPath()	+	"requestSaveAdmin.php";
           	requestSaveAdmin.send(setSendParameters()); 
           	viewMode(false);    		      		
        }