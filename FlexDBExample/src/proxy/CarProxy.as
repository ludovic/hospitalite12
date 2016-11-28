package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	
	public class CarProxy
	{
		private static var _car:ArrayCollection = new ArrayCollection;
		private static var _carDispo:ArrayCollection = new ArrayCollection;
		private static var _carDispoWithGare:ArrayCollection = new ArrayCollection;
		
		public static function loadCarDispo():void
		{
			QueryHelper.execute("Select * from transport where id_transport in (select id_transport from passer_par where id_pele =" + index.peleActuel.id_pele+" and id_transport<>0) ORDER BY  `nom_transport` ASC ", provideCarDispo, queryError)
		}
		
		private static function provideCarDispo(evt:SQLEvent):void
		{
			_carDispo = new ArrayCollection(evt.result.data);
		}
		private static function queryError(evt:SQLErrorEvent):void
		{
			Alert.show(evt.error);
		}
		
		public static function get CarDispo():ArrayCollection
		{
			return _carDispo;
		}
		
		public static function loadCarDispoWithGare():void
		{
			QueryHelper.execute("Select t.id_transport,g.id_gare,g.nom,pp.heure_aller from transport t, passer_par pp, gare g  where t.id_transport = pp.id_transport and g.id_gare = pp.id_gare and t.id_transport<>0 and pp.id_pele =" + index.peleActuel.id_pele, provideCarDispoWithGare, queryError)
		}
		
		private static function provideCarDispoWithGare(evt:SQLEvent):void
		{
			_carDispoWithGare = new ArrayCollection(evt.result.data);
		}
		
		public static function get CarDispoWithGare():ArrayCollection
		{
			return _carDispoWithGare;
		}
	}
}