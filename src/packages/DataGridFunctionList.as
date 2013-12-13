package packages
{
	public class DataGridFunctionList
	{
	import mx.controls.DataGrid;
	import mx.controls.dataGridClasses.DataGridColumn;
	import mx.collections.ArrayCollection;
	import mx.collections.XMLListCollection;
	import mx.collections.IList;
	import mx.collections.IViewCursor;
	import mx.collections.CursorBookmark;

		

		public static function returnSelectedRowValue(dg:DataGrid,selIndex:int,colNumber:int):String
		{
			var data:String = "";
			var columns:Array = dg.columns;		
			var column:DataGridColumn;
		
			var dataProvider:Object = dg.dataProvider;
			

		
			var cursor:IViewCursor = dataProvider.createCursor ();		
			var obj:Object = null;
		
				
				//loop through all columns for the row
				for(var k:int = 0; k <selIndex; k++){	
										
					cursor.moveNext();	
				
				}
			obj = cursor.current;	
			column = columns[colNumber];
			data =column.itemToLabel(obj);		
			
			
			return (data);
		}	
	}
}