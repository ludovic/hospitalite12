package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.db.Database;
	import phi.db.Query;
	import phi.interfaces.IDatabase;
	import phi.interfaces.IQuery;
	
	public class ProfessionSanteProxy
	{
		private  static var db       :IDatabase;
		private static var _profession:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		
		public static function load():void
		{
			db = Database.getInstance();
			query= new Query();
			query.connect("conn1", db);
			query.addEventListener(Query.QUERY_END, provide);
			query.addEventListener(Query.QUERY_ERROR,queryError);
			query.execute("Select id_profession_sante,Profession from profession_sante" );
		}
		
		private static function provide(evt:Object):void
		{
			_profession = query.getRecords();
		}
		private static function queryError(evt:Event):void
		{
			Alert.show(query.getError());
		}
		
		public static function get Profession():ArrayCollection
		{
			return _profession;
		}
	}
}