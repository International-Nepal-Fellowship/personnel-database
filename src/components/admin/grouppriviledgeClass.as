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
	
	public class grouppriviledgeClass extends basicCanvasClass
	{
		public function grouppriviledgeClass()
		{
			super();
		}
		include "../grouppriviledgeCommon.as";
	}
}
