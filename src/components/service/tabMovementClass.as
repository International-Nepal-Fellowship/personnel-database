package components.service
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.controls.DateField;
	import mx.controls.TextArea;
	import mx.controls.Label;
	import components.service.popUpAddress;
	import components.service.popUpEmail;
	import components.service.popUpPhone;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DateUtils;
	import mx.collections.ArrayCollection;
	import mx.rpc.events.ResultEvent;
	import mx.controls.Alert;
	import packages.DataGridNew;

	public class tabMovementClass extends tabCanvasClass
	{
		public function tabMovementClass()
		{
			super();
		}

		include "../movementCommon.as";		
	}
}