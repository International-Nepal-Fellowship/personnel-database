package components
{	
	import flash.events.IOErrorEvent;
	import flash.net.FileFilter;
	import flash.net.FileReference;
	import flash.net.URLRequest;
	import mx.controls.Alert;
	import mx.rpc.http.mxml.HTTPService;
	import mx.controls.Button;
	import flash.events.DataEvent;
	import mx.rpc.events.ResultEvent;
	import mx.controls.TextInput;	
	import packages.basicPopupClass;
	import flash.events.MouseEvent;
	import packages.ComboBoxNew;
	import packages.DataGridNew;
	import mx.events.ListEvent;
	import flash.events.KeyboardEvent;
	import mx.controls.DataGrid;

	//import components.DGIR_DynamicDP; 
	
	public class popupSearchClass extends basicPopupClass
	{
		public function popupSearchClass()
		{
				super();
		}
		include "popupSearchCommon.as";
	}
}
