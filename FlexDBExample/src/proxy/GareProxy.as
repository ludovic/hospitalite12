package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.interfaces.IQuery;
	
	public class GareProxy
	{
		import phi.db.Database;
		import phi.db.Query;
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _gare:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		
		public static function loadGare():void
		{
			db = Database.getInstance();
			query= new Query();
			query.connect("conn1", db);
			query.addEventListener(Query.QUERY_END, provideGare);
			query.addEventListener(Query.QUERY_ERROR,queryError);
			query.execute("Select * from gare" )
		}
		
		private static function provideGare(evt:Object):void
		{
			_gare = query.getRecords();
		}
		private static function queryError(evt:Event):void
		{
			Alert.show(query.getError());
		}
		
		public static function get Gare():ArrayCollection
		{
			return _gare;
		}
	}
}