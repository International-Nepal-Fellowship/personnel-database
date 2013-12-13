package packages
{
	
	import flash.utils.getTimer;
	import mx.controls.Alert;
	
	public class timeOutHandler
	{
		public static var timeoutTotal:Number=100;
      	public var timeout:Number = 20000; // timeout after ten seconds
     	
     	
		public function timeOutHandler()
		{	
			
			timeoutTotal+=100;
			
		}
		
		public function isTimeOut():Boolean{	
			
	         // if the total time of inactivity passes your timeout
	         // and the session already hasn't expired then logout user
	        
	         if (timeoutTotal > timeout) {
	            // logout user
	            // or set flag
	            return true;	            
	          
	         }
	        else{
	        	return false;
	        } 
         	
		}
		
	}
}