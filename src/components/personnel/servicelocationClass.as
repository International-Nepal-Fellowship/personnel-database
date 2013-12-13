package components.personnel
{
	import mx.controls.TextInput;	
	import packages.adminCanvasClass;
	import components.service.popUpAddress;
	import components.service.popUpEmail;
	import components.service.popUpPhone;
	import mx.managers.PopUpManager;
	import packages.ComboBoxNew;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.mxml.HTTPService;

	public class servicelocationClass extends adminCanvasClass
	{
		public function servicelocationClass()
		{
			super();
		}
		
		include "../locationCommon.as";
	}
}