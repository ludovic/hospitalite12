package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.interfaces.IQuery;
	
	public class CarProxy
	{
		import phi.db.Database;
		import phi.db.Query;
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _car:ArrayCollection = new ArrayCollection;
		private static var _carDispo:ArrayCollection = new ArrayCollection;
		private static var _carDispoWithGare:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		private static var query2    :IQuery;
		private static var query3    :IQuery;
		
		public static function loadCarDispo():void
		{
			db = Database.getInstance();
			query= new Query();
			query.connect("conn1", db);
			query.addEventListener(Query.QUERY_END, provideCarDispo);
			query.addEventListener(Query.QUERY_ERROR,queryError);
			query.execute("Select * from transport where id_transport in (select id_transport from passer_par where id_pele =" + index.peleActuel.id_pele+" and id_transport<>0) " )
		}
		
		private static function provideCarDispo(evt:Object):void
		{
			_carDispo = query.getRecords();
		}
		private static function queryError(evt:Event):void
		{
			Alert.show((evt.target as Query).getError());
		}
		
		public static function get CarDispo():ArrayCollection
		{
			return _carDispo;
		}
		
		public static function loadCarDispoWithGare():void
		{
			db = Database.getInstance();
			query3= new Query();
			query3.connect("conn1", db);
			query3.addEventListener(Query.QUERY_END, provideCarDispoWithGare);
			query3.addEventListener(Query.QUERY_ERROR,queryError);
			query3.execute("Select t.id_transport,g.id_gare,g.nom,pp.heure_aller from transport t, passer_par pp, gare g  where t.id_transport = pp.id_transport and g.id_gare = pp.id_gare and t.id_transport<>0 and pp.id_pele =" + index.peleActuel.id_pele)
		}
		
		private static function provideCarDispoWithGare(evt:Object):void
		{
			_carDispoWithGare = query3.getRecords();
		}
		
		public static function get CarDispoWithGare():ArrayCollection
		{
			return _carDispoWithGare;
		}
	}
}