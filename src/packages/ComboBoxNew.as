package packages
{
import flash.events.Event;
import flash.events.FocusEvent;
import flash.events.KeyboardEvent;
import flash.ui.Keyboard;
import flash.utils.Timer;
import flash.events.TimerEvent;
import mx.events.ListEvent;

import mx.collections.ArrayCollection;
import mx.controls.ComboBox;

/** 
 *  ComboBox without CTRL key
 */
	
/**
 * Type-ahead Combobox allows the user to match ComboBox options by typing in the first few characters.
 *
 * @author Oliver Merk - (oliverm@olivermerk.com)
 */
	 	
public class ComboBoxNew extends ComboBox
{	
	private var searchText:String = "";
	private var searchRegEx:RegExp;
	private var savedIndex:int = -1;
	private var t:Timer;

	public function ComboBoxNew()
	{	
		t = new Timer(150,10);
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
		
		if (i == -2) i = this.selectedIndex;
		if (i<0) return "";
		
		if ( this.dataProvider[i] is String ) {
			return (this.dataProvider[i]);
		}
		else {
			if ( this.dataProvider[i] is Number ) {
				return (this.dataProvider[i].toString());
			}
			else return (this.dataProvider[i].value);
		}
	}
		
	override public function close(trigger:Event=null):void {
				
		if (trigger != null) {
			resetSearchString(null);
			trace("close "+trigger +" - saving position at: "+this.selectedItem + " ("+this.selectedIndex+")");
			this.dispatchEvent(new ListEvent(ListEvent.CHANGE, true, false));
		}
		trace("close");
		super.close(trigger);		
	}
	
	override protected function focusInHandler(event:FocusEvent):void {
		
		resetSearchString(null);		
		trace("focus in saving position at: "+this.selectedItem + " ("+this.selectedIndex+")");
		super.focusInHandler(event);
	}
			
	// remove event if ctrl key down
	override protected function keyDownHandler(event:KeyboardEvent):void {

		//trace("keydown "+event.ctrlKey+" "+event.keyCode);

		if (event.keyCode == Keyboard.ENTER  ) { //close the combo dropdown and select current item
			trace("closing");
			this.dispatchEvent(new ListEvent(ListEvent.CHANGE, true, false));
			//this.open();
			this.close();
			return;
		}
		
		if (!event.ctrlKey)
			super.keyDownHandler(event);
		//else
			//trace(event.charCode);
	}

	/** Each time the user presses a key, filter the ComboBox items to match. */
	override protected function keyUpHandler(event:KeyboardEvent):void {
	
		//trace("keyup "+event.ctrlKey+" "+event.keyCode);
		//trace("keyup "+event.target+", "+event.currentTarget);
		
		if (event.ctrlKey) {
			if (event.keyCode == Keyboard.DOWN) {
				//trace("opening");
				this.open();
			}
			return;
		} 

		//trace("open = "+this.dropdown.visible);
		// Number or letter entered
		resetTimer();
		
		if ( isAlphaChar( event.keyCode ) ) {

			//trace("alpha keycode: "+event.keyCode);
			searchText = searchText+String.fromCharCode(event.charCode);
			if ( searchText != "" ) doSearch(searchText);
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
					//trace("restoring position to: "+this.selectedItem + " ("+this.selectedIndex+")");				
				}	
				else 
				{
					//trace("passing on keycode: "+event.keyCode);
					resetSearchString(null);
					//trace("keyup saving position at: "+this.selectedItem + " ("+this.selectedIndex+")");
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
		if ((this.dataProvider[0]=="None") || (this.dataProvider[0]=="Select..")) i = 1;  // skip first element (either None or Select)
		
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
					trace("match for '"+searching+ "' found for: "+searchIn);
					this.selectedIndex = i;	
					trace("match at: "+this.selectedIndex);	
					//trace("open2 = "+this.dropdown.visible);
					if (this.dropdown.visible) { //if dropdown is open, update it
						var savedSearchText:String = searchText;
						ArrayCollection( this.dataProvider ).refresh( );
						this.open(); //refresh closes the dropdown so we need to reopen it
						this.dropdown.scrollToIndex(i);
						searchText = savedSearchText;
						//trace("open3 = "+this.dropdown.visible);
					}
					done = true;				
				}
			}
			i = i + 1;
		}
			
		if (!match) {
			trace("no match for '"+searching+"'");
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
