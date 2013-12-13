package components.service
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.DateField;
	import components.service.popUpLeaveType;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DateUtils;
	import mx.collections.ArrayCollection;

	public class tabLeaveClass extends tabCanvasClass
	{
		public function tabLeaveClass()
		{
			super();
			defaultListHeight = 200;
			expandedListHeight = 270;
		}

		include "../leaveCommon.as";		
	}
}