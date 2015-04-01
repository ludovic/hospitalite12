package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.interfaces.IQuery;
	
	public class ModuleProxy
	{
		import phi.db.Database;
		import phi.db.Query;
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _moduleDispo:ArrayCollection = new ArrayCollection;
		private static var query2   :IQuery;
		
		
		private static function queryError(evt:Event):void
		{
			Alert.show(query2.getError());
		}
		
		public static function loadModuleDispo():void
		{
			db = Database.getInstance();
			query2= new Query();
			query2.connect("conn1", db);
			query2.addEventListener(Query.QUERY_END, provideModuleDispo);
			query2.addEventListener(Query.QUERY_ERROR,queryError);
			query2.execute("Select m.id_module, m.libelle, m.etage from chambre c, etre_disponible ed, module m where c.numero = ed.numero and c.id_module=m.id_module and ed.id_pele =" + index.peleActuel.id_pele+" GROUP BY m.id_module " )
		}
		
		private static function provideModuleDispo(evt:Object):void
		{
			_moduleDispo = query2.getRecords();
		}
		
		
		public static function get ModuleDispo():ArrayCollection
		{
			return _moduleDispo;
		}
		
	}
}