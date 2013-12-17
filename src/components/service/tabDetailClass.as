package components.service
{
	import packages.tabCanvasClass;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.controls.TextArea;
	import mx.controls.DateField;
	import components.popUpWindow;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DateUtils;
	import mx.collections.ArrayCollection;	
	import mx.controls.Button;
	import components.application.popupDocuments;	

	public class tabDetailClass extends tabCanvasClass
	{
		public function tabDetailClass()
		{
			super();
			//defaultListHeight = 110;
			//expandedListHeight = 180;
		}

		include "../detailCommon.as";		
	}
}
