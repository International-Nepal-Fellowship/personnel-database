package components.application
{
	import mx.controls.TextInput;
	import packages.ComboBoxNew;
	import mx.controls.CheckBox;	
	import packages.popupDualWindowClass;
	import components.service.popUpAddress;
	import components.service.popUpEmail;
	import components.service.popUpPhone;
	import mx.managers.PopUpManager;	
	import mx.collections.ArrayCollection;
	import mx.collections.IViewCursor;
	import mx.controls.dataGridClasses.DataGridColumn;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.mxml.HTTPService;
	import packages.DataGridNew;
	import mx.controls.Label;

	public class popupOrganisationClass extends popupDualWindowClass
	{
		public function popupOrganisationClass()
		{
			super();
		}
				
		include "../organisationCommon.as";
	}
}