package components.admin
{	
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import components.service.popUpLocation;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	import mx.rpc.events.ResultEvent;
	import packages.adminCanvasClass;
	import mx.collections.ArrayCollection;
	import mx.controls.CheckBox;
	import components.popUpWindow;
		
	public class othersettingsClass extends adminCanvasClass
	{
		public function othersettingsClass()
		{
			super();
		}
		include "../othersettingsCommon.as";
	}
}

