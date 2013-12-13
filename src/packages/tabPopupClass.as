package packages
{
	import mx.controls.Alert;
	import mx.controls.Button;
	import packages.ComboBoxNew;
	import packages.DataGridNew;
	import mx.controls.DataGrid;
	import mx.controls.CheckBox;
	import mx.controls.Label;
	import mx.controls.TextInput;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.mxml.HTTPService;
	import mx.rpc.soap.SOAPFault; 
	import mx.managers.PopUpManager;
	import mx.collections.ArrayCollection;
	import mx.containers.TitleWindow;
	import components.biodata.TitleWindowData; 
	import flash.events.KeyboardEvent;
	import flash.system.System;
	import mx.utils.ObjectProxy; 
	import packages.DataGridDataExporter; //for copying the datagrid's data to clipboard      				

	public class tabPopupClass extends basicPopupClass
	{	
		public function tabPopupClass()
		{
			super();
		}

		include "tabCommon.as";
	}
}
