package components.biodata
{
	import packages.popupDualWindowClass;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.collections.ArrayCollection;
	import mx.controls.TextArea;
	import mx.controls.DateField;
	import packages.DateUtils;
	import mx.managers.PopUpManager;
	import components.biodata.popUpCountries;
	import mx.rpc.events.ResultEvent;

	public class popupVisaClass extends popupDualWindowClass
	{
		public function popupVisaClass()
		{
			super();
		}

		include "../visaCommon.as";		
	}
}