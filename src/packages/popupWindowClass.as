package packages
{
	import mx.containers.TitleWindow;
	import mx.controls.Alert;
	import mx.controls.Button;
	import packages.ComboBoxNew;
	import packages.DataGridNew;
	import mx.controls.DataGrid;
	import mx.controls.Label;
	import mx.controls.TextInput;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.mxml.HTTPService;
	import mx.rpc.soap.SOAPFault;
	import mx.managers.PopUpManager;         				

	public class popupWindowClass extends basicPopupClass
	{		
		public function popupWindowClass()
		{
			super();
		}

		include "adminCommon.as";	
	}
}