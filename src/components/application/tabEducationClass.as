package components.application
{	
	import packages.tabCanvasClass;    
    import mx.controls.Alert;
    import packages.ComboBoxNew;
    import components.application.popUpQualification;
	import components.application.popUpSpeciality;
	import mx.controls.DateField;
	import mx.controls.TextInput;
	import packages.DateUtils;	
	import mx.collections.ArrayCollection;
	import components.popUpWindow;
	import mx.managers.PopUpManager;
	import flash.events.DataEvent;
	import flash.events.IOErrorEvent;
	import flash.net.FileFilter;
	import flash.net.FileReference;
	import flash.net.URLRequest;
	import mx.controls.Image;
	import mx.rpc.http.mxml.HTTPService;
	
	public class tabEducationClass extends tabCanvasClass
	{
		public function tabEducationClass()
		{
			super();
			//defaultListHeight = 200;
			//expandedListHeight = 270;
		}
	include "../educationCommon.as";
	}
}