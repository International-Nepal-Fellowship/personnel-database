package packages
{   
	import mx.containers.Canvas;
	import mx.controls.Alert;
	import mx.controls.Button;
	import packages.ComboBoxNew;
	import packages.DataGridNew;
	import mx.controls.DataGrid;
	import mx.controls.Label;
	import mx.controls.TextInput;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.mxml.HTTPService;
	import mx.rpc.soap.SOAPFault; 
	import mx.managers.PopUpManager; 
	import mx.collections.IViewCursor;
	import mx.controls.CheckBox;
	import mx.controls.dataGridClasses.DataGridColumn;
	import mx.collections.ArrayCollection;
	     				
	public class adminDualCanvasClass extends adminCanvasClass
	{	
		public function adminDualCanvasClass()
		{
			super();
		}
		
		include "dualCommon.as";
	}
}