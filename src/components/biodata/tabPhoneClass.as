package components.biodata
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.collections.ArrayCollection;
	import mx.managers.PopUpManager;
	import components.biodata.popUpCountries;
	import mx.controls.Button;

	public class tabPhoneClass extends tabCanvasClass
	{
		public function tabPhoneClass()
		{
			super();
			defaultListHeight = 180;
			expandedListHeight = 250;
		}

		include "../phoneCommon.as";		
	}
}