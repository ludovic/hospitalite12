package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.interfaces.IQuery;

	public class AffectationProxy
	{
		import phi.db.Database;
		import phi.db.Query;
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _affectation:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		
		public static function loadAffectation():void
		{
			db = Database.getInstance();
			query= new Query();
			query.connect("conn1", db);
			query.addEventListener(Query.QUERY_END, provideAffectation);
			query.addEventListener(Query.QUERY_ERROR,queryError);
			query.execute("Select id_affectation,Service from affectation" )
		}
		
		private static function provideAffectation(evt:Object):void
		{
			_affectation = query.getRecords();
		}
		private static function queryError(evt:Event):void
		{
			Alert.show(query.getError());
		}
		
		public static function get Affectation():ArrayCollection
		{
			return _affectation;
		}
	}
}