package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	
	public class ChambreProxy
	{
		private static var _chambre:ArrayCollection = new ArrayCollection;
		private static var _chambreDispo:ArrayCollection = new ArrayCollection;
		private static var _chambreNonDispo:ArrayCollection = new ArrayCollection;
		
		public static function loadChambre():void
		{
			QueryHelper.execute("Select c.numero, c.libelle, c.lits, c.hebergement, c.etage, c.ascenseur, c.id_module, m.libelle as module_libelle from chambre c, module m where c.id_module=m.id_module" ,provideChambre, queryError);
		}
		
		private static function provideChambre(evt:SQLEvent):void
		{
			_chambre = new ArrayCollection(evt.result.data);
		}
		private static function queryError(evt:SQLErrorEvent):void
		{
			Alert.show(evt.error);
		}
		
		public static function get Chambre():ArrayCollection
		{
			return _chambre;
		}
		
		public static function loadChambreDispo():void
		{
			var queryText:String = "Select c.numero,c.libelle,c.etage, lits,hebergement ,ascenseur,c.id_module, m.libelle as module_libelle from chambre c, etre_disponible ed, module m where c.numero = ed.numero and c.id_module=m.id_module and ed.id_pele =" + index.peleActuel.id_pele+" order by c.libelle" ;
			QueryHelper.execute(queryText, provideChambreDispo, queryError);
		}
		
		private static function provideChambreDispo(evt:SQLEvent):void
		{
			_chambreDispo = new ArrayCollection(evt.result.data);
		}
		
		
		public static function get ChambreDispo():ArrayCollection
		{
			return _chambreDispo;
		}
		
		public static function loadChambreNonDispo():void
		{
			QueryHelper.execute("Select * from chambre c where c.numero not in (select ed.numero from  etre_disponible ed where ed.id_pele =" + index.peleActuel.id_pele+")", provideChambreNonDispo, queryError)
		}
		
		private static function provideChambreNonDispo(evt:SQLEvent):void
		{
			_chambreNonDispo = new ArrayCollection(evt.result.data);
		}
		
		
		public static function get ChambreNonDispo():ArrayCollection
		{
			return _chambreNonDispo;
		}
	}
}