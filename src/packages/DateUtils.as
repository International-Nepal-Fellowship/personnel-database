package packages
{
	import mx.controls.DateField;
	import mx.formatters.DateFormatter;
	import mx.utils.ObjectUtil;

	public class DateUtils extends DateFormatter
	{
		public function DateUtils()
		{
			super();
		}
		
		public static function parseDate(string:String):Date {
			
			return DateFormatter.parseDateString(string);
		}
		
		public static function dateFieldToDate(datefld:DateField):Date {
			
			return DateField.stringToDate(datefld.text,datefld.formatString);
		}

		public static function dateToString(value:Date,formatString:String):String {
			
			return DateField.dateToString(value,formatString);
		}

		public static function dateFieldToString(datefld:DateField,formatString:String=""):String {
			
			if (formatString == "")
				return dateToString(dateFieldToDate(datefld),datefld.formatString);
			else
				return dateToString(dateFieldToDate(datefld),formatString);
		}
		
		public static function stringToDateFieldString(string:String,datefld:DateField,formatString:String=""):String {
			
			if (string == null) string = "0000-00-00";
			
			if (formatString == "")
				return dateToString(DateField.stringToDate(string,datefld.formatString),datefld.formatString);
			else
				return dateToString(DateField.stringToDate(string,formatString),datefld.formatString);
		}
						
		public static function compareDateFieldDates(datefld1:DateField,datefld2:DateField):int {
			
			return ObjectUtil.dateCompare(dateFieldToDate(datefld1),dateFieldToDate(datefld2));
		}
	}
}