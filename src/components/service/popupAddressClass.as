package components.service
{
	import packages.tabPopupClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.controls.TextArea;
	import mx.collections.ArrayCollection;
	import mx.managers.PopUpManager;
	import components.biodata.popUpCountries;
	import mx.controls.Alert;

	public class popupAddressClass extends tabPopupClass
	{
		public function popupAddressClass()
		{
			super();
		}

		include "../addressCommon.as";		
	}
}