package components.patients
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import mx.controls.TextInput;
	import packages.DateUtils;
	import packages.ComboBoxNew;
	import mx.controls.DataGrid;
	import mx.controls.DateField;	
	import mx.controls.Button;
	import mx.collections.ArrayCollection;
	import flash.events.DataEvent;
	import flash.events.IOErrorEvent;
	import mx.controls.Alert;
	import mx.rpc.http.mxml.HTTPService;
	import mx.managers.PopUpManager;
	
	public class tabVisitdetailsClass extends tabCanvasClass
	{
		public function tabVisitdetailsClass()
		{
			super();
			defaultListHeight = 156;
			expandedListHeight = 226;
		}
		include "../visitdetailsCommon.as";		
	}
}
