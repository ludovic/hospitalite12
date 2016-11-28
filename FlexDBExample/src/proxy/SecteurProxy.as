package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;

	public class SecteurProxy
	{
		private static var _secteur:ArrayCollection = new ArrayCollection;
		
		public static function load():void
		{			
			QueryHelper.execute("Select id_secteur,section from secteur",provide, queryError);
		}
		
		private static function provide(evt:SQLEvent):void
		{
			_secteur = new ArrayCollection(evt.result.data);
		}
		private static function queryError(evt:SQLErrorEvent):void
		{
			Alert.show(evt.error);
		}
		
		public static function get Secteur():ArrayCollection
		{
			return _secteur;
		}
		
	}
}