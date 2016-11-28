package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	import phi.interfaces.IQuery;
	
	public class GareProxy
	{
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _gare:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		
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