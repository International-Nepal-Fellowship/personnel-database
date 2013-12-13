package components.application
{	
	import packages.tabCanvasClass;    
    import mx.controls.Alert;
    import packages.ComboBoxNew;
	import mx.controls.DateField;
	import mx.controls.TextInput;
	import mx.controls.TextArea;
	import packages.DateUtils;	
	import mx.collections.ArrayCollection;
	import components.popUpWindow;
	import components.biodata.popUpCountries;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	
	public class tabWorkExperienceClass extends tabCanvasClass
	{
		public function tabWorkExperienceClass()
		{
			super();
			defaultListHeight = 180;
			expandedListHeight = 250;
		}
	include "../workExperienceCommon.as";
	}
}