package components.admin
{
	import mx.controls.TextInput;	
	import mx.controls.Button;
	import mx.controls.Label;
	import packages.basicCanvasClass;	
	import mx.collections.ArrayCollection;
	import mx.events.CollectionEvent;
	import mx.controls.Alert;
	import packages.userPermissionObject;
	import mx.rpc.events.ResultEvent;
	import packages.ComboBoxNew;
	import packages.DataGridNew;
	import mx.controls.DataGrid;
	import mx.rpc.http.mxml.HTTPService;
	import mx.events.CloseEvent;
	import mx.controls.CheckBox;
	import mx.managers.PopUpManager;
   	import mx.containers.TitleWindow;
		
	public class userpriviledgeClass extends grouppriviledgeClass
	{
		public function userpriviledgeClass()
		{
			super();
		}
		
		include "../userpriviledgeCommon.as";
	}
}
