package components.service
{
	import packages.ComboBoxNew;
	import mx.controls.DateField;
	import mx.controls.TextInput;
	import mx.controls.CheckBox;	
	import packages.popupWindowClass;
	import packages.DateUtils;
	import mx.managers.PopUpManager;
	import components.popUpWindow;
	import components.application.popupDocuments;
	
	public class popupCoursesClass extends popupWindowClass
	{
		public function popupCoursesClass()
		{
			super();
		}

		include "../coursesCommon.as";
	}
}