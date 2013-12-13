package components.biodata
{
	import packages.tabCanvasClass;
	import mx.controls.CheckBox;
	import packages.ComboBoxNew;
	import mx.controls.TextInput;
	import mx.collections.ArrayCollection;

	public class tabEmailClass extends tabCanvasClass
	{
		public function tabEmailClass()
		{
			super();
			defaultListHeight = 180;
			expandedListHeight = 250;
		}
		
		include "../emailCommon.as";		
	}
}