package common.helper
{
	import com.adobe.utils.DateUtil;

	public class Tools
	{
		public static function dateToString(date:Date):String
		{
			if(date != null)
				var dateString:String = date.fullYear+"-"+(date.month+1)+"-"+date.date+" "+date.hours+":"+date.minutes+":"+date.seconds;    
			else
				dateString ="";
			return dateString;
		}
		
		public static function stringToDate(value:String):Date
		{
			
			if(value != null)
			{
				var myArray:Array = value.split("-");
				var partHour:String = (myArray[2] as String).split(" ")[1] as String;
				if(partHour == null)
				{
					var date:Date = new Date(myArray[0],myArray[1]-1,(myArray[2] as String).split(" ")[0]); 
				}	
				else
				{
					var arrayHour:Array = partHour.split(":");
					date = new Date(myArray[0],myArray[1]-1,(myArray[2] as String).split(" ")[0],arrayHour[0],arrayHour[1],arrayHour[2]); 
				}
			}
			else
				date =new Date();
			return date;
		}
		
		public static function sanitize(text:String):String
		{
			return replaceAll(text,"'", "\\'");
		}
		
		public static function replaceAll(myText:String, replace:String, replaceWith:String):String
		{
			myText = myText.replace(new RegExp(replace, 'g'), replaceWith);
			return myText;
		}
		
		public static function ouiNon(value:Boolean):String
		{
			if(value)
				return "oui";
			else
				return "non";
		}
		
		public static function isMineur(dateNaissance:Date,datePele:Date):Boolean
		{
			var ans:Number = (datePele.getTime()- dateNaissance.getTime())/(1000*3600*24*365.25);
			return (ans<18);
		}
		
		public static function age(dateNaissance:Date,datePele:Date):Number
		{
			var ans:Number = (datePele.getTime()- dateNaissance.getTime())/(1000*3600*24*365.25);
			return Math.floor(ans);
		}
	}
}