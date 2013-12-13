package components.personnel
{
	import packages.adminDualCanvasClass;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.collections.ArrayCollection;
	import mx.controls.TextArea;
	import mx.controls.DateField;
	import packages.DateUtils;
	import mx.managers.PopUpManager;
	import components.biodata.popUpCountries;
	import mx.rpc.events.ResultEvent;

	public class visaClass extends adminDualCanvasClass
	{
		public function visaClass()
		{
			super();
		}

		include "../visaCommon.as";		
	}
}