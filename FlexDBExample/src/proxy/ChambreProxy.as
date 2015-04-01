package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.interfaces.IQuery;
	
	public class ChambreProxy
	{
		import phi.db.Database;
		import phi.db.Query;
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _chambre:ArrayCollection = new ArrayCollection;
		private static var _chambreDispo:ArrayCollection = new ArrayCollection;
		private static var _chambreNonDispo:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		private static var query2   :IQuery;
		private static var query3   :IQuery;
		
		public static function loadChambre():void
		{
			db = Database.getInstance();
			query= new Query();
			query.connect("conn1", db);
			query.addEventListener(Query.QUERY_END, provideChambre);
			query.addEventListener(Query.QUERY_ERROR,queryError);
			query.execute("Select c.numero, c.libelle, c.lits, c.hebergement, c.etage, c.ascenseur, c.id_module, m.libelle as module_libelle from chambre c, module m where c.id_module=m.id_module" );
		}
		
		private static function provideChambre(evt:Object):void
		{
			_chambre = query.getRecords();
		}
		private static function queryError(evt:Event):void
		{
			Alert.show(query.getError());
		}
		
		public static function get Chambre():ArrayCollection
		{
			return _chambre;
		}
		
		public static function loadChambreDispo():void
		{
			db = Database.getInstance();
			query2= new Query();
			query2.connect("conn1", db);
			query2.addEventListener(Query.QUERY_END, provideChambreDispo);
			query2.addEventListener(Query.QUERY_ERROR,queryError);
			query2.execute("Select c.numero,c.libelle,c.etage, lits,hebergement ,ascenseur,c.id_module, m.libelle as module_libelle from chambre c, etre_disponible ed, module m where c.numero = ed.numero and c.id_module=m.id_module and ed.id_pele =" + index.peleActuel.id_pele+" order by c.libelle" )
		}
		
		private static function provideChambreDispo(evt:Object):void
		{
			_chambreDispo = query2.getRecords();
		}
		
		
		public static function get ChambreDispo():ArrayCollection
		{
			return _chambreDispo;
		}
		
		public static function loadChambreNonDispo():void
		{
			db = Database.getInstance();
			query3= new Query();
			query3.connect("conn1", db);
			query3.addEventListener(Query.QUERY_END, provideChambreNonDispo);
			query3.addEventListener(Query.QUERY_ERROR,queryError);
			query3.execute("Select * from chambre c where c.numero not in (select ed.numero from  etre_disponible ed where ed.id_pele =" + index.peleActuel.id_pele+")" )
		}
		
		private static function provideChambreNonDispo(evt:Object):void
		{
			_chambreNonDispo = query3.getRecords();
		}
		
		
		public static function get ChambreNonDispo():ArrayCollection
		{
			return _chambreNonDispo;
		}
	}
}