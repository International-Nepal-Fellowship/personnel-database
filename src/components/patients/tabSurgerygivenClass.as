package components.patients
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import mx.controls.TextInput;
	import packages.DateUtils;
	import packages.ComboBoxNew;
	import mx.controls.DateField;	
	import mx.controls.Button;
	import mx.collections.ArrayCollection;
	import flash.events.DataEvent;
	import flash.events.IOErrorEvent;
	import mx.controls.Alert;
	import mx.rpc.http.mxml.HTTPService;
	import components.popUpWindow;
	import mx.managers.PopUpManager;
	
	public class tabSurgerygivenClass extends tabCanvasClass
	{
		public function tabSurgerygivenClass()
		{
			super();
			defaultListHeight = 220;
			expandedListHeight = 290;
		}
		include "../surgerygivenCommon.as";		
	}
}
