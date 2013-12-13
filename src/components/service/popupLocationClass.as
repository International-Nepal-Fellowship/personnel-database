package components.service
{
	import mx.controls.TextInput;	
	import packages.popupWindowClass;
	import components.service.popUpAddress;
	import components.service.popUpEmail;
	import components.service.popUpPhone;
	import mx.managers.PopUpManager;
	import packages.ComboBoxNew;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.mxml.HTTPService;

	public class popupLocationClass extends popupWindowClass
	{
		public function popupLocationClass()
		{
			super();
		}

		include "../locationCommon.as";
	}
}