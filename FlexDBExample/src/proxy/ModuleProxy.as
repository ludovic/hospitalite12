package proxy
{	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	
	public class ModuleProxy
	{
		private static var _moduleDispo:ArrayCollection = new ArrayCollection;
		
		private static function queryError(evt:SQLErrorEvent):void
		{
			Alert.show(evt.error);
		}
		
		public static function loadModuleDispo():void
		{
			QueryHelper.execute("Select m.id_module, m.libelle, m.etage from chambre c, etre_disponible ed, module m where c.numero = ed.numero and c.id_module=m.id_module and ed.id_pele =" + index.peleActuel.id_pele+" GROUP BY m.id_module ", provideModuleDispo, queryError)
		}
		
		private static function provideModuleDispo(evt:SQLEvent):void
		{
			_moduleDispo = new ArrayCollection(evt.result.data);
		}
		
		public static function get ModuleDispo():ArrayCollection
		{
			return _moduleDispo;
		}
		
	}
}