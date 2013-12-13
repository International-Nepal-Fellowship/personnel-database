package components.application
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
	
	public class popupDocumentsClass extends basicPopupClass
	{
		public function popupDocumentsClass()
		{
			super();
		}
		
		include "../documentsCommon.as";

	}
}
