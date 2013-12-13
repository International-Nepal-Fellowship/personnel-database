package components.service
{
	import packages.tabCanvasClass;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.controls.DateField;
	import components.popUpWindow;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DateUtils;
	import mx.collections.ArrayCollection;	
	import mx.controls.Button;
	import components.application.popupDocuments;	

	public class tabInsuranceClass extends tabCanvasClass
	{
		public function tabInsuranceClass()
		{
			super();
			//defaultListHeight = 110;
			//expandedListHeight = 180;
		}

		include "../insuranceCommon.as";		
	}
}