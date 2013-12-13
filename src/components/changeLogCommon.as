// ActionScript file
import mx.collections.ArrayCollection;
import mx.collections.Sort;
import mx.collections.SortField;
import mx.controls.Button;
import mx.controls.dataGridClasses.DataGridColumn;

import packages.DataGridNew;

		public var sortDG:Button;
		public var requestChangeLogData:HTTPService;

        [Bindable]private var sortA:Sort; 
         
        private var sortBy0:SortField;
        private var sortBy1:SortField;
        private var sortBy2:SortField;
        private var sortBy3:SortField;
        private var columns:Array;
        private var col:DataGridColumn; 
        public var myDPColl1:ArrayCollection; 
		private var columnOrder:Array = new Array();

        public function sortChangeLogDataGrid(myGrid:DataGridNew):void {
			if(dgList.dataProvider.length<2) return;
            columnOrder = dgList.columns;
           // columnOrder = myGrid.columns;
            sortA = new Sort();
            
            // A true second parameter specifies a case-insensitive sort.
            // A true third parameter specifies descending sort order.
            // A true fourth parameter specifies a numeric sort.
            columns=dgList.columns;
            	col=columns[0];             
             	sortBy0 = new SortField(col.headerText, true);   
             	col=columns[1];              
             	sortBy1 = new SortField(col.headerText, true);		
             	col=columns[2];           
             	sortBy2 = new SortField(col.headerText, true);	
             	col=columns[3];           
             	sortBy3 = new SortField(col.headerText, true);		             
             	
             	sortA.fields=[sortBy0, sortBy1, sortBy2, sortBy3];  
        
            myDPColl1.sort=sortA; 
          	myDPColl1.refresh();
            dgList.dataProvider=myDPColl1;            
            dgList.columns = columnOrder;
            
            dgList.rowCount=myDPColl1.length +1;   //number of rows plus 1 (for headers)         
                        
        }   

override protected function loadData(current:Boolean=false):void{					
	super.loadData();			                	
    getChangeLogData();        	      	             				
}

public function getChangeLogData():void{
	parentApplication.searching=true;
	var parameters:Object			=	new Object();	
	requestChangeLogData.useProxy	=	false;
	requestChangeLogData.url		=	parentApplication.getPath()	+	"getChangeLogData.php";
	requestChangeLogData.send();	
}
    
public function changeLogResult(event:ResultEvent):void{		
	super.defaultResult(event);	 	
	dgList.dataProvider=requestChangeLogData.lastResult.changeLog.changeLog; 
	if(dgList.dataProvider.length>1)
		myDPColl1=  requestChangeLogData.lastResult.changeLog.changeLog; 
	  parentApplication.searching=false;              
}  
    