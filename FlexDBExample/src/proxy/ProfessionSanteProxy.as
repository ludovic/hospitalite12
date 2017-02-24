package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	
	public class ProfessionSanteProxy
	{
		private static var _profession:ArrayCollection = new ArrayCollection;
		
		public static function load():void
		{
			QueryHelper.execute("Select id_profession_sante,Profession from profession_sante",provide, queryError);
		}
		
		private static function provide(evt:SQLEvent):void
		{
			_profession = new ArrayCollection(evt.result.data);
		}
		private static function queryError(evt:SQLErrorEvent):void
		{
			Alert.show(evt.error);
		}
		
		public static function get Profession():ArrayCollection
		{
			return _profession;
		}
	}
}