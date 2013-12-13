package components.biodata
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.Image;
	import mx.controls.TextInput;
	import mx.collections.ArrayCollection;
	import mx.controls.TextArea;
	import mx.controls.DateField;
	import flash.events.DataEvent;
	import flash.events.IOErrorEvent;
	import flash.net.FileFilter;
	import flash.net.FileReference;
	import flash.net.URLRequest;
	import mx.controls.Alert;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DateUtils;
	import mx.managers.PopUpManager;
	import components.biodata.popUpCountries;
	import mx.rpc.events.ResultEvent;
	import components.service.popUpPost;

	public class tabVisahistoryClass extends tabCanvasClass
	{
		public function tabVisahistoryClass()
		{
			super();
			defaultListHeight = 138;
			expandedListHeight = 208;
		}

		include "../visahistoryCommon.as";		
	}
}