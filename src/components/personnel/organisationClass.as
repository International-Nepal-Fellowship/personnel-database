package components.personnel
{
	import mx.controls.TextInput;
	import packages.ComboBoxNew;
	import mx.controls.CheckBox;	
	import packages.adminDualCanvasClass;
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

	public class organisationClass extends adminDualCanvasClass
	{
		public function organisationClass()
		{
			super();
		}
		
		include "../organisationCommon.as";	
	}
}