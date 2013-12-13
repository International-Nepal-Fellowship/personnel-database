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
	
	public class tabRegistrationsClass extends tabCanvasClass
	{
		public function tabRegistrationsClass()
		{
			super();
			//defaultListHeight = 200;
			//expandedListHeight = 270;
		}
	include "../registrationsCommon.as";
	}
}