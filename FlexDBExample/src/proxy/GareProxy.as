package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	
	public class GareProxy
	{
		private static var _gare:ArrayCollection = new ArrayCollection;
		
		public static function loadGare():void
		{
			QueryHelper.execute("Select * from gare",provideGare, queryError);
		}
		
		private static function provideGare(event:SQLEvent):void
		{
			_gare = new ArrayCollection(event.result.data);
		}
		private static function queryError(event:SQLErrorEvent):void
		{
			Alert.show(event.error);
		}
		
		public static function get Gare():ArrayCollection
		{
			return _gare;
		}
	}
}