/**
	_________________________________________________________________________________________________________________

	DataGridDataExporter is a util-class to export DataGrid's data into different format.	
	@example
		<code>
			var csvData:String = DataGridDataExporter.exportCSV (dg);
		</code>
	__________________________________________________________________________________________________________________

	*/
package packages
{
	import flash.system.System;
	
	import mx.collections.IViewCursor;
	//import mx.controls.Alert;
	import mx.controls.DataGrid;
	import mx.controls.dataGridClasses.DataGridColumn;
	import mx.controls.Alert;

	public class DataGridDataExporter
	{

		public static function exportCSV(dg:DataGrid, csvSeparator:String="\t", lineSeparator:String="\n"):String
		{
			var data:String = "";
			var columns:Array = dg.columns;
			var columnCount:int = columns.length;
			var column:DataGridColumn;
			var header:String = "";
			var headerGenerated:Boolean = false;
			var dataProvider:Object = dg.dataProvider;

			var rowCount:int = dataProvider.length;
			var dp:Object = null;
			
		

			
			var cursor:IViewCursor = dataProvider.createCursor ();
			var j:int = 0;
			
			//loop through rows
			while (!cursor.afterLast)
			{
				var obj:Object = null;
				obj = cursor.current;
				
				//loop through all columns for the row
				for(var k:int = 0; k < columnCount; k++)
				{
					column = columns[k];
					
					//Exclude column data which is invisible (hidden)
					if(!column.visible)
					{
						continue;
					}
				
					//data += "\""+ column.itemToLabel(obj)+ "\"";
					data += column.itemToLabel(obj);
					
					//var dataStr:String=column.itemToLabel(obj);
					//dataStr=dataStr.replace( "\n", ' NewLine ' );
					//	Alert.show(dataStr);
					

					if(k < (columnCount -1))
					{
						data += csvSeparator;
					}

					//generate header of CSV, only if it's not genereted yet
					if (!headerGenerated)
					{
						//header += "\"" + column.headerText + "\"";
						header += column.headerText;
						if (k < columnCount - 1)
						{
							header += csvSeparator;
						}
					}
					
				
				}
				
				headerGenerated = true;

				if (j < (rowCount - 1))
				{
					data += lineSeparator;
				}

				j++;
				cursor.moveNext ();
			}
			
			//set references to null:
			dataProvider = null;
			columns = null;
			column = null;

			 // Copy the data to the clipboard
           	System.setClipboard(header + "\r\n" + data);
           	Alert.show("Data copied to clipboard");
			return (header + "\r\n" + data);
		}	
	}

}
