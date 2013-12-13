package components.application
{
	import packages.tabCanvasClass;	
	import mx.collections.ArrayCollection;
	import flash.events.DataEvent;
	import flash.events.IOErrorEvent;	
	import mx.rpc.http.mxml.HTTPService;
	import mx.controls.CheckBox;
	import mx.controls.TextInput;
	import packages.DateUtils;
	import packages.ComboBoxNew;
	import mx.controls.DateField;
	import mx.controls.Button;
	import mx.managers.PopUpManager;

	public class tabSecondmentClass extends tabCanvasClass
	{
		public function tabSecondmentClass()
		{
			super();
			defaultListHeight = 200;
			expandedListHeight = 270;
		}
		
		include "../secondmentCommon.as";		
	}
}