package components.service
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.DateField;
	import mx.controls.TextInput;
	import components.popUpWindow;
	import components.service.popUpPost;
	import components.service.popUpLocation;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DateUtils;
	import mx.collections.ArrayCollection;

	public class tabServiceClass extends tabCanvasClass
	{
		public function tabServiceClass()
		{
			super();
		}

		include "../sserviceCommon.as";		
	}
}