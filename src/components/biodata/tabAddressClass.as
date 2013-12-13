package components.biodata
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.collections.ArrayCollection;
	import mx.controls.TextArea;
	import mx.managers.PopUpManager;
	import components.biodata.popUpCountries;
	import mx.controls.Alert;

	public class tabAddressClass extends tabCanvasClass
	{
		public function tabAddressClass()
		{
			super();
			defaultListHeight = 180;
			expandedListHeight = 250;
		}

		include "../addressCommon.as";		
	}
}