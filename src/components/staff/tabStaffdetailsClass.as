package components.staff
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
	import mx.managers.PopUpManager;
	
	public class tabStaffdetailsClass extends tabCanvasClass
	{
		public function tabStaffdetailsClass()
		{
			super();
			defaultListHeight = 208;
			expandedListHeight = 278;
		}
		include "../staffdetailsCommon.as";		
	}
}
