package components.service
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.DateField;
	import mx.controls.TextInput;
	import mx.controls.TextArea;
	import mx.managers.PopUpManager;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DateUtils;
	import mx.collections.ArrayCollection;
	import components.application.popupDocuments;
	import mx.controls.Button;

	public class tabReviewClass extends tabCanvasClass
	{
		public function tabReviewClass()
		{
			super();
		}

		include "../reviewCommon.as";		
	}
}