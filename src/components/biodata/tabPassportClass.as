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

	public class tabPassportClass extends tabCanvasClass
	{
		public function tabPassportClass()
		{
			super();
		}

		include "../passportCommon.as";		
	}
}