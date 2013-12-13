package components.biodata
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import mx.controls.Image;
	import mx.controls.TextInput;
	import mx.collections.ArrayCollection;
	import mx.controls.TextArea;
	import flash.events.DataEvent;
	import flash.events.IOErrorEvent;
	import flash.net.FileFilter;
	import flash.net.FileReference;
	import flash.net.URLRequest;
	import mx.controls.Alert;
	import mx.rpc.http.mxml.HTTPService;

	public class tabPhotoClass extends tabCanvasClass
	{
		public function tabPhotoClass()
		{
			super();
			defaultListHeight = 208;
			expandedListHeight = 278;
		}

		include "../photoCommon.as";		
	}
}