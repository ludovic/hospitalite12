/**
	_________________________________________________________________________________________________________________

	DataGridDataExporter is a util-class to export DataGrid's data into different format.	
	@class DataGridDataExporter (public)
	@author Abdul Qabiz (mail at abdulqabiz dot com) 
	@version 0.01 (2/8/2007)
	@availability 9.0+
	@usage<code>DataGridDataExporter.<staticMethod> (dataGridReference)</code>
	@example
		<code>
			var csvData:String = DataGridDataExporter.exportCSV (dg);
		</code>
	__________________________________________________________________________________________________________________

	*/
package common.helper
{
	import mx.collections.ArrayCollection;
	import mx.collections.CursorBookmark;
	import mx.collections.IList;
	import mx.collections.IViewCursor;
	import mx.collections.Sort;
	import mx.collections.XMLListCollection;
	import mx.controls.DataGrid;
	import mx.controls.dataGridClasses.DataGridColumn;

	public class DataGridDataExporter
	{

		public static function exportCSV(dg:DataGrid, csvSeparator:String=";", lineSeparator:String="\n"):String
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
				
					data += "\""+ column.itemToLabel(obj)+ "\"";

					if(k < (columnCount -1))
					{
						data += csvSeparator;
					}

					//generate header of CSV, only if it's not genereted yet
					if (!headerGenerated)
					{
						header += "\"" + column.headerText + "\"";
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

			
			return (header + "\r\n" + data);
		}	
		
		public static function exportCSVFromArrayCollection(ac:ArrayCollection,header:Boolean=true):String
		{
			var text:String = "";
			var rows:int = ac.length;
			if(header)
			{
				for(var n:String in ac[0])
				{
					text = text + n +";";
				}
				text = text + "\n";
			}
			for(var m:int=0;m<rows;++m)
			{
				var myArray:ArrayCollection = new ArrayCollection();
				for(var o:String in ac[m])
				{
					myArray.addItem(o);
				}
				if(!header)
				{
					var sort:Sort = new Sort();
					myArray.sort =sort;
					myArray.refresh();
				}
				for(var i:int=0;i<myArray.length;i++)
				{
					text = text + ac[m][myArray[i]] +";";
				}
				text = text.slice(0,text.length-1);
				text = text + "\n";
			}
			return text;
		}	
	}

}
