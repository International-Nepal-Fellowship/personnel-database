package packages
{
import flash.events.FocusEvent;
import flash.events.KeyboardEvent;
import flash.ui.Keyboard;
import flash.utils.Timer;
import flash.events.TimerEvent;

import mx.collections.ArrayCollection;
import mx.controls.DataGrid;

/** 
 *  ComboBox without CTRL key
 */
	
/**
 * Type-ahead Combobox allows the user to match ComboBox options by typing in the first few characters.
 *
 * @author Oliver Merk - (oliverm@olivermerk.com)
 */
	 	
public class DataGridNew extends DataGrid
{	
	private var searchText:String = "";
	private var searchRegEx:RegExp;
	private var savedIndex:int = -1;
	private var t:Timer;
	
	public function DataGridNew()
	{	
		t = new Timer(200,10);
        t.addEventListener(TimerEvent.TIMER_COMPLETE, resetSearchString);
		super();
	}

	private function startTimer():void{
			
        t.start();
	}
	
	private function resetTimer():void{
		
		t.reset();
		t.start();
	}
		
    private function resetSearchString(event:TimerEvent):void{ 
    	
    	searchText = "";
		savedIndex = this.selectedIndex; 
    }
    
	public function getSelected(i:int=-2):String {
		
		var firstColumn:Object = this.columns[0].dataField;
		
		if (i == -2) i = this.selectedIndex;
		if (i<0) return "";
		
		if ( this.dataProvider[i][firstColumn] is String ) {
			return (this.dataProvider[i][firstColumn]);
		}
		else {
			return (String(this.dataProvider[i][firstColumn]));
		}
	}

	public function setSelected(item:String):void {
		
		var firstColumn:Object = this.columns[0].dataField;
		this.selectedItem[firstColumn] = item;
	}
			
	override protected function focusInHandler(event:FocusEvent):void {
		
		resetSearchString(null);
		//trace("focus in saving position at: "+this.selectedIndex);
		super.focusInHandler(event);
	}
	
	// remove event if ctrl key down
	override protected function keyDownHandler(event:KeyboardEvent):void {
		
		if (!event.ctrlKey)
			super.keyDownHandler(event);
		//else
			//trace(event.charCode);
	}

	/** Each time the user presses a key, filter the ComboBox items to match. */
	override protected function keyUpHandler(event:KeyboardEvent):void { 

		if (event.ctrlKey) {
			return;
		} 

		resetTimer();
		
		// Number or letter entered
		if ( isAlphaChar( event.keyCode ) ) {

			//trace("alpha keycode: "+event.keyCode);
			searchText = searchText+String.fromCharCode(event.charCode);
			doSearch(searchText);
		}
		else {
			if ( isDelChar( event.keyCode ) ) {	
				//trace("delete keycode: "+event.keyCode);
				backSearch();
			}
			else {
				if ( isEscChar( event.keyCode ) ) {	
					//trace("esc keycode: "+event.keyCode);	
					searchText = "";
					this.selectedIndex = savedIndex;
					//trace("restoring position to: "+this.selectedIndex);				
				}	
				else 
				{
					//trace("passing on keycode: "+event.keyCode);
					resetSearchString(null);
					//trace("saving position at: "+this.selectedIndex);
					super.keyUpHandler(event);
				}
			}
		}
	}

	private function backSearch():void{
		
		var searchLen:uint = searchText.length;

		if (searchLen > 0) {
			searchText = searchText.substr(0,searchLen-1);
			this.selectedIndex = 0;
			doSearch(searchText);					
		}
	}

	public function doSearch(searching:String):void {
	
		var done:Boolean = false;
		var match:Boolean = false;
		var maxIndex:int = this.dataProvider.length - 1;
		var i:uint = 0;
		
		// Set up the search expression
		searchRegEx = new RegExp( searching, 'i' );
		
		while (!done) {
			if (i > maxIndex) {
				done = true;
			}
			else {
				var searchIn:String = getSelected(i);
				//trace("searching: "+searchIn+": "+"("+i+")");
				match = (searchIn.search( searchRegEx ) == 0 );
				if (match) {
					//trace("match for '"+searching+ "' found for: "+searchIn);
					this.selectedIndex = i;	
					this.scrollToIndex(i);
					//trace("match at: "+this.selectedIndex);
					//ArrayCollection( this.dataProvider ).refresh( );
					//trace("match after: "+this.selectedIndex);
					done = true;				
				}
			}
			i = i + 1;
		}
			
		if (!match) {
			//trace("no match for '"+searching+"'");
			backSearch();
		}		
	}	

	/** Filter out any non-alphanumeric key strokes */
	private function isAlphaChar( keyCode:int ):Boolean {

		var isAlpha:Boolean = false;

		if (
			( keyCode > 47 && keyCode < 58 )
			||
			( keyCode > 64 && keyCode < 91 )
			||
			( keyCode > 96 && keyCode < 123 )
			||
			( keyCode == Keyboard.SPACE )
			||
			( keyCode == 189 ) //-
			||
			( keyCode == 187 ) //+
			) {
			isAlpha = true;
		}

		return isAlpha;
	}
	
		/** Filter out any non-delete key strokes */
	private function isDelChar( keyCode:int ):Boolean {

		var isDel:Boolean = false;

		if (
			( keyCode == Keyboard.BACKSPACE )
			) {
			isDel = true;
		}

		return isDel;
	}
	
	/** Filter out any non-escape key strokes */
	private function isEscChar( keyCode:int ):Boolean {

		var isEsc:Boolean = false;

		if (
			( keyCode == Keyboard.DELETE)
			) {
			isEsc = true;
		}

		return isEsc;
	}
}
}