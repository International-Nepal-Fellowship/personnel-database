package components.service
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import mx.controls.TextInput;
	import packages.DateUtils;
	import packages.ComboBoxNew;
	import mx.controls.DateField;
	import mx.controls.TextArea;	
	import mx.controls.Button;
	import mx.collections.ArrayCollection;
	import flash.events.DataEvent;
	import flash.events.IOErrorEvent;
	import mx.controls.Alert;
	import mx.rpc.http.mxml.HTTPService;
	import mx.managers.PopUpManager;
	import components.application.popupDocuments;

	public class tabHomeAssignmentClass extends tabCanvasClass
	{
		public function tabHomeAssignmentClass()
		{
			super();
			defaultListHeight = 140;
			expandedListHeight = 210;
		}

		include "../homeAssignmentCommon.as";		
	}
}